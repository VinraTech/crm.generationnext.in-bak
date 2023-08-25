<?php
use App\Employee;
?>
@extends('layouts.adminLayout.backendLayout')
@section('content')
<style>
table, th, td {
  border:1px solid black;
}
</style>
<?php use App\Bank;
$banks = Bank::banks();
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Employee's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('EmployeeController@employees') }}">Employees</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        
                        <form  role="form" class="form-horizontal" method="post" @if(empty($employeedata)) id="addEditEmployee" action="{{ url('s/admin/add-edit-employee') }}" @else  id="editEmployee" action="{{ url('s/admin/add-edit-employee/'.$employeedata['id']) }}" @endif enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Full Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($employeedata['name']))?$employeedata['name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select State :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="state"  class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($employeedata['state']) && $employeedata['state'] == $state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select City :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="city" class="selectbox form-control" id="AppendCities"> 
                                            <option value="">Select</option>
                                            @if(!empty($employeedata['city']))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($employeedata['city']) && $employeedata['city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                               <!--  <div class="form-group">
                                    <label class="col-md-3 control-label">Designation :</label>

                                    <div class="col-md-5">
                                        <select name="type" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($getemptypes as $emptype)
                                                <option value="{{ $emptype['short_name'] }}" @if(!empty($employeedata['type']) && $employeedata['type'] == $emptype['short_name']) selected @endif>{{ ucwords($emptype['full_name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Select Image: </label>
                                    <div class="col-md-4">
                                        <div data-provides="fileinput" class="fileinput fileinput-new">
                                            <div style="" class="fileinput-new thumbnail">
                                            <?php if(!empty($employeedata['image'])){
                                                $path = "images/AdminImages/".$employeedata['image']; 
                                            if(file_exists($path)) { ?>
                                                <img style="height:100px;widtyh:100px;" class="img-responsive"  src="{{ asset('images/AdminImages/'.$employeedata['image'])}}">
                                            <?php }else{?>
                                                    <img style="height:100px;widtyh:100px;" class="img-responsive"  src="{{ asset('images/default.png') }}">
                                            <?php } } else { ?>
                                            <img style="height:100px;widtyh:100px;" class="img-responsive"  src="{{ asset('images/default.png') }}">
                                            <?php } ?>
                                        </div>
                                            <div style="max-width: 200px; max-height: 150px; line-height: 10px;" class="fileinput-preview fileinput-exists thumbnail">
                                            </div>
                                            <div>
                                                <div class="form-group">
                                                    <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                    Select Image </span>
                                                    <span class="fileinput-exists">
                                                    Select Image </span>
                                                    <input type="file" id="Image" name="image">
                                                    </span>
                                                    <a data-dismiss="fileinput" class="btn default fileinput-exists" href="#">
                                                    Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // echo "<pre>";
                                // // print_r($getTeamLevels);
                                // // exit;
                                // foreach($getTeamLevels as $key => $level)
                                // {
                                //     $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                                //     print_r($getEmpType);
                                //     foreach($level['getemps'] as $skey => $sublevel1)
                                //     {
                                //         foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                                //         {
                                //             $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); 
                                //             print_r($getEmpType);
                                //             $getdetails = Employee::with(['getemps'=>function($query){

                                //                 $query->with('getemps');

                                //             }])->where('id',$sublevel2['id'])->first();

                                //             $getdetails = json_decode(json_encode($getdetails),true);
                                //             foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
                                //             {
                                //                 $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); 
                                //                 print_r($getEmpType);
                                //             }
                                //             echo "<pre>";
                                //             print_r($getdetails);
                                //             exit;
                                //         }
                                //     }
                                // }
                                ?>

    <div class="form-group">
        <label class="col-md-3 control-label">Select Team Level  :<span  style="color:red;"> *</span></label>
        <div class="col-md-5 jquerySelectbox">
            <select name="parent_id" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" onchange="TeamLevelOnChange()" id="parent_id">

                <option data-level='0' value="">Select</option>
                <option data-level='1' value="ROOT" @if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == "ROOT") selected @endif>ROOT</option>


                @foreach($getTeamLevels as $key => $level)
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option data-level="{{  $EngLevel[$level['id']] }}" value="{{$level['id']}}" @if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']) selected @endif>&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                    @foreach($level['getemps'] as $skey => $sublevel1)
                        <?php 
						$getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first();
                          
						?>
                        <option data-level="{{  $EngLevel[$sublevel1['id']] }}" value="{{$sublevel1['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - @if(isset($getEmpType->full_name)){{$getEmpType->full_name}}@endif</option>
                        @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                            <?php 
							$getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); 
							
							?>
                            <option data-level="{{  $EngLevel[$sublevel2['id']] }}" value="{{$sublevel2['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            @foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
                                <?php 
								$getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first();
                              
								?>
                                <option data-level="{{  $EngLevel[$sublevel3['id']] }}"  value="{{$sublevel3['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;{{$sublevel3['name']}} - {{$getEmpType->full_name}} </option>




                                <?php
                                 $getdetails = Employee::with(['getemps'=>function($query){

                                     $query->with('getemps');

                                 }])->where('id',$sublevel3['id'])->first();

                                 $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                @foreach($getdetails['getemps'] as $sssskey=> $sublevel4)
                                    <?php  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); 
									
									?>
                                    <option data-level="{{  $EngLevel[$sublevel4['id']] }}" value="{{$sublevel4['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel4['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;{{$sublevel4['name']}} - {{$getEmpType->full_name}}</option>
                                @endforeach  





                            @endforeach


                        @endforeach
                    @endforeach
                @endforeach
            </select>
        
		</div>
    </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Product :</label>
                                    <div class="col-md-5">
                                        <select name="products[]" class="selectpicker" multiple data-width="100%" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option selected="selected">    All Products
                                            </option>
                                            @foreach($getproducts as $product)
                                            
                                                <?php if(!empty($empPids) && in_array($product['id'],$empPids)) {
                                                    $selected = "selected";
                                                }else{
                                                    $selected="";
                                                }
                                                ?>
                                                
                                                <option value="{{ $product['id'] }}" {{$selected}}>{{ ucwords($product['name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Email: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" name="email" autocomplete="off" placeholder="Email" class="form-control" style="color:gray" value="{{(!empty($employeedata['email']))?$employeedata['email']: '' }}"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Employee Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($employeedata['address']))?$employeedata['address']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">DOB :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                       
                                        <input type="date" class="form-control" placeholder="Select DOB" name="dob" value="{{(!empty($employeedata['dob']))?$employeedata['dob']: '' }}" autocomplete="off" required/>
                                       <!--  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date Of Joining :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        
                                        <input type="date" class="form-control" placeholder="Select Date Of Joining" name="doj" value="{{(!empty($employeedata['doj']))?$employeedata['doj']: '' }}" autocomplete="off" required/>
                                        <!-- <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                        
                                    </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Pan Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pan" autocomplete="off" placeholder="PAN" class="form-control" style="color:gray"   value="{{(!empty($employeedata['pan']))?$employeedata['pan']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Aadhaar Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="adhaar_no" autocomplete="off" placeholder="Adhaar Number" class="form-control" style="color:gray"   value="{{(!empty($employeedata['adhaar_no']))?$employeedata['adhaar_no']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Designation :<span  style="color:red;"> *</span></label>

                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="type" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" required> 
                                            <option value="">Select</option>
                                            @foreach($getemptypes as $emptype)
                                                <option value="{{ $emptype['short_name'] }}" @if(!empty($employeedata['type']) && $employeedata['type'] == $emptype['short_name']) selected @endif>{{ ucwords($emptype['full_name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Select Designation :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="designation_name" class="selectbox"> 
                                            <option value="">Select</option>
                                           
                                            
                                                @foreach($designationdetails as $designation)
                                                    <option value="{{ $designation['designation_name'] }}" @if(!empty($employeedata['designation_name']) && $employeedata['designation_name'] == $designation['designation_name']) selected @endif>{{ $designation['designation_name'] }}</option>
                                                @endforeach
                                           
                                        </select>
                                    </div>
                                </div> 
                         -->
                                <div class="form-group">
                                <label class="col-md-3 control-label">Bank Details form:<span  style="color:red;"> *</span></label>
                                <div class="col-md-5">
                                <?php $b_name = (isset($employeedata['bank_name']))?explode(',', $employeedata['bank_name']):[];
                                    $acc = (isset($employeedata['account_no']))?explode(',', $employeedata['account_no']):[];
                                     $ifcode = (isset($employeedata['ifsc_code']))?explode(',', $employeedata['ifsc_code']):[];
                                ?>
                                <table style="width:100%" id="bankdetails_list">
                                 <thead>
                                 <tr>
                                    <th data-bank>Bank Name</th>
                                    <th data-account>Account Number</th>
                                    <th data-ifsc>IFSC Code</th>
                                    @if(count($b_name)<=1 && count($acc)<=1 && count($ifcode)<=1)
                                    <th>Add row</th>@endif
                                 </tr>
                                 </thead>
                                 <tbody>
                                 <tr data-row>
                                    <td data-bank>
                                        @if(count($b_name)!=0)
                                        @foreach($b_name as $ban_name)
                                       <select id="" required class="form-control" name="bank_name[]">
                                            <option value="" selected="selected">Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank['short_name']}}" @if($bank['short_name'] == $ban_name) selected="" @endif>{{$bank['short_name']}}</option>
                                            @endforeach
                                        </select>
                                        @endforeach
                                        @else
                                        <select id="" required class="form-control" name="bank_name[]">
                                        <option value="" selected="selected">Select Bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank['short_name']}}">{{$bank['short_name']}}</option>
                                        @endforeach
                                        </select>
                                        @endif
                                        </td>                                
                                    <td data-account>
                                        @if(count($acc)!=0)
                                        @foreach($acc as $accno)
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control" value="{{$accno}}">
                                        
                                        @endforeach
                                        @else
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control">
                                        @endif
                                        </td>
                                    <td data-ifsc>
                                        @if(count($ifcode)!=0)
                                        @foreach($ifcode as $icode)
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code" value="{{$icode}}"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        @endforeach
                                        @else
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control  ifs_code"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        @endif
                                        </td>
                                    @if(count($b_name)<=1 && count($acc)<=1 && count($ifcode)<=1)
                                    <td><button><a href="javascript:void(0);" id="add_details">
                                    Add Row
                                </a></button></td>
                                @endif
                                 </tr>
                                 </tbody>
                              </table>
                              <span class="addebtn_errs" style="display: none;">Cannot add more than one row!!</span>
                          </div>
                            </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Monthly Salary :<span  style="color:red;"> *</span> </label>
                                        <div class="col-md-5">
                                            <input type="text" name="monthly_salary" autocomplete="off" placeholder="Monthly Salary" class="form-control" style="color:gray"   value="{{(!empty($employeedata['monthly_salary']))?$employeedata['monthly_salary']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">PCC : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pcc" autocomplete="off" placeholder="PCC" class="form-control" style="color:gray"   value="{{(!empty($employeedata['pcc']))?$employeedata['pcc']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Blood Group : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="blood_group" autocomplete="off" placeholder="Blood Group" class="form-control" style="color:gray"   value="{{(!empty($employeedata['blood_group']))?$employeedata['blood_group']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Emergency Phone Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="emergency_number" autocomplete="off" placeholder="Emergency Phone Number" class="form-control" style="color:gray"   value="{{(!empty($employeedata['emergency_number']))?$employeedata['emergency_number']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Medical Status : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="medical_status" autocomplete="off" placeholder="Medical Status" class="form-control" style="color:gray"   value="{{(!empty($employeedata['medical_status']))?$employeedata['medical_status']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Mobile: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Mobile" name="mobile"  style="color:gray" class="form-control" value="{{(!empty($employeedata['mobile']))?$employeedata['mobile']: '' }}"/>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Refer to Department :<br>(While Allocating Lead) </label>
                                    <div class="col-md-5">
                                        <?php 
                                                $yesselected="";
                                                $noselected ="selected";   
                                            ?>
                                        @if(!empty($employeedata['refer_to_dept']))
                                            @if($employeedata['refer_to_dept'] =="yes")
                                                <?php 
                                                $yesselected="selected";
                                                $noselected ="";   
                                            ?>
                                            @else
                                                <?php 
                                                $yesselected="";
                                                $noselected ="selected";   
                                            ?>
                                            @endif
                                         @endif
                                        <select name="refer_to_dept" class="selectbox"> 
                                            <option value="yes" {{$yesselected}}>Yes</option>
                                            <option value="no" {{$noselected}}>No</option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">System  Access:</label>
                                    <div class="col-md-5">
                                        <?php 
                                            $limitedselected="selected";
                                            $fullselected ="";
                                            $hierarchyselected ="";   
                                            ?>
                                        @if(!empty($employeedata['is_access']))
                                            @if($employeedata['is_access'] =="full")
                                                <?php 
                                                $fullselected="selected";
                                                $limitedselected = "";
                                                $hierarchyselected = "";
                                            ?>
                                            @elseif($employeedata['is_access'] =="hierarchy")
                                                <?php 
                                                $fullselected="";
                                                $limitedselected = "";
                                                $hierarchyselected = "selected";  
                                            ?>
                                            @else
                                                <?php 
                                                $fullselected="";
                                                $limitedselected = "selected";
                                                $hierarchyselected = "";  
                                            ?>
                                            @endif
                                         @endif
                                        <select name="is_access" class="selectbox"> 
                                            <option value="limited" {{$limitedselected}}>Limited (Team only)</option>
                                            <option value="full" {{$fullselected}}>Full System Access</option>
                                            <option value="hierarchy" {{$hierarchyselected}}>Hierarchy</option>
                                        </select>
                                    </div>
                                </div>
                                @if(empty($employeedata))
                                    <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password"  style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                  <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password" value="{{$employeedata['decrypt_password']}}" style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif        
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" id="update_employee" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("input").change(function(){ 
	 submitButtonEnable();
});
$('select').on('change',(event) => {
     submitButtonEnable();
 });
function submitButtonEnable(){ 
	 $(':input[type="submit"]').prop('disabled', false);	
	 $('#update_employee').removeClass('disabled');  
}

function TeamLevelOnChange(){
	var level = $('#parent_id').find('option:selected').attr('data-level');
	
	 if(level > 4){
		alert("You can't add "+level+" th level");
		$('#parent_id option').attr('selected', false);
		return false;
	} 
}
</script>
@stop