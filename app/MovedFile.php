<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovedFile extends Model
{
    //
    public function movedby(){
    	return $this->belongsTo('App\Employee','moved_by');
    }
}
