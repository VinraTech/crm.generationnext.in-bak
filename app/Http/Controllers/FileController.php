<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Employee;
use App\FileEmployee;
use DB;
use Cookie;
use Session;
use Crypt;
use Illuminate\Support\Facades\Mail;
use PDF;
use DateTime;
use App\File;
use App\Client;
use App\IndividualApplicant;
use App\NonindividualApplicant;
use App\FilePropertyDetail;
use App\FileRefer;
use App\Checklist;
use App\FileChecklist;
use App\Bank;
use App\BankDetail;
use App\Banker;
use App\MovedFile;
use App\NonindividualPartner;
use App\FileLoanDetail;
use App\FileStatusDetail;
use App\EligibilityFile;
use App\BankFile;
use App\PropertyValuation;
use App\TrackerStatus;
use App\BankFileTracker;
use App\ApplicantFinancialDetail;
use App\FileApproval;
use App\FileDisbursement;
use App\FileAssetDetail;
use App\CompanyModel;
use App\Company;
use App\AssetValuation;
use Excel;
use App\PartialFile;
class FileController extends Controller
{

    public function files(Request $Request){
		
        if(isset($_GET['type']) && !empty($_GET['type'])){
            if($_GET['type'] =="bank"){
                Session::put('active',17);
                $type = $_GET['type'];
                $title = ucwords($type)." Files - Express Paisa";
                $heading =ucwords($type)." Files";
                $addfile="no";
                $module_id=21;
            }elseif($_GET['type'] =="declined"){
                Session::put('active',18);
                $type = $_GET['type'];
                $title = ucwords($type)." Files - Express Paisa";
                $heading =ucwords($type)." Files";
                $addfile="no";
                $module_id=22;
            }elseif($_GET['type'] =="approved"){
                Session::put('active',21);
                $type = $_GET['type'];
                $title = ucwords($type)." Files - Express Paisa";
                $heading =ucwords($type)." Files";
                $addfile="no";
                $module_id=26;
            }else{
            	$module_id=17;
                Session::put('active',16);
                $type = $_GET['type'];

                $title = ucwords($type)." Files - Express Paisa";
                $heading =ucwords($type)." Files";
                $addfile="no";
            }
        }else{
        	$module_id=17;
            Session::put('active',13); 
            $addfile="yes";
            $title = "Login Files - Express Paisa";
            $type="login";
            $heading =ucwords($type)." Files";
        }

        if($Request->ajax()){
            
            
            $conditions = array();
            $data = $Request->input();
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id');
                
            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                        $query->where('employee_id',Session::get('empSession')['id']);
                });
            }
            if(!empty($data['bank'])){
                $bankid = $data['bank'];
                $querys = $querys->whereHas('filebank', function ($query) use($bankid) {
                    $query->where('bank_id',$bankid);
                });
            }
            if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
            if(!empty($data['department'])){
                $querys = $querys->where('files.department','like','%'.$data['department'].'%');
            }
            if(!empty($data['customer_name'])){
                $querys = $querys->where('clients.customer_name','like','%'.$data['customer_name'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['loan_ins'])){
                $querys = $querys->where('files.loan_ins','like','%'.$data['loan_ins'].'%');
            }
            if(!empty($data['facility_type'])){
                $querys = $querys->where('files.facility_type','like','%'.$data['facility_type'].'%');
            }
            if(!empty($data['salesofficer'])){
                $querys = $querys->where('clients.created_emp',$data['salesofficer']);
            }
            
            
            $querys = $querys->OrderBy('files.id','DESC');
            $query_count_array=json_decode( json_encode($querys->get()), true);
            $iTotalRecords = count($query_count_array);
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
            }else{ 
                /*$querys = $this->getfiledata($type); */
                $client_id_list = $this->getfiledataList($type);
				$client_id_list=json_decode( json_encode($client_id_list), true);
				$client_id_array = array_column($client_id_list, 'id');
				
				$querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
				->wherein('files.id',$client_id_array)
               ->groupBy('file_employees.file_id');
				
				
                $access = Employee::checkAccess();
                $f_no = array();
                $fcus_name = array();
                $fcomp_name = array();
                $floan_ins = array();
                $fsales_officer = array();
                if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
			
			if(!empty($data['name'])){ 
                //$querys = $querys->where('clients.customer_name','like','%'.$data['name'].'%');
            }
            if(!empty($data['department'])){
                $querys = $querys->where('files.department','like','%'.$data['department'].'%');
            }
			
            if(!empty($data['customer_name'])){
                $querys = $querys->where('clients.customer_name','like','%'.$data['customer_name'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['loan_ins'])){
                $querys = $querys->where('files.loan_ins','like','%'.$data['loan_ins'].'%');
            }
            if(!empty($data['facility_type'])){
                $querys = $querys->where('files.facility_type','like','%'.$data['facility_type'].'%');
            }
            if(!empty($data['salesofficer'])){
                $querys = $querys->where('clients.created_emp',$data['salesofficer']);
            }
            
				 $querys = $querys->OrderBy('files.id','DESC');
                  $iTotalRecords = count($querys->get());
				  
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
            // dd($querys);
            foreach($querys as $file){
                $loandata = DB::table('file_loan_details')->where('file_id',$file['id'])->first();
               
                $loandata=json_decode( json_encode($loandata), true);
                // dd($loandata);
                if(!empty($loandata['processing_fees_percent'])){
                   $processing_fees_percent = $loandata['processing_fees_percent'];
                }else{
                    $processing_fees_percent = "Data not available";
                }
                if(!empty($loandata['processing_fees_amount'])){
                   $processing_fees_amount = $loandata['processing_fees_amount'];
                }else{
                    $processing_fees_amount = "Data not available";
                }
                if(!empty($loandata['tenure_in_months'])){
                   $tenure_in_months = $loandata['tenure_in_months'];
                }else{
                    $tenure_in_months = "Data not available";
                }
                if(!empty($loandata['emi_start_date'])){
                   $emi_start_date = $loandata['emi_start_date'];
                }else{
                    $emi_start_date = "Data not available";
                }
                if(!empty($loandata['no_of_installment_paid'])){
                   $no_of_installment_paid = $loandata['no_of_installment_paid'];
                }else{
                    $no_of_installment_paid = "Data not available";
                }
                if(!empty($loandata['no_of_installment_balance'])){
                   $no_of_installment_balance = $loandata['no_of_installment_balance'];
                }else{
                    $no_of_installment_balance = "Data not available";
                }
                if(!empty($loandata['emi_amt'])){
                   $emi_amt = $loandata['emi_amt'];
                }else{
                    $emi_amt = "Data not available";
                }
                if(!empty($loandata['disbursement_type'])){
                   $disbursement_type = $loandata['disbursement_type'];
                }else{
                    $disbursement_type = "Data not available";
                }
                $client = Client::where('id',$file['client_id'])->first();
                $salesofficer = Employee::getemployee($client->created_emp);
                // if(!empty($file['getbank'])){
                //     $bank = $file['getbank']['bankdetail']['short_name'];
                // }else{
                //      $bank = "Not moved yet";
                // }
                if(!empty($loandata['bank_name'])){
                    $bank = $loandata['bank_name'];
                }else{
                     $bank = "Not moved yet";
                }

                // Access check
                $isAccess = 0;
                if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['type'] != "HR-M" && Session::get('empSession')['type'] !="hro" && Session::get('empSession')['is_access'] !="full"){
                	$isAccess = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'edit_access'=>'1','module_id'=>$module_id])->select('module_id')->count();
                } else {
                	$isAccess = 1;
                }
                $actionValues='';
                $deleteFile= '';
                $disbursement = '';
                
                $editfile="";
                if(!isset($_GET['type'])){
                    if($access =="true" || Session::get('empSession')['type']=="bm"){
                    	if($isAccess == 1)
                    	{
                    		// $editfile = '<a title="Edit File" class="btn btn-sm green margin-top-10" href="'.url('/s/admin/edit-generated-file/'.$file['id']).'"><i class="fa fa-edit"></i></a>';
                    	}
                    }
                }
                $disbursement ="";
                if(isset($_GET['type']) && $_GET['type'] =="approved"){
                	if($isAccess == 1)
                	{
                		// $disbursement = '<a title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/update-disbursement-details/'.$file['id']).'">Update Disbursement</a>
                  //   	<a title="Approve & Move to Bank" class="btn btn-sm red margin-top-10" onclick=" return ConfirmDelete()" href="'.url('/s/admin/move-to-declined/'.$file['id']).'">Move to Declined</a>';
                	}
                }
                
                if($isAccess == 1)
                {
                	$actionValues='<a title="View Details" class="btn btn-sm margin-top-10 blue" href="'.url('/s/admin/create-applicants/'.$file['id']).'">View & Edit</a>';
                    if(isset($_GET['type']) && $_GET['type'] =="declined"){
                        // $actionValues='<a title="Declined Reason" class="btn btn-sm margin-top-10 blue declinedModal" >Reason for Declined</a>';
                    }
	                if($access =="true" || Session::get('empSession')['type']=="bm"){
	                    // $deleteFile= '<a title="Delete File" onclick=" return ConfirmDelete()" class="btn btn-sm margin-top-10 red" href="'.url('/s/admin/destroy-file/'.$file['id']).'"><i class="fa fa-times"></i></a>';
                        $deleteFile= '';
	                }else{
	                    $deleteFile= '';
	                }
                }
                if(isset($_GET['type']) && $_GET['type'] =="approved"){
                    $amount = (!empty($file['approved_amount']) ? '<b> Rs '. FileLoanDetail::format($file['approved_amount']).'</b>' : 'Not Available');
                }else{
                    $amount = (!empty($file['loan_amount']) ? '<b> Rs '. FileLoanDetail::format($file['loan_amount']).'</b>' : 'Not Available');
                }
                $num = ++$i;

                if($isAccess == 1)
                {
                    $file_link = '<a target="_blank" title="View File Details" class="btn btn-sm margin-top-10 green" href="'.url('/s/admin/create-applicants/'.$file['id']).'?open=modal">'.$file['file_no'].'</i></a>';
                } else {
                    $file_link = $file['file_no'];
                }

                $records["data"][] = array(     
                    $file_link,
                    $file['client_name'],  
                    $file['company_name'],
                    $file['loan_ins'],  
                    $amount, 
                    // $file['facility_type'],
                    $bank,
                    // $processing_fees_percent,
                    // $processing_fees_amount,
                    // $tenure_in_months,
                    // $emi_start_date,
                    // $no_of_installment_paid,
                    // $no_of_installment_balance,
                    // $emi_amt,
                    // $disbursement_type,
                    $salesofficer['name'],
                    $actionValues.$editfile.$disbursement.$deleteFile
                );
               
           
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
            
        }

        return View::make('admin.files.files')->with(compact('title','heading','addfile'));
    }

    public function addFile(Request $request){
        Session::put('active',13);
        /*$access = Employee::checkAccess();
        if($access=="true"){*/

            
        /*}else{
            $team = $this->getEmployees(Session::get('empSession')['id']);
            $clients = DB::table('clients')->wherein('sale_officer',$team)->where('status',1)->get();
        }*/
        
        // dd($clients);
        $clientfiles = array();
        $clientdetail = array();
        if($request->isMethod('get')){
            $data = $request->all();
            if(isset($data['client_id'])){
                $clientdetail = Client::where('id',$data['client_id'])->first();
                $data = $request->all();
                $clientfiles = File::where('client_id',$data['client_id'])->get();
                $clientfiles = json_decode(json_encode($clientfiles),true);
            }
        }
        $title ="Add File - Express Paisa";
		$getclientdata = ''; 
		if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['is_access']!="full"){
		 $getclientdata = $this->getclientdata();
        }
		return view('admin.files.add-file')->with(compact('title','clientfiles','clientdetail','getclientdata'));
    }

    public function generatefile(Request $request,$clientid){
        Session::put('active',13);
        if($request->isMethod('post')){
            $data= $request->all();
            // dd($data);
            //echo "<pre>"; print_r($data); die;
            $emps = array();
            array_push($emps, $data['empa']);
            array_push($emps, $data['empb']);
            array_push($emps, $data['empc']);
            array_push($emps, $data['empd']);
            array_push($emps, $data['empe']);

            $file = new File;
            
            $file->client_id = $clientid;
            
            
            // $file->facility_type = $data['facility_type'];
            $file->department = "";
            // $file->lts_no = $data['lts_no'];
            $file->remarks = $data['remarks'];
            $file->address = $data['address'];
            $file->loan_ins = $data['loan_ins'];
            $file->loan_type = $data['loan_type'];
            $file->program = $data['program'];
            $file->insurance_type = $data['insurance_type'];
            $file->loan_amount = $data['loan_amount'];
            // $file->file_type = $data['file_type'];
            $file->move_to = "login";
            // if($data['file_type'] =="indirect"){
            //     $file->channel_partner_id = $data['channel_partner_id'];
            // }
             //File Number Generation
            $lastFile = DB::table('files')->orderBy('id', 'desc')->select('file_no')->first();
            $lastFile = json_decode(json_encode($lastFile),true);
            
            if(!empty($lastFile)){
                $getcode = $lastFile['file_no'];
                $haystack = $getcode;
                $needle1   = "FSC";
                if( strpos( $haystack, $needle1 ) !== false) {
                    $getcode= ltrim($getcode,'FSC');
                }                
                $code = (int)$getcode+1;
                $genfileno = "FSC".$code;
            }else{
                $genfileno = 'FSC'.'101';
            }
            $file->file_no = $genfileno;
            

            $file->save();
            if(!empty($file->file_no)){
                 $fileid = DB::getPdo()->lastInsertId();
             }else{
                $fileid = "";
             }

            if ($request->hasFile('pan_card')) {
                $file_name = $request->file('pan_card');
                
                if(count($file_name)>0){
                 
                 // dd("ss"); 
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                
                $extension = $file_name[0]->getClientOriginalExtension();
                
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                // dd($fileName);
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->pan_card = $fileName;
                
            }
                $file->save();
            }
            if ($request->hasFile('adhaar_card')) {
                $file_name = $request->file('adhaar_card');
               
                 if(count($file_name)>0){   
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->adhaar_card = $fileName;
               
            
            }
               $file->save();
            }
            if ($request->hasFile('salary_slip')) {
                $file_name = $request->file('salary_slip');
                if(count($file_name)){
                    
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->salary_slip = $fileName;
                
                }
            
                $file->save();
            }
            if ($request->hasFile('bank_passbook')) {
                $file_name = $request->file('bank_passbook');
                 if(count($file_name)>0){
                    
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->bank_passbook = $fileName;
               
            }
                 $file->save();
            }
            if ($request->hasFile('voter_id')) {
                $file_name = $request->file('voter_id');
                if(count($file_name)>0){
                   
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->voter_id = $fileName;
                
                }
            
               $file->save();
            }
            if ($request->hasFile('passport')) {
                $file_name = $request->file('passport');
                if(count($file_name)>0){
                  
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->passport = $fileName;
                
                }
            
               $file->save();
            }
            if ($request->hasFile('driving_licence')) {
                $file_name = $request->file('driving_licence');
                if(count($file_name)>0){
                    
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->driving_licence = $fileName;
                
                }
            
              $file->save();
            }
            if ($request->hasFile('rent_agreement')) {
                $file_name = $request->file('rent_agreement');
                if(count($file_name)>0){
                    
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->rent_agreement = $fileName;
                
                }
            
                 $file->save();
            }
            if ($request->hasFile('letterhead')) {
                $file_name = $request->file('letterhead');
                 if(count($file_name)>0){
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->letterhead = $fileName;
                
            }
                $file->save();
            }
            if ($request->hasFile('photo')) {
                $file_name = $request->file('photo');
                 if(count($file_name)>0){
                   
                $filename = pathinfo($file_name[0]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file_name[0]->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/FileDetails'.'/';
                $file_name[0]->move($destinationPath, $fileName);
                $file->photo = $fileName;
                
            }
               $file->save();
            }


             
             $ifAlreadysentforApproval = FileApproval::where('file_id',$fileid)->count();
             
             if($ifAlreadysentforApproval ==0){
                    $getbm = FileEmployee::where(['file_id'=>$fileid,'type'=>'bm'])->first();
                    if($getbm){
                        $bm = $getbm->employee_id;
                    }else{
                        $bm = 2;
                    }
                    $approval = new FileApproval;
                    $approval->file_id =  $fileid;
                    $approval->type ="Move to Login Files";
                    $approval->status = "login";
                    $approval->approval_from = Session::get('empSession')['id'];
                    $approval->employee_id = $bm;
                    $approval->save();
                }

           
            
             
            
            foreach($emps as $emp){
                  
                $fileemp = new FileEmployee;
                $fileemp->file_id = $fileid;
                $explodeData = explode('-',$emp);
                
                // if($explodeData[0] =="source"){
                //     File::where('id',$fileid)->update(['source'=>$explodeData[1]]);
                // }elseif($explodeData[0] =="sales"){
                //     File::where('id',$fileid)->update(['sales_officer'=>$explodeData[1]]);
                // }
                $fileemp->type = (isset($explodeData[0])?$explodeData[0]:'');
                $fileemp->employee_id = (isset($explodeData[1])?$explodeData[1]:'');
               
                $fileemp->save();
            }
            $message = "File number has been generated successfully";

            if(isset($data['old_file'])){
                
                //Indvidual Applicants
                $getIndApplicants = IndividualApplicant::where('file_id',$data['old_file'])->get();
                foreach($getIndApplicants as $indApp){
                    $individualApp = IndividualApplicant::find($indApp->id);
                    $individualApp = $individualApp->replicate();
                    $individualApp->file_id = $fileid;
                    $individualApp->save();
                }
                //Non Indvidual Applicants
                $getNonIndApplicants = NonindividualApplicant::where('file_id',$data['old_file'])->get();
              
                foreach($getNonIndApplicants as $nonindApp){
                    $nonIndividualApp = NonindividualApplicant::find($nonindApp->id);
                    $nonIndividualApp = $nonIndividualApp->replicate();
                    $nonIndividualApp->file_id = $fileid;
                    $nonIndividualApp->save();
                    $newnonIndividualAppid = DB::getPdo()->lastInsertId();
                    //Non Individual Partners
                    $getNonIndPartners = NonindividualPartner::where('non_individual_applicant_id',$nonindApp->id)->get();
                    foreach($getNonIndPartners as $nonpartner){
                        $replicatePartner = NonindividualPartner::find($nonpartner->id);
                        $replicatePartner = $replicatePartner->replicate();
                        $replicatePartner->non_individual_applicant_id = $newnonIndividualAppid;
                        $replicatePartner->save();
                    }
                }
                //Property Details
                $getpropertyDetails = FilePropertyDetail::where('file_id',$data['old_file'])->get();
                foreach($getpropertyDetails as $property){
                    $replicateProperty = FilePropertyDetail::find($property->id);
                    $replicateProperty = $replicateProperty->replicate();
                    $replicateProperty->file_id = $fileid;
                    $replicateProperty->save();
                }
                //File References
                $filerefers = FileRefer::where('file_id',$data['old_file'])->get();
                foreach($filerefers as $filerefer){
                    $replicateFileRef = FileRefer::find($filerefer->id);
                    $replicateFileRef = $replicateFileRef->replicate();
                    $replicateFileRef->file_id = $fileid;
                    $replicateFileRef->save();
                }
                //Copy Loan Details
                // $fileloans = FileLoanDetail::where('file_id',$data['old_file'])->get();
                // foreach($fileloans as $fileloan){
                //     $replicatefileloan = FileLoanDetail::find($fileloan->id);
                //     $replicatefileloan = $replicatefileloan->replicate();
                //     $replicatefileloan->file_id = $fileid;
                //     $replicatefileloan->save();
                // }
                $message= "File number has been generated and information has been copied successfully";
            }
            $movefile = new MovedFile;
            $movefile->file_id = $fileid;
            $movefile->move_type="login";
            $movefile->details = "Moved to Login Files";
            $movefile->moved_by = Session::get('empSession')['id'];
            $movefile->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success',$message);
        }
        $title ="Generate File - Express Paisa";
        $clientdetail = Client::where('id',$clientid)->first();
       

        $getpartners = DB::table('channel_partners')->get();
        $getpartners = json_decode(json_encode($getpartners),true); 
        $filedetail = array();
        if(isset($_GET['fileid'])){
            $filedetail =  File::where('id',$_GET['fileid'])->first();
            $filedetail = json_decode(json_encode($filedetail),true);
        }

        return view('admin.files.generate-file')->with(compact('title','getpartners','clientdetail','filedetail'));
    }

    public function editGeneratedFile(Request $request,$fileid){
        $title="Edit Generated File - Express Paisa";
        $filedetail =  File::where('id',$fileid)->first();
        $filedetail = json_decode(json_encode($filedetail),true);
        $clientdetail = Client::where('id',$filedetail['client_id'])->first();
        $getpartners = DB::table('channel_partners')->get();
        $getpartners = json_decode(json_encode($getpartners),true); 
        if($request->isMethod('post')){
            $data = $request->all();
            $file = File::find($fileid);
            $file->department = $data['department'];
            $file->facility_type = $data['facility_type'];
            $file->lts_no = $data['lts_no'];
            $file->remarks = $data['remarks'];
            $file->file_type = $data['file_type'];
            if($data['file_type'] =="indirect"){
                $file->channel_partner_id = $data['channel_partner_id'];
            }else{
                $file->channel_partner_id = '';
            }
            $file->save();
            //delete File Employees Data
            FileEmployee::where('file_id',$fileid)->delete();
            foreach($data['emps'] as $emp){
                $fileemp = new FileEmployee;
                $fileemp->file_id = $fileid;
                $explodeData = explode('-',$emp);
                if($explodeData[0] =="source"){
                    File::where('id',$fileid)->update(['source'=>$explodeData[1]]);
                }
                $fileemp->type = $explodeData[0];
                $fileemp->employee_id = $explodeData[1];
                $fileemp->save();
            }
            return redirect()->action('FileController@files')->with('flash_message_success','File Details has been updated successfully');
        }
        return view('admin.files.edit-generated-file')->with(compact('title','getpartners','clientdetail','filedetail'));
    }

    public function createApplicants(Request $request,$fileid){
        $data = $request->all();
		if(isset($data['mode'])){
			$mode = $data['mode'];
		}else{
			$mode = '';
		}
		
		
		$title ="Create Applicant - Express Paisa";
        $filedetails = File::with('filebanks')->where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        $fileloandetails = FileLoanDetail::where('file_id',$fileid)->get();
        $fileloandetails = json_decode(json_encode($fileloandetails),true);
        // dd($filedetails);        
        //echo "<pre>"; print_r($filedetails); die;
        $applicantAccess = "yes";
        if(Session::get('empSession')['type'] =="salesmanager" || Session::get('empSession')['type'] =="sales"){
            if($filedetails['move_to'] =="operations"){
                $applicantAccess = "no";
            }
        }
        $emp = array();
        $empdetails = DB::table('file_employees')->orderBy('id', 'desc')->where('file_id',$fileid)->get();
        $empdetails = json_decode(json_encode($empdetails),true);
        foreach($empdetails as $empd){
           array_push($emp,$empd['employee_id']);
        }
        
        //Individual Applicants
        $getIndApplicants = IndividualApplicant::where('file_id',$fileid)->orderby('id','DESC')->get();
        $getIndApplicants = json_decode(json_encode($getIndApplicants),true);
        // dd($getIndApplicants);
        
        //Trackers
        $bankFileApproved = BankFileTracker::where('type','approval')->where('status','Approved')->where('file_id',$fileid)->count();
         
        if($bankFileApproved >= 1){
            $trackers = TrackerStatus::trackers($filedetails['department']);

        }else{
            
            $trackers = TrackerStatus::trackers($filedetails['department'],'disbursement');
            

        }
        $loanDetailsCount = FileLoanDetail::where('file_id',$fileid)->count();
               if($loanDetailsCount > 0){
                $filecheck = DB::table('file_approvals')->where('file_id',$fileid)->get();
                $filecheck = json_decode(json_encode($filecheck),true);
        //        $ifAlreadysentforApproval = FileApproval::where('file_id',$fileid)->count();
        //          // dd($filecheck);
                if($filecheck && $mode == 'move_to_operations'){
                 if($filecheck[0]['status'] == "login"){
                    $filed = FileApproval::where('file_id',$fileid)->update(array('status' => 'pending_approval'));
                     
                
                    $fileinfo = File::find($fileid);
                    $fileinfo->move_to = "operations";
                    $fileinfo->save();

                    $movedfile = new MovedFile;
                    $movedfile->file_id = $fileid;
                    $movedfile->move_type = "pending_approval";
                    $movedfile->details = "Move to Pending Approval";
                    $movedfile->moved_by = Session::get('empSession')['id'];
                    $movedfile->save();
					Session::flash('flash_message_success', 'File hass been Move to Pending Approval');  
                 }
             }
            }
      

        
        return view('admin.files.create-applicants')->with(compact('title','filedetails','getIndApplicants','applicantAccess','fileloandetails','trackers','emp'));
    }

    public function addIndividualApplicant(Request $request,$fileid,$applicantid=null){
        Session::put('active',13);
        $applicantdetails= array();
        if($applicantid){
            $applicantdetails = IndividualApplicant::where('id',$applicantid)->first();
            $applicantdetails = json_decode(json_encode($applicantdetails),true);
        }
        //echo "<pre>"; print_r($applicantdetails); die;
        if($request->isMethod('post')){
            $data = $request->all();
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('individual_applicants');
            if($applicantid){
                $addIndFile = IndividualApplicant::find($applicantid);
            }else{
                $addIndFile = new IndividualApplicant;
            }
            $addIndFile->file_id = $fileid;
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $addIndFile->$key = $postdata;
                }
            }
            $addIndFile->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','IndividualApplicant has been updated successfully!');             
        }
        $title="Add Individual Applicant - Express Paisa";
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        $clientid = $filedetails['client_id'];
        $clientdata = DB::table('clients')->where('id',$clientid)->first();
        $clientdata = json_decode(json_encode($clientdata),true);
        // dd($clientdata);

        return view('admin.files.add-individual-applicant')->with(compact('title','filedetails','applicantdetails','clientdata'));
    }

    public function appendFiles(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $files = File::getfiles();
            $appendFile ='<div class="form-group">
                            <label class="col-md-3 control-label">Select File:</label>
                            <div class="col-md-4">
                                <select name="file_id" class="selectpicker form-control getfileNo" required data-live-search="true" adata-width="100%"> 
                                    <option value="">Select</option>';
                                    foreach($files as $file){
                                        $appendFile .= '<option value="'.$file['id'].'">'.$file['file_no'].' ('.$file['name'].'-'.$file['mobile'].'-'.$file['pan'].')</option>';
                                    }
            $appendFile .= '</select></div></div>';
            return $appendFile;
        }
    }

    public function addNonIndividualApplicant(Request $request,$fileid,$nonapplicantid=null){
        Session::put('active',13);
        $nonapplicantdetails= array();
        $getpartners = array();
        if($nonapplicantid){
            $nonapplicantdetails = NonindividualApplicant::where('id',$nonapplicantid)->first();
            $nonapplicantdetails = json_decode(json_encode($nonapplicantdetails),true);
            $getpartners = NonindividualPartner::where('non_individual_applicant_id',$nonapplicantid)->get();
            $getpartners = json_decode(json_encode($getpartners),true); 
        }
        if($request->isMethod('post')){
            $data = $request->all();
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('nonindividual_applicants');
            if($nonapplicantid){
                $addNonIndFile = NonindividualApplicant::find($nonapplicantid);
            }else{
                $addNonIndFile = new NonindividualApplicant;
            }
            $addNonIndFile->file_id = $fileid;
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $addNonIndFile->$key = $postdata;
                }
            }
            $addNonIndFile->save();
            if($nonapplicantid){
                $nonIndAppid = $nonapplicantid;
                if(isset($data['partner_name'])){
                    foreach($data['partner_name'] as $pakey => $nonAddedpartner){
                        if(!empty($nonIndpartner)){
                            $addedNonpartner = NonindividualPartner::find($data['partner_id'][$pakey]);
                            $addedNonpartner->name = $nonAddedpartner;
                            $addedNonpartner->non_individual_applicant_id = $nonIndAppid;
                            $addedNonpartner->dob = $data['partner_dob'][$pakey];
                            $addedNonpartner->nationality = $data['partner_nationality'][$pakey];
                            $addedNonpartner->occupation = $data['partner_occupation'][$pakey];
                            $addedNonpartner->shareholding = $data['partner_shareholding'][$pakey];
                            $addedNonpartner->save();
                        }
                    }
                }
            }else{
                $nonIndAppid = DB::getPdo()->lastInsertId();
            }
            foreach($data['name'] as $pkey => $nonIndpartner){
                if(!empty($nonIndpartner)){
                    $nonpartner = new NonindividualPartner;
                    $nonpartner->name = $nonIndpartner;
                    $nonpartner->non_individual_applicant_id = $nonIndAppid;
                    $nonpartner->dob = $data['dob'][$pkey];
                    $nonpartner->nationality = $data['nationality'][$pkey];
                    $nonpartner->occupation = $data['occupation'][$pkey];
                    $nonpartner->shareholding = $data['shareholding'][$pkey];
                    $nonpartner->save();
                }
            }
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Non IndividualApplicant has been updated successfully!');             
        }
        $title="Add Non Individual Applicant - Express Paisa";
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        return view('admin.files.add-non-individual-applicant')->with(compact('title','filedetails','nonapplicantdetails','getpartners'));
    }   

    public function updateApplicantFinancialDetails(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $financials = array('Audited Turnover'=> array('slug'=>'turnover','gst'=>'yes'), 'Net Profit'=> array('slug'=>'net_profit','gst'=>'no'),'Depereciation'=> array('slug'=>'depereciation','gst'=>'no'),'Other Income'=> array('slug'=>'other_income','gst'=>'no'),'Rental Income'=> array('slug'=>'rental_income','gst'=>'no'));
            $appendData='<input type="hidden" name="file_id" value='.$data['fileid'].'>
            <input type="hidden" name="applicant_id" value='.$data['applicantid'].'>
            <input type="hidden" name="type" value='.$data['type'].'>';
            foreach($financials as $fkey=> $financial){
                $ly="";$py="";$gst="";
                $financialexits =  ApplicantFinancialDetail::where(['file_id'=>$data['fileid'],'type'=> $data['type'],'applicant_id'=>$data['applicantid'],'ftype'=>$financial['slug']])->first();
                if($financialexits){
                    $ly = $financialexits->ly;
                    $py = $financialexits->py;
                    $gst = $financialexits->gst;
                }
                $appendData .= '<tr>
                                    <td>'.$fkey.'</td>
                                    <td><input type="number" min="0" class="form-control" name="data['.$financial['slug'].'][ly]" placeholder="Last Year" value="'.$ly.'" required></td>
                                    <td><input type="number" min="0"  class="form-control" name="data['.$financial['slug'].'][py]" placeholder="Prevous Year" value="'.$py.'" required></td>';
                                    if($financial['gst'] =="yes"){
                                        $appendData .='<td><input type="number" class="form-control" min="0" name="data['.$financial['slug'].'][gst]" placeholder="Enter GST Return" value="'.$gst.'" required></td>';
                                    }
                                $appendData .= '</tr>';
            }
            return $appendData;
        }else if($request->isMethod('post')){
            $data = $request->all();
            foreach($data['data'] as $fkey => $financeData){
                $checkifexits = ApplicantFinancialDetail::where(['file_id'=>$data['file_id'],'type'=> $data['type'],'applicant_id'=>$data['applicant_id'],'ftype'=>$fkey])->first();
                if($checkifexits){
                    $finance = ApplicantFinancialDetail::find($checkifexits->id);
                }else{
                    $finance = new ApplicantFinancialDetail;
                }
                $finance->file_id = $data['file_id'];
                $finance->type = $data['type'];
                $finance->ftype = $fkey;
                $finance->applicant_id = $data['applicant_id'];
                $finance->ly = $financeData['ly'];
                $finance->py = $financeData['py'];
                if(isset($financeData['gst'])){
                    $finance->gst = $financeData['gst'];
                }
                $finance->created_by = Session::get('empSession')['id'];
                $finance->save();
            }
            return redirect()->back()->with('flash_message_success','Financial details has been updated successfully');
        }
    }

    public function deleteApplicant($type,$applicantid){
        if($type=="individual"){
            IndividualApplicant::where('id',$applicantid)->delete();
        }else{
            NonindividualApplicant::where('id',$applicantid)->delete();
            NonindividualPartner::where('non_individual_applicant_id',$applicantid)->delete();
        }
        //Delete financial details
        ApplicantFinancialDetail::where(['type'=>$type,'applicant_id'=>$applicantid])->delete();
        return redirect()->back()->with('flash_message_success','Record has been deleted successfully');
    }

    public function deleteProperty($propertyid){
        FilePropertyDetail::where('id',$propertyid)->delete();
        PropertyValuation::where('property_id',$propertyid)->delete();
        return redirect()->back()->with('flash_message_success','Property Detail has been deleted successfully');
    }

    public function deleteAsset($assetid){
        FileAssetDetail::where('id',$assetid)->delete();
        AssetValuation::where('asset_id',$assetid)->delete();
        return redirect()->back()->with('flash_message_success','Asset Detail has been deleted successfully');
    }

    public function addFacilityRequirement(Request $request,$fileid){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('files');
            $facilityRequ = File::find($fileid);
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $facilityRequ->$key = $postdata;
                }
            }
            $facilityRequ->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','About 
                My Facility requirement has been updated successfully!');  
        }
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        $title="About My Facility Requirement";
        return view('admin.files.add-facility-requirement')->with(compact('title','filedetails'));
    }

    public function addAssetDetail(Request $request,$fileid,$assetid=null){
        Session::put('active',13);
        $assetdetails= array();
        $modeldetails = array();
        if($assetid){
            $assetdetails = FileAssetDetail::where('id',$assetid)->first();
            $assetdetails = json_decode(json_encode($assetdetails),true);
            $modeldetails = CompanyModel::where('company_id',$assetdetails['company_id'])->get();
            $modeldetails = json_decode(json_encode($modeldetails),true);
        }
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('file_asset_details');
            if($assetid){
                $addassetFile = FileAssetDetail::find($assetid);
            }else{
                $addassetFile = new FileAssetDetail;
            }
            $addassetFile->file_id = $fileid;
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $addassetFile->$key = $postdata;
                }
            }
            $addassetFile->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Asset details has been updated successfully!');        
        }
        $companies = DB::table('companies')->orderby('name','ASC')->get();
        $companies = json_decode(json_encode($companies),true);
        $title="Add Asset Detail - Express Paisa";
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        return view('admin.files.add-asset-detail')->with(compact('title','filedetails','assetdetails','companies','modeldetails'));
    }

    public function addPropertyDetail(Request $request,$fileid,$propertyid=null){
        Session::put('active',13);
        $propertydetails= array();
        if($propertyid){
            $propertydetails = FilePropertyDetail::where('id',$propertyid)->first();
            $propertydetails = json_decode(json_encode($propertydetails),true);
        }
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('file_property_details');
            if($propertyid){
                $addpropertyfile = FilePropertyDetail::find($propertyid);
            }else{
                $addpropertyfile = new FilePropertyDetail;
            }
            $addpropertyfile->file_id = $fileid;
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $addpropertyfile->$key = $postdata;
                }
            }
            $addpropertyfile->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Property details has been updated successfully!');        
        }
        $title="Add Property Detail - Express Paisa";
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        return view('admin.files.add-property-detail')->with(compact('title','filedetails','propertydetails'));
    }

    public function getCompanyModels(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $getmodels = DB::table('company_models')->where('company_id',$data['companyid'])->get();
            $getmodels = json_decode(json_encode($getmodels),true);
            $models = '<option value="">Select Model</option>';
            foreach($getmodels as $key => $model){
                $models .= '<option value="'.$model['id'].'">'.$model['model'].' - '.$model['variant'].' - '.$model['type'].'</option>';
            }
            print_r($models);
        }
    }

    public function updateValuations(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $banks = BankFile::with('bankdetail')->where('file_id',$data['fileid'])->get();
            $banks = json_decode(json_encode($banks),true);
            $valuation = '<input type="hidden" name="file_id" value='.$data['fileid'].'>
                            <input type="hidden" name="property_id" value='.$data['propertyid'].'>';
                            foreach($banks as $bank){
                                $getvaluation = PropertyValuation::getvaluation($data['fileid'],$data['propertyid'],$bank['bank_id']);
                                $filledvalue1 =""; $filledvalue2 ="";
                                if($getvaluation){
                                    $filledvalue1 = $getvaluation['value1'];
                                    $filledvalue2 = $getvaluation['value2'];
                                }
                                $val1 = "bank[".$bank['bank_id']."][val1]";
                                $val2 = "bank[".$bank['bank_id']."][val2]";
                                $valuation .= '<tr>
                                                <td>'.$bank['bankdetail']['short_name'].'</td>
                                                <td><input type="number" class="form-control" name="'.$val1.'" placeholder="Val-1" required value='.$filledvalue1.'></td>
                                                <td><input type="number" class="form-control" name="'.$val2.'" placeholder="Val-2" required value='.$filledvalue2.'></td>
                                            </tr>';
                            }
            return $valuation;
        }elseif($request->isMethod('post')){
            $data = $request->all();
            foreach($data['bank'] as $bkey=> $bank){
                $checkifexists = PropertyValuation::where(['file_id'=>$data['file_id'],'property_id'=>$data['property_id'],'bank_id'=>$bkey])->first();
                if($checkifexists){
                    $pvaluation = PropertyValuation::find($checkifexists->id);
                }else{
                    $pvaluation = new PropertyValuation;
                }
                $pvaluation->file_id = $data['file_id'];
                $pvaluation->property_id = $data['property_id'];
                $pvaluation->bank_id = $bkey;
                $pvaluation->value1 = $bank['val1'];
                $pvaluation->value2 = $bank['val2'];
                $pvaluation->created_by = Session::get('empSession')['id'];
                $pvaluation->save();
            }
            return redirect()->back()->with('flash_message_success','Property Valution has been updated successfully');
        }
    }

    public function updateAssetValuations(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $banks = BankFile::with('bankdetail')->where('file_id',$data['fileid'])->get();
            $banks = json_decode(json_encode($banks),true);
            $valuation = '<input type="hidden" name="file_id" value='.$data['fileid'].'>
                            <input type="hidden" name="asset_id" value='.$data['assetid'].'>';
                            foreach($banks as $bank){
                                $getvaluation = AssetValuation::getvaluation($data['fileid'],$data['assetid'],$bank['bank_id']);
                                $filledvalue1 ="";
                                if($getvaluation){
                                    $filledvalue1 = $getvaluation['value1'];
                                }
                                $val1 = "bank[".$bank['bank_id']."][val1]";
                                $valuation .= '<tr>
                                                <td>'.$bank['bankdetail']['short_name'].'</td>
                                                <td><input type="number" class="form-control" name="'.$val1.'" placeholder="Val-1" required value='.$filledvalue1.'></td>
                                            </tr>';
                            }
            return $valuation;
        }elseif($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach($data['bank'] as $bkey=> $bank){
                $checkifexists = AssetValuation::where(['file_id'=>$data['file_id'],'asset_id'=>$data['asset_id'],'bank_id'=>$bkey])->first();
                if($checkifexists){
                    $pvaluation = AssetValuation::find($checkifexists->id);
                }else{
                    $pvaluation = new AssetValuation;
                }
                $pvaluation->file_id = $data['file_id'];
                $pvaluation->asset_id = $data['asset_id'];
                $pvaluation->bank_id = $bkey;
                $pvaluation->value1 = $bank['val1'];
                $pvaluation->created_by = Session::get('empSession')['id'];
                $pvaluation->save();
            }
            return redirect()->back()->with('flash_message_success','Asset Valution has been updated successfully');
        }
    }

    public function addReference(Request $request,$fileid,$referid=null){
        Session::put('active',13);
        $referdetail= array();
        if($referid){
            $referdetail = FileRefer::where('id',$referid)->first();
            $referdetail = json_decode(json_encode($referdetail),true);
        }
        if($request->isMethod('post')){
            $data = $request->all();
            $getcolumns = DB::getSchemaBuilder()->getColumnListing('file_refers');
            if($referid){
                $addrefer = FileRefer::find($referid);
            }else{
                $addrefer = new FileRefer;
            }
            $addrefer->file_id = $fileid;
            foreach ($data as $key => $postdata) {
                if(in_array($key, $getcolumns)){
                    $addrefer->$key = $postdata;
                }
            }
            $addrefer->save();
            return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Reference has been updated successfully!');           
        }
        $title="Add Reference - Express Paisa";
        $filedetails = File::where('id',$fileid)->first();
        $filedetails = json_decode(json_encode($filedetails),true);
        return view('admin.files.add-reference')->with(compact('title','filedetails','referdetail'));
    }

    public function updateEligibilityDetails(Request $request,$fileid){
        $file = File::join('clients','clients.id','=','files.client_id')->with(['filebanks','filestatus','eligibilityfiles'])->select('files.*','clients.name as client_name')->find($fileid);
        $filedetails = json_decode(json_encode($file),true);
        if($filedetails['move_to'] =="operations"){
            Session::put('active',16);
        }
        if($request->isMethod('post')){
            $data = $request->all();
           
            if(isset($data['file_status'])){
                $filestatus = new FileStatusDetail;
                $filestatus->file_id = $fileid;
                $filestatus->status = $data['file_status'];
                $filestatus->comments = $data['comments'];
                $filestatus->updated_by = Session::get('empSession')['id'];
                $filestatus->save();
                $banks = array();
                if($data['file_status'] == "Move to Bank"){
                    if(!empty($data['banks'])){
                        $file->banks()->sync($data['banks']);
                        $banks = Bank::wherein('id',$data['banks'])->select('short_name')->get();
                        $banks = array_flatten(json_decode(json_encode($banks),true));
                        $banks = implode(',',$banks);
                    }
                }   
            }
            if ($request->hasFile('eligibility_file')) {
                $file = $request->file('eligibility_file');
                $eligibleFile = new EligibilityFile;
                $filename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $filename."-".str_random(3)."-".time().".".$extension;
                $destinationPath = 'images/EligibilityFiles'.'/';
                $file->move($destinationPath, $fileName);
                $eligibleFile->file_id = $fileid;
                $eligibleFile->filename = $fileName;
                $eligibleFile->created_by = Session::get('empSession')['id'];
                $eligibleFile->save();
            }
            if(isset($data['file_status'])){
                if($this->mode=="live"){
                    $emails =  Employee::getempTray($filedetails);
                   /* $emails = array('kunal100@yopmail.com');*/
                    $client = Client::find($filedetails['client_id']);
                    $messageData = [
                        'clientdetails' => $client,
                        'filedetails' =>$filedetails,
                        'data' => $data,
                        'banks' => $banks,
                    ];
                    Mail::send('emails.send-file-status-email', $messageData, function($message) use ($emails,$filedetails){
                        $message->to($emails)->subject('File status Updated successfully #'.$filedetails['file_no']);
                    });
                }
            }
            if($data['save']=="Move"){
                $ifAlreadysentforApproval = FileApproval::where('file_id',$fileid)->count();
                if($ifAlreadysentforApproval ==0){
                    $getbm = FileEmployee::where(['file_id'=>$fileid,'type'=>'bm'])->first();
                    if($getbm){
                        $bm = $getbm->employee_id;
                    }else{
                        $bm = 2;
                    }
                    $approval = new FileApproval;
                    $approval->file_id =  $fileid;
                    $approval->type ="Move to Bank Approval";
                    $approval->approval_from = Session::get('empSession')['id'];
                    $approval->employee_id = $bm;
                    $approval->save();
                    if($this->mode=="live"){
                        //Send intimation email to bm
                        $approvalfrom = Session::get('empSession')['name'];
                        $empdetails = Employee::getemployee($bm);
                        $email = $empdetails['email'];
                        $name = $empdetails['name'];
                        $messageData = [
                            'filedetails' =>$filedetails,
                            'name' => $name,
                            'banks' => $banks,
                            'approvalfrom'=> $approvalfrom
                        ];
                        Mail::send('emails.send-approval-email', $messageData, function($message) use ($email,$filedetails){
                            $message->to($email)->subject('Move to Bank Approval Request #'.$filedetails['file_no']);
                        });
                    }
                    return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Eligibility details has been updated successfully. Note :- We have sent approval request to Business Manager and this file still in operations only.');
                }else{
                    return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Eligibility details has been updated successfully. Note :- This file already sent for approval to Business Manager.');
                }
            }else{
                return redirect::to('/s/admin/create-applicants/'.$fileid)->with('flash_message_success','Eligibility details has been updated successfully!.');
            }
        }
        $title="Update Eligibility Details - Express Paisa";
        return view('admin.files.update-eligibility-details')->with(compact('title','filedetails'));
    }

    public function downloadEligibilityFile($filename){
        return response()->download(public_path('images/EligibilityFiles/'.$filename));
    }

    public function appendOccupationForm(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $applicantdetails['occupation'] = $data['occupation'];
            return response()->json([
               'view' => (String)View::make('layouts.adminLayout.occupation-form')->with(compact('applicantdetails'))
            ]);
        }
    }

    public function appendCrm(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $indirectTypes = Employee::gettypes('indirect');
            $employees = Employee::getemployees('all');
            $getpartners = DB::table('channel_partners')->get();
            $getpartners = json_decode(json_encode($getpartners),true); 
            $appendData ="";
            foreach ($indirectTypes as $indkey => $indirect) {
                $appendData .=  '<div class="form-group col-md-6">
                                    <label class="col-md-6 control-label">'.$indirect['full_name'].':</label>
                                    <div class="col-md-6">
                                        <select name="emps[]" class="selectbox" required>
                                            <option value="">Select</option>';
                                            foreach($employees as $key => $emp){
                                                $appendData .= '<option value='.$indirect['short_name'].'-'.$emp['id'].'>'.$emp['name'] .' - '.$emp['emptype'].'</option>';
                                               
                                            }
                                            $appendData .= '</select>
                                    </div>
                                </div>';
                                if($indkey ==0){
                                    $appendData.='<div class="clearfix"></div>';
                                }
            }
            if($data['type'] =="indirect"){
                $appendData.='<div class="form-group col-md-6">
                                        <label class="col-md-6 control-label">Select Channel Partner :</label>
                                        <div class="col-md-6">
                                            <select name="channel_partner_id" class="selectbox" required>
                                                <option value="">Select</option>';
                                                foreach($getpartners as $key => $partner){
                                                    $appendData .= '<option value='.$partner['id'].'>'.$partner['name'] .' - '.$partner['type'].'</option>';
                                                   
                                                }
                                                $appendData .= '</select>
                                        </div>
                                    </div>';
            }
            return $appendData;
        }
    }

    public function addcheckList(Request $request,$fileid){

        $checklistfile = File::find($fileid);
        // if($checklistfile->type_of_interest ==""){
        //     return redirect()->back()->with('flash_message_error','You cannot update checklist because you have not added facility requirements');
        // }
        // if($checklistfile->department=="Mortgage"){
        //     $propertycount = FilePropertyDetail::where('file_id',$fileid)->count();
        //     if($propertycount == 0){
        //         return redirect()->back()->with('flash_message_error','You cannot update checklist because you have not added property details');
        //     }
        // }elseif($checklistfile->department =="Car Loan"){
        //     $propertycount = FileAssetDetail::where('file_id',$fileid)->count();
        //     if($propertycount == 0){
        //         return redirect()->back()->with('flash_message_error','You cannot update checklist because you have not added asset details.');
        //     }
        // }
        //check for file refernce
        // $filerefercount = FileRefer::where('file_id',$fileid)->count();
        // if($filerefercount == 0){
        //     return redirect()->back()->with('flash_message_error','You cannot update checklist because you have not added File References');
        // }
        $loanDetailsCount = FileLoanDetail::where('file_id',$fileid)->count();

        if($loanDetailsCount ==0){
            return redirect()->back()->with('flash_message_error','You cannot move to pending approval because you have not added Loan Details');
        }
        //Individual Applicants check
        // $indApplicants = IndividualApplicant::where('file_id',$fileid)->count();

        // if($indApplicants ==0){
        //     return redirect()->back()->with('flash_message_error','You cannot update checklist because you have not added Individual Applicant details');
        // }
        //Check for Financial/Income Details
        // $checkincomedetails = ApplicantFinancialDetail::where('file_id',$fileid)->count();

        // if($checkincomedetails ==0){
        //     return redirect()->back()->with('flash_message_error','You cannot update checklist. Please add atleast one Financial/ Income details of Individual or Non-individual Applicants by clicking on blue plus sign under Applicants');
        // }  
        $title="Checklist - Express Paisa";
        $getchecklists = Checklist::with(['subchecklist'=>function($query){
            $query->with('subchecklist');
        }])->where('parent_id','ROOT')->get();
        $getchecklists = json_decode(json_encode($getchecklists),true);

        $checkFileAlreadyMoved = MovedFile::where('file_id',$fileid)->where('move_type','pending_approval')->count();
        if($request->isMethod('post')){
            $data = $request->all();
            array_walk_recursive($data, function(&$data) {
                $data = strip_tags($data);
            });
            $request->merge($data);
            $message="Checklist has been updated successfully!";
            $ifAlreadysentforApproval = FileApproval::where('file_id',$fileid)->count();

            if($data['save'] =="Move"){
                $message="Checklist has been updated and moved to pending approval!";
                $movefile = new MovedFile;
                $movefile->file_id = $fileid;
                $movefile->move_type="pending_approval";
                $movefile->details = "Moved to Pending Approval";
                $movefile->moved_by = Session::get('empSession')['id'];
                $movefile->save();
                if($checkFileAlreadyMoved ==0){
                    $checklistfile->move_to = "operations";
                    $checklistfile->save();
                }
                if($ifAlreadysentforApproval ==0){
                    $getbm = FileEmployee::where(['file_id'=>$fileid,'type'=>'bm'])->first();
                    if($getbm){
                        $bm = $getbm->employee_id;
                    }else{
                        $bm = 2;
                    }
                    $approval = new FileApproval;
                    $approval->file_id =  $fileid;
                    $approval->type ="Move to Pending Approval";
                    $approval->approval_from = Session::get('empSession')['id'];
                    $approval->employee_id = $bm;
                    $approval->save();
                }
            }
            if(!empty($data['checklist'])){
                $fileappr = FileApproval::find($fileid);
                
                $checklistfile->checklists()->sync($data['checklist']);
                $filecheck = DB::table('file_approvals')->where('file_id',$fileid)->get();
                $filecheck = json_decode(json_encode($filecheck),true);
               $ifAlreadysentforApproval = FileApproval::where('file_id',$fileid)->count();
                 // dd($filecheck);
                 if($filecheck[0]['status'] == "login"){
                    $filed = FileApproval::where('file_id',$fileid)->update(array('status' => 'pending_approval'));
                     
                
                    $fileinfo = File::find($fileid);
                    $fileinfo->move_to = "operations";
                    $fileinfo->save();

                    $movedfile = new MovedFile;
                    $movedfile->file_id = $fileid;
                    $movedfile->move_type = "pending_approval";
                    $movedfile->details = "Move to Pending Approval";
                    $movedfile->moved_by = Session::get('empSession')['id'];
                    $movedfile->save();
                 }

                 return redirect()->action('FileController@pendingApprovals')->with('flash_message_success','File Details has been move to pending approval successfully');


            }else{
                FileChecklist::where('file_id',$fileid)->delete();
            }
           
            return redirect()->back()->with('flash_message_success',$message);
        }
        return view('admin.files.add-checklist')->with(compact('title','getchecklists','fileid','checkFileAlreadyMoved'));
    }

    public function addLoanDetails(Request $request,$fileid){ 
         $filedata = DB::table('files')->where('id',$fileid)->first();
             $filedata = json_decode(json_encode($filedata),true);
            
        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data);
               // dd(Session::get('lprog'));
            $totalemiamt = 0;
            if(!isset($data['date'])){
            foreach($data['customer_name'] as $loankey => $loandata){
                if(isset($data['loan_id'][$loankey])){
                    $fileLoandetail = FileLoanDetail::find($data['loan_id'][$loankey]);
                }else{
                    $fileLoandetail = new FileLoanDetail;
                }
                $fileLoandetail->file_id = $fileid;
                // $fileLoandetail->lan = $loandata;
                if($filedata['move_to'] != "login"){
                  $fileLoandetail->date =$data['date'][$loankey];
                }else{

                  $fileLoandetail->date = "";
                  
                }
                $fileLoandetail->customer_name =$data['customer_name'][$loankey];
                $fileLoandetail->bank_name =$data['bank_name'][$loankey];
                $fileLoandetail->banker_name =(isset($data['banker_name'][$loankey]))?$data['banker_name'][$loankey]:'';
                $fileLoandetail->type =$data['type'][$loankey];
                $fileLoandetail->program =$data['program'][$loankey];
                $fileLoandetail->loan_amt =$data['loan_amt'][$loankey];
                $fileLoandetail->remarks = $data['remarks'][$loankey];
                // $fileLoandetail->foir = $data['foir'][$loankey];
                
                $fileLoandetail->save();
            }
            }else{
                foreach($data['customer_name'] as $loankey => $loandata){
                 if(isset($data['loan_id'][$loankey])){
                    $fileLoandetail = FileLoanDetail::find($data['loan_id'][$loankey]);
                 }
                 $fileLoandetail->file_id = $fileid;
                 $fileLoandetail->date =$data['date'][$loankey];
                 $fileLoandetail->customer_name =$data['customer_name'][$loankey];
                 $fileLoandetail->loan_amt =$data['loan_amt'][$loankey];
                 $fileLoandetail->remarks = $data['remarks'][$loankey];
                 $fileLoandetail->save();
                }
                $bankname = Session::get('bankname');
                $loantype = Session::get('ltype');
                $loanprog = Session::get('lprog');
                foreach($bankname as $bank){
                   $fileLoandetail->bank_name = $bank;
                   $fileLoandetail->save(); 
                }
                foreach($loantype as $lntype){
                   $fileLoandetail->type = $lntype;
                   $fileLoandetail->save(); 
                }
                foreach($loanprog as $lnprog){
                   $fileLoandetail->program = $lnprog;
                   $fileLoandetail->save(); 
                }
            }

            $updateEmiAmt = File::find($fileid);
            $updateEmiAmt->total_emi_amt = $totalemiamt;
            $updateEmiAmt->save();
            if($fileLoandetail->date != ""){
                //Update File Status
                 $file = File::find($fileid);
                 $file->move_to = "approved";
                 $file->save();
                //move file History
                 $movefile = new MovedFile;
                 $movefile->file_id = $fileid;
                 $movefile->move_type="approved";
                 $movefile->details = "Moved to Approved Files";
                 $movefile->moved_by = Session::get('empSession')['id'];
                 $movefile->save();
                 return redirect()->action('FileController@files')->with('flash_message_success','Loan Details has been updated and moved to approved files successfully');

            }else{
                $sum = 0;
                $sum += array_sum($data['loan_amt']);

                return redirect()->action('FileController@files')->with('flash_message_success','Loan Details has been updated successfully');
                if($sum == $filedata['loan_amount']){
                    return redirect()->action('FileController@files')->with('flash_message_success','Loan Details has been updated successfully');
                }else{
                    return redirect()->back()->with('flash_message_success','Required loan amount has not been filled correctly. But Loan Details has been updated successfully');
                }
                
            }
        }
        $loandetails = FileLoanDetail::where('file_id',$fileid)->get();
        $loandetails = json_decode(json_encode($loandetails),true);
        // dd($loandetails);
        // $fileDetails = DB::table('files')->select('total_emi_amt')->where('id',$fileid)->first();
       
        // dd($fileDetails);
        // $totalemiamt = $fileDetails->total_emi_amt;
       
       
        $clientid = $filedata['client_id'];
        $clientdata = DB::table('clients')->where('id',$clientid)->first();
        $clientdata = json_decode(json_encode($clientdata),true);
        
        $title="Add Loan Details - Express Paisa";
        return view('admin.files.add-loan-details')->with(compact('title','fileid','loandetails','clientdata','filedata'));
    }

    public function addBankDetails(Request $request,$fileid){
         // dd($fileid);
        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data);
             // dd(Session::get('bankname'));
            foreach($data['customer_name'] as $loankey => $loandata){
                
                if(isset($data['loan_id'][$loankey])){
                   
                    $fileBankdetail = BankDetail::find($data['loan_id'][$loankey]);

                    if(empty($fileBankdetail)){

                       
                    $fileBankdetail = new BankDetail;
                }else{
                      
                      $fileBankdetail = BankDetail::find($data['loan_id'][$loankey]);
                    
                }
              }
                $fileBankdetail->file_id = $fileid;

                // $fileBankdetail->lan = $loandata;
                $fileBankdetail->customer_name =(isset($data['customer_name']))?$data['customer_name'][$loankey]:'';
                $fileBankdetail->bank_name =(isset($data['bank_name']))?$data['bank_name'][$loankey]:'';
                $fileBankdetail->type =(isset($data['type']))?$data['type'][$loankey]:'';
                $fileBankdetail->program =(isset($data['program']))?$data['program'][$loankey]:'';
                $fileBankdetail->loan_amt =(isset($data['loan_amt']))?$data['loan_amt'][$loankey]:'';
                $fileBankdetail->status = (isset($data['status']))?$data['status'][$loankey]:'';
                 $fileBankdetail->lan = (isset($data['lan'][$loankey]))?$data['lan'][$loankey]:'';
                $fileBankdetail->approved_amount = (isset($data['approved_amount'][$loankey]))?$data['approved_amount'][$loankey]:'';
                $fileBankdetail->date = (isset($data['date'][$loankey]))?$data['date'][$loankey]:'';
                $fileBankdetail->roi = (isset($data['roi'][$loankey]))?$data['roi'][$loankey]:'';
                $fileBankdetail->processing_fees_percent = (isset($data['processing_fees_percent'][$loankey]))?$data['processing_fees_percent'][$loankey]:'';
                $fileBankdetail->processing_fees_amount = (isset($data['processing_fees_amount'][$loankey]))?$data['processing_fees_amount'][$loankey]:'';
                $fileBankdetail->disbursement_type = (isset($data['disbursement_type'][$loankey]))?$data['disbursement_type'][$loankey]:'';
                $fileBankdetail->emi_amount = (isset($data['emi_amount'][$loankey]))?$data['emi_amount'][$loankey]:'';
                $fileBankdetail->emi_start_date = (isset($data['emi_start_date'][$loankey]))?$data['emi_start_date'][$loankey]:'';
                $fileBankdetail->emi_end_date = (isset($data['emi_end_date'][$loankey]))?$data['emi_end_date'][$loankey]:'';
                $fileBankdetail->tenure_in_months = (isset($data['tenure_in_months'][$loankey]))?$data['tenure_in_months'][$loankey]:'';
                
                $fileBankdetail->remarks = (isset($data['remarks'][$loankey]))?$data['remarks'][$loankey]:'';
                // $fileBankdetail->foir = $data['foir'][$loankey];
                $fileBankdetail->agree = (isset($data['agree'][$loankey]))?$data['agree'][$loankey]:'No';
                
                $fileBankdetail->save();
                }
           
               
            foreach($data['agree'] as $status){
                if($status != '' && isset($data['disbursement_type']))
                {
                        foreach($data['disbursement_type'] as $disbtype){

                            if($status == "No" && $disbtype == "Fully Disbursed"){
                                File::where('id',$fileid)->update(['move_to'=>'declined']);
                                $movefile = new MovedFile;
                                $movefile->file_id = $fileid;
                                $movefile->move_type="declined";
                                $movefile->details = "Moved to Declined Files";
                                $movefile->save();
                                $message = "File moved to declined files";
                                return redirect()->back()->with('flash_message_success','Bank Details has been updated successfully and File moved to declined files!');
                            }elseif($status == "No" && $disbtype == "Partially Disbursed"){
                                File::where('id',$fileid)->update(['move_to'=>'declined']);
                                $movefile = new MovedFile;
                                $movefile->file_id = $fileid;
                                $movefile->move_type="declined";
                                $movefile->details = "Moved to Declined Files";
                                $movefile->save();
                                $message = "File moved to declined files";
                                return redirect()->back()->with('flash_message_success','Bank Details has been updated successfully and File moved to declined files!');

                            }
                            elseif($status == "Yes" && $disbtype == "Fully Disbursed"){
                                File::where('id',$fileid)->update(['move_to'=>'disbursement']);
                                $disbursementdata = FileDisbursement::where('file_id',$fileid)->where('disb_type','disbursed')->first();
                                
                                $movefile = new MovedFile;
                                $movefile->file_id = $fileid;
                                $movefile->move_type="disbursement";
                                $movefile->details = "Moved to Disbursed Files";
                                $movefile->save();
                                $message = "File moved to Disbursed files";
                                if($disbursementdata){
                                    $disbursement = FileDisbursement::find($disbursementdata->id);
                                }else{
                                    

                                   $disbursement = new FileDisbursement; 
                                   $disbursement->file_id = $fileid;
                                }
                                $disbursement->save();
                                return redirect()->action('FileController@disbursementFiles')->with('flash_message_success','Bank Details has been updated and moved to disbursed files successfully');

                            }else{
                                 File::where('id',$fileid)->update(['move_to'=>'partially']);
                                 $disbursementdata = FileDisbursement::where('file_id',$fileid)->where('disb_type','partially_disbursed')->first();
                                $movefile = new MovedFile;
                                $movefile->file_id = $fileid;
                                $movefile->move_type="partially";
                                $movefile->details = "Moved to Partially Disbursed Files";
                                $movefile->save();
                                $message = "File moved to Partial Disbursed files";
                                if($disbursementdata){
                                    $disbursement = FileDisbursement::find($disbursementdata->id);
                                }else{
                                    

                                   $disbursement = new FileDisbursement;
                                   $disbursement->disb_type = "partially_disbursed"; 
                                   $disbursement->file_id = $fileid;
                                }
                                $disbursement->save();
                                return redirect()->action('FileController@partiallydisbursementFiles')->with('flash_message_success','Bank Details has been updated and moved to partially disbursed files successfully');
                            }
                        }
                    } else {
                        // Last case declined
                        File::where('id',$fileid)->update(['move_to'=>'declined']);
                        $movefile = new MovedFile;
                        $movefile->file_id = $fileid;
                        $movefile->move_type="declined";
                        $movefile->details = "Moved to Declined Files";
                        $movefile->save();
                        $message = "File moved to declined files";
                        return redirect()->back()->with('flash_message_success','Bank Details has been updated successfully and File moved to declined files!');
                    }
                
                }
           }
           
       
        $loandetails = FileLoanDetail::where('file_id',$fileid)->get();
        $loandetails = json_decode(json_encode($loandetails),true);
        $bankdetails = BankDetail::where('file_id',$fileid)->get();
        $bankdetails = json_decode(json_encode($bankdetails),true);
        // dd($loandetails);
        // $fileDetails = DB::table('files')->select('total_emi_amt')->where('id',$fileid)->first();
       
        // dd($fileDetails);
        // $totalemiamt = $fileDetails->total_emi_amt;
        $filedata = DB::table('files')->where('id',$fileid)->first();
        $filedata = json_decode(json_encode($filedata),true);
       
        $clientid = $filedata['client_id'];
        $clientdata = DB::table('clients')->where('id',$clientid)->first();
        $clientdata = json_decode(json_encode($clientdata),true);
        
        $title="Add Bank Details - Express Paisa";
        return view('admin.files.add-bankdetails')->with(compact('title','fileid','loandetails','clientdata','filedata','bankdetails'));

    }

    public function calculateInstallments (Request $request) {
        if($request->ajax()) {
            $data = $request->all();
            $date1 = $data['emidate'];
            $tenure = $data['tenure'];
            $date2 = date("Y-m-d");
            $ts1 = strtotime($date1);
            $ts2 = strtotime($date2);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1) +1;
            $resp = array('diff'=> $diff, 'tenure'=> $tenure - $diff);
            return $resp;
        }
    }

    public function appendFileStatusForm(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $getstatus = TrackerStatus::where('type',$data['type'])->get();
            $appendform = '<input type="hidden" id="Fileid" name="file_id" value="'.$data['fileid'].'">
                        <input type="hidden" id="Fileid" name="bank_id" value="'.$data['bankid'].'">
                        <input type="hidden" id="StatusType" name="type" value="'.$data['type'].'"">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Select Status:</label>
                            <select class="form-control" name="status" id="getStatus" required>
                                <option value="">Please Select</option>';
                                foreach($getstatus as $status){
                                    $appendform .= '<option data-isamount="'.$status['is_amount'].'" data-isdate='.$status['is_date'].' value="'.$status['name'].'">'.$status['name'].'</option>';
                                }
                             $appendform .='</select>
                        </div>
                        <div id="AppendDateData"></div>
                        <div id="AppendAmountData"></div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Comments:</label>
                                <textarea name="comments" placeholder="Enter Comments" class="form-control" id="message-text"></textarea>
                        </div>';

            return array('appendform' => $appendform,'type'=> $data['type']);
        }
    }

    public function updateBankFileStatus(Request $request){
        if($request->isMethod('post')){
            $successmessage = "Bank Tracker Status has been updated successfully";
            $data = $request->all(); 
            if($data['type']=="approval" && $data['status'] =="Approved"){
                $filedetails = File::select('department')->where('id',$data['file_id'])->first();
                if($filedetails->department =="Mortgage"){
                    $totalProperties = FilePropertyDetail::where('file_id',$data['file_id'])->count();
                    $getValuationCount = PropertyValuation::where('file_id',$data['file_id'])->count();
                    if($totalProperties != $getValuationCount){
                        return redirect()->back()->with('flash_message_error','We cannot moved file to approval because you have not added the property valuations');
                    }
                }elseif ($filedetails->department =="Car Loan") {
                    $totalAssets = FileAssetDetail::where('file_id',$data['file_id'])->count();
                    $getValuationCount = AssetValuation::where('file_id',$data['file_id'])->count();
                    if($totalAssets != $getValuationCount){
                        return redirect()->back()->with('flash_message_error','We cannot moved file to approval because you have not added the asset valuations');
                    }
                }
                //check Non individual Applicants financial details
                $checkNonindApps  = NonindividualApplicant::where('file_id',$data['file_id'])->count();
                if($checkNonindApps !=0){
                    $checknonFinancialDetails = ApplicantFinancialDetail::where(['file_id'=>$data['file_id'],'type'=>'non-individual'])->count();
                    if($checknonFinancialDetails ==0){
                        return redirect()->back()->with('flash_message_error','We cannot moved file to approval because you have not added the Non individual Applicant Financial details');
                    }
                }else{
                    $checkFinancialDetails = ApplicantFinancialDetail::where(['file_id'=>$data['file_id'],'type'=>'individual'])->count();
                    if($checkFinancialDetails==0){
                        return redirect()->back()->with('flash_message_error','We cannot moved file to approval because you have not added the Individual Applicant Financial details');
                    }
                }
            }
            $bankFileTracker= new BankFileTracker;
            $bankFileTracker->file_id = $data['file_id'];
            $bankFileTracker->bank_id = $data['bank_id'];
            $bankFileTracker->type = $data['type'];
            $bankFileTracker->status = $data['status'];
            $bankFileTracker->comments = $data['comments'];
            $bankFileTracker->created_by = Session::get('empSession')['id'];
            if(isset($data['date'])){
                $bankFileTracker->date = $data['date'];
            }
            if(isset($data['amount'])){
                $bankFileTracker->amount = $data['amount'];
            }
            if($data['type'] =="approval"){
                if(isset($data['date']) && isset($data['amount'])){
                    File::where('id',$data['file_id'])->update(['approved_date'=> $data['date'],'approved_amount'=> $data['amount']]);
                }
            }
            $bankFileTracker->save();
            $id = DB::getPdo()->lastInsertId();
            $trackerstatusdetails = TrackerStatus::trackerstatus($data['status'],$data['type']);
            if($trackerstatusdetails && $trackerstatusdetails['move_to'] !="no"){
                $filestatus  = File::find($data['file_id']);
                $filestatus->move_to = $trackerstatusdetails['move_to'];
                $filestatus->save();
                $successmessage = "Files has been moved to ".$trackerstatusdetails['move_to']." files";
                // Moved Files History
                $movefile = new MovedFile;
                $movefile->file_id = $data['file_id'];
                $movefile->move_type=$trackerstatusdetails['move_to'];
                $movefile->details = "Moved to ".$trackerstatusdetails['move_to']." Files";
                $movefile->moved_by = Session::get('empSession')['id'];
                $movefile->save();
            }
            if($this->mode=="live"){
                $checkForEmail = TrackerStatus::where('type',$data['type'])->first();
                if($checkForEmail && $checkForEmail->send_email =="yes"){
                    $details =  BankFileTracker::with(['createdby','bankdetail'])->where('id',$id)->first();
                    $details = json_decode(json_encode($details),true);
                    $filedetails = File::where('id',$data['file_id'])->first();
                    $filedetails = json_decode(json_encode($filedetails),true);
                    $client = Client::find($filedetails['client_id']);
                    $emails =  Employee::getempTray($filedetails);
                    /*$emails = array('kunal100@yopmail.com');*/
                    $messageData = [
                        'clientdetails' => $client,
                        'filedetails' => $filedetails,
                        'details' => $details,
                        'type' =>ucwords($data['type']),
                    ];
                    Mail::send('emails.file-tracker-email', $messageData, function($message) use ($emails,$filedetails){
                        $message->to($emails)->subject('Bank File Tracker Status #'.$filedetails['file_no']);
                    });
                }
            }
            return redirect()->back()->with('flash_message_success',$successmessage);
        }
    }

    public function getBankFileHistory(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $bankTrackers = BankFileTracker::with('createdby')->where(['type'=> $data['type'],'bank_id'=>$data['bankid'],'file_id'=> $data['fileid']])->orderby('created_at','DESC')->get();
            $bankTrackers = json_decode(json_encode($bankTrackers),true);
            $appendData ='<thead>
                        <tr>
                            <th class="text-center">Status</th>
                            <th class="text-center">Comments</th>
                            <th class="text-center">Date</th>';
                            if($data['type'] =="approval"){
                                $appendData .= '<th class="text-center">Amount</th>';  
                            }
                        $appendData .='<th class="text-center">Updated By</th>
                                        <th class="text-center">Updated At</th>
                                    </tr>
                    </thead><tbody>';
            if(!empty($bankTrackers)){
                foreach ($bankTrackers as $key => $trackerdetail) {
                    $appendData .= '<tr>
                                        <td>'.$trackerdetail['status'].'</td>
                                        <td>'.$trackerdetail['comments'].'</td>
                                        <td>'.(!empty($trackerdetail['date']) ? date('d M Y',strtotime($trackerdetail['date'])):'' ).'</td>';
                                        if($data['type'] =="approval"){
                                           $appendData .= '<td>'.$trackerdetail['amount'].'</td>';
                                        }
                                    $appendData .='<td>'.$trackerdetail['createdby']['name'].'</td><td>'.date('d M Y h:ia',strtotime($trackerdetail['created_at'])).'</td></tr>';
                }
            }else{
                $appendData .= '<tr><td colspan="5">No history found.</td></tr>';
            }
            $appendData .= '</tbody>';
            return array('appendData'=> $appendData,'type'=> ucwords($data['type']));
        }
    }

    public function pendingApprovals(Request $Request){
        Session::put('active',20); 
        $title = "Move to Bank Approvals - Express Paisa";
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = FileApproval::join('files','files.id','=','file_approvals.file_id')->join('clients','clients.id','=','files.client_id')->join('employees','employees.id','=','file_approvals.approval_from')->select('file_approvals.*','files.file_no','files.id as fileid','files.facility_type','clients.customer_name','clients.company_name','employees.name as empname');
			$querys = $querys->where('file_approvals.status','pending_approval');
            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->where('file_approvals.employee_id',Session::get('empSession')['id']);
            }
            if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
            if(!empty($data['name'])){
                $querys = $querys->where('clients.customer_name','like','%'.$data['name'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['facility_type'])){
                $querys = $querys->where('files.facility_type','like','%'.$data['facility_type'].'%');
            }
            $querys = $querys->OrderBy('file_approvals.created_at','DESC');
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
            }else{
               /* $querys = $this->pendingfiledata(); */
                
				$file_id_list = $this->pendingfiledataList();
				$file_id_list=json_decode( json_encode($file_id_list), true);
				$file_id_array = array_column($file_id_list, 'id');
				
				
				
				$querys = FileApproval::join('files','files.id','=','file_approvals.file_id')->join('clients','clients.id','=','files.client_id')->join('employees','employees.id','=','file_approvals.approval_from')->select('file_approvals.*','files.file_no','files.id as fileid','files.facility_type','clients.customer_name','clients.company_name','employees.name as empname');
				$querys = $querys->wherein('file_approvals.id',$file_id_array); 
				$querys = $querys->where('file_approvals.status','pending_approval');
				
				
                $access = Employee::checkAccess();
                $f_no = array();
                $fcus_name = array();
                $fcomp_name = array();
                
                if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
				if(!empty($data['name'])){
					$querys = $querys->where('clients.customer_name','like','%'.$data['name'].'%');
				}
				if(!empty($data['company_name'])){
					$querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
				}
				if(!empty($data['facility_type'])){
					$querys = $querys->where('files.facility_type','like','%'.$data['facility_type'].'%');
				}
                
                 $iTotalRecords = $querys->count();
				 $iDisplayLength = intval($_REQUEST['length']);
                 $iDisplayStart = intval($_REQUEST['start']);
				 $sEcho = intval($_REQUEST['draw']);
				 $querys =  $querys
                	->skip($iDisplayStart)->take($iDisplayLength)
                	->get();
                
                 
                 $records = array();
                 $records["data"] = array(); 
                 $end = $iDisplayStart + $iDisplayLength;
                 $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                 $i=$iDisplayStart;
            }

            $isAccess = 0;
            if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['type'] != "HR-M" && Session::get('empSession')['type'] !="hro" && Session::get('empSession')['is_access'] !="full"){
            	$isAccess = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'edit_access'=>'1','module_id'=>'25'])->select('module_id')->count();
            } else {
            	$isAccess = 1;
            }
            $action = '';
           
            foreach($querys as $approval){
                if($approval['status'] =="not approved"){
                	if($isAccess == 1)
                	{
                		$action = '<a title="Approve & Move to Bank" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="'.url('/s/admin/approve-move-file/'.$approval['id']).'">Approve</a><br><a title="Decline & Move to Decline Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="'.url('/s/admin/decline-move-file/'.$approval['id']).'">Decline</a><br>';
                	}
                } else{
                     
                        if($isAccess == 1)
                        {
                          $action = "Moved successfully! (".date('d M Y h:ia',strtotime($approval['updated_at'])).")";
                          if($action && $approval['status'] =="pending_approval"){
                          	$action = '<a title="Approve & Move to Bank" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="'.url('/s/admin/approve-move-file/'.$approval['id']).'">Approve</a><br><a title="Decline & Move to Decline Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="'.url('/s/admin/decline-move-file/'.$approval['id']).'">Decline</a><br>';
                          }
                       }
                     
                        
                }
                $approvedby ="";
                if($approval['approved_by'] !=0){
                    $approvedby = $this->empinfowithoutType($approval['approved_by']);
                }
                $num = ++$i;


                if($isAccess == 1)
                {
                    $file_link = '<a target="_blank" title="View File Details" class="btn btn-sm green" href="'.url('/s/admin/create-applicants/'.$approval['fileid']).'?open=modal">'.$approval['file_no'].'</i></a>';
                } else {
                    $file_link = $approval['file_no'];
                }
                $records["data"][] = array(     
                    $file_link,
                    // $approval['facility_type'],  
                    $approval['customer_name'],  
                    $approval['company_name'],  
                    $approval['empname'],
                    $approvedby,
                    $action
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        return View::make('admin.files.pending-approvals')->with(compact('title'));
    }

    public function approveAndMove($approveid){

        $details = FileApproval::find($approveid);

        $details->approved_by = Session::get('empSession')['id'];
        $details->type = "Moved to bank files";
        $details->status ="approved";
        $details->save();
        //Update File Status
        $file = File::find($details->file_id);
        $file->move_to = "bank";
        $file->save();

        //move file History
        $movefile = new MovedFile;
        $movefile->file_id = $details->file_id;
        $movefile->move_type="bank";
        $movefile->details = "Moved to Bank Files";
        $movefile->moved_by = Session::get('empSession')['id'];
        $movefile->save();
        return redirect()->action('FileController@pendingApprovals')->with('flash_message_success','File has been moved to bank successfully');
    }

    public function declineAndMove($approveid){

        $details = FileApproval::find($approveid);
        $details->approved_by = Session::get('empSession')['id'];
        $details->type = "Moved to declined files";
        $details->status ="declined";
        $details->save();

        //Update File Status
        $file = File::find($details->file_id);
        $file->move_to = "declined";
        $file->save();

        //move file History
        $movefile = new MovedFile;
        $movefile->file_id = $details->file_id;
        $movefile->move_type="declined";
        $movefile->details = "Moved to Declined Files";
        $movefile->moved_by = Session::get('empSession')['id'];
        $movefile->save();
        return redirect()->action('FileController@pendingApprovals')->with('flash_message_success','File has been moved to declined files successfully');
    }

    public function wipAndMove($approveid){
        $details = FileApproval::find($approveid);
        $details->approved_by = Session::get('empSession')['id'];
        $details->type = "Moved to login files";
        $details->status ="login";
        $details->save();
        

        //Update File Status
        $file = File::find($details->file_id);
        $file->move_to = "login";
        $file->save();

        //move file History
        $movefile = new MovedFile;
        $movefile->file_id = $details->file_id;
        $movefile->move_type="login";
        $movefile->details = "Moved to Login Files";
        $movefile->moved_by = Session::get('empSession')['id'];
        $movefile->save();
        return redirect()->action('FileController@pendingApprovals')->with('flash_message_success','File has been moved to login files successfully');
    }

    public function updateDisbursement(Request $request, $fileid){

        $title = "Update Disbursement Details";
        $disbursementdata = FileDisbursement::where('file_id',$fileid)->first();
         $disbursementdata = json_decode(json_encode($disbursementdata),true);
         $bankdetails = DB::table('bank_details')->where('file_id',$fileid)->get();
         $bankdetails = json_decode(json_encode($bankdetails),true);

        $filedetails = File::find($fileid);
        $filedetails = json_decode(json_encode($filedetails),true);
        $filedata = File::find($fileid);
        // dd($filedata);
        $partialdetails = PartialFile::where('file_id',$fileid)->get();
        $partialdetails = json_decode(json_encode($partialdetails),true);
        
        if($request->isMethod('post')){
            
            if($filedetails['move_to'] =="approved" || $filedetails['move_to'] =="disbursement" || $filedetails['move_to'] =="partially"){

                $message ="Details updated successfully!";
                $data = $request->all();
                // dd($data);
                // dd($disbursementdata);
                if($disbursementdata){
                    $disbursement = FileDisbursement::find($disbursementdata['id']);
                }else{
                    $disbursement = new FileDisbursement; 
                    $disbursement->file_id = $fileid;
                }
                $disbursement->lan_no = $data['lan_no'];
                $disbursement->amount = $data['amount'];
                $disbursement->roi = (isset($data['roi']))?$data['roi']:'';
                $disbursement->tenure = (isset($data['tenure']))?$data['tenure']:'';
                $disbursement->pf_per = $data['pf_per'];
                $disbursement->pf_amt = $data['pf_amt'];
                $disbursement->disb_type = $data['disb_type'];
                $disbursement->emi_amt = $data['emi_amt'];
                $disbursement->first_emi_date = $data['first_emi_date'];
                $disbursement->last_emi_date = $data['last_emi_date'];
                if(isset($data['remarks'])){
                    $disbursement->remarks = $data['remarks'];
                }
                $moveto ="approved";
                if(isset($data['status'])){
                    if($data['status'] =="approved"){
                        $disbursement->status = "approved";
                        $disbursement->transaction_date = isset($data['transaction_date'])?$data['transaction_date']:'';
                        $disbursement->pdd = $data['pdd'];
                        $disbursement->welcome_kits = $data['welcome_kits'];
                        $disbursement->lod = $data['lod'];
                        $disbursement->chk = $data['chk'];
                        $message = "File Disbursed successfully.";
                        $moveto ="disbursement";
                    }elseif($data['status'] =="declined"){
                        $disbursement->status = "declined";
                        //Update File
                        // dd($filedetails['move_to']);
                        $filedata->move_to = "declined";
                        $filedata->save();
                         //Moved File History
                        $movefile = new MovedFile;
                        $movefile->file_id = $fileid;
                        $movefile->move_type="declined";
                        $movefile->details = "Moved to Declined Files";
                        $movefile->moved_by = Session::get('empSession')['id'];
                        $movefile->save();
                        $moveto ="declined";
                        $message = "Disbursement details updated successfully and file moved to declined files";
                    }elseif($data['status'] =="approved-partial"){
                        $disbursement->chk = $data['chk'];
                        $disbursement->status = "approved";
                        
                        $moveto ="partially";
                        $details ="Moved to Partially Disbursement Files";
                        $message = "Details updated successfully and file moved to Partially Disbursed files";
                        //save Partially Disbursed Data
                        if(isset($data['partial_date'])){
	                        foreach($data['partial_date'] as $pkey => $partialdate){
	                            $partial = new PartialFile;
	                            $partial->file_id = $fileid;
	                            $partial->partial_date = $partialdate;
	                            $partial->partial_amount = $data['partial_amount'][$pkey];
	                            $partial->created_by = Session::get('empSession')['id'];
	                            $partial->save();
	                        }
                        }
                    }
                }else{
                    if($data['disb_type'] =="disbursed"){
                        $disbursement->final_disbursement_date = $data['final_disbursement_date'];
                        $filedata->disbursement_date = $data['final_disbursement_date'];
                        $filedata->disbursement_amount = $data['amount'];
                        $moveto ="disbursement";
                        $details ="Moved to Disbursement Files";
                        $message = "Disbursement details updated successfully and file moved to Disbursement files";
                    }else{
                        $disbursement->chk = $data['chk'];
                        $moveto ="partially";
                        $details ="Moved to Partially Disbursement Files";
                        $message = "Details updated successfully and file moved to Partially Disbursed files";
                        //save Partially Disbursed Data
                        if(isset($data['partial_date'])){
	                        foreach($data['partial_date'] as $pkey => $partialdate){
	                            $partial = new PartialFile;
	                            $partial->file_id = $fileid;
	                            $partial->partial_date = $partialdate;
	                            $partial->partial_amount = $data['partial_amount'][$pkey];
	                            $partial->created_by = Session::get('empSession')['id'];
	                            $partial->save();
	                        }
                        }
                    }
                    //Update File
                    $filedata->move_to = $moveto;
                    $filedata->save();
                    // dd($filedetails);
                    //Moved File History
                    $movefile = new MovedFile;
                    $movefile->file_id = $fileid;
                    $movefile->move_type=$moveto;
                    $movefile->details = $details;
                    $movefile->moved_by = Session::get('empSession')['id'];
                    $movefile->save();
                    $message = $message;   
                }
                $disbursement->save();
                if($moveto =="disbursement"){
                    return redirect::to('/s/admin/disbursement-files')->with('flash_message_success',$message);
                }elseif($moveto =="partially"){
                    return redirect::to('/s/admin/partially-disbursement-files')->with('flash_message_success',$message);
                }elseif($moveto=="declined"){
                    return redirect::to('/s/admin/files?type=declined')->with('flash_message_success',$message);
                }else{
                    return redirect::to('/s/admin/files?type=approved')->with('flash_message_success',$message);
                }
            }else{
                return redirect()->action('AdminController@dashboard')->with('flash_message_error','You cannot update disbursement details');
            }
        }
       
       
        return view('admin.files.update-disbursement')->with(compact('title','disbursementdata','bankdetails','fileid','filedetails','partialdetails'));
    }

    public function partiallydisbursementFiles(Request $Request){
        Session::put('active',23); 
        $title = "Partially Disbursement Files - Express Paisa";
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = File::with('getbank')->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->join('file_employees','file_employees.file_id','=','files.id')->select('files.*','clients.name as client_name','clients.company_name','file_disbursements.emi_amt','file_disbursements.lan_no','file_disbursements.amount','file_employees.employee_id as salesofficer')->where('move_to','partially')->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id');

            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                        $query->where('employee_id',Session::get('empSession')['id']);
                });
            }
            if(!empty($data['bank'])){
                $bankid = $data['bank'];
                $querys = $querys->whereHas('filebank', function ($query) use($bankid) {
                    $query->where('bank_id',$bankid);
                });
            }
            if(!empty($data['department'])){
                $querys = $querys->where('files.department','like','%'.$data['department'].'%');
            }
            if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
            if(!empty($data['name'])){
                $querys = $querys->where('clients.customer_name','like','%'.$data['name'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['loan_type'])){
                $querys = $querys->where('files.loan_type','like','%'.$data['loan_type'].'%');
            }
            if(!empty($data['salesofficer'])){
                $querys = $querys->where('clients.created_emp',$data['salesofficer']);
            }
            $querys = $querys->OrderBy('files.id','DESC');
            //$iTotalRecords = $querys->where($conditions)->count();
            $query_count_array=json_decode( json_encode($querys->get()), true);
            $iTotalRecords = count($query_count_array);
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
                $querys = $this->partdisbdata();
                $f_no = array();
                $fcus_name = array();
                $fcomp_name = array();
                $f_salesoff = array();
                $f_loan = array();
                if(!empty($data['file_no'])){
                   foreach($querys as $qry){

                     array_push($f_no, $qry['file_no']);
                   }
                   $res = in_array($data['file_no'], $f_no);

                   if($res){
                    
                     $querys = DB::table('files')->where('file_no','like','%'.$data['file_no'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['customer_name'])){
                   foreach($querys as $qry){

                     array_push($fcus_name, $qry['customer_name']);
                   }
                   $res = in_array($data['customer_name'], $fcus_name);

                   if($res){
                    
                     $querys = DB::table('files')->where('customer_name','like','%'.$data['customer_name'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['company_name'])){
                   foreach($querys as $qry){

                     array_push($fcomp_name, $qry['company_name']);
                   }
                   $res = in_array($data['company_name'], $fcomp_name);

                   if($res){
                    
                     $querys = DB::table('files')->where('company_name','like','%'.$data['company_name'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['salesofficer'])){
                    foreach($querys as $qry){

                     array_push($f_salesoff, $qry['salesofficer']);
                   }
                   $res = in_array($data['salesofficer'], $f_salesoff);

                   if($res){
                    
                     $querys = DB::table('files')->where('salesofficer','like','%'.$data['salesofficer'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['loan_type'])){
                    foreach($querys as $qry){

                     array_push($f_loan, $qry['loan_type']);
                   }
                   $res = in_array($data['loan_type'], $f_loan);

                   if($res){
                    
                     $querys = DB::table('files')->where('loan_type','like','%'.$data['loan_type'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                 $iTotalRecords = count($querys);
                 $iDisplayLength = intval($_REQUEST['length']);
                 $iDisplayStart = intval($_REQUEST['start']);
                 $sEcho = intval($_REQUEST['draw']);
                 $records = array();
                 $records["data"] = array(); 
                 $end = $iDisplayStart + $iDisplayLength;
                 $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                 $i=$iDisplayStart;

            }
            foreach($querys as $file){
                 $fileloandt = FileLoanDetail::where('file_id',$file['id'])->get();
                 $fileloandt = json_decode(json_encode($fileloandt),true);
                 $arr = array();
                 $bank_arr = array();
                 $disbursement = FileDisbursement::where('file_id',$file['id'])->first();
                 $disbursement_amount = (isset($disbursement->amount))?$disbursement->amount:'';
                 // Temp not included disbursement amount for partial alone
                 foreach($fileloandt as $loandt){
                    array_push($arr, $loandt['loan_amt']);
                    array_push($bank_arr, $loandt['bank_name']);

                    $sum =  array_sum($arr);
                }
                 $bankn = implode(',', $bank_arr);

                 $client = Client::where('id',$file['client_id'])->first();
                $salesofficer = Employee::getemployee($client->created_emp);
                if(!empty($file['getbank'])){
                    $bank = $file['getbank']['bankdetail']['short_name'];
                }else{
                     $bank = $bankn;
                }
                $file_disb = DB::table('file_disbursements')->where('file_id',$file['id'])->first();
                $file_disb = json_decode(json_encode($file_disb),true);

                $isAccess = 0;
                if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['type'] != "HR-M" && Session::get('empSession')['type'] !="hro" && Session::get('empSession')['is_access'] !="full"){
                	$isAccess = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'edit_access'=>'1','module_id'=>'29'])->select('module_id')->count();
                } else {
                	$isAccess = 1;
                }
                $disbursement = '';
                $actionValues = '';

                if($isAccess == 1)
                {
                	$disbursement = '<a title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/update-disbursement-details/'.$file['id']).'">Update Disbursement</a>
	                <a title="Export History" class="btn btn-sm blue margin-top-10" href="'.url('s/admin/export-partially-files/'.$file['id']).'">Export History</a>';
	                $actionValues='<a title="View Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/create-applicants/'.$file['id']).'">View & Edit</a>';
                }
                
                if($file_disb['chk'] == "1"){
                    $disbursement = '<a title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/update-disbursement-details/'.$file['id']).'">View Status</a>';
                    $partialdetails = PartialFile::where('file_id',$file['id'])->first();
                    $partialdetails = json_decode(json_encode($partialdetails),true);
                    $partialamount = (isset($partialdetails['partial_amount']))?$partialdetails['partial_amount']:'0';
                    $actionValues = "Partially disbursed!. Disbursed Amount = $partialamount. (".date('d M Y h:ia',strtotime($file['updated_at'])).")";
                }
                $num = ++$i;
                if($isAccess == 1)
                {
                    $file_link = '<a target="_blank" title="View File Details" class="btn btn-sm margin-top-10 green" href="'.url('/s/admin/create-applicants/'.$file['id']).'?open=modal">'.$file['file_no'].'</i></a>';
                } else {
                    $file_link = $file['file_no'];
                }
                $records["data"][] = array(     
                    $file_link,
                    // $file['lan_no'],  
                    // $file['department'], 
                    $client['customer_name'],  
                    $file['company_name'],
                    '<b> Rs '. $sum.'</b>',
                    $file['facility_type'],  
                    $bank,
                    $salesofficer['name'],
                    $actionValues.$disbursement
                );
            
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        return View::make('admin.files.partially-disbursement')->with(compact('title'));
    }

    public function disbursementFiles(Request $Request){
        Session::put('active',19); 
        $title = "Disbursement Files - Express Paisa";
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();

            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $querys = File::with('getbank')->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->join('file_employees','file_employees.file_id','=','files.id')->select('files.*','clients.name as client_name','clients.company_name','file_disbursements.emi_amt','file_disbursements.lan_no','file_disbursements.amount','file_employees.employee_id as salesofficer')->where('move_to','disbursement')->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id');

            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                        $query->where('employee_id',Session::get('empSession')['id']);
                });
            }
            if(!empty($data['bank'])){
                $bankid = $data['bank'];
                $querys = $querys->whereHas('filebank', function ($query) use($bankid) {
                    $query->where('bank_id',$bankid);
                });
            }
            if(!empty($data['department'])){
                $querys = $querys->where('files.department','like','%'.$data['department'].'%');
            }
            if(!empty($data['file_no'])){
                $querys = $querys->where('files.file_no','like','%'.$data['file_no'].'%');
            }
            if(!empty($data['name'])){
                $querys = $querys->where('clients.customer_name','like','%'.$data['name'].'%');
            }
            if(!empty($data['company_name'])){
                $querys = $querys->where('clients.company_name','like','%'.$data['company_name'].'%');
            }
            if(!empty($data['loan_type'])){
                $querys = $querys->where('files.loan_type','like','%'.$data['loan_type'].'%');
            }
            if(!empty($data['salesofficer'])){
                $querys = $querys->where('clients.created_emp',$data['salesofficer']);
            }
            $querys = $querys->OrderBy('files.id','DESC');
            //$iTotalRecords = $querys->where($conditions)->count();
            $query_count_array=json_decode( json_encode($querys->get()), true);
            $iTotalRecords = count($query_count_array);
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
                $querys = $this->disbdata();
                $f_no = array();
                $fcus_name = array();
                $fcomp_name = array();
                $f_salesoff = array();
                $f_loan = array();
                if(!empty($data['file_no'])){
                   foreach($querys as $qry){

                     array_push($f_no, $qry['file_no']);
                   }
                   $res = in_array($data['file_no'], $f_no);

                   if($res){
                    
                     $querys = DB::table('files')->where('file_no','like','%'.$data['file_no'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['customer_name'])){
                   foreach($querys as $qry){

                     array_push($fcus_name, $qry['customer_name']);
                   }
                   $res = in_array($data['customer_name'], $fcus_name);

                   if($res){
                    
                     $querys = DB::table('files')->where('customer_name','like','%'.$data['customer_name'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['company_name'])){
                   foreach($querys as $qry){

                     array_push($fcomp_name, $qry['company_name']);
                   }
                   $res = in_array($data['company_name'], $fcomp_name);

                   if($res){
                    
                     $querys = DB::table('files')->where('company_name','like','%'.$data['company_name'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['salesofficer'])){
                    foreach($querys as $qry){

                     array_push($f_salesoff, $qry['salesofficer']);
                   }
                   $res = in_array($data['salesofficer'], $f_salesoff);

                   if($res){
                    
                     $querys = DB::table('files')->where('salesofficer','like','%'.$data['salesofficer'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                if(!empty($data['loan_type'])){
                    foreach($querys as $qry){

                     array_push($f_loan, $qry['loan_type']);
                   }
                   $res = in_array($data['loan_type'], $f_loan);

                   if($res){
                    
                     $querys = DB::table('files')->where('loan_type','like','%'.$data['loan_type'].'%')->get();
                     $querys = json_decode(json_encode($querys),true);

                   }
                }
                 $iTotalRecords = count($querys);
                 $iDisplayLength = intval($_REQUEST['length']);
                 $iDisplayStart = intval($_REQUEST['start']);
                 $sEcho = intval($_REQUEST['draw']);
                 $records = array();
                 $records["data"] = array(); 
                 $end = $iDisplayStart + $iDisplayLength;
                 $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                 $i=$iDisplayStart;

            }
            foreach($querys as $file){
                $clien = DB::table('clients')->where('id',$file['client_id'])->first();
                $clien = json_decode(json_encode($clien),true);

                $fileloandt = FileLoanDetail::where('file_id',$file['id'])->get();
                 $fileloandt = json_decode(json_encode($fileloandt),true);
                 $arr = array();
                 $bank_arr = array();
                 $disbursement = FileDisbursement::where('file_id',$file['id'])->first();
                 $disbursement_amount = (isset($disbursement->amount))?$disbursement->amount:'';
                 foreach($fileloandt as $loandt){
                    array_push($arr, $loandt['loan_amt']);
                    array_push($bank_arr, $loandt['bank_name']);

                    $sum =  ($disbursement_amount!= '' && $disbursement_amount!= '0')?$disbursement_amount:array_sum($arr);
                }
                 $bankn = implode(',', $bank_arr);
                $client = Client::where('id',$file['client_id'])->first();
                $salesofficer = Employee::getemployee($client->created_emp);
                if(!empty($file['getbank'])){
                    $bank = $file['getbank']['bankdetail']['short_name'];
                }else{
                     $bank =$bankn;
                }
                $file_disb = DB::table('file_disbursements')->where('file_id',$file['id'])->first();
                $file_disb = json_decode(json_encode($file_disb),true);

                $isAccess = 0;
                if(Session::get('empSession')['type'] != "admin" && Session::get('empSession')['type'] != "HR-M" && Session::get('empSession')['type'] !="hro" && Session::get('empSession')['is_access'] !="full"){
                	$isAccess = DB::table('employee_roles')->where(['emp_id'=>Session::get('empSession')['id'],'edit_access'=>'1','module_id'=>'24'])->select('module_id')->count();
                } else {
                	$isAccess = 1;
                }
                $disbursement = '';
                $actionValues = '';

                if($isAccess == 1)
                {
                	$disbursement = '<a title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/update-disbursement-details/'.$file['id']).'">Update Disbursement</a>
	                    <a title="Export History" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/export-file-history/'.$file['id']).'">Export History</a>';
	                $actionValues='<a title="View Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/create-applicants/'.$file['id']).'">View & Edit</a>';
                }
                
                if($file_disb['chk'] == "1"){
                    $disbursement = '<br><a title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="'.url('/s/admin/update-disbursement-details/'.$file['id'].'?action=view').'">View Status</a>';
                    $actionValues = "File Disbursed successfully! (".date('d M Y h:ia',strtotime($file['updated_at'])).")";
                }
                $num = ++$i;
                if($isAccess == 1)
                {
                    $file_link = '<a target="_blank" title="View File Details" class="btn btn-sm margin-top-10 green" href="'.url('/s/admin/create-applicants/'.$file['id']).'?open=modal">'.$file['file_no'].'</i></a>';
                } else {
                    $file_link = $file['file_no'];
                }
                $records["data"][] = array(     
                    $file_link,
                    // $file['lan_no'],  
                    // $file['department'], 
                    $clien['customer_name'],  
                    $file['company_name'],
                    '<b> Rs '. $sum.'</b>',
                    $file['loan_type'],  
                    $bank,
                    $salesofficer['name'],
                    $actionValues.$disbursement
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        return View::make('admin.files.disbursement-files')->with(compact('title'));
    }

    public function destroyFile($fileid){
        File::where('id',$fileid)->delete();
        MovedFile::where('file_id',$fileid)->delete();
        ApplicantFinancialDetail::where('file_id',$fileid)->delete();
        BankFile::where('file_id',$fileid)->delete();
        BankFileTracker::where('file_id',$fileid)->delete();
        FileChecklist::where('file_id',$fileid)->delete();
        FileApproval::where('file_id',$fileid)->delete();
        FileEmployee::where('file_id',$fileid)->delete();
        FileLoanDetail::where('file_id',$fileid)->delete();
        FilePropertyDetail::where('file_id',$fileid)->delete();
        FileAssetDetail::where('file_id',$fileid)->delete();
        FileRefer::where('file_id',$fileid)->delete();
        FileStatusDetail::where('file_id',$fileid)->delete();
        IndividualApplicant::where('file_id',$fileid)->delete();
        NonindividualApplicant::where('file_id',$fileid)->delete();
        PropertyValuation::where('file_id',$fileid)->delete();
        AssetValuation::where('file_id',$fileid)->delete();
        return redirect()->back()->with('flash_message_success','File has been deleted successfully and it cannot be recovered again');
    }

    public function movetoDeclined($fileid){
        File::where('id',$fileid)->update(['move_to'=>'declined']);
        $movefile = new MovedFile;
        $movefile->file_id = $fileid;
        $movefile->move_type="declined";
        $movefile->details = "Moved to Declined Files";
        $movefile->moved_by = Session::get('empSession')['id'];
        $movefile->save();
        $message = "File moved to declined files";
        return redirect()->back()->with('flash_message_success','File has been moved to declined files');
    }

    public function showBanker(Request $request)
    {
        $bank_short_name = $request->bankid;
        $bank_details = Bank::where('short_name',$bank_short_name)->first();
        $bank_details = json_decode(json_encode($bank_details),true);
        $bank_id = $bank_details['id'];
        $bank_details = Banker::where('bank_id',$bank_id)->orderby('banker_name','asc')->get();
        $bank_details = json_decode(json_encode($bank_details),true);
        echo json_encode($bank_details);
    }

    public function searchFiles(Request $request){
        Session::put('active',24);
        if($request->isMethod('get')){
            $files = array();
            $data = $request->all();
            if(isset($data['search_by']) && isset($data['search'])){
                $querys = File::with('getbank')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name');
                // ->where('file_employees.type','sales')
                // ,'file_employees.employee_id as salesofficer'
                // ->join('file_employees','file_employees.file_id','=','files.id')
                $access = Employee::checkAccess();
                if($access =="false"){
                    $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                            $query->where('employee_id',Session::get('empSession')['id']);
                    });
                }
                $getclientids = array();
                if($data['search_by'] =="company"){
                    $querys = $querys->where('clients.company_name','like','%'.$data['search'].'%');
                }else if($data['search_by'] =="file_no"){
                    $querys = $querys->where('files.file_no','like','%'.$data['search'].'%');
                }else{
                    $querys = $querys->where('clients.customer_name','like','%'.$data['search'].'%');
                }
                $querys = $querys->orderby('id','DESC')->get();
                $files = json_decode(json_encode($querys),true);
                //echo "<pre>"; print_r($files); die;
            }
        }
        $title="Search Files - Express Paisa";
        return view('admin.files.search-file')->with(compact('title','files'));
    }

    public function getFileSummary(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $statusArray = array('login' => 'Login File Date','operations'=>'Move to Operations Files Date','bank'=>'Move to Bank Date','approved'=>'Move to Approved File Date','partially'=>'Move to Partial Disburse','disbursement'=>'Move to Disbursement File Date');
            $appendsummary ="";
            foreach($statusArray as $skey=> &$status){
                $checktracker = MovedFile::with('movedby')->where(['file_id'=>$data['fileid'],'move_type'=>$skey])->first();
                if($checktracker){
                    if($skey =="partially"){
                        $partiallyTracker = MovedFile::with('movedby')->where(['file_id'=>$data['fileid'],'move_type'=>$skey])->get();
                        foreach($partiallyTracker as $pkey=> $partialTracker){
                            $appendsummary.= '<tr>
                                        <td>'.$status.' ('.++$pkey.')</td>
                                        <td>'.date('d F Y, h:ia',strtotime($partialTracker->created_at)).' (Moved By:- '.$partialTracker['movedby']['name'].')</td>
                                    </tr>';
                        }
                    }else{
                        $appendsummary.= '<tr>
                                        <td>'.$status.'</td>
                                        <td>'.date('d F Y, h:ia',strtotime($checktracker->created_at)).' (Moved By:- '.$checktracker['movedby']['name'].')</td>
                                    </tr>';
                    }
                }else{
                    if($skey !="partially"){
                        $appendsummary.= '<tr>
                                            <td>'.$status.'</td>
                                            <td>Not Moved Yet</td>
                                        </tr>';
                    }
                }
            }
            return $appendsummary;
        }
    }

    public function exportDisFileHistory(Request $request,$fileid=null){
        $filedetails =  array();
        if($fileid==""){
            if($request->isMethod('post')){
                $data = $request->all();
                $getfileids = FileDisbursement::select('file_id')->whereMonth('final_disbursement_date',$data['month'])->whereYear('final_disbursement_date',$data['year'])->where('disb_type','disbursed')->get();
                $getfileids = array_flatten(json_decode(json_encode($getfileids),true));
                $files = File::whereIn('files.id',$getfileids);
            }
        }else{
            $files = File::where('files.id',$fileid);
        }
        $files = $files->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->select('files.*','clients.name as applicant_name','clients.company_name','file_disbursements.amount','file_disbursements.transaction_date','file_disbursements.pdd','file_disbursements.welcome_kits','file_disbursements.lod','file_disbursements.final_disbursement_date')->where('file_disbursements.disb_type','disbursed')->get();
        $files = json_decode(json_encode($files),true);
        foreach($files as $fkey=> $details){
            $fileid = $details['id'];
            $bankdetails = BankFile::where('file_id',$fileid)->with('bankdetail')->first();
            $bankdetails = json_decode(json_encode($bankdetails),true);
            $filedetails[$fkey]['File Number'] = $details['file_no'];
            $filedetails[$fkey]['Department'] = $details['department'];
            $filedetails[$fkey]['Company Name'] = $details['company_name'];
            $filedetails[$fkey]['Applicant Name'] = $details['applicant_name'];
            $filedetails[$fkey]['Facility Type'] = $details['facility_type'];
            $filedetails[$fkey]['Loan'] = (isset($details['amount']) ? $details['amount'] :'Not Found');
            $filedetails[$fkey]['Bank'] = (isset($bankdetails['bankdetail']['short_name']) ? $bankdetails['bankdetail']['short_name'] :'Not Found');

            $trackerarray = array('login' => 'Sent For Login/ Pendencies received dates','imf'=>'IMF meeting date/time','pd' => 'PD Date','credit queries'=>'Credit Queries','legal' =>'Legal Received / Not','approval'=>'Approval Date');
            foreach($trackerarray as $tstatus=> &$tracker){
                $trackerthread = BankFileTracker::where(['file_id'=>$fileid,'bank_id'=>$bankdetails['bank_id'],'type'=>$tstatus])->orderby('id','Desc')->first();
                if($trackerthread){
                    if(!empty($trackerthread->date)){
                        $filedetails[$fkey][$tracker] = $trackerthread->status. " (".date('d F Y',strtotime($trackerthread->date)).")";
                    }else{
                        $filedetails[$fkey][$tracker] = $trackerthread->status;
                    }
                }else{
                    $filedetails[$fkey][$tracker] = "";
                }
            }
            if($details['department'] =="Mortgage"){
                $propertyValuations = PropertyValuation::with('property')->where('file_id',$fileid)->get();
                $propertyValuations = json_decode(json_encode($propertyValuations),true);
                $val1 = "";
                $val2 = "";
                foreach($propertyValuations as $propertyVal){
                    $val1 .=  $propertyVal['property']['property_title'].": ".$propertyVal['value1'].", ";
                    $val2 .=  $propertyVal['property']['property_title'].": ".$propertyVal['value2'].", ";
                }
                $filedetails[$fkey]['VAL1'] = rtrim($val1,', ');
                $filedetails[$fkey]['VAL2'] = rtrim($val2,', ');
            }elseif($details['department'] =="Car Loan"){
                $assetValuations = AssetValuation::with('asset')->where('file_id',$fileid)->get();
                $assetValuations = json_decode(json_encode($assetValuations),true);
                $val1 = "";
                foreach($assetValuations as $propertyVal){
                    $val1 .=  $propertyVal['value1'].", ";
                }
                $filedetails[$fkey]['VAL1'] = rtrim($val1,', ');
                $filedetails[$fkey]['VAL2'] = "";
            }else{
                $filedetails[$fkey]['VAL1'] = "";
                $filedetails[$fkey]['VAL2'] = "";
            }
            $filedetails[$fkey]['Final Disb Date'] = ((isset($details['final_disbursement_date'])&& !empty($details['final_disbursement_date'])) ? date('d F Y',strtotime($details['final_disbursement_date'])) :'');
            $filedetails[$fkey]['Transaction date'] = ((isset($details['transaction_date']) && !empty($details['transaction_date'])) ? date('d F Y',strtotime($details['transaction_date'])) :'');
            $filedetails[$fkey]['PDD submission dates'] = ((isset($details['pdd']) && !empty($details['pdd'])) ? date('d F Y',strtotime($details['pdd'])) :'');
            $filedetails[$fkey]['Welcome Kits sent dates'] = ((isset($details['welcome_kits'])&& !empty($details['welcome_kits'])) ? date('d F Y',strtotime($details['welcome_kits'])) :'');
            $filedetails[$fkey]['LOD applied & received dates'] = ((isset($details['lod']) && !empty($details['lod'])) ? date('d F Y',strtotime($details['lod'])) :'');

            //Get File Tray Employees
            $getemployees = FileEmployee::with('emp')->where('file_id',$fileid)->get();
            $getemployees= json_decode(json_encode($getemployees),true);
            foreach($getemployees as $employee){
                $getType = DB::table('employee_types')->where('short_name',$employee['type'])->select('full_name')->first();
                if($getType){
                    $filedetails[$fkey][$getType->full_name] = $employee['emp']['name'];
                }
            }
            $filedetails[$fkey]['File Type'] = $details['file_type'];
            if($details['file_type'] =="indirect"){
                $channelpartner = DB::table('channel_partners')->where('id',$details['channel_partner_id'])->select('name')->first();
                if($channelpartner){
                    $filedetails[$fkey]['Channel Partner'] = $channelpartner->name;
                }
            }
        }
        return Excel::create("Sale Tracker-"."-".date("Y.m.d"), function($excel) use ($filedetails) {
            $excel->sheet('mySheet', function($sheet) use ($filedetails){
                /*$sheet->cell('A1:P1', function($cells) {
                    $cells->setBackground('#FFFF00');
                });*/
                $sheet->fromArray($filedetails);
                 // Freeze first row
                $sheet->freezeFirstRow();
                $sheet->cell('A1:P1', function($cell) {
                    // Set font
                    $cell->setFont(array(
                        'family'     => 'Calibri',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
            });
        })->download('xls');
    }
}
