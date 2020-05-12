<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use App\Models\Admin\Payments;
use App\Models\Admin\Locations;
use App\Models\Admin\Refund;
use App\Helpers\MailConfigurations;
use App\Helpers\PhoneNumberFormat;
use App\Helpers\CheckSite;
use App\Models\Admin\Settings;
use App\Models\Admin\Notifications;
class BookingsController extends Controller
{
    public function view_bookings(Request $request){
        $today = $request->input('today');
        $date = $request->input('date');        
        $from_date = $date[0];
        $to_date = $date[1];  
        $search = $request->input('search');
        $locationID = $request->input('booking_location_id');
        // echo $locationID;exit();
        $target = $request->input('targets');
        $status = $request->input('status');
    	$bookings = Booking::view_bookings($request,$search,$from_date,$to_date,$locationID,$target,$status,$today); //$date to be sent
    	if ($bookings) {
    		return response()->json(['error'=>false,'bookings'=>$bookings]);
    	}else {
    		return response()->json(['error'=>false,'bookings'=>[]]);
    	}
    }

    public function view_todays_bookings(Request $request){
        $today = $request->input('today');
        $locationID = $request->input('location_id');
    	$bookings = Booking::view_todays_bookings($request,$locationID,$today); //$date to be sent
    	if ($bookings) {
    		return response()->json(['error'=>false,'bookings'=>$bookings]);
    	}else {
    		return response()->json(['error'=>false,'bookings'=>[]]);
    	}
    }
    public function view_targets(Request $request){
        $location = $request->location_id;
        $date = $request->date;
        $timing = $request->timing;
        $target = $request->target;
        list($bookings,$selectedDay) = Booking::view_targets($request,$location,$date,$timing,$target); //$date to be sent
        if ($bookings!='' && $selectedDay!='' ) {
            return response()->json(['error'=>false,'bookings'=>$bookings,'selectedDay'=>$selectedDay ]);
        }else {
            return response()->json(['error'=>true,'message'=>'No bookings returend']);
        }
    }
    public function add_booking(Request $request){
        if(date('Y-m-d', strtotime($request->date)) < date("Y-m-d")){
            return response()->json(['error'=>true,'message'=>'Booking for previous date is not allowed']);
        }else{
            if($request->payment_type == "cash"){
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'checkedPositions' => 'required',
                    'payment_status' => 'required',

                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'checkedPositions.required' => 'Please select position on a target!',
                    'payment_status.required' => 'Payment Status is required!'
                ];

                $this->validate($request, $rules, $customMessages);
            }else{
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'checkedPositions' => 'required',
                    'payment_status' => 'required',
                    'payment_card_number' =>'required',
                    'payment_expiry_date' =>'required'
                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'checkedPositions.required' => 'Please select position on a target!',
                    'payment_card_number.required' => 'Card number is required!',
                    'payment_expiry_date.required' => 'Expiry date is required!'
                ];
                $this->validate($request, $rules, $customMessages);
            }
            if($request->payment_status == "1"){
                $editBookingType ="pending_booking";
            }else if($request->payment_status == "8"){
                $editBookingType ="partial_booking";
            }else{
                $editBookingType ="approve_booking";
            }

            list($booking_id,$booking_order_id)  = Booking::add_booking($request);

            $Payments = Payments::add_payment($booking_id,$request);
           
            if ($booking_id !='' && $Payments!='') {
                $send_email_notification = $this->send_email_notification($request, $booking_order_id, $booking_id, $editBookingType);
                if( $send_email_notification !='error sending Email'){
                    return response()->json(['error'=>false,'message'=>'Booked Successfully', 'booking_order_id'=>$booking_order_id]);
                }else{
                    return response()->json(['error'=>true,'message'=>'Booking updated but email not sent']);
                }
            }else {
                return response()->json(['error'=>true,'message'=>'No bookings returend']);
            }
        }
        
    }
    public function get_location_target(Request $request){
        $location_id = $request->location_id;
        if($location_id){
            $locations = Locations::find($location_id);
            $locations->get();
        }
        if (isset($locations)) {
            return response()->json(['error'=>false,'locations'=>$locations]);
        }else {
            return response()->json(['error'=>true,'message'=>'No Location returend']);
        }
    }
    public function update_status(Request $request){
        $payment_id = $request->payment_id;
        $payment_status = $request->payment_status;
        $status = Payments::findOrFail($payment_id);
        $status->payment_status = $payment_status;
        $status->save(); 
        if ($status) {
            return response()->json(['error'=>false,'status'=>'Status Updated']);
        }else {
            return response()->json(['error'=>true,'message'=>'No Status Updated']);
        }
    }
    public function edit_bookings(Request $request, $booking_id){
        $booking = Booking::edit_bookings($request, $booking_id); //$date to be sent
        if ($booking) {
            return response()->json(['error'=>false,'booking'=>$booking]);
        }else {
            return response()->json(['error'=>true,'message'=>'No record found']);
        }
    }
    public function update_booking(Request $request, $booking_id){

        if($request->date < date("Y-m-d")){

            return response()->json(['error'=>true,'message'=>'Can not reschedule to previous date']);
        }else{
            if($request->payment_type == "cash"){
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'Selected' => 'required',
                    'payment_status' => 'required',

                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'Selected.required' => 'Please select position on a target!',
                    'payment_status.required' => 'Payment Status is required!'
                ];

                $this->validate($request, $rules, $customMessages);
            }else{
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'Selected' => 'required',
                    'payment_card_number' =>'required',
                    'payment_expiry_date' =>'required'
                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'Selected.required' => 'Please select position on a target!',
                    'payment_card_number.required' => 'Card number is required!',
                    'payment_expiry_date.required' => 'Expiry date is required!'
                ];
                $this->validate($request, $rules, $customMessages);
            }
            // exit();
            $editBookingType ="edit_booking";
            $update_booking = Booking::update_booking($request, $booking_id);
            $Payments = Payments::update_payment($booking_id,$request);
            if ($update_booking && $Payments) {
                $send_email_notification = $this->send_email_notification($request,$request->booking_order_id, $booking_id, $editBookingType);
                if( $send_email_notification !='error sending Email'){
                    return response()->json(['error'=>false,'message'=>'Successfull']);
                }else{
                    return response()->json(['error'=>true,'message'=>'Booking updated but email not sent']);
                }
            }else {
                return response()->json(['error'=>true,'message'=>'No bookings returend']);
            }
        }
    }
    public function view_targetsEdit(Request $request,$booking_id){
        $location = $request->location_id;
        $date = $request->date;
        $timing = $request->timing;
        list($editBookingById,$alreadybooked,$selectedDay) = Booking::view_targetsEdit($request,$location,$date,$timing,$booking_id);
        if ($editBookingById!='' && $selectedDay!='' ) {
            return response()->json(['error'=>false,'bookings'=>$editBookingById,'selectedDay'=>$selectedDay,'alreadybooked'=>$alreadybooked ]);
        }else {
            return response()->json(['error'=>true,'message'=>'No bookings returend']);
        }
    }
    public function cancel_booking(Request $request,$booking_id){

        if($request->refund_type == "dedit_card" || $request->refund_type == "credit_card"){
            $rules = [
                'refund_type' => 'required',
                'refund_card_number' => 'required',
                'booking_first_name' => 'required'
            ];
            $customMessages = [
                'refund_type.required' => 'Refund Type is required!',
                'refund_card_number.required' => 'Card number is required!',
                'refund_card_number.numeric' => 'Card number should be numeric!',
                'booking_first_name.required' => 'Card holder name is required!',
            ];
            $this->validate($request, $rules, $customMessages);
        }else if($request->refund_type == "cheque"){
            $rules = [
                'refund_type' => 'required',
                'refund_cheque_number' => 'required|numeric',
                'booking_first_name' => 'required'
            ];
            $customMessages = [
                'refund_type.required' => 'Refund Type is required!',
                'refund_cheque_number.numeric' => 'Cheque number should be numeric!',
                'refund_cheque_number.required' => 'Cheque number is required!'
            ];
            $this->validate($request, $rules, $customMessages);
        }else{
            $rules = [
                'refund_type' => 'required',
            ];
            $customMessages = [
                'refund_type.required' => 'Refund Type is required!',
            ];
            $this->validate($request, $rules, $customMessages);

        }

        $editBookingType ="cancel_booking";
        
        if($request->refund_type == "none"){
            $payment_total_amount = '0';
            $refund_payment = Refund::refund_payment($request,$booking_id, $payment_total_amount);
        }else{
        $site_id = $request->user()->site_id;              
        $payment = Payments::where('payment_fk_booking_id',$booking_id)
                                ->where('payment_fk_site_id',$site_id)
                                ->first();
          if($payment->payment_status == 1){
            $payment_total_amount = 0;
            $refund_payment = Refund::refund_payment($request,$booking_id, $payment_total_amount);
          }                      
          if($payment->payment_status == 8) {
            $payment_total_amount = $payment->payment_paid;
            $refund_payment = Refund::refund_payment($request,$booking_id, $payment_total_amount);

          }else if($payment->payment_status == 2){
            $payment_total_amount = ($request->location_price_each_position * $request->totalPositions)+ $request->total_tax;
            $refund_payment = Refund::refund_payment($request,$booking_id, $payment_total_amount);
          }                    
        }

        $cancel_payment = Payments::cancel_payment($request,$booking_id);
        $cancel_booking = Booking::cancel_booking($request,$booking_id);
        if ($cancel_booking!='' && $cancel_payment!='' && $refund_payment!='') {
            $send_email_notification = $this->send_email_notification($request,$request->booking_order_id, $booking_id, $editBookingType);
            if( $send_email_notification !='error sending Email'){
                return response()->json(['error'=>false,'message'=>'Successfully Canceled']);
            }else{
                return response()->json(['error'=>true,'message'=>'Booking has been canceled but email not sent']);
            }
        }else {
            return response()->json(['error'=>true,'message'=>'No bookings returend']);
        }
    }

    public function invite_friend(Request $request)
    {
        $invite_friend = Booking::invite_friend($request);
        if($invite_friend){
                return response()->json(['error'=>false,'message'=>'Invitation sent Successfully']);
            }else{
                return response()->json(['error'=>true,'message'=>'No Email sent']);
            }
    }
    public function add_booking_site(Request $request){

        if(date('Y-m-d', strtotime($request->date)) < date("Y-m-d")){
            return response()->json(['error'=>true,'message'=>'Booking for previous date is not allowed']);
        }else{
            if($request->payment_type == "cash"){
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'checkedPositions' => 'required',
                    'payment_status' => 'required',

                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'checkedPositions.required' => 'Please select position on a target!',
                    'payment_status.required' => 'Payment Status is required!'
                ];

                $this->validate($request, $rules, $customMessages);
            }else{
                $rules = [
                    'booking_first_name' => 'required',
                    'booking_phone' => 'required',
                    'booking_email' => 'required|email',
                    'date' => 'required',
                    'checkedPositions' => 'required',
                    'payment_card_number' =>'required',
                    'payment_expiry_date' =>'required',
                    'payment_status' => 'required',
                ];
                $customMessages = [
                    'booking_first_name.required' => 'First name is required!',
                    'booking_phone.required' => 'Phone number is required!',
                    'booking_email.required' => 'Email is required!',
                    'booking_email.email' => 'Invalid email',
                    'date.required' => 'Date is required!',
                    'checkedPositions.required' => 'Please select position on a target!',
                    'payment_card_number.required' => 'Card number is required!',
                    'payment_expiry_date.required' => 'Expiry date is required!',
                    'payment_status.required' => 'Payment Status is required',

                ];
                $this->validate($request, $rules, $customMessages);
            }
            if($request->payment_status == "1"){
                $editBookingType ="pending_booking";
            }else if($request->payment_status == "8"){
                $editBookingType ="partial_booking";
            }else{
                $editBookingType ="approve_booking";
            }
            list($booking_id,$booking_order_id) = Booking::add_booking_site($request);
            $Payments = Payments::add_payment_site($booking_id,$request);

            if ($booking_id !='' && $Payments!='') {
                $send_email_notification = $this->send_email_notification_site($request, $booking_order_id, $booking_id, $editBookingType);
                if( $send_email_notification !='error sending Email'){
                    return response()->json(['error'=>false,'message'=>'Successfull', 'booking_order_id'=>$booking_order_id]);
                }else{
                    return response()->json(['error'=>true,'message'=>'Booking updated but email not sent']);
                }
            }else {
                return response()->json(['error'=>true,'message'=>'No bookings returend']);
            }
        }
        
    }
    public function view_targets_site(Request $request){
        $location = $request->location_id;
        $date = $request->date;
        $timing = $request->timing;
        $target = $request->target;
        list($bookings,$selectedDay) = Booking::view_targets_site($request,$location,$date,$timing,$target); //$date to be sent
        if ($bookings!='' && $selectedDay!='' ) {
            return response()->json(['error'=>false,'bookings'=>$bookings,'selectedDay'=>$selectedDay ]);
        }else {
            return response()->json(['error'=>true,'message'=>'No bookings returend']);
        }
    }
    public function invite_friend_site(Request $request)
    {
        $invite_friend = Booking::invite_friend_site($request);
        if($invite_friend){
                return response()->json(['error'=>false,'message'=>'Invitation sent Successfully']);
            }else{
                return response()->json(['error'=>true,'message'=>'No Email sent']);
            }
    }

    //email sending module
    public function send_email_notification($request,$booking_order_id,$booking_id,$editBookingType){
        $site_id = $request->user()->site_id;
        $config = MailConfigurations::setMailConfigurations($site_id);
        $setting = Settings::get_setting($request);
        if($setting->setting_customer_notification_active == 1){
            $service_name = $setting->setting_service_name;
            $company_name = $setting->setting_company_name;
            $employee_full_name = $setting->setting_employee_first_name." ".$setting->setting_employee_last_name;

            $to_email= $request->booking_email;
            $to_name= $request->booking_first_name." ".$request->booking_last_name;
            $from_email = 'sales@shopallcars.com';
            $from_name = 'Lumberjaxe';
            if($editBookingType == "partial_booking"){
            $notification_type = "8";
            $notification = Notifications::get_notification($notification_type);
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_booking");
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_payment");
            $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "pending_booking"){
            $notification_type = "1";
            $notification = Notifications::get_notification($notification_type);
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_booking");
            $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "approve_booking"){
            $notification_type = "2";
            $notification = Notifications::get_notification($notification_type);
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_booking");
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_payment");
            $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "edit_booking"){
            $notification_type = "5";
            $notification = Notifications::get_notification($notification_type);
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_reschedule");
            $emailsFile = 'emails.reschedule';
        }else if($editBookingType == "cancel_booking"){
            $notification_type = "4";
            $notification = Notifications::get_notification($notification_type);
            $this->send_email_notification_to_admin($booking_order_id, $request,"new_cancellation");
            $emailsFile = 'emails.cancel_booking';
            }
            if (isset($notification->notification_subject)) {
            $subj = $notification->notification_subject;
            $msg = $notification->notification_message;
            }else {
                $subj = "Welcome to your first booking";
                $msg = "You are most Welcome for your 1st booking.";
            }
            $vars = array(
            '$customer_full_name'       => "".$to_name."",
            '$employee_full_name'       => "".$employee_full_name."",
            '$service_name'      				=> "".$service_name."", 
            '$location_address'       	=> "".$request->location_name."",
            '$appointment_date_time'    => "".PhoneNumberFormat::ShowDateFormat($request->date)."",
            '$company_name'       			=> "".$company_name."",
            '$booking_order_id'					=> "".$booking_order_id.""
            );
            $msg = strtr($msg, $vars);
            $subj = strtr($subj, $vars);
        
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
    public function send_email_notification_to_admin($booking_order_id,$request,$editBookingType){
        
        $setting = Settings::get_setting($request);
        if($setting->setting_admin_notification_active == 1){
            $service_name = $setting->setting_service_name;
            $company_name = $setting->setting_company_name;
            $employee_full_name = $setting->setting_employee_first_name." ".$setting->setting_employee_last_name;
            $customer_name = $request->booking_first_name." ".$request->booking_last_name;
            $customer_paid = $request->total_payment;
            $customer_paid_with = $request->payment_type;
            $to_email= $setting->setting_company_email;
            $to_name= $employee_full_name;

            $from_email = 'sales@shopallcars.com';
            $from_name = 'Lumberjaxe';

            if($editBookingType == "new_booking"){
                $notification_type = "8";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_payment"){
                $notification_type = "9";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_cancellation"){
                $notification_type = "10";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_reschedule"){
                $notification_type = "11";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_location"){
                $notification_type = "12";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }
            if (isset($notification->notification_subject)) {
            $subj = $notification->notification_subject;
            $msg = $notification->notification_message;
            }else {
                $subj = "Welcome to your first booking";
                $msg = "You are most Welcome for your 1st booking.";
            }

            $vars = array(
            '$admin_name'      				  => "".$to_name."",
            '$customer_full_name'       => "".$customer_name."",
            '$customer_paid_amount'     => "$".$customer_paid."",
            '$customer_payment_method'  => "".ucfirst(str_replace('_',' ',$customer_paid_with))."",
            '$employee_full_name'       => "".$employee_full_name."",
            '$service_name'      			  => "".$service_name."", 
            '$location_address'       	=> "".$request->location_name."",
            '$appointment_date_time'    => "".PhoneNumberFormat::ShowDateFormat($request->date)."",
            '$company_name'       			=> "".$company_name."",
            '$booking_order_id'    			=> "".$booking_order_id.""
            );
            $msg = strtr($msg, $vars);
            $subj = strtr($subj, $vars);
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
    public function send_email_notification_to_admin_site($booking_order_id,$request,$editBookingType){

        $setting = Settings::get_site_settings($request);
        if($setting->setting_admin_notification_active == 1){
            $service_name = $setting->setting_service_name;
            $company_name = $setting->setting_company_name;
            $employee_full_name = $setting->setting_employee_first_name." ".$setting->setting_employee_last_name;
            $customer_name = $request->booking_first_name." ".$request->booking_last_name;
            $customer_paid = $request->total_payment;
            $customer_paid_with = $request->payment_type;

            $to_email= $setting->setting_company_email;
            $to_name= $employee_full_name;

            $from_email = 'sales@shopallcars.com';
            $from_name = 'Lumberjaxe';

            if($editBookingType == "new_booking"){
                $notification_type = "8";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_payment"){
                $notification_type = "9";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_cancellation"){
                $notification_type = "10";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_reschedule"){
                $notification_type = "11";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "new_location"){
                $notification_type = "12";
                $notification = Notifications::get_notification($notification_type);
                $emailsFile = 'emails.reschedule';
            }
            if (isset($notification->notification_subject)) {
            $subj = $notification->notification_subject;
            $msg = $notification->notification_message;
            }else {
                $subj = "Welcome to your first booking";
                $msg = "You are most Welcome for your 1st booking.";
            }

            $vars = array(
            '$admin_name'      				  => "".$to_name."",
            '$customer_full_name'       => "".$customer_name."",
            '$customer_paid_amount'     => "$".$customer_paid."",
            '$customer_payment_method'  => "".ucfirst(str_replace('_',' ',$customer_paid_with))."",
            '$employee_full_name'       => "".$employee_full_name."",
            '$service_name'      			  => "".$service_name."", 
            '$location_address'       	=> "".$request->location_name."",
            '$appointment_date_time'    => "".PhoneNumberFormat::ShowDateFormat($request->date)."",
            '$company_name'       			=> "".$company_name."",
            '$booking_order_id'    			=> "".$booking_order_id.""
            );
            $msg = strtr($msg, $vars);
            $subj = strtr($subj, $vars);
        
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
    public function send_email_notification_site($request,$booking_order_id,$booking_id,$editBookingType){
        
            $site_id = CheckSite::CheckSite();
            $config = MailConfigurations::setMailConfigurations($site_id);
        $setting = Settings::get_site_settings($request);
            if($setting->setting_customer_notification_active == 1){
                $service_name = $setting->setting_service_name;
                $company_name = $setting->setting_company_name;
                $employee_full_name = $setting->setting_employee_first_name." ".$setting->setting_employee_last_name;

                $to_email= $request->booking_email;
                $to_name= $request->booking_first_name." ".$request->booking_last_name;
                $from_email = 'sales@shopallcars.com';
                $from_name = 'Lumberjaxe';

            if($editBookingType == "partial_booking"){
                $notification_type = "8";
                $notification = Notifications::get_notification($notification_type);
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_booking");
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_payment");
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "pending_booking"){
                $notification_type = "1";
                $notification = Notifications::get_notification($notification_type);
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_booking");
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "approve_booking"){
                $notification_type = "2";
                $notification = Notifications::get_notification($notification_type);
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_booking");
                    $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_payment");
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "edit_booking"){
                $notification_type = "5";
                $notification = Notifications::get_notification($notification_type);
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_reschedule");
                $emailsFile = 'emails.reschedule';
            }else if($editBookingType == "cancel_booking"){
                $notification_type = "4";
                $notification = Notifications::get_notification($notification_type);
                $this->send_email_notification_to_admin_site($booking_order_id,$request,"new_cancellation");
                $emailsFile = 'emails.cancel_booking';
            }
            if (isset($notification->notification_subject)) {
                $subj = $notification->notification_subject;
                $msg = $notification->notification_message;
            }else {
                $subj = "Welcome to your first booking";
                $msg = "You are most Welcome for your 1st booking.";
            }
                $vars = array(
                '$customer_full_name'       => "".$to_name."",
                '$employee_full_name'       => "".$employee_full_name."",
                '$service_name'       			=> "".$service_name."", 
                '$location_address'       	=> "".$request->location_name."",
                '$appointment_date_time'    => "".PhoneNumberFormat::ShowDateFormat($request->date)."",
                '$company_name'       			=> "".$company_name."",
                '$booking_order_id'      	  => "".$booking_order_id.""
                );
                $msg = strtr($msg, $vars);
                $subj = strtr($subj, $vars);
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

}
