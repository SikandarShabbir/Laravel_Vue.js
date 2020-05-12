<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Helpers\CheckSite;
use App\Helpers\PhoneNumberFormat;
use App\Models\Admin\Settings;
use App\Models\Admin\Notifications;
use App\Helpers\MailConfigurations;

class Locations extends Model
{
	use SoftDeletes;
	protected $primaryKey = 'location_id';
	protected $table = 'locations';
	const CREATED_AT = 'location_created_at';
	const UPDATED_AT = 'location_updated_at';

	public static function add_location($request){

		$location_fk_site_id = Auth::user()->site_id;
		$Locations = new Locations;
		$Locations->location_fk_site_id = $location_fk_site_id;
		$Locations->location_name = $request->location_name;
		$Locations->location_address = $request->location_address;
		$Locations->location_country = $request->location_country;
		$Locations->location_number_of_target = $request->location_number_of_target;
		$Locations->location_closed_days = $request->location_closed_days;
		$Locations->location_open_days = $request->location_open_days;
		$Locations->location_operation_hour = $request->location_operation_hour;
		$Locations->location_total_position = $request->location_total_position;
		$Locations->location_price_each_position = $request->location_price_each_position;
		$Locations->location_tax = $request->location_tax;
		$Locations->save();
		$lastlocationID= $Locations->location_id;
		Locations::send_email_notification_to_admin($request,"new_location");
	 	return $lastlocationID;
	}

	public static function send_email_notification_to_admin($request,$editBookingType){
		$site_id = Auth::user()->site_id;
		
		$setting = Settings::get_setting($request);
		if($setting->setting_admin_notification_active == 1){
			$service_name = $setting->setting_service_name;
			$company_name = $setting->setting_company_name;
			$employee_full_name = $setting->setting_employee_first_name." ".$setting->setting_employee_last_name;

			$to_email= $setting->setting_company_email;
			$to_name= $employee_full_name;

			$from_email = 'sales@shopallcars.com';
			$from_name = 'Lumberjaxe';	
			$notification = Notifications::get_notification(12);
			$emailsFile = 'emails.reschedule';
			
			if (isset($notification->notification_subject)) {
			$subj = $notification->notification_subject;
			$msg = $notification->notification_message;
			}else {
				$subj = "New Location";
				$msg = "First Location has been added";
			}

				$vars = array(
				'$admin_name'      			=> "".$to_name."",
				'$employee_full_name'       => "".$employee_full_name."",
				'$service_name'      		=> "".$service_name."", 
				'$location_name'			=> "".$request->location_name."",
				'$location_address'       	=> "".$request->location_address."",
				'$location_country'       	=> "".$request->location_country."",
				'$company_name'       		=> "".$company_name."",
				);
				$msg = strtr($msg, $vars);
				$subj = strtr($subj, $vars);
			$config = MailConfigurations::setMailConfigurations($site_id);
			$data = array("body" => $msg,"name" => $to_name);
			\Mail::send($emailsFile, $data, function($message) use ($to_name, $to_email,$from_email,$from_name, $subj) {
				$message->to($to_email, $to_name)->subject($subj);
				$message->from($from_email, $from_name);
			});

			if (\Mail::failures()) {
				return "error sending Email";
				}else{
				return "Email sent";
				}
		}
	}  
	
	public static function get_locations(){
		$site_id = Auth::user()->site_id;
        $Locations = Locations::select(
         	'locations.location_id',
         	'locations.location_name',
         	'locations.location_address',
         	'locations.location_country',
         	'locations.location_number_of_target',
         	'locations.location_total_position',
         	'location_days.locationday_start_time',
         	'location_days.locationday_end_time',
         	'location_days.locationday_is_open',
         	'days.day_number',
         	'days.day_name'
        )
		->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
		->join('days','location_days.locationday_fk_days_id','=', 'days.day_number')
		->where('location_fk_site_id', $site_id)
		->orderBy('location_id', 'ASC')
		->orderBy('days.day_number', 'ASC')->get();
		foreach ($Locations as $Location) {
		$Location->location_time =	PhoneNumberFormat::convert_time($Location['locationday_start_time'])." - ".PhoneNumberFormat::convert_time($Location['locationday_end_time']);
		}


		$parrent=[];
		$sameDay = [];

		$branches=[];

		$lc=0;
        foreach ($Locations as $key=>$Location) {

if($Location->location_id==$lc){
	array_push($branches[$Location->location_id],$Location);
}else{
	$branches[$Location->location_id]=[];
		array_push($branches[$Location->location_id],$Location);
}
$lc=$Location->location_id;

// echo $key;

        	//print_r($Location);
        //	exit();
   //          if(!array_key_exists($Location['location_id'],$parrent)){ // checking if key not exist in array
   //              $parrent[$Location['location_id']]=[];
   //              $sameDay[$Location['location_id']]=[];
   //          }
   //          $parrent[$Location['location_id']][$key]=$Location;
           
			// print_r($parrent[$Location['location_id']])

    //         if(in_array($Location->location_time, $sameDay[$Location['location_id']])){
    //         	$key = array_search($Location->location_time, $sameDay[$Location['location_id']]);
    //         	$sameDay[$Location['location_id']][$key-1]=  $sameDay[$Location['location_id']][$key-1].','.$Location->day_name;

    //         	$pos = array_search($Location->location_time, $sameDay[$Location['location_id']]);
    //         	$sameDay[$Location['location_id']][$pos-1] =  $sameDay[$Location['location_id']][$key-1];
				// // unset($sameDay[$Location['location_id']][$pos]);
    // //         	array_push($sameDay[$Location['location_id']], (object)[
				// // 	'day_name' => $sameDay[$Location['location_id']][$key-1],
				// // 	'locationday_end_time' => $Location->location_time,
				// // ]);
    //         }else{
    //         	array_push($sameDay[$Location['location_id']],$Location->day_name,$Location->location_time);
    //         }
        }



// $dayTime = [];
// foreach ($branches as $key => $branche) {
// 	foreach ($branche as $subkey => $day) {
// 		if(!empty($dayTime)){
// 			foreach($dayTime as $keyIndex=> $obj) {
// 			    if ($day->location_time == $obj->location_time) {
// 			        $obj->day_name = $obj->day_name.','.$day->day_name;
// 			        break;
// 			    }
// 			  //   else if($keyIndex  sizeof($dayTime)){
// 			  //   	array_push($dayTime, (object)[
// 					// 	'day_name' => $day->day_name,
// 					// 	'location_time' =>$day->location_time,
// 					// ]);
// 			  //   }
// 			}
// 		}else{
// 			array_push($dayTime, (object)[
// 				'day_name' => $day->day_name,
// 				'location_time' =>$day->location_time,
// 				'locationday_is_open' =>$day->locationday_is_open ,
// 				'location_name' => $day->location_name,
// 				'location_address' => $day->location_address,
// 				'location_country' => $day->location_country,
// 				'location_number_of_target' => $day->location_number_of_target,
// 				'location_total_position' => $day->location_total_position,
// 				'day_number' => $day->day_number,
// 			]);
// 		}
// 	}
// }




  //      print_r( $dayTime);
		// exit();
        return $branches;
    }
    public static function get_site_locations(){
		$site_id = Auth::user()->site_id;
        $Locations = Locations::where('location_fk_site_id', $site_id)->orderBy('location_id', 'ASC')->get();
        return $Locations;
    }

    public static function get_location_data($request, $location_id,$date){
		$day =date('D', strtotime($date));
		$date =date('Y-m', strtotime($date));
		$site_id = $request->user()->site_id;

        $Days = Days::leftjoin('location_days','days.day_id','=','location_days.locationday_fk_days_id')
    				->select('location_days.locationday_start_time','location_days.locationday_end_time','location_days.locationday_is_open','days.day_name','days.day_start_time','days.day_end_time','days.day_number')
    				->where('location_days.locationday_fk_location_id',$location_id)
    				->where('location_days.locationday_fk_site_id',$site_id)
    				->orderBy('location_days.locationday_fk_days_id', 'asc')->get();

        $Location = Locations::join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')->where('location_fk_site_id', $site_id)->where('location_id', $location_id)->orderBy('location_id', 'asc')->get();
		
		$LocationTimes = Locations::select(
			DB::raw('min(location_days.locationday_start_time) as locationday_start_time'),
			DB::raw('max(location_days.locationday_end_time) as locationday_end_time'))
		->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
		->join('days','location_days.locationday_fk_days_id','=','days.day_number')
		->where(function($query) use ($day) {
			if($day){
				$query->Where('days.day_name', $day);
			}
		})
		->where('location_days.locationday_is_open','=','1')
		->where('location_fk_site_id', $site_id)
		->where('location_id', $location_id)
		->get();

		
		$total_bookings = Booking::join('locations','bookings.booking_fk_location_id','=','locations.location_id')
			->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
			->select(
				'bookings.booking_target_date',
				'locations.location_number_of_target',
				'locations.location_total_position',
				DB::raw('(select count(bookedtarget_position) from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id) as total_booked_position')
				)
			->where('location_fk_site_id', $site_id)
			->where(DB::raw("DATE_FORMAT(bookings.booking_target_date, '%Y-%m')"), "=", $date)
			->where('location_id', $location_id)
			->get();


			$total_bookings_array=array();
			foreach($total_bookings as $total_booking){
		        if(!in_array($total_booking, $total_bookings_array)){
		        	$total_bookings_array[]=$total_booking;
		        }
			}
		return array($Location,$LocationTimes, $total_bookings_array,$Days);
    }

    public static function update_location($request, $location_id){
		$location_fk_site_id = Auth::user()->site_id;
		$Locations = Locations::where('location_fk_site_id', $location_fk_site_id)->where('location_id',$location_id)->first();
		$Locations->location_number_of_target = $request->location_number_of_target;
		$Locations->location_address = $request->location_address;
		$Locations->location_name = $request->location_name;
		$Locations->location_total_position = $request->no_of_positions;
		$Locations->location_price_each_position = $request->price_of_target;
		$Locations->location_tax = $request->sales_tax;
		$Locations->location_country = $request->country;

		$Locations->save();
		$lastlocationID= $Locations->location_id;
	 	return $lastlocationID;
	}

	public static function get_locations_site($request){
		$site_id = CheckSite::CheckSite();
        $Locations = Locations::where('location_fk_site_id', $site_id)->orderBy('location_id', 'ASC')->get();
        return $Locations;
    }
        public static function get_location_data_site($request, $location_id,$date){
		$day =date('D', strtotime($date));
		$date =date('Y-m', strtotime($date));
		$site_id = CheckSite::CheckSite();
		// echo $location_id;
		// echo $site_id;

        $Days = Days::join('location_days','days.day_id','=','location_days.locationday_fk_days_id')
    				->select('location_days.locationday_start_time','location_days.locationday_end_time','location_days.locationday_is_open','days.day_name','days.day_start_time','days.day_end_time','days.day_number')
    				->where('location_days.locationday_fk_location_id',$location_id)
    				->where('location_days.locationday_fk_site_id',$site_id)
    				->orderBy('location_days.locationday_fk_days_id', 'asc')->get();

        $Location = Locations::join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')->where('location_fk_site_id', $site_id)->where('location_id', $location_id)->orderBy('location_id', 'asc')->get();
		
		$LocationTimes = Locations::select(
			DB::raw('min(location_days.locationday_start_time) as locationday_start_time'),
			DB::raw('max(location_days.locationday_end_time) as locationday_end_time'))
		->join('location_days','locations.location_id','=','location_days.locationday_fk_location_id')
		->join('days','location_days.locationday_fk_days_id','=','days.day_number')
		->where(function($query) use ($day) {
			if($day){
				$query->Where('days.day_name', $day);
			}
		})
		->where('location_days.locationday_is_open','=','1')
		->where('location_fk_site_id', $site_id)
		->where('location_id', $location_id)
		->get();

		
		$total_bookings = Booking::join('locations','bookings.booking_fk_location_id','=','locations.location_id')
			->join('booked_targets','bookings.booking_id','=','booked_targets.bookedtarget_fk_booking_id')
			->select(
				'bookings.booking_target_date',
				'locations.location_number_of_target',
				'locations.location_total_position',
				DB::raw('(select count(bookedtarget_position) from booked_targets where bookedtarget_fk_booking_id=bookings.booking_id) as total_booked_position')
				)
			->where('location_fk_site_id', $site_id)
			->where(DB::raw("DATE_FORMAT(bookings.booking_target_date, '%Y-%m')"), "=", $date)
			->where('location_id', $location_id)
			->get();


			$total_bookings_array=array();
			foreach($total_bookings as $total_booking){
		        if(!in_array($total_booking, $total_bookings_array)){
		        	$total_bookings_array[]=$total_booking;
		        }
			}
			
		return array($Location,$LocationTimes, $total_bookings_array,$Days);
    }
}


								