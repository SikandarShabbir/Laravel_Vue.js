<?php
//require __DIR__.'/adminRoutes.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if(Request::segment(1) != ''){
	Route::group(['prefix'=>Request::segment(1), 'middleware' => 'SiteURLMiddelware'],function(){    
    	Route::get('/', 'Site\SiteController@checkSite');
	});
}

Route::get('/', function () {
    return view('welcome');
});

