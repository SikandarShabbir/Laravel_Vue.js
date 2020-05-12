<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use App\Models\Admin\Payments;
use App\Models\Admin\Locations;

class PaymentsController extends Controller
{
    public function view_finance(Request $request){
    	$date = $request->input('date');        
    	$tab_value = $request->input('tab_value');        
    	$from_date = $date[0];
    	$to_date = $date[1];
        $locationID = $request->input('location_id');
    	$payments = Payments::view_finance($request,$locationID,$from_date,$to_date,$tab_value);
    	if ($payments) {
    		return response()->json(['error'=>false,'payments'=>$payments]);
    	}else {
    		return response()->json(['error'=>true,'message'=>'No payments returend']);
    	}
    }
    public function view_dashboard(Request $request){
        $date = $request->input('date');     
        $from_date = $date[0];
        $to_date = $date[1];
        $dashboard = Payments::view_dashboard($request,$from_date,$to_date);        
        if ($dashboard) {
            return response()->json(['error'=>false,'dashboard'=>$dashboard]);
        }else {
            return response()->json(['error'=>true,'message'=>'No payments returend']);
        }
    }
    public function update_partial_payment(Request $request){
        // $request->validate([
        //             'paid_partial_payment' =>'required:numeric'
        // ]);
        // if($request->payments == 1){
        //     return response()->json(['error'=>false,'message'=>'Paymnet updated successufully']);
        // }
        $site_id = $request->user()->site_id;
        $payment = Payments::where('payment_fk_booking_id',$request->booking_id)
                            ->where('payment_fk_site_id',$site_id)->first();
        $pending = $payment->payment_total_amount - $payment->payment_paid;
        // echo $pending." ".$request->paid_partial_payment; exit();
        // $payments_equal = bccomp($request->paid_partial_payment, $pending, 2) == 0;   
        // var_dump($payments_equal);exit();
        // $compare_floats = bccomp($request->paid_partial_payment, $pending, 2) == 1;                               
        // if ($compare_floats) {
        //     return response()->json(['error'=>false,'message'=>'Please enter correct amount']);        
        // }                  
    	$update_payment = Payments::update_partial_payment($request);
    	if ($update_payment) {
    		return response()->json(['error'=>false,'message'=>'Paymnet updated successufully']);
    	}else {
    		return response()->json(['error'=>true,'message'=>'No payments updated']);
    	}
    }
}
