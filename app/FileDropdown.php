<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FileDropdown;
class FileDropdown extends Model
{
    //
    public static function getfiledropdown($type){
    	$getdetails = FileDropdown::where('type',$type)->orderBy('value')->get();
    	$getdetails = json_decode(json_encode($getdetails),true);
    	return $getdetails;
    }
}
