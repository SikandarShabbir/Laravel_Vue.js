<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Admin\Locations;
use App\Models\Admin\Payments;
use App\Models\Admin\Refund;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\BookedTargets;
use App\Models\Admin\InviteFriend;
use App\Helpers\PhoneNumberFormat;
use Carbon\Carbon;
use App\Helpers\CheckSite;
use App\Helpers\MailConfigurations;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Booking extends Model{
    use SoftDeletes;
    protected $primaryKey = 'booking_id';
    protected $table = 'bookings';
    const CREATED_AT = 'booking_created_at';
	const UPDATED_AT = 'booking_updated_at';                    
    protected $dates = ['deleted_at']; 

	public static function view_bookings($request,$search,$from_date,$to_date,$locationID,$target,$status,$today){
    	$site_id = $request->user()->site_id;
		$bookings = Booking::join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
			->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
			->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
			->select('bookings.booking_id','bookings.booking_order_id','bookings.booking_email','bookings.booking_first_name','bookings.booking_last_name','bookings.booking_target_date','bookings.booking_created_at','locations.location_name','payments.payment_total_amount','payments.payment_paid','payments.payment_pending','payments.payment_id','payments.payment_status', 
				'bookings.booking_target_start_time as first_time','bookings.booking_target_end_time as last_time',
				DB::raw('(select count(distinct bookedtarget_time) from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id limit 1) as duration'),
				DB::raw('(select count(*) from payments where (payments.payment_status = 2) and `payments`.`deleted_at` is null limit 1) as paid_bookings'),
				DB::raw('(select count(*) from payments where (payments.payment_status = 1 OR payments.payment_status = 8) and `payments`.`deleted_at` is null limit 1) as unpaid_bookings'))
			->where(function($query) use ($search,$from_date,$to_date,$target,$locationID,$status,$today) {
	    	if($from_date && $to_date){	
	    		$query->where('bookings.booking_target_date', '>=', $from_date);
	    		$query->where('bookings.booking_target_date', '<=', $to_date);
	    	}
	    	else if($today){
	    		$query->Where('bookings.booking_target_date','=',"{$today}");
	    	}
	    	if($locationID){
	    		$query->Where('locations.location_id','=',"{$locationID}");
	    	}
	    	if($target){
	    		$query->Where('booked_targets.booked_target_number','=',"{$target}");
	    	}
	    	if($status){
	    		$query->Where('payments.payment_status','=',"{$status}");
	    	}
	    	if($search){
				$query->where(function($query) use ($search){
					$query->Where('bookings.booking_first_name','LIKE',"%{$search}%");
					$query->orWhere('bookings.booking_last_name','LIKE',"%{$search}%");
					$query->orWhere('bookings.booking_email','LIKE',"%{$search}%");
					$query->orWhere('locations.location_name','LIKE',"%{$search}%");
					$query->orWhere('booked_targets.booked_target_number','LIKE',"%{$search}%");
					$query->orWhere('booked_targets.bookedtarget_position','LIKE',"%{$search}%");
	    		});
	    	}
    	})
		->orderBy('booking_target_date')
		->where('booking_fk_site_id', $site_id)
		->distinct()
		->getQuery()
		// ->toSql();
		// return $bookings;
		->paginate(5,['bookings.booking_id']);
	 	foreach ($bookings as $booking) {
	 		if ($booking->payment_status == 1) {
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Pending";
	 		}
	 		else if ($booking->payment_status == 2) {
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Approved";
	 		}
	 		else{
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Reserved";
	 		}
	 			$booking->payment_status = $payment_obj;
	 	}
	 	foreach ($bookings as $booking) {	 	 	
	 		$targets = BookedTargets::select('booked_target_number','bookedtarget_position')->Where('bookedtarget_fk_booking_id','=',$booking->booking_id)->get();
	 		$booking->targets_positions = $targets;
	 		$counter = count($targets);
	 		$targets_and_positions = array();
	 		$targets_positions_new = array();
	 		for($i = 0; $i < $counter; $i++){ 
	 			if($i==0){
	 				$target = $targets[$i]->booked_target_number;
	 				$position = $targets[$i]->bookedtarget_position;
	 				$targets_and_positions['target'] = $target; 
	 				$targets_and_positions['position'] = $position;
	 				$targets_positions_new[] = $targets_and_positions;

	 			}else{
	 				$key = array_search($targets[$i]->booked_target_number, array_column($targets_positions_new, 'target'));
		 			if ($key > -1)
		 			{	
		 				$pos = $targets_positions_new[$key]['position'].",".$targets[$i]->bookedtarget_position;
		 				$targets_positions_new[$key]['position'] = $pos;
		 			}else{
		 				$target = $targets[$i]->booked_target_number;
		 				$position = $targets[$i]->bookedtarget_position;
		 				$targets_and_positions['target'] = $target; 
		 				$targets_and_positions['position'] = $position;
		 				$targets_positions_new[] = $targets_and_positions;
		 			}
	 			}
	 		}
	 		$booking->targets_positions_new = $targets_positions_new;
	 	}


	 	return $bookings;
  	}
	public static function view_todays_bookings($request,$locationID,$today){
    	$site_id = $request->user()->site_id;
		$bookings = Booking::join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
			->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
			->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
			->select('bookings.booking_id','bookings.booking_email','bookings.booking_first_name','bookings.booking_last_name','bookings.booking_target_date','bookings.booking_created_at','locations.location_name','payments.payment_total_amount','payments.payment_paid','payments.payment_pending','payments.payment_id','payments.payment_status', DB::raw('(select bookedtarget_time from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id order by bookedtarget_time asc limit 1) as first_time'), 
				DB::raw('(select bookedtarget_time from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id order by bookedtarget_time desc limit 1) as last_time'),DB::raw('(select count(distinct bookedtarget_time) from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id limit 1) as duration'))
			->where(function($query) use ($locationID,$today) {
	    	if($today){
	    		$query->Where('bookings.booking_target_date','=',"{$today}");
	    	}
	    	if($locationID){
	    		$query->Where('locations.location_id','=',"{$locationID}");
	    	}
    	})
		->orderBy('booking_target_date')
		->where('booking_fk_site_id', $site_id)
		->distinct()
		->getQuery()
		->paginate(5,['bookings.booking_id']);
	 	foreach ($bookings as $booking) {
	 		if ($booking->payment_status == 1) {
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Pending";
	 		}
	 		else if ($booking->payment_status == 2) {
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Approved";
	 		}
	 		else{
	 			$payment_obj = new Payments;
	 			$payment_obj->id = $booking->payment_status;
	 			$payment_obj->title = "Reserved";
	 		}
	 			$booking->payment_status = $payment_obj;
	 	} 
	 	foreach ($bookings as $booking) {	 	 	
	 	 	$targets = BookedTargets::select('booked_target_number','bookedtarget_position','bookedtarget_time')->Where('bookedtarget_fk_booking_id','=',$booking->booking_id)->get();
	 	 	
	 	 	$booking->targets_positions = $targets;
	 		$counter = count($targets);
	 		$targets_and_positions = array();
	 		$targets_positions_new = array();
	 		for($i = 0; $i < $counter; $i++){ 
	 			if($i==0){
	 				$target = $targets[$i]->booked_target_number;
	 				$position = $targets[$i]->bookedtarget_position;
	 				$targets_and_positions['target'] = $target; 
	 				$targets_and_positions['position'] = $position;
	 				$targets_positions_new[] = $targets_and_positions;

	 			}else{
	 				$key = array_search($targets[$i]->booked_target_number, array_column($targets_positions_new, 'target'));
		 			if ($key > -1)
		 			{	
		 				$pos = $targets_positions_new[$key]['position'].",".$targets[$i]->bookedtarget_position;
		 				$targets_positions_new[$key]['position'] = $pos;
		 			}else{
		 				$target = $targets[$i]->booked_target_number;
		 				$position = $targets[$i]->bookedtarget_position;
		 				$targets_and_positions['target'] = $target; 
		 				$targets_and_positions['position'] = $position;
		 				$targets_positions_new[] = $targets_and_positions;
		 			}
	 			}
	 		}
	 		$booking->targets_positions_new = $targets_positions_new;
	 	 	// $booking->targets_positions = $targets;
	 	}
    	return $bookings;
  	}
    public static function view_targets($request,$location,$date,$timing,$target){
		$site_id =  $request->user()->site_id;
		$date =date('Y-m-d', strtotime($date));
		$day =date('D', strtotime($date));

		$selectedDay = Locations::select('location_days.locationday_is_open')
			->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
			->join('days','location_days.locationday_fk_days_id','=','days.day_number')
			->where('location_fk_site_id', $site_id)
			->where('location_id', $location)
			->where('days.day_name', $day)
		->first();

		$bookings = Locations::select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time', 'bookings.booking_id')
		->leftjoin('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
		->join('bookings','locations.location_id','=','bookings.booking_fk_location_id')
		->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
		->join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
		->where(function($query) use ($location,$date,$timing,$target) {
				if($date == null && $timing == null && $target == null )
				{
					$query->select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time');
				}
				if($location){
					$query->Where('locations.location_id','=',$location);
				}
				if($target != "All"){
					$query->Where('booked_targets.booked_target_number','LIKE',"%{$target}%");
				}
				if($date){
					$query->Where( 'bookings.booking_target_date','=',"{$date}");
				}
				if($timing){
					$query->Where( 'booked_targets.bookedtarget_time','=',$timing);
				}
    		})
		->where('location_fk_site_id', $site_id)
		->orderBy('bookings.booking_target_date')
    	->get();
		return array($bookings,$selectedDay);
    }
    public static function add_booking($request){
    	$site_id = $request->user()->site_id;
		$Booking = new Booking;
		$Booking->booking_fk_site_id = $site_id;
		$Booking->booking_fk_location_id = $request->location_id;
		$Booking->booking_order_id =PhoneNumberFormat::storeOrderIDFormat(time());
		$Booking->booking_first_name = $request->booking_first_name;
		$Booking->booking_last_name = $request->booking_last_name;
		$Booking->booking_phone = PhoneNumberFormat::storePhoneNumberFormat($request->booking_phone);
		$Booking->booking_email = $request->booking_email;
		$Booking->booking_target_start_time = $request->booking_target_start_time;
		$Booking->booking_target_end_time = $request->booking_target_end_time;
		$Booking->booking_target_date  =date('Y-m-d', strtotime($request->date));
		$Booking->save();
		$booking_id = $Booking->booking_id;
		$booking_order_id = PhoneNumberFormat::setOrderIDFormat($Booking->booking_order_id);

		foreach ($request->checkedPositions as $checkedPosition) {
			$exploded_checkedPosition= explode(',', $checkedPosition);
			foreach ($exploded_checkedPosition as $checkPosition) {
				$exploded_Position=  explode(' ', $checkPosition);
				$BookedTargets = new BookedTargets;
				$BookedTargets->bookedtarget_fk_site_id = $site_id;
				$BookedTargets->bookedtarget_fk_booking_id = $Booking->booking_id;
				$BookedTargets->booked_target_number = $exploded_Position[0];
				$BookedTargets->bookedtarget_position = $exploded_Position[1];
				$BookedTargets->bookedtarget_time = $exploded_Position[2];
				$BookedTargets->save();
    		}
    	}

    	return array($booking_id,$booking_order_id);
	}
  	public static function edit_bookings($request,$booking_id){
    	$site_id = $request->user()->site_id;
    	//Booking by Booking ID
		$editBooking = Booking::select( 
			'bookings.booking_id',
			'bookings.booking_email',
			'bookings.booking_first_name',
			'bookings.booking_last_name',
			'bookings.booking_target_date',
			'bookings.booking_created_at',
			'bookings.booking_phone',
			'bookings.booking_order_id',
			'locations.location_name',
			'payments.payment_total_amount',
			'payments.payment_status',
			'payments.payment_type',
			'payments.payment_card_number',
			'payments.payment_expiry_date',
			'booked_targets.bookedtarget_time',
			'bookings.booking_fk_location_id',
			'payments.payment_paid',
			'payments.payment_pending',
			'bookings.booking_target_start_time as first_time','bookings.booking_target_end_time as last_time',
			DB::raw('(select count(distinct bookedtarget_time) from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id limit 1) as duration'),
			DB::raw('(SELECT COUNT(booked_targets.bookedtarget_position) FROM booked_targets where booked_targets.bookedtarget_fk_booking_id = '.$booking_id.') as totalPositions')
		)
		->join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
		->join('locations','bookings.booking_fk_location_id','=','locations.location_id')
		->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
		->where('bookings.booking_id', $booking_id)->where('bookings.booking_fk_site_id', $site_id)->first();

		$targets = BookedTargets::select('booked_target_number','bookedtarget_position','bookedtarget_time')->Where('bookedtarget_fk_booking_id','=',$booking_id)->get();
	 	$editBooking->targets_positions = $targets;
	 		$counter = count($targets);
	 		$targets_and_positions = array();
	 		$targets_positions_new = array();
	 		for($i = 0; $i < $counter; $i++){ 
	 			if($i==0){
	 				$target = $targets[$i]->booked_target_number;
	 				$position = $targets[$i]->bookedtarget_position;
	 				$targets_and_positions['target'] = $target; 
	 				$targets_and_positions['position'] = $position;
	 				$targets_positions_new[] = $targets_and_positions;

	 			}else{
	 				$key = array_search($targets[$i]->booked_target_number, array_column($targets_positions_new, 'target'));
		 			if ($key > -1){	
		 				$pos = $targets_positions_new[$key]['position'].",".$targets[$i]->bookedtarget_position;
		 				$targets_positions_new[$key]['position'] = $pos;
		 			}else{
		 				$target = $targets[$i]->booked_target_number;
		 				$position = $targets[$i]->bookedtarget_position;
		 				$targets_and_positions['target'] = $target; 
		 				$targets_and_positions['position'] = $position;
		 				$targets_positions_new[] = $targets_and_positions;
		 			}
	 			}
	 		}
	 		$editBooking->targets_positions_new = $targets_positions_new;
	 		$editBooking->booking_order_id =  PhoneNumberFormat::setOrderIDFormat($editBooking->booking_order_id);
	 	return $editBooking;
  	}
  	public static function update_booking($request, $booking_id){
		$site_id = $user = $request->user()->site_id;
		$Booking = Booking::where('booking_id', $booking_id)->first();
		$Booking->booking_first_name = $request->booking_first_name;
		$Booking->booking_last_name = $request->booking_last_name;
		$Booking->booking_phone = PhoneNumberFormat::storePhoneNumberFormat($request->booking_phone);
		$Booking->booking_email = $request->booking_email;
		$Booking->booking_target_start_time = $request->booking_target_start_time;
		$Booking->booking_target_end_time = $request->booking_target_end_time;
		$Booking->booking_target_date  =date('Y-m-d', strtotime($request->date));
		$Booking->save();
		$LastBooking_id = $Booking->booking_id;

		$BookedTargetsDelete = BookedTargets::Where('bookedtarget_fk_booking_id', '=', $Booking->booking_id)->forcedelete();

		foreach ($request->Selected as $checkedPosition) {
			$exploded_checkedPosition= explode(',', $checkedPosition);
			$exploded_Position=  explode(' ', $exploded_checkedPosition[0]);
			$BookedTargets = new BookedTargets;
			$BookedTargets->bookedtarget_fk_site_id = $site_id;
			$BookedTargets->bookedtarget_fk_booking_id = $Booking->booking_id;
			$BookedTargets->booked_target_number = $exploded_Position[1];
			$BookedTargets->bookedtarget_position = $exploded_Position[2];
			$BookedTargets->bookedtarget_time = $exploded_Position[3];
			$BookedTargets->save();
    	}

		$payment_total_amount = ($request->location_price_each_position * $request->total_selected_positions)+ $request->total_tax;      
        $payments_data = Payments::where('payment_fk_booking_id',$booking_id)
                        ->where('payment_fk_site_id',$site_id)
                        ->first();

        if ($request->payment_status == 2) {
            $payments_data->update([
				'payment_total_amount'=>$payment_total_amount,
				'payment_paid'=> $payment_total_amount,
				'payment_status' => 2,
				'payment_type'=>$request->payment_type,
			]);
			return $payments_data;  	
        } 
        if ($request->payment_status == 1) {
            $payments_data->update([
				'payment_total_amount'=>$payment_total_amount,
				'payment_paid'=> 0,
				'payment_type'=>$request->payment_type,
			]);
			return $payments_data;  	
        }                
        if($payments_data->payment_status != 8 && $request->previous_amount > $payment_total_amount){
			$refund_amount = $payments_data->payment_total_amount - $payment_total_amount;
			$payments_data->update([
				'payment_total_amount'=>$payment_total_amount,
				'payment_paid'=>$payment_total_amount,
				'payment_type'=>$request->payment_type,
				'payment_status' => 2,

			]);
			$refund_payment = Refund::refund_payment($request,$Booking->booking_id,$refund_amount);
			return $refund_payment;
		}
		if($payments_data->payment_status != 8 && $request->previous_amount < $payment_total_amount){
			if($request->paid_partial_payment){
			$extra_amount = $payments_data->payment_paid + $request->paid_partial_payment;
				$payments_data->update([
				'payment_total_amount'=>$payment_total_amount,
				'payment_paid'=>$extra_amount,
				'payment_type'=>$request->payment_type,
			]);
			}else{
			$payments_data->update([
				'payment_total_amount'=>$payment_total_amount,
				'payment_paid'=>$payment_total_amount,
				'payment_type'=>$request->payment_type,

			]);
			}
			if ($payments_data->payment_total_amount > $payments_data->payment_paid) {
				$payments_data->update([
					'payment_status' => 8
	        ]);
			}
			if($payments_data->payment_total_amount == $payments_data->payment_paid) {
				$payments_data->update(['payment_status' => 2
	        ]);
			}
			return $payments_data;
		}		
		if($payments_data->payment_status == 8 && $request->payment_paid > $payment_total_amount){
			$refund_amount = $payments_data->payment_paid - $payment_total_amount;
			$payments_data->update(['payment_paid' => $payment_total_amount,
									'payment_total_amount' => $payment_total_amount,
									'payment_status' => 2,
									'payment_type'=>$request->payment_type,
		]);
			$refund_payment = Refund::refund_payment($request,$Booking->booking_id,$refund_amount);
			return $refund_payment;
		}			
		if($payments_data->payment_status == 8 && $request->payment_paid < $payment_total_amount){
			if($request->paid_partial_payment){
			$payment_paid = $payments_data->payment_paid + $request->paid_partial_payment;
			$payments_data->update(['payment_total_amount'=>$payment_total_amount,
									'payment_paid' => $payment_paid,
									'payment_type'=>$request->payment_type,
		                           ]);
			}else{				
			$payments_data->update(['payment_total_amount'=>$payment_total_amount,
								'payment_paid' => $payment_total_amount,
								'payment_type'=>$request->payment_type,
	                           ]);
			}
			if ($payments_data->payment_total_amount == $payments_data->payment_paid) {
				$payments_data->update(['payment_status' => 2
	                           ]);
			}
			return $payments_data;
		}


	 	return $LastBooking_id;
	}
	public static function view_targetsEdit($request,$location,$date,$timing,$booking_id){
		$site_id =  $request->user()->site_id;
		$date =date('Y-m-d', strtotime($date));
		$day =date('D', strtotime($date));
		$selectedDay = Locations::select('location_days.locationday_is_open')
			->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
			->join('days','location_days.locationday_fk_days_id','=','days.day_number')
			->where('location_fk_site_id', $site_id)
			->where('location_id', $location)
			->where('days.day_name', $day)
		->first();

		// Alreadybooked By Id
		$editBookingById = Booking::select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time', 'bookings.booking_id')
		->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
		->join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
		->where(function($query) use ($date,$timing) {
				if($date){
					$query->Where( 'bookings.booking_target_date','=',"{$date}");
				}
    		})
		->where('booking_fk_site_id', $site_id)
		->where('bookings.booking_id', $booking_id)
		->orderBy('bookings.booking_target_date')
    	->get();
    	// Alreadybooked By Id
    	// Alreadybooked
    	$alreadybooked = Booking::select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time', 'bookings.booking_id', 'bookings.booking_target_date')
		->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
		->join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
		->where(function($query) use ($date,$timing) {
				if($date){
					$query->Where( 'bookings.booking_target_date','=',"{$date}");
				}
				if($timing){
					$query->Where( 'booked_targets.bookedtarget_time','=',$timing);
				}
    		})
		->where('booking_fk_site_id', $site_id)
		->where('bookings.booking_id','!=', $booking_id)
		->orderBy('bookings.booking_target_date')
    	->get();
    	// Alreadybooked
		return array($editBookingById,$alreadybooked,$selectedDay);
    }

	public static function cancel_booking($request,$booking_id){
		$cancel_booking = Booking::where('booking_id', $booking_id)->delete();
		if($cancel_booking){
			$cancel_bookingTarget = BookedTargets::Where('bookedtarget_fk_booking_id', '=', $booking_id)->forcedelete();	
		}
		return $cancel_bookingTarget;
	}
	public static function invite_friend($request){
		$validatedData = $request->validate([
            'invitefriend_email' => 'required',
            'invitefriend_first_name' => 'required',
          //  'invitefriend_last_name' => 'required',
            'email' => 'required',
        ]);
        
			$site_id =  $request->user()->site_id;
			$config = MailConfigurations::setMailConfigurations($site_id);
    	$customer_name = $request->booking_first_name." ".$request->booking_last_name;
    	$customer_email = $request->email;
    	$customer_location = $request->location_name;
    	$customer_booking_date = $request->date;
    	$customer_booking_start_time = $request->start_time;
    	$customer_booking_end_time = $request->end_time;
		$from_email = 'sales@shopallcars.com';
		$from_name = $customer_name;
		$emailsFile = 'emails.cancel_booking';
    	$total_friends = count($request->invitefriend_email);

    	for($i = 0; $i<$total_friends; $i++){
    		$friend_email = $request->invitefriend_email[$i];
    		$friend_first_name = $request->invitefriend_first_name[$i];
    		//$friend_last_name = $request->invitefriend_last_name[$i];
    		$friend_name = $friend_first_name;
				$subj = "Invitation form ".$from_name;
				if($request->checkedPositionWithTarget !== null){
					$request->targets_positions = $request->checkedPositionWithTarget;
				}
				for($t = 0; $t<sizeof($request->targets_positions); $t++){
					$tagets_booked[$t] = $request->targets_positions[$t]['target'];
				}
				$targets_booked = implode(',',$tagets_booked);
    		$msg = "Dear ".$friend_name."<br/> I have booked targets ".$targets_booked." on ".$request->date.". I'm waiting for you at ".$customer_location.", ".$request->location_address." on ".$customer_booking_date." from ".PhoneNumberFormat::convert_time($customer_booking_start_time)." to ".PhoneNumberFormat::convert_time($customer_booking_end_time)." Thanks. <br/> ".$customer_name ;
    		$data = array("body" => $msg,"name" => $friend_email);
    		\Mail::send($emailsFile, $data, function($message) use ($friend_name, $friend_email,$from_email,$from_name, $subj) {
    			$message->to($friend_email, $friend_name)->subject($subj);
    			$message->from($from_email, $from_name);
    		});
    		if (\Mail::failures()) {
    			$email_notification = "error sending Email";
    		}
    		else{
    			$invite_friend = new InviteFriend;    		
    			$invite_friend->invitefriend_fk_site_id = $site_id;
    			$invite_friend->invitefriend_fk_booking_email = $customer_email;
    			$invite_friend->invitefriend_first_name = $friend_first_name;
    		//	$invite_friend->invitefriend_last_name = $friend_last_name;
    			$invite_friend->invitefriend_email = $friend_email;
    			$invite_friend->save();
    			$email_notification = "Email sent successfully";
    		}

    	}
    	return $email_notification;
	}
	public static function add_booking_site($request){
		$site_id = CheckSite::CheckSite();
		$Booking = new Booking;
		$Booking->booking_fk_site_id = $site_id;
		$Booking->booking_order_id =PhoneNumberFormat::storeOrderIDFormat(time());
		$Booking->booking_fk_location_id = $request->location_id;
		$Booking->booking_first_name = $request->booking_first_name;
		$Booking->booking_last_name = $request->booking_last_name;
		$Booking->booking_phone = PhoneNumberFormat::storePhoneNumberFormat($request->booking_phone);
		$Booking->booking_email = $request->booking_email;
		$Booking->booking_target_start_time = $request->booking_target_start_time;
		$Booking->booking_target_end_time = $request->booking_target_end_time;
		$Booking->booking_target_date  =date('Y-m-d', strtotime($request->date));
		$Booking->save();
		$booking_id = $Booking->booking_id;
		$booking_order_id = PhoneNumberFormat::setOrderIDFormat($Booking->booking_order_id);
		
		foreach ($request->checkedPositions as $checkedPosition) {
			$exploded_checkedPosition= explode(',', $checkedPosition);
			foreach ($exploded_checkedPosition as $checkPosition) {
				$exploded_Position=  explode(' ', $checkPosition);
				$BookedTargets = new BookedTargets;
				$BookedTargets->bookedtarget_fk_site_id = $site_id;
				$BookedTargets->bookedtarget_fk_booking_id = $Booking->booking_id;
				$BookedTargets->booked_target_number = $exploded_Position[0];
				$BookedTargets->bookedtarget_position = $exploded_Position[1];
				$BookedTargets->bookedtarget_time = $exploded_Position[2];
				$BookedTargets->save();
    		}
    	}
	 	return array($booking_id,$booking_order_id);
	}
	public static function invite_friend_site($request){
		$validatedData = $request->validate([
            'invitefriend_email' => 'required',
            'invitefriend_first_name' => 'required',
           // 'invitefriend_last_name' => 'required',
            'booking_email' => 'required',
        ]);
		$site_id = CheckSite::CheckSite();
		$config = MailConfigurations::setMailConfigurations($site_id);
    	$customer_name = $request->booking_first_name." ".$request->booking_last_name;
    	$customer_email = $request->booking_email;
    	$customer_location = $request->location_name;
			$customer_booking_date = $request->date;
			$customer_booking_start_time = $request->start_time;
			$customer_booking_end_time = $request->end_time;
		$from_email = 'sales@shopallcars.com';
		$from_name = 'Lumberjaxe';
		$emailsFile = 'emails.cancel_booking';
    	$total_friends = count($request->invitefriend_email);
    	for($i = 0; $i<$total_friends; $i++){
    		$friend_email = $request->invitefriend_email[$i];
    		$friend_first_name = $request->invitefriend_first_name[$i];
    		// $friend_last_name = $request->invitefriend_last_name[$i];
    		$friend_name = $friend_first_name;
				$subj = "Invitation form ".$from_name;
    		for($t = 0; $t<sizeof($request->checkedPositionWithTarget); $t++){
					$tagets_booked[$t] = $request->checkedPositionWithTarget[$t]['target'];
				}
				$targets_booked = implode(',',$tagets_booked);
    		$msg = "Dear ".$friend_name."<br/> I have booked targets ".$targets_booked." on ".$request->date.". I'm waiting for you at ".$customer_location.", ".$request->location_address." on ".$customer_booking_date." from ".PhoneNumberFormat::convert_time($customer_booking_start_time)." to ".PhoneNumberFormat::convert_time($customer_booking_end_time)." Thanks. <br/> ".$customer_name ;
    		$data = array("body" => $msg,"name" => $friend_email);
    		\Mail::send($emailsFile, $data, function($message) use ($friend_name, $friend_email,$from_email,$from_name, $subj) {
    			$message->to($friend_email, $friend_name)->subject($subj);
    			$message->from($from_email, $from_name);
    		});
    		if (\Mail::failures()) {
    			$email_notification = "error sending Email";
    		}
    		else{
    			$invite_friend = new InviteFriend;    		
    			$invite_friend->invitefriend_fk_site_id = $site_id;
    			$invite_friend->invitefriend_fk_booking_email = $customer_email;
    			$invite_friend->invitefriend_first_name = $friend_first_name;
    			//$invite_friend->invitefriend_last_name = $friend_last_name;
    			$invite_friend->invitefriend_email = $friend_email;
    			$invite_friend->save();
    			$email_notification = "Email sent successfully";
    		}
    	}
    	return $email_notification;
	}
	public static function view_targets_site($request,$location,$date,$timing,$target){
		$site_id = CheckSite::CheckSite();
		$date =date('Y-m-d', strtotime($date));
		$day =date('D', strtotime($date));
		$selectedDay = Locations::select('location_days.locationday_is_open')
			->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
			->join('days','location_days.locationday_fk_days_id','=','days.day_number')
			->where('location_fk_site_id', $site_id)
			->where('location_id', $location)
			->where('days.day_name', $day)
		->first();
		$bookings = Locations::select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time', 'bookings.booking_id')
		->leftjoin('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
		->join('bookings','locations.location_id','=','bookings.booking_fk_location_id')
		->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
		->join('payments','bookings.booking_id','=','payments.payment_fk_booking_id')
		->where(function($query) use ($location,$date,$timing,$target) {
				if($date == null && $timing == null && $target == null )
				{
					$query->select('booked_targets.booked_target_number','booked_targets.bookedtarget_position','payments.payment_status','bookings.booking_first_name','booked_targets.bookedtarget_time');
				}
				if($location){
					$query->Where('locations.location_id','=',$location);
				}
				if($target != "All"){
					$query->Where('booked_targets.booked_target_number','LIKE',"%{$target}%");
				}
				if($date){
					$query->Where( 'bookings.booking_target_date','=',"{$date}");
				}
				if($timing){
					$query->Where( 'booked_targets.bookedtarget_time','=',$timing);
				}
    		})
		->where('location_fk_site_id', $site_id)
		->orderBy('bookings.booking_target_date')
    	->get();
		return array($bookings,$selectedDay);
    }
}