<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EmployeeRole;
class EmployeeRole extends Model
{
    //
    public static function checkAccess($moduleid,$empid){
    	$view = ""; $edit = ""; $delete = "";
    	$checkAccess = EmployeeRole::where(['module_id'=>$moduleid,'emp_id'=>$empid])->first();
    	if($checkAccess){
    		if($checkAccess->view_access ==1){
    			$view = "checked";
    		}
    		if($checkAccess->edit_access == 1){
    			$edit = "checked";
    		}
    		if($checkAccess->delete_access == 1){
    			$delete = "checked";
    		}
    	}
    	$access = array('view' =>$view,'edit' => $edit,'delete' =>$delete);
    	return $access;
    }
}
