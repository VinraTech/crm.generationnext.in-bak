<?php $__env->startSection('content'); ?>

<div class="page-content-wrapper">

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Bank Management </h1>

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

                        <form  role="form"  id="addEditBankform" class="form-horizontal" method="post" <?php if(empty($getbankdetails)): ?> action="<?php echo e(url('s/admin/add-edit-banks')); ?>" <?php else: ?>  action="<?php echo e(url('s/admin/add-edit-banks/'.$getbankdetails['id'])); ?>" <?php endif; ?>> 

                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />

                            <div class="form-body">

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Full Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Full Name" name="full_name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['full_name']))?$getbankdetails['full_name']: ''); ?>"/>

                                    </div>

                                </div> 

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Short Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Short Name" name="short_name" style="color:gray" autocomplete="off" class="form-control" value="<?php echo e((!empty($getbankdetails['short_name']))?$getbankdetails['short_name']: ''); ?>"/>

                                    </div>

                                </div>

                                

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Type :</label>

                                    <div class="col-md-5">

                                        <?php 

                                                $bankselected="";

                                                $nbfcselected ="";   

                                            ?>

                                        <?php if(!empty($getbankdetails['type'])): ?>

                                            <?php if($getbankdetails['type'] =="Bank"): ?>

                                                <?php 

                                                $bankselected="selected";

                                                $nbfcselected ="";   

                                            ?>

                                            <?php else: ?>

                                                <?php 

                                                $bankselected="";

                                                $nbfcselected ="selected";   

                                            ?>

                                            <?php endif; ?>

                                         <?php endif; ?>

                                        <select name="type" class="selectbox"> 

                                            <option value="">Select</option>

                                            <option value="Bank" <?php echo e($bankselected); ?>>Bank</option>

                                            <option value=" Non-Banking Financial Company" <?php echo e($nbfcselected); ?>> Non-Banking Financial Company</option>

                                        </select>

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