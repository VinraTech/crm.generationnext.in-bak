<?php $__env->startSection('content'); ?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Banker's Management</h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
            </li>
        </ul>
         <?php if(Session::has('flash_message_error')): ?>
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> <?php echo session('flash_message_error'); ?> </div>
        <?php endif; ?>
        <?php if(Session::has('flash_message_success')): ?>
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> <?php echo session('flash_message_success'); ?> </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-green-sharp bold uppercase">Banker</span>
                            <span class="caption-helper">manage records...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <div class="btn green">
                                            <a href="<?php echo e(action('MasterController@addEditBank')); ?>" style="text-decoration:none; color:white">Add Banker</a> <i class="fa fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>
                                            No.
                                        </th>
                                        <th>
                                            Banker Name
                                        </th>
                                        <th>
                                            Bank Name
                                        </th>
                                        <th>
                                            RM Code
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th>
                                            Phone Number
                                        </th>
                                        <th>
                                            Address
                                        </th>
                                        <th>
                                            State
                                        </th>
                                        <th>
                                            District
                                        </th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="banker_name" placeholder="Banker Name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="bank_name" placeholder="Bank Name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="rm_code" placeholder="RM Code"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="email" placeholder="Email"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="phone_number" placeholder="Phone Number"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="address" placeholder="Address"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="state" placeholder="State"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="district" placeholder="District"></td>
                                        <td>
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i title="Search" class="fa fa-search"></i></button>
                                                <button class="btn btn-sm red filter-cancel"><i title="Reset" class="fa fa-refresh"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>