<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;
use DB;
class City extends Model
{
    //
    public static function getcities($state){
    	$stateid = DB::table('states')->select('id')->where('state',$state)->first();
        $getcities = DB::table('cities')->where('state_id',$stateid->id)->get();
        $getcities = json_decode(json_encode($getcities),true);
        return $getcities;
    }
}
