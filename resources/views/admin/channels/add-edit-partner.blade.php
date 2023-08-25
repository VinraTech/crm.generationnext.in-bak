@extends('layouts.adminLayout.backendLayout')
@section('content')
<style>
table, th, td {
  border:1px solid black;
}
</style>
<?php 
use App\Bank;
use App\Employee;
$banks = Bank::banks();
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Channel Partner's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('ChannelPartnerController@channelpartners') }}">Channel Partners</a>
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
                        <form  role="form"  id="addEditChannelPartner" class="form-horizontal" method="post" @if(empty($partnerdata)) action="{{ url('s/admin/add-edit-partner') }}" @else  action="{{ url('s/admin/add-edit-partner/'.$partnerdata['id']) }}" @endif  enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body"> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Full Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($partnerdata['name']))?$partnerdata['name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($partnerdata['company_name']))?$partnerdata['company_name']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select State :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="state" class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($partnerdata['state']) && $partnerdata['state'] == $state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select City :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="city" class="selectbox" id="AppendCities"> 
                                            <option value="">Select</option>
                                            @if(!empty($partnerdata['city']))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($partnerdata['city']) && $partnerdata['city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Channel Relation :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="emp_id" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" onchange="TeamLevelOnChange()" id="emp_id">
                                            <option data-level='0' value="">Select</option>
                                            @foreach($getTeams as $key => $level)
                                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); ?>
                                                <option data-level='1' value="{{$level['id']}}" @if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $level['id']) selected @endif>&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                                                @foreach($level['getemps'] as $skey => $sublevel1)
                                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                                                    <option data-level='2' value="{{$sublevel1['id']}}"@if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $sublevel1['id']) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - {{$getEmpType->full_name}}</option>
                                                    @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                                                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                                                        <option data-level='3' value="{{$sublevel2['id']}}" @if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $sublevel2['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>
                                                         <!-- start -->
														 <?php
															$getdetails = Employee::with(['getemps'=>function($query){

																$query->with('getemps');

															}])->where('id',$sublevel2['id'])->first();

															$getdetails = json_decode(json_encode($getdetails),true);
															?>
														 @foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
																<?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
																<option data-level='4' value="{{$sublevel3['id']}}"@if(!empty($partnerdata['parent_id']) && $partnerdata['parent_id'] == $sublevel2['id'] && $sublevel3['id'] == $partnerdata['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;{{$sublevel3['name']}} - {{$getEmpType->full_name}}</option>




																<?php
																 $getdetails = Employee::with(['getemps'=>function($query){

																	 $query->with('getemps');

																 }])->where('id',$sublevel3['id'])->first();

																 $getdetails = json_decode(json_encode($getdetails),true);
																?>
																@foreach($getdetails['getemps'] as $ssskey=> $sublevel4)
																	<?php  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
																	<option data-level='5' value="{{$sublevel4['id']}}"@if(!empty($partnerdata['parent_id']) && $partnerdata['parent_id'] == $sublevel3['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;{{$sublevel4['name']}} - {{$getEmpType->full_name}}</option>
																@endforeach 

															@endforeach
                                                         <!-- end -->
													@endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Type :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <?php $typeArray = array('Connector','DSA'); ?>
                                        <select name="type" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($typeArray as $key => $type)
                                                <option value="{{$type}}" @if(!empty($partnerdata['type']) && $partnerdata['type'] == $type) selected @endif>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Email: </label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Email"  style="color:gray" class="form-control" @if(!empty($partnerdata['email']))   value="{{(!empty($partnerdata['email']))?$partnerdata['email']: '' }}" readonly  @else name="email"  @endif/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($partnerdata['address']))?$partnerdata['address']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">DOB :</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date dobDatepicker">
                                        <input type="text" class="form-control" placeholder="Select DOB" name="dob" value="{{(!empty($partnerdata['dob']))?$partnerdata['dob']: '' }}" autocomplete="off" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Mobile: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Mobile" name="mobile"  style="color:gray" class="form-control" value="{{(!empty($partnerdata['mobile']))?$partnerdata['mobile']: '' }}"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Pan Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pan" autocomplete="off" placeholder="PAN" class="form-control" style="color:gray"   value="{{(!empty($partnerdata['pan']))?$partnerdata['pan']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Aadhaar Card Number :<span  style="color:red;"> *</span> </label>
                                        <div class="col-md-5">
                                            <input type="text" name="adhaar_no" autocomplete="off" placeholder="Adhaar Number" class="form-control" style="color:gray"   value="{{(!empty($partnerdata['adhaar_no']))?$partnerdata['adhaar_no']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">GST Number :</label>
                                        <div class="col-md-5">
                                            <input type="text" name="gst_no" autocomplete="off" placeholder="GST Number" class="form-control" style="color:gray"   value="{{(!empty($partnerdata['gst_no']))?$partnerdata['gst_no']: '' }}" />
                                        </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 control-label">Bank Details form:<span  style="color:red;"> *</span></label>
                                <div class="col-md-5">
                                <?php $chb_name = (isset($partnerdata['bank_name']))?explode(',', $partnerdata['bank_name']):[];
                                    $chacc = (isset($partnerdata['account_no']))?explode(',', $partnerdata['account_no']):[];
                                     $chifcode = (isset($partnerdata['ifsc_code']))?explode(',', $partnerdata['ifsc_code']):[];
                                ?>
                                
                                <table style="width:100%" id="channelbankdetails_list">
                                <thead>
                                 <tr>
                                    <th data-bank>Bank Name</th>
                                    <th data-account>Account Number</th>
                                    <th data-ifsc>IFSC Code</th>
                                    @if(count($chb_name)<=1 && count($chacc)<=1 && count($chifcode)<=1)
                                    <th>Add row</th>@endif
                                 </tr>
                                </thead>
                                <tbody>
                                 <tr data-row>
                                    <td data-bank>
                                        @if(count($chb_name)!=0)
                                        @foreach($chb_name as $chban_name)
                                       <select id="" required class="form-control" name="bank_name[]">
                                            <option value="" selected="selected">Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank['short_name']}}" @if($bank['short_name'] == $chban_name) selected="" @endif>{{$bank['short_name']}}</option>
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
                                        @if(count($chacc)!=0)
                                        @foreach($chacc as $chaccno)
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control" value="{{$chaccno}}">
                                        
                                        @endforeach
                                        @else
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control">
                                        @endif
                                        </td>
                                    <td data-ifsc>
                                        @if(count($chifcode)!=0)
                                        @foreach($chifcode as $chicode)
                                        <input type="text" name="ifsc_code[]"  placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code" value="{{$chicode}}"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        @endforeach
                                        @else
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        @endif
                                        </td>
                                    @if(count($chb_name)<=1 && count($chacc)<=1 && count($chifcode)<=1)
                                    <td><button><a href="javascript:void(0);" id="add_bankdetails">
                                    Add Row
                                </a></button></td>
                                @endif
                                 </tr>
                             </tbody>
                              </table>

                              <span class="addbtn_errs" style="display: none;">Cannot add more than one row!!</span>
                          </div>
                            </div>
                                <div class="form-group">
                                            <label class="col-md-3 control-label">Upload Photo:</label>
                                           <div class="col-md-4">
                                        <div data-provides="fileinput" class="fileinput fileinput-new">
                                            <div style="" class="fileinput-new thumbnail">
                                             
                                             <?php if(!empty($partnerdata['pic'])){
                                                $path = "images/ChannelpartnerFiles/".$partnerdata['pic']; 
                                            if(file_exists($path)) { ?>
                                                <img style="height:100px;widtyh:100px;" class="img-responsive"  src="{{ asset('images/ChannelpartnerFiles/'.$partnerdata['pic'])}}">
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
                                                    <input type="file" class="form-control" name="pic[]">
                                                    </span>
                                                    <a data-dismiss="fileinput" class="btn default fileinput-exists" href="#">
                                                    Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                            <label class="col-md-3 control-label">Upload Company Documents:</label>
                                            <div class="col-md-5">
                                                <input type="file" class="form-control" name="company_docs[]" style="color:gray" multiple>
                                            </div>
                                </div>
                                @if(empty($partnerdata))
                                    <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password"  style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif        
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function TeamLevelOnChange(){
	var level = $('#emp_id').find('option:selected').attr('data-level');
	if(level > 3){
		alert("You can't add "+level+" th level");
		$('#emp_id option').attr('selected', false);
		return false;
	}
}
</script>
@stop