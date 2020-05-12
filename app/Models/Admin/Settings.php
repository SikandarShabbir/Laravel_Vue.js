<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
//for local storage Image
use File;
use Storage;
use App\Helpers\CheckSite;
use Illuminate\Support\Facades\Input;
use App\Helpers\PhoneNumberFormat;
//End picture 
class Settings extends Model
{
    //
	use SoftDeletes;
	protected $primaryKey = 'setting_id';
	protected $table = 'general_settings';
	const CREATED_AT = 'setting_created_at';
	const UPDATED_AT = 'setting_updated_at';

	public static function add_setting($request){
		
		$upload_path = 'storage/app/settings/';
        $PATH = str_replace('\\', '/', $upload_path);
        $setting_logo  = '';
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $fileName = explode('.',$fileName);
            $fileName = uniqid($fileName[0]).'.'.$fileName[1];
            $fileExtension = $file->getClientOriginalExtension();
            if(!File::isDirectory($PATH)){ //check the userProfile directory is exist
                File::makeDirectory($PATH, 0777, true); //if not then create
                Input::file('logo')->move($PATH,$fileName); //move the file
                $setting_logo = $PATH.$fileName;
            }else{
                if($fileExtension == 'jpg' || $fileExtension == 'JPG' || $fileExtension =='png' ||$fileExtension == 'PNG' || $fileExtension== 'jpeg' || $fileExtension == 'JPEG'){
                    Input::file('logo')->move($PATH,$fileName);
                    $setting_logo = $PATH.$fileName;
                }
            }
        }
		$setting_fk_site_id = $request->user()->site_id;
		$Settings = Settings::where('setting_fk_site_id', $setting_fk_site_id)->first();
		if($Settings){
			$Settings->setting_fk_site_id = $setting_fk_site_id;
			$Settings->setting_primary_color = $request->setting_primary_color;
			$Settings->setting_secondary_color = $request->setting_secondary_color;
			$Settings->setting_text_color = $request->setting_text_color;
			$Settings->setting_text_bg_color = $request->setting_text_bg_color;
			$Settings->setting_background = $request->setting_background;
			$Settings->setting_font = $request->setting_font;
			if($setting_logo !== ''){
	            $Settings->setting_logo =$setting_logo;
	        }
			$Settings->setting_language = $request->setting_language;
			$Settings->save();
		}
		else{
			$Settings = new Settings;
			$Settings->setting_fk_site_id = $setting_fk_site_id;
			$Settings->setting_primary_color = $request->setting_primary_color;
			$Settings->setting_secondary_color = $request->setting_secondary_color;
			$Settings->setting_text_color = $request->setting_text_color;
			$Settings->setting_text_bg_color = $request->setting_text_bg_color;
			$Settings->setting_background = $request->setting_background;
			$Settings->setting_font = $request->setting_font;
			if($setting_logo !== ''){
				$Settings->setting_logo = $setting_logo;
			}
			$Settings->setting_language = $request->setting_language;
			$Settings->save();
		}
		$lastsettingID= $Settings->setting_id;
	 	return $lastsettingID;
	}
	public static function get_setting($request){
		$site_id = $request->user()->site_id;
        $Settings = Settings::where('setting_fk_site_id', $site_id)->orderBy('setting_fk_site_id', 'ASC')->first();
        return $Settings;
	}
	public static function get_site_settings($request){
		$site_id = CheckSite::CheckSite();
        $Settings = Settings::where('setting_fk_site_id', $site_id)->orderBy('setting_fk_site_id', 'ASC')->first();
        return $Settings;
    }
	public static function site_configuration($request){
		$setting_fk_site_id = $request->user()->site_id;
		$Settings = Settings::where('setting_fk_site_id', $setting_fk_site_id)->first();
		if($Settings){
			$Settings->setting_company_name = $request->setting_company_name;
			$Settings->setting_service_name = $request->setting_service_name;
			$Settings->setting_employee_first_name = $request->setting_employee_first_name;
			$Settings->setting_employee_last_name = $request->setting_employee_last_name;
			$Settings->setting_company_email = $request->setting_company_email;
			$Settings->setting_company_phone =PhoneNumberFormat::storePhoneNumberFormat($request->setting_company_phone);
			$Settings->setting_admin_notification_active = $request->setting_admin_notification_active;
			$Settings->setting_customer_notification_active = $request->setting_customer_notification_active;
			$Settings->setting_mail_host = $request->setting_mail_host;
			$Settings->setting_mail_username = $request->setting_mail_username;
			$Settings->setting_mail_password = $request->setting_mail_password;
			$Settings->setting_mail_encryption = $request->setting_mail_encryption;
			$Settings->save();
		}
		else{
			$Settings = new Settings;
			$Settings->setting_fk_site_id = $setting_fk_site_id;
			$Settings->setting_company_name = $request->setting_company_name;
			$Settings->setting_service_name = $request->setting_service_name;
			$Settings->setting_employee_first_name = $request->setting_employee_first_name;
			$Settings->setting_employee_last_name = $request->setting_employee_last_name;
			$Settings->setting_company_email = $request->setting_company_email;
			$Settings->setting_company_phone = PhoneNumberFormat::storePhoneNumberFormat($request->setting_company_phone);
			$Settings->setting_admin_notification_active = $request->setting_admin_notification_active;
			$Settings->setting_customer_notification_active = $request->setting_customer_notification_active;
			$Settings->setting_mail_host = $request->setting_mail_host;
			$Settings->setting_mail_username = $request->setting_mail_username;
			$Settings->setting_mail_password = $request->setting_mail_password;
			$Settings->setting_mail_encryption = $request->setting_mail_encryption;
			$Settings->save();
		}
		$lastsettingID= $Settings->setting_id;
	 	return $lastsettingID;
	}

}
