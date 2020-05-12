<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Notifications;

class NotificationsController extends Controller
{
    public function view_notifications(Request $request)
    {
    	$notifications = Notifications::view_notifications($request);
    	if ($notifications) {
    		return response()->json(['error'=>false,'notifications'=>$notifications]);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong']);
    	}
    }
    public function add_notifications(Request $request)
    {
    	$validatedData = $request->validate([
            'notification_type' => 'required:numeric',
            'subject' => 'required',
            'body' => 'required',
		]);
    	$notifications = Notifications::add_notifications($request);
    	if ($notifications) {
    		return response()->json(['error'=>false,'message'=>'Successfully Added']);
    	}else {
    		return response()->json(['error'=>true,'message'=>'Something went wrong']);
    	}
    }
}
