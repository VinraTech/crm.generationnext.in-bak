<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AssetValuation;
class AssetValuation extends Model
{
    //
     public static function getvaluation($fileid,$assetid,$bankid){
    	$getvaluation = AssetValuation::where(['file_id'=>$fileid,'asset_id'=>$assetid,'bank_id'=>$bankid])->first();
    	$getvaluation = json_decode(json_encode($getvaluation),true);
    	return $getvaluation; 
    }

    public function asset(){
    	return $this->belongsTo('App\FileAssetDetail','asset_id');
    }
}
