<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    //
    public function subchecklist(){
    	return $this->hasMany('App\Checklist','parent_id');
    }
}
