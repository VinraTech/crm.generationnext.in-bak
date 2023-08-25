<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileAssetDetail extends Model
{
    //
    public function company(){
    	return $this->belongsTo('App\Company','company_id');
    }

    public function model(){
    	return $this->belongsTo('App\CompanyModel','company_model_id');
    }
}
