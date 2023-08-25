<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public function allocatelead(){
    	return $this->hasOne('App\AllocateLead','lead_id')->select('id','lead_id','allocate_to')->with('allocateto');
    }

    public function leadthreads(){
    	return $this->hasOne('App\LeadThread','lead_id')->select('id','lead_id','emp_id','lead_status_id','appoint_date_time','message','type')->with('threadleadstatus');
    }
}
