<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use Session;
use App\Employee;
use App\File;
use App\FileApproval;
class Controller extends BaseController
{
	public $mode;
	public function __construct(){
		 error_reporting(0);
		$whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $this->mode = "live";
        }else{
            $this->mode = "local";
        }
	}
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function empinfowithoutType($empid){
        $getEmpdetails = DB::table('employees')->where('id',$empid) ->select('name','type')->first();
        if($getEmpdetails){
            return $getEmpdetails->name;
        }else{
            return '';
        }
        
    }

    public function empinfo($empid){
        $getEmpdetails = DB::table('employees')->where('id',$empid) ->select('name','type')->first();
        $empTypeFullname = $this->empTypeFullname($getEmpdetails->type);
        return $getEmpdetails->name. " (".$empTypeFullname.")";
    }

    public function empemail($empid){
        $getEmpdetails = DB::table('employees')->where('id',$empid) ->select('email')->first();
        return $getEmpdetails->email;
    }

    public function getemptypes(){
    	$getemptypes = DB::table('employee_types')->where('status',1)->orderby('full_name')->get();
    	$getemptypes = json_decode(json_encode($getemptypes),true);
        
    	return $getemptypes;
    }

    public function empTypeFullname($type){
        $getEmpType = DB::table('employee_types')->where('short_name',$type)->first();
        return $getEmpType->full_name;
    }

    public function geteamLevels(){
        $getEmployees = Employee::with(['getemps'=>function($query){
            $query->with('getemps');
        }])->where('parent_id','ROOT')->get();
        $getEmployees = json_decode(json_encode($getEmployees),true);
        
        return $getEmployees;
    }

    public function gettel_old(){
        $getteldata = Employee::where('type','tel')->where('status',1)->orderby('name','asc')->get();
        $getteldata = json_decode(json_encode($getteldata),true);

         return $getteldata;
    }
    public function gettel(){
        $getteldata = Employee::where('team_id','!=','')->where('status',1)->orderby('name','asc')->get();
        $getteldata = json_decode(json_encode($getteldata),true);

         return $getteldata;
    }
    public function getteamtel(){

        if(Session::get('empSession')['type'] == "bm"){
          $gettldata = Employee::where('parent_id',Session::get('empSession')['id'])->where('type','TL')->get(); 
          $gettldata = json_decode(json_encode($gettldata),true);
          foreach($gettldata as $tldata){
            $tl_id = $tldata['id'];
            $getteldata = Employee::where('parent_id',$tl_id)->where('type','tel')->get();
            $getteldata = json_decode(json_encode($getteldata),true);
            return $getteldata;
          }
          
          
        }elseif(Session::get('empSession')['type'] == "tel"){

        $teldata = Employee::where('id',Session::get('empSession')['id'])->get();
        $teldata = json_decode(json_encode($teldata),true);
        foreach($teldata as $tel){ 
          $getteldata = Employee::where('parent_id',$tel['parent_id'])->where('type','tel')->get();
          $getteldata = json_decode(json_encode($getteldata),true);
          return $getteldata;
        }
        
        }elseif(Session::get('empSession')['type'] == "BH"){
            $bmdata = Employee::where('id',Session::get('empSession')['id'])->get();
            $bmdata = json_decode(json_encode($bmdata),true);
            
            foreach($bmdata as $bm){
                
                $bm_id = $bm['id'];
                $smsdata = Employee::where('parent_id',$bm_id)->where('type','bm')->get();
                $smsdata = json_decode(json_encode($smsdata),true);
                
               }
               if(!empty($smsdata)){
              foreach($smsdata as $sms){
                $sms_id = $sms['id'];
                $gettldata = Employee::where('parent_id',$sms_id)->where('type','TL')->get();
                $gettldata = json_decode(json_encode($gettldata),true);

               }
              foreach($gettldata as $tldata){
                $tl_id = $tldata['id'];
                $getteldata = Employee::where('parent_id',$tl_id)->where('type','tel')->get();
                $getteldata = json_decode(json_encode($getteldata),true);
                
                return $getteldata;
           
               
            
            }
            }else{
                $getteldata = Employee::where('parent_id',$bm_id)->where('type','tel')->get();
                $getteldata = json_decode(json_encode($getteldata),true);
                return $getteldata;
            }
              
            


        }elseif(Session::get('empSession')['type'] == "TL"){
            $gettldata = Employee::where('type',Session::get('empSession')['type'])->get();
            $gettldata = json_decode(json_encode($gettldata),true);
            foreach($gettldata as $tldata){
                $tl_id = $tldata['id'];
                $getteldata = Employee::where('parent_id',$tl_id)->where('type','tel')->get();
                $getteldata = json_decode(json_encode($getteldata),true);
                return $getteldata;
            }
        }
        else{
            $getteldata = Employee::where('type','tel')->get();
            $getteldata = json_decode(json_encode($getteldata),true);
            return $getteldata;
        }
    }

    public function getemployeedata(){
        $arr_id = array();
        $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
        $logdata = json_decode(json_encode($logdata),true);
         foreach($logdata as $llog)
         {
            //pushing parent id if the access is limited
            if($llog['is_access'] == 'limited')
            {
              $isRootCheck = Employee::where('id',$llog['parent_id'])->where('parent_id','ROOT')->first();
              if($isRootCheck)
              {
                // Root user. Take all the telecallers
                $root_user = $isRootCheck['id'];
                // Ist level
                $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
                $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                if(!empty($emp_parentdata))
                {
                  foreach($emp_parentdata as $empparent)
                  {
                    array_push($arr_id, $empparent['id']);
                    // IInd level
                    $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                    $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                    if(!empty($emp_parentdata))
                    {
                      foreach($emp_parentdata as $empparent)
                      {
                        array_push($arr_id, $empparent['id']);
                        // 3rd Level
                        $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                        $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                        if(!empty($emp_parentdata))
                        {
                          foreach($emp_parentdata as $empparent)
                          {
                            array_push($arr_id, $empparent['id']);
                          }
                        }
                      }
                    }


                  }
                }

              } 
              else 
              {
                // Not a root user. Find the root user
                $parent_data = Employee::where('id',$llog['parent_id'])->where('status',1)->first();
                //dd($parent_data);
                $parent_data_id = $parent_data['parent_id'];
                //dd($parent_data_id);
                $isRootCheck2 = Employee::where('id',$parent_data_id)->where('parent_id','ROOT')->first();
                if($isRootCheck2)
                {
                  // Root user. Take all the telecallers
                  $root_user = $isRootCheck2['id'];
                  // Ist level
                  $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
                  $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                  if(!empty($emp_parentdata))
                  {
                    foreach($emp_parentdata as $empparent)
                    {
                      array_push($arr_id, $empparent['id']);
                      // 2nd Level
                      $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata))
                      {
                        foreach($emp_parentdata as $empparent)
                        {
                          array_push($arr_id, $empparent['id']);
                          // 3rd Level
                          $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                          $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                          if(!empty($emp_parentdata))
                          {
                            foreach($emp_parentdata as $empparent)
                            {
                              array_push($arr_id, $empparent['id']);
                            }
                          }
                        }
                      }
                    }
                  }
                } else {
                  // 3rd Root Check
                  // Not a root user. Find the root user
                  $parent_data = Employee::where('id',$parent_data_id)->where('status',1)->first();
                  //dd($parent_data);
                  $parent_data_id = $parent_data['parent_id'];
                  //dd($parent_data_id);
                  $isRootCheck3 = Employee::where('id',$parent_data_id)->where('parent_id','ROOT')->first();
                  if($isRootCheck3)
                  {
                    // Root user. Take all the telecallers
                    $root_user = $isRootCheck3['id'];
                    // Ist level
                    $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
                    $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                    if(!empty($emp_parentdata))
                    {
                      foreach($emp_parentdata as $empparent)
                      {
                        array_push($arr_id, $empparent['id']);
                        // 2nd Level
                        $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                        $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                        if(!empty($emp_parentdata))
                        {
                          foreach($emp_parentdata as $empparent)
                          {
                            array_push($arr_id, $empparent['id']);
                            // 3rd Level
                            $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                            $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                            if(!empty($emp_parentdata))
                            {
                              foreach($emp_parentdata as $empparent)
                              {
                                array_push($arr_id, $empparent['id']);
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            //pushing parent id if the access is limited
            // if($llog['is_access'] == 'limited')
            // {
            //   array_push($arr_id, $llog['parent_id']);
            //   $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
            //   $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
            //   if(!empty($emp_parentdata)){
            //     foreach($emp_parentdata as $empparent){
            //       array_push($arr_id, $empparent['id']);
            //     }
            //   }
            // }

            array_push($arr_id, $llog['id']);
            $emponedata = Employee::where('parent_id',$llog['id'])->get();
            $emponedata = json_decode(json_encode($emponedata),true);
            if(!empty($emponedata)){
              foreach($emponedata as $empone){
                array_push($arr_id, $empone['id']);
                $emps = $this->setempdata($empone['id']);
                if($emps == true){
                  $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                  $emptwodata = json_decode(json_encode($emptwodata),true);
                  if(!empty($emptwodata)){
                    foreach($emptwodata as $emptwo){
                    array_push($arr_id, $emptwo['id']);
                    $emply = $this->setempdata($emptwo['id']);
                     if($emply == true){
                        $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                        $empthreedata = json_decode(json_encode($empthreedata),true);
                        if(!empty($empthreedata)){

                          foreach($empthreedata as $empthree){
                            array_push($arr_id, $empthree['id']);

                            $empls = $this->setempdata($empthree['id']);
                            if($empls == true){
                              $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                              $empfourdata = json_decode(json_encode($empfourdata),true);
                              if(!empty($empfourdata)){
                                foreach($empfourdata as $empfour){
                                    array_push($arr_id, $empfour['id']);
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
          Session::push('teamid', $arr_id);
          $team_arr =  Session::get('teamid');
         foreach($team_arr as $team_arrs){
          $querys = DB::table('employees')->whereIn('id',$team_arrs)->orderby('name','asc')->get();
          $querys = json_decode(json_encode($querys),true);
         }
         return $querys;
    }
    
	public function getemployeedataList(){
        $eng_id = Session::get('empSession')['id'];
        $employee_profile =  Employee::where('id',$eng_id)->first();
		$type = $employee_profile['type']; 
		$is_access = $employee_profile['is_access']; 
		if($type == 'admin' || $is_access == 'full'){ 
			  return $this->geteamLevels();
		}else if($is_access == 'limited' || $employee_profile['parent_id'] == 'ROOT' ){ 
			
			
			 $team_id = Session::get('empSession')['team_id'];
			$querys = DB::table('employees')->select('id')->where('id','!=',1)->where('team_id',$team_id)->get();
			return $querys; 
			
			
			/*
			
			if($employee_profile['parent_id'] != 'ROOT'){
				$parent_id =  $employee_profile['parent_id']; 
				$employee_admin = Employee::where('id',$parent_id)->first();
				if($employee_admin['parent_id'] != '' && $employee_admin['parent_id'] != 'ROOT' && is_numeric($employee_admin['parent_id']) == 1){ 
					$parent_id =  $employee_admin['parent_id']; 
					$employee_admin = Employee::where('id',$parent_id)->first();
				    if($employee_admin['parent_id'] != '' && $employee_admin['parent_id'] != 'ROOT' && is_numeric($employee_admin['parent_id']) == 1){ 
						$parent_id =  $employee_admin['parent_id'];					
						$employee_admin = Employee::where('id',$parent_id)->first();
						if($employee_admin['parent_id'] != '' && $employee_admin['parent_id'] != 'ROOT' && is_numeric($employee_admin['parent_id']) == 1){ 
							$parent_id =  $employee_admin['parent_id']; 
							$employee_admin = Employee::where('id',$parent_id)->first();
							if($employee_admin['parent_id'] != '' && $employee_admin['parent_id'] != 'ROOT' && is_numeric($employee_admin['parent_id']) == 1){ 
								$parent_id =  $employee_admin['parent_id'];		
								$employee_admin = Employee::where('id',$parent_id)->first();
								
							}else{
								$admin_id =  $employee_admin['id'];
							}
						}else{
							$admin_id =  $employee_admin['id'];
						}
					}else{
						$admin_id =  $employee_admin['id'];
					}
				}else{
					$admin_id =  $employee_admin['id'];
				}
			}else{
					$admin_id =  $employee_profile['id'];
			}
			
		   $eng_id_list= [];
			$getEmployees = Employee::select('id')->with(['getemps'=>function($query){
				$query->with('getemps');
			}])->where('parent_id','ROOT')->where('id',$admin_id)->get();
			
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
		 $engdata =  Employee::select('id')->wherein('id',$eng_id_list)->get();
         return $engdata;	
			*/
		}else{
			$parent_id = $employee_profile['parent_id'];
			$engdata =  Employee::select('id')->where('id',$parent_id)->get();
			return $engdata; 
		}
    }
    
	
	
    public function setempdata($empid){
       
        $empl = Employee::where('parent_id',$empid)->get();
        $empl = json_decode(json_encode($empl),true);
        
        if($empl){

            return true;
        }else{
            return false;
        }
    }

    
    public function getclientdata(){
        
        $arr_id = array();
          $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);
                 foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }
                 // dd($arr_id);
               Session::push('teamid', $arr_id);
               $team_arr =  Session::get('teamid');
               foreach($team_arr as $team_arrs){
                $querys = DB::table('clients')->whereIn('tel_name',$team_arrs)->orWhereIn('created_emp',$arr_id)->get();
                $querys = json_decode(json_encode($querys),true);
               }

               return $querys;

    }

    
   public function get_emp_clientdata($EngId){
        
        $arr_id = array();
          $logdata = Employee::where('id',$EngId)->get();
                $logdata = json_decode(json_encode($logdata),true);
                 foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }
                 // dd($arr_id);
               Session::push('teamid', $arr_id);
               $team_arr =  Session::get('teamid');
               foreach($team_arr as $team_arrs){
                $querys = DB::table('clients')->whereIn('tel_name',$team_arrs)->orWhereIn('created_emp',$arr_id)->get();
                $querys = json_decode(json_encode($querys),true);
               }

               return $querys;

    }

   public function getclientdataList(){
        
     $logdata = Employee::where('id',Session::get('empSession')['id'])->first();
     
	 if($logdata['is_access'] == 'limited'){
		 $querys = $this->getemployeedataList();
		 $emp_id_list=json_decode( json_encode($querys), true);
		 $arr_id = array_column($emp_id_list, 'id');
		
		 Session::push('teamid', $arr_id);
         $team_arr =  Session::get('teamid');
         $arr_key = count($team_arr);
         $querys = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->get();
         $querys = json_decode(json_encode($querys),true);
               
        
	 }else{
		$querys =  $this->getclientdata();
	 }
	 
	 return $querys;

    }

    



   

   

    
    public function getpartnerdata(){
        $arr_id = array();
          $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);
                 foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }
                 Session::push('teamid', $arr_id);
                  $team_arr =  Session::get('teamid');
               foreach($team_arr as $team_arrs){
                $querys = DB::table('channel_partners')->whereIn('emp_id',$team_arrs)->get();
                $querys = json_decode(json_encode($querys),true);
               }


               return $querys;
    }

    
	public function getpartnerdataList(){
        $logdata = Employee::where('id',Session::get('empSession')['id'])->first();               
        if($logdata['is_access'] == 'limited'){
			    $emp_id_list = $this->getemployeedataList();
				$emp_id_list=json_decode( json_encode($emp_id_list), true);
				$arr_id = array_column($emp_id_list, 'id');
			   Session::push('teamid', $arr_id);
               $team_arr =  Session::get('teamid');
               foreach($team_arr as $team_arrs){
                $querys = DB::table('channel_partners')->whereIn('emp_id',$team_arrs)->get();
                $querys = json_decode(json_encode($querys),true);
               }
		}else{
			$querys = $this->getpartnerdata();
		}
        return $querys;
    }

    
	
	public function getfiledata($type){

        $arr_id = array();
        $cli_id = array();
                $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);

                foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, (int)$llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }
                 
                 //dd($arr_id);
                
                $clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->get();
                $clientdata = json_decode(json_encode($clientdata),true);
                 
                foreach($clientdata as $cli){
                    array_push($cli_id, $cli['id']);
                }

                
                
                $querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id')->whereIn('files.client_id',$cli_id)->OrderBy('files.id','DESC')->get();
                $querys = json_decode(json_encode($querys),true);
                // dd($querys);
              
                if(empty($querys)){
                    $querys = array();
                }
               
              return $querys;
    }

    public function getfiledataList($type){

                $client_id_list = $this->getclientdataList();
				$cli_id = array_column($client_id_list, 'id');
                
                $querys = File::with('getbank')->join('file_employees','file_employees.file_id','=','files.id')->join('clients','clients.id','=','files.client_id')->select('files.*','clients.customer_name as client_name','clients.company_name','file_employees.employee_id as salesofficer')->where('move_to',$type)->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id')->whereIn('files.client_id',$cli_id)->OrderBy('files.id','DESC')->get();
                $querys = json_decode(json_encode($querys),true);
                // dd($querys);
              
                if(empty($querys)){
                    $querys = array();
                }
               
              return $querys;
    }

	
	public function pendingfiledata(){

      $arr_id = array();
        $cli_id = array();
                $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);

                foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }
                 $clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->get();
                 $clientdata = json_decode(json_encode($clientdata),true);
                 
                 foreach($clientdata as $cli){
                    array_push($cli_id, $cli['id']);
                 }

                 $querys = FileApproval::join('files','files.id','=','file_approvals.file_id')->join('clients','clients.id','=','files.client_id')->join('employees','employees.id','=','file_approvals.approval_from')->select('file_approvals.*','files.file_no','files.id as fileid','files.facility_type','clients.customer_name','clients.company_name','employees.name as empname')->whereIn('files.client_id',$cli_id)->OrderBy('file_approvals.created_at','DESC')->get();
                 $querys = json_decode(json_encode($querys),true);
                 if(empty($querys)){
                    $querys = array();
                 }
               
              return $querys;
                 
    }

     public function pendingfiledataList(){

		 $clientdata = $this->getclientdataList();
		 $cli_id = [];
		 foreach($clientdata as $cli){
			array_push($cli_id, $cli['id']);
		 }

		 $querys = FileApproval::join('files','files.id','=','file_approvals.file_id')->join('clients','clients.id','=','files.client_id')->join('employees','employees.id','=','file_approvals.approval_from')->select('file_approvals.*','files.file_no','files.id as fileid','files.facility_type','clients.customer_name','clients.company_name','employees.name as empname')->whereIn('files.client_id',$cli_id)->OrderBy('file_approvals.created_at','DESC')->get();
		 $querys = json_decode(json_encode($querys),true);
		 if(empty($querys)){
			$querys = array();
		 }
	   
		 return $querys;
                 
    }

    
	
	public function disbdata(){
      $arr_id = array();
        $cli_id = array();
                $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);

                foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']);
                      $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }

                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 }

                 $clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->get();
                 $clientdata = json_decode(json_encode($clientdata),true);
                 
                 foreach($clientdata as $cli){
                    array_push($cli_id, $cli['id']);
                 }
                 $querys = File::with('getbank')->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->join('file_employees','file_employees.file_id','=','files.id')->select('files.*','clients.name as client_name','clients.company_name','file_disbursements.emi_amt','file_disbursements.lan_no','file_disbursements.amount','file_employees.employee_id as salesofficer')->where('move_to','disbursement')->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id')->whereIn('files.client_id',$cli_id)->OrderBy('files.id','DESC')->get();
               $querys = json_decode(json_encode($querys),true);
               if(empty($querys)){
                    $querys = array();
                 }
               
              return $querys;
    }

    public function partdisbdata(){
      $arr_id = array();
        $cli_id = array();
                $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
                $logdata = json_decode(json_encode($logdata),true);

                foreach($logdata as $llog){
                    //pushing parent id if the access is limited
                    if($llog['is_access'] == 'limited')
                    {
                      array_push($arr_id, $llog['parent_id']); 
					 
                      $emp_parentdata = Employee::where('parent_id','!=','ROOT')->where('parent_id',$llog['parent_id'])->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                     
					  if(!empty($emp_parentdata)){
                        foreach($emp_parentdata as $empparent){
                          array_push($arr_id, $empparent['id']);
                        } 
                      }
                    }
					
                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = $this->setempdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = $this->setempdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = $this->setempdata($empthree['id']);
                                       if($empls == true){
                                         $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                                         $empfourdata = json_decode(json_encode($empfourdata),true);
                                          if(!empty($empfourdata)){
                                            foreach($empfourdata as $empfour){
                                                array_push($arr_id, $empfour['id']);
                                            }
                                          }
                                       }
                                    }
                                }
                             }
                            }

                           }
                        }
                        
                    }
                }
                 } 
                 $clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->get();
                 $clientdata = json_decode(json_encode($clientdata),true);
                 
                 foreach($clientdata as $cli){
                    array_push($cli_id, $cli['id']);
                 }

                 $querys = $querys = File::with('getbank')->join('clients','clients.id','=','files.client_id')->join('file_disbursements','file_disbursements.file_id','=','files.id')->join('file_employees','file_employees.file_id','=','files.id')->select('files.*','clients.name as client_name','clients.company_name','file_disbursements.emi_amt','file_disbursements.lan_no','file_disbursements.amount','file_employees.employee_id as salesofficer')->where('move_to','partially')->where(function ($query) { $query->orWhere('file_employees.type','BH')->orWhere('file_employees.type','salesmanager')->orWhere('file_employees.type','bm')->orWhere('file_employees.type','opm')->orWhere('file_employees.type','op')->orWhere('file_employees.type','tel')->orWhere('file_employees.type','docboy')->orWhere('file_employees.type','AH')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','HR-M')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','deo')->orWhere('file_employees.type','FSE')->orWhere('file_employees.type','ceo')->orWhere('file_employees.type','TL')->orWhere('file_employees.type','AA')->orWhere('file_employees.type','ITH')->orWhere('file_employees.type','PEON')->orWhere('file_employees.type','BOE')->orWhere('file_employees.type','hro')->orWhere('file_employees.type','chp');})
               ->groupBy('file_employees.file_id')->whereIn('files.client_id',$cli_id)->OrderBy('files.id','DESC')->get();
               $querys = json_decode(json_encode($querys),true);
               if(empty($querys)){
                    $querys = array();
                 }
               
              return $querys;
    }

   
    public function getteam($empid){
        $getEmployees = Employee::with(['getemps'=>function($query){
            $query->with('getemps');
        }])->where('id',$empid)->get();
        $getEmployees = json_decode(json_encode($getEmployees),true);
         
        return $getEmployees;
    }

    public function checkTeam($empid){
        $employeedetails = Employee::where('id',$empid)->first();
        $employeedetails =json_decode(json_encode($employeedetails),true);
        $teamdetails="";
        if($employeedetails['parent_id'] =="ROOT"){
            $getdetails = Employee::with(['getemps'=>function($query){
                $query->with('getemps');
            }])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            $teamdetails .= '<ul>
                                <li>'.$getdetails['name'].'</li>
                                <ul>';
                                    foreach($getdetails['getemps'] as $level1){
                                        $teamdetails .= '<li>'.$level1['name'].'</li><ul>';
                                        foreach ($level1['getemps'] as $key => $level2) {
                                            $teamdetails .= '<li>'.$level2['name'].'</li>';
                                        }
                                        $teamdetails .='</ul>';
                                    }
                                $teamdetails .= '</ul>
                            </ul>';
        }else{
            $getdetails = Employee::with(['getemps'])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            if(!empty($getdetails['getemps'])){
                $teamdetails .='<ul>
                                    <li>'.$getdetails['name'].'</li><ul>';
                                    foreach($getdetails['getemps'] as $level1){
                                        $teamdetails .= '<li>'.$level1['name'].'</li>';
                                    }
                                $teamdetails .= '</ul></ul>';
            }

        }
        return $teamdetails;
    }

    public function getEmployees($empid){
        $employeedetails = Employee::where('id',$empid)->first();
        $employeedetails =json_decode(json_encode($employeedetails),true);
        if($employeedetails['parent_id'] =="ROOT"){
            $getdetails = Employee::with(['getemps'=>function($query){
                $query->with('getemps');
            }])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            $empids[]  = $getdetails['id']; 
                foreach($getdetails['getemps'] as $level1){
                    $empids[]  = $level1['id'];
                    foreach ($level1['getemps'] as $key => $level2) {
                        $empids[] =$level2['id'];
                    }
                }
        }else{
            $getdetails = Employee::with(['getemps'])->where('id',$empid)->first();
            $getdetails = json_decode(json_encode($getdetails),true);
            if(!empty($getdetails['getemps'])){
                $empids[]  = $getdetails['id']; 
                foreach($getdetails['getemps'] as $level1){
                    $empids[]  = $level1['id'];
                }
            }else{
                $empids[]  = $getdetails['id'];
            }
            //pushing parent id if the access is limited
            if($getdetails['is_access'] == 'limited')
            {
              array_push($empids, $getdetails['parent_id']);
              $emp_parentdata = Employee::where('parent_id',$getdetails['parent_id'])->get();
              $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
              if(!empty($emp_parentdata)){
                foreach($emp_parentdata as $empparent){
                  array_push($empids, $empparent['id']);
                }
              }
            }
        }
        return $empids;
    }

    public function getproducts(){
        $getproducts = DB::table('products')->orderBy('name')->get();
        $getproducts = json_decode(json_encode($getproducts),true);
        
        return $getproducts;
    }

    public function getbankinfo($bankid){
        $bankdetails = DB::table('banks')->where('id',$bankid)->first();
        return $bankname = $bankdetails->full_name; 
    }

    public function getleadstatus($addleadstatus){
        if($addleadstatus ==1){
            $getleadstatuses = DB::table('lead_statuses')->where('status',1)->where('add_lead_status',1)->get();
        }elseif($addleadstatus =="update"){
            if(Session::get('empSession')['type']=="car"){
                $getleadstatuses = DB::table('lead_statuses')->where('status',1)->where('type','!=',2)->where('update_lead_status',1)->get();
            }else{
                $getleadstatuses = DB::table('lead_statuses')->where('status',1)->where('update_lead_status',1)->get();
            }
        }else{
            $getleadstatuses = DB::table('lead_statuses')->get();
        }
        $getleadstatuses = json_decode(json_encode($getleadstatuses),true);
        return $getleadstatuses;
    }

    public function customerProfiles(){
        $profiles = DB::table('customer_profiles')->get();
        $profiles = json_decode(json_encode($profiles),true);
        return $profiles;
    }

    public function states(){
        $getstates = DB::table('states')->where('status',1)->get();
        $getstates = json_decode(json_encode($getstates),true);
        return $getstates;
    }
    
    public function desigdata(){
        $getdata = DB::table('designation')->get();
        $getdata = json_decode(json_encode($getdata),true);
        return $getdata;
    }
    public function channeldata(){
      $arr_id = array();
      $logdata = Employee::where('id',Session::get('empSession')['id'])->get();
      $logdata = json_decode(json_encode($logdata),true);
       foreach($logdata as $llog)
       {
          //pushing parent id if the access is limited
          if($llog['is_access'] == 'limited')
          {
            $isRootCheck = Employee::where('id',$llog['parent_id'])->where('parent_id','ROOT')->first();
            if($isRootCheck)
            {
              // Root user. Take all the telecallers
              $root_user = $isRootCheck['id'];
              // Ist level
              array_push($arr_id, $root_user);
              $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
              $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
              if(!empty($emp_parentdata))
              {
                foreach($emp_parentdata as $empparent)
                {
                  array_push($arr_id, $empparent['id']);
                  // IInd level
                  $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                  $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                  if(!empty($emp_parentdata))
                  {
                    foreach($emp_parentdata as $empparent)
                    {
                      array_push($arr_id, $empparent['id']);
                      // 3rd Level
                      $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata))
                      {
                        foreach($emp_parentdata as $empparent)
                        {
                          array_push($arr_id, $empparent['id']);
                        }
                      }
                    }
                  }

                }
              }

            } 
            else 
            {
              // Not a root user. Find the root user
              $parent_data = Employee::where('id',$llog['parent_id'])->where('status',1)->first();
              //dd($parent_data);
              $parent_data_id = $parent_data['parent_id'];
              //dd($parent_data_id);
              $isRootCheck2 = Employee::where('id',$parent_data_id)->where('parent_id','ROOT')->first();
              if($isRootCheck2)
              {
                // Root user. Take all the telecallers
                $root_user = $isRootCheck2['id'];
                array_push($arr_id, $root_user);
                // Ist level
                $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
                $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                if(!empty($emp_parentdata))
                {
                  foreach($emp_parentdata as $empparent)
                  {
                    array_push($arr_id, $empparent['id']);
                    // 2nd Level
                    $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                    $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                    if(!empty($emp_parentdata))
                    {
                      foreach($emp_parentdata as $empparent)
                      {
                        array_push($arr_id, $empparent['id']);
                        // 3rd Level
                        $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                        $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                        if(!empty($emp_parentdata))
                        {
                          foreach($emp_parentdata as $empparent)
                          {
                            array_push($arr_id, $empparent['id']);
                          }
                        }
                      }
                    }
                  }
                }
              } else {
                // 3rd Root Check
                // Not a root user. Find the root user
                $parent_data = Employee::where('id',$parent_data_id)->where('status',1)->first();
                //dd($parent_data);
                $parent_data_id = $parent_data['parent_id'];
                //dd($parent_data_id);
                $isRootCheck3 = Employee::where('id',$parent_data_id)->where('parent_id','ROOT')->first();
                if($isRootCheck3)
                {
                  // Root user. Take all the telecallers
                  $root_user = $isRootCheck3['id'];
                  // Ist level
                  $emp_parentdata = Employee::where('parent_id',$root_user)->where('status',1)->get();
                  $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                  if(!empty($emp_parentdata))
                  {
                    foreach($emp_parentdata as $empparent)
                    {
                      array_push($arr_id, $empparent['id']);
                      // 2nd Level
                      $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                      $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                      if(!empty($emp_parentdata))
                      {
                        foreach($emp_parentdata as $empparent)
                        {
                          array_push($arr_id, $empparent['id']);
                          // 3rd Level
                          $emp_parentdata = Employee::where('parent_id',$empparent['id'])->where('status',1)->get();
                          $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
                          if(!empty($emp_parentdata))
                          {
                            foreach($emp_parentdata as $empparent)
                            {
                              array_push($arr_id, $empparent['id']);
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }

          //pushing parent id if the access is limited
          // if($llog['is_access'] == 'limited')
          // {
          //   array_push($arr_id, $llog['parent_id']);
          //   $emp_parentdata = Employee::where('parent_id',$llog['parent_id'])->get();
          //   $emp_parentdata = json_decode(json_encode($emp_parentdata),true);
          //   if(!empty($emp_parentdata)){
          //     foreach($emp_parentdata as $empparent){
          //       array_push($arr_id, $empparent['id']);
          //     }
          //   }
          // }

          array_push($arr_id, $llog['id']);
          $emponedata = Employee::where('parent_id',$llog['id'])->get();
          $emponedata = json_decode(json_encode($emponedata),true);
          if(!empty($emponedata)){
            foreach($emponedata as $empone){
              array_push($arr_id, $empone['id']);
              $emps = $this->setempdata($empone['id']);
              if($emps == true){
                $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                $emptwodata = json_decode(json_encode($emptwodata),true);
                if(!empty($emptwodata)){
                  foreach($emptwodata as $emptwo){
                  array_push($arr_id, $emptwo['id']);
                  $emply = $this->setempdata($emptwo['id']);
                   if($emply == true){
                      $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                      $empthreedata = json_decode(json_encode($empthreedata),true);
                      if(!empty($empthreedata)){

                        foreach($empthreedata as $empthree){
                          array_push($arr_id, $empthree['id']);

                          $empls = $this->setempdata($empthree['id']);
                          if($empls == true){
                            $empfourdata = Employee::where('parent_id',$empthree['id'])->get();
                            $empfourdata = json_decode(json_encode($empfourdata),true);
                            if(!empty($empfourdata)){
                              foreach($empfourdata as $empfour){
                                  array_push($arr_id, $empfour['id']);
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
        //dd($arr_id);
       $querys = DB::table('channel_partners')->whereIn('emp_id',$arr_id)->where('status',1)->get();
        $querys = json_decode(json_encode($querys),true);
       return $querys;





        // $partner = DB::table('channel_partners')->where('status',1)->get();
        // $partner = json_decode(json_encode($partner),true);
        // return $partner;
    }

    public function cities($stateid){
        $getcities = DB::table('cities')->where('state_id',$stateid)->orderBy('city')->get();
        $getcities = json_decode(json_encode($getcities),true);
        
        return $getcities;
    }

    public function channelpartnerinfo($partnerid){
        $getdetails = DB::table('channel_partners')->where('id',$partnerid) ->select('name','type')->first();
        if($getdetails){
            return $getdetails->name. " (".$getdetails->type.")";
        }else{
            return '';
        }
        
    }

    public function getTeamEmails($empid){
        $employeedetails = Employee::where('id',$empid)->first();
        $employeedetails =json_decode(json_encode($employeedetails),true);
        $ccemails = array();
        if(Session::get('empSession')['type'] !="admin"){
            if($employeedetails['parent_id'] !="ROOT"){
                $getMemberEmail = DB::table('employees')->where('id',$employeedetails['parent_id'])->select('email','parent_id')->first();
                $ccemails[] = $getMemberEmail->email;
                if($getMemberEmail->parent_id !="ROOT"){
                   $getMembEmail = DB::table('employees')->where('id',$getMemberEmail->parent_id)->select('email','parent_id')->first(); 
                   $ccemails[] = $getMembEmail->email;
                }
            }
        }
        return $ccemails;
    }

    public function leadStatus($statusid){
        $getleadstatus = DB::table('lead_statuses')->where('id',$statusid)->first();
        return $getleadstatus->name;
    }
	
	public  function checkEmpAccess($module_id){
	   $view_access = 0; $edit_access = 0; $delete_access = 0;
	  if(Session::get('empSession')['type'] == "admin" ||  Session::get('empSession')['is_access']=="full"){
		  $view_access = 1;$edit_access = 1;$delete_access = 1;
	  }else{
		  $modules = DB::table('modules')->where('id',$module_id)->first();
		  $modules = json_decode(json_encode($modules),true);
		  if(!empty($modules)){
			  $roles = DB::table('employee_roles')->where('emp_id',Session::get('empSession')['id'])->where('module_id',$modules['id'])->first();
			  $roles = json_decode(json_encode($roles),true);
			  if(!empty($roles)){ 
				  if($roles['view_access'] == 1){
					  $view_access = 1;
				  }
				  if($roles['edit_access'] == 1){
					  $edit_access = 1;
				  }
				  if($roles['delete_access'] == 1){
					  $delete_access = 1;
				  }
			  }
		  }
	  }
		return ['module_id'=>$module_id,'view_access'=>$view_access,'edit_access'=>$edit_access,'delete_access'=>$delete_access];
	}
	
	
	
	public  function getEngTeamLevels($eng_id){ 
		$employee_profile =  Employee::where('id',$eng_id)->first();
		$type = $employee_profile['type']; 
		$is_access = $employee_profile['is_access']; 
		if($type == 'admin' || $is_access == 'full'){ 
			  return $this->geteamLevels();
		}else if($is_access == 'limited' ){ 
			
			$engdata =  Employee::where('team_id',$employee_profile['team_id'])->get();
			return $engdata;
		}else{
			$parent_id = $employee_profile['parent_id'];
			$engdata =  Employee::where('id',$parent_id)->get();
			return $engdata; 
		}
	}
	public  function getEmpLevel(){ 
	    $EngLevel = array();
	   
				$getTeamLevels = $this->geteamLevels();
                foreach($getTeamLevels as $key => $level){
                    $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
					$EngLevel[$level['id']] = 1; 
                    foreach($level['getemps'] as $skey => $sublevel1){
                        $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); 
                        $EngLevel[$sublevel1['id']] = 2; 						
                        foreach($sublevel1['getemps'] as $sskey=> $sublevel2){
                            $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); 
                            $EngLevel[$sublevel2['id']] = 3;
                            $getdetails = Employee::with(['getemps'=>function($query){
                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            foreach($getdetails['getemps'] as $ssskey=> $sublevel3){
                                 $EngLevel[$sublevel3['id']] = 4;
                                 $getdetails = Employee::with(['getemps'=>function($query){

                                     $query->with('getemps');

                                 }])->where('id',$sublevel3['id'])->first();
                                 $getdetails = json_decode(json_encode($getdetails),true);
                                
                                foreach($getdetails['getemps'] as $ssskey=> $sublevel4){
                                      $EngLevel[$sublevel4['id']] = 5;
									  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); 
                                     
                                }
							}
                        }
					}
                }
	
        return $EngLevel;
    }
	
	public function getEmployeeTeamId(){
		
		$check_team_id = Employee::max('team_id');
		if(empty($check_team_id)){
			$team_id = '1';
		}else{
			$team_id = $check_team_id + '1';
		}
		return $team_id;
	}
}
