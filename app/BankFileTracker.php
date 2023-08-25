<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BankFileTracker;
class BankFileTracker extends Model
{
    //
    public static function getdetails($tracker,$bankid,$fileid){
    	$getdetails = BankFileTracker::where(['type'=> $tracker,'bank_id'=>$bankid,'file_id'=> $fileid])->orderby('created_at','DESC')->first();
    	if($getdetails){
    		$datestring ="";
    		if(!empty($getdetails->date)){
    			$datestring = " (".date('d F Y',strtotime($getdetails->date)).")";
    		}
    		$details = $getdetails->status.$datestring;
    	}else{
    		$details = "-";
    	}
    	return $details;
    }

    public function createdby(){
    	return $this->belongsTo('App\Employee','created_by');
    }

    public function bankdetail(){
    	return $this->belongsTo('App\Bank','bank_id');
    }
}
