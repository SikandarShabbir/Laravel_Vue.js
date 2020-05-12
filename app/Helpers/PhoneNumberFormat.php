<?php 

namespace App\Helpers;

class PhoneNumberFormat
{
    
    public static function storePhoneNumberFormat($phoneNumber){
        $Replace = array('(', ')', ' ', '-');
        return str_replace($Replace , '', $phoneNumber);
    }

    public static function setPhoneNumberFormat($phoneNumber){
		return "(".substr($phoneNumber, 0, 3).") ".substr($phoneNumber, 3, 3)."-".substr($phoneNumber,6);
    }

    public static function storeCardNumberFormat($cardNumber){
        $Replace = array(' ');
        return str_replace($Replace , '', $cardNumber);
    }

    public static function storeDateFormat($cardNumber){
        $Replace = array(' ', '|');
        return str_replace($Replace , '', $cardNumber);
    }
    public static function convert_time($time) {
        if ($time == '00') {
            $time = $time+12;
            return $time.':00';
        } else if($time >=1 && $time <= 11){
            return $time.':00';
        } else if ($time == '12') {
            return $time.':00';
        } else if($time >12 && $time <= 23){
            // $time = $time-12;
            return $time.':00';
        }
    }

    public static function storeOrderIDFormat($orderID){
        return "00".$orderID;
    }
    public static function setOrderIDFormat($booking_order_id){
        $parts = str_split($booking_order_id, 4);
        $booking_order_id = $parts[0] .'-'. $parts[1] .'-'. $parts[2];
        return $booking_order_id;
    }

    public static function ShowDateFormat($date){
        return date('M d, Y', strtotime($date));
    }
}