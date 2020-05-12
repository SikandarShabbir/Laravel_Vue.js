<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Locations;
use App\Models\Admin\Settings;
use App\Models\Admin\Days;
use App\Models\Admin\LocationDays;
use Illuminate\Support\Facades\Auth;
use Lang;
use App;
class SettingsController extends Controller
{
    //
    public $successStatus = 200;
    public $unAuthorizedStatus = 401;
    public $unprocessableStatus = 422;

    public function add_setting(Request $request){
        $rules = [
            'setting_primary_color' => 'required',
            'setting_secondary_color' => 'required',
            'setting_text_color' => 'required',
            'setting_text_bg_color' => 'required',
            'setting_background' => 'required',
            'setting_font' => 'required',
            'setting_language' => 'required',
        ];
        $customMessages = [
            'setting_primary_color.required' => 'Primary Color is required!',
            'setting_secondary_color.required' => 'Secondary Color is required!',
            'setting_text_color.required' => 'Text Color is required!',
            'setting_text_bg_color.required' => 'Text Color on Background is required!',
            'setting_background.required' => 'Background Color is required!',
            'setting_font.required' => 'Font is required!',
            'setting_language.required' => 'Please select language!',
        ];
        $this->validate($request, $rules, $customMessages);
    	$setting = Settings::add_setting($request);
    	if ($setting) {
    		return response()->json(['error'=>false,'setting'=>'Successfully Added']);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong']);
    	}
    }
    public  function get_setting(Request $request){
        $setting = Settings::get_setting($request);
        if ($setting) {
            return response()->json(['error'=>false,'setting'=> $setting]);
        }else {
            return response()->json(['error'=>true,'message'=>'Something went wrong']);
        }
    }

    public  function get_site_settings(Request $request){
        $setting = Settings::get_site_settings($request);
        if ($setting) {
            return response()->json(['error'=>false,'setting'=> $setting]);
        }else {
            return response()->json(['error'=>true,'message'=>'Something went wrong']);
        }
    }
    public function add_location(Request $request){

    	$validatedData = $request->validate([
            'location_name' => 'required|unique:locations,location_name',
            'location_number_of_target' => 'required|numeric',
            'location_price_each_position' => 'required|numeric',
            'location_total_position' => 'required|numeric',
            'location_tax' => 'required|numeric'
        ]);

    	$Location = Locations::add_location($request);
		$LocationDays = LocationDays::add_locationday($request, $Location);

    	if ($Location && $LocationDays) {
    		return response()->json(['error'=>false,'setting'=>'Successfully Added']);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
    	}
    }

    public  function get_days(){
        $Days = Days::get_days();
        if ($Days) {
    		return response()->json(['error'=>false,'days'=> $Days]);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
    	}
    }

    public  function get_locations(){
        $branches = Locations::get_locations();

        if ($branches !== '') {
    		return response()->json(['error'=>false,'locations'=> $branches]);
    	}
    }

    public  function get_site_locations(Request $request){
        $Locations = Locations::get_site_locations($request);
        if ($Locations !== '') {
            return response()->json(['error'=>false,'locations'=> $Locations]);
        }
    }

    public  function get_locations_site(Request $request){
        $Locations = Locations::get_locations_site($request);
        if ($Locations !== '') {
            return response()->json(['error'=>false,'locations'=> $Locations]);
        }
    }

    public  function get_location_data(Request $request,$location_id){

        $date = $request->date;

        list($Location,$LocationTimes, $total_bookings, $Days) = Locations::get_location_data($request,$location_id,$date);
        $DaysClose  = Days::get_days_Location($location_id);
        if ($Location !='' && $LocationTimes!='') {
    		return response()->json(['error'=>false,'location'=> $Location,'locationtimes'=> $LocationTimes, 'total_bookings'=> $total_bookings, 'DaysClose'=> $DaysClose, 'Days'=>$Days ]);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
    	}
    }

    public  function get_days_Location($location_id){
        
       list($Days,$DaysClose)  = Days::get_days_Location($location_id);
        if ($Days && $DaysClose) {
    		return response()->json(['error'=>false,'days'=> $Days,'daysClose'=> $DaysClose]);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
    	}
    }

    public  function update_location(Request $request ,$location_id){
        $validatedData = $request->validate([
            'location_name' => 'required',
            'location_address' => 'required',
            'country' => 'required',
            'location_number_of_target' => 'required|numeric',
            'price_of_target' => 'required|numeric',
            'no_of_positions' => 'required|numeric',
            'sales_tax' => 'required|numeric'
        ]);
        $Location = Locations::update_location($request, $location_id);
        $LocationDays = LocationDays::update_locationday($request, $location_id);
        if ($Location) {
    		return response()->json(['error'=>false,'message'=> 'Successfully Updated']);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
    	}
    }

    public function view_locations(){
        $site_id = Auth::user()->site_id;
        $locations = Locations::where('location_fk_site_id', $site_id)->get();
        if ($locations) {
            return response()->json(['error'=>false,'locations'=> $locations]);
        }else {
            return response()->json(['error'=>true,'message'=>'Something went wrong']);
        }
    }

    public function get_language(Request $request){
        $local = Settings::select('setting_language')->first();
        if($local){
            $setting_language = $local->setting_language;
            
        }else{
            $setting_language =  'en';
        }
        App::setlocale($setting_language);
        $dashboard = Lang::get('dashboard');
        $targets = Lang::get('targets');
        $bookings = Lang::get('bookings');
        $finance = Lang::get('finance');
        $notifications = Lang::get('notifications');
        $settings = Lang::get('settings');
        $add_customer_popup = Lang::get('add_customer_popup');
        $manage_booking = Lang::get('manage_booking');

        $languages = [
            'dashboard'=>$dashboard,
            'targets'=>$targets,
            'bookings'=>$bookings,
            'finance'=>$finance,
            'notifications'=>$notifications,
            'settings'=>$settings,
            'add_customer_popup'=>$add_customer_popup,
            'manage_booking'=>$manage_booking,
        ];
        return response()->json(['error'=>false,  'language'=> $languages]); 
    }
    public  function get_location_data_site(Request $request,$location_id){
        $date = $request->date;
        list($Location,$LocationTimes, $total_bookings, $Days) = Locations::get_location_data_site($request,$location_id,$date);
        $DaysClose  = Days::get_days_Location($location_id);
        if ($Location !='' && $LocationTimes!='') {
            return response()->json(['error'=>false,'location'=> $Location,'locationtimes'=> $LocationTimes, 'total_bookings'=> $total_bookings, 'DaysClose'=> $DaysClose, 'Days'=>$Days ]);
        }else {
            return response()->json(['error'=>true,'message'=>'Something went wrong'], $this->unprocessableStatus);
        }
    }
    public function site_configuration(Request $request){
        $rules = [
            'setting_company_name' => 'required',
            'setting_service_name' => 'required',
            'setting_employee_first_name' => 'required',
            'setting_employee_last_name' => 'required',
            'setting_company_email' => 'required|email',
            'setting_company_phone' => 'required',
            'setting_mail_host' => 'required',
            'setting_mail_username' => 'required|email',
            'setting_mail_password' => 'required',
            'setting_mail_encryption' => 'required',
        ];
        $customMessages = [
            'setting_company_name.required' => 'Company name is required!',
            'setting_service_name.required' => 'Service name is required!',
            'setting_employee_first_name.required' => 'First name is required!',
            'setting_employee_last_name.required' => 'Last name on Background is required!',
            'setting_company_email.required' => 'Email is required!',
            'setting_company_email.email' => 'Invalid email!',
            'setting_company_phone.required' => 'Phone number is required!',
            'setting_mail_host.required' => 'SMTP Mail Host is required',
            'setting_mail_username.required' => 'SMTP Mail Username is required',
            'setting_mail_username.email' => 'Invalid SMTP Mail !',
            'setting_mail_password.required' => 'SMTP Mail password is required',
            'setting_mail_encryption.required' => 'SMTP Mail Encryption is required',
        ];
        $this->validate($request, $rules, $customMessages);
        $setting = Settings::site_configuration($request);
        if ($setting) {
            return response()->json(['error'=>false,'setting'=>'Successfully Added']);
        }else {
            return response()->json(['error'=>true,'message'=>'Something went wrong']);
        }
    }
}