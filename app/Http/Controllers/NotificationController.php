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
use DateTime;
use App\Notification;
use App\NotificationEmployee;
class NotificationController extends Controller
{
    public function notifications(Request $Request){
        Session::put('active',11); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('notifications');
            if(!empty($data['title'])){
                $querys = $querys->where('notifications.title','like','%'.$data['title'].'%');
            }
            $querys = $querys->OrderBy('notifications.id','Desc');
            $iTotalRecords = $querys->where($conditions)->count();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayStart = intval($_REQUEST['start']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $querys =  $querys->where($conditions)
                    ->skip($iDisplayStart)->take($iDisplayLength)
                    ->get();
            $sEcho = intval($_REQUEST['draw']);
            $records = array();
            $records["data"] = array(); 
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            $i=$iDisplayStart;
            $querys=json_decode( json_encode($querys), true);
            foreach($querys as $notification){ 
            	$checked='';
                if($notification['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                $actionValues='<a title="Edit Status" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-lead-status/'.$notification['id']).'"> <i class="fa fa-edit"></i>';
                $actionValues = '';
                $num = ++$i;
                $records["data"][] = array(     
                    $num,
                    $notification['title'],  
                    $notification['description'],  
                    '<div  id="'.$notification['id'].'" rel="notifications" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Notifications - Express Paisa";
        return View::make('admin.notifications.notifications')->with(compact('title'));
    }

    public function addEditNotification(Request $request,$notifyid=null){
    	if($notifyid==""){
            $title ="Send Notification";
            $message = "Notification Send Successfully!";
            $notification = new Notification;
            $notifydata = array();
        }else{
            $title ="Edit Notification";
            $message = "Notification Send Successfully!";
            $notification = Notification::find($notifyid);
            $notifydata = json_decode(json_encode($notification),true);
        }
        if($request->isMethod('post')){
            $data = $request->all();
            $notification->title = $data['title'];
            $notification->description = $data['description'];
            $notification->created_by = Session::get('empSession')['id'];
            $notification->status = 1;
            $notification->save();
            if($notifyid==""){
                $notificationid = DB::getPdo()->lastInsertId();
            }else{
                $notificationid = $notifyid;
            }
            if(isset($data['emp_ids'])){
                foreach ($data['emp_ids'] as $key => $empid) {
                    $notificationemp = new NotificationEmployee;
                    $notificationemp->emp_id = $empid;
                    $notificationemp->notification_id = $notificationid;
                    $notificationemp->save();
                }
            }
            return Redirect()->action('NotificationController@notifications')->with('flash_message_success','Notification send Successfully!');
        }
        if(Session::get('empSession')['type']=="admin"){
            $geteamLevels = $this->geteamLevels();
        }else{
            $geteamLevels = $this->getteam(Session::get('empSession')['id']);
        }
        return view('admin.notifications.add-edit-notification')->with(compact('title','geteamLevels','notifydata'));
    }

    public function viewNotification($notifyid){
        $getnotifyDetails = Notification::where('id',$notifyid)->first();
        $getnotifyDetails = json_decode(json_encode($getnotifyDetails),true);
        $checkAccess = NotificationEmployee::where(['notification_id'=>$notifyid,'emp_id'=>Session::get('empSession')['id']])->count();
        if($checkAccess !=0 || Session::get('empSession')['type'] =="admin"){
            NotificationEmployee::where(['notification_id'=>$notifyid,'emp_id'=>Session::get('empSession')['id']])->update(['is_view'=>'yes']);
            $title ="View Notification Details";
            return view('admin.notifications.view-notification')->with(compact('title','getnotifyDetails'));
        }
    }
}
