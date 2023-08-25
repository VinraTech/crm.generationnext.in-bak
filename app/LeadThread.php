<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadThread extends Model
{
    //
    public function getemp(){
    	return $this->belongsTo('App\Employee','emp_id')->select('id','name','image','type');
    }

    public function threadfiles(){
    	return $this->hasMany('App\ThreadFile','thread_id');
    }

    public function threadleadstatus(){
    	return $this->belongsTo('App\LeadStatus','lead_status_id')->select('id','name','type','lead_behaviour');
    }

    public function leaddetail(){
        return $this->belongsTo('App\Lead','lead_id')->select('id','lead_id','company_name');
    }
}
