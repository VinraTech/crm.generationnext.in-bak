<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ChannelPartner;
class ChannelPartner extends Model
{
    //
    public static function partnerdetail($partnerid){
    	$getdetails = ChannelPartner::where('id',$partnerid)->first();
    	$getdetails = json_decode(json_encode($getdetails),true);
    	return $getdetails;
    }
}
