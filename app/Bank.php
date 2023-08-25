<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Bank extends Model
{
    //
    public static function banks(){
    	$banks = DB::table('banks')->where('status',1)->orderBy('short_name')->get();
    	$banks = json_decode(json_encode($banks),true);
    	return $banks;
    }

    public static  function bankinfo($bankid){
        $bankdetails = DB::table('banks')->where('id',$bankid)->first();
        return $bankname = $bankdetails->short_name; 
    }
}
