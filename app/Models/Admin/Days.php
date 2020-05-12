<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Days extends Model
{
    //
    use SoftDeletes;
    protected $table = 'days';
    protected $primaryKey = 'day_id';
    const CREATED_AT = 'day_created_at';
	const UPDATED_AT = 'day_updated_at';

    public static function get_days(){
        $Days = Days::orderBy('day_id', 'asc')->get();
        return $Days;
    }
    public static function get_days_Location($location_id){
        
        // $Days = Days::leftjoin('location_days','days.day_id','=','location_days.locationday_fk_days_id')
    				// ->select('location_days.locationday_start_time','location_days.locationday_end_time','location_days.locationday_is_open','days.day_name','days.day_start_time','days.day_end_time','days.day_number')
    				// ->where('location_days.locationday_fk_location_id',$location_id)
    				// ->orderBy('location_days.locationday_fk_days_id', 'asc')->get();

                    $DaysClose = Days::leftjoin('location_days','days.day_id','=','location_days.locationday_fk_days_id')
                    ->select('days.day_number', 'days.day_name')
                    ->where('location_days.locationday_fk_location_id',$location_id)
                    ->where('location_days.locationday_is_open',0)
                    ->orderBy('location_days.locationday_fk_days_id', 'asc')->get();
        return $DaysClose;
         // return array($Days,$DaysClose);
    }
    
}