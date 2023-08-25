<?php $__env->startSection('content'); ?>
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
                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(action('ChannelPartnerController@channelpartners')); ?>">Channel Partners</a>
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
                        <form  role="form"  id="addEditChannelPartner" class="form-horizontal" method="post" <?php if(empty($partnerdata)): ?> action="<?php echo e(url('s/admin/add-edit-partner')); ?>" <?php else: ?>  action="<?php echo e(url('s/admin/add-edit-partner/'.$partnerdata['id'])); ?>" <?php endif; ?>  enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
                            <div class="form-body"> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Full Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($partnerdata['name']))?$partnerdata['name']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($partnerdata['company_name']))?$partnerdata['company_name']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select State :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="state" class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-stateid="<?php echo e($state['id']); ?>" value="<?php echo e($state['state']); ?>" <?php if(!empty($partnerdata['state']) && $partnerdata['state'] == $state['state']): ?> selected <?php endif; ?>><?php echo e($state['state']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select City :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <select name="city" class="selectbox" id="AppendCities"> 
                                            <option value="">Select</option>
                                            <?php if(!empty($partnerdata['city'])): ?>
                                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($city['city']); ?>" <?php if(!empty($partnerdata['city']) && $partnerdata['city'] == $city['city']): ?> selected <?php endif; ?>><?php echo e($city['city']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Channel Relation :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="emp_id" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" onchange="TeamLevelOnChange()" id="emp_id">
                                            <option data-level='0' value="">Select</option>
                                            <?php $__currentLoopData = $getTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); ?>
                                                <option data-level='1' value="<?php echo e($level['id']); ?>" <?php if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $level['id']): ?> selected <?php endif; ?>>&#9679;&nbsp;<?php echo e($level['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                                <?php $__currentLoopData = $level['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey => $sublevel1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                                                    <option data-level='2' value="<?php echo e($sublevel1['id']); ?>"<?php if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $sublevel1['id']): ?> selected <?php endif; ?> >&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;<?php echo e($sublevel1['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                                    <?php $__currentLoopData = $sublevel1['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sskey=> $sublevel2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                                                        <option data-level='3' value="<?php echo e($sublevel2['id']); ?>" <?php if(!empty($partnerdata['emp_id']) && $partnerdata['emp_id'] == $sublevel2['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;<?php echo e($sublevel2['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
                                                         <!-- start -->
														 <?php
															$getdetails = Employee::with(['getemps'=>function($query){

																$query->with('getemps');

															}])->where('id',$sublevel2['id'])->first();

															$getdetails = json_decode(json_encode($getdetails),true);
															?>
														 <?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first(); ?>
																<option data-level='4' value="<?php echo e($sublevel3['id']); ?>"<?php if(!empty($partnerdata['parent_id']) && $partnerdata['parent_id'] == $sublevel2['id'] && $sublevel3['id'] == $partnerdata['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel3['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>




																<?php
																 $getdetails = Employee::with(['getemps'=>function($query){

																	 $query->with('getemps');

																 }])->where('id',$sublevel3['id'])->first();

																 $getdetails = json_decode(json_encode($getdetails),true);
																?>
																<?php $__currentLoopData = $getdetails['getemps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssskey=> $sublevel4): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<?php  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); ?>
																	<option data-level='5' value="<?php echo e($sublevel4['id']); ?>"<?php if(!empty($partnerdata['parent_id']) && $partnerdata['parent_id'] == $sublevel3['id']): ?> selected <?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo;&raquo; &nbsp;<?php echo e($sublevel4['name']); ?> - <?php echo e($getEmpType->full_name); ?></option>
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                         <!-- end -->
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Type :<span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <?php $typeArray = array('Connector','DSA'); ?>
                                        <select name="type" class="selectbox"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $typeArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($type); ?>" <?php if(!empty($partnerdata['type']) && $partnerdata['type'] == $type): ?> selected <?php endif; ?>><?php echo e($type); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Email: </label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Email"  style="color:gray" class="form-control" <?php if(!empty($partnerdata['email'])): ?>   value="<?php echo e((!empty($partnerdata['email']))?$partnerdata['email']: ''); ?>" readonly  <?php else: ?> name="email"  <?php endif; ?>/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($partnerdata['address']))?$partnerdata['address']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">DOB :</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date dobDatepicker">
                                        <input type="text" class="form-control" placeholder="Select DOB" name="dob" value="<?php echo e((!empty($partnerdata['dob']))?$partnerdata['dob']: ''); ?>" autocomplete="off" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-3 control-label">Mobile: <span  style="color:red;"> *</span></label>
                                    <div class="col-md-5">
                                        <input type="text" autocomplete="off" placeholder="Mobile" name="mobile"  style="color:gray" class="form-control" value="<?php echo e((!empty($partnerdata['mobile']))?$partnerdata['mobile']: ''); ?>"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Pan Card Number : </label>
                                        <div class="col-md-5">
                                            <input type="text" name="pan" autocomplete="off" placeholder="PAN" class="form-control" style="color:gray"   value="<?php echo e((!empty($partnerdata['pan']))?$partnerdata['pan']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">Aadhaar Card Number :<span  style="color:red;"> *</span> </label>
                                        <div class="col-md-5">
                                            <input type="text" name="adhaar_no" autocomplete="off" placeholder="Adhaar Number" class="form-control" style="color:gray"   value="<?php echo e((!empty($partnerdata['adhaar_no']))?$partnerdata['adhaar_no']: ''); ?>" />
                                        </div>
                                </div>
                                <div class="form-group ">
                                        <label class="col-md-3 control-label">GST Number :</label>
                                        <div class="col-md-5">
                                            <input type="text" name="gst_no" autocomplete="off" placeholder="GST Number" class="form-control" style="color:gray"   value="<?php echo e((!empty($partnerdata['gst_no']))?$partnerdata['gst_no']: ''); ?>" />
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
                                    <?php if(count($chb_name)<=1 && count($chacc)<=1 && count($chifcode)<=1): ?>
                                    <th>Add row</th><?php endif; ?>
                                 </tr>
                                </thead>
                                <tbody>
                                 <tr data-row>
                                    <td data-bank>
                                        <?php if(count($chb_name)!=0): ?>
                                        <?php $__currentLoopData = $chb_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chban_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <select id="" required class="form-control" name="bank_name[]">
                                            <option value="" selected="selected">Select Bank</option>
                                            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bank['short_name']); ?>" <?php if($bank['short_name'] == $chban_name): ?> selected="" <?php endif; ?>><?php echo e($bank['short_name']); ?></option>
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
                                        <?php if(count($chacc)!=0): ?>
                                        <?php $__currentLoopData = $chacc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chaccno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e($chaccno); ?>">
                                        
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <input type="text" name="account_no[]" placeholder="Account Number" style="color:gray" autocomplete="off" class="form-control">
                                        <?php endif; ?>
                                        </td>
                                    <td data-ifsc>
                                        <?php if(count($chifcode)!=0): ?>
                                        <?php $__currentLoopData = $chifcode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chicode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="text" name="ifsc_code[]"  placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code" value="<?php echo e($chicode); ?>"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <input type="text" name="ifsc_code[]" placeholder="IFSC Code" style="color:gray" autocomplete="off" class="form-control ifs_code"><span class="ifs_err" style="display: none;">IFSC code must have 11 caharacters, first 4 characters are uppercase alphabets, fifth is zero and last 6 characters are numeric can be alphabetic!</span>
                                        <?php endif; ?>
                                        </td>
                                    <?php if(count($chb_name)<=1 && count($chacc)<=1 && count($chifcode)<=1): ?>
                                    <td><button><a href="javascript:void(0);" id="add_bankdetails">
                                    Add Row
                                </a></button></td>
                                <?php endif; ?>
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
                                                <img style="height:100px;widtyh:100px;" class="img-responsive"  src="<?php echo e(asset('images/ChannelpartnerFiles/'.$partnerdata['pic'])); ?>">
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
                                <?php if(empty($partnerdata)): ?>
                                    <div class="form-group ">
                                        <label class="col-md-3 control-label">Password: </label>
                                        <div class="col-md-5">
                                            <input type="password" placeholder="Password" name="password"  style="color:gray" class="form-control"/>
                                            <div class="progress password-meter" id="passwordMeter">
                                                <div class="progress-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>        
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>