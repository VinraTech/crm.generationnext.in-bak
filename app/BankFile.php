<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankFile extends Model
{
    //
    public function bankdetail(){
    	return $this->belongsTo('App\Bank','bank_id');
    }
}
