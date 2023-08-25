<?php $__env->startSection('content'); ?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Profile</h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo e(action('AdminController@dashboard')); ?>">Dashboard</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="profile-sidebar" style="width:250px;">
                    <div class="profile-sidebar" style="width: 250px;">
                        <?php echo $__env->make('layouts.adminLayout.profilesidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                </div>
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 col-sm-12">
                                <?php if(Session::has('flash_message_error')): ?>
                                <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Error!</strong> <?php echo session('flash_message_error'); ?> </div>
                                <?php endif; ?>
                                <?php if(Session::has('flash_message_success')): ?>
                                <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Success!</strong> <?php echo session('flash_message_success'); ?> </div>
                                <?php endif; ?>
                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Admin Information
                                        </div>
                                        <div class="actions">
                                            <a href="<?php echo e(action('AdminController@settings')); ?>" onClick="javascript:document.location.reload(true)" class="btn btn-default btn-sm">
                                            <i class="fa fa-pencil"></i> Edit </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Admin Name:
                                            </div>
                                            <div class="col-md-9 value">
                                                <?php if(!empty($admindata['name'])){
                                                    echo $admindata['name'];
                                                    } ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Email:
                                            </div>
                                            <div class="col-md-9 value">
                                                <?php if(!empty($admindata['email'])){
                                                    echo $admindata['email'];
                                                    } ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Mobile number:
                                            </div>
                                            <div class="col-md-9 value">
                                                <?php if(!empty($admindata['mobile'])){
                                                    echo $admindata['mobile'];
                                                    } ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Last Login: 
                                            </div>
                                            <div class="col-md-9 value">
                                                <?php if(!empty($admindata['last_login'])){
                                                    echo date('d F Y',strtotime($admindata['last_login']));
                                                    } ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Created At: 
                                            </div>
                                            <div class="col-md-9 value">
                                                <?php if(!empty($admindata['created_at'])){
                                                    echo date('d F Y',strtotime($admindata['created_at']));
                                                    } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>