<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\File;
use DB;
use App\FileLoanDetail;
use App\Employee;
use Session;
class File extends Model
{
    //

    public static function getfiles(){
    	$getfiles = File::get();
    	$getfiles = json_decode(json_encode($getfiles),true);
    	return $getfiles;
    }

    public static function getfiledetail($fileid){
    	$filedetails = File::where('id',$fileid)->first();
    	$filedetails = json_decode(json_encode($filedetails),true);
    	return $filedetails;
    }

    public function checklists(){
        return $this->belongsToMany('App\FileChecklist','file_checklists','file_id','checklist_id');
    }

    public function filestatus(){
        return $this->hasMany('App\FileStatusDetail');
    }

    public function eligibilityfiles(){
        return $this->hasMany('App\EligibilityFile');
    }

    public function filebanks(){
        return $this->hasMany('App\BankFile')->with('bankdetail');
    }

    public function banks(){
        return $this->belongsToMany('App\BankFile','bank_files','file_id','bank_id');
    }

    public function empfiles(){
        return $this->hasMany('App\FileEmployee','file_id');
    }

    public static function checkfoSel($fileid,$type,$empid){
        $sel ="";
        $details = DB::table('file_employees')->where(['employee_id'=>$empid,'type'=>$type,'file_id'=> $fileid])->count();
        if($details >=1){
            $sel ="selected";
        }
        return $sel;
    }

    public function getbank(){
        return $this->hasOne('App\BankFile','file_id')->with('bankdetail');
    }

    public static function getfilemp($fileid,$type){
        $empdetails = FileEmployee::with('emp')->where('file_id',$fileid)->where('type',$type)->first();
        $empdetails = json_decode(json_encode($empdetails),true);
        return $empdetails;
    }

    public function filebank(){
        return $this->hasOne('App\BankFile');
    }

    public static function getLoanAmt($filedetails){
        if($filedetails['move_to'] == "approved"){
            $getapprovaldetails = BankFileTracker::where('file_id',$filedetails['id'])->where('type','approval')->select('amount')->first();
            if($getapprovaldetails){
                $amount = '<b> Rs '.FileLoanDetail::format($getapprovaldetails->amount).'</b>';
            }else{
                $amount = 'Not Available';
            }
        }elseif($filedetails['move_to'] == "partially" || $filedetails['move_to'] == "disbursement"){
            $getdetails = FileDisbursement::where('file_id',$filedetails['id'])->select('amount')->first();
            if($getdetails){
                $amount = '<b> Rs '.FileLoanDetail::format($getdetails->amount).'</b>';
            }else{
                $amount = 'Not Available';
            }
        }else{
            $amount = "<b> Rs. " .FileLoanDetail::format($filedetails['amount_requested'])."</b>";
        }
        return $amount;
    }

    public static function getfileReports($ftskey,$dept,$ftype,$requestdata){
        $response = array('total_files'=>0,'total_amount'=>'0');
        if($requestdata['ym'] !="all"){
            $explodeym = explode('-',$requestdata['ym']);
            $year = $explodeym[1];
            $month = $explodeym[0];
            $query_date = $year.'-'.$month.'-01';
            $startdate = date('Y-m-01', strtotime($query_date));
            $enddate = date('Y-m-t', strtotime($query_date));
        }else{
            $explodeyears = explode('-',$requestdata['y']);
            $start = 'April 1, '.$explodeyears[0];
            $startdate = date('Y-m-d', strtotime($start));
            $end = "March 31, ".$explodeyears[1];
            $enddate = date('Y-m-d', strtotime($end));
        }
        $details = File::query();
        //$details = $details->where(['move_to'=>$ftskey,'department'=>$dept]);
        $details = $details->where(['department'=>$dept]);
        if($ftskey =="login" || $ftskey=="operations"|| $ftskey=="bank"|| $ftskey=="declined"){
            $details = $details->has('movedfiles','>',0)->wherehas('movedfiles',function ($query) use($ftskey,$startdate,$enddate) {
                            $query->where('move_type',$ftskey)
                                ->whereDate('created_at', '>=', $startdate)
                                ->whereDate('created_at', '<=', $enddate);
                    })->with(['movedfiles'=>function ($query) use($ftskey,$startdate,$enddate) {
                            $query->where('move_type',$ftskey)
                                ->whereDate('created_at', '>=', $startdate)
                                ->whereDate('created_at', '<=', $enddate);
                    }]);
        }elseif($ftskey =="approved"){
            $details = $details->whereDate('files.approved_date', '>=', $startdate)
                        ->whereDate('files.approved_date', '<=', $enddate);
        }elseif($ftskey=="partially"){
            $details = $details->withCount(['partialfiles as partialamount' => function($query) use($startdate,$enddate){
                    $query->select(DB::raw('sum(partial_amount)'))
                            ->whereDate('partial_date', '>=', $startdate)
                            ->whereDate('partial_date', '<=', $enddate);
                }])->has('partialfiles','>',0)->whereHas('partialfiles',function($query) use($startdate,$enddate){
                    $query->whereDate('partial_date', '>=', $startdate)
                        ->whereDate('partial_date', '<=', $enddate);
                })->with(['partialfiles'=>function($query) use($startdate,$enddate){
                    $query->whereDate('partial_date', '>=', $startdate)
                            ->whereDate('partial_date', '<=', $enddate);
                }]);
        }elseif($ftskey =="disbursement"){
            $details = $details->where('move_to','disbursement')->whereDate('files.disbursement_date', '>=', $startdate)
                        ->whereDate('files.disbursement_date', '<=', $enddate);
        }
        if($requestdata['type'] =="Individual"){
            if($ftype=="indirect"){
                $details = $details->whereColumn('files.source','!=','files.sales_officer');
                $details = $details->where('files.sales_officer',$requestdata['individual']);
            }else{
                /*$details = $details->whereColumn('files.source','files.sales_officer');*/
                $details = $details->where('files.source',$requestdata['individual']);
            }
        }elseif($requestdata['type'] == 'Team Wise'){
            $empid = $requestdata['team'];
            $empdetails = Employee::select('id','type')->where('id',$empid)->first();
            $details = $details->where('files.file_type',$ftype)->has('teamfiles','>',0)->wherehas('teamfiles',function($query) use($empdetails,$ftype){
                $query->where('file_employees.type',$empdetails->type)->where('file_employees.employee_id',$empdetails->id);
            })->with(['teamfiles'=>function($query) use($empdetails,$ftype){
                $query->where('file_employees.type',$empdetails->type)->where('file_employees.employee_id',$empdetails->id);
            }]);
        }elseif($requestdata['type'] == 'Bank Wise'){
            $bankid = $requestdata['bank'];
            $details = $details->where('files.file_type',$ftype)->has('filebank','>',0)->wherehas('filebank',function($query) use ($bankid){
                    $query->where('bank_id',$bankid);
            });
            $access = Employee::checkAccess();
            if($access =="false"){
                $empid = Session::get('empSession')['id'];
                $empdetails = Employee::select('id','type')->where('id',$empid)->first();
                $details = $details->has('teamfiles','>',0)->wherehas('teamfiles',function($query) use($empdetails,$ftype){
                    $query->where('file_employees.type',$empdetails->type)->where('file_employees.employee_id',$empdetails->id);
                })->with(['teamfiles'=>function($query) use($empdetails,$ftype){
                    $query->where('file_employees.type',$empdetails->type)->where('file_employees.employee_id',$empdetails->id);
                }]);
            }
        }
        $debug="false";
        if($debug=="true"){
            $details = $details->get();
            $response = json_decode(json_encode($details),true);
            echo "<pre>"; print_r($response); die;
        }else{
            if($ftskey=="approved"){
                $details = $details->select(DB::raw('count(files.id) as total_files'),DB::raw('sum(files.approved_amount) as total_amount'))->first();
                $response = json_decode(json_encode($details),true);
            }elseif($ftskey=="partially"){
                /*$details = $details->addSelect(DB::raw('count(files.id) as total_files'),DB::raw('sum(partialamount)'))->get();
                $response = json_decode(json_encode($details),true);
                echo "<pre>"; print_r($response); die;*/
                $details = $details->get();
                $details = json_decode(json_encode($details),true);
                $partialamount = array_sum(array_column($details,'partialamount'));
                $response['total_files'] = count($details);
                $response['total_amount'] = $partialamount;
            }elseif($ftskey =="disbursement"){
                $details = $details->select(DB::raw('count(files.id) as total_files'),DB::raw('sum(files.disbursement_amount) as total_amount'))->first();
                $response = json_decode(json_encode($details),true);
            }else{
                $details = $details->select(DB::raw('count(files.id) as total_files'),DB::raw('sum(files.amount_requested) as total_amount'))->first();
                $response = json_decode(json_encode($details),true);
            }
            //echo "<pre>"; print_r($details); die;
        }
        return $response;
    }

    public function teamfiles(){
        return $this->hasOne('App\FileEmployee','file_id');
    }

    public function movedfiles(){
        return $this->hasOne('App\MovedFile','file_id');
    }

    public function partialfiles(){
        return $this->hasMany('App\PartialFile','file_id');
    }
}
