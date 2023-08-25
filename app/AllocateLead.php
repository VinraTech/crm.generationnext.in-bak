<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllocateLead extends Model
{
    //
    public function allocateto(){
    	return $this->belongsTo('App\Employee','allocate_to')->select('id','name','type','email','parent_id')->with('getemp');
    }

    public function allocateby(){
    	return $this->belongsTo('App\Employee','allocate_by')->select('id','name','type');
    }
}	
