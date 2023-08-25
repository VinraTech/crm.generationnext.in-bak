<?php $__env->startSection('content'); ?>

<?php use App\EmployeeRole; ?>

<div class="page-content-wrapper">

    

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Employees's Management </h1>

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

        <?php if(Session::has('flash_message_success')): ?>

            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> <?php echo session('flash_message_success'); ?> </div>

        <?php endif; ?>

        <div class="row">

            <div class="col-md-12 ">

                <div class="portlet blue-hoki box ">

                    <div class="portlet-title">

                        <div class="caption">

                            <i class="fa fa-gift"></i><?php echo e($title); ?>


                        </div>

                    </div>

                    <div class="portlet-body form">

                        <form id="subadminForm" role="form" class="form-horizontal" method="post" action="<?php echo e(url('/s/admin/update-role/'.$employeeid)); ?>"> 

                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />

                            <div class="form-body">

                                <?php $__currentLoopData = $getModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php $getAccess = EmployeeRole::checkAccess($module['id'],$employeeid);

                                        $viewChecked = $getAccess['view']; 

                                        $editChecked = $getAccess['edit']; 

                                        $deleteChecked = $getAccess['delete'];

                                    ?>

                                    <input type="hidden" name="module_id[<?php echo e($module['id']); ?>]" value="<?php echo e($module['id']); ?>">

                                    <div class="form-group">

                                        <label class="col-md-3 control-label"><?php echo e($module['name']); ?>:</label>

                                        <div class="checkbox-list">

                                            <div class="col-md-9">

                                                <label class="checkbox-inline">

                                                <input type="checkbox" rel="<?php echo e($module['id']); ?>" id="view-<?php echo e($module['id']); ?>" data-attr="View" class="getModuleid" name="module_id[<?php echo e($module['id']); ?>][view_access]" value="1"<?php echo e($viewChecked); ?>> View Only </label>

                                                <label class="checkbox-inline">

                                                <?php if($module['edit_route'] !=""): ?>

                                                <input type="checkbox" rel="<?php echo e($module['id']); ?>" data-attr="Edit"  id="edit-<?php echo e($module['id']); ?>" class="getModuleid" name="module_id[<?php echo e($module['id']); ?>][edit_access]" value="1" <?php echo e($editChecked); ?> > View/Edit </label>

                                                <?php endif; ?>

                                                <?php if($module['delete_route'] !=""): ?>

                                                    <label class="checkbox-inline">

                                                    <input type="checkbox" rel="<?php echo e($module['id']); ?>" data-attr="Delete" id="delete-<?php echo e($module['id']); ?>" class="getModuleid" name="module_id[<?php echo e($module['id']); ?>][delete_access]" value="1" <?php echo e($deleteChecked); ?>> View/Edit/Delete</label>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div> 

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>               

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