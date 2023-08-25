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
use App\ThreadFile;
use DateTime;
use App\LeadReminder;
class LeadController extends Controller
{
    public function leads(Request $Request){
        Session::put('active',5); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status');
            if(!empty($data['lead_id'])){
                $querys = $querys->where('leads.lead_id','like','%'.$data['lead_id'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('leads.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['last_status'])){
                $querys = $querys->where('leads.last_status','like','%'.$data['last_status'].'%');
            }
            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->where('leads.source',Session::get('empSession')['id']);
            }
            $querys = $querys->OrderBy('leads.id','DESC');
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
            foreach($querys as $lead){
                $leadGenType = " (".$this->empTypeFullname($lead['emptype']) .")";
                $allocateLead ="";
                $checkleadAllocateCount = DB::table('allocate_leads')->where('lead_id',$lead['id'])->count();
                if(($access =="true" || Session::get('empSession')['parent_id'] =="ROOT" ) && $checkleadAllocateCount ==0){
                    $allocateLead = '<a title="Allocate Lead" class="btn btn-sm green margin-top-10" href="'.url('/s/admin/allocate-lead/'.$lead['id']).'"><i class="fa fa-hand-o-right"></i></a>';
                }
                $updateLeadStatus ='';
                /*$checkcount = AllocateLead::where('lead_id',$lead['id'])->count();
                if($checkcount >0){
                    $updateLeadStatus ='<a target="_blank" title="Update Lead Status" class="btn btn-sm green margin-top-10" href='.url('/s/admin/update-lead-status/'.$lead['id']).'><i class="fa fa-clock-o"></i></a>';
                }*/
                $downloadLeadFile="";
                $getDownloadFilesCount = DB::table('lead_files')->where('lead_id',$lead['id'])->count();
                if($getDownloadFilesCount >0){
                    $downloadLeadFile = '<a  title="Download Zip of '.$lead['lead_id'].'" class="btn btn-sm yellow margin-top-10" href="'.url('/s/admin/download-lead-zip/'.$lead['id']).'"><i class="fa fa-download"></i></a>';
                }
                $editlead ='<a title="Edit Lead" class="btn btn-sm green margin-top-10 " href="'.url('/s/admin/edit-lead/'.$lead['id']).'"><i class="fa fa-edit"></i></a>'; 
                $actionValues='<a title="View Full Lead Details" class="btn btn-sm blue margin-top-10 getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'><i class="fa fa-file"></i></a>'.$editlead.$downloadLeadFile.$allocateLead.$updateLeadStatus;
                $num = ++$i;
                $records["data"][] = array(     
                    '<a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'>'.$lead['lead_id'].'</i></a>',
                    $lead['company_name'],  
                    $lead['loan_amt'],  
                    $lead['phone_no'],
                    $lead['lead_generator'].$leadGenType,
                    date('d M Y h:ia',strtotime($lead['appoint_date_time'])),
                    '<span class="label label-sm label-success">'.$lead['current_status'].'</span>',
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Generated Leads - Express Paisa";
        $getleadstatuses = $this->getleadstatus('0');
        return View::make('admin.leads.leads')->with(compact('title','getleadstatuses'));
    }

    public function addLead(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            unset($data['_token']);
            $appointDateTime = $data['appoint_date_time'];
            $differnceHoursAndMinutes = DB::table('extra_hours')->select('value')->first();
            $reminderdateTime = date('Y-m-d H:i', strtotime($differnceHoursAndMinutes->value,strtotime($appointDateTime)));
            unset($data['appoint_date_time']);
            $lead = new Lead;
            $lastLead = DB::table('leads')->orderBy('id', 'desc')->select('lead_id')->first();
            $lastLead = json_decode(json_encode($lastLead),true);
            $dept = explode('-',$data['department']); 
            if(!empty($lastLead)){
                $getcode = $lastLead['lead_id'];
                $haystack = $getcode;
                $needle1   = "LM";
                $needle2   = "LA";
                $needle3   = "LI";
                if( strpos( $haystack, $needle1 ) !== false) {
                    $getcode= ltrim($getcode,'LM');
                }elseif( strpos( $haystack, $needle2 ) !== false) {
                    $getcode= ltrim($getcode,'LA');
                }elseif( strpos( $haystack, $needle3 ) !== false) {
                    $getcode= ltrim($getcode,'LI');
                }
                $code = (int)$getcode+1;
                $genleadid = "L".$dept[0].$code;
            }else{
                $genleadid = 'L'.$dept[0].'101';
            }
            $lead->lead_id = $genleadid;
            foreach ($data as $key => $value) {
                if($key !=="leadfiles"){
                    if($key=="department"){
                        $lead->department = $dept[1];
                    }else{
                        $lead->$key = $value;
                    }
                }
            }
            $lead->reminder_date_time = $reminderdateTime;
            $lead->appoint_date_time = $reminderdateTime;
            $lead->source = Session::get('empSession')['id'];
            $lead->save();
            $leadid = DB::getPdo()->lastInsertId();
            if ($request->hasFile('leadfiles')) {
                $files = $request->file('leadfiles');
                foreach($files as $file){
                    $leadfile = new LeadFile;
                    $filename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $filename."-".str_random(3)."-".date('his')."-".str_random(3).".".$extension;
                    $destinationPath = 'images/LeadFiles'.'/';
                    $file->move($destinationPath, $fileName);
                    $leadfile->lead_id = $leadid;
                    $leadfile->file = $fileName;
                    $leadfile->save();
                }
            }
            $leadthread = new LeadThread;
            $leadthread->lead_id = $leadid;
            $leadthread->emp_id = Session::get('empSession')['id'];
            $leadthread->lead_status_id = $data['last_status'];
            $leadthread->appoint_date_time = $appointDateTime;
            $leadthread->reminder_date_time = $reminderdateTime;
            $leadthread->message = $data['comments'];
            $leadthread->type = "parent";
            $leadthread->save();
            if($this->mode=="live"){
                $leadDetails = DB::table('leads')->where('id',$leadid)->first();
                $email = Session::get('empSession')['email'];
                $name = Session::get('empSession')['name'];
                $ccemails= $this->getTeamEmails(Session::get('empSession')['id']);
                $lead_id = $leadDetails->lead_id;
                $messageData = [
                    'leadDetails' => $leadDetails,
                    'name' =>$name,
                ];
                Mail::send('emails.send-lead-email', $messageData, function($message) use ($email,$ccemails,$lead_id){
                    $message->to($email)->subject('Lead created successfully #'.$lead_id);
                    if(!empty($ccemails)){
                        $message->cc($ccemails);
                    }
                });
            }
            return Redirect()->action('LeadController@leads')->with('flash_message_success','Lead has been added successfully!');
        }
        Session::put('active',4);
        $title="Add Lead - Express Paisa";
        $banks = DB::table('banks')->where('status',1)->get();
        $banks = json_decode(json_encode($banks),true);  
        $getleadstatuses = $this->getleadstatus('1');
        $getprofiles = $this->customerProfiles();
        return View('admin.leads.add-lead')->with(compact('title','banks','getleadstatuses','getprofiles'));
    }

    public function editLead(Request $request,$leadid){
        if($request->isMethod('post')){
            $data = $request->all();
            unset($data['_token']);
            $lead = Lead::find($leadid);
            foreach($data as $lkey=> $leadData){
                $lead->$lkey =$leadData;
            }
            $lead->save();
            return Redirect()->action('LeadController@leads')->with('flash_message_success','Lead has been updated successfully!');
        }
        $leaddetails = Lead::where('id',$leadid)->first();
        $leaddetails= json_decode(json_encode($leaddetails),true);
        $getprofiles = $this->customerProfiles();
        $title="Edit Lead - Express Paisa";
        return view('admin.leads.edit-lead')->with(compact('title','leaddetails','getprofiles'));
    }

    public function getLeadDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $getLeadDetails = DB::table('leads')->join('employees','employees.id','=','leads.source')->select('leads.*','employees.name as lead_generator','employees.type as emptype')->where('leads.id',$data['leadid'])->first();
            $indirectdetails="";
            if($getLeadDetails->lead_type =="Indirect"){
                $crminfo = $this->empinfowithoutType($getLeadDetails->crm_id);
                $partnerinfo = $this->channelpartnerinfo($getLeadDetails->channel_partner_id);
                $indirectdetails = '<tr>
                                    <td width="40%">Channel Relation Manager</td>
                                    <td width="60%">'.$crminfo.'</td>
                                </tr>
                                <tr>
                                    <td width="40%">Channel Partner</td>
                                    <td width="60%">'.$partnerinfo.'</td>
                                </tr>';
            }
            $leadGenType = $this->empTypeFullname($getLeadDetails->emptype);
            $leadDetails ='<tr>
                        <td width="40%">Lead Id</td>
                        <td width="60%">'.$getLeadDetails->lead_id.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Source</td>
                        <td width="60%">'.$getLeadDetails->lead_generator.' ('.$leadGenType.')'.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Lead Created Date & Time</td>
                        <td width="60%">'.date('d F Y h:ia',strtotime($getLeadDetails->created_at)).'</td>
                    </tr>
                    <tr>
                        <td>First Appointment Date & Time</td>
                        <td width="60%">'.date('d F Y h:ia',strtotime($getLeadDetails->appoint_date_time)).'</td>
                    </tr>
                    <tr>
                        <td width="40%">Company Name</td>
                        <td width="60%">'.$getLeadDetails->company_name.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Contact Person</td>
                        <td width="60%">'.$getLeadDetails->contact_person.'</td>
                    </tr>
                     <tr>
                        <td width="40%">Profile</td>
                        <td width="60%">'.$getLeadDetails->profile.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Loan Amount</td>
                        <td width="60%">'.$getLeadDetails->loan_amt.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Phone No.</td>
                        <td width="600%">'.$getLeadDetails->phone_no.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Cell No.</td>
                        <td width="600%">'.$getLeadDetails->cell_no.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Product</td>
                        <td width="600%">'.$getLeadDetails->product.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Lead Type</td>
                        <td width="600%">'.$getLeadDetails->lead_type.'</td>
                    </tr>
                    '.$indirectdetails.'
                    <tr>
                        <td width="40%">Lead Priority</td>
                        <td width="600%">'.$getLeadDetails->priority.'</td>
                    </tr>
                    <tr>
                        <td width="40%">Current Status</td>
                        <td width="600%"><span class="label label-sm label-success">'.$this->leadStatus($getLeadDetails->last_status).'</span></td>
                    </tr>
                    <tr>
                        <td width="40%">Comments</td>
                        <td width="600%">'.$getLeadDetails->comments.'</td>
                    </tr>';
            $getallocatedleadEmps = AllocateLead::with(['allocateto','allocateby'])->where('lead_id',$data['leadid'])->get();
            $getallocatedleadEmps = json_decode(json_encode($getallocatedleadEmps),true);
            $allocateleads = '<tr>
                                    <th>Sr No.</th>
                                    <th>Allocate To</th>
                                    <th>Allocate By</th>
                                    <th>Allocate Date & Time</th>
                                </tr>';
            if(!empty($getallocatedleadEmps)){
                foreach($getallocatedleadEmps as $key => $allocateemp){
                    $getAllocateToType = $this->empTypeFullname($allocateemp['allocateto']['type']);
                    $getAllocateByType = $this->empTypeFullname($allocateemp['allocateby']['type']);
                    $allocateleads .= '<tr>
                                        <td>'.++$key.'</td>
                                        <td>'.$allocateemp['allocateto']['name'].'</td>
                                        <td>'.$allocateemp['allocateby']['name'].'</td>
                                        <td>'.date('d F Y h:ia',strtotime($allocateemp['created_at'])).'</td>
                                    </tr>';
                }
            }else{
                $allocateleads .= '<tr ><td colspan="4" style="text-align:center;"> No Allocation yet.</td></tr>';
            }
            return response()->json(
                [
                    'leadDetails' => $leadDetails,
                    'allocateleads' => $allocateleads,
                    'leadid'=>$getLeadDetails->lead_id
                ]
            );
        }
    }

    public function downloadLeadFiles($leadid){
        $getLeadFiles = DB::table('lead_files')->where('lead_id',$leadid)->select('file')->get();
        $getLeadFiles = array_flatten(json_decode(json_encode($getLeadFiles),true));
        if(!empty($getLeadFiles)){
            $files = array();
            foreach($getLeadFiles as $file){
                $files[] = glob(public_path('images/LeadFiles/'.$file));
            }
            $leaddetail = DB::table('leads')->where('id',$leadid)->select('lead_id')->first();
            $zipfilename = $leaddetail->lead_id.".zip";
            if(!file_exists(public_path('images/LeadZipFiles/'.$zipfilename))){
            Zipper::make(public_path('images/LeadZipFiles/'.$zipfilename))->add($files)->close();
            }
            return response()->download(public_path('images/LeadZipFiles/'.$zipfilename));
        }else{
            return redirect()->back()->with('flash_message_success','No files found.');
        }
    }

    public function allocateLead(Request $request,$leadid){
        $access = Employee::checkAccess();
        if($access =="true" || Session::get('empSession')['parent_id'] =="ROOT"){
            if($request->isMethod('post')){
                $data = $request->all();
                $checkleadAllocateCount = DB::table('allocate_leads')->where('lead_id',$leadid)->where('allocate_to',$data['allocate_to'])->where('is_refer','no')->count();
                if($checkleadAllocateCount == 0){
                    $allocate = new AllocateLead;
                    $allocate->lead_id = $leadid;
                    $allocate->allocate_to = $data['allocate_to'];
                    if(isset($data['is_refer'])){
                        $allocate->is_refer = $data['is_refer'];
                    }
                    $allocate->allocate_by = Session::get('empSession')['id'];
                    $allocate->save();
                    if($this->mode=="live"){
                        $leadDetails = DB::table('leads')->select('lead_id','source','last_status','company_name','contact_person')->where('id',$leadid)->first();
                        $allocateTo = $this->empinfowithoutType($data['allocate_to']);
                        $allocateBy = $this->empinfowithoutType(Session::get('empSession')['id']);
                        $leadGeneratedBy = $this->empinfowithoutType($leadDetails->source);
                        $email = $this->empemail($data['allocate_to']);
                        $ccemails= $this->getTeamEmails($data['allocate_to']);
                        $messageData = [
                            'leadid' => $leadDetails->lead_id,
                            'getLeadDetails' =>$leadDetails,
                            'allocateTo' => $allocateTo,
                            'allocateBy' => $allocateBy,
                            'leadGeneratedBy' => $leadGeneratedBy,
                            'current_status' => $this->leadStatus($leadDetails->last_status)
                        ];
                        Mail::send('emails.send-lead-allocate-email', $messageData, function($message) use ($email,$ccemails){
                            $message->to($email)->subject('You have received a new Lead');
                            if(!empty($ccemails)){
                                $message->cc($ccemails);
                            }
                        });
                    }
                    /*if(isset($_GET['t']) && $_GET['t'] =='r'){
                        return redirect()->action('LeadController@receivedLeads')->with('flash_message_success','Lead has been allocated successfully!');
                    }else{*/
                        return redirect()->action('LeadController@allocatedLeads')->with('flash_message_success','Lead has been allocated successfully!');
                    /*}*/
                }else{
                    $empname = $this->empinfo($data['allocate_to']);
                    return redirect()->back()->with('flash_message_error','This lead already allocated to '.$empname);
                }
            }
            $title = "Allocate Lead - Express Paisa";
            $getLeadDetails = DB::table('leads')->where('id',$leadid)->first();
            $getLeadDetails =  json_decode(json_encode($getLeadDetails),true);
            if(!empty($getLeadDetails)){
                if($access =="true"){
                    $geteamLevels = $this->geteamLevels();
                }else{
                    $geteamLevels = $this->getteam(Session::get('empSession')['id']);
                }
                return view('admin.leads.allocate-lead')->with(compact('title','getLeadDetails','geteamLevels'));
            }else{
                return redirect()->action('AdminController@dashboard')->with('flash_message_error','Something Went wrong. Please try again');
            }
        }else{
            return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this functionality');
        }
    }

    public function appendAllocationEmployees(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $appendemployees = "";
            $access = Employee::checkAccess();
            if($data['refer'] =="no"){
                if($access =="true"){
                    $geteamLevels = $this->geteamLevels();
                }else{
                    $geteamLevels = $this->getteam(Session::get('empSession')['id']);
                }
                $appendemployees = '<div class="form-group">
                                    <label class="col-md-3 control-label">Allocate Lead to :</label>
                                    <div class="col-md-5">
                                        <select name="allocate_to" class="selectbox" required>
                                            <option value="">Select</option>';
                                            foreach($geteamLevels as $key => $level){
                                                $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first();
                                                $appendemployees .= '<option value='.$level['id'].'>&#9679;&nbsp;'.$level['name'].'-'.$getEmpType->full_name.'</option>';
                                                foreach($level['getemps'] as $skey => $sublevel1){
                                                    $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first();
                                                    $appendemployees .= '<option value='.$sublevel1['id'].'>&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;'.$sublevel1['name'].' - '.$getEmpType->full_name.'</option>';
                                                    foreach($sublevel1['getemps'] as $sskey=> $sublevel2){
                                                         $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first();
                                                        $appendemployees .= '<option value='.$sublevel2['id'].'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;'.$sublevel2['name'].' - '.$getEmpType->full_name.'</option>';
                                                    }
                                                }
                                            }
                                        $appendemployees .='</select>
                                    </div>
                                </div>';   
            }elseif($data['refer'] =="yes"){
                if($access =="true"){
                    $getreferdepts = DB::table('employees')->where('refer_to_dept','yes')->get();
                }else{
                    $getreferdepts = DB::table('employees')->where('id','!=',Session::get('empSession')['id'])->where('refer_to_dept','yes')->get();
                }
                $getreferdepts = json_decode(json_encode($getreferdepts),true);
                $appendemployees ='<div class="form-group">
                                    <label class="col-md-3 control-label">Allocate Lead to(Refer to Manager):</label>
                                    <div class="col-md-5">
                                        <select name="allocate_to" class="selectbox" required>
                                            <option value="">Select</option>';
                                            foreach($getreferdepts as $referdeptemp){
                                                $getEmpType = DB::table('employee_types')->where('short_name',$referdeptemp['type'])->first();
                                                $appendemployees .= '<option value='.$referdeptemp['id'].'>'.$referdeptemp['name'].' - '.$getEmpType->full_name.'</option>';
                                            }
                                            $appendemployees .='</select>
                                    </div>
                                </div>';
            }
            return $appendemployees; 
        }
    }

    public function appendIndirectDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            if($data['type'] =="crm"){
                $getallcrms = DB::table('employees')->get();
                $getallcrms = json_decode(json_encode($getallcrms),true);
                $crms = '<label class="col-md-3 control-label">Select Channel Relation Manager :</label>
                            <div class="col-md-5">
                                <select name="crm_id" class="selectbox getCrm">
                                    <option value=>Select</option>'; 
                                    foreach($getallcrms as $key => $crm){
                                        $getChannelPartnerCount = DB::table('channel_partners')->where('emp_id',$crm['id'])->count();
                                        if($getChannelPartnerCount >0){
                                            $crms .= '<option value='.$crm['id'].'>'.$crm['name'].'</option>';
                                        }
                                    }
                                $crms .= '</select>
                            </div>';
                return $crms;
            }elseif($data['type']=="partners"){
                $getpartners = DB::table('channel_partners')->where('emp_id',$_GET['crmid'])->get(); 
                $getpartners = json_decode(json_encode($getpartners),true);
                $partners = '<label class="col-md-3 control-label">Select Channel Partner :</label>
                            <div class="col-md-5">
                                    <select name="channel_partner_id" class="selectbox">
                                        <option value=>Select</option>'; 
                                        foreach($getpartners as $key => $partner){
                                           $partners .= '<option value='.$partner['id'].'>'.$partner['name'].'</option>';
                                        }
                                    $partners .= '</select>
                                </div>';
                return $partners;
            }
        }
    }

    public function allocatedLeads(Request $Request){
        Session::put('active',3); 
        $access = Employee::checkAccess();
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->join('allocate_leads','allocate_leads.lead_id','=','leads.id')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status','allocate_leads.allocate_to')->whereExists( function ($query)  {
                    $query->from('allocate_leads')
                    ->whereRaw('leads.id = allocate_leads.lead_id');
                });
            if(!empty($data['lead_id'])){
                $querys = $querys->where('leads.lead_id','like','%'.$data['lead_id'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('leads.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['last_status'])){
                $querys = $querys->where('leads.last_status','like','%'.$data['last_status'].'%');
            }
            if(!empty($data['allocate_to'])){
                $querys = $querys->where('allocate_leads.allocate_to',$data['allocate_to']);
            }
            if(isset($_GET['t']) && $_GET['t'] =="inactive"){
                $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1,'lead_behaviour'=>'Inactive'])->get();
                $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                $querys = $querys->whereIn('leads.last_status',$getleadstatusids);
                $refer ="false";
            }elseif(isset($_GET['t']) && $_GET['t'] =="refer"){
                $refer ="true";
            }else{
                $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Inactive','Closed'])->get();
                $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                $querys = $querys->whereNotIn('leads.last_status',$getleadstatusids);
                $refer ="false";
            }
            
            if($refer =="false"){
                if($access =="false"){
                    $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                    $querys = $querys->whereIn('allocate_leads.allocate_to',$getEmployees);
                }else{
                    $querys = $querys->where('allocate_leads.is_refer','no');
                }
            }else{
                if($access =="false"){
                    $querys = $querys->where('allocate_leads.allocate_by',Session::get('empSession')['id'])->where('allocate_leads.is_refer','yes');
                }else{
                    $querys = $querys->where('allocate_leads.is_refer','yes');
                }
            }
            $querys = $querys->OrderBy('leads.id','DESC');
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
            foreach($querys as $lead){
                $nextAppointmentDate ="";
                $appointmentstring ="";
                if(!empty($lead['reminder_date_time'])){
                    $getlatestAppointmnet = DB::table('lead_threads')->where('lead_id',$lead['id'])->orderby('id','DESC')->first();
                    $getlatestAppointmnet = json_decode(json_encode($getlatestAppointmnet),true);
                    if($getlatestAppointmnet){
                        $nextAppointmentDate =  date('d M Y h:ia', strtotime($getlatestAppointmnet['appoint_date_time']));
                        $currentdate = date('Y-m-d G:i');
                        if($getlatestAppointmnet['appoint_date_time'] < $currentdate){
                            $appointmentstring = "<p style=color:red;>".$nextAppointmentDate."</p>";
                        }else{
                             $appointmentstring = '<p>'.$nextAppointmentDate.'</p>';
                        }
                    }
                }
                $allocateTo = $this->empinfowithoutType($lead['allocate_to']);
                $allocateLead="";
                $updateLeadStatus ='<a target="_blank" title="Update Lead Status" class="btn btn-sm green margin-top-10" href='.url('/s/admin/update-lead-status/'.$lead['id']).'><i class="fa fa-clock-o"></i></a>';
                $actionValues='<a title="View Lead Details" class="btn btn-sm blue margin-top-10 getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'><i class="fa fa-file"></i></a>'.$updateLeadStatus;
                $num = ++$i;
                $records["data"][] = array(     
                    '<a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'>'.$lead['lead_id'].'</a>',
                    $lead['company_name'],  
                    $allocateTo, 
                    $lead['lead_generator'],
                    $appointmentstring,
                    '<span class="label label-sm label-success">'.$lead['current_status'].'</span>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Allocated Leads - Express Paisa";
        $getleadstatuses = $this->getleadstatus('0');
        if($access =="true"){
            $getTeamLevels = $this->geteamLevels();
        }else{
            $getTeamLevels = $this->getteam(Session::get('empSession')['id']);
        }
        return View::make('admin.leads.allocated-leads')->with(compact('title','getleadstatuses','getTeamLevels'));
    }

    public function unAllocatedLeads(Request $Request){
        Session::put('active',4); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status')->whereNotExists( function ($query)  {
                    $query->from('allocate_leads')
                    ->whereRaw('leads.id = allocate_leads.lead_id');
                });
            if(!empty($data['lead_id'])){
                $querys = $querys->where('leads.lead_id','like','%'.$data['lead_id'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('leads.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['last_status'])){
                $querys = $querys->where('leads.last_status','like','%'.$data['last_status'].'%');
            }
            $access = Employee::checkAccess();
            if($access=="false"){
                $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                $querys = $querys->whereIn('leads.source',$getEmployees);
            }
            $querys = $querys->OrderBy('leads.id','DESC');
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
            $access = Employee::checkAccess();
            foreach($querys as $lead){
                $allocateLead="";
                if(($access =="true" || Session::get('empSession')['parent_id'] =="ROOT" )){
                    $allocateLead = '<a title="Allocate Lead" class="btn btn-sm green margin-top-10" href="'.url('/s/admin/allocate-lead/'.$lead['id']).'"><i class="fa fa-hand-o-right"></i></a>';
                }             
                $actionValues='<a title="View Lead Details" class="btn btn-sm blue margin-top-10 getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'><i class="fa fa-file"></i></a>'.$allocateLead;
                $num = ++$i;
                $records["data"][] = array(     
                    '<a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'>'.$lead['lead_id'].'</a>',
                    $lead['company_name'],  
                    $lead['loan_amt'],  
                    $lead['phone_no'],  
                    $lead['lead_generator'],
                    date('d M Y h:ia',strtotime($lead['appoint_date_time'])),
                    '<span class="label label-sm label-success">'.$lead['current_status'].'</span>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "UnAllocated Leads - Express Paisa";
        $getleadstatuses = $this->getleadstatus('0');
        return View::make('admin.leads.unallocated-leads')->with(compact('title','getleadstatuses'));
    }



    public function updateLeadStatus(Request $request, $leadid){
        if(isset($_GET['r']) && $_GET['r'] =="ld"){
            Session::put('leadid',$leadid);
        }
        if(!Session::has('empSession')){
            return redirect::to('s/2018/admin');
        }
        $getLeadDetails = DB::table('leads')->where('id',$leadid)->select('id','lead_id','company_name','source','contact_person','last_status')->first();
        $getLeadDetails = json_decode(json_encode($getLeadDetails),true);
        if(!empty($getLeadDetails)){
            $updateStatusAccess ="no";
            $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
            $checkAllocateCount = DB::table('allocate_leads')->where(['lead_id'=>$leadid])->whereIn('allocate_to',$getEmployees)->count();
            if($checkAllocateCount >0){
                $updateStatusAccess ="yes";
            }
            $access = Employee::checkAccess();
            if($access =="true"){
                $updateStatusAccess ="yes";
            }
            if($updateStatusAccess =="yes"){
                if($request->isMethod('post')){
                    $data = $request->all();
                    $thread = new LeadThread;
                    $thread->emp_id = Session::get('empSession')['id'];
                    $thread->lead_id = $leadid;
                    $thread->lead_status_id = $data['lead_status_id'];
                    $appointmentDatetime="";
                    if(isset($data['appoint_date_time']) && !empty($data['appoint_date_time'])){
                        $differnceHoursAndMinutes = DB::table('extra_hours')->select('value')->first();
                        $reminderDateTime = date('Y-m-d H:i', strtotime($differnceHoursAndMinutes->value,strtotime($data['appoint_date_time'])));
                        $thread->appoint_date_time = $data['appoint_date_time'];
                        $thread->reminder_date_time = $reminderDateTime;
                        $appointmentDatetime = $data['appoint_date_time'];
                    }else{
                        $reminderDateTime="";
                    }
                    $thread->message = $data['message'];
                    $thread->save();
                    $threadid = DB::getPdo()->lastInsertId();
                    $threadFileNameArray = array();
                    if ($request->hasFile('files')) {
                        $files = $request->file('files');
                        foreach($files as $file){
                            $threadfile = new ThreadFile;
                            $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                            $extension = $file->getClientOriginalExtension();
                            $fileName = $filename."-".str_random(2)."-".date('h-i-s').str_random(2).".".$extension;
                            $threadFileNameArray[] = $fileName;
                            $destinationPath = 'images/ThreadFiles'.'/';
                            $file->move($destinationPath, $fileName);
                            $threadfile->thread_id = $threadid;
                            $threadfile->file = $fileName;
                            $threadfile->save();
                        }
                    }
                    $lead = Lead::find($leadid);
                    $lead->last_status = $data['lead_status_id'];
                    $lead->reminder_date_time = $reminderDateTime;
                    $lead->save();
                    if($this->mode=="live"){
                        $submitby = $this->empinfowithoutType(Session::get('empSession')['id']);
                        $ccemails= $this->getTeamEmails(Session::get('empSession')['id']);
                        $sentleadid = $getLeadDetails['lead_id'];
                        $messageData = [
                            'leadid' => $sentleadid,
                            'getLeadDetails' =>$getLeadDetails,
                            'actualleadid' => $leadid,
                            'threadmessage'  => $data['message'],
                            'status'        => $this->leadStatus($data['lead_status_id']),
                            'submitby' => $submitby,
                            'appointmentDatetime' => $appointmentDatetime
                        ];
                        $status = $this->leadStatus($data['lead_status_id']);
                        $allemails = array(Session::get('empSession')['email']);
                        Mail::send('emails.send-lead-thread-email', $messageData, function($message) use ($allemails,$ccemails,$threadFileNameArray,$sentleadid,$status){
                            $message->to($allemails)->subject("#".$sentleadid."-".$status);
                            if(!empty($ccemails)){
                                $message->cc($ccemails);
                            }
                            if(!empty($threadFileNameArray)){
                                foreach($threadFileNameArray as $threadfile){
                                    $message->attach(public_path('images/ThreadFiles/'.$threadfile));
                                }
                            }
                        });
                    }
                    return redirect::to('s/admin/update-lead-status/'.$leadid)->with('flash_message_success','Status has been updated successfully!. <a href="'.url('s/admin/allocated-leads').'">Go back to Alloacted Leads</a>');
                }
                $getLeadThreads = LeadThread::with(['threadleadstatus','getemp','threadfiles'])->where('lead_id',$leadid)->orderby('id','DESC')->get();
                $getLeadThreads = json_decode(json_encode($getLeadThreads),true);
                $leadStatus = $this->getleadstatus("update");
                $getReminderEmps = array();
                $access = Employee::checkAccess();
                if($access=="true"){
                    $getAllocateEmps = DB::table('allocate_leads')->select('allocate_to')->where('lead_id',$leadid)->get();
                    $getAllocateEmps = array_flatten(json_decode(json_encode($getAllocateEmps),true));
                    $getReminderEmps = DB::table('employees')->select('id','email','name','type')->wherein('id',$getAllocateEmps)->get();
                    $getReminderEmps = json_decode(json_encode($getReminderEmps),true);
                }
                $title="Update Lead Status - Express Paisa";
                $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Closed'])->get();
                $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
                return view('admin.leads.update-lead-status')->with(compact('title','getLeadDetails','leadStatus','getLeadThreads','getReminderEmps','getleadstatusids'));
            }else{
                return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this functionality');
            }
        }else{
            return redirect()->action('AdminController@dashboard')->with('flash_message_error','Something went wrong!');
        }
    } 

    public function downloadThreadFile($tfileid){
        $thraedFile = DB::table('thread_files')->where('id',$tfileid)->select('file')->first();
        return response()->download(public_path('images/ThreadFiles/'.$thraedFile->file ));
    }

    public function appendLeadStatusData(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $appendData="";
            $getAppointmentCount = DB::table('lead_statuses')->where(['id'=>$data['status'],'type'=>'1'])->count();
            if($getAppointmentCount > 0){
                $appendData =   '<div class="form-group dateTimeDown">
                                    <div class="col-md-12">
                                        <div class="input-group input-append date appointmentdatetimepicker ">
                                            <input type="text" placeholder="Select Appointment Date Time" class="form-control" name="appoint_date_time" value="'.date("Y-m-d H:i:s"/*, strtotime('+2 hours')*/).'" required />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>';
            }
            return $appendData;
        }
    }

    public function inactiveLeads(Request $Request){
        $access = Employee::checkAccess();
        Session::put('active',15); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->join('allocate_leads','allocate_leads.lead_id','=','leads.id')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status','allocate_leads.allocate_to')->whereExists( function ($query)  {
                    $query->from('allocate_leads')
                    ->whereRaw('leads.id = allocate_leads.lead_id');
                });
            if(!empty($data['lead_id'])){
                $querys = $querys->where('leads.lead_id','like','%'.$data['lead_id'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('leads.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['last_status'])){
                $querys = $querys->where('leads.last_status','like','%'.$data['last_status'].'%');
            }
            if(!empty($data['allocate_to'])){
                $querys = $querys->where('allocate_leads.allocate_to',$data['allocate_to']);
            }
            $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1,'lead_behaviour'=>'Inactive'])->get();
            $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
            $querys = $querys->whereIn('leads.last_status',$getleadstatusids);
            if($access=="false"){
                $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                $querys = $querys->whereIn('allocate_leads.allocate_to',$getEmployees);
            }else{
                $querys = $querys->where('allocate_leads.is_refer','no');
            }
            $querys = $querys->OrderBy('leads.id','DESC');
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
            foreach($querys as $lead){
                $appointmentstring ="";
                $allocateTo = $this->empinfowithoutType($lead['allocate_to']);
                $allocateLead="";
                $updateLeadStatus ='<a target="_blank" title="Update Lead Status" class="btn btn-sm green margin-top-10" href='.url('/s/admin/update-lead-status/'.$lead['id']).'><i class="fa fa-clock-o"></i></a>';
                $actionValues='<a title="View Lead Details" class="btn btn-sm blue margin-top-10 getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'><i class="fa fa-file"></i></a>'.$updateLeadStatus;
                $num = ++$i;
                $records["data"][] = array(     
                    '<a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'>'.$lead['lead_id'].'</a>',
                    $lead['company_name'],  
                    $allocateTo, 
                    $lead['lead_generator'],
                    '<span class="label label-sm label-success">'.$lead['current_status'].'</span>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Inactive Leads - Express Paisa";
        $getleadstatuses = $this->getleadstatus('0');
        if($access=="true"){
            $getTeamLevels = $this->geteamLevels();
        }else{
            $getTeamLevels = $this->getteam(Session::get('empSession')['id']);
        }
        $pagettype="inactive";
        return View::make('admin.leads.allocated-leads')->with(compact('title','getleadstatuses','getTeamLevels','pagettype'));
    }

    public function closedLeads(Request $Request){
        $access = Employee::checkAccess();
        Session::put('active',12); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('leads')->join('employees','employees.id','=','leads.source')->join('lead_statuses','lead_statuses.id','=','leads.last_status')->join('allocate_leads','allocate_leads.lead_id','=','leads.id')->select('leads.*','employees.name as lead_generator','employees.type as emptype','lead_statuses.name as current_status','allocate_leads.allocate_to')->whereExists( function ($query)  {
                    $query->from('allocate_leads')
                    ->whereRaw('leads.id = allocate_leads.lead_id');
                });
            if(!empty($data['lead_id'])){
                $querys = $querys->where('leads.lead_id','like','%'.$data['lead_id'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('leads.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['allocate_to'])){
                $querys = $querys->where('allocate_leads.allocate_to',$data['allocate_to']);
            }
            $getleadstatusids = DB::table('lead_statuses')->select('id')->where(['status'=>1])->whereIn('lead_behaviour',['Closed'])->get();
            $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
            $querys = $querys->whereIn('leads.last_status',$getleadstatusids);
            if($access=="false"){
                $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
                $querys = $querys->whereIn('allocate_leads.allocate_to',$getEmployees);
            }
            $querys = $querys->OrderBy('leads.id','DESC');
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
            foreach($querys as $lead){
                $allocateTo = $this->empinfowithoutType($lead['allocate_to']);
                $updateLeadStatus ='<a target="_blank" title="Update Lead Status" class="btn btn-sm green margin-top-10" href='.url('/s/admin/update-lead-status/'.$lead['id']).'><i class="fa fa-clock-o"></i></a>';
                $actionValues='<a title="View Lead Details" class="btn btn-sm blue margin-top-10 getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'><i class="fa fa-file"></i></a>'.$updateLeadStatus;
                $num = ++$i;
                $records["data"][] = array(     
                    '<a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname="'.$lead['company_name'].'" data-leadid='.$lead['id'].'>'.$lead['lead_id'].'</a>',
                    $lead['company_name'],  
                    $allocateTo, 
                    $lead['lead_generator'],
                    '<span class="label label-sm label-success">'.$lead['current_status'].'</span>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Closed Leads - Express Paisa";
        $getleadstatuses = $this->getleadstatus('0');
        if($access =="true"){
            $getTeamLevels = $this->geteamLevels();
        }else{
            $getTeamLevels = $this->getteam(Session::get('empSession')['id']);
        }
        return View::make('admin.leads.closed-leads')->with(compact('title','getleadstatuses','getTeamLevels'));
    }


    public function SendReminderEmail(Request $request,$leadid){
        if($request->isMethod('post')){
            $data = $request->all();
            $getLeadDetails = DB::table('leads')->where('id',$leadid)->first();
            $sendby = $this->empinfowithoutType(Session::get('empSession')['id']);
            $sendto = $this->empinfowithoutType($data['emp_id']);
            $ccemails= $this->getTeamEmails($data['emp_id']);
            $sentleadid = $getLeadDetails->lead_id;
            if($this->mode=="live"){
                $messageData = [
                    'leadid' => $leadid,
                    'actualleadid' => $leadid,
                    'threadmessage'  => $data['message'],
                    'sendby' => $sendby,
                    'sendto' => $sendto,
                ];
                $email = $this->empemail($data['emp_id']);
                Mail::send('emails.send-reminder-email', $messageData, function($message) use ($email,$ccemails,$sentleadid){
                    $message->to($email)->subject("Reminder #".$sentleadid);
                    if(!empty($ccemails)){
                        $message->cc($ccemails);
                    }
                }); 
            }
            return redirect::to('s/admin/update-lead-status/'.$leadid)->with('flash_message_success','Reminder has been sent successfully to '.$sendto. " and his Team Managers");
        }
    }

    public function leadtrackingReminder(){
        $getleadstatusids = DB::table('lead_statuses')
                            ->select('id')
                            ->where(['status'=>1])->whereIn('lead_behaviour',['Reminder'])
                            ->get();
        $getleadstatusids = array_flatten(json_decode(json_encode($getleadstatusids),true));
        $getleads = Lead::with(['allocatelead'=>function($query){
                        $query->orderby('id','desc');
                    },'leadthreads'=>function($query){
                        $query->orderby('id','desc');
                    }])
                    ->select('id','lead_id','last_status','source','reminder_date_time')
                    ->wherein('last_status',$getleadstatusids);
        if(!isset($_GET['type'])){
            $getleads = $getleads->where('reminder_date_time','=',date('Y-m-d H:i'));
        }else if($_GET['type'] =="yest"){
            $date_raw = date('Y-m-d H:i');
            $reminderdate =  date('Y-m-d H:i', strtotime('-1 day', strtotime($date_raw)));
            $getleads = $getleads->where('reminder_date_time','=',$reminderdate);
        }else if($_GET['type'] =="dby"){
            $date_raw = date('Y-m-d H:i');
            $reminderdate = date('Y-m-d H:i', strtotime('-2 day', strtotime($date_raw)));
            $getleads = $getleads->where('reminder_date_time','=',$reminderdate);
        }else{
            $getleads = $getleads->where('reminder_date_time','=',date('Y-m-d H:i'));
        }
        $getleads =  $getleads->get();
        $getleads = json_decode(json_encode($getleads),true);
        foreach ($getleads as $key => $lead) {
            $lead_id = $lead['lead_id'];
            $getLeadDetails = Lead::where('id',$lead['id'])->first();
            $getLeadDetails = json_decode(json_encode($getLeadDetails),true);
            $email ="";
            $ccemails = array();
            $messageData = array();
            if(!empty($lead['allocatelead'])){
                $remindercount = DB::table('lead_reminders')->where(['lead_id'=>$lead['id'],'thread_id'=>$lead['leadthreads']['id']])->orderby('id','desc')->first();
                $remindercount = json_decode(json_encode($remindercount),true);
                if(!empty($remindercount)){
                    $getempdetails = Employee::with('getemp')->where('id',$remindercount['reminder_sent_to'])->select('id','parent_id')->first();
                    $getempdetails = json_decode(json_encode($getempdetails),true);
                    if($getempdetails['parent_id'] !="ROOT"){
                        $reminder = new LeadReminder;
                        $reminder->lead_id = $lead['id'];
                        $reminder->reminder_sent_to = $getempdetails['getemp']['id'];
                        $reminder->thread_id = $lead['leadthreads']['id'];
                        $reminder->save();
                        $name = $getempdetails['getemp']['name'];
                        $email = $getempdetails['getemp']['email'];
                        if($getempdetails['getemp']['parent_id'] =="ROOT"){
                            $ccemails = array('amit.verma@shivcredits.in');
                        }else{
                            $ccemails = array($getempdetails['getemp']['getemp']['email']);
                        }
                    }
                }else{
                    if($lead['allocatelead']['allocateto']['parent_id'] =="ROOT"){
                        $name = $lead['allocatelead']['allocateto']['name'];
                        $email = $lead['allocatelead']['allocateto']['email'];
                        $ccemails = array('amit.verma@shivcredits.in'); 
                    }else{
                        $name = $lead['allocatelead']['allocateto']['name'];
                        $email = $lead['allocatelead']['allocateto']['email'];
                        if(isset($lead['allocatelead']['allocateto']['getemp']['email'])){
                            $ccemails = array($lead['allocatelead']['allocateto']['getemp']['email']);
                        }
                    }
                    $reminder = new LeadReminder;
                    $reminder->lead_id = $lead['id'];
                    $reminder->reminder_sent_to = $lead['allocatelead']['allocateto']['id'];
                    $reminder->thread_id = $lead['leadthreads']['id'];
                    $reminder->save();
                }
                if($email !=""){
                    $messageData = [
                        'leadid' => $lead_id,
                        'getLeadDetails' =>$getLeadDetails,
                        'messagetitle' => 'Hello! '.$name. ". The status of Lead (" .$lead_id.") still is in ". $lead['leadthreads']['threadleadstatus']['name'].". Kindly Update the status and Next Appointment Date & Time. Details are below:-",
                        'current_status' => $lead['leadthreads']['threadleadstatus']['name'],
                        'appoint_date_time' => $lead['leadthreads']['appoint_date_time'],
                        'statuslink' => '<a href='.url('/s/admin/update-lead-status/'.$lead['id'].'?r=ld').'>Click here</a>'
                    ];
                }
            }else{
                $getLeadSourceDetails = DB::table('employees')->where('id',$lead['source'])->first();
                $ccemails = array('amit.verma@shivcredits.in');
                if($getLeadSourceDetails->parent_id =="ROOT"){
                    $name = $getLeadSourceDetails->name;
                    $email = $getLeadSourceDetails->email;
                }else{
                   $getteamdetails = DB::table('employees')->where('id',$getLeadSourceDetails->parent_id)->first(); 
                   if($getteamdetails->parent_id =="ROOT"){
                        $name = $getteamdetails->name;
                        $email = $getteamdetails->email;
                    }else{
                        $managerdetails = DB::table('employees')->where('id',$getteamdetails->parent_id)->first();
                        $name = $managerdetails->name;
                        $email = $managerdetails->email;
                    }
                }
                $messageData = [
                    'leadid' => $lead_id,
                    'getLeadDetails' =>$getLeadDetails,
                    'messagetitle' => 'Hello! '.$name. ". Lead " .$lead_id. " has not been allocated yet. Details are below:-",
                    'allocationlink' => '<a href='.url('/s/admin/allocate-lead/'.$lead['id']).'>Allocate Lead</a>'
                ];
            }
            if($this->mode=="live" && !empty($email) ){
                Mail::send('emails.send-lead-tracking-email', $messageData, function($message) use ($email,$ccemails,$lead_id){
                    $message->to($email)->subject("Reminder #".$lead_id);
                    if(!empty($ccemails)){
                        $message->cc($ccemails);
                    }
                }); 
            }
        }
        echo "cron job run successfully";
    }

    public function quickReminder(Request $request){
        Session::put('active',9);
        if($request->isMethod('post')){
            $data = $request->all();
            if(isset($data['lead_id']) && !empty($data['lead_id']) && isset($data['emp_ids']) && !empty($data['emp_ids'])){
                $ccemails = array();
                if(isset($data['include_in_cc']) && $data['include_in_cc']  =="yes"){
                    $ccemails = array(Session::get('empSession')['email']);
                }
                $getLeadDetails = DB::table('leads')->where('id',$data['lead_id'])->first();
                $sentleadid = $getLeadDetails->lead_id;
                foreach($data['emp_ids'] as $key => $empid){
                    $sendto = $this->empinfowithoutType($empid);
                    $email = $this->empemail($empid);
                    $sendby = $this->empinfowithoutType(Session::get('empSession')['id']);
                    if($this->mode=="live"){
                        $messageData = [
                            'leadid' => $getLeadDetails->lead_id,
                            'actualleadid' => $getLeadDetails->id,
                            'threadmessage'  => $data['message'],
                            'sendby' => $sendby,
                            'sendto' => $sendto,
                        ];
                        Mail::send('emails.send-reminder-email', $messageData, function($message) use ($email,$ccemails,$sentleadid){
                            $message->to($email)->subject("Reminder #".$sentleadid);
                            if(!empty($ccemails)){
                                $message->cc($ccemails);
                            }
                        });
                    }
                }
                return redirect()->back()->with('flash_message_success','Reminder has been sent successfully');
            }else{
                return redirect()->back()->with('flash_message_error','Something went wrong!');
            }
        }
        $getleadstatuses = DB::table('lead_statuses')->where('status',1)->get();
        $getleadstatuses = json_decode(json_encode($getleadstatuses),true);
        $leadIdsArr = array();
        if(Session::get('empSession')['type'] == "admin"){
            foreach($getleadstatuses as $key => $status){
                $getleads = DB::table('leads')->select('id','lead_id','company_name','source')->where('last_status',$status['id'])->get();
                $getleads = json_decode(json_encode($getleads),true); 
                $satusname = $status['name']." (".count($getleads).")";
                $leadIdsArr[$satusname] = $getleads;
            }
        }else{
            $getEmployees = $this->getEmployees(Session::get('empSession')['id']);
            foreach($getleadstatuses as $key => $status){
                $getleads = DB::table('leads')->select('id','lead_id','company_name','source')->wherein('source',$getEmployees)->where('last_status',$status['id'])->get();
                $getleads = json_decode(json_encode($getleads),true); 
                $satusname = $status['name']." (".count($getleads).")";
                $leadIdsArr[$satusname] = $getleads;
            }
        }
        $title = "Quick Reminder - Express Paisa";
        return view('admin.reminders.quick-reminder')->with(compact('title','leadIdsArr'));
    }

    public function appendReminderDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $leadlink = '<div class="form-group">
                            <label class="col-md-3 control-label">Lead Details:</label>
                                <div class="col-md-4">
                                    <a title="View Full Lead Details" class="btn btn-sm green getLeadDetails" href="javascript:;" data-companyname='.$data['companyname'].' data-leadid='.$data['leadid'].'>'.$data['lead_id'].'
                                    </a> (<a target="_blank" title="View Full Lead Details" href='.url('s/admin/update-lead-status/'.$data['leadid']).'>Check Lead Status
                                    </a>)
                                </div>
                            </label>
                        </div>';
            $getAllocationdetails = DB::table('allocate_leads')->where(['lead_id'=>$data['leadid']])->orderby('id','DESC')->first();
            $getAllocationdetails = json_decode(json_encode($getAllocationdetails),true);
            if(!empty($getAllocationdetails)){
                $getallocateemp = Employee::with('getemp')->where('id',$getAllocationdetails['allocate_to'])->first();
                $getallocateemp = json_decode(json_encode($getallocateemp),true);
                $appendemployees = '<div class="form-group">
                                    <label class="col-md-3 control-label">Select Employees :</label>
                                    <div class="col-md-4">
                                        <select name="emp_ids[]" class="selectbox" multiple required>';
                                                if(isset($getallocateemp['getemp']['getemp']['id'])){
                                                    $getEmpType = DB::table('employee_types')->where('short_name',$getallocateemp['getemp']['getemp']['type'])->first();
                                                    $appendemployees .= '<option value='.$getallocateemp['getemp']['getemp']['id'].'>'.$getallocateemp['getemp']['getemp']['name'].'-'.$getEmpType->full_name.'</option>';
                                                }
                                                if(isset($getallocateemp['getemp']['id'])){
                                                    $getEmpType = DB::table('employee_types')->where('short_name',$getallocateemp['getemp']['type'])->first();
                                                    $appendemployees .= '<option value='.$getallocateemp['getemp']['id'].'>'.$getallocateemp['getemp']['name'].'-'.$getEmpType->full_name.'</option>';
                                                }
                                                $getEmpType = DB::table('employee_types')->where('short_name',$getallocateemp['type'])->first();
                                                $appendemployees .= '<option value='.$getallocateemp['id'].'>'.$getallocateemp['name'].'-'.$getEmpType->full_name.'</option>';
                                        $appendemployees .='</select>
                                    </div>
                                </div>';  

            }else{
                $getallocateemp = Employee::with('getemp')->where('id',$data['source'])->first();
                $getallocateemp = json_decode(json_encode($getallocateemp),true);
                if($getallocateemp['parent_id'] =="ADMIN"){
                    $empid = $getallocateemp['id'];
                }else{
                    if($getallocateemp['parent_id'] =="ROOT"){
                        $empid = $getallocateemp['id'];
                    }else{
                        if($getallocateemp['getemp']['parent_id']=="ROOT"){
                            $empid = $getallocateemp['getemp']['id'];
                        }else{
                            $empid = $getallocateemp['getemp']['getemp']['id'];
                        }
                    }
                }
                $getempdetails = DB::table('employees')->where('id',$empid)->first();
                $getempdetails = json_decode(json_encode($getempdetails),true);
                $appendemployees = '<div class="form-group">
                                    <label class="col-md-3 control-label">Select Employees :</label>
                                    <div class="col-md-4">
                                        <select name="emp_ids[]" class="selectbox" multiple required>';
                                                $getEmpType = DB::table('employee_types')->where('short_name',$getempdetails['type'])->first();
                                                $appendemployees .= '<option value='.$getempdetails['id'].'>'.$getempdetails['name'].'-'.$getEmpType->full_name.'</option>';
                                        $appendemployees .='</select>
                                    </div>
                                </div>';
            }
            $includeMeinCc = '<div class="form-group">
                                    <label class="col-md-3 control-label">Include Me in CC :</label>
                                    <div class="col-md-4">
                                        <input type="checkbox" name="include_in_cc" value="yes" style="color:gray" autocomplete="off" class="margin-top-10">
                                    </div>
                                </div>';
            return response()->json(
                [
                    'leadlink' => $leadlink,
                    'appendemployees' =>$appendemployees,
                    'includeMeinCc' => $includeMeinCc
                ]
            );
        }
    }  
        
    public function hoursDifference(Request $request){
        Session::put('active',10); 
        if($request->isMethod('post')){
            $data = $request->all();
            $hoursMinutesString = $data['value']." ".$data['type'];
            DB::table('extra_hours')->where('id',1)->update(['value'=>$hoursMinutesString]);
            return redirect()->back()->with('flash_message_success','Record has been updated successfully!');
        }
        $title ="Hours Difference - Express Paisa";
        $hoursAndMinutes = DB::table('extra_hours')->first();
        $hoursAndMinutes = explode(' ',$hoursAndMinutes->value);
        $hourMin= $hoursAndMinutes[0];
        $type = $hoursAndMinutes[1];
        return view('admin.hours-difference')->with(compact('title','hourMin','type'));
    }
}   
