<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class FileChecklist extends Model
{
    //
    public static function checkSelection($fileid,$checklistid){
    	$checkexists = DB::table('file_checklists')->where(['file_id'=>$fileid,'checklist_id'=>$checklistid])->count();
    	if($checkexists ==1){
    		$ischecked ="checked";
    	}else{
    		$ischecked ="";
    	}
    	return $ischecked;
    }
}
