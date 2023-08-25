<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
class NotificationEmployee extends Model
{
    //
    public static function getNotifications($empid){
        $getnotifications = NotificationEmployee::with('notificationdetails')->where('emp_id',$empid)->orderBy('created_at','DESC')->get();
        $getnotifications = json_decode(json_encode($getnotifications),true);
        $getnotificationscount = NotificationEmployee::where('emp_id',$empid)->where('is_view','no')->count();
        return array('count'=>$getnotificationscount,'notifications'=>$getnotifications);
    }

    public function notificationdetails(){
    	return $this->belongsTo('App\Notification','notification_id');
    }

    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
