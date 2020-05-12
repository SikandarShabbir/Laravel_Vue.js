<?php

namespace App\Http\Controllers\Site;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use Request;
use DB;

class SiteController extends Controller
{
    public function checkSite(Request $request){
		$URL = Request::segment(1);
		$site_url=DB::select('select site_name from sites where site_name = :site_name', ['site_name' => $URL]);
		if(!empty($site_url)){
			$site_url = $site_url[0]->site_name;
			return view("site", ['error'=>false, 'value'=>$site_url,'change'=>'site']);
		}else{
			return  redirect(Request::root().'/#/admin');
		}
    }  
}
