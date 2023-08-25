<?php $__env->startSection('content'); ?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<?php use App\Employee; use App\Bank; ?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>File's Management</h1>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Disbursement Files</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                                <button data-toggle="modal" data-target="#FileExportModal" class="btn btn-primary">Click to Export Data</button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="10%">
                                            File No
                                        </th>
                                        <!-- <th width="15%">
                                            LAN No.
                                        </th>
                                        <th width="15%">
                                            Department
                                        </th> -->
                                        <th width="10%">
                                           Applicant
                                        </th>
                                        <th width="10%">
                                           Company 
                                        </th>
                                        <th width="20%">
                                           Amount
                                        </th>
                                        <th>
                                           Facility Type
                                        </th>
                                        <th width="15%">
                                           Bank
                                        </th>
                                        <th>
                                          Sales Officer
                                        </th>
                                        <th width="15%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td><input type="text" class="form-control form-filter input-sm" name="file_no" placeholder="File No"></td>
                                        <!-- <td></td> -->
                                        <!-- <td>
                                            <?php $departments = array('Mortgage','Car Loan','Business Loan') ?>
                                            <select class="form-control form-filter input-sm" name="department">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($department); ?>"><?php echo e($department); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td> -->
                                        <td><input type="text" class="form-control form-filter input-sm" name="name" placeholder="Client Name" <?php if(isset($_GET['customer_name'])): ?> value="<?php echo e($_GET['customer_name']); ?>" <?php endif; ?>></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="company_name" placeholder="Company Name" ></td>
                                        <td></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="facility_type" placeholder="Facility Type"></td>
                                        <td>
                                            <?php $banks = Bank::banks(); ?>
                                            <select class="form-control form-filter input-sm" name="bank">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($bank['id']); ?>"><?php echo e($bank['short_name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <?php $getallemps = Employee::getemployees('all') ?>
                                        <td>
                                            <select class="form-control form-filter input-sm" name="salesofficer">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $getallemps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $getemp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($getemp['id']); ?>"><?php echo e($getemp['name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
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
<div class="modal fade" id="FileExportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="exampleModalLabel">Export Files Data</h5>
            </div>
            <div class="modal-body">
            <form  action="<?php echo e(url('s/admin/export-file-history')); ?>" method="post"><?php echo e(csrf_field()); ?>

                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Select Year:</label>
                    <select name="year" class="form-control">
                        <?php for($i=2018; $i <= date('Y');$i++): ?>
                            <option value="<?php echo e($i); ?>" <?php if($i== date('Y')): ?> selected <?php endif; ?>><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Select Month:</label>
                    <select name="month" class="form-control">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <?php  $month = date('F', mktime(0,0,0,$m, 1, date('Y')));?>
                            <option value="<?php echo e($m); ?>" <?php if($m==  date('m')): ?> selected <?php endif; ?>><?php echo e($month); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>