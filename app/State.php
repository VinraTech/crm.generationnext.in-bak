<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;
class State extends Model
{
    //
    public static function getstates(){
    	$states = State::where('status',1)->get();
    	$states = json_decode(json_encode($states),true);
    	return $states;
    }
}
