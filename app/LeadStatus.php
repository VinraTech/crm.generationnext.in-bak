<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use App\Employee;
class LeadStatus extends Model
{
    //

    public static function getstatuscount($type){
        $access = Employee::checkAccess();
		$getcount = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->join('allocate_leads','allocate_leads.lead_id','=','leads.id')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status','allocate_leads.allocate_to')->whereExists( function ($query)  {
                    $query->from('allocate_leads')
                    ->whereRaw('leads.id = allocate_leads.lead_id');
                });
    	if($type=="refer"){
    		if($access=="false"){
                $getcount = $getcount->where('allocate_leads.allocate_by',Session::get('empSession')['id'])->where('allocate_leads.is_refer','yes');
            }else{
                $getcount = $getcount->where('allocate_leads.is_refer','yes');
            }
    	}elseif($type=="active"){
    		$getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Inactive','Closed'])->get();
            $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
            $getcount = $getcount->whereNotIn('leads.last_status',$getleadstatusids);
            if($access=="false"){
                $getEmployees = LeadStatus::getEmployees(Session::get('empSession')['id']);
                $getcount = $getcount->whereIn('allocate_leads.allocate_to',$getEmployees);
            }else{
                $getcount = $getcount->where('allocate_leads.is_refer','no');
            }
    	}
    	$getcount = $getcount->count();
    	return $getcount;
    }

    public static function getEmployees($empid){
        $employeedetails = Employee::where('id',$empid)->first();
        $employeedetails =json_decode(json_encode($employeedetails),true);
        if($employeedetails['parent_id'] =="ROOT"){
            $getdetails = Employee::with(['getemps'=>function($query){
                $query->with('getemps');
            }])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            $empids[]  = $getdetails['id']; 
                foreach($getdetails['getemps'] as $level1){
                    $empids[]  = $level1['id'];
                    foreach ($level1['getemps'] as $key => $level2) {
                        $empids[] =$level2['id'];
                    }
                }
        }else{
            $getdetails = Employee::with(['getemps'])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            if(!empty($getdetails['getemps'])){
                $empids[]  = $getdetails['id']; 
                foreach($getdetails['getemps'] as $level1){
                    $empids[]  = $level1['id'];
                }
            }else{
                $empids[]  = $getdetails['id'];
            }
        }
        return $empids;
    }
}
