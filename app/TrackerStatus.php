<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class TrackerStatus extends Model
{
    //
    public static function trackers($department,$type=null){
    	$trackers = DB::table('tracker_statuses')->select('type','type_full_name')->where('status',1)->groupby('type')->where('type','!=',$type)->orderby('sort','ASC')->wherein('access',['all',$department])->get();
    	$trackers = json_decode(json_encode($trackers),true);
    	return $trackers;
    }

    public static function trackerstatus($name,$type){
    	$details =  DB::table('tracker_statuses')->select('move_to')->where(['name'=> $name,'type'=> $type])->first();
    	$details = json_decode(json_encode($details),true);
    	return $details;
    }
}
