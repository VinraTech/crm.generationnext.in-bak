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
use Excel;
use App\PartialFile;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ReportsController extends Controller
{
    public function exportfiles($type){
        $access = Employee::checkAccess();
        $files = File::with('getbank')->join('clients','clients.id','=','files.client_id')->select('files.id','files.client_id','files.file_no as File No','clients.customer_name as Applicant Name','clients.company_name as Company Name','files.department as Department','files.amount_requested as Loan Amount','files.facility_type as Facility Type','files.file_type','files.channel_partner_id',DB::raw('DATE_FORMAT(files.created_at, "%d-%b-%Y %h:%i%p") as Login_Date_Time'))->where('move_to',$type);
        if($type=="operations"){
            $files = $files->join('moved_files','moved_files.file_id','=','files.id')->where('moved_files.move_type','operations')->addSelect(DB::raw('DATE_FORMAT(moved_files.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Operations_Date_Time'));
        }
        if($type=="bank"){
           // $files = $files->join('moved_files as mop','mop.file_id','=','files.id')->where('mop.move_type','operations')->addSelect(DB::raw('DATE_FORMAT(mop.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Operations_Date_Time'));
            //$files = $files->join('moved_files','moved_files.file_id','=','files.id')->where('moved_files.move_type','bank')->addSelect(DB::raw('DATE_FORMAT(moved_files.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Bank_Date_Time'));
            $this->exportbankfiles('bank'); die();
		}
        if($type=="approved"){ 
            /*$files = $files->join('moved_files as mop','mop.file_id','=','files.id')->where('mop.move_type','operations')->addSelect(DB::raw('DATE_FORMAT(mop.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Operations_Date_Time'));
            $files = $files->join('moved_files as mob','mob.file_id','=','files.id')->where('mob.move_type','bank')->addSelect(DB::raw('DATE_FORMAT(mob.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Bank_Date_Time'));
            $files = $files->join('moved_files','moved_files.file_id','=','files.id')->where('moved_files.move_type','approved')->addSelect(DB::raw('DATE_FORMAT(moved_files.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Approved_Date_Time')); */
			
			$this->exportbankfiles('approved');  die();
        }
        if($type=="declined"){ 
            $files = $files->join('moved_files','moved_files.file_id','=','files.id')->where('moved_files.move_type','declined')->addSelect(DB::raw('DATE_FORMAT(moved_files.created_at, "%d-%b-%Y %h:%i%p") as Moved_To_Declined_Date_Time'));
        }
        if($access=="false"){
            $team = $this->getEmployees(Session::get('empSession')['id']);
            $fileids = FileEmployee::wherein('employee_id',$team)->groupby('file_id')->select('file_id')->get();
            $fileids = array_flatten(json_decode(json_encode($fileids),true));
            $files = $files->wherein('files.id',$fileids);
        }
        $files = $files->groupby('files.id')->orderBy('files.id','DESC')->get();
        $files = json_decode(json_encode($files),true);
        //echo "<pre>"; print_r($files); die;
        foreach($files as $fkey=> $file){
			$loandetails = FileLoanDetail::where('file_id',$file['id'])->first();
            $loandetails = json_decode(json_encode($loandetails),true);
			
			$client = Client::where('id',$file['client_id'])->first();
           
			$salesofficer = Employee::getemployee($client->created_emp);
			
			$files[$fkey]['Sales Officer'] = $salesofficer['name'];
			$files[$fkey]['Loan Amount'] = (isset($loandetails))?number_format($loandetails['loan_amt']):'';
            //Bank Details
            if(isset($file['getbank']) && !empty($file['getbank'])){
                $files[$fkey]['Bank'] = $file['getbank']['bankdetail']['short_name'];
            }
            //Get File Tray Employees
            $getemployees = FileEmployee::with('emp')->where('file_id',$file['id'])->get();
            $getemployees= json_decode(json_encode($getemployees),true);
            foreach($getemployees as $employee){
                $getType = DB::table('employee_types')->where('short_name',$employee['type'])->select('full_name')->first();
                if($getType){
                    $files[$fkey][$getType->full_name] = $employee['emp']['name'];
                }
            }
            if(!isset($files[$fkey]['Business Development Manager'])){
                $files[$fkey]['Business Development Manager'] ="";
            }
            if(!isset($files[$fkey]['Business Development Officer'])){
                $files[$fkey]['Business Development Officer'] ="";
            }
            $files[$fkey]['File Type'] = $file['file_type'];
            //$files[$fkey]['Channel Partner'] ="";
            if($file['file_type'] =="indirect"){
                $channelpartner = DB::table('channel_partners')->where('id',$file['channel_partner_id'])->select('name')->first();
                if($channelpartner){
                    //$files[$fkey]['Channel Partner'] = $channelpartner->name;
                }
            }
            unset($files[$fkey]['channel_partner_id']);
            unset($files[$fkey]['file_type']);
            unset($files[$fkey]['id']);
            unset($files[$fkey]['getbank']);
            unset($files[$fkey]['client_id']);
			
        }
		
		
        return Excel::create("File Tracker"."-".date("Y.m.d"), function($excel) use ($files) {
            $excel->sheet('mySheet', function($sheet) use ($files){
                $sheet->fromArray($files);
            });
        })->download('xls');
    }

   


     public function exportPartialDisFileHistory(Request $request,$fileid=null){
        $filedetails =  array();
        if($fileid==""){
            if($request->isMethod('post')){
                $data = $request->all();
                $getfileids = PartialFile::select('file_id')->whereMonth('partial_date',$data['month'])->whereYear('partial_date',$data['year'])->get();
                $getfileids = array_flatten(json_decode(json_encode($getfileids),true));
                $files = File::whereIn('files.id',$getfileids)->join('partial_files','partial_files.file_id','=','files.id')->whereMonth('partial_files.partial_date',$data['month'])->whereYear('partial_files.partial_date',$data['year']);
            }
        }else{
            $files = File::where('files.id',$fileid)->join('partial_files','partial_files.file_id','=','files.id');
        }
        $files = $files->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->select('files.*','clients.name as applicant_name','clients.company_name','file_disbursements.amount','file_disbursements.transaction_date','file_disbursements.pdd','file_disbursements.welcome_kits','file_disbursements.lod','file_disbursements.final_disbursement_date','partial_files.partial_amount','partial_files.partial_date')->where('file_disbursements.disb_type','partially')->get();
        $files = json_decode(json_encode($files),true);
        //echo "<pre>"; print_r($files); die;
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
            $filedetails[$fkey]['Partial Amount'] = (isset($details['partial_amount']) ? $details['partial_amount'] :'Not Found');
            $filedetails[$fkey]['Partial Date'] = ((isset($details['partial_date']) && !empty($details['partial_date'])) ? date('d F Y',strtotime($details['partial_date'])) :'');
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
                    //$filedetails[$fkey]['Channel Partner'] = $channelpartner->name;
                }
            }
        }
        return Excel::create("Partial Sale Tracker-"."-".date("Y.m.d"), function($excel) use ($filedetails) {
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

    public function appendMonths(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            $monthsArr = array();
            $year = explode('-',$data['year']);

            $startYear = $year[0];
            $endYear = $year[1];
            $optionsmonth = "<option value=''>Select</option>";
            $optionsmonth .="<option value='all'>All</option>";
            for($sm=4; $sm<=12; $sm++){
                $smkey = $sm."-".$startYear;
                $month = date('F',mktime(0, 0, 0, $sm));
                $optionsmonth .= "<option value=".$smkey.">".$month." (".$startYear.")"."</option>";
            }
            for($em=1; $em<=3; $em++){
                $emkey = $em."-".$endYear;
                $month = date('F',mktime(0, 0, 0, $em));
                $optionsmonth .= "<option value=".$emkey.">".$month." (".$endYear.")"."</option>";
            }
            return $optionsmonth;
        }
    }

    public function url_test( $url ) {
        $timeout = 10;
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
        $http_respond = curl_exec($ch);
        $http_respond = trim( strip_tags( $http_respond ) );
        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        //echo $http_code; die;
        if ( ( $http_code == "200" ) || ( $http_code == "302" ) ) {
            return true;
        } else {
            // return $http_code;, possible too
            return false;
      }
      curl_close( $ch );

      $website = "https://bantuphotos.com/";
        if( !$this->url_test( $website ) ) {
          echo $website ." is down!";
        }
        else { echo $website ." functions correctly."; }
        die;
    }
    
    public function checkServerOnline($server, $port = 21) {
        $check = @fsockopen($server, $port);
        if ($check) {
            @fclose($check);
            return true;
        } else {
            return false;
        }
    }

    public function checkForServer($server){
        $check = @fsockopen($server, 21);
        if ($check) {
            @fclose($check);
            return  "online";
            exit;
        }else{
            return  "offline";
        }
        /*$to = \Carbon\Carbon::createFromFormat('Y-m-d G:i:s', '2015-5-6 15:30:54');
        $from = \Carbon\Carbon::createFromFormat('Y-m-d G:i:s', '2015-5-6 15:35:55');
        $diff_in_minutes = $to->diffInMinutes($from);
        echo $diff_in_minutes; die;*/
    }

     public function exportreport(Request $request){
      
      $data = $request->all(); 
      $case_sta = explode(',', $request['casestat']);
      if($case_sta == 'all')
      {
        $case_sta = 'approved,declined,disbursement,login,bank,operations,partially';
      }

      if($data['type'] =="Individual"){
                            // $clien_id = array();
                            // $clien = DB::table('clients')->where('tel_name',$data['individual'])->get();
                            // $clien = json_decode(json_encode($clien),true);

                            // foreach($clien as $cli){
                            //     array_push($clien_id, $cli['id']);
                            // }

                            $query = $this->get_emp_clientdata($data['individual']);
							
                            $clien_id = [];
                            if(count($query) > 0)
                            {
                                foreach($query as $val)
                                {
                                    array_push($clien_id,$val['id']);
                                }
                            }

                            $from = $data['srt']." 00:00:00";
                            $to = $data['endt']." 23:59:59";

                            $file_id = array();
                            if($data['bank'] != 'all_banks')
                            {
                                $banks_id = explode(',', $data['bank']);
                                // dd($banks_id);
                                $bank_name = array();
                                $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                                $bank_details = json_decode(json_encode($bank_details),true);
                                foreach($bank_details as $banks){
                                    array_push($bank_name, $banks['short_name']);
                                }
                                $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                                $filloandets = json_decode(json_encode($filloandets),true);
                                foreach($filloandets as $loandet){
                                    array_push($file_id,$loandet['file_id']);
                                }
                            }

                        if($data['prod'] != ""){
                            $prod_name = explode(',',$data['prod']);

                            if(count($clien_id) > 0)
                            {
                                $filedata = File::whereIn('client_id',$clien_id)->whereIn('move_to',$case_sta)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                            } else {
                                $filedata = File::whereIn('client_id',$clien_id)->whereIn('move_to',$case_sta)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                            }
                        
                        }else{
                            if(count($clien_id) > 0)
                            {
                                $filedata = File::whereIn('client_id',$clien_id)->whereIn('move_to',$case_sta)->whereBetween('created_at', [$from, $to]);
                            } else {
                                $filedata = File::whereIn('client_id',$clien_id)->whereIn('move_to',$case_sta)->whereBetween('created_at', [$from, $to]);   
                            }
                            
                        }
                        if($data['bank'] != 'all_banks')
                        {
                          $filedata = $filedata->whereIn('id',$file_id)->get();
                        } else {
                          $filedata = $filedata->get();
                        }
                        $filedata = json_decode(json_encode($filedata),true);
                        
                         $id = array();
                     foreach($filedata as $file){
                        if($file['move_to'] == 'approved' || $file['move_to'] == 'partially' || $file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where(function($query){
                                $query->where('disb_type', 'disbursed')
                                ->orWhere('disb_type', 'partially_disbursed');
                            })
                            ->where('file_id', $file['id'])->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'partially')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','partially')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        }
                        if($file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','Fully Disbursed')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'declined' || $file['move_to'] == 'login' || $file['move_to'] == 'operations' || $file['move_to'] == 'bank' || $file['move_to'] == 'approved'){
                            array_push($id, $file['id']);
                        }
                     }

                     $files = File::with('getbank')->join('clients','clients.id','=','files.client_id')->select('files.id','clients.channel_partner','clients.tel_name','files.move_to','files.loan_type as Product Name','files.file_no as File No','files.created_at as Bank Login Date','files.updated_at as Latest Action Date','clients.customer_name as Client Name','clients.company_name as Client Company Name','files.loan_amount as Required Net Loan Amount','files.remarks as Remarks','clients.state as State','clients.city as Region','files.move_to as File Status','clients.mobile as Contact No','clients.pan as PAN.No')->whereIn('files.id',$id)->get();
                     $files = json_decode(json_encode($files),true);
                     //dd($files);
                     
                     foreach($files as $fkey=> &$file){
                        $app_amount = array();
                        $approved_amount = 0;
                        $getemployees = FileEmployee::with('emp')->where('file_id',$file['id'])->get();
                        $getemployees= json_decode(json_encode($getemployees),true);
                        $loandetails = FileLoanDetail::where('file_id',$file['id'])->first();
                        $loandetails = json_decode(json_encode($loandetails),true);
                        $disbdetails = DB::table('file_disbursements')->where('file_id',$file['id'])->get();
                        $disbdetails = json_decode(json_encode($disbdetails),true);
                        $bankdetails = DB::table('bank_details')->where('file_id',$file['id'])->get();
                        $bankdetails = json_decode(json_encode($bankdetails),true);
                        foreach($bankdetails as $bank){
                           array_push($app_amount, $bank['approved_amount']);
                        }
                        $sum = array_sum($app_amount);
                        $approved_amount += $sum;
                        foreach($disbdetails as $disb){
                            $disb_amount = $disb['amount'];
                        }

                        $loan_amount = (isset($loandetails))?$loandetails['loan_amt']:'';
                        $bank_name = (isset($loandetails))?$loandetails['bank_name']:'';
                        $banker_name = (isset($loandetails))?$loandetails['banker_name']:'';
                        $bank_id = DB::table('banks')->where('short_name',$bank_name)->first();
                        if(isset($bank_id) && $bank_id != '' && $banker_name == '')
                        {
                            $bkr_name = DB::table('bankers')->where('bank_id',$bank_id->id)->first();
                            $bkr_name = json_decode(json_encode($bkr_name),true);
                            $banker_name = $bkr_name['banker_name'];
                        }

                        foreach($getemployees as $employee){

                          $getType = DB::table('employee_types')->where('short_name',$employee['type'])->where('file_action','direct')->select('full_name')->first();

                       if($getType){

                          $emp_name = DB::table("employees")->where('id',$employee['employee_id'])->first();
                          $emp_name = json_decode(json_encode($emp_name),true);

                          $files[$fkey][$getType->full_name] = $emp_name['name'];
                          
                        }
                        }

                        if(!isset($files[$fkey]['Branch Head'])){
                          $files[$fkey]['Branch Head'] ="";
                        }
                        if(!isset($files[$fkey]['Business Manager'])){
                          $files[$fkey]['Business Manager'] ="";
                        }
                        if(!isset($files[$fkey]['Team Leader'])){
                          $files[$fkey]['Team Leader'] ="";
                        }
                        if($file['File Status'] == 'declined')
                        {
                            $file['File Status'] = 'Declined';   
                        }
                        if($file['File Status'] == 'login')
                        {
                            $file['File Status'] = 'WIP Files';   
                        }
                        if($file['File Status'] == 'operations')
                        {
                            $file['File Status'] = 'Pending Approval';   
                        }
                        if($file['File Status'] == 'bank')
                        {
                            $file['File Status'] = 'Login Bank Files';   
                        }
                        else if($file['File Status'] == 'approved' || $file['File Status'] == 'partially' || $file['File Status'] == 'disbursement')
                        {
                            $file_disbursement = FileDisbursement::where('file_id',$file['id'])->first(); 
                            $file_disbursement = json_decode(json_encode($file_disbursement),true);
                            if($file_disbursement['disb_type'] == 'disbursed' || $file_disbursement['disb_type'] == 'partially_disbursed')
                            {
                                //if($file_disbursement['chk'] == '1'){
									$file['File Status'] = 'Approved';
								//}else{
									//$file['File Status'] = 'Under Approval Process';
								//}  
                            } 
                            else if($file_disbursement['disb_type'] == 'partially')
                            {
                                $disb_amount = PartialFile::where('file_id',$file['id'])->value('partial_amount');
                                $file['File Status'] = 'Partially Disbursed'; 
                            }
                            else if($file_disbursement['disb_type'] == 'Fully Disbursed')
                            {
                                $file['File Status'] = 'Fully Disbursed'; 
                            }

                            if($file['File Status'] == 'approved')
                            {
                               // if($file_disbursement['chk'] == '1'){
									$file['File Status'] = 'Approved';
								//}else{
									//$file['File Status'] = 'Under Approval Process';
								//}  
                            }
                        }
						
						if($file['move_to'] == 'approved'){
								$file['File Status'] = 'Under Approval Process';
							}
                        if($file['channel_partner'] != ""){
                            $chp = DB::table('channel_partners')->where('id',$file['channel_partner'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Channel Partner";
                            $files[$fkey]['Channel Partner Name'] = $chp['name'];
                            $files[$fkey]['Channel Partner Company Name'] = $chp['company_name'];
                            $files[$fkey]['Telecaller'] = "";
                        }
                        if($file['tel_name'] != "" && $file['channel_partner'] == ""){
                            $chp = DB::table('employees')->where('id',$file['tel_name'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Telecaller";
                            $files[$fkey]['Telecaller'] = $chp['name'];
                            $files[$fkey]['Channel Partner Name'] = "";
                            $files[$fkey]['Channel Partner Company Name'] = "";
                        }
                        
                        if(empty($loandetails)){
                            $files[$fkey]['Bank Name'] = "";
                            $files[$fkey]['Banker Name'] = "";
                        }else{
                            $files[$fkey]['Bank Name'] = $bank_name;
                            $files[$fkey]['Banker Name'] = $banker_name;
                        }
                        if(empty($disbdetails)){
                            $files[$fkey]['Disburse Amount'] = "";
                        }else{
                            $files[$fkey]['Disburse Amount'] = $disb_amount;
                        }
                        if($file['File Status'] == 'operations' || $file['File Status'] == 'bank') {
                            $files[$fkey]['Approved Loan Amount'] = "";
                        }else{
                            $files[$fkey]['Approved Loan Amount'] = $approved_amount;
                        }
                        unset($files[$fkey]['id']);
                        unset($files[$fkey]['channel_partner']);
                        unset($files[$fkey]['Channel Partner']);
                        unset($files[$fkey]['getbank']);
                        unset($files[$fkey]['tel_name']);
                        unset($files[$fkey]['move_to']);
                    }

                    //$files = $files[2];
                    //dd($files);
                    
                     
                     return Excel::create("File Report"."-".date("Y.m.d"), function($excel) use ($files) {
                     $excel->sheet('mySheet', function($sheet) use ($files){
                     $sheet->fromArray($files);
                     });
                     })->download('xls');
                         
        
      }else if($data['type'] =="Team Wise"){
           //$cli_id = Employee::fileteamreport($data['team']);
            if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
                $client_arr = [];
            } else {
                $query = $this->getclientdata();
                $client_arr = [];
                if(count($query) > 0)
                {
                    foreach($query as $val)
                    {
                        array_push($client_arr,$val['id']);
                    }
                }
            }
           $cli_id = $client_arr;

                            $file_id = array();
                            if($data['bank'] != 'all_banks')
                            {
                                $banks_id = explode(',', $data['bank']);
                                // dd($banks_id);
                                $bank_name = array();
                                $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                                $bank_details = json_decode(json_encode($bank_details),true);
                                foreach($bank_details as $banks){
                                    array_push($bank_name, $banks['short_name']);
                                }
                                $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                                $filloandets = json_decode(json_encode($filloandets),true);
                                foreach($filloandets as $loandet){
                                    array_push($file_id,$loandet['file_id']);
                                }
                            }
                            $from = $data['srt']." 00:00:00";
                            $to = $data['endt']." 23:59:59";
                            if($data['prod'] != ""){
                                $prod_name = explode(',',$data['prod']);
                                if(count($cli_id) > 0)
                                {
                                    $filedata = File::whereIn('client_id',$cli_id)->whereIn('move_to',$case_sta)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                } else {
                                    $filedata = File::whereIn('move_to',$case_sta)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                }
                             
                            }else{
                                if(count($cli_id) > 0)
                                {
                                    $filedata = File::whereIn('client_id',$cli_id)->whereIn('move_to',$case_sta)->whereBetween('created_at', [$from, $to]);
                                } else {
                                    $filedata = File::whereIn('move_to',$case_sta)->whereBetween('created_at', [$from, $to]);
                                }
                            }
                            if($data['bank'] != 'all_banks')
                            {
                              $filedata = $filedata->whereIn('id',$file_id)->get();
                            } else {
                              $filedata = $filedata->get();
                            }
                            $filedata = json_decode(json_encode($filedata),true);

                          $id = array();
                          //dd($filedata);
                     foreach($filedata as $file){
                        if($file['move_to'] == 'approved' || $file['move_to'] == 'partially' || $file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where(function($query){
                                $query->where('disb_type', 'disbursed')
                                ->orWhere('disb_type', 'partially_disbursed');
                            })
                            ->where('file_id', $file['id'])->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'partially')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','partially')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        }
                        if($file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','Fully Disbursed')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'declined' || $file['move_to'] == 'login' || $file['move_to'] == 'operations' || $file['move_to'] == 'bank' || $file['move_to'] == 'approved'){
                            array_push($id, $file['id']);
                        }
                     }
                     //dd($id);
                     
                     $files = File::with('getbank')->join('clients','clients.id','=','files.client_id')->select('files.id','clients.channel_partner','clients.tel_name','files.loan_type as Product Name','files.file_no as File No','files.created_at as Bank Login Date','files.move_to','files.updated_at as Latest Action Date','clients.customer_name as Client Name','clients.company_name as Client Company Name','files.loan_amount as Required Net Loan Amount','files.remarks as Remarks','clients.state as State','clients.city as Region','files.move_to as File Status','clients.mobile as Contact No','clients.pan as PAN.No')->whereIn('files.id',$id)->get();
                     $files = json_decode(json_encode($files),true);
                     
                     //dd($files);
					 
                     foreach($files as $fkey=> &$file){
                        $app_amount = array();
                        $approved_amount = 0;
                        $getemployees = FileEmployee::with('emp')->where('file_id',$file['id'])->get();
                        $getemployees= json_decode(json_encode($getemployees),true);
                        $loandetails = FileLoanDetail::where('file_id',$file['id'])->first();
                        $loandetails = json_decode(json_encode($loandetails),true);
                        $disbdetails = DB::table('file_disbursements')->where('file_id',$file['id'])->get();
                        $disbdetails = json_decode(json_encode($disbdetails),true);
                        $bankdetails = DB::table('bank_details')->where('file_id',$file['id'])->get();
                        $bankdetails = json_decode(json_encode($bankdetails),true);
                        foreach($bankdetails as $bank){
                           array_push($app_amount, $bank['approved_amount']);
                        }
                        $sum = array_sum($app_amount);
                        $approved_amount += $sum;
                        foreach($disbdetails as $disb){
                            $disb_amount = $disb['amount'];
                        }
                        $loan_amount = (isset($loandetails))?$loandetails['loan_amt']:'';
                        $bank_name = (isset($loandetails))?$loandetails['bank_name']:'';
                        $banker_name = (isset($loandetails))?$loandetails['banker_name']:'';
                        $bank_id = DB::table('banks')->where('short_name',$bank_name)->first();
                        if(isset($bank_id) && $bank_id != '' && $banker_name == '')
                        {
                            $bkr_name = DB::table('bankers')->where('bank_id',$bank_id->id)->first();
                            $bkr_name = json_decode(json_encode($bkr_name),true);
                            $banker_name = $bkr_name['banker_name'];
                        }

                       
                        foreach($getemployees as $employee){

                          $getType = DB::table('employee_types')->where('short_name',$employee['type'])->where('file_action','direct')->select('full_name')->first();

                       if($getType){

                          $emp_name = DB::table("employees")->where('id',$employee['employee_id'])->first();
                          $emp_name = json_decode(json_encode($emp_name),true);

                          $files[$fkey][$getType->full_name] = $emp_name['name'];
                          
                        }
                        }
                        if(!isset($files[$fkey]['Branch Head'])){
                          $files[$fkey]['Branch Head'] ="";
                        }
                        if(!isset($files[$fkey]['Business Manager'])){
                          $files[$fkey]['Business Manager'] ="";
                        }
                        if(!isset($files[$fkey]['Team Leader'])){
                          $files[$fkey]['Team Leader'] ="";
                        }
                        if($file['File Status'] == 'declined')
                        {
                            $file['File Status'] = 'Declined';   
                        }
                        if($file['File Status'] == 'login')
                        {
                            $file['File Status'] = 'WIP Files';   
                        }
                        if($file['File Status'] == 'operations')
                        {
                            $file['File Status'] = 'Pending Approval';   
                        }
                        if($file['File Status'] == 'bank')
                        {
                            $file['File Status'] = 'Login Bank Files';   
                        }
                        else if($file['File Status'] == 'approved' || $file['File Status'] == 'partially' || $file['File Status'] == 'disbursement')
                        {
                            $file_disbursement = FileDisbursement::where('file_id',$file['id'])->first(); 
                            $file_disbursement = json_decode(json_encode($file_disbursement),true);
                           
							if($file_disbursement['disb_type'] == 'disbursed' || $file_disbursement['disb_type'] == 'partially_disbursed')
                            {
                               // if($file_disbursement['chk'] == '1'){ 
									$file['File Status'] = 'Approved';
								//}else{ 
								//	$file['File Status'] = 'Under Approval Process';
								//}  
                            } 
                            else if($file_disbursement['disb_type'] == 'partially')
                            {
                                $disb_amount = PartialFile::where('file_id',$file['id'])->value('partial_amount');
                                $file['File Status'] = 'Partially Disbursed'; 
                            }
                            else if($file_disbursement['disb_type'] == 'Fully Disbursed')
                            {
                                $file['File Status'] = 'Fully Disbursed'; 
                            }

                            if($file['File Status'] == 'approved')
                            { 
                               // if($file_disbursement['chk'] == '1'){
									$file['File Status'] = 'Approved';
								//}else{
									//$file['File Status'] = 'Under Approval Process';
								//}  
                            }
                        }
							
							if($file['move_to'] == 'approved'){
								$file['File Status'] = 'Under Approval Process';
							}
						    
						
                        if($file['channel_partner'] != ""){
                            $chp = DB::table('channel_partners')->where('id',$file['channel_partner'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Channel Partner";
                            $files[$fkey]['Channel Partner Name'] = $chp['name'];
                            $files[$fkey]['Channel Partner Company Name'] = $chp['company_name'];
                            $files[$fkey]['Telecaller'] = "";
                        }
                        if($file['tel_name'] != "" && $file['channel_partner'] == ""){
                            $chp = DB::table('employees')->where('id',$file['tel_name'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Telecaller";
                            $files[$fkey]['Telecaller'] = $chp['name'];
                            $files[$fkey]['Channel Partner Name'] = "";
                            $files[$fkey]['Channel Partner Company Name'] = "";
                        }

                        if(empty($loandetails)){
                            $files[$fkey]['Bank Name'] = "";
                            $files[$fkey]['Banker Name'] = "";
                        }else{
                            $files[$fkey]['Bank Name'] = $bank_name;
                            $files[$fkey]['Banker Name'] = $banker_name;
                        }
                        if(empty($disbdetails)){
                            $files[$fkey]['Disburse Amount'] = "";
                        }else{
                            $files[$fkey]['Disburse Amount'] = $disb_amount;
                        }
                        if($file['File Status'] == 'operations' || $file['File Status'] == 'bank') {
                            $files[$fkey]['Approved Loan Amount'] = "";
                        }else{
                            $files[$fkey]['Approved Loan Amount'] = $approved_amount;
                        }
                        unset($files[$fkey]['id']);
                        unset($files[$fkey]['channel_partner']);
                        unset($files[$fkey]['Channel Partner']);
                        unset($files[$fkey]['getbank']);
                        unset($files[$fkey]['tel_name']);
                        unset($files[$fkey]['move_to']);
						
                    }
                     
                   
                     return Excel::create("File Report"."-".date("Y.m.d"), function($excel) use ($files) {
                     $excel->sheet('mySheet', function($sheet) use ($files){
                     $sheet->fromArray($files);
                     });
                     })->download('xls');
        
      }else if($data['type'] =="All Branches"){
                            
                            $file_id = array();
                            if($data['bank'] != 'all_banks')
                            {
                                $banks_id = explode(',', $data['bank']);
                                // dd($banks_id);
                                $bank_name = array();
                                $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                                $bank_details = json_decode(json_encode($bank_details),true);
                                foreach($bank_details as $banks){
                                    array_push($bank_name, $banks['short_name']);
                                }
                                $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                                $filloandets = json_decode(json_encode($filloandets),true);
                                foreach($filloandets as $loandet){
                                    array_push($file_id,$loandet['file_id']);
                                }
                            }

                            $from = $data['srt']." 00:00:00";
                            $to = $data['endt']." 23:59:59";

                            if($data['prod'] != ""){
                                $prod_name = explode(',',$data['prod']);
                                $filedata = File::whereIn('move_to',$case_sta)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                            } else{
                                $filedata = File::whereIn('move_to',$case_sta)->whereBetween('created_at', [$from, $to]);
                            }

                            if($data['bank'] != 'all_banks')
                            {
                              $filedata = $filedata->whereIn('id',$file_id)->get();
                            } else {
                              $filedata = $filedata->get();
                            }
                            $filedata = json_decode(json_encode($filedata),true);

                      $id = array();
                     foreach($filedata as $file){
                        if($file['move_to'] == 'approved' || $file['move_to'] == 'partially' || $file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where(function($query){
                                $query->where('disb_type', 'disbursed')
                                ->orWhere('disb_type', 'partially_disbursed');
                            })
                            ->where('file_id', $file['id'])->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'partially')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','partially')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        }
                        if($file['move_to'] == 'disbursement')
                        {
                            $filedata = FileDisbursement::where('file_id',$file['id'])->where('disb_type','Fully Disbursed')->count();
                            //echo $filedet['id']."-".$filedata."<br/>";
                            if($filedata > 0)
                            {
                                array_push($id, $file['id']);
                            }
                        } 
                        if($file['move_to'] == 'declined' || $file['move_to'] == 'login' || $file['move_to'] == 'operations' || $file['move_to'] == 'bank' || $file['move_to'] == 'approved'){
                            array_push($id, $file['id']);
                        }
                     }
                    $files = File::with('getbank')->join('clients','clients.id','=','files.client_id')->select('files.id','clients.channel_partner','clients.tel_name','files.loan_type as Product Name','files.move_to','files.file_no as File No','files.created_at as Bank Login Date','files.updated_at as Latest Action Date','clients.customer_name as Client Name','clients.company_name as Client Company Name','files.loan_amount as Required Net Loan Amount','files.remarks as Remarks','clients.state as State','clients.city as Region','files.move_to as File Status','clients.mobile as Contact No','clients.pan as PAN.No')->whereIn('files.id',$id)->get();
                     $files = json_decode(json_encode($files),true);

                     //dd($files);
                     foreach($files as $fkey=> &$file){
                        $app_amount = array();
                        $approved_amount = 0;
                        $getemployees = FileEmployee::with('emp')->where('file_id',$file['id'])->get();
                        $getemployees= json_decode(json_encode($getemployees),true);
                        $loandetails = FileLoanDetail::where('file_id',$file['id'])->first();
                        $loandetails = json_decode(json_encode($loandetails),true);
                        $disbdetails = DB::table('file_disbursements')->where('file_id',$file['id'])->get();
                        $disbdetails = json_decode(json_encode($disbdetails),true);
                        $bankdetails = DB::table('bank_details')->where('file_id',$file['id'])->get();
                        $bankdetails = json_decode(json_encode($bankdetails),true);
                        foreach($bankdetails as $bank){
                           array_push($app_amount, $bank['approved_amount']);
                        }
                        $sum = array_sum($app_amount);
                        $approved_amount += $sum;
                        foreach($disbdetails as $disb){
                            $disb_amount = $disb['amount'];
                        }

                        $loan_amount = (isset($loandetails))?$loandetails['loan_amt']:'';
                        $bank_name = (isset($loandetails))?$loandetails['bank_name']:'';
                        $banker_name = (isset($loandetails))?$loandetails['banker_name']:'';
                        $bank_id = DB::table('banks')->where('short_name',$bank_name)->first();
                        if(isset($bank_id) && $bank_id != '' && $banker_name == '')
                        {
                            $bkr_name = DB::table('bankers')->where('bank_id',$bank_id->id)->first();
                            $bkr_name = json_decode(json_encode($bkr_name),true);
                            $banker_name = $bkr_name['banker_name'];
                        }

                        foreach($getemployees as $employee){

                          $getType = DB::table('employee_types')->where('short_name',$employee['type'])->where('file_action','direct')->select('full_name')->first();

                       if($getType){

                          $emp_name = DB::table("employees")->where('id',$employee['employee_id'])->first();
                          $emp_name = json_decode(json_encode($emp_name),true);

                          $files[$fkey][$getType->full_name] = $emp_name['name'];
                          
                        }
                        }
                        if(!isset($files[$fkey]['Branch Head'])){
                          $files[$fkey]['Branch Head'] ="";
                        }
                        if(!isset($files[$fkey]['Business Manager'])){
                          $files[$fkey]['Business Manager'] ="";
                        }
                        if(!isset($files[$fkey]['Team Leader'])){
                          $files[$fkey]['Team Leader'] ="";
                        }
                        if($file['File Status'] == 'declined')
                        {
                            $file['File Status'] = 'Declined';   
                        }
                        if($file['File Status'] == 'login')
                        {
                            $file['File Status'] = 'WIP Files';   
                        }
                        if($file['File Status'] == 'operations')
                        {
                            $file['File Status'] = 'Pending Approval';   
                        }
                        if($file['File Status'] == 'bank')
                        {
                            $file['File Status'] = 'Login Bank Files';   
                        }
                        else if($file['File Status'] == 'approved' || $file['File Status'] == 'partially' || $file['File Status'] == 'disbursement')
                        {
                            $file_disbursement = FileDisbursement::where('file_id',$file['id'])->first(); 
                            $file_disbursement = json_decode(json_encode($file_disbursement),true);
                            if($file_disbursement['disb_type'] == 'disbursed' || $file_disbursement['disb_type'] == 'partially_disbursed')
                            {
                                //if($file_disbursement['chk'] == '1'){
									$file['File Status'] = 'Approved';
								//}else{
									//$file['File Status'] = 'Under Approval Process';
								//}  
                            } 
                            else if($file_disbursement['disb_type'] == 'partially')
                            {
                                $disb_amount = PartialFile::where('file_id',$file['id'])->value('partial_amount');
                                $file['File Status'] = 'Partially Disbursed'; 
                            }
                            else if($file_disbursement['disb_type'] == 'Fully Disbursed')
                            {
                                $file['File Status'] = 'Fully Disbursed'; 
                            }

                            if($file['File Status'] == 'approved')
                            {
                               // if($file_disbursement['chk'] == '1'){
									$file['File Status'] = 'Approved';
								//}else{
									//$file['File Status'] = 'Under Approval Process';
								//}  
                            }
                        }
						
						if($file['move_to'] == 'approved'){
								$file['File Status'] = 'Under Approval Process';
						}
							
                        if($file['channel_partner'] != ""){
                            $chp = DB::table('channel_partners')->where('id',$file['channel_partner'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Channel Partner";
                            $files[$fkey]['Channel Partner Name'] = $chp['name'];
                            $files[$fkey]['Channel Partner Company Name'] = $chp['company_name'];
                            $files[$fkey]['Telecaller'] = "";
                        }
                        if($file['tel_name'] != "" && $file['channel_partner'] == ""){
                            $chp = DB::table('employees')->where('id',$file['tel_name'])->first();
                            $chp = json_decode(json_encode($chp),true);
                            $files[$fkey]['Lead Origin'] = "Telecaller";
                            $files[$fkey]['Telecaller'] = $chp['name'];
                            $files[$fkey]['Channel Partner Name'] = "";
                            $files[$fkey]['Channel Partner Company Name'] = "";
                        }
                        
                        if(empty($loandetails)){
                            $files[$fkey]['Bank Name'] = "";
                            $files[$fkey]['Banker Name'] = "";
                        }else{
                            $files[$fkey]['Bank Name'] = $bank_name;
                            $files[$fkey]['Banker Name'] = $banker_name;
                        }
                        if(empty($disbdetails)){
                            $files[$fkey]['Disburse Amount'] = "";
                        }else{
                            $files[$fkey]['Disburse Amount'] = $disb_amount;
                        }
                        if($file['File Status'] == 'operations' || $file['File Status'] == 'bank') {
                            $files[$fkey]['Approved Loan Amount'] = "";
                        }else{
                            //$files[$fkey]['Approved Loan Amount'] = (isset($disb_amount) && $disb_amount!= '0')?$disb_amount:$loan_amount;
                            $files[$fkey]['Approved Loan Amount'] = $approved_amount;
                        }
                        unset($files[$fkey]['id']);
                        unset($files[$fkey]['channel_partner']);
                        unset($files[$fkey]['Channel Partner']);
                        unset($files[$fkey]['getbank']);
                        unset($files[$fkey]['tel_name']);
                        unset($files[$fkey]['move_to']);
                    }
                     
                     // echo "<pre>";
                     // print_r($files);
                     // exit;
                     return Excel::create("File Report"."-".date("Y.m.d"), function($excel) use ($files) {
                        $excel->sheet('mySheet', function($sheet) use ($files){
                            $sheet->fromArray($files);
                        });
                     })->download('xls');
            }
      
      }
   

    public function fileReports(request $request){
        Session::put('active',25);
        if(Session::get('empSession')['type']=="admin" || Session::get('empSession')['is_access']=="full"){
            $getTeamLevels = $this->geteamLevels();
        }else{
            $getTeamLevels = $this->getteam(Session::get('empSession')['id']);
        }
        $banks = Bank::banks();
        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data);
            if($data['bank'] !='all_banks')
            {
                $bank_det = implode(',', $data['bank']);
            } else {
                $bank_det = 'all_banks';
            }
            if($data['type'] =="Individual" && $data['format_type'] == "Tabular"){
                
                if(!isset($data['product_type'])){
                    $prod = "";
                }else{
                    $prod = implode(',', $data['product_type']);
                }
                    $case_status='all';
                    // print_r($data);
                    // exit;
                    if(isset($data['status']) && count($data['status']) > 0)
                    {
                        $case_status = implode(',', $data['status']);
                    }
                
                if($data['start_date'] == ""){
                    $strt = "";
                }else{
                    $strt = $data['start_date'];
                }
                if($data['end_date'] == ""){
                    $end = "";
                }else{
                    $end  = $data['end_date'];
                }
                
                return redirect::to('s/admin/file-report-results?type=Individual&bank='.$bank_det.'&individual='.$data['individual'].'&prod='.$prod.'&srt='.$strt.'&endt='.$end.'&casestat='.$case_status);
            }else if($data['type'] =="Team Wise" && $data['format_type'] == "Tabular"){
                if(!isset($data['product_type'])){
                    $prod = "";
                }else{
                    $prod = implode(',', $data['product_type']);
                }
                
                    $case_status='all';
                    if(isset($data['status']) && count($data['status']) > 0)
                    {
                        $case_status = implode(',', $data['status']);
                    }
                
                if($data['start_date'] == ""){
                    $strt = "";
                }else{
                    $strt = $data['start_date'];
                }
                if($data['end_date'] == ""){
                    $end = "";
                }else{
                    $end  = $data['end_date'];
                }
                return redirect::to('s/admin/file-report-results?type=Team Wise&bank='.$bank_det.'&team='.$data['team'].'&prod='.$prod.'&srt='.$strt.'&endt='.$end.'&casestat='.$case_status);
            }else if($data['type'] =="All Branches" && $data['format_type'] == "Tabular"){
                if(!isset($data['product_type'])){
                    $prod = "";
                }else{
                    $prod = implode(',', $data['product_type']);
                }
                
                    $case_status='all';
                    // print_r($data);
                    // exit;
                    if(isset($data['status']) && count($data['status']) > 0)
                    {
                        $case_status = implode(',', $data['status']);
                    }
                
                if($data['start_date'] == ""){
                    $strt = "";
                }else{
                    $strt = $data['start_date'];
                }
                if($data['end_date'] == ""){
                    $end = "";
                }else{
                    $end  = $data['end_date'];
                }
                return redirect::to('s/admin/file-report-results?type=All Branches&bank='.$bank_det.'&prod='.$prod.'&srt='.$strt.'&endt='.$end.'&casestat='.$case_status);
            }
            echo "<pre>"; print_r($data); die;
        }
        $title="File Reports - Express Paisa";
        return view('admin.reports.file-reports')->with(compact('title','getTeamLevels','banks'));
    }

    public function filereportResults(request $request){
		$data = $request->all();
        if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['type']=="HR-M" || Session::get('empSession')['type']=="hro" || Session::get('empSession')['is_access']=="full"){
            $client_arr = [];
			if($data['type'] == 'Individual'){ 
				   $getclientdata = $this->get_emp_clientdata($data['individual']);
				   $getclientdata = json_decode(json_encode($getclientdata),true);
				   $client_arr = array_column($getclientdata, 'id');
					
			} 
        } else { 
            $query = $this->getclientdata();
            $client_arr = [];
            if(count($query) > 0)
            {
                foreach($query as $val)
                {
                    array_push($client_arr,$val['id']);
                }
            }
			
        } 
        $title = "Report Results";
        return view('admin.reports.report-results')->with(compact('title','client_arr'));
    }
	
	public function exportbankfiles($type){ 
		  Session::put('report_type',$type);
		  
		  
		  /*
		  
		  fputcsv($handle, ["Client Code","Client Name","Company Name","Applicant Name","DOB","Co-Applicant Name","Co-Applicant DOB","Mobile","PAN","Adhar No","Email","Sales Officer"]);
            
			$type = 'bank';
			$querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id');
                
            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                        $query->where('employee_id',Session::get('empSession')['id']);
                });
            }
			$rows = $querys->OrderBy('files.id','DESC')->get();
			$rows = json_decode(json_encode($rows),true);
			foreach($rows as $file){ 
			    $loandata = DB::table('file_loan_details')->where('file_id',$file['id'])->first();
                $loandata=json_decode( json_encode($loandata), true);
				$client = Client::where('id',$file['client_id'])->first();
                $salesofficer = Employee::getemployee($client->created_emp);
				if(isset($_GET['type']) && $_GET['type'] =="approved"){
                    $amount = (!empty($file['approved_amount']) ? '<b> Rs '. FileLoanDetail::format($file['approved_amount']).'</b>' : 'Not Available');
                }else{
                    $amount = (!empty($file['loan_amount']) ? '<b> Rs '. FileLoanDetail::format($file['loan_amount']).'</b>' : 'Not Available');
                }
				if(!empty($loandata['bank_name'])){
                    $bank = $loandata['bank_name'];
                }else{
                     $bank = "Not moved yet";
                }
				
					echo 'file_link    -  '.$file['file_no'].'<br>';
					echo 'company_name    -  '.$file['company_name'].'<br>';
					echo 'loan_ins    -  '.$file['loan_ins'].'<br>';
					echo 'amount    -  '.$amount.'<br>';
					echo 'bank    -  '.$bank.'<br>';
					echo 'salesofficer    -  '.$salesofficer['name'].'<br>';
					
				
		    }
		  
		  exit;
		  */
		  
		  
		  
			$conditions = array();
            $data = array();
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
            fputcsv($handle, ["File No","Applicant Name","Company Name","Loan Type","Loan Amount","Bank","Sales Officer"]);
            
			$type = Session::get('report_type');
			$querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id');
                
            $access = Employee::checkAccess();
            if($access =="false"){
                $querys = $querys->has('empfiles', '>=',1)->whereHas('empfiles', function ($query){
                        $query->where('employee_id',Session::get('empSession')['id']);
                });
            }
			$querys = $querys->OrderBy('files.id','DESC');
			
			
			
			
			
            $querys = $querys->chunk(500, function($rows) use($handle) {
                foreach($rows as $file){
				$type = Session::get('report_type');					
                $loandata = DB::table('file_loan_details')->where('file_id',$file['id'])->first();
                $loandata=json_decode( json_encode($loandata), true);
				$client = Client::where('id',$file['client_id'])->first();
                $salesofficer = Employee::getemployee($client->created_emp);
				if(isset($type) && $type =="approved"){
                    $amount = (!empty($file['approved_amount']) ? ' Rs '. FileLoanDetail::format($file['approved_amount']).'' : 'Not Available');
                }else{
                    $amount = (!empty($file['loan_amount']) ? ' Rs '. FileLoanDetail::format($file['loan_amount']).'' : 'Not Available');
                }
				if(!empty($loandata['bank_name'])){
                    $bank = $loandata['bank_name'];
                }else{
                     $bank = "Not moved yet";
                }
                    fputcsv($handle, [
                       $file['file_no'],
					   $file['client_name'],
                       $file['company_name'],
                       $file['loan_ins'],
                       $amount,
                       $bank,
                       $salesofficer['name'], 
                    ]);
                }
            });
            fclose($handle);
        },200, $headers);
        
		
		return $response->send();
		
		
		
		
	}
	
	
	public function superadmin(){ 
	    if(Session::get('empSession')['type'] == 'admin' && Session::get('empSession')['parent_id'] == 'ADMIN' ){
			$getTeamLevels = $this->getEngTeamLevels(Session::get('empSession')['id']);
			return View::make('admin.superadmin.report')->with(compact('getTeamLevels'));
	    }else{ 
			return redirect::to('/s/admin/2018');
		}
	} 
	
}
