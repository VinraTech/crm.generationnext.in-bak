@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\File; use App\FileLoanDetail;
$types = FileDropdown::getfiledropdown('facility');
?>
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
				        <form target="_blank" class="form-horizontal" method="post" action="{{url('/s/admin/file-reports')}}">@csrf
				        	<div class="form-body">
				                <div class="row">
                        			<div class="form-group">
			                            <label class="col-md-3 control-label">Select Type:</label>
			                            <div class="col-md-4">
			                                <select name="type" class=" form-control getType" required> 
			                                    <?php 
			                                    if(Session::get('empSession')['type']=="admin" || Session::get('empSession')['is_access']=="full"){
			                                    	$typeArr = array('Individual','Team Wise','All Branches');
			                                    } else if(Session::get('empSession')['is_access']=="limited") {
			                                    	$typeArr = array('Individual','Team Wise');
			                                    } else if(Session::get('empSession')['is_access']=="hierarchy") {
			                                    	$typeArr = array('Individual','All Branches');
			                                    }
												sort($typeArr);
			                                    ?>
			                                    <option value=""> Select</option>
			                                    @foreach($typeArr as $skey=> $type)
			                                    	<option value="{{$type}}" @if(isset($_GET['type'])  && $_GET['type']==$type) selected @endif>{{$type}}</option>
			                                    @endforeach
			                                </select>
			                            </div>
			                       	</div>
			                       	<div class="form-group" id="Individual" style="display: none;">
	                                    <label class="col-md-3 control-label">Select Individual Employee :</label>
	                                    <div class="col-md-4">
	                                        <select name="individual" class="selectbox">
	                                            @foreach($getTeamLevels as $key => $level)
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option value="{{$level['id']}}" @if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']) selected @endif>&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                    @foreach($level['getemps'] as $skey => $sublevel1)
                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                        <option value="{{$sublevel1['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - @if(isset($getEmpType->full_name)){{$getEmpType->full_name}}@endif</option>
                        @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                            <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                            <option value="{{$sublevel2['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            @foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
                                <option value="{{$sublevel3['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;{{$sublevel3['name']}} - {{$getEmpType->full_name}}</option>




                                <?php
                                $getdetails = Employee::with(['getemps'=>function($query){

                                    $query->with('getemps');

                                }])->where('id',$sublevel3['id'])->first();

                                $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                @foreach($getdetails['getemps'] as $ssskey=> $sublevel4)
                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
                                    <option value="{{$sublevel4['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;{{$sublevel4['name']}} - {{$getEmpType->full_name}}</option>
                                @endforeach





                            @endforeach


                        @endforeach
                    @endforeach
                @endforeach
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group" id="Team" style="display: none;">
	                                    <label class="col-md-3 control-label">Select Team :</label>
	                                    <div class="col-md-4">
	                                        <select name="team" class="selectbox">
	                                             @foreach($getTeamLevels as $key => $level)
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option value="{{$level['id']}}" @if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']) selected @endif>&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                    @foreach($level['getemps'] as $skey => $sublevel1)
                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                        <option value="{{$sublevel1['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - @if(isset($getEmpType->full_name)){{$getEmpType->full_name}}@endif</option>
                        @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                            <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                            <option value="{{$sublevel2['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            @foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
                                <option value="{{$sublevel3['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;{{$sublevel3['name']}} - {{$getEmpType->full_name}}</option>




                                <?php
                                $getdetails = Employee::with(['getemps'=>function($query){

                                    $query->with('getemps');

                                }])->where('id',$sublevel3['id'])->first();

                                $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                @foreach($getdetails['getemps'] as $ssskey=> $sublevel4)
                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
                                    <option value="{{$sublevel4['id']}}"@if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;{{$sublevel4['name']}} - {{$getEmpType->full_name}}</option>
                                @endforeach





                            @endforeach


                        @endforeach
                    @endforeach
                @endforeach
	                                        </select>
	                                    </div>
	                                </div>
                                    <?php $banks_id = array(); ?>
	                                <div class="form-group" id="Bank">
	                                    <label class="col-md-3 control-label">Select Bank :</label>
	                                    <div class="col-md-4">
	                                        <select name="bank[]" class="selectpicker " multiple data-live-search="true" data-size="7" data-width="100%" required>
	                                        @foreach($banks as $key => $bank)
	                                         <?php array_push($banks_id, $bank['id']) ?>
	                                        @endforeach
	                                        <?php $bank = implode(',',$banks_id); ?>
                                                <option value="all_banks" selected>All Branches</option>
	                                            @foreach($banks as $key => $bank)
	                                                <option value="{{$bank['id']}}">{{$bank['short_name']}}</option>
	                                            @endforeach
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Select Product Type :</label>
	                                    <div class="col-md-4">
	                                        <select name="product_type[]" class="selectpicker" multiple data-live-search="true" data-size="7" data-width="100%">
	                                        
	                                            @foreach($types as $type)
	                                                <option value="{{$type['value']}}">{{$type['value']}}</option>
	                                            @endforeach
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Start Date :</label>
	                                    <div class="col-md-4">
	                                      <div class="fullWidth">
					        			     <input type="text"  class="form-control dobDatepicker__table" name="start_date" placeholder="Start Date" autocomplete="off" required />
					        		      </div>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">End Date :</label>
	                                    <div class="col-md-4">
	                                      <div class="fullWidth">
					        			     <input type="text"  class="form-control dobDatepicker__table" name="end_date" placeholder="End Date" autocomplete="off" required />
					        		      </div>
	                                    </div>
	                                </div>
	                                <div class="form-group">
			                            <label class="col-md-3 control-label">Data Format:</label>
			                            <div class="col-md-4">
			                                <select name="format_type" class=" form-control" required> 
			                                    <?php $formatArr = array('Graph','Tabular');?>
			                                    <option value=""> Select</option>
			                                    @foreach($formatArr as $fkey=> $format)
			                                    	<option value="{{$format}}">{{$format}}</option>
			                                    @endforeach
			                                </select>
			                            </div>
			                       	</div>
			                       	<div class="form-group">
			                       		<?php $statusArr = array('approved'=>'Approved Files','bank'=>'Login/Bank Files','declined'=>'Declined Files','disbursement'=>'Disbursement Files','login'=>'Work In Progress Files','operations'=>'Pending Approval Files','partially'=>'Partially Disburse File');?>
	                                    <label class="col-md-3 control-label">Case Status :</label>
	                                    <div class="col-md-4">
	                                        <select name="status[]" class="selectpicker" multiple data-width="100%">
	                                        	

	                                            @foreach($statusArr as $key => $stat)
	                                                <option value="{{$key}}">{{$stat}}</option>
	                                            @endforeach
	                                        </select>
	                                    </div>
	                                </div>
	                                <!-- <div class="form-group">
	                                    <label class="col-md-3 control-label">Select Year :</label>
	                                    <div class="col-md-4">
	                                        <select name="year" class="form-control getYear" required>
												<option value="">Select</option>
												<?php
												$dates = range('2018', date('Y')+10);
												foreach($dates as $date){
													if (date('m', strtotime($date)) <= 6) {//Upto June
												        $year = ($date-1) . '-' . $date;
												    } else {//After June
												        $year = $date . '-' . ($date + 1);
												    }
												    echo "<option value='$year'>$year</option>";
												}?>
											</select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                	<label class="col-md-3 control-label">Select Month :</label>
	                                    <div class="col-md-4">
	                                        <select name="month" class="form-control" id="AppendMonths" required>
	                                        	<option value="">Select</option>
	                                        	
	                                    </select>
	                                </div>
				                </div> -->
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
		$(document).on('change','.getType',function(){
			var value = $(this).val();
			if(value=="Individual"){
				$('#Individual').show();
				$('#Team').hide();
				//$('#Bank').hide();
			}else if(value=="Team Wise"){
				$('#Team').show();
				$('#Individual').hide();
				//$('#Bank').hide();
			}else if(value=="All Branches"){
				$('#Bank').show();
				$('#Team').hide();
				$('#Individual').hide();
			}else{
				//$('#Bank').hide();
				$('#Team').hide();
				$('#Individual').hide();
			}
		});

		$(document).on('change','.getYear',function(){
			var year = $(this).val();
			if(year==""){
				$('#AppendMonths').html('<option value="">Select</option>');
			}else{
				$.ajax({
					data : {year:year},
					url : '/s/admin/append-months',
					type : 'post',
					success:function(resp){
						$('#AppendMonths').html(resp);
					},
					error:function(){
					}
				})
			}
		})

	})

	$('.dobDatepicker__table').datetimepicker({
	        format:'YYYY-MM-DD',
	        useCurrent: false,
	        allowInputToggle: true,
	        maxDate: moment()
	});
</script>
@stop