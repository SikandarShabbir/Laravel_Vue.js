<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth; 
use Closure;
use Config;
use DB;
use request;

class SiteURLMiddelware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next, $guard = null)
    {


        $URL = Request::segment(1);

        $site_url=DB::select('select site_name from sites where site_name = :site_name', ['site_name' => $URL]);
        if(count($site_url) > 0){   
            return $next($request);
        }else{
            return $next($request);
        } 


        // exit(); 
    }
}
