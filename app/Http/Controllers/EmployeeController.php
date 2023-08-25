<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Employee;
use DB;
use Cookie;
use Session;
use Crypt;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\EmployeeRole;
use App\EmployeeType;
use App\Module;
use App\EmployeeProduct;
use App\Designation;
use App\EmployeeTarget;
use Image;
use Carbon;

class EmployeeController extends Controller
{

    public function employees(Request $Request){
       
        Session::put('active',1); 
        if($Request->ajax()){
            $conditions = array('is_trash'=>'no');
            $data = $Request->input();
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = DB::table('employees')->where('id','!=',1);
            
            if(!empty($data['name'])){
                $querys = $querys->where('name','like','%'.$data['name'].'%');
            }
            if(!empty($data['email'])){
                $querys = $querys->where('email','like','%'.$data['email'].'%');
            }
            if(!empty($data['reporting_person'])){
                $querys = $querys->where('parent_id',$data['reporting_person']);
            }
            $access = Employee::checkAccess();
            if($access=="false"){
                if(Session::get('empSession')['type'] !="accountant"){
                    $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                    $querys = $querys->whereIn('employees.id',$getEmployees);
                }
            }
            $querys = $querys->OrderBy('id','DESC');
            
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
            }else{
                //$querys = $this->getemployeedata();
				if(Session::get('empSession')['is_access'] == 'limited'){
					$team_id = Session::get('empSession')['team_id'];
					$querys = DB::table('employees')->where('id','!=',1)->where('team_id',$team_id);
				}else{
					$eng_id_list = $this->getemployeedataList();
					$eng_id_list=json_decode( json_encode($eng_id_list), true);
					$eng_id_array = array_column($eng_id_list, 'id');
					$querys = DB::table('employees')->where('id','!=',1)->wherein('id',$eng_id_array);
				}
				 
                 $em_name = array();
                 $em_mail = array();
                 $em_code = array();
                 if(!empty($data['name'])){
                   $querys = $querys->where('name','like','%'.$data['name'].'%');
                 }
                 if(!empty($data['email'])){
                   $querys = $querys->where('email','like','%'.$data['email'].'%');
                 }
				 if(!empty($data['emp_code'])){
                   $querys = $querys->where('emp_code','like','%'.$data['emp_code'].'%');
                 }
                 if(!empty($data['reporting_person'])){
                    $querys = $querys->where('parent_id',$data['reporting_person']);
                }
				
				$iTotalRecords = $querys->count();
                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayStart = intval($_REQUEST['start']);
				$querys =  $querys
                	->skip($iDisplayStart)->take($iDisplayLength)
                	->get();
				$querys=json_decode( json_encode($querys), true);
		
                $sEcho = intval($_REQUEST['draw']);
                $records = array();
                $records["data"] = array(); 
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $i=$iDisplayStart;
            }
                  $modules = DB::table('modules')->where('name','Employees')->first();
                  $modules = json_decode(json_encode($modules),true);

                  $emp_roles = DB::table('employee_roles')->where('emp_id',Session::get('empSession')['id'])->where('module_id',$modules['id'])->get();
                   $emp_roles = json_decode(json_encode($emp_roles),true);
                   
            foreach($querys as $emp){ 
                $id= base64_encode(convert_uuencode($emp['id'])); 
                $checked='';
                if($emp['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                if(Session::get('empSession')['type']=="admin" || Session::get('empSession')['type']=="bm"){
                    $employeerole ='
                    <a target="_blank" title="Set Employee Target"  class="btn btn-sm blue margin-top-10 delete"  href="'.url('s/admin/add-employee-target/'.$emp['id']).'" > <i class="fa fa-plus"></i>
                    </a>
                    <a  title="Update Employee Role" class="btn btn-sm yellow margin-top-10" href="'.url('s/admin/update-role/'.$emp['id']).'"> <i class="fa fa-unlock-alt"></i>
                    </a>';
                }else{
                    $password = "";
                    $employeerole ='';
                }
                if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
                $actionValues='<a title="View Employee Details" class="btn btn-sm blue margin-top-10 getEmpid" href="javascript:;" id='.$emp['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Employee" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-employee/'.$emp['id']).'"> <i class="fa fa-edit"></i>
                    </a>'.$employeerole.'
                    <a style="display:none;" title="Delete Employee"  class="btn btn-sm red margin-top-10 delete" onclick=" return ConfirmDelete()" href="'.url('s/admin/delete-employee/'.$emp['id']).'" > <i class="fa fa-times"></i>
                    </a>';
                }else{
                    foreach($emp_roles as $emp_role){
                        if($emp_role['edit_access'] == "1" && $emp_role['delete_access'] == "0"){
                           $actionValues='<a title="View Employee Details" class="btn btn-sm blue margin-top-10 getEmpid" href="javascript:;" id='.$emp['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Employee" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-employee/'.$emp['id']).'"> <i class="fa fa-edit"></i>
                    </a>';
                        }elseif($emp_role['edit_access'] == "0" && $emp_role['delete_access'] == "1"){
                            $actionValues='<a title="View Employee Details" class="btn btn-sm blue margin-top-10 getEmpid" href="javascript:;" id='.$emp['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Employee" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-employee/'.$emp['id']).'"> <i class="fa fa-edit"></i>
                    </a>
                    <a title="Delete Employee"  class="btn btn-sm red margin-top-10 delete" onclick=" return ConfirmDelete()" href="'.url('s/admin/delete-employee/'.$emp['id']).'" > <i class="fa fa-times"></i>
                    </a>';
                        }elseif($emp_role['edit_access'] == "1" && $emp_role['delete_access'] == "1"){
                            $actionValues='<a title="View Employee Details" class="btn btn-sm blue margin-top-10 getEmpid" href="javascript:;" id='.$emp['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Employee" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-employee/'.$emp['id']).'"> <i class="fa fa-edit"></i>
                    </a>
                    <a title="Delete Employee"  class="btn btn-sm red margin-top-10 delete" onclick=" return ConfirmDelete()" href="'.url('s/admin/delete-employee/'.$emp['id']).'" > <i class="fa fa-times"></i>
                    </a>';
                }else{
                    $actionValues='';
                    }
                }
                }
                $getEmpType = DB::table('employee_types')->where('short_name',$emp['type'])->first();
                $num = ++$i;
                $parent_user = Employee::where('id',$emp['parent_id'])->first();
                $parent_user = json_decode(json_encode($parent_user),true);
                // echo $emp['parent_id'];
                // dd($parent_user);
                $records["data"][] = array(     
                    $num,
                    $emp['name'],
                    $emp['email'],
                    $parent_user['name'],
                    $getEmpType->full_name,
                    '<div  id="'.$emp['id'].'" rel="employees" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>',   
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Employees - Express Paisa";
        return View::make('admin.employees.employees')->with(compact('title'));
    }

    public function getEmpDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $employeedetails = Employee::with('getproducts')->where('id',$data['id'])->first();
            $destination = public_path('/images/AdminImages/');
            $empimage = '';
            if(!empty($employeedetails->image) && file_exists($destination.$employeedetails->image)){
                $empimage = '<tr>
                        <td width="40%">Image</td>
                        <td width="600%"><img width="80px" src="'.asset('/images/AdminImages/'.$employeedetails->image).'"</td>
                    </tr>';
            }
            $empproducts="";
            if(count($employeedetails->getproducts) !=0){
                foreach($employeedetails->getproducts as $key => $product){
                    $empproducts .=$product['productdetail']['name'].", ";
                }
            }
            $getTeamdetails = $this->checkTeam($employeedetails->id);
            $teamdetails ="";
            if(!empty($getTeamdetails)){
                $teamdetails = '<tr>
                        <td width="40%">Team</td>
                        <td width="600%">'.$getTeamdetails.'</td>
                    </tr>';
            }
            $getEmpType = $this->empTypeFullname($employeedetails->type);
            echo '<tr>
                        <td width="40%">Name</td>
                        <td width="60%">'.$employeedetails->name.'</td>
                    </tr>'.$empimage.
                    '<tr>
                        <td width="40%">State</td>
                        <td width="60%">'.$employeedetails->state.'</td>
                    </tr>
                    <tr>
                        <td width="40%">City</td>
                        <td width="60%">'.$employeedetails->city.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Employee Type</td>
                        <td width="60%">'.$getEmpType.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Employee Code</td>
                        <td width="60%">'.$employeedetails->emp_code.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Date of Birth</td>
                        <td width="60%">'.date('d F Y',strtotime($employeedetails->dob)).'</td>
                    </tr>
                    <tr>
                        <td width="40%">Email</td>
                        <td width="600%">'.$employeedetails->email.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Mobile</td>
                        <td width="600%">'.$employeedetails->mobile.'</td>
                    </tr>
                     <tr>
                        <td width="40%">Address</td>
                        <td width="600%">'.$employeedetails->address.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Products</td>
                        <td width="600%">'.$empproducts.'</td>
                    </tr>'.$teamdetails.'';
        }
    }

    public function addeditEmployee(Request $request, $id=null){

    	if($id ==""){
            $message ='Employee has been added successfully!';
    		$title = "Add Employee - Express Paisa"; 
    		$employee = new Employee;
    		$employeedata = array();
            $getEmpProducts = array();
            $empPids = array();
            $cities = array(); 
    	}else{ 
            $message = 'Employee has been updated successfully!';
    		$title = "Edit Employee -  Express Paisa"; 
    		$employeedata = DB::table('employees')->where('id',$id)->first();
    		$employeedata = json_decode(json_encode($employeedata),true);
    		$employee = Employee::find($id);

            $getEmpProducts = DB::table('employee_products')->where('emp_id',$id)->get();
            $getEmpProducts =json_decode(json_encode($getEmpProducts),true);
            $empPids = array_map(function ($ar) {return $ar['product_id'];}, $getEmpProducts);
            if(!empty($employeedata['state'])){
                $getstateid = DB::table('states')->select('id')->where('state',$employeedata['state'])->first();
                $cities = $this->cities($getstateid->id);
            }
    	}
    	if($request->isMethod('post')){
    		$data = $request->all();
            // dd($id);
            //dd($data);
			
            $b_name = implode(',', $data['bank_name']);

            $acc = implode(',', $data['account_no']);
            $ifcode = implode(',', $data['ifsc_code']);
            unset($data['_token']);
            $empPoducts = $data['products'];
            unset($data['products']); 
    		foreach ($data as $key => $value) {
    			if($key != "password"){
					
					$employee->$key = $value;
    			}
    		}
			if($data['parent_id'] == 'ROOT' && $id == ''){
				$employee->team_id = $this->getEmployeeTeamId();
			}else{
				if($data['parent_id'] == 'ROOT'){
					
				}else{
					$get_team_id =  DB::table('employees')->where('id',$data['parent_id'])->select('team_id')->first();
					$employee->team_id = $get_team_id->team_id;
				}
			}
			
			
    		if(empty($employeedata)){
                $getlastEmpid = DB::table('employees')->where('type','!=','admin')->orderby('id','desc')->select('emp_code')->first();
                $getlastEmpid = json_decode(json_encode($getlastEmpid),true);
                if(!empty($getlastEmpid)){
                    $getcode = $getlastEmpid['emp_code'];
                    $getcode= ltrim ($getcode,'GN');
                    $code = (int)$getcode+1;
                    $empcode = "GN".$code;
                }else{
                    $empcode = "GN101"; 
                }
                $employee->emp_code = $empcode;
    			$employee->status = 1;
                $employee->password = md5($data['password']);
    			$employee->decrypt_password = $data['password'];
    		}
            if(!empty($employeedata)){
                $employee->password = md5($data['password']);
                $employee->decrypt_password = $data['password'];
            }
            if($request->hasFile('image')){
                if (Input::file('image')->isValid()) {
                    $file = Input::file('image');
                    $img = Image::make($file);
                    $destination = public_path('/images/AdminImages/');
                    if(!empty($employeedata) &&  $employeedata['image'] !="" && file_exists($destination.$employeedata['image'])){
                        unlink($destination.$employeedata['image']);
                    }
                    $ext = $file->getClientOriginalExtension();
                    $mainFilename = "passport-".str_random(5).date('h-i-s').".".$ext;
                    $img->save($destination.$mainFilename);
                    $employee->image= $mainFilename;
                }
            }
            $data['doj'] = date('Y-m-d', strtotime($request->doj));
            $data['dob'] = date('Y-m-d', strtotime($request->dob));
            $employee->doj = $data['doj'];
            $employee->dob = $data['dob'];
            $employee->pan = $data['pan'];
            $employee->adhaar_no = $data['adhaar_no'];
            
            $employee->bank_name = $b_name;
            $employee->account_no = $acc;
            $employee->ifsc_code = $ifcode;
            $employee->monthly_salary = $data['monthly_salary'];
            $employee->pcc = $data['pcc'];
            $employee->email = (isset($data['email']))?$data['email']:'';
            $employee->blood_group = $data['blood_group'];
            $employee->emergency_number = $data['emergency_number'];
            $employee->medical_status = $data['medical_status'];
            $employee->is_access = $data['is_access'];
    		$employee->save();
            if($id==""){
                $empid = DB::getPdo()->lastInsertId();
                if($this->mode=="live"){
                    $email = $data['email'];
                    $messageData = [
                        'empcode' => $empcode,
                        'empname' => $data['name'],
                        'empemail' => $email,
                        'password'  => $data['password'],
                        'type' => $this->empTypeFullname($data['type']),
                    ];
                    // Mail::send('emails.employee-register-email', $messageData, function($message) use ($email){
                    //     $message->to($email)->subject("Account created successfully");
                    // }); 
                }
            }else{
                $empid = $id;
            }
            if(!empty($getEmpProducts)){
                DB::table('employee_products')->where('emp_id',$id)->delete();
            }
            foreach($empPoducts as  $eproduct){
                $product = new EmployeeProduct;
                $product->emp_id = $empid;
                $product->product_id = $eproduct;
                $product->save();
            }
    		return redirect()->action('EmployeeController@employees')->with('flash_message_success',$message);
    	}
        $getemptypes = $this->getemptypes();
       
       /* $getTeamLevels = $this->geteamLevels(); */
        $getTeamLevels = $this->getEngTeamLevels(Session::get('empSession')['id']);
         
		
		$EngLevel = $this->getEmpLevel();
		
    	$getproducts = $this->getproducts();
        $states = $this->states();
        $designationdetails = $this->desigdata();
        
        return view('admin.employees.add-edit-employee')->with(compact('title','employeedata','EngLevel','getemptypes','getTeamLevels','getproducts','empPids','states','cities','designationdetails'));
    }

    public function deleteEmployee($id){
        DB::table('employees')->where('id',$id)->update(['is_trash'=>'yes']);
    	return redirect()->action('EmployeeController@employees')->with('flash_message_success','Record has been deleted successfully!');
    }

    public function checkEmployeeEmail(Request $request) {
        $data = $request->all();
        $empEmail = $data['email'];
        $checkEmail = DB::table('employees')
                       ->where('email', $empEmail)
                       ->count();
        if($checkEmail == 1) {
             echo '{"valid":false}';die;;
        }else {
        	echo '{"valid":true}';die;
        }
    }

    public function updateRole(Request $request,$id){
        $getModules = Module::where('shown_in_roles','1')->get();
        $getModules = json_decode(json_encode($getModules),true);
        $employeeid = $id;
        $getRoleDetails = EmployeeRole::where('emp_id',$employeeid)->get();
        $getRoleDetails = json_decode(json_encode($getRoleDetails),true);
        if($request->isMethod('post')){
            $data = $request->all();

            foreach ($data['module_id'] as $mkey => $module) {
                $checkIfExists = EmployeeRole::where(['emp_id'=>$id,'module_id'=>$mkey])->first();
                if(!empty($checkIfExists)){
                    $emprole = EmployeeRole::find($checkIfExists->id);
                }else{
                    $emprole = new EmployeeRole;
                    $emprole->emp_id = $id;
                    $emprole->module_id = $mkey;
                }
                // dd($data['module_id'][$mkey]);
                if(is_array($data['module_id'][$mkey])){

                    foreach ($data['module_id'][$mkey] as $akey => $value) {
                        if(isset($data['module_id'][$mkey]['view_access'])){
                            $emprole->$akey = 1;
                        }else{
                            $emprole->view_access = 0;
                        }
                        if(isset($data['module_id'][$mkey]['edit_access'])){
                            $emprole->$akey = 1;
                        }else{
                            $emprole->edit_access = 0;
                        }
                        if(isset($data['module_id'][$mkey]['delete_access'])){
                            $emprole->$akey = 1;
                        }else{
                            $emprole->delete_access = 0;
                        }
                    }
                }else{
                    $emprole->view_access = 0;
                    $emprole->edit_access = 0;
                    $emprole->delete_access = 0;
                }
                $emprole->save();

            }
            return redirect()->back()->with('flash_message_success','Employee Roles Updated Successfully!');
        }
        $title = "Update Employee Role - Express Paisa";
        return view('admin.employees.update-roles')->with(compact('title','employeeid','getRoleDetails','getModules'));
    }

    public function getCities(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $getcities = DB::table('cities')->where('state_id',$data['stateid'])->get();
            $getcities = json_decode(json_encode($getcities),true);
            $cities = '<option value="">Select</option>';
            foreach($getcities as $key => $city){
                $cities .= '<option value="'.$city['city'].'">'.$city['city'].'</option>';
            }
            print_r($cities);
        }
    }

    public function verifyEmpemail(Request $request) {
        $data = $request->all();
        $empEmail = $data['email'];
        $checkEmail = DB::table('employees')
                       ->where('email', $empEmail)
                       ->count();
        if($checkEmail == 0) {
             echo '{"valid":false}';die;;
        }else {
            echo '{"valid":true}';die;
        }
    }

    public function resetPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
			if($this->mode=="live"){
				 $randompassword = str_random(6);
			}else{
				 $randompassword = '123456';
			}
           
            $encryptPassword = md5($randompassword);
            Employee::where('email',$data['email'])->update(['password'=>$encryptPassword,'decrypt_password'=>$randompassword]);
            $getempdetails = Employee::where('email',$data['email'])->first();
            $getempdetails = json_decode(json_encode($getempdetails),true);
            $email = $data['email'];
            if($this->mode=="live"){
                $messageData = [
                    'empdetails' => $getempdetails,
                    'password' => $randompassword,
                ];
                Mail::send('emails.reset-password-email', $messageData, function($message) use ($email){
                    $message->to($email)->subject("Password Reset successfully");
                }); 
            }
            return redirect()->action('AdminController@login')->with('flash_message_success','Password has been reset successfully and sent to your email. If not received in Inbox donot forget to check Spam folder');
        }
    }

    public function addEmpTarget(Request $request, $empid){
        $title ="Employee Target";
        if($request->isMethod('post')){
            $data = $request->all();
            foreach($data['months'] as $mkey => $month){
                $explodeMonth = explode('-',$month);
                $checkifexists = DB::table('employee_targets')->where(['employee_id'=>$empid,'year'=>$data['year'],'month'=>$explodeMonth[0]])->select('id')->first();
                if($checkifexists){
                    $target = EmployeeTarget::find($checkifexists->id); 
                }else{
                    $target = new EmployeeTarget; 
                }
                $target->employee_id = $empid;
                $target->year = $data['year'];
                $target->month =  $explodeMonth[0];
                $target->month_name =  $explodeMonth[1];
                $target->target =  $data['target'][$mkey];
                $target->description =  $data['description'][$mkey];
                $target->save();
            }
            return redirect()->back()->with('flash_message_success','Targets has been set successfully');
        }
        $empdetails = Employee::where('id',$empid)->first();
        $empdetails = json_decode(json_encode($empdetails),true);
        /*echo "<pre>"; print_r($empdetails); die;*/
        return view('admin.employees.add-employee-target')->with(compact('title','empdetails'));
    }

    public function viewDesignation(Request $Request){
          Session::put('active',52); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();

            $querys = DB::table('employee_types');
            if(!empty($data['designation_name'])){
                $querys = $querys->where('employee_types.full_name','like','%'.$data['designation_name'].'%');
            }
            $querys = $querys->OrderBy('employee_types.id','Desc');
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
            // dd($querys);
            foreach($querys as $designation){ 
                
                $actionValues='<a title="Edit Designation" class="btn btn-sm green margin-top-10" href="'.url('/s/admin/add-edit-designation/'.$designation['id']).'"> <i class="fa fa-edit"></i>';
                $deleteFile= '<a title="Delete Designation" onclick=" return ConfirmDelete()" class="btn btn-sm margin-top-10 red" href="'.url('/s/admin/delete-designation/'.$designation['id']).'"><i class="fa fa-times"></i></a>';
                $num = ++$i;
                $records["data"][] = array(     
                    $num,
                    $designation['full_name'],  
                    $actionValues.$deleteFile
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        return view('admin.designation.designation');

    }

    public function addeditDesignation(Request $request,$id=null){
          
         if($id ==""){
              $title = "Add Designation - Express Paisa";
              $getdesignationdetails = array();
              $message ="Designation has been added successfully!";
         }else{
               $title = "Edit Designation - Express Paisa";
               $getdesignationdetails = DB::table('employee_types')->where('id',$id)->first();
               $getdesignationdetails = json_decode(json_encode($getdesignationdetails),true);

               $message ="Designation has been updated successfully!";
         }
         if($request->isMethod('post')){
            $data = $request->all();
             
             unset($data['_token']);
              
            if($getdesignationdetails){
                 
                $designation = EmployeeType::find($id);

            }else{

                $designation = new EmployeeType;
            }
           
            
           
            $designation->full_name = $data['full_name'];
            $designation->short_name = $data['short_name'];
            $designation->status = 1;
            // $designation->file_action = $data['file_action'];
            
            
            $designation->save();
            return Redirect()->action('EmployeeController@viewDesignation')->with('flash_message_success',$message);
        }
        return view('admin.designation.add-edit-designation')->with(compact('title','getdesignationdetails'));
    }

    public function deleteDesignation($id){

        EmployeeType::where('id',$id)->delete();
        return redirect()->action('EmployeeController@viewDesignation')->with('flash_message_success','Record has been deleted successfully!');
    }
	
	 public function empTeamId(){
		 $employees =  Employee::where('parent_id','ROOT')->where('team_id',null)->get()->toArray();
		 foreach($employees as $employee){ 
			 $eng_id_list = [];
			 $getEmployees = Employee::select('id')->with(['getemps'=>function($query){
				$query->with('getemps');
			 }])->where('parent_id','ROOT')->where('id',$employee['id'])->get();
			 $getEmployees = json_decode(json_encode($getEmployees),true);
							 
							 foreach($getEmployees as $level){ 
							 array_push($eng_id_list, $level['id']);
							
							 foreach($level['getemps'] as $skey => $sublevel1){ 
								array_push($eng_id_list, $sublevel1['id']);
								
								foreach($sublevel1['getemps'] as $sskey=> $sublevel2){ 
									array_push($eng_id_list, $sublevel2['id']);
									$getdetails = Employee::select('id')->with(['getemps'=>function($query){

													 $query->with('getemps');

												 }])->where('id',$sublevel2['id'])->first();

												 $getdetails = json_decode(json_encode($getdetails),true);
												
									foreach($getdetails['getemps'] as $ssskey=> $sublevel3){
										array_push($eng_id_list, $sublevel3['id']);
										$getdetails = Employee::select('id')->with(['getemps'=>function($query){

													 $query->with('getemps');

												 }])->where('id',$sublevel3['id'])->first();

												 $getdetails = json_decode(json_encode($getdetails),true);
												 foreach($getdetails['getemps'] as $ssskey=> $sublevel4){
													 array_push($eng_id_list, $sublevel4['id']);
												 }
									}
								}
							 }			 
						}
						
						if(!empty($eng_id_list)){
							$team_id = $this->getEmployeeTeamId();
							
							Employee::wherein('id',$eng_id_list)->update(['team_id'=>$team_id]);
						}
						echo 'Employee id'.$employee['id'].' -----------'.'team_id = '.$team_id.'<br>';
						
						
		 }
	 }



}
