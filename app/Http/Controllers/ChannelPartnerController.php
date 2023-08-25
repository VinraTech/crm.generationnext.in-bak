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
use Image;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Module;
usE App\ChannelPartner;
class ChannelPartnerController extends Controller
{
    
    public function channelpartners(Request $Request){
		//$querys = $this->getpartnerdataList();
		//pd($querys);
        Session::put('active',2); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = DB::table('channel_partners');
            if(!empty($data['name'])){
                $querys = $querys->where('name','like','%'.$data['name'].'%');
            }
            if(!empty($data['email'])){
                $querys = $querys->where('email','like','%'.$data['email'].'%');
            }
            $access = Employee::checkAccess();
            if($access=="false"){
                $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                $querys = $querys->whereIn('channel_partners.emp_id',$getEmployees);
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
            //$querys = $this->getpartnerdata();
            $id_list = $this->getpartnerdataList();
			
			$id_list=json_decode( json_encode($id_list), true);
			$id_array = array_column($id_list, 'id');
			
			$querys = DB::table('channel_partners')->wherein('id',$id_array);
			
			
                $ch_name = array();
                $ch_mail = array();
                
                
                
                if(!empty($data['name'])){
					$querys = $querys->where('name','like','%'.$data['name'].'%');
				}
				if(!empty($data['email'])){
					$querys = $querys->where('email','like','%'.$data['email'].'%');
				}
                
               
                 $iTotalRecords = $querys->count();
                 $iDisplayLength = intval($_REQUEST['length']);
                 $iDisplayStart = intval($_REQUEST['start']);
                 $sEcho = intval($_REQUEST['draw']);
				 $querys =  $querys
                	->skip($iDisplayStart)->take($iDisplayLength)
                	->get();
				 $querys=json_decode( json_encode($querys), true);
                 $records = array();
                 $records["data"] = array(); 
                 $end = $iDisplayStart + $iDisplayLength;
                 $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                 $i=$iDisplayStart;
        }
        
       
                  $modules = DB::table('modules')->where('name','Channel Partners')->first();
                  $modules = json_decode(json_encode($modules),true);

                  $emp_roles = DB::table('employee_roles')->where('emp_id',Session::get('empSession')['id'])->where('module_id',$modules['id'])->get();
                   $emp_roles = json_decode(json_encode($emp_roles),true);
           
            foreach($querys as $partner){
            	$crminfo = $this->empinfo($partner['emp_id']);
                $id= base64_encode(convert_uuencode($partner['id'])); 
                $checked='';
                if($partner['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
                $actionValues='<a title="View Channel Partner Details" class="btn btn-sm blue margin-top-10 getPartnerid" href="javascript:;" id='.$partner['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Channel Partner" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-partner/'.$partner['id']).'"> <i class="fa fa-edit"></i>
                    </a>';
                }else{
                    foreach($emp_roles as $emp_role){
                        if($emp_role['edit_access'] == "1"){
                           $actionValues='<a title="View Channel Partner Details" class="btn btn-sm blue margin-top-10 getPartnerid" href="javascript:;" id='.$partner['id'].'> <i class="fa fa-file"></i>
                    </a>
                    <a title="Edit Channel Partner" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-partner/'.$partner['id']).'"> <i class="fa fa-edit"></i>
                    </a>'; 
                        }else{
                           $actionValues='';
                        }
                    }
                }
                $num = ++$i;
                $records["data"][] = array(     
                    $num,
                    $partner['name'],
                    $partner['email'],
                    $partner['type'],
                    $crminfo,
                    '<div  id="'.$partner['id'].'" rel="channel_partners" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>',   
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Channel Partners - Express Paisa";
        return View::make('admin.channels.partners')->with(compact('title'));
    }

    public function getpartnerDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $partnerdetails = ChannelPartner::where('id',$data['id'])->first();
            $crminfo = $this->empinfo($partnerdetails->emp_id);
            echo '<tr>
                        <td width="40%">Name</td>
                        <td width="60%">'.$partnerdetails->name.'</td>
                    </tr>
                    <tr>
                        <td width="40%">State</td>
                        <td width="60%">'.$partnerdetails->state.'</td>
                    </tr>
                    <tr>
                        <td width="40%">City</td>
                        <td width="60%">'.$partnerdetails->city.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Type</td>
                        <td width="60%">'.$partnerdetails->type.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Channel Relation Manager </td>
                        <td width="60%">'.$crminfo.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Date of Birth</td>
                        <td width="60%">'.date('d F Y',strtotime($partnerdetails->dob)).'</td>
                    </tr>
                    <tr>
                        <td width="40%">Email</td>
                        <td width="600%">'.$partnerdetails->email.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Address</td>
                        <td width="600%">'.$partnerdetails->address.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Mobile</td>
                        <td width="600%">'.$partnerdetails->mobile.'</td>
                    </tr>';
        }
    }

    public function addeditPartner(Request $request, $id=null){

    	if($id ==""){
            $message ='Channel Partner has been added successfully!';
    		$title = "Add Channel Partner - Express Paisa"; 
    		$partner = new ChannelPartner;
    		$partnerdata = array();
            $getEmpProducts = array();
            $empPids = array();
            $cities = array(); 
    	}else{
            $message = 'Channel Partner has been updated successfully!';
    		$title = "Edit Channel Partner -  Express Paisa"; 
    		$partnerdata = DB::table('channel_partners')->where('id',$id)->first();
    		$partnerdata = json_decode(json_encode($partnerdata),true);
    		$partner = ChannelPartner::find($id);
            if(!empty($partnerdata['state'])){
                $getstateid = DB::table('states')->select('id')->where('state',$partnerdata['state'])->first();
                $cities = $this->cities($getstateid->id);
            }
    	}
    	if($request->isMethod('post')){
    		$data = $request->all();
             
            $chb_name = implode(',', $data['bank_name']);
              
            $chacc = implode(',', $data['account_no']);
            
            $chifcode = implode(',', $data['ifsc_code']);
               
            unset($data['_token']);
    		foreach ($data as $key => $value) {
    			if($key != "password"){
					$partner->$key = $value;
    			}
    		}
            
    		if(empty($partnerdata)){

    			$partner->status = 1; 
                
    		    $partner->password = md5($data['password']);
                
               
    		}

                $partner->bank_name = $chb_name;
                $partner->account_no = $chacc;
                $partner->ifsc_code = $chifcode;

            if ($request->hasFile('pic')) {
                 
                $file_name = $request->file('pic');
                
                 
                      
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                   
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                 
                $destinationPath = 'images/ChannelpartnerFiles'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $partner->pic = $fileName;
               
          
              
            }
            
            if ($request->hasFile('company_docs')) {

                $files = $request->file('company_docs');
                $arr = array();
                foreach($files as $fkey => $file){
                    
                    $originalname = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $originalname."-".time().".".$extension;
                    $destinationPath = 'images/ChannelcompanyDocs/'.$fkey."/";
                    $file->move($destinationPath, $fileName);
                    array_push($arr, $fileName);
                    $fname = implode('|', $arr);
                    $partner->company_docs = $fname;
                }
                
                
            }
            
            // dd($partner);
    		$partner->save();
             
            

             
    		return redirect()->action('ChannelPartnerController@channelpartners')->with('flash_message_success',$message);
    	}
        if(Session::get('empSession')['type'] =="admin"){
           // $getTeams = $this->geteamLevels();
        }else{
           // $getTeams = $this->getteam(Session::get('empSession')['id']);
        }
		$getTeams = $this->getEngTeamLevels(Session::get('empSession')['id']);
        $getCrms = DB::table('employees')->where('status',1);
        if(Session::get('empSession')['type'] !="admin"){
            $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
            $getCrms = $getCrms->whereIn('id',$getEmployees);
        }
    	$getCrms = $getCrms->get();
    	$getCrms = json_decode(json_encode($getCrms),true);
        $states = $this->states();
        return view('admin.channels.add-edit-partner')->with(compact('title','partnerdata','getCrms','states','cities','getTeams'));
    }

    public function checkPartnerEmail(Request $request) {
        $data = $request->all();
        $email = $data['email'];
        $checkEmail = DB::table('channel_partners')
                       ->where('email', $email)
                       ->count();
        if($checkEmail == 1) {
             echo '{"valid":false}';die;;
        }else {
        	echo '{"valid":true}';die;
        }
    }
}
