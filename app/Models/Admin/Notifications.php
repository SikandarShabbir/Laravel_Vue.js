<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{
    //
    use SoftDeletes;
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    const CREATED_AT = 'notification_created_at';
	const UPDATED_AT = 'notification_updated_at';                    
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'notification_fk_site_id',
        'notification_subject',
        'notification_message',
        'notification_type',
        'notification_is_admin',
    ];
    public static function get_notification($notification_type){
        $notification = Notifications::where('notification_type', $notification_type)->first();
        return $notification;

    }
     public static function add_notifications($request)
    {
        $site_id = $user = $request->user()->site_id;
        $notification_id = $request->notification_id;
        $subject = $request->subject;
        $body = $request->body;
        $notification_type = $request->notification_type;
        $is_admin = $request->is_admin;
        if ($notification_id) 
        {
            $notifications = Notifications::updateOrCreate([
            'notification_fk_site_id'=>$site_id,
            'notification_type'=>$notification_type,
            'notification_id'=>$notification_id,
            'notification_is_admin'=>$is_admin
            ],
            ['notification_fk_site_id' => $site_id,
            'notification_subject'=> $subject,
            'notification_message' => $body,
            'notification_type' => $notification_type,
            'notification_is_admin'=>$is_admin]);
        }else {
            $notifications = Notifications::create([
            'notification_fk_site_id' => $site_id,
            'notification_subject'=> $subject,
            'notification_message' => $body,
            'notification_type' => $notification_type,
            'notification_is_admin'=>$is_admin
            ]);
        }
        
        return $notifications;
    }

    public static function view_notifications($request){
        $site_id = $user = $request->user()->site_id;
        $pending = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',1)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($pending == null) {
            $pending = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $pending = json_decode($pending);
        }
        $approved = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',2)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($approved == null) {
        $approved = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $approved = json_decode($approved);
        }
        $reject = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',3)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($reject == null) {
            $reject = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $reject = json_decode($reject);
        }
        $canceled = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',4)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($canceled == null) {
            $canceled = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $canceled = json_decode($canceled);
        }
        $rescheduled = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',5)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($rescheduled == null) {
        $rescheduled = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $rescheduled = json_decode($rescheduled);
        }
        $reminder = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',6)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($reminder == null) {
        $reminder = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $reminder = json_decode($reminder);
        }
        $follow_up = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',7)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($follow_up == null) {
        $follow_up = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $follow_up = json_decode($follow_up);
        }
        $new_booking = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',8)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($new_booking == null) {
        $new_booking = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $new_booking = json_decode($new_booking);
        }
        $new_payment = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',9)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($new_payment == null) {
        $new_payment = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $new_payment = json_decode($new_payment);
        }
        $new_cancellation = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',10)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($new_cancellation == null) {
        $new_cancellation = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $new_cancellation = json_decode($new_cancellation);
        }
        $new_rescheduled = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',11)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($new_rescheduled == null) {
        $new_rescheduled = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $new_rescheduled = json_decode($new_rescheduled);
        }
        $new_location = Notifications::where('notification_fk_site_id',$site_id)
                    ->where('notification_type',12)
                    ->select('notification_subject','notification_message','notification_id')
                    ->first();
        if ($new_location == null) {
        $new_location = '{"notification_id":0,"notification_subject":"","notification_message":""}';
            $new_location = json_decode($new_location);
        }

        return ['pending'=>$pending,
                'approved'=>$approved,
                'reject'=>$reject,
                'canceled'=>$canceled,
                'rescheduled'=>$rescheduled,
                'reminder'=>$reminder,
                'follow_up'=>$follow_up,
                'new_booking'=>$new_booking,
                'new_payment'=>$new_payment,
                'new_cancellation'=>$new_cancellation,
                'new_rescheduled'=>$new_rescheduled,
                'new_location'=>$new_location
               ];
    }
    // public static function add_notifications($request)
    // {
    //     $site_id = $user = $request->user()->site_id;
    //     $subject = $request->subject;
    //     $body = $request->body;
    //     $notification_type = $request->notification_type;
    //     $notifications = Notifications::updateOrCreate([
    //         'notification_fk_site_id'=>$site_id,
    //         'notification_type'=>$notification_type
    //         ],
    //         ['notification_fk_site_id' => $site_id,
    //         'notification_subject'=> $subject,
    //         'notification_message' => $body,
    //         'notification_type' => $notification_type]);
    //     return $notifications;
    // }

    // public static function view_notifications(){
    //     $notifications = Notifications::all();
    //     return $notifications;
    // }
}
