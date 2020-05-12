<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LocationDays extends Model
{
    //
    use SoftDeletes;
    protected $table = 'location_days';
	protected $primaryKey = 'location_days_id';
	
	const CREATED_AT = 'locationday_created_at';
	const UPDATED_AT = 'locationday_updated_at';


	public static function add_locationday($request, $Location){
		$locationday_fk_site_id = Auth::user()->site_id;
		if(!empty($request->startTimes )){
			foreach ($request->startTimes as $timeing) {
				$timeingToexplod = explode(" ",$timeing);
				$locationday_fk_days_id = $timeingToexplod[0];
				$locationday_start_time = $timeingToexplod[1];
				$locationday_end_time	= $timeingToexplod[2];

				$LocationDays = new LocationDays;
				$LocationDays->locationday_fk_site_id = $locationday_fk_site_id;
				$LocationDays->locationday_fk_location_id = $Location;
				if($locationday_start_time && $locationday_end_time !== "close"){
					$LocationDays->locationday_fk_days_id = $locationday_fk_days_id;
					$LocationDays->locationday_start_time = $locationday_start_time;
					$LocationDays->locationday_end_time = $locationday_end_time;
					$LocationDays->locationday_is_open = '1';				
				}else{
					$LocationDays->locationday_fk_days_id = $locationday_fk_days_id;
					$LocationDays->locationday_is_open = '0';
				}
				$LocationDays->save();
				$lastlocationDayID= $LocationDays->location_days_id;
			}
			return $lastlocationDayID;
		}			
	}

	public static function update_locationday($request, $location_id){

		$locationday_fk_site_id = Auth::user()->site_id;
		foreach ($request->startTimes as $timeing) {
		$timeingToexplod = explode(" ",$timeing);
			$locationday_fk_days_id = $timeingToexplod[0];
			$locationday_start_time = $timeingToexplod[1];
			$locationday_end_time	= $timeingToexplod[2];
			
			$LocationDays =  LocationDays::where('locationday_fk_site_id', $locationday_fk_site_id)->where('locationday_fk_location_id',$location_id)->where('locationday_fk_days_id', $locationday_fk_days_id)->first();
				if($locationday_start_time && $locationday_end_time !== "close"){
					$LocationDays->locationday_fk_days_id = $locationday_fk_days_id;
					$LocationDays->locationday_start_time = $locationday_start_time;
					$LocationDays->locationday_end_time = $locationday_end_time;
					$LocationDays->locationday_is_open = '1';				
				}else{
					$LocationDays->locationday_fk_days_id = $locationday_fk_days_id;
					$LocationDays->locationday_is_open = '0';
				}
			$LocationDays->save();
			$lastlocationDayID= $LocationDays->location_days_id;
		}
		
	 	return $lastlocationDayID;
					
	}
}
