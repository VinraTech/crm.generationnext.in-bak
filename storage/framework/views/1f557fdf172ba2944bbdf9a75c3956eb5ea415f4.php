<?php $__env->startSection('content'); ?>
<?php
use App\Bank;

$allbank = Bank::orderby('full_name','asc')->get();
?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Bank Management</h1>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Bank</span>
                            <span class="caption-helper">manage records...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <?php if($bank_access['edit_access'] == 1): ?>
						<div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <div class="btn green">
                                            <a href="<?php echo e(action('MasterController@addEditBanks')); ?>" style="text-decoration:none; color:white">Add Bank</a> <i class="fa fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php endif; ?>
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>
                                            No.
                                        </th>
                                        <th>
                                            Full Name
                                        </th>
                                        <th>
                                            Short Name
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td></td>
                                        <td>
                                            <select name="full_name" id="full_name" class="select form-filter input-sm">
                                                <option value="">Select</option>
                                                <?php foreach($allbank as $bank) { ?>
                                                <option value="<?php echo $bank->id; ?>"><?php echo $bank->full_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <!-- <input type="text" class="form-control form-filter input-sm" name="full_name" placeholder="Name"> -->
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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