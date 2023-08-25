<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    public function undermodules(){
    	return $this->hasMany('App\Module','parent_id')->orderby('sortorder','asc')->where('status',1);
    }
}
