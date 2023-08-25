<?php $__env->startSection('content'); ?>
<?php 
use App\FileDropdown; 
$types = FileDropdown::getfiledropdown('facility');

?>
<div class="page-content-wrapper">

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Banker's Management </h1>

            </div>

        </div>

        <ul class="page-breadcrumb breadcrumb">

            <li>

                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>

                <i class="fa fa-circle"></i>

            </li>

            <li>

                <a href="<?php echo e(action('MasterController@banks')); ?>">Bankers</a>

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

                        <form  role="form"  id="addEditBankerform" class="form-horizontal" method="post" <?php if(empty($getbankdetails)): ?> action="<?php echo e(url('s/admin/add-edit-bank')); ?>" <?php else: ?>  action="<?php echo e(url('s/admin/add-edit-bank/'.$getbankdetails['id'])); ?>" <?php endif; ?>> 

                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />

                            <div class="form-body">
                             
                             <div class="form-group">

                                    <label class="col-md-3 control-label">Banker Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Name" name="banker_name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['banker_name']))?$getbankdetails['banker_name']: ''); ?>"/>

                                    </div>

                                </div>

                                 <div class="form-group">

                                    <label class="col-md-3 control-label">Bank Name :</label>

                                    <div class="col-md-5 jquerySelectbox">
                    
									  <select name="bank_name"  class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-stateid="<?php echo e($bank['id']); ?>" value="<?php echo e($bank['full_name']); ?>" <?php if(!empty($getbankdetails['bank_name']) && $getbankdetails['bank_name'] == $bank['full_name']): ?> selected <?php endif; ?>><?php echo e($bank['full_name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                    </div>

                                </div>
                               
                                <div class="form-group">

                                    <label class="col-md-3 control-label">RM Code :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="RM Code" name="rm_code" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['rm_code']))?$getbankdetails['rm_code']: ''); ?>"/>

                                    </div>

                                </div>
                                <div class="form-group">

                                    <label class="col-md-3 control-label">Product List :</label>

                                    <div class="col-md-5">

                                        <select name="product[]" class="selectpicker" multiple  data-live-search="true" data-size="7" data-width="100%">
                                        <option selected="selected">    All Products
                                        </option>
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <?php if(!empty($banPids) && in_array($type['id'],$banPids)) {
                                                    $selected = "selected";
                                                }else{
                                                    $selected="";
                                                }
                                                ?>
                                            <option value="<?php echo e($type['id']); ?>" <?php echo e($selected); ?>><?php echo e(ucwords($type['value'])); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    </div>

                                </div>


                                

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Email :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Email" name="email" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['email']))?$getbankdetails['email']: ''); ?>"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Phone Number :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Phone Number" name="phone_number" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['phone_number']))?$getbankdetails['phone_number']: ''); ?>"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Address :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['address']))?$getbankdetails['address']: ''); ?>"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">State :</label>

                                    <div class="col-md-5 jquerySelectbox">

                                       <select name="state" class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-stateid="<?php echo e($state['id']); ?>" value="<?php echo e($state['state']); ?>" <?php if(!empty($getbankdetails['state']) && $getbankdetails['state'] == $state['state']): ?> selected <?php endif; ?>><?php echo e($state['state']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">District :</label>

                                    <div class="col-md-5">

                                       <select name="district" class="selectbox" id="AppendCities"> 
                                            <option value="">Select</option>
                                            <?php if(!empty($getbankdetails['district'])): ?>
                                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($city['city']); ?>" <?php if(!empty($getbankdetails['district']) && $getbankdetails['district'] == $city['city']): ?> selected <?php endif; ?>><?php echo e($city['city']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">City :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="City" name="city" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['city']))?$getbankdetails['city']: ''); ?>"/>

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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>