<?php 

namespace App\Helpers;
use App\Models\Admin\Site;
use App\User;

class CheckSite
{
    
    public static function CheckSite(){
    	$url = url()->previous();
		$endofurl = strrchr(parse_url($url, PHP_URL_PATH), '/');
		$exploded_url = explode("/",$endofurl);

        $site_id = Site::Where('site_name', $exploded_url[1])->first();
        return $site_id->site_id;
    }

    public static function CheckUserSite($user_site_id){
    	$url = url()->previous();
		$endofurl = strrchr(parse_url($url, PHP_URL_PATH), '/');
		$exploded_url = explode("/",$endofurl);
		// $site_id = User::select('sites.site_name')->join('sites','users.site_id','=','sites.site_id')->where('users.id', $user_id)->first();
		// print_r($exploded_url);
		// echo ();
		// exit();
		// dd($exploded_url);
		if($exploded_url[1] != ''){
			$site_id = Site::Where('site_name', $exploded_url[1])->first();
			if($site_id->site_id == $user_site_id){
        		return 1;
	        }else{
	        	return 0;
	        }
		}else{
			return 1;
		}
        //return $site_id->site_id;
    }
}