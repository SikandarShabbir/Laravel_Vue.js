<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PhoneNumberFormat;
use App\Models\Admin\BookedTargets;
use App\Models\Admin\Locations;
use App\Models\Admin\PartialPayments;
use DB;
use App\Helpers\CheckSite;

class Payments extends Model
{
    //
    use SoftDeletes;
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    const CREATED_AT = 'payment_created_at';
	const UPDATED_AT = 'payment_updated_at';
    protected $guarded = [];

    public static function add_payment($booking_id,$request){

        $site_id = $request->user()->site_id;
        $payment_total_amount = ($request->location_price_each_position * $request->total_selected_positions)+ $request->total_tax;
        $partially_paid = $request->paid_partial_payment; 
        $remaining_amount = $payment_total_amount - $request->paid_partial_payment;
        // echo $total_payment = $request->total_payment;
        // echo $remaining_payment = $request->remaining_payment;
        // exit();
        $Payments = new Payments;
        $Payments->payment_fk_site_id = $site_id;
        $Payments->payment_fk_booking_id = $booking_id;
        $Payments->payment_card_number = PhoneNumberFormat::storeCardNumberFormat($request->payment_card_number);
        $Payments->payment_expiry_date = PhoneNumberFormat::storeDateFormat($request->payment_expiry_date);
        // if($request->payment_type !='cash'){
        //     $Payments->payment_status = '1';
        // }else{

            $Payments->payment_status = $request->payment_status;
        // }
        
        $Payments->payment_type = $request->payment_type;
        $Payments->payment_total_amount = $payment_total_amount;
        if ($request->payment_status == 8) {
            $Payments->payment_paid = $partially_paid;
            // $Payments->payment_pending = $remaining_amount;
        }else if($request->payment_status == 2){
            $Payments->payment_paid = $payment_total_amount;
        }
        $Payments->save();
        $lastpaymentID = $Payments->payment_id;
        return $lastpaymentID;
    }
    public static function view_finance($request,$locationID,$from_date,$to_date,$tab_value){
        $site_id = $request->user()->site_id;
        $payments = Payments::join('bookings','bookings.booking_id','=','payments.payment_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->select('payments.payment_created_at','bookings.booking_id','bookings.booking_order_id','bookings.booking_email','bookings.booking_first_name','bookings.booking_last_name','bookings.booking_created_at','locations.location_name','locations.location_id','payments.payment_total_amount','payments.payment_id','payments.payment_type','payments.payment_status','payments.payment_created_at',DB::raw('(select sum(payments.payment_paid) from payments where payments.payment_fk_site_id = '.$site_id.' and `payments`.`deleted_at` is null) as total_sales'),DB::raw('(select sum(payments.payment_total_amount) from payments where payments.payment_fk_site_id = '.$site_id.' and `payments`.`deleted_at` is null) as total_balance'),'payments.payment_paid','payments.payment_pending')
            ->where(function($query) use ($locationID,$from_date,$to_date,$tab_value) {
            if($from_date && $to_date){ 
                $query->where('payments.payment_created_at', '>=', $from_date);
                $query->where('payments.payment_created_at', '<=', $to_date);
            }
            if($locationID){
                $query->Where('locations.location_id','=',"{$locationID}");
            }
            if($tab_value == 2){
                $query->Where('payments.payment_status',2);
            }
            if($tab_value == 1){
                $query->Where('payments.payment_status',1);
                $query->orWhere('payments.payment_status',8);
            }
        })
        ->orderBy('payment_created_at')
        ->where('booking_fk_site_id', $site_id)
        ->paginate(5,['bookings.booking_id']);
        return $payments;
    } 
    public static function view_dashboard($request,$from_date,$to_date){

        $site_id = $request->user()->site_id;
        $today = $request->today;

        $unpaid_customer = Payments::join('bookings','bookings.booking_id','=','payments.payment_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
                if($from_date && $to_date){ 
                    $query->where('bookings.booking_target_date', '>=', $from_date);
                    $query->where('bookings.booking_target_date', '<=', $to_date);
                }else{
                    $query->where('bookings.booking_target_date',$today);
                }})
        ->where(function($query)use ($request,$site_id){
            $query->where('location_id',$request->location_id);
            $query->where('payments.payment_fk_site_id', $site_id);
            $query->where('payment_status',1);
        })
        ->orWhere(function($query) use($site_id){
            $query->where('payments.payment_fk_site_id', $site_id);
            $query->where('payment_status',8);        
        })
        ->count();
        //  dd($unpaid_customer->getBindings());
        // return $unpaid_customer;

        $paid_customer = Payments::join('bookings','bookings.booking_id','=','payments.payment_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
            if($from_date && $to_date){ 
                    $query->where('bookings.booking_target_date', '>=', $from_date);
                    $query->where('bookings.booking_target_date', '<=', $to_date);
                }else{
                    $query->where('bookings.booking_target_date',$today);
                }})
        ->where('location_id',$request->location_id)
        ->where('payments.payment_fk_site_id', $site_id)
        ->where('payment_status',2)
        ->count();
        // return $paid_customer;
        $partial_paid_customer = Payments::join('bookings','bookings.booking_id','=','payments.payment_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
            if($from_date && $to_date){ 
                $query->where('bookings.booking_target_date', '>=', $from_date);
                $query->where('bookings.booking_target_date', '<=', $to_date);
            }else{
                $query->where('bookings.booking_target_date',$today);
            }})
        ->where('location_id',$request->location_id)
        ->where('payment_status',8)
        ->where('payments.payment_fk_site_id', $site_id)
        ->count();

        $total_bookings = $unpaid_customer + $paid_customer + $partial_paid_customer;

        $paid_booked_targets = BookedTargets::join('bookings','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->join('payments','payments.payment_fk_booking_id','=','bookings.booking_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
            if($from_date && $to_date){ 
                    $query->where('bookings.booking_target_date', '>=', $from_date);
                    $query->where('bookings.booking_target_date', '<=', $to_date);
                }else{
                    $query->where('bookings.booking_target_date',$today);
                }})
        ->where('locations.location_id',$request->location_id)
        ->where('booked_targets.bookedtarget_fk_site_id', $site_id)
        ->where('payments.payment_status',2)
        ->count();

        $pending_booked_targets = BookedTargets::join('bookings','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->join('payments','payments.payment_fk_booking_id','=','bookings.booking_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
            if($from_date && $to_date){ 
                    $query->where('bookings.booking_target_date', '>=', $from_date);
                    $query->where('bookings.booking_target_date', '<=', $to_date);
                }else{
                    $query->where('bookings.booking_target_date',$today);
                }})
        ->where('locations.location_id',$request->location_id)
        ->where('booked_targets.bookedtarget_fk_site_id', $site_id)
        ->where('payments.payment_status',1)
        ->count();

        $partial_booked_targets = BookedTargets::join('bookings','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
            ->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
            ->join('payments','payments.payment_fk_booking_id','=','bookings.booking_id')
            ->where(function($query) use ($from_date,$to_date,$today) {
            if($from_date && $to_date){ 
                    $query->where('bookings.booking_target_date', '>=', $from_date);
                    $query->where('bookings.booking_target_date', '<=', $to_date);
                }else{
                    $query->where('bookings.booking_target_date',$today);
                }})
        ->where('locations.location_id',$request->location_id)
        ->where('booked_targets.bookedtarget_fk_site_id', $site_id)
        ->where('payments.payment_status',8)
        ->count();
        
        $booked_targets = $paid_booked_targets + $pending_booked_targets + $partial_booked_targets;
        $location_data = Locations::select('location_number_of_target', 'location_total_position')
                        ->where('location_id',$request->location_id)
                        ->where('locations.location_fk_site_id', $site_id)
                        ->first();
        if($location_data !== null){
            $location_no_of_targets = $location_data->location_number_of_target;
            $location_position = $location_data->location_total_position;

            $locationday_time = LocationDays::select('locationday_start_time', 'locationday_end_time')
            ->join('days','locationday_fk_days_id','=','day_number')
            ->where('locationday_fk_location_id',$request->location_id)
            ->where('location_days.locationday_fk_site_id', $site_id)->where('days.day_name', date('D'))->first();

            // return $locationday_end_time;
            if($locationday_time->locationday_end_time != 0 && $locationday_time->locationday_start_time != 0){
                $location_hours = $locationday_time->locationday_end_time - $locationday_time->locationday_start_time + 1;
                $total_location_targets = $location_no_of_targets * $location_position * $location_hours;
                $available_targets = $total_location_targets - $booked_targets;
                $booked_targets_perc = $booked_targets / $total_location_targets * 100;
                $available_targets_perc = $available_targets / $total_location_targets * 100;
            }else{
                $location_hours=  "Closed";
                $total_location_targets= 0;
                $available_targets= 0;
                $booked_targets_perc= 0;
                $available_targets_perc= 0;
            }
        }else{
            $available_targets= 0;
            $booked_targets_perc= 0;
            $available_targets_perc= 0;
        }
        
        $dashboard = [
            'unpaid_customer'=>$unpaid_customer,
            'paid_customer'=>$paid_customer,
            'total_bookings'=>$total_bookings,
            'booked_targets'=>$booked_targets,
            'available_targets'=>$available_targets,
            'booked_targets_perc'=>$booked_targets_perc,
            'available_targets_perc'=>$available_targets_perc,
        ];
        return $dashboard;
    } 
    public static function update_payment($booking_id,$request){
        $site_id = $request->user()->site_id;
        // $payment_total_amount = ($request->location_price_each_position * $request->total_selected_positions)+ $request->total_tax;
        // echo $booking_id." ".$site_id;exit();
        $Payments = Payments::where('payment_fk_booking_id',$booking_id)
                            ->where('payment_fk_site_id',$site_id)->first();                  

        // $Payments->payment_status = $request->payment_status;
        // dd($Payments);
        // $Payments->payment_type = $request->payment_type;
        // $Payments->payment_total_amount = $payment_total_amount;
        if (isset($request->payment_card_number)) {                                
        $Payments->payment_card_number = PhoneNumberFormat::storeCardNumberFormat($request->payment_card_number);
        $Payments->payment_expiry_date = PhoneNumberFormat::storeDateFormat($request->payment_expiry_date);
        $Payments->save();
        $lastpaymentID = $Payments->payment_id;
        return $lastpaymentID;
        }  
        $lastpaymentID = $Payments->payment_id;
        return $lastpaymentID;
    }
    public static function cancel_payment($request,$booking_id){
        $Payments = Payments::where('payment_fk_booking_id',$booking_id)->delete();
        return $Payments;
    }
    public static function add_payment_site($booking_id,$request){

        $site_id = CheckSite::CheckSite();
        $Payments = new Payments;
        $Payments->payment_fk_site_id = $site_id;
        $Payments->payment_fk_booking_id = $booking_id;
        $Payments->payment_card_number = PhoneNumberFormat::storeCardNumberFormat($request->payment_card_number);
        $Payments->payment_expiry_date = PhoneNumberFormat::storeDateFormat($request->payment_expiry_date);
        $Payments->payment_status = $request->payment_status;
        $Payments->payment_type = $request->payment_type;
        if ($request->payment_status == 8) {
        $Payments->payment_paid = $request->paid_partial_payment;
        $Payments->payment_total_amount = $request->payment_total_amount;
        }else if($request->payment_status == 2) {
        $Payments->payment_paid = $request->payment_total_amount;
        $Payments->payment_total_amount = $request->payment_total_amount;
        }else if($request->payment_status == 1){
        $Payments->payment_paid = 0;
        $Payments->payment_total_amount = $request->payment_total_amount;          
        }
        $Payments->save();
        $lastpaymentID = $Payments->payment_id;
        return $lastpaymentID;
    }
    public static function update_partial_payment($request){
        $site_id = $request->user()->site_id;
        $Payments = Payments::where('payment_fk_booking_id',$request->booking_id)
                            ->where('payment_fk_site_id',$site_id)->first();
            $total = $Payments->payment_total_amount;
            $paid = $Payments->payment_paid;
            $pending = $total-$paid;
            // $payments_equal = bccomp($request->paid_partial_payment, $pending, 2) == 0;   
           // var_dump($payments_equal);exit();
         if ($request->payment_status == 2) {
               $Payments->update(['payment_status'=> 2,
                   'payment_paid'=>$Payments->payment_total_amount
               ]);
               if($Payments){
                   PartialPayments::create([
                       'partial_payment_fk_booking_id' => $request->booking_id,
                       'partial_payment_amount_paid' => $Payments->payment_total_amount,
                       'partial_payment_payment_type' => $request->payment_type,
                   ]);
               }
               return $Payments;
           }                
        if($request->paid_partial_payment == $pending && $Payments->payment_status == 8){
            
           $payment_paid = $paid + $request->paid_partial_payment;
           // echo $pending-$request->paid_partial_payment;exit();
            $Payments->update(['payment_status'=> 2,
                              'payment_paid'=> $payment_paid,
                          ]);
            if($Payments){
                PartialPayments::create([
                    'partial_payment_fk_booking_id' => $request->booking_id,
                    'partial_payment_amount_paid' => $request->paid_partial_payment,
                    'partial_payment_payment_type' => $request->payment_type,
                ]);
            }
            return $Payments;
        }
        // if($Payments->payment_status == 8 && $request->paid_partial_payment < $pending){
        if($Payments->payment_status == 8){
           $payment_paid = $paid + $request->paid_partial_payment;

            $Payments->update(['payment_paid'=>$payment_paid]);

            if ($Payments->payment_total_amount == $Payments->payment_paid) {
                $Payments->update(['payment_status' => 2
                               ]);
            }
            if($Payments){
                PartialPayments::create([
                    'partial_payment_fk_booking_id' => $request->booking_id,
                    'partial_payment_amount_paid' => $request->paid_partial_payment,
                    'partial_payment_payment_type' => $request->payment_type,
                ]);
            }
            return $Payments;
        }

    }
}
