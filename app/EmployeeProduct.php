<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeProduct extends Model
{
    //
    public function productdetail(){
    	return $this->belongsTo('App\Product','product_id');
    }
}
