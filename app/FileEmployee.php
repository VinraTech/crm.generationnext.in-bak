<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileEmployee extends Model
{
    //
    public function emp(){
    	return $this->belongsTo('App\Employee','employee_id');
    }
}
