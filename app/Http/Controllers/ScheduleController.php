<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Employee;
use DB;
use Cookie;
use Session;
use Crypt;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Lead;
use App\AllocateLead;
use App\LeadFile;
use Zipper;
use App\LeadThread;
class ScheduleController extends Controller
{
	public function schedules(Request $request){
		$getschedules = array();
		$name = "";
		$date= "";
		if($request->isMethod('post')){
			$data = $request->all();
			$getschedules = LeadThread::with('leaddetail')->where('appoint_date_time','!=','')->where('type','child')->where('emp_id',$data['emp_id'])->whereDate('appoint_date_time',$data['s_date'])->get();
			$getschedules = json_decode(json_encode($getschedules),true);
			$name = $this->empinfowithoutType($data['emp_id']);
			$date= $data['s_date'];
		}else{
			if(Session::get('empSession')['type'] != "admin"){
				$date= date('Y-m-d');
				$name = Session::get('empSession')['name'];
				$getschedules = LeadThread::with('leaddetail')->where('appoint_date_time','!=','')->where('type','child')->where('emp_id',Session::get('empSession')['id'])->whereDate('appoint_date_time',date('Y-m-d'))->get();
				$getschedules = json_decode(json_encode($getschedules),true);
			}
		}
		Session::put('active',8);
		$title ="Employee Schedules - Express Paisa";
		if(Session::get('empSession')['type'] =="admin"){
            $getTeams = $this->geteamLevels();
        }else{
            $getTeams = $this->getteam(Session::get('empSession')['id']);
        }
        return view('admin.schedules.schedules')->with(compact('title','getTeams','getschedules','name','date'));
	}
}
