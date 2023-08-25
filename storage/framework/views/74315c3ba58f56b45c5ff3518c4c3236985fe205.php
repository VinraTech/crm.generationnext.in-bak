<?php $__env->startSection('content'); ?>
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
                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i><?php echo e($title); ?>

				        </div>
				    </div>
				    <div class="portlet-body form">
				        <form target="_blank" class="form-horizontal" method="post" action="<?php echo e(url('/s/admin/file-reports')); ?>"><?php echo csrf_field(); ?>
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
			                                    <?php $__currentLoopData = $typeArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey=> $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                                    	<option value="<?php echo e($type); ?>" <?php if(isset($_GET['type'])  && $_GET['type']==$type): ?> selected <?php endif; ?>><?php echo e($type); ?></option>
			                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			                                </select>
			                            </div>
			                       	</div>
			                       	<div class="form-group" id="Individual" style="display: none;">
	                                    <label class="col-md-3 control-label">Select Individual Employee :</label>
	                                    <div class="col-md-4">
	                                        <select name="individual" class="selectbox">
	                                            <?php $__currentLoopData = $getTeamLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option value="<?php echo e($level['id']); ?>" <?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']): ?> selected <?php endif; ?>>&#9679;&nbsp;<?php echo e($level['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                    <?php $__currentLoopData = $level['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey => $sublevel1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                        <option value="<?php echo e($sublevel1['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']): ?> selected <?php endif; ?> >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;<?php echo e($sublevel1['name']); ?> - <?php if(isset($getEmpType->full_name)): ?><?php echo e($getEmpType->full_name); ?><?php endif; ?></option>
                        <?php $__currentLoopData = $sublevel1['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sskey=> $sublevel2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                            <option value="<?php echo e($sublevel2['id']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;<?php echo e($sublevel2['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
                                <option value="<?php echo e($sublevel3['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel3['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>




                                <?php
                                $getdetails = Employee::with(['getemps'=>function($query){

                                    $query->with('getemps');

                                }])->where('id',$sublevel3['id'])->first();

                                $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
                                    <option value="<?php echo e($sublevel4['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel4['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>





                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group" id="Team" style="display: none;">
	                                    <label class="col-md-3 control-label">Select Team :</label>
	                                    <div class="col-md-4">
	                                        <select name="team" class="selectbox">
	                                             <?php $__currentLoopData = $getTeamLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option value="<?php echo e($level['id']); ?>" <?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']): ?> selected <?php endif; ?>>&#9679;&nbsp;<?php echo e($level['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                    <?php $__currentLoopData = $level['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey => $sublevel1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                        <option value="<?php echo e($sublevel1['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']): ?> selected <?php endif; ?> >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;<?php echo e($sublevel1['name']); ?> - <?php if(isset($getEmpType->full_name)): ?><?php echo e($getEmpType->full_name); ?><?php endif; ?></option>
                        <?php $__currentLoopData = $sublevel1['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sskey=> $sublevel2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                            <option value="<?php echo e($sublevel2['id']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;<?php echo e($sublevel2['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
                                <option value="<?php echo e($sublevel3['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel3['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>




                                <?php
                                $getdetails = Employee::with(['getemps'=>function($query){

                                    $query->with('getemps');

                                }])->where('id',$sublevel3['id'])->first();

                                $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
                                    <option value="<?php echo e($sublevel4['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel4['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>





                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                        </select>
	                                    </div>
	                                </div>
                                    <?php $banks_id = array(); ?>
	                                <div class="form-group" id="Bank">
	                                    <label class="col-md-3 control-label">Select Bank :</label>
	                                    <div class="col-md-4">
	                                        <select name="bank[]" class="selectpicker " multiple data-live-search="true" data-size="7" data-width="100%" required>
	                                        <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                         <?php array_push($banks_id, $bank['id']) ?>
	                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                        <?php $bank = implode(',',$banks_id); ?>
                                                <option value="all_banks" selected>All Branches</option>
	                                            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                                <option value="<?php echo e($bank['id']); ?>"><?php echo e($bank['short_name']); ?></option>
	                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Select Product Type :</label>
	                                    <div class="col-md-4">
	                                        <select name="product_type[]" class="selectpicker" multiple data-live-search="true" data-size="7" data-width="100%">
	                                        
	                                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                                <option value="<?php echo e($type['value']); ?>"><?php echo e($type['value']); ?></option>
	                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
			                                    <?php $__currentLoopData = $formatArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fkey=> $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                                    	<option value="<?php echo e($format); ?>"><?php echo e($format); ?></option>
			                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			                                </select>
			                            </div>
			                       	</div>
			                       	<div class="form-group">
			                       		<?php $statusArr = array('approved'=>'Approved Files','bank'=>'Login/Bank Files','declined'=>'Declined Files','disbursement'=>'Disbursement Files','login'=>'Work In Progress Files','operations'=>'Pending Approval Files','partially'=>'Partially Disburse File');?>
	                                    <label class="col-md-3 control-label">Case Status :</label>
	                                    <div class="col-md-4">
	                                        <select name="status[]" class="selectpicker" multiple data-width="100%">
	                                        	

	                                            <?php $__currentLoopData = $statusArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                                <option value="<?php echo e($key); ?>"><?php echo e($stat); ?></option>
	                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>