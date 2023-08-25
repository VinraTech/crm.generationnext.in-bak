<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Closure;
use Session;
use App\EmployeeRole;
use App\Module;
class Employee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty(Session::has('empSession'))){
            return redirect()->action('AdminController@login')->with('flash_message_error', 'Please Login');
        }else{
            if((Session::get('empSession')['type']!="admin" && Session::get('empSession')['is_access']!="full")){
                $currentUrl = Route::getFacadeRoot()->current()->uri();

                $moduleDetail = Module::orwhere('view_route',$currentUrl)->orwhere('edit_route',$currentUrl)->orwhere('delete_route',$currentUrl)->first();
                $moduleDetail = json_decode(json_encode($moduleDetail),true);
                if(!empty($moduleDetail)){
                $getEmployeeRoledetail = EmployeeRole::where(['emp_id'=>Session::get('empSession')['id'],'module_id' => $moduleDetail['id']])->first();
                $getEmployeeRoledetail = json_decode(json_encode($getEmployeeRoledetail),true);
                }
                /*if($currentUrl == 's/admin/update-role/{id}'){
                    return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this functionality');
                }*/
                if(!empty($getEmployeeRoledetail) && !empty($moduleDetail)){
                    Session::put('empRoleDetails',$getEmployeeRoledetail);
                    Session::put('empModuleDetails',$moduleDetail);
                    if($currentUrl == $moduleDetail['view_route'] && $getEmployeeRoledetail['view_access'] == 0){
                        return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this page');
                    }
                    if($currentUrl == $moduleDetail['edit_route'] && $getEmployeeRoledetail['edit_access'] == 0){
                        return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this page');
                    }
                    if($currentUrl == $moduleDetail['delete_route'] && $getEmployeeRoledetail['delete_access'] == 0){
                        return redirect()->action('AdminController@dashboard')->with('flash_message_error','You have no right to access this page');
                    }
                }
            }
        }
        return $next($request);
    }
}
