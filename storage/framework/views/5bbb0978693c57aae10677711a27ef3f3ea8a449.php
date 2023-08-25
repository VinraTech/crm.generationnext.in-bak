<?php
use App\Employee;
?>

<?php $__env->startSection('content'); ?>
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
                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(action('EmployeeController@employees')); ?>">Employees</a>
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
                        
                        <form  role="form" class="form-horizontal" method="post" <?php if(empty($employeedata)): ?> id="addEditEmployee" action="<?php echo e(url('s/admin/add-edit-employee')); ?>" <?php else: ?>  id="editEmployee" action="<?php echo e(url('s/admin/add-edit-employee/'.$employeedata['id'])); ?>" <?php endif; ?> enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Full Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($employeedata['name']))?$employeedata['name']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select State :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="state"  class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-stateid="<?php echo e($state['id']); ?>" value="<?php echo e($state['state']); ?>" <?php if(!empty($employeedata['state']) && $employeedata['state'] == $state['state']): ?> selected <?php endif; ?>><?php echo e($state['state']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select City :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="city" class="selectbox form-control" id="AppendCities"> 
                                            <option value="">Select</option>
                                            <?php if(!empty($employeedata['city'])): ?>
                                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($city['city']); ?>" <?php if(!empty($employeedata['city']) && $employeedata['city'] == $city['city']): ?> selected <?php endif; ?>><?php echo e($city['city']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div> 
                               <!--  <div class="form-group">
                                    <label class="col-md-3 control-label">Designation :</label>

                                    <div class="col-md-5">
                                        <select name="type" class="selectbox"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $getemptypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emptype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($emptype['short_name']); ?>" <?php if(!empty($employeedata['type']) && $employeedata['type'] == $emptype['short_name']): ?> selected <?php endif; ?>><?php echo e(ucwords($emptype['full_name'])); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                <img style="height:100px;widtyh:100px;" class="img-responsive"  src="<?php echo e(asset('images/AdminImages/'.$employeedata['image'])); ?>">
                                            <?php }else{?>
                                                    <img style="height:100px;widtyh:100px;" class="img-responsive"  src="<?php echo e(asset('images/default.png')); ?>">
                                            <?php } } else { ?>
                                            <img style="height:100px;widtyh:100px;" class="img-responsive"  src="<?php echo e(asset('images/default.png')); ?>">
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
                <option data-level='1' value="ROOT" <?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == "ROOT"): ?> selected <?php endif; ?>>ROOT</option>


                <?php $__currentLoopData = $getTeamLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                    <option data-level="<?php echo e($EngLevel[$level['id']]); ?>" value="<?php echo e($level['id']); ?>" <?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $level['id']): ?> selected <?php endif; ?>>&#9679;&nbsp;<?php echo e($level['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                    <?php $__currentLoopData = $level['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey => $sublevel1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
						$getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first();
                          
						?>
                        <option data-level="<?php echo e($EngLevel[$sublevel1['id']]); ?>" value="<?php echo e($sublevel1['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel1['id']): ?> selected <?php endif; ?> >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;<?php echo e($sublevel1['name']); ?> - <?php if(isset($getEmpType->full_name)): ?><?php echo e($getEmpType->full_name); ?><?php endif; ?></option>
                        <?php $__currentLoopData = $sublevel1['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sskey=> $sublevel2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
							$getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); 
							
							?>
                            <option data-level="<?php echo e($EngLevel[$sublevel2['id']]); ?>" value="<?php echo e($sublevel2['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel2['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;<?php echo e($sublevel2['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>

                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php 
								$getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first();
                              
								?>
                                <option data-level="<?php echo e($EngLevel[$sublevel3['id']]); ?>"  value="<?php echo e($sublevel3['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel3['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel3['name']); ?> - <?php echo e($getEmpType->full_name); ?> </option>




                                <?php
                                 $getdetails = Employee::with(['getemps'=>function($query){

                                     $query->with('getemps');

                                 }])->where('id',$sublevel3['id'])->first();

                                 $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sssskey=> $sublevel4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); 
									
									?>
                                    <option data-level="<?php echo e($EngLevel[$sublevel4['id']]); ?>" value="<?php echo e($sublevel4['id']); ?>"<?php if(!empty($employeedata['parent_id']) && $employeedata['parent_id'] == $sublevel4['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel4['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  





                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        
		</div>
    </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Product :</label>
                                    <div class="col-md-5">
                                        <select name="products[]" class="selectpicker" multiple data-width="100%" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option selected="selected">    All Products
                                            </option>
                                            <?php $__currentLoopData = $getproducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                                <?php if(!empty($empPids) && in_array($product['id'],$empPids)) {
                                                    $selected = "selected";
                                                }else{
                                                    $selected="";
                                                }
                                                ?>
                                                
                                                <option value="<?php echo e($product['id']); ?>" <?php echo e($selected); ?>><?php echo e(ucwords($product['name'])); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Email: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" name="email" autocomplete="off" placeholder="Email" class="form-control" style="color:gray" value="<?php echo e((!empty($employeedata['email']))?$employeedata['email']: ''); ?>"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Employee Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($employeedata['address']))?$employeedata['address']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">DOB :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                       
                                        <input type="date" class="form-control" placeholder="Select DOB" name="dob" value="<?php echo e((!empty($employeedata['dob']))?$employeedata['dob']: ''); ?>" autocomplete="off" required/>
                                       <!--  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date Of Joining :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        
                                        <input type="date" class="form-control" placeholder="Select Date Of Joining" name="doj" value="<?php echo e((!empty($employeedata['doj']))?$employeedata['doj']: ''); ?>" autocomplete="off" required/>
                                        <!-- <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                        
                                    </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Pan Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pan" autocomplete="off" placeholder="PAN" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['pan']))?$employeedata['pan']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Aadhaar Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="adhaar_no" autocomplete="off" placeholder="Adhaar Number" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['adhaar_no']))?$employeedata['adhaar_no']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Designation :<span  style="color:red;"> *</span></label>

                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="type" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" required> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $getemptypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emptype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($emptype['short_name']); ?>" <?php if(!empty($employeedata['type']) && $employeedata['type'] == $emptype['short_name']): ?> selected <?php endif; ?>><?php echo e(ucwords($emptype['full_name'])); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Select Designation :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="designation_name" class="selectbox"> 
                                            <option value="">Select</option>
                                           
                                            
                                                <?php $__currentLoopData = $designationdetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($designation['designation_name']); ?>" <?php if(!empty($employeedata['designation_name']) && $employeedata['designation_name'] == $designation['designation_name']): ?> selected <?php endif; ?>><?php echo e($designation['designation_name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                           
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
                                    <?php if(count($b_name)<=1 && count($acc)<=1 && count($ifcode)<=1): ?>
                                    <th>Add row</th><?php endif; ?>
                                 </tr>
                                 </thead>
                                 <tbody>
                                 <tr data-row>
                                    <td data-bank>
                                        <?php if(count($b_name)!=0): ?>
                                        <?php $__currentLoopData = $b_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ban_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <select id="" required class="form-control" name="bank_name[]">
                                            <option value="" selected="selected">Select Bank</option>
                                            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bank['short_name']); ?>" <?php if($bank['short_name'] == $ban_name): ?> selected="" <?php endif; ?>><?php echo e($bank['short_name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <select id="" required class="form-control" name="bank_name[]">
                                        <option value="" selected="selected">Select Bank</option>
                                        <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($bank['short_name']); ?>"><?php echo e($bank['short_name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php endif; ?>
                                        </td>                                
                                    <td data-account>
                                        <?php if(count($acc)!=0): ?>
                                        <?php $__currentLoopData = $acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e($accno); ?>">
                                        
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control">
                                        <?php endif; ?>
                                        </td>
                                    <td data-ifsc>
                                        <?php if(count($ifcode)!=0): ?>
                                        <?php $__currentLoopData = $ifcode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code" value="<?php echo e($icode); ?>"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control  ifs_code"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        <?php endif; ?>
                                        </td>
                                    <?php if(count($b_name)<=1 && count($acc)<=1 && count($ifcode)<=1): ?>
                                    <td><button><a href="javascript:void(0);" id="add_details">
                                    Add Row
                                </a></button></td>
                                <?php endif; ?>
                                 </tr>
                                 </tbody>
                              </table>
                              <span class="addebtn_errs" style="display: none;">Cannot add more than one row!!</span>
                          </div>
                            </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Monthly Salary :<span  style="color:red;"> *</span> </label>
                                        <div class="col-md-5">
                                            <input type="text" name="monthly_salary" autocomplete="off" placeholder="Monthly Salary" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['monthly_salary']))?$employeedata['monthly_salary']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">PCC : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pcc" autocomplete="off" placeholder="PCC" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['pcc']))?$employeedata['pcc']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Blood Group : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="blood_group" autocomplete="off" placeholder="Blood Group" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['blood_group']))?$employeedata['blood_group']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Emergency Phone Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="emergency_number" autocomplete="off" placeholder="Emergency Phone Number" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['emergency_number']))?$employeedata['emergency_number']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Medical Status : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="medical_status" autocomplete="off" placeholder="Medical Status" class="form-control" style="color:gray"   value="<?php echo e((!empty($employeedata['medical_status']))?$employeedata['medical_status']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Mobile: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Mobile" name="mobile"  style="color:gray" class="form-control" value="<?php echo e((!empty($employeedata['mobile']))?$employeedata['mobile']: ''); ?>"/>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label class="col-md-3 control-label">Refer to Department :<br>(While Allocating Lead) </label>
                                    <div class="col-md-5">
                                        <?php 
                                                $yesselected="";
                                                $noselected ="selected";   
                                            ?>
                                        <?php if(!empty($employeedata['refer_to_dept'])): ?>
                                            <?php if($employeedata['refer_to_dept'] =="yes"): ?>
                                                <?php 
                                                $yesselected="selected";
                                                $noselected ="";   
                                            ?>
                                            <?php else: ?>
                                                <?php 
                                                $yesselected="";
                                                $noselected ="selected";   
                                            ?>
                                            <?php endif; ?>
                                         <?php endif; ?>
                                        <select name="refer_to_dept" class="selectbox"> 
                                            <option value="yes" <?php echo e($yesselected); ?>>Yes</option>
                                            <option value="no" <?php echo e($noselected); ?>>No</option>
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
                                        <?php if(!empty($employeedata['is_access'])): ?>
                                            <?php if($employeedata['is_access'] =="full"): ?>
                                                <?php 
                                                $fullselected="selected";
                                                $limitedselected = "";
                                                $hierarchyselected = "";
                                            ?>
                                            <?php elseif($employeedata['is_access'] =="hierarchy"): ?>
                                                <?php 
                                                $fullselected="";
                                                $limitedselected = "";
                                                $hierarchyselected = "selected";  
                                            ?>
                                            <?php else: ?>
                                                <?php 
                                                $fullselected="";
                                                $limitedselected = "selected";
                                                $hierarchyselected = "";  
                                            ?>
                                            <?php endif; ?>
                                         <?php endif; ?>
                                        <select name="is_access" class="selectbox"> 
                                            <option value="limited" <?php echo e($limitedselected); ?>>Limited (Team only)</option>
                                            <option value="full" <?php echo e($fullselected); ?>>Full System Access</option>
                                            <option value="hierarchy" <?php echo e($hierarchyselected); ?>>Hierarchy</option>
                                        </select>
                                    </div>
                                </div>
                                <?php if(empty($employeedata)): ?>
                                    <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password"  style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                  <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password" value="<?php echo e($employeedata['decrypt_password']); ?>" style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>        
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>