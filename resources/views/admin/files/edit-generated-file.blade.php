@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\File;?>
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
                <a href="{{ action('FileController@files') }}">Files</a>
            </li>
        </ul>
        <div class="row" id="RemoveIfTypeChange">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i>{{$title}}
				        </div>
				    </div>
				    <div class="portlet-body form">
				        <form  class="form-horizontal" method="post" action="{{url('/s/admin/edit-generated-file/'.$filedetail['id'])}}" enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Applicant Name :</label>
					                    <div class="col-md-6">
					                        <p style="margin-top:8px;">{{$clientdetail->name}}</p>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Company Name :</label>
					                    <div class="col-md-6">
					                        <p style="margin-top:8px;">{{$clientdetail->company_name}}</p>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Mobile :</label>
					                    <div class="col-md-6">
					                        <p style="margin-top:8px;">{{$clientdetail->mobile}}</p>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">PAN :</label>
					                    <div class="col-md-6">
					                        <p style="margin-top:8px;">{{$clientdetail->pan}}</p>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Select Department:</label>
					                    <?php $departments = array('Mortgage','Car Loan','Business Loan') ?>
					                    <div class="col-md-6">
					                        <select name="department" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($departments as $key => $department)
					                            	<option value="{{$department}}" @if(!empty($filedetail['department']) && $filedetail['department'] == $department) selected @endif>{{$department}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                	<?php $facilities = FileDropdown::getfiledropdown('facility'); ?>
					                    <label class="col-md-6 control-label">Type of Facility:</label>
					                    <div class="col-md-6">
					                        <select name="facility_type" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($facilities as $key => $facility)
					                            	<option value="{{$facility['value']}}" @if(!empty($filedetail['facility_type']) && $filedetail['facility_type'] == $facility['value']) selected @endif>{{$facility['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
									<?php $directTypes = Employee::gettypes('direct'); ?>
									@foreach($directTypes as $dkey=> $directType)
										@if($directType['short_name'] =="bm")
											<?php $emps = Employee::getemployees('bm');?>
										@else
											<?php $emps = Employee::getemployees('all');?>
										@endif
										
										<div class="form-group col-md-6">
					                		<label class="col-md-6 control-label">{{$directType['full_name']}}:</label>
						                    <div class="col-md-6">
					                        	@if($directType['is_edit'] =="no")
					                        		<?php $getemp = File::getfilemp($filedetail['id'],$directType['short_name']); ?>
					                        		<p style="margin-top: 9px">{{$getemp['emp']['name']}}</p>
													<input type="hidden" name="emps[]" value="{{$directType['short_name']}}-{{$getemp['employee_id']}}">
					                        	@else
							                        <select name="emps[]" class="selectbox" required> 
							                            <option value="">Select</option>
							                        	@foreach($emps as $key => $emp)
							                        		<?php $sel=""; ?>
															@if(!empty($filedetail)) 
																<?php $sel = File::checkfoSel($filedetail['id'],$directType['short_name'],$emp['id']); ?>
															@endif
							                            	<option value="{{$directType['short_name']}}-{{$emp['id']}}" {{$sel}}>{{$emp['name']}} - {{$emp['emptype']}}</option>
							                            @endforeach
							                        </select>
							                    @endif
						                    </div>
					               		</div>
					               		@if ($dkey % 2 == 0)
					               			<div class="clearfix"></div>
					               		@endif
									@endforeach
					            	<?php $employees = Employee::getemployees('all'); ?>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Select Source :</label>
					                    <div class="col-md-6">
					                        <select name="emps[]" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($employees as $key => $emp)
					                        		<?php $sel=""; ?>
													@if(!empty($filedetail)) 
														<?php $sel = File::checkfoSel($filedetail['id'],'source',$emp['id']); ?>
													@endif
					                            	<option value="source-{{$emp['id']}}" {{$sel}}>{{$emp['name']}} - {{$emp['emptype']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Select Type :</label>
					                    <div class="col-md-6">
					                        <select name="file_type" class="selectbox getFiletype" required> 
					                            <option value="">Select</option>
					                        	<option value="direct" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "direct") selected @endif>Direct</option>
					                        	<option value="indirect" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "indirect") selected @endif>Indirect</option>
					                        	<option value="other" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "other") selected @endif>Other Department</option>
					                        </select>
					                    </div>
					                </div>
					                <div id="Appendcrm">
					                	@if(!empty($filedetail['file_type']) && ($filedetail['file_type'] == "indirect" || $filedetail['file_type'] == "other" ))
					                	<?php $indirectTypes = Employee::gettypes('indirect'); ?>
					                	@foreach ($indirectTypes as $indkey => $indirect)
											<div class="form-group col-md-6">
							                    <label class="col-md-6 control-label">{{$indirect['full_name']}}:</label>
							                    <div class="col-md-6">
							                        <select name="emps[]" class="selectbox" required>
							                            <option value="">Select</option>';
							                            @foreach($employees as $key => $emp)
							                            	<?php $sel=""; ?>
															@if(!empty($filedetail)) 
																<?php $sel = File::checkfoSel($filedetail['id'],$indirect['short_name'],$emp['id']); ?>
															@endif
					                                        <option value="{{$indirect['short_name']}}-{{$emp['id']}}" {{$sel}}>{{$emp['name']}} - {{$emp['emptype']}}</option>
					                                    @endforeach
							                        </select>
							                    </div>
				                			</div>
				                			@if($indkey ==0)
				                				<div class="clearfix"></div>
				                			@endif
					                	@endforeach
					                	@if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "indirect")
                                		<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Select Channel Partner :</label>
						                    <div class="col-md-6">
						                        <select name="channel_partner_id" class="selectbox" required>
						                            <option value="">Select</option>';
						                            @foreach($getpartners as $key => $partner)
				                                        <option value="{{$partner['id']}}" @if(!empty($filedetail['channel_partner_id']) && $filedetail['channel_partner_id'] == $partner['id']) selected @endif>{{$partner['name'] }} - {{$partner['type']}}</option>
				                                    @endforeach
						                        </select>
						                    </div>
						                </div>
						                @endif
                                		@endif
					                </div>
					                <div class="clearfix"></div>
                                		<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">LTS Number :</label>
						                    <div class="col-md-6">
						                        <input type="text" placeholder="LTS Number" name="lts_no" style="color:gray" autocomplete="off" class="form-control" value="{{$filedetail['lts_no']}}" required>
						                    </div>
						                </div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Remarks :</label>
						                    <div class="col-md-6">
						                        <textarea type="text" placeholder="Remarks" name="remarks"style="color:gray" autocomplete="off" class="form-control">{{$filedetail['remarks']}}</textarea>
						                    </div>
						                </div>
				                </div>
				            </div>
				            <div class="form-actions right1 text-center">
				                <button class="btn green" type="submit">Update</button>
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
		$(document).on('change','.getFiletype',function(){
			var type = $(this).val();
			if(type =="indirect"|| type =="other" ){
				$('.loadingDiv').show();
				$.ajax({
					data : {type :type},
					url : '/s/admin/append-crm',
					type : 'post',
					success:function(resp){
						$('#Appendcrm').html(resp);
						$('.loadingDiv').hide();
					},
					error:function(){}
				})
			}else{
				$('#Appendcrm').html('');
			}
		});
	})
</script>
@stop