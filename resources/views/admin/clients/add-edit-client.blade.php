@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\Employee;?>
<style>
table, th, td {
  border:1px solid black;
}
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>User's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('ClientController@clients') }}">Clients</a>
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
                        <form  role="form"  id="addEditClient" class="form-horizontal" method="post" @if(empty($clientdata)) action="{{ url('s/admin/add-edit-client') }}" @else  action="{{ url('s/admin/add-edit-client/'.$clientdata['id']) }}" @endif enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Client Reference No :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Client Reference No" name="client_ref_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['client_ref_no']))?$clientdata['client_ref_no']: '' }}"/>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Customer Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Customer Name" name="customer_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['customer_name']))?$clientdata['customer_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group" >
                                        <label class="col-md-3 control-label">Client Gender: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="client_gender">
                                                <?php $client_genderArr = array('Male','Female','Third Gender'); 
                                                sort($client_genderArr);?>
                                                <option value="">Select</option>
                                                @foreach($client_genderArr as $ckey => $client_gender)
                                                    <option value="{{$client_gender}}" @if(!empty($clientdata['client_gender']) && $clientdata['client_gender'] == $client_gender) selected @endif >{{ucwords($client_gender)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Mobile: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Mobile" name="mobile"  style="color:gray" class="form-control" value="{{(!empty($clientdata['mobile']))?$clientdata['mobile']: '' }}"/>
                                    </div>
                                </div>
                               
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Alternate Mobile : </label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Alternate Mobile" name="alt_mobile"  style="color:gray" class="form-control" value="{{(!empty($clientdata['alt_mobile']))?$clientdata['alt_mobile']: '' }}"/>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Current Residential Address :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Current Residential Address" name="current_res_address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['current_res_address']))?$clientdata['current_res_address']: '' }}"/>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Present Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Present Address" name="present_address"  style="color:gray" autocomplete="off" class="form-control pres_addr" value="{{(!empty($clientdata['present_address']))?$clientdata['present_address']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Near Landmark :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Near Landmark" name="near_landmark" style="color:gray" autocomplete="off" class="form-control pre_landmark" value="{{(!empty($clientdata['near_landmark']))?$clientdata['near_landmark']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select State :<span  style="color:red"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="state" class="selectbox selectpicker getState cli_state" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($clientdata['state']) && $clientdata['state'] == $state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select District :<span  style="color:red"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="city" class="selectbox cli_city" id="AppendCities"> 
                                            <option value="">Select</option>
                                            @if(!empty($clientdata['city']))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($clientdata['city']) && $clientdata['city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Pin Code :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Pincode"  name="ofc_pincode" style="color:gray" autocomplete="off" class="form-control pre_pincode" value="{{(!empty($clientdata['ofc_pincode']))?$clientdata['ofc_pincode']: '' }}"/>
                                    </div>
                            </div>
                              
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Present Address and Permanent Address are same:</label>
                                    <div class="col-md-5">
                                        @if(isset($clientdata['chk_addr']) && $clientdata['chk_addr'] == "1")
                                         <input type="checkbox"  name="chk_addr" style="color:gray; width: 30px;" autocomplete="off" class="form-control pre_addr_chk" value="" checked/>
                                         
                                        @else
                                        <input type="checkbox"  name="chk_addr" style="color:gray; width: 30px;" autocomplete="off" class="form-control pre_addr_chk" value=""/>
                                        @endif
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Permanent Address Details:</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Permanent Address Details"  name="perm_addr" style="color:gray" autocomplete="off" class="form-control perman_addr" value="{{(!empty($clientdata['perm_addr']))?$clientdata['perm_addr']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Permanent Landmark :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Permanent Landmark" name="permanent_landmark" style="color:gray" autocomplete="off" class="form-control perm_landmark" value="{{(!empty($clientdata['permanent_landmark']))?$clientdata['permanent_landmark']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Permanent State :</label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="perm_state" class="selectbox getPermState cli_perm_state selectpicker" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($clientdata['perm_state']) && $clientdata['perm_state'] == $state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Permanent District :</label>
                                    <div class="col-md-5">
                                        <select name="perm_city" class="selectbox cli_perm_city" id="AppendPermCities"> 
                                            <option value="">Select</option>
                                            @if(!empty($clientdata['perm_city']))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($clientdata['perm_city']) && $clientdata['perm_city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Permanent Pin Code :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Permanent Pincode" name="perm_pincode" style="color:gray" autocomplete="off" class="form-control permt_pincode" value="{{(!empty($clientdata['perm_pincode']))?$clientdata['perm_pincode']: '' }}"/>
                                    </div>
                                </div>
                                <!-- <div class="form-group" >
                                        <label class="col-md-3 control-label">Permanent Address: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="permenant_address">
                                                <?php $presentaddrArr = array('Owned','Parental','Company Provided','Rented'); 
                                                sort($presentaddrArr);?>
                                                <option value="">Select</option>
                                                @foreach($presentaddrArr as $ckey => $presentaddr)
                                                    <option value="{{$presentaddr}}" @if(!empty($clientdata['permenant_address']) && $clientdata['permenant_address'] == $presentaddr) selected @endif >{{ucwords($presentaddr)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No Of Years At Current City :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="No Of Years At Current City" name="current_city_years" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['current_city_years']))?$clientdata['current_city_years']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No Of Years In Curr Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="No Of Years In Current Address" name="current_address_years" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['current_address_years']))?$clientdata['current_address_years']: '' }}"/>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Highest Qualification :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Qualification" name="qualification" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['qualification']))?$clientdata['qualification']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Institute Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Institute Name" name="institute_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['institute_name']))?$clientdata['institute_name']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Year Of Passing :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Year Of Passing" name="year_of_passing" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['year_of_passing']))?$clientdata['year_of_passing']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Email Personal :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Email Personal" name="email_personal" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['email_personal']))?$clientdata['email_personal']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Email Official :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Email Office" name="email_ofc" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['email_ofc']))?$clientdata['email_ofc']: '' }}"/>
                                    </div>
                            </div>
                             @if(!empty($clientdata) && $clientdata['pan_status'] =="yes")
                              <input type="hidden" name="pan_status" value="{{$clientdata['pan_status']}}">
                                @else
                                    <div class="form-group" id="SelectAppender">
                                        <label class="col-md-3 control-label">Pan Status: <span  style="color:red;"> *</span></label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="pan_status" id="getPanStatus" required>
                                                <?php $panArr = array('no'=>'Applied For','yes'=>'I have Pan Number'); ?>
                                                <option value="">Select</option>
                                                @foreach($panArr as $pkey => $panstatus)
                                                    <option value="{{$pkey}}" @if(!empty($clientdata['pan_status']) && $clientdata['pan_status'] == $pkey) selected @endif >{{$panstatus}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                @endif
                                <div id="AppenderPan">
                                    <div class="form-group collapse" id="AppendPan">
                                    </div>
                                </div>

                                @if(!empty($clientdata) && $clientdata['pan_status'] =="yes")
                                    <div class="form-group ">
                                        <label class="col-md-3 control-label">Individual Pan: </label>
                                        <div class="col-md-5">
                                            <input type="text"   name ="pan"autocomplete="off" placeholder="PAN" class="form-control" style="color:gray"   value="{{(!empty($clientdata['pan']))?$clientdata['pan']: '' }}" readonly />
                                            
                                        </div>
                                    </div>
                               
                                @endif
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Aadhaar No:</label>
                                    <div class="col-md-5">
                                        <input type="text" id="ad_value" autocomplete="off" placeholder="Adhaar No" class="form-control" style="color:gray" @if(empty($clientdata)) name="adhar_no" @else  value="{{(!empty($clientdata['adhar_no']))?$clientdata['adhar_no']: '' }}"  @endif  />
                                        <span class="adh_err" style="display: none;">This Adhaar Number already exists!!</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Customer DOB :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        
                                        <input type="date"  class="form-control" placeholder="Select DOB"   name="dob" value="{{(!empty($clientdata['dob']))?$clientdata['dob']: '' }}" autocomplete="off" />
                                       <!--  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                        
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Mother's Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Mother Name" name="mother_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['mother_name']))?$clientdata['mother_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Father's Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Father Name" name="father_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['father_name']))?$clientdata['father_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group" >
                                        <label class="col-md-3 control-label">Marital Status: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="marital_status" id="mar_status">
                                                <?php $marital_statusArr = array('Single','Married'); ?>
                                                <option value="">Select</option>
                                                @foreach($marital_statusArr as $ckey => $marital)
                                                    <option value="{{$marital}}" @if(!empty($clientdata['marital_status']) && $clientdata['marital_status'] == $marital) selected @endif >{{ucwords($marital)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                 <div class="form-group" id="sp_name">
                                    <label class="col-md-3 control-label">Spouse Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Spouse Name" name="spouse_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['spouse_name']))?$clientdata['spouse_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group" id="sp_dob">
                                    <label class="col-md-3 control-label">Spouse DOB :</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date clientDatepicker">
                                        <input type="text" class="form-control" placeholder="Select Spouse DOB" name="spouse_dob" value="{{(!empty($clientdata['spouse_dob']))?$clientdata['spouse_dob']: '' }}" autocomplete="off" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Co-Applicant Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Name" name="co_applicant_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['co_applicant_name']))?$clientdata['co_applicant_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Co-Applicant Mail Id :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Co-Applicant Mail Id" name="coapp_mail" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['coapp_mail']))?$clientdata['coapp_mail']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Co-Applicant Mobile Number :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Co-Applicant Mobile Number" name="coapp_mob" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['coapp_mob']))?$clientdata['coapp_mob']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Co-Applicant DOB :</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date clientDatepicker">
                                        <input type="text" class="form-control" placeholder="Select DOB" name="co_applicant_dob" value="{{(!empty($clientdata['co_applicant_dob']))?$clientdata['co_applicant_dob']: '' }}" autocomplete="off" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name :
                                    </label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['company_name']))?$clientdata['company_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Identifications :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Identifications" name="company_identifications" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['company_identifications']))?$clientdata['company_identifications']: '' }}"/>
                                    </div>
                                </div>
                               <!--  <div class="form-group" id="SelectAppender">
                                    <label class="col-md-3 control-label">Ownership :</label>
                                        
                                        <div class="col-md-5">
                                            
                                            <input type="radio" class="form-check-input" name="company_details" value="Owner" <?php echo (isset($clientdata['company_details']) && $clientdata['company_details'] == "Owner") ? 'checked="checked"' : ''; ?>/>
                                            <label for="owner">Owner</label>

                                             
                                            <input type="radio" class="form-check-input" name="company_details" value="Partner" <?php echo (isset($clientdata['company_details']) && $clientdata['company_details'] == "Partner") ? 'checked="checked"' : ''; ?>/>
                                            <label for="partner">Partner</label>
                                            

                                           
                                            <input type="radio" class="form-check-input" name="company_details" value="Salaried" <?php echo (isset($clientdata['company_details']) && $clientdata['company_details'] == "Salaried") ? 'checked="checked"' : ''; ?>/>
                                            <label for="salaried">Salaried</label>
                                            
                                        </div>
                             </div> -->
                                <!-- <div class="form-group">
                                        <label class="col-md-3 control-label">Select Job Profile: </label>
                                        <div class="col-md-5">
                                        <select class="form-control" name="profile">
                                                <?php $profileArr = array('select (by default)','salaried','self employed','partnership','corporate','trust','professional','doctor','society'); sort($profileArr);?>
                                                <option value="">Select</option>
                                                @foreach($profileArr as $ckey => $profile)
                                                    <option value="{{$profile}}" @if(!empty($clientdata['profile']) && $clientdata['profile'] == $profile) selected @endif >{{ucwords($profile)}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    </div> -->
                            <div class="form-group">
                                    <label class="col-md-3 control-label">Total Work Experience :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Total Work Experience"  name="tot_work_experience" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['tot_work_experience']))?$clientdata['tot_work_experience']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">    Present Company Work Experience :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Present Company Work Experience" name="present_company_exp" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['present_company_exp']))?$clientdata['present_company_exp']: '' }}"/>
                                    </div>
                            </div>
                             <div class="form-group">
                                    <label class="col-md-3 control-label">    Office Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Office Address" name="ofc_address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['ofc_address']))?$clientdata['ofc_address']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">    Office Landmark :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Office Landmark" name="ofc_landmark" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['ofc_landmark']))?$clientdata['ofc_landmark']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-3 control-label">    Office Lanline Number :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Office Lanline Number" name="ofc_lanline_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['ofc_lanline_no']))?$clientdata['ofc_lanline_no']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Reference from:</label>
                                <div class="col-md-5">
                                <?php $ref_name = (isset($clientdata['reference_name']))?explode(',', $clientdata['reference_name']):[];
                                    $phone_num = (isset($clientdata['phone_number']))?explode(',', $clientdata['phone_number']):[];
                                     $addrs = (isset($clientdata['proper_address']))?explode(',', $clientdata['proper_address']):[];
                                ?>
                                <table style="width:100%" id="refernce_list">
                                 <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    @if(count($ref_name)<=1 && count($phone_num)<=1 && count($addrs)<=1)
                                    <th>Add row</th>@endif
                                 </tr>
                                 <tr>
                                    <td>
                                        @if(count($ref_name)!=0)
                                        @foreach($ref_name as $ref)
                                       <input type="text" name="reference_name[]" placeholder="Reference Name" style="color:gray" autocomplete="off" class="form-control" value="{{$ref}}">
                                        @endforeach
                                        @else
                                        <input type="text" name="reference_name[]" placeholder="Reference Name" style="color:gray" autocomplete="off" class="form-control">
                                        @endif
                                        </td>                                
                                    <td>
                                        @if(count($phone_num)!=0)
                                        @foreach($phone_num as $phone)
                                        <input type="text" name="phone_number[]" placeholder="Phone Number" style="color:gray" autocomplete="off" class="form-control" value="{{$phone}}">
                                        
                                        @endforeach
                                        @else
                                        <input type="text" name="phone_number[]" placeholder="Phone Number" style="color:gray" autocomplete="off" class="form-control">
                                        @endif
                                        </td>
                                    <td>
                                        @if(count($addrs)!=0)
                                        @foreach($addrs as $addr)
                                        <input type="text" name="proper_address[]" placeholder="Address" style="color:gray" autocomplete="off" class="form-control" value="{{$addr}}">
                                        @endforeach
                                        @else
                                        <input type="text" name="proper_address[]" placeholder="Address" style="color:gray" autocomplete="off" class="form-control">
                                        @endif
                                        </td>
                                    @if(count($ref_name)<=1 && count($phone_num)<=1 && count($addrs)<=1)
                                    <td><button><a href="javascript:void(0);" id="add_ref">
                                    Add Row
                                </a></button></td>
                                @endif
                                 </tr>
                              </table>
                          </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-3 control-label">Occupation: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="occupation" id="occupt">
                                                <?php $occupationArr = array('Salaried','Self Employed Businessman','Self Employed Professional'); sort($occupationArr);?>
                                                <option value="">Select</option>
                                                @foreach($occupationArr as $ckey => $occupation)
                                                    <option value="{{$occupation}}" @if(!empty($clientdata['occupation']) && $clientdata['occupation'] == $occupation) selected @endif >{{ucwords($occupation)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="prof">
                                        <label class="col-md-3 control-label">Profession: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="profession" id="profess">
                                                <?php $professionArr = array('Doctor','CA / CS','Consultant','Architect','Other');
                                                sort($professionArr); ?>
                                                <option value="">Select</option>
                                                @foreach($professionArr as $ckey => $profession)
                                                    <option value="{{$profession}}" @if(!empty($clientdata['profession']) && $clientdata['profession'] == $profession) selected @endif >{{ucwords($profession)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group" id="profess_other">
                                    <label class="col-md-3 control-label">Other Profession :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Other Profession" name="profession" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['profession']))?$clientdata['profession']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="comp">
                                        <label class="col-md-3 control-label">Type Of Company: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="company_type" id="comp_type">
                                                <?php $companytypeArr = array('Pvt. Ltd.','Partnership','Proprietor','Other'); sort($companytypeArr);?>
                                                <option value="">Select</option>
                                                @foreach($companytypeArr as $ckey => $companytype)
                                                    <option value="{{$companytype}}" @if(!empty($clientdata['company_type']) && $clientdata['company_type'] == $companytype) selected @endif >{{ucwords($companytype)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group" id="comp_type_other">
                                    <label class="col-md-3 control-label">Other Company Type :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Other Company Type" name="company_type" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['company_type']))?$clientdata['company_type']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="buis">
                                        <label class="col-md-3 control-label">Nature of Business: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="buisness_nature" id="buis_nat">
                                                <?php $buisnatureArr = array('Manufacturer','Service Provider','Trader / Distributor','Comm. Agent','Retailer','Other'); sort($buisnatureArr);?>
                                                <option value="">Select</option>
                                                @foreach($buisnatureArr as $ckey => $buisnature)
                                                    <option value="{{$buisnature}}" @if(!empty($clientdata['buisness_nature']) && $clientdata['buisness_nature'] == $buisnature) selected @endif >{{ucwords($buisnature)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group" id="buis_nat_other">
                                    <label class="col-md-3 control-label">Other Buisness Nature :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Other Buisness Nature" name="buisness_nature" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['buisness_nature']))?$clientdata['buisness_nature']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="comptyp">
                                        <label class="col-md-3 control-label">Type of Company for Salaried: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="company_type_salaried" id="comp_salaried">
                                                <?php $compsalariedArr = array('Pvt. Ltd.','Partnership','Proprietor','Public Ltd.','Retailers','PSU','Govt.','MNC','Other'); sort($compsalariedArr);?>
                                                <option value="">Select</option>
                                                @foreach($compsalariedArr as $ckey => $compsalaried)
                                                    <option value="{{$compsalaried}}" @if(!empty($clientdata['company_type_salaried']) && $clientdata['company_type_salaried'] == $compsalaried) selected @endif >{{ucwords($compsalaried)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group" id="comp_salaried_other">
                                    <label class="col-md-3 control-label">Other Company Type Salaried :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Other Company Type Salaried" name="company_type_salaried" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['company_type_salaried']))?$clientdata['company_type_salaried']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="inds">
                                        <label class="col-md-3 control-label">Industry Type: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="industry_type" id="ind_type">
                                                <?php $indtypeArr = array('Automobile','Agriculture Based','Banking','BPO','Capital Goods','Telecom
','IT','Retail','Real Estate','Consumer Durables','FMCG','NBFC','Marketing / Adv.','Pharma','Media','Other'); sort($indtypeArr);?>
                                                <option value="">Select</option>
                                                @foreach($indtypeArr as $ckey => $indtype)
                                                    <option value="{{$indtype}}" @if(!empty($clientdata['industry_type']) && $clientdata['industry_type'] == $indtype) selected @endif >{{ucwords($indtype)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="form-group" id="ind_types">
                                    <label class="col-md-3 control-label">Other Industry Type :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Other Industry Type" name="industry_type" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['industry_type']))?$clientdata['industry_type']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="mon_salar">
                                    <label class="col-md-3 control-label">Monthly Salary :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Monthly Salary" name="monthly_salary" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['monthly_salary']))?$clientdata['monthly_salary']: '' }}"/>
                                    </div>
                            </div>
                            <div class="form-group" id="ann_turn">
                                    <label class="col-md-3 control-label">Annual Turnover :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Annual Turnover" name="annual_turnover" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['annual_turnover']))?$clientdata['annual_turnover']: '' }}"/>
                                    </div>
                            </div>
                             <div class="form-group">
                                    <label class="col-md-3 control-label">Relative :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Relative" name="relative" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['relative']))?$clientdata['relative']: '' }}"/>
                                    </div>
                            </div>

                            <div class="form-group">
                                        <label class="col-md-3 control-label">Lead Origin:<span  style="color:red"> *</span> </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="lead_origin" id="origin_data" <?php echo (!empty($clientdata['lead_origin']) && $clientdata['lead_origin'] != '')?'disabled':''; ?> />
                                                <?php $leadoriginArr = array('channel partner','local'); ?>
                                                <option value="">Select</option>
                                                @foreach($leadoriginArr as $ckey => $leadorigin)
                                                    <option value="{{$leadorigin}}" @if(!empty($clientdata['lead_origin']) && $clientdata['lead_origin'] == $leadorigin) selected  @endif  >{{ucwords($leadorigin)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if(!empty($clientdata) && $clientdata['lead_origin'] =="channel partner")
                                    <div class="form-group">
                                    <label class="col-md-3 control-label">Select Channel Partner :<span  style="color:red"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <?php
                                         $chan_id = (isset($clientdata['channel_partner']))?$clientdata['channel_partner']:'';
                                         $chp = DB::table('channel_partners')->where('id',$chan_id)->first();
                                         $chp = json_decode(json_encode($chp),true);

                                        ?>
                                        <select name="channel_partner" class="selectbox" disabled > 
                                            <option value="">Select</option>
                                            @foreach($channelpartners as $channel)
                                                <option  value="{{ $channel['id'] }}" @if(!empty($chp) && $chp['name'] == $channel['name']) selected @endif>{{ $channel['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                  <div class="form-group" id="chan_part">
                                    <label class="col-md-3 control-label">Select Channel Partner :<span  style="color:red"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <?php
                                         $chan_id = (isset($clientdata['channel_partner']))?$clientdata['channel_partner']:'';
                                         $chp = DB::table('channel_partners')->where('id',$chan_id)->first();
                                         $chp = json_decode(json_encode($chp),true);

                                        ?>
                                        <select name="channel_partner" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%"> 
                                        	<option value="">Select</option>
                                            @foreach($channelpartners as $channel)
                                                <option  value="{{ $channel['id'] }}" @if(!empty($chp) && $chp['name'] == $channel['name']) selected @endif>{{ $channel['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($clientdata) && $clientdata['lead_origin'] =="local")
                                 <div class="form-group">
                                    <label class="col-md-3 control-label">Select Telecaller :<span  style="color:red;"> *</span></label>

                                    <div class="col-md-5">
                                       <?php 

                                          $tel_id = (isset($clientdata['tel_name']))?$clientdata['tel_name']:'';
                                          $telname = Employee::where('id',$tel_id)->first();
                                          $telname = json_decode(json_encode($telname),true);
                                          
                                       ?>

                                        <select name="tel_name" class="selectbox" disabled > 
                                            <option value="">Select</option>
                                           
                                            @foreach($getteldata as $gettel)
                                                @if($gettel['status'] == 1)
                                                <option value="{{ $gettel['id'] }}" @if(!empty($telname) && $telname['name'] == $gettel['name']) selected @endif>{{ ucwords($gettel['name']) }}</option>
                                                @endif
                                            @endforeach
                                            
                                        </select>

                                    </div>
                                </div>
                                @else
                                  <div class="form-group" id="telc_list">
                                    <label class="col-md-3 control-label">Select Telecaller :<span  style="color:red;"> *</span></label>

                                    <div class="col-md-5 jquerySelectbox">
                                       <?php 

                                          $tel_id = (isset($clientdata['tel_name']))?$clientdata['tel_name']:'';
                                          $telname = Employee::where('id',$tel_id)->first();
                                          $telname = json_decode(json_encode($telname),true);
                                          
                                       ?>

                                        <select name="tel_name" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                           
                                            @foreach($getteldata as $gettel)
                                                @if($gettel['status'] == 1)
                                                <option value="{{ $gettel['id'] }}" @if(!empty($telname) && $telname['name'] == $gettel['name']) selected @endif>{{ ucwords($gettel['name']) }}</option>
                                                @endif
                                            @endforeach
                                            
                                        </select>

                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                        <label class="col-md-3 control-label">Select Status: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="client_status">
                                                <?php $client_statusArr = array('old','fresh'); sort($client_statusArr);?>
                                                <option value="">Select</option>
                                                @foreach($client_statusArr as $ckey => $client_status)
                                                    <option value="{{$client_status}}" @if(!empty($clientdata['client_status']) && $clientdata['client_status'] == $client_status) selected @endif >{{ucwords($client_status)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label class="col-md-3 control-label">Client Photo:</label>
                                            <div class="col-md-4">
                                        <div data-provides="fileinput" class="fileinput fileinput-new">
                                            <div style="" class="fileinput-new thumbnail">
                                             
                                             <?php if(!empty($clientdata['client_pic'])){
                                                $path = "images/ClientPhoto/".$clientdata['client_pic']; 
                                            if(file_exists($path)) { ?>
                                                <img style="height:100px;widtyh:100px;" class="img-responsive"  src="{{ asset('images/ClientPhoto/'.$clientdata['client_pic'])}}">
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
                                                    <input type="file" class="form-control" name="client_pic[]">
                                                    </span>
                                                    <a data-dismiss="fileinput" class="btn default fileinput-exists" href="#">
                                                    Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                
                                
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Permanent Mobile :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Permanent Mobile" name="permanent_mobile" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['permanent_mobile']))?$clientdata['permanent_mobile']: '' }}"/>
                                    </div>
                                </div> -->
                                
                                
                                
                                

                                
                            
                            
                            
                            
                           
                            
                            <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Bank Details :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Bank Details" name="bank_details" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['bank_details']))?$clientdata['bank_details']: '' }}"/>
                                    </div>
                            </div> -->

                            
                            
                            
                            
                           
                            
                            
                            
                                
                                <!-- <div class="form-group" id="SelectAppender">
                                        <label class="col-md-3 control-label">Client Category: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="category">
                                                <?php $categoryArr = array('hni','general'); ?>
                                                <option value="">Select</option>
                                                @foreach($categoryArr as $ckey => $category)
                                                    <option value="{{$category}}" @if(!empty($clientdata['category']) && $clientdata['category'] == $category) selected @endif >{{ucwords($category)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                     
                                    
                                    
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Applicant Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($clientdata['name']))?$clientdata['name']: '' }}"/>
                                    </div>
                                </div> -->
                                
                                
                                
                               <!--  <div class="form-group ">
                                    <label class="col-md-3 control-label">Email: </label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Email" class="form-control" style="color:gray" name="email" value="{{(!empty($clientdata['email']))?$clientdata['email']: '' }}"  />
                                    </div>
                                </div> -->
                                
                                
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Select Sales Officer :</label>
                                    <div class="col-md-5">
                                        <select name="sale_officer" class="selectbox">
                                            <option value="">Select</option>
                                            @foreach($getTeamLevels as $key => $level)
                                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); ?>
                                                <option value="{{$level['id']}}" @if(!empty($clientdata['sale_officer']) && $clientdata['sale_officer'] == $level['id']) selected @endif>&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                                                @foreach($level['getemps'] as $skey => $sublevel1)
                                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                                                    <option value="{{$sublevel1['id']}}"@if(!empty($clientdata['sale_officer']) && $clientdata['sale_officer'] == $sublevel1['id']) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - {{$getEmpType->full_name}}</option>
                                                    @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                                                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                                                        <option value="{{$sublevel2['id']}}" @if(!empty($clientdata['sale_officer']) && $clientdata['sale_officer'] == $sublevel2['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  -->      
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" type="submit" id="update_client">Submit</button>
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
	 $('#update_client').removeClass('disabled');  
}
</script>
@stop
