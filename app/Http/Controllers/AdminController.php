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
use App\Module;
use App\File;
use App\EmployeeRole;
use App\FileApproval;
use DateTime;
class AdminController extends Controller
{
    public function status(Request $request){
        if(Session::has('empSession')){
            if($request->ajax()){
                $data = $request->input();
                if(DB::table($data['table'])->where('id', $data['id'])->update(['status' => $data['status'] ]) ){
                    echo "1";die;
                } else {
                    echo "0";die; 
                }
            }
        }
        else{
            return redirect()->action('AdminController@login')->with('flash_message_error', 'Kindly login first');
        }
    }

    public function login(Request $request){
        if(Session::has('empSession')){
           return redirect()->action('AdminController@dashboard');
        }
        if($request->isMethod('post')){
            $this->validate($request, [
                'email'=>'required',
                'password'=>'required'
            ]);
            $userdata = $request->all();
            $userpassword['password'] = md5($userdata['password']);
            $admin  = DB::table('employees')
                    ->where('email', $userdata['email'])
                    ->where('password', $userpassword['password'])
                    ->where('is_trash','no')
                    ->where('status',1)
                    ->first();
            if(!empty($admin)){
                if(isset($userdata['remember'])){
                    $cookieData = $userdata;
                    unset($userdata['remember']);
                    Cookie::queue('rememberMe',json_encode($cookieData),(86400 * 30));
                }else{
                    Cookie::queue(\Cookie::forget('rememberMe'));
                }
                $empSession = json_decode( json_encode($admin), true);
                Session::put('empSession', $empSession);
                if(Session::get('empSession')['type'] !="admin"){
                    $getAllModuleIds = Module::select('id')->get();
                    $getAllModuleIds = array_flatten(json_decode(json_encode($getAllModuleIds),true));
                    foreach ($getAllModuleIds as $key => $modid) {
                        $CheckifExists = EmployeeRole::where(['emp_id'=>Session::get('empSession')['id'], 'module_id'=>$modid])->count();
                        if($CheckifExists ==0){
                            $emprole = new EmployeeRole;
                            $emprole->emp_id =  Session::get('empSession')['id'];
                            $emprole->module_id = $modid;
                            if($modid == 7){
                                $emprole->view_access = 1;
                                $emprole->edit_access = 1;
                                $emprole->delete_access = 0;
                            }
                            if($modid == 5){
                                $emprole->view_access = 1;
                                $emprole->edit_access = 1;
                                $emprole->delete_access = 0;
                            }
                            $emprole->save();
                        }
                    }
                }
                DB::table('employees')
                ->where('id', Session::get('empSession')['id'])
                ->update(['last_login' => date('Y:m:d h:i:s')]);
                if(Session::has('leadid')){
                    return redirect::to('s/admin/update-lead-status/'.Session::get('leadid'));
                }else{
                    return redirect()->action('AdminController@dashboard');
                }
            }   
            else{
                return redirect()->action('AdminController@login')->with('flash_message_error', 'Your email or password is incorrect, please enter correct value');
            }
        }else{
            if(Cookie::get('rememberMe')){
                $stayTuned = Cookie::get('rememberMe');
                return View::make('admin.admin_login')->with('stayTuned',$stayTuned);
            }
            else{
                return view('admin.admin_login');
            }
        } 
    }

    public function checkAdminEmail(Request $request) {
        $data = $request->all();
        $email = $data['email'];
        $check_email = DB::table('employees')
                       ->where('email', $email)
                       ->first();
        $count = count($check_email);
        if($count == 1) {
            echo '{"valid":true}';die;
        } else {
            echo '{"valid":false}';die;;
        }
    }

    public function dashboard(){
        Session::put('active','dashboard');
        $title = "Dashboard - Express Paisa";
        $access = Employee::checkAccess();
        if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $getModules = DB::table('modules')->where('table_name','!=','')->select('id','name','view_route','table_name','icon')->where('dashboard_status',1)->orderBy('dashboardsort','ASc')->get();
        }else{
            $getEmpModules = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'view_access'=>'1'])->select('module_id')->get();
            $getEmpModules = array_flatten(json_decode(json_encode($getEmpModules),true));
            $getModules = DB::table('modules')->whereIn('id',$getEmpModules)->where('table_name','!=','')->select('id','name','view_route','table_name','icon')->where('dashboard_status',1)->orderBy('dashboardsort','ASc')->get();
        }
        $getModules = json_decode(json_encode($getModules),true);
        foreach ($getModules as $key => $module) {
            if($access=="true"){
                if($module['table_name'] =="unallocate"){
                    $getModules[$key]['table_count'] =  DB::table('leads')->whereNotExists( function ($query)  {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id');
                    })->count();
                }elseif($module['table_name'] =="allocated"){
                    $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Closed','Inactive'])->get();
                    $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                    $getModules[$key]['table_count'] =  DB::table('leads')->whereNotin('last_status',$getleadstatusids)->whereExists( function ($query)  {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id');
                    })->count();
                }elseif($module['table_name'] =="closed" || $module['table_name'] =="inactive" ){
                    $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',[$module['table_name']])->get();
                    $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                    $getModules[$key]['table_count'] =  DB::table('leads')->wherein('last_status',$getleadstatusids)->whereExists( function ($query)  {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id');
                    })->count();
                }elseif($module['table_name'] =="employees"){
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->where('type','!=','admin')->count();
                }elseif($module['table_name'] =="login" || $module['table_name'] =="operations" || $module['table_name'] =="bank" || $module['table_name'] =="disbursement" || $module['table_name'] =="approved" || $module['table_name'] =="partially" ){
                    $getModules[$key]['table_count'] =  DB::table('files')->where('move_to',$module['table_name'])->count();
                }elseif($module['table_name'] =="pending" ){
                    //$getModules[$key]['table_count'] =  DB::table('file_approvals')->where('status','not approved')->count();
                    $getModules[$key]['table_count'] = FileApproval::join('files','files.id','=','file_approvals.file_id')->join('clients','clients.id','=','files.client_id')->join('employees','employees.id','=','file_approvals.approval_from')->select('file_approvals.*','files.file_no','files.id as fileid','files.facility_type','clients.customer_name','clients.company_name','employees.name as empname')->count();
                }else{
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->count();
                }
            }else{
                /* $getEmployees = $this->getEmployees(Session::get('empSession')['id']); */
                $getEmployees = $this->getemployeedataList();
				$getEmployees=json_decode( json_encode($getEmployees), true);
				$getEmployees = array_column($getEmployees, 'id');
				if($module['table_name'] =="leads"){
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->where('source',Session::get('empSession')['id'])->count();
                }elseif($module['table_name'] =="employees"){
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->wherein('id',$getEmployees)->count();
                }elseif($module['table_name'] =="unallocate"){
                    $getModules[$key]['table_count'] =  DB::table('leads')->whereNotExists( function ($query)  {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id');
                    })->whereIn('leads.source',$getEmployees)->count();
                }elseif($module['table_name'] =="allocated"){
                    $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Closed','Inactive'])->get();
                    $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                    $getModules[$key]['table_count'] =  DB::table('leads')->whereNotin('last_status',$getleadstatusids)->whereExists( function ($query) use($getEmployees) {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id')
                        ->whereIn('allocate_leads.allocate_to',$getEmployees);
                    })->count();
                }elseif($module['table_name'] =="closed" || $module['table_name'] =="inactive"){
                    $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',[$module['table_name']])->get();
                    $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                    $getModules[$key]['table_count'] =  DB::table('leads')->wherein('last_status',$getleadstatusids)->whereExists( function ($query) use($getEmployees) {
                        $query->from('allocate_leads')
                        ->whereRaw('leads.id = allocate_leads.lead_id')
                        ->whereIn('allocate_leads.allocate_to',$getEmployees);
                    })->count();
                }elseif($module['table_name'] =="channel_partners"){
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->whereIn('emp_id',$getEmployees)->count();
                }elseif($module['table_name'] =="clients"){
                    //$getModules[$key]['table_count'] =  DB::table($module['table_name'])->whereIn('sale_officer',$getEmployees)->count();
                    $getModules[$key]['table_count'] =  count($this->getclientdata());
                }elseif($module['table_name'] =="login" || $module['table_name'] =="operations" || $module['table_name'] =="bank" || $module['table_name'] =="disbursement" || $module['table_name'] =="approved"|| $module['table_name'] =="partially"){

                    // $getModules[$key]['table_count'] =  File::has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                    //         $query->where('employee_id',Session::get('empSession')['id']);
                    //     })->where('move_to',$module['table_name'])->count();
                    $getModules[$key]['table_count'] =  count($this->getfiledataList($module['table_name']));
                }elseif($module['table_name'] =="pending" ){
                    // $getModules[$key]['table_count'] =  DB::table('file_approvals')->where('employee_id',Session::get('empSession')['id'])->where('status','not approved')->count();
                    $getModules[$key]['table_count'] =  count($this->pendingfiledataList());
                }else{
                    $getModules[$key]['table_count'] =  DB::table($module['table_name'])->count();
                }

            }
        }
		$this->getfiledataList('approved');
		if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['is_access']!="full"){
			$approved_file_count = count($this->partdisbdata());
        }
		$countModules = count($getModules);
        return view('admin.admin_dashboard')->with(compact('title','getModules','approved_file_count'));
    }

    public function profile(Request $request){
        if(Session::has('empSession')){
            Session::put('active',3);
            $admindata = DB::table('employees')->where('id', Session::get('empSession')['id'])->first();
            $admindata=json_decode( json_encode($admindata), true);
            $title = "Profile - Express Paisa";
            return view('admin.profile', ['admindata'=>$admindata,'title'=>$title]);
        } else{
            return redirect()->action('AdminController@login')->with('flash_message_error', 'Please login');
        }
    }

    public function logout(){
        DB::table('employees')->where('id',Session::get('empSession')['id'])->update(['last_login' => date('Y-m-d h:i:s')]);
        Session::forget('empModuleDetails');
        Session::forget('empRoleDetails');
        Session::forget('empSession');
        Session::forget('leadid');
        return redirect()->action('AdminController@login')->with('flash_message_success', 'Logged out successfully.');
       
    }

    public function settings(Request $request){
        if(Session::has('empSession')){
             Session::put('active',4);
            if($request->isMethod('post')){
                $data = $request->all();
                $update_data = DB::table('employees')
                    ->where('id', Session::get('empSession')['id'])
                    ->update([
                        'name'=>$data['name'],
                        'email'=>$data['email'],
                        'mobile'=>$data['mobile']]);             
            if($update_data){
                return redirect()->action('AdminController@profile')->with('flash_message_success', 'Profile has been updated successfully');        
            } else {
                return redirect()->action('AdminController@profile')->with('flash_message_success', 'Profile has been updated successfully');
                } 
            }
            else{
                $admindata = DB::table('employees')->where('id', Session::get('empSession')['id'])->first();
                $admindata =json_decode( json_encode($admindata), true);
                $title = "Account Settings -  Express Paisa";
                return view('admin.admin_accountSettings', ['admindata'=>$admindata,'title'=>$title]); 
            }
        }
    }

    public function changeAdminLogo(Request $request){
        $admindata = Session::get('empSession');
        $admindata =json_decode( json_encode($admindata), true);
        $image=$_FILES;
        if($image['image']['error']==0){
            $imgName = pathinfo($_FILES['image']['name']);
            $ext = $imgName['extension'];
            $NewImageName = rand(4,10000);
            $destination = base_path() . '/public/images/AdminImages/';
            if(move_uploaded_file($image['image']['tmp_name'],$destination.$NewImageName.".".$ext)){
                if(file_exists($destination.Session::get('empSession')['image']) && !empty(Session::get('empSession')['image'])){
                    
                    unlink($destination.Session::get('empSession')['image']);
                }
                Session::put('empSession.image', $NewImageName.".".$ext);  
                $image =DB::table('employees')
                ->where('id', Session::get('empSession')['id'])
                ->update(['image' => $NewImageName.".".$ext]);
                if(!empty($image)){
                   return redirect()->action('AdminController@profile')->with('flash_message_success', 'Image has been uploaded successfully');         
                } else {
                   return redirect('s/admin/settings/#tab_1_2')->with('flash_message_error', 'You have not Select any image'); 
                }
            }
        }
         else {
            return redirect('s/admin/settings/#tab_1_2')->with('flash_message_error', 'You have not Select any image'); 
        }
    }
    public function checkAdminPassword(Request $request) {
        $data = $request->all();
        $password = $data['password'];
        $check_password = DB::table('employees')
                       ->where('password', md5($password))
                       ->first();
        $check_password = json_decode( json_encode($check_password), true);
        $count = count($check_password);
        
        if($count > 0) {
            echo '{"valid":true}';die;
        } else {
            echo '{"valid":false}';die;;
        }
    }
    
    public function changeAdminPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->input();
            if(!empty($data)){
                if(Session::get('empSession')['password'] == md5($data['password'])){
                    DB::table('employees')
                        ->where('id', Session::get('empSession')['id'])
                        ->update(['password' => md5($data['new_password']),'decrypt_password'=> $data['new_password']]);
                    Session::put('empSession.password', md5($data['new_password']));  
                    return redirect('s/admin/settings/')->with('flash_message_success', 'Password has been updated successfully');   
                }else{
                    return redirect('s/admin/settings/#tab_1_3')->with('flash_message_error', 'please enter correct current password'); 
                }
            }
        }
    }
}