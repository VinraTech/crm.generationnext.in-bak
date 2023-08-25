@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\State; use App\City;?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>File's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('/s/admin/create-applicants/'.$filedetails['id']) }}">Applicants</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i>{{$title}}
				        </div>
				    </div>
				    <div class="portlet-body form">
				        <form  class="form-horizontal" method="post" @if(!empty($applicantdetails)) action="{{url('/s/admin/add-individual-applicant/'.$filedetails['id'].'/'.$applicantdetails['id'])}}" @else action="{{url('/s/admin/add-individual-applicant/'.$filedetails['id'])}}" @endif enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('applicant type'); ?>
					                    <label class="col-md-6 control-label">Applicant Type:</label>
					                    <div class="col-md-6">
					                        <select name="applicant_type" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['applicant_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Name" name="name" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($applicantdetails)?$applicantdetails['name']:$clientdata['name'] )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Mother's Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Mother's Name" name="mother_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['mother_name']:$clientdata['mother_name'] )}}" required>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">PAN :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="PAN" name="pan" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($applicantdetails)?$applicantdetails['pan']:$clientdata['pan'] )}}" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Father's Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Father's Name" name="father_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['father_name']:$clientdata['father_name'] )}}" required>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Spouse Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Spouse Name" name="spouse_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['spouse_name']:$clientdata['spouse_name'] )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('martial'); ?>
					                    <label class="col-md-6 control-label">Martial Status :</label>
					                    <div class="col-md-6">
					                        <select name="martial_status" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['martial_status'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Number of Dependents :</label>
					                    <div class="col-md-6" id="depen">
					                        <input type="number" placeholder="Number of Dependents" name="no_of_dependents"  style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($applicantdetails)?$applicantdetails['no_of_dependents']:'0' )}}">
					                        
					                        <button id="plus" type="button" value="+" >+</button>
		                                    <button id="minus" type="button" value="-" >-</button>
		                                
                                          
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Date of Birth:</label>
					                    <div class="col-md-6">
					                        <div class="input-group input-append date dobDatepicker">
		                                        <input type="text" class="form-control" placeholder="Select DOB" name="dob" autocomplete="off" value="{{(!empty($applicantdetails)?$applicantdetails['dob']:$clientdata['dob'] )}}">
		                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		                                    </div>
							            </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">UID(Adhar No.) :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="UID(Adhar No.)" name="uid" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($applicantdetails)?$applicantdetails['uid']:$clientdata['adhar_no'] )}}" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('other proof'); ?>
					                    <label class="col-md-6 control-label">Other Proof If any :</label>
					                    <div class="col-md-6">
					                        <select name="other_proof" id="otherProof" class="selectbox"> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['other_proof'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Enter Proof Details/Number :</label>
					                    <div class="col-md-6">
					                        <input id="otherProofDetails" type="text" placeholder="Enter Proof Details/Number" name="other_proof_details" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($applicantdetails)?$applicantdetails['other_proof_details']:'' )}}"readonly>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('residental'); ?>
					                    <label class="col-md-6 control-label">Residental Status :</label>
					                    <div class="col-md-6">
					                        <select name="residental_status" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['residental_status'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('current residence'); ?>
					                    <label class="col-md-6 control-label">Current Residence :</label>
					                    <div class="col-md-6">
					                        <select name="current_residence" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['current_residence'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('qualification'); ?>
					                    <label class="col-md-6 control-label">Qualification :</label>
					                    <div class="col-md-6">
					                        <select name="qualification" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($applicantdetails && $applicantdetails['qualification'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Nationality :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Nationality" name="nationality" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['nationality']:'' )}}" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <hr>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Address 1 :</label>
					                    <div class="col-md-6">
					                        <textarea  placeholder="Enter Address 1" name="address1" style="color:gray" autocomplete="off" class="form-control" required>{{(!empty($applicantdetails)?$applicantdetails['address1']:$clientdata['current_res_address'] )}}</textarea>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Address 2 :</label>
					                    <div class="col-md-6">
					                        <textarea  placeholder="Enter Address 2" name="address2" style="color:gray" autocomplete="off" class="form-control">{{(!empty($applicantdetails)?$applicantdetails['address2']:'' )}}</textarea>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">State :</label>
					                    <div class="col-md-6">
					                    	<?php $states = State::getstates(); ?>
					                        <select name="state" class="selectbox getState"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($applicantdetails) && $applicantdetails['state']==$state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">City :</label>
					                    <div class="col-md-6">
					                       <select name="city" class="selectbox" id="AppendCities" required> 
                                            <option value="">Select</option>
                                            @if(!empty($applicantdetails['city']))
                                            	<?php $cities = City::getcities($applicantdetails['state']); ?>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($applicantdetails['city']) && $applicantdetails['city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">District :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="District" name="district" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['district']:'' )}}" required>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">PinCode :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="pincode" name="pincode" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['pincode']:'' )}}" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Landmark :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Landmark" name="landmark" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['landmark']:$clientdata['permanent_landmark'] )}}" required>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Residing Since :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Residing Since" name="residing_since" style="color:gray" autocomplete="off" value="{{(!empty($applicantdetails)?$applicantdetails['residing_since']:'' )}}" class="form-control" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Telephone No. :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Telephone No." name="tel_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['tel_no']:'' )}}" required>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Mobile No. :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Mobile No." name="mobile_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails)?$applicantdetails['mobile_no']:$clientdata['mobile'] )}}" required>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Email Id. :</label>
					                    <div class="col-md-6">
					                        <input type="email" placeholder="Email Id." name="email" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($applicantdetails)?$applicantdetails['email']:'' )}}">
					                    </div>
					                </div>
					                @if(empty($applicantdetails['occupation']))
						                <div class="form-group col-md-6">
						                	<?php $occupation = FileDropdown::getfiledropdown('occupation'); ?>
						                    <label class="col-md-6 control-label">Select Occupation :</label>
						                    <div class="col-md-6">
						                        <select name="occupation" class="selectbox getOccupation" required> 
						                            <option value="">Select</option>
						                        	@foreach($occupation as $key => $dropval)
						                            	<option value="{{$dropval['value']}}">{{$dropval['value']}}</option>
						                            @endforeach
						                        </select>
						                    </div>
						                </div>
						               <div class="clearfix"></div>
						            @else
						            	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Occupation :</label>
						                    <div class="col-md-6">
						                        <p style="margin-top: 8px;">{{$applicantdetails['occupation']}}</p>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                @include('layouts.adminLayout.occupation-form')
						            @endif
						            <div id="AppendOccupationForm">
					                </div>
				                </div>
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
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('change','#otherProof',function(){
			var value = $(this).val();
			if(value==""){
				$('#otherProofDetails').val('');
				$('#otherProofDetails').attr('readonly', true);
			}else{
				$('#otherProofDetails').removeAttr('readonly');
			} 
		});
		
		$(document).on('change','.getOccupation',function(){
			$('.loadingDiv').show();
			var occupation = $(this).val();
			$.ajax({
				url : '/s/admin/append-occupation-form',
				data: {occupation:occupation},
				type : 'post',
				success:function(resp){
					$('#AppendOccupationForm').html(resp.view);
					$('.dobDatepicker').datetimepicker({
				        format:'YYYY-MM-DD',
				        useCurrent: false,
				        allowInputToggle: true
				    });
				    $('.loadingDiv').hide();
				},
				error:function(){
					alert('Error');
				}
			})
		});

		 $("#plus").on('click',function(){
		 	
            $("#depen input").val(parseInt($("#depen input").val())+1);
           
         });

         $("#minus").on('click',function(){
         	if(parseInt($("#depen input").val()) > 0){
              $("#depen input").val(parseInt($("#depen input").val())-1);
            }
         });

     
	});
</script>
@stop