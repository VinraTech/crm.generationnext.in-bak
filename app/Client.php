<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
class Client extends Model
{
    //
    public static function clientdetails($clientid){
    	$client = Client::where('id',$clientid)->first();
    	$client = json_decode(json_encode($client),true);
    	return $client;
    }

    public function saleofficer(){
    	return $this->belongsTo('App\Employee','sale_officer');
    }
}
