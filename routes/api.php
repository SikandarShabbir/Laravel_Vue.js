<?php
use Illuminate\Http\Request;

Route::post('login', 'API\UserController@login');

//localization languages

// Route::get('lang/{locale}','API\Admin\SettingsController@get_language');
Route::get('lang','API\Admin\SettingsController@get_language');
Route::get('get_locations_site', 'API\Admin\SettingsController@get_locations_site');
Route::post('get_location_data_site/{id}', 'API\Admin\SettingsController@get_location_data_site');
Route::post('/add_booking_site','API\Admin\BookingsController@add_booking_site');
Route::post('/invite_friend_site','API\Admin\BookingsController@invite_friend_site');
Route::post('/view_targets_site','API\Admin\BookingsController@view_targets_site');
// Route::post('/get_language_site','API\Admin\SettingsController@get_language_site');
Route::get('get_site_settings', 'API\Admin\SettingsController@get_site_settings');

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('isloggedin', 'API\UserController@isloggedin');
	Route::get('logout', 'API\UserController@logout');

	// Location
	Route::post('add_location', 'API\Admin\SettingsController@add_location');
	Route::get('get_locations', 'API\Admin\SettingsController@get_locations');
	Route::post('get_location_data/{id}', 'API\Admin\SettingsController@get_location_data');
	Route::post('edit_location/{id}', 'API\Admin\SettingsController@update_location');
	Route::get('get_site_locations', 'API\Admin\SettingsController@get_site_locations');
	Route::get('view_locations', 'API\Admin\SettingsController@view_locations');

	//Bookings
	Route::post('/view_bookings','API\Admin\BookingsController@view_bookings');
	Route::post('/view_targets','API\Admin\BookingsController@view_targets');
	Route::post('/add_booking','API\Admin\BookingsController@add_booking');
	Route::post('/get_location_target','API\Admin\BookingsController@get_location_target');
	Route::get('/edit_bookings/{id}','API\Admin\BookingsController@edit_bookings');
	Route::post('/update_status','API\Admin\BookingsController@update_status');
	Route::get('/booking_status_by_day','API\Admin\BookingsController@booking_status_by_day');
	Route::post('/edit_booking/{id}','API\Admin\BookingsController@update_booking');
	Route::post('/cancel_booking/{id}','API\Admin\BookingsController@cancel_booking');
	Route::post('/view_targetsEdit/{id}','API\Admin\BookingsController@view_targetsEdit');
	Route::post('/invite_friend','API\Admin\BookingsController@invite_friend');
	// Settings
	Route::post('add_setting', 'API\Admin\SettingsController@add_setting');
	Route::get('get_setting', 'API\Admin\SettingsController@get_setting');
	Route::post('site_configuration', 'API\Admin\SettingsController@site_configuration');
	
	//Days
	Route::post('get_days', 'API\Admin\SettingsController@get_days');
	Route::get('get_days_Location/{id}', 'API\Admin\SettingsController@get_days_Location');
	//Payments
	Route::post('view_finance','API\Admin\PaymentsController@view_finance');
	Route::post('update_partial_payment/{id}','API\Admin\PaymentsController@update_partial_payment');
	//Dashboard
	Route::post('view_dashboard','API\Admin\PaymentsController@view_dashboard');
	Route::post('/view_todays_bookings','API\Admin\BookingsController@view_bookings');
	//Notifications
	Route::post('add_notifications','API\Admin\NotificationsController@add_notifications');
	Route::post('view_notifications','API\Admin\NotificationsController@view_notifications');
	//Invite friend
	// Route::post('invite_friend','API\Admin\NotificationsController@view_notifications');
});
