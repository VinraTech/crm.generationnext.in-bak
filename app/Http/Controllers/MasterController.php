<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;
use Cookie;
use Session;
use Crypt;
use App\LeadStatus;
use App\Bank;
use App\Banker;
use App\BankProduct;
class MasterController extends Controller
{
    public function allleadStatus(Request $Request){
        Session::put('active',6); 
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();
            $querys = DB::table('lead_statuses');
            if(!empty($data['name'])){
                $querys = $querys->where('lead_statuses.name','like','%'.$data['name'].'%');
            }
            $querys = $querys->OrderBy('lead_statuses.id','Desc');
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
            foreach($querys as $leadstatus){ 
            	$checked='';
                if($leadstatus['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
                $actionValues='<a title="Edit Status" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-lead-status/'.$leadstatus['id']).'"> <i class="fa fa-edit"></i>';
                $num = ++$i;
                $addleadStatus = "Yes";
                $updateLeadStatus = "Yes";
                if($leadstatus['add_lead_status'] == 0){
                	$addleadStatus = "No";
                }
                if($leadstatus['update_lead_status'] == 0){
                	$updateLeadStatus = "No";
                }
                $type="";
                if($leadstatus['type'] == 1){
                	$type = "Appointment Date & Time";
                }else if($leadstatus['type'] == 2){
                	$type = "Refer to Auto Loan";
                }
                $records["data"][] = array(     
                    $num,
                    $leadstatus['name'],  
                    $type,  
                    $addleadStatus,
                    $updateLeadStatus,
                    '<div  id="'.$leadstatus['id'].'" rel="lead_statuses" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Lead Status - Express Paisa";
        return View::make('admin.master.lead-statuses')->with(compact('title'));
    }

    public function addEditLeadStatus(Request $request, $statusid=null){
    	if($statusid !=""){
    		$title = "Edit Lead Status - Express Paisa";
    		$getStatusdetails = DB::table('lead_statuses')->where('id',$statusid)->first();
    		$getStatusdetails = json_decode(json_encode($getStatusdetails),true);
    		$message ="Status has been updated successfully!";
    	}else{
    		$title = "Add Lead Status - Express Paisa";
    		$getStatusdetails = array();
    		$message ="Status has been added successfully!";
    	}
    	if($request->isMethod('post')){
    		$data = $request->all();
    		if($statusid !=""){
    			$leadstatus = LeadStatus::find($statusid);
    			$sortnumber = $leadstatus->sort;
    		}else{
    			$leadstatus = new LeadStatus;
    			$getLastSortnumber = DB::table('lead_statuses')->OrderBy('sort','desc')->first();
    			$sortnumber = $getLastSortnumber->sort+1;
    		}
    		$leadstatus->name = $data['name'];
    		$leadstatus->type = $data['type'];
    		$leadstatus->add_lead_status = $data['add_lead_status'];
            $leadstatus->update_lead_status = $data['update_lead_status'];
    		$leadstatus->lead_behaviour = $data['lead_behaviour'];
    		$leadstatus->status = 1;
    		$leadstatus->sort = $sortnumber;
    		$leadstatus->save();
    		return Redirect()->action('MasterController@allleadStatus')->with('flash_message_success',$message);
    	}
    	return view('admin.master.add-edit-lead-status')->with(compact('title','getStatusdetails'));
    }

    public function banks(Request $Request){
        // dd($Request->input());
        Session::put('active',7); 
		$banker_access = $this->checkEmpAccess('10');	
        if($Request->ajax()){
            $conditions = array();
            $data = $Request->input();

            $querys = DB::table('bankers');
            if(!empty($data['banker_name'])){
                $querys = $querys->where('bankers.banker_name','like','%'.$data['banker_name'].'%');
            }
            if(!empty($data['bank_name'])){
                $querys = $querys->where('bankers.bank_name','like','%'.$data['bank_name'].'%');
            }
            if(!empty($data['rm_code'])){
                $querys = $querys->where('bankers.rm_code','like','%'.$data['rm_code'].'%');
            }
            if(!empty($data['email'])){
                $querys = $querys->where('bankers.email','like','%'.$data['email'].'%');
            }
            if(!empty($data['phone_number'])){
                $querys = $querys->where('bankers.phone_number','like','%'.$data['phone_number'].'%');
            }
            if(!empty($data['address'])){
                $querys = $querys->where('bankers.address','like','%'.$data['address'].'%');
            }
            if(!empty($data['state'])){
                $querys = $querys->where('bankers.state','like','%'.$data['state'].'%');
            }
            if(!empty($data['district'])){
                $querys = $querys->where('bankers.district','like','%'.$data['district'].'%');
            }
            $querys = $querys->OrderBy('bankers.id','Desc');
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
            foreach($querys as $bank){ 
			    $actionValues = '';
            	if($banker_access['edit_access'] == 1){
					$actionValues='<a title="Edit Banker" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-bank/'.$bank['id']).'"> <i class="fa fa-edit"></i>';
				}
                if($banker_access['delete_access'] == 1){
					$actionValues .= '<a title="Delete Banker" onclick=" return ConfirmDelete()" class="btn btn-sm margin-top-10 red" href="'.url('/s/admin/delete-banker/'.$bank['id']).'"><i class="fa fa-times"></i></a>';
                }
				$num = ++$i;
                $records["data"][] = array(     
                    $num,
                    $bank['banker_name'],  
                     $bank['bank_name'],
                     $bank['rm_code'],
                     $bank['email'],
                     $bank['phone_number'],
                     $bank['address'],
                     $bank['state'],
                     $bank['district'],
                     
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Bankers - Express Paisa";
        return View::make('admin.master.banks')->with(compact('title','banker_access'));
    }

    public function addEditBank(Request $request, $id=null){
        // dd($request->all());
        
        if($id !=""){
            $bankerid = $id;
            $title = "Edit Banker - Express Paisa";
            $getbankdetails = DB::table('bankers')->where('id',$id)->first();
            $getbankdetails = json_decode(json_encode($getbankdetails),true);
            $banProducts = DB::table('banker_product')->where('banker_id',$id)->get();
            $banProducts =json_decode(json_encode($banProducts),true);
            $banPids = array_map(function ($ar) {return $ar['product_id'];}, $banProducts);
            if(!empty($getbankdetails['state'])){
                $getstateid = DB::table('states')->select('id')->where('state',$getbankdetails['state'])->first();
                $cities = $this->cities($getstateid->id);
            }
            
            $message ="Banker has been updated successfully!";
        }else{
            

            $title = "Add Banker - Express Paisa";
            $getbankdetails = array();
            $cities = array();
            $banPids = array();
            $banProducts = array();
            $message ="Banker has been added successfully!";
        }
        if($request->isMethod('post')){
            $data = $request->all();
             $bankerproducts = $data['product'];
             unset($data['product']);
            if($id !=""){
                $bank = Banker::find($id);
            }else{
                $bank = new Banker;
            }
            $ban_id = DB::table('banks')->where('full_name',$data['bank_name'])->first();
            $ban_id = json_decode(json_encode($ban_id),true);
            
            $bank->bank_id = $ban_id['id'];
            // dd($bank->banker_id);
            $bank->banker_name = $data['banker_name'];
            $bank->bank_name = $data['bank_name'];
            $bank->rm_code = $data['rm_code'];
            
            $bank->city = $data['city'];
            $bank->email = $data['email'];
            $bank->phone_number = $data['phone_number'];
            $bank->address = $data['address'];
            $bank->state = $data['state'];
            $bank->district = $data['district'];
            
            $bank->save();
            $bankerid = DB::getPdo()->lastInsertId();
            
            foreach($bankerproducts as $bankerprod){
                $banproduct = new BankProduct;
                $banproduct->banker_id = $bankerid;
                $banproduct->product_id = $bankerprod;

                $banproduct->save();
            }
            return Redirect()->action('MasterController@banks')->with('flash_message_success',$message);
        }
        $states = $this->states();
        $banks = DB::table('banks')->where('status',1)->orderby('full_name','asc')->get();
        $banks = json_decode(json_encode($banks),true);

        return view('admin.master.add-edit-bank')->with(compact('title','getbankdetails','states','cities','banks','banPids'));
    }

    public function deleteBanker($id){
        
        Banker::where('id',$id)->delete();
        return redirect()->action('MasterController@banks')->with('flash_message_success','Record has been deleted successfully!');
    }

    public function bank(Request $Request){

        Session::put('active',51);
        $bank_access = $this->checkEmpAccess('32');		 
        if($Request->ajax()){
            // dd("ds");
            $conditions = array();
            $data = $Request->input();

            $querys = DB::table('banks');
            
            if(!empty($data['full_name'])){
                $querys = $querys->where('banks.id',$data['full_name']);
            }
            $querys = $querys->OrderBy('banks.id','Desc');
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
            foreach($querys as $bank){ 
                $checked='';
                if($bank['status']==1){
                    $checked='on';
                }else{
                    $checked='off';
                }
				$actionValues = '';
				if($bank_access['edit_access'] == 1){
					$actionValues='<a title="Edit Bank" class="btn btn-sm green margin-top-10" href="'.url('s/admin/add-edit-banks/'.$bank['id']).'"> <i class="fa fa-edit"></i>';
                }
				if($bank_access['delete_access'] == 1){
				   $actionValues .= '<a title="Delete Bank" onclick=" return ConfirmDelete()" class="btn btn-sm margin-top-10 red" href="'.url('/s/admin/delete-banks/'.$bank['id']).'"><i class="fa fa-times"></i></a>';
                }
				$num = ++$i;
                $records["data"][] = array(     
                    $num,
                    $bank['full_name'],  
                     $bank['short_name'],
                     $bank['type'],
                    '<div  id="'.$bank['id'].'" rel="banks" class="bootstrap-switch  bootstrap-switch-'.$checked.'  bootstrap-switch-wrapper bootstrap-switch-animate toogle_switch">
                    <div class="bootstrap-switch-container" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary">&nbsp;Active&nbsp;&nbsp;</span><label class="bootstrap-switch-label">&nbsp;</label><span class="bootstrap-switch-handle-off bootstrap-switch-default">&nbsp;Inactive&nbsp;</span></div></div>', 
                    $actionValues
                );
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            return response()->json($records);
        }
        $title = "Banks - Express Paisa";
        return View::make('admin.master.bank')->with(compact('title','bank_access'));

    }

    public function addEditBanks(Request $request, $bankid=null){
        // dd($request->all());
        if($bankid !=""){
            $title = "Edit Bank - Express Paisa";
            $getbankdetails = DB::table('banks')->where('id',$bankid)->first();
            $getbankdetails = json_decode(json_encode($getbankdetails),true);
            
           
            $message ="Bank has been updated successfully!";
        }else{
            $title = "Add Bank - Express Paisa";
            $getbankdetails = array();
            
            $message ="Bank has been added successfully!";
        }
        if($request->isMethod('post')){
            $data = $request->all();
            
            if($bankid !=""){
                $bank = Bank::find($bankid);
            }else{
                $bank = new Bank;
            }
            $bank->full_name = $data['full_name'];
            $bank->short_name = $data['short_name'];
            $bank->type = $data['type'];
            $bank->status = 1;
            $bank->save();
            return Redirect()->action('MasterController@bank')->with('flash_message_success',$message);
        }
        
        return view('admin.master.add-edit-banks')->with(compact('title','getbankdetails'));
    }

    public function deleteBanks($id){
        
        Bank::where('id',$id)->delete();
        return redirect()->action('MasterController@bank')->with('flash_message_success','Record has been deleted successfully!');
    }
}
