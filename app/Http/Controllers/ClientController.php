<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use DB;
use Cookie;
use Session;
use Crypt;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Client;
use App\Employee;
use App\CompanyModel;
use Image;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ClientController extends Controller
{
    //
    public function clients(Request $Request){
		
        Session::put('active',14); 

        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $access = Employee::checkAccess();
            // if($access=="true"){
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
                $querys = DB::table('clients');
                // else{
                //     $team = $this->getEmployees(Session::get('empSession')['id']);
                //     $querys = DB::table('clients')->wherein('sale_officer',$team);
                // }
                if(!empty($data['client_id'])){
                    $querys = $querys->where('client_id','like','%'.$data['client_id'].'%');
                }
                if(!empty($data['customer_name'])){
                    $querys = $querys->where('customer_name','like','%'.$data['customer_name'].'%');
                }
                if(!empty($data['company_name'])){
                    $querys = $querys->where('company_name','like','%'.$data['company_name'].'%');
                }
                if(!empty($data['email'])){
                    $querys = $querys->where('email_personal','like','%'.$data['email'].'%');
                }
                if(!empty($data['mobile'])){
                    $querys = $querys->where('mobile','like','%'.$data['mobile'].'%');
                }
                if(!empty($data['pan'])){
                    $querys = $querys->where('pan','like','%'.$data['pan'].'%');
                }
                if(!empty($data['salesofficer'])){
                    $querys = $querys->where('created_emp',$data['salesofficer']);
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
                //$client_id_list = $this->getclientdata();
                
				$client_id_list = $this->getclientdataList();
				$client_id_list=json_decode( json_encode($client_id_list), true);
				$client_id_array = array_column($client_id_list, 'id');
				$querys = DB::table('clients')->wherein('id',$client_id_array);
				
                $cl_id = array();
                $cl_name = array();
                $cl_mail = array();
                $cl_comp = array();
                $cl_mob = array();
                $cl_pan = array();
                
				if(!empty($data['client_id'])){
                   $querys = $querys->where('client_id','like','%'.$data['client_id'].'%');
                 }
                
				if(!empty($data['customer_name'])){
                   $querys = $querys->where('customer_name','like','%'.$data['customer_name'].'%');
                }
				if(!empty($data['company_name'])){
                   $querys = $querys->where('company_name','like','%'.$data['company_name'].'%');
                }
                
			   
                if(!empty($data['email'])){
                   $querys = $querys->where('email_personal','like','%'.$data['email'].'%');
                }
			    if(!empty($data['mobile'])){
                   $querys = $querys->where('mobile','like','%'.$data['mobile'].'%');
                }
				if(!empty($data['pan'])){
                   $querys = $querys->where('pan','like','%'.$data['pan'].'%');
                }
				if(!empty($data['salesofficer'])){
                    $querys = $querys->where('created_emp',$data['salesofficer']);
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
          
                  $modules = DB::table('modules')->where('name','Clients')->first();
                  $modules = json_decode(json_encode($modules),true);

                  $emp_roles = DB::table('employee_roles')->where('emp_id',Session::get('empSession')['id'])->where('module_id',$modules['id'])->get();
                   $emp_roles = json_decode(json_encode($emp_roles),true);
                  
            foreach($querys as $client){
                $checked='';
                if($client['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                if($client['created_emp']){
                    $saleofficer = Employee::where('id',$client['created_emp'])->first();
                    $saleofficer = $saleofficer->name;
                }else{
                    $saleofficer = "Not Available";
                }
                if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
                $actionValues='
                    <a title="Edit Client" class="btn btn-sm green" href="'.url('s/admin/add-edit-client/'.$client['id']).'"> <i class="fa fa-edit"></i>
                    </a> 
                    <a title="view Disbursement Files" class="btn btn-sm red margin-top-10" href="'.url('s/admin/disbursement-files?name='.$client['name']).'"><i class="fa fa-file"></i>
                    </a>';
                }else{
                    foreach($emp_roles as $emp_role){
                    if($emp_role['edit_access'] == "1"){
                       $actionValues='
                    <a title="Edit Client" class="btn btn-sm green" href="'.url('s/admin/add-edit-client/'.$client['id']).'"> <i class="fa fa-edit"></i>
                    </a>
                    <a title="view Disbursement Files" class="btn btn-sm red margin-top-10" href="'.url('s/admin/disbursement-files?name='.$client['name']).'"><i class="fa fa-file"></i>
                    </a>';
                    }else{
                    $actionValues = '';
                }
                }
                }
                $num = ++$i;
                $records["data"][] = array(     
                    $client['client_id'],
                    $client['customer_name'],
                    $client['company_name'],
                    $client['email_personal'],
                    $client['mobile'],
                    $client['pan'],
                    $saleofficer,
                    '<div  id="'.$client['id'].'" rel="clients" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>',   
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Clients - Express Paisa";
        return View::make('admin.clients.clients')->with(compact('title'));
    }

    public function addeditClient(Request $request, $clientid=null){
        // dd($request->all());
        // dd(Session::get('empSession')['type']);
        if($clientid ==""){
            $message ='Client has been added successfully!';
            $title = "Add Client - Express Paisa"; 
            $client = new Client;
            
            $clientdata = array();
            $cities = array();
        }else{ 

            $message = 'Client has been updated successfully!';
            $title = "Edit Client -  Express Paisa"; 
            $client = Client::find($clientid);
            $clientdata = json_decode(json_encode($client),true);
            // dd($clientdata);
            
           
            if(!empty($clientdata['state'])){
                $getstateid = DB::table('states')->select('id')->where('state',$clientdata['state'])->first();
                
                $cities = $this->cities($getstateid->id);
                // dd($cities);
        }
        }
        
        if($request->isMethod('post')){

            $data = $request->all();
            // dd($data);
            $ref_name = implode(',', $data['reference_name']);

            $phone = implode(',', $data['phone_number']);
            $addr = implode(',', $data['proper_address']);
            // dd($data['reference_name']);
           
            // dd($all_array);

            unset($data['_token']); 
            foreach ($data as $key => $value) {
                $client->$key = $value;
            }
           
                
                if(empty($clientdata)){
                    $getlastClientId = DB::table('clients')->orderby('id','desc')->select('client_id')->first();
                    $getlastClientId = json_decode(json_encode($getlastClientId),true);
                if(!empty($getlastClientId)){
                    $getcode = $getlastClientId['client_id'];
                    $getcode= ltrim ($getcode,'SC');
                    $code = (int)$getcode+1;
                    $clinetcode = "C".$code;
                }else{
                    $clinetcode = "C101"; 
                }

                
                $client->client_id = $clinetcode;
            }
            
           
                if ($request->hasFile('client_pic')) {
                 
                $file_name = $request->file('client_pic');
                
                 
                        
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                   
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                 
                $destinationPath = 'images/ClientPhoto'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $client->client_pic = $fileName;
               
          
              
            }
                if($data['pan_status'] == "no"){
                    $client->pan = "Applied For PAN";
                }else{
                    $client->pan = $data['pan'];
                }
                $client->customer_name = $data['customer_name'];

                $client->client_gender = $data['client_gender'];
                $client->alt_mobile = $data['alt_mobile'];
                $client->mother_name = $data['mother_name'];
                $client->father_name = $data['father_name'];
                $client->marital_status = $data['marital_status'];
                $client->created_emp = Session::get('empSession')['id'];
                // $client->current_res_address = $data['current_res_address'];
                $client->near_landmark = $data['near_landmark'];
                $client->current_city_years =$data['current_city_years'];
                $client->current_address_years = $data['current_address_years'];
                if(isset($data['lead_origin']))
                {
                    $client->lead_origin = $data['lead_origin'];
                }
                // $client->profile = $data['profile'];
                $client->client_status = $data['client_status'];
                // $client->permenant_address = $data['permenant_address'];
                $client->permanent_landmark = $data['permanent_landmark'];
                
                $client->spouse_name = $data['spouse_name'];
                $client->spouse_dob  = $data['spouse_dob'];
                if($data['tot_work_experience'] == ""){
                    $client->tot_work_experience = "";
                }else{
                    $client->tot_work_experience = $data['tot_work_experience'];
                }
                if($data['present_company_exp'] == ""){
                    $client->present_company_exp = "";
                }else{
                   $client->present_company_exp = $data['present_company_exp']; 
                }
                
                $client->state = $data['state'];
                $client->city = $data['city'];
                $client->ofc_address = $data['ofc_address'];
                $client->ofc_landmark = $data['ofc_landmark'];
                $client->ofc_pincode = $data['ofc_pincode'];
                $client->ofc_lanline_no = $data['ofc_lanline_no'];
               
               if(isset($data['chk_addr'])){
                $client->chk_addr = '1';
               }else{
                $client->chk_addr = '0';
               }
               
                $client->permenant_address = $data['present_address'];
               
                // $client->permenant_address = $data['permenant_address'];
                
                
                $client->perm_addr = $data['perm_addr'];
                $client->perm_state = $data['perm_state'];
                $client->perm_city = $data['perm_city'];
                $client->perm_pincode = $data['perm_pincode'];
                if(isset($data['tel_name']) && $data['lead_origin'] == 'local')
                {
                    $client->tel_name = $data['tel_name'];
                }
               
                $client->coapp_mail = $data['coapp_mail'];
                $client->coapp_mob = $data['coapp_mob'];

                $client->occupation = $data['occupation'];
                $client->profession = $data['profession'];
                $client->company_type = $data['company_type'];
                $client->buisness_nature = $data['buisness_nature'];
                $client->company_type_salaried = $data['company_type_salaried'];
                $client->industry_type = $data['industry_type'];
                $client->monthly_salary = $data['monthly_salary'];
                $client->annual_turnover = $data['annual_turnover'];

                $client->email_ofc = $data['email_ofc'];
                $client->email_personal = $data['email_personal'];
                if(isset($data['channel_partner']) && $data['lead_origin'] == 'channel partner')
                {
                    $client->channel_partner = $data['channel_partner'];
                }
                $client->reference_name = $ref_name;
                $client->phone_number = $phone;
                $client->proper_address = $addr;
                $client->relative = $data['relative'];
                $client->qualification = $data['qualification'];
                $client->institute_name = $data['institute_name'];
                $client->year_of_passing = $data['year_of_passing'];
                if(!empty($data['company_details'])){
                   $client->company_details = $data['company_details'];
                }else{
                   $client->company_details = "";
                }
                

                $client->company_identifications = $data['company_identifications'];
                $client->status = 1;
               
                $client->save();
               

               

            return redirect()->action('ClientController@clients')->with('flash_message_success',$message);
        }
        // dd($clientdata);
        // dd(Session::get('empSession')['type']);
        if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $getteldata = $this->gettel();
            $querys = DB::table('channel_partners')->where('status',1)->orderby('name','asc')->get();
            $channelpartners = json_decode(json_encode($querys),true);
            
        }else{
            // // Root check
            // $isRootCheck = Employee::where('id',Session::get('empSession')['parent_id'])->where('parent_id','ROOT')->get();
            // if(count($isRootCheck) == 1)
            // {
            //     $getteldata = $this->gettel();
            // } else {
            //     $getteldata = $this->getemployeedata();
            // }
			
            $getteldata = DB::table('employees')->where('team_id',Session::get('empSession')['team_id'])->orderby('name','asc')->get();
			$getteldata=json_decode( json_encode($getteldata), true);
			
			/* $getteldata = $this->getemployeedata(); */
            /*$channelpartners = $this->channeldata(); */
			$id_list = $this->getpartnerdataList();
			
			$id_list=json_decode( json_encode($id_list), true);
			$id_array = array_column($id_list, 'id');
			
			$channelpartners = DB::table('channel_partners')->wherein('id',$id_array)->get();
			$channelpartners = json_decode( json_encode($channelpartners), true);
        }
        $getTeamLevels = $this->geteamLevels();
        $states  = $this->states();
        
        // dd($getteldata);

        return view('admin.clients.add-edit-client')->with(compact('title','clientdata','getTeamLevels','states','cities','channelpartners','getteldata'));
    }

    public function CheckClientPan(Request $request) {
      
        if(isset($_GET['type']) && $_GET['type']=="adhar"){

            $data = $request->all();

            $adhar = $data['adhar_no'];
            $check = DB::table('clients')
                           ->where('adhar_no', $adhar)
                           ->count();

            if($check == 1) {
                 return response()->json(['success'=>'false']);
            }else {
                return response()->json(['success'=>'true']);
            }
        }else{
            $data = $request->all();
            $pan = $data['pan'];
            $check = DB::table('clients')
                           ->where('pan', $pan)
                           ->count();
            if($check == 1) {
                 return response()->json(['success'=>'false']);
            }else {
                return response()->json(['success'=>'true']);
            }
        }
    }

    public function companyModels(Request $Request){
        Session::put('active',22); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('company_models')->join('companies','companies.id','=','company_models.company_id')->select('company_models.*','companies.name as company_name');
            if(!empty($data['company_name'])){
                $querys = $querys->where('companies.name','like','%'.$data['company_name'].'%');
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
            foreach($querys as $model){
                $checked='';
                if($model['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                $actionValues='
                    <a title="Edit" class="btn btn-sm green" href="'.url('s/admin/add-edit-model/'.$model['id']).'"> <i class="fa fa-edit"></i>
                    </a>';
                $num = ++$i;
                $records["data"][] = array(     
                    $model['company_name'],
                    $model['model'],
                    $model['variant'],
                    $model['type'],
                    '<div  id="'.$model['id'].'" rel="company_models" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>',   
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Manufacturers - Express Paisa";
        return View::make('admin.companies.company-models')->with(compact('title'));
    }

    public function addEditModel(Request $request,$modelid=null){
        if($modelid==""){
            $title ="Add Manufacturer Model";
            $message ="Model has been added successfully";
            $model = new CompanyModel;
            $modeldata = array();
        }else{
            $model = CompanyModel::find($modelid);
            $modeldata = json_decode(json_encode($model),true);
            $title ="Edit Manufacturer Model";
            $message ="Model has been updated successfully";
        }
        if($request->isMethod('post')){
            $data = $request->all();
            $model->company_id = $data['company_id'];
            $model->model = $data['model'];
            $model->variant = $data['variant'];
            $model->type = $data['type'];
            $model->status = 1;
            $model->save();
            return redirect()->action('ClientController@companyModels')->with('flash_message_success',$message);
        }
        $companies =DB::table('companies')->orderby('name','ASC')->get();
        $companies = json_decode(json_encode($companies),true);
        return view('admin.companies.add-edit-model')->with(compact('title','modeldata','companies'));
    }

    public function exportClients(){
        $headers = array(
            'Content-Type'        => 'text/csv',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=Client-List.csv',
            'Expires'             => '0',
            'Pragma'              => 'public',
        );
        $response = new StreamedResponse(function(){
            // Open output stream
            $handle = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($handle, ["Client Code","Client Name","Company Name","Applicant Name","DOB","Co-Applicant Name","Co-Applicant DOB","Mobile","PAN","Adhar No","Email","Sales Officer"]);
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
			   $exportclients  = Client::with('saleofficer');
		   }else{
			   $client_id = [];
			   $getclientdata = $this->getclientdata();
			   foreach($getclientdata as $clientdata){
                  array_push($client_id, $clientdata['id']);
               }
			   $exportclients  = Client::with('saleofficer')->wherein('id',$client_id);
		   }
            $exportclients = $exportclients->chunk(500, function($clients) use($handle) {
                foreach ($clients as $client) {  

                   if($client->created_emp){
						$saleofficer = Employee::where('id',$client->created_emp)->first();
						$saleofficer = $saleofficer->name;
					}else{
						$saleofficer = "Not Available";
					}

				
                    fputcsv($handle, [
                        $client->client_id,
                        $client->customer_name ,
                        $client->company_name,
                        $client->name,
                        ' '.date('d-m-Y',strtotime($client->dob)).' ',
                        $client->co_applicant_name,
                        $client->co_applicant_dob,
                        $client->mobile,
                        $client->pan,
                        ' '.$client->adhar_no.' ',
                        $client->email_personal,
                        $saleofficer,
                    ]);
                }
            });
            fclose($handle);
        },200, $headers);
        
		return $response->send();
    }

}
