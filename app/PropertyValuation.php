<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PropertyValuation;
class PropertyValuation extends Model
{
    //
    public static function getvaluation($fileid,$propertyid,$bankid){
    	$getvaluation = PropertyValuation::where(['file_id'=>$fileid,'property_id'=>$propertyid,'bank_id'=>$bankid])->first();
    	$getvaluation = json_decode(json_encode($getvaluation),true);
    	return $getvaluation; 
    }

    public function property(){
    	return $this->belongsTo('App\FilePropertyDetail','property_id');
    }
}
