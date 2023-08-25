<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Module;
use DB;
use Session;
class ModuleController extends Controller
{
    //
    public static function getModules(){
    	if(Session::get('empSession')['type']=="admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro"){
    		$allModules = Module::with('undermodules')->where(['parent_id'=>'ROOT','status'=>1])->orderBy('sortorder','asc')->get();
            $allModules = json_decode(json_encode($allModules),true);
            return $allModules;
    	}else{
            $getEmpModules = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'view_access'=>'1'])->select('module_id')->get();
            $getEmpModules = array_flatten(json_decode(json_encode($getEmpModules),true));
            $allModules = Module::with(['undermodules'=>function($query) use($getEmpModules){
                $query->whereIn('id',$getEmpModules);
            }])->where(['parent_id'=>'ROOT','status'=>1])->orderBy('sortorder','asc')->get();
            $allModules = json_decode(json_encode($allModules),true);
    		/*$allModules = Module::whereIn('id',$getEmpModules)->where('status',1)->orderby('sortorder','ASC')->get();
	    	$allModules = json_decode(json_encode($allModules),true);*/
            //dd($allModules);
	    	return $allModules;
    	}
    }
}
