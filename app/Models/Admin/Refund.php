<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\PhoneNumberFormat;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'refund_id';
    protected $table = 'refund';
    const CREATED_AT = 'refund_created_at';
	const UPDATED_AT = 'refund_updated_at';                    
    protected $dates = ['deleted_at'];


    public static function refund_payment($request,$booking_id, $payment_total_amount){
    	
    	// if($request->total_selected_positions !=''){
    	// 	$refund_total_amount = ($request->location_price_each_position * $request->total_selected_positions)+ $request->total_tax;
    	// }else{
    	// 	$refund_total_amount = ($request->location_price_each_position * $request->totalPositions)+ $request->total_tax;
    	// }


		$refund_total_amount = $payment_total_amount;
    	//$payment_total_amount = ($request->location_price_each_position * $request->total_selected_positions)+ $request->total_tax;
        $site_id = $request->user()->site_id;
        $Refund = new Refund;
        $Refund->refund_fk_site_id = $site_id;
        $Refund->refund_fk_booking_id = $booking_id;
        $Refund->refund_card_number = PhoneNumberFormat::storeCardNumberFormat($request->refund_card_number);
        $Refund->refund_cheque_number = $request->refund_cheque_number;

        if( $request->refund_type !=""){
        	$Refund->refund_type = $request->refund_type;
        }else{
        	$Refund->refund_type = "Cash";
        }
        
        $Refund->refund_total_amount = $refund_total_amount;
        $Refund->save();
        $lastrefundID = $Refund->refund_id;
        return $lastrefundID;
    }
}
