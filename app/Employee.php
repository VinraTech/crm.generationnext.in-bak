<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;
use Session;
use DB;
class Employee extends Model
{
    //
    public function getemps(){
    	return $this->hasMany('App\Employee','parent_id')->select('id','name','team_id','type','parent_id')->orderby('name','asc');
    }

    public function getproducts(){
    	return $this->hasMany('App\EmployeeProduct','emp_id')->with('productdetail');
    }

    public function getemp(){
    	return $this->belongsTo('App\Employee','parent_id')->select('id','name','email','type','parent_id')->with('getemp');
    }

    public static function getemployees($type){
        if($type =="bm"){
            $getemps = Employee::join('employee_types','employee_types.short_name','=','employees.type')->select('employees.*','employee_types.full_name as emptype')->where('type',$type)->where('employees.status',1)->get(); 
        }else{
            $getemps = Employee::join('employee_types','employee_types.short_name','=','employees.type')->select('employees.*','employee_types.full_name as emptype')->where('employees.status',1)->get();
        }
        $getemps = json_decode(json_encode($getemps),true);
        return $getemps;
    }

    public static function getemployee($empid){
        $getemp = Employee::join('employee_types','employee_types.short_name','=','employees.type')->select('employees.*','employee_types.full_name as emptype')->where('employees.id',$empid)->first();
        $getemp = json_decode(json_encode($getemp),true);
        return $getemp;
    }

    public static function getempTray($filedetails){
        $empids = DB::table('file_employees')->select('employee_id')->where('file_id',$filedetails['id'])->get();
        $empidsArr  = array_unique(array_flatten(json_decode(json_encode($empids),true)));
        $emps = Employee::wherein('id',$empidsArr)->select('email')->get();
        $emps = array_flatten(json_decode(json_encode($emps),true));
        return $emps;
    }

    public static function checkAccess(){
        $fullaccess ="false";
        $empdetails = Employee::where('id',Session::get('empSession')['id'])->first();
        if(!empty($empdetails)){
        if($empdetails->type =="admin"){
            $fullaccess = "true";
        }else{
            if($empdetails->is_access =="full"){
                $fullaccess = "true";
            }
        }
        }
        return $fullaccess;
    }

    public static function gettypes($type){
        $types = DB::table('employee_types')->where('file_action',$type)->get();
        $types = json_decode(json_encode($types),true);
        array_unshift($types,"");
        unset($types[0]);
        return $types;
    }

    public static function empdetail($fileid,$type){
        $emp = "Not Found";
        $details = FileEmployee::with('emp')->where(['file_id'=> $fileid,'type'=> $type])->first();
        if($details && $details->emp){
            $emp = $details->emp->name;
        }
        return $emp;
    }

    public static function getempldata($empid){
        
        $empl = Employee::where('id',$empid)->get();
        $empl = json_decode(json_encode($empl),true);
        
        if($empl){

            return true;
        }else{
            return false;
        }
    }
    
    public static function fileteamreport($teamid){
       $arr_id = array();
        $cli_id = array();
                $logdata = Employee::where('id',$teamid)->get();
                $logdata = json_decode(json_encode($logdata),true);

                foreach($logdata as $llog){
                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = Employee::setempsdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                           if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = Employee::setempsdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = Employee::setempsdata($empthree['id']);
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
                 $clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->get();
                $clientdata = json_decode(json_encode($clientdata),true);
                 
                foreach($clientdata as $cli){
                    array_push($cli_id, $cli['id']);
                }
               
                return $cli_id;
    }
    public static function setempsdata($empid){
         $empl = Employee::where('parent_id',$empid)->get();
         $empl = json_decode(json_encode($empl),true);
        
         if($empl){

            return true;
         }else{
            return false;
         }
    }

    public static function empsiddata(){
        $arr_id = array();
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
                    //dd($arr_id);


                    array_push($arr_id, $llog['id']);
                    $emponedata = Employee::where('parent_id',$llog['id'])->get();
                    $emponedata = json_decode(json_encode($emponedata),true);
                    if(!empty($emponedata)){
                    foreach($emponedata as $empone){
                        array_push($arr_id, $empone['id']);
                        $emps = Employee::setempsdata($empone['id']);
                        if($emps == true){
                            $emptwodata = Employee::where('parent_id',$empone['id'])->get();
                            $emptwodata = json_decode(json_encode($emptwodata),true);
                        if(!empty($emptwodata)){
                           foreach($emptwodata as $emptwo){
                            array_push($arr_id, $emptwo['id']);
                            $emply = Employee::setempsdata($emptwo['id']);
                             if($emply == true){
                                $empthreedata = Employee::where('parent_id',$emptwo['id'])->get();
                                $empthreedata = json_decode(json_encode($empthreedata),true);
                                if(!empty($empthreedata)){

                                    foreach($empthreedata as $empthree){
                                        array_push($arr_id, $empthree['id']);

                                        $empls = Employee::setempsdata($empthree['id']);
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
                 return $arr_id;
                    
    }
    
    public static function getTargetDetails($empid,$year,$month){
        $details = DB::table('employee_targets')->where(['employee_id'=>$empid,'year'=>$year,'month'=>$month])->first();
        $details = json_decode(json_encode($details),true);
        return $details;
    }

    public static function getteamEmps($empid){
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
        }
        return $empids;
    }
}
