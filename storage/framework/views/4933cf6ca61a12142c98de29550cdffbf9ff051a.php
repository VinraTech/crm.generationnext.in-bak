<?php $__env->startSection('content'); ?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<?php use App\Employee; use App\Bank;?>
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
                            <span class="caption-subject font-green-sharp bold uppercase"><?php echo e($heading); ?></span>
                        </div>
                        <?php $types =array('login','operations','bank','approved','declined');?>
                        <?php if(!isset($_GET['type'])): ?>
                            <div class="actions">
                                <div class="btn-group">
                                    <a href="<?php echo e(url('/s/admin/export-files/login')); ?>" class="btn btn-primary">Export Login Files</a>
                                </div>
                            </div>
                        <?php elseif(isset($_GET['type']) && in_array($_GET['type'],$types)): ?>

                            <div class="actions">
                                <div class="btn-group">
                                    <a href="<?php echo e(url('/s/admin/export-files/'.$_GET['type'])); ?>" class="btn btn-primary">Export  <?php echo e(ucwords($_GET['type'])); ?> Files</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="portlet-body">
                        <?php if($addfile=="yes"): ?>
                             <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group">
                                           <a href="<?php echo e(action('FileController@addFile')); ?>" class="btn btn-primary">Add File</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="10%">
                                            File No
                                        </th>
                                        <!-- <th width="10%">
                                            Department
                                        </th> -->
                                        <th width="15%">
                                            Applicant Name
                                        </th width="15%"> 
                                        <th>
                                           Company Name
                                        </th>
                                        <th>
                                            Loan Type
                                        </th>
                                        <th>
                                           Loan Amount
                                        </th>
                                        <!-- <th>
                                            Facility Type             
                                        </th> -->
                                        <th width="15%">
                                            Bank            
                                        </th>
                                        <!-- <th>
                                            Processing Fees(%)
                                        </th>
                                        <th>
                                            Process Fees
                                        </th>
                                        <th>
                                            Tenure ( in Months )
                                        </th>
                                        <th>
                                            EMI Start Date
                                        </th>
                                        <th>
                                            No of Installment. Paid
                                        </th>
                                        <th>
                                            No. if Installment. Balance
                                        </th>
                                        <th>
                                            EMI Amt
                                        </th>
                                        <th>
                                            Disbursement Type
                                        </th> -->
                                        <th width="15%">
                                          Sales Officer
                                        </th>
                                        <th width="15%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td><input type="text" class="form-control form-filter input-sm" name="file_no" placeholder="File No"></td>
                                        <!-- <td>
                                            <?php $departments = array('Mortgage','Car Loan','Business Loan') ?>
                                            <select class="form-control form-filter input-sm" name="department">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($department); ?>"><?php echo e($department); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td> -->
                                        <td><input type="text" class="form-control form-filter input-sm" name="name" placeholder="Applicant Name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="company_name" placeholder="Company Name"></td>
                                        <td>
                                            <?php $loantypes = array('Loan','Insurance') ?>
                                            <select class="form-control form-filter input-sm" name="loan_ins">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $loantypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $loantype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($loantype); ?>"><?php echo e($loantype); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td></td>
                                        <!-- <td><input type="text" class="form-control form-filter input-sm" name="facility_type" placeholder="Facility Type"></td> -->
                                        <?php $getallemps = Employee::getemployees('all') ?>
                                        <td>
                                            <?php $banks = Bank::banks(); ?>
                                            <select class="form-control form-filter input-sm" name="bank">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($bank['id']); ?>"><?php echo e($bank['short_name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <!-- <td><input type="number" class="form-control form-filter input-sm" name="processing_fees_percent" placeholder="Processing Fees%"></td>
                                        <td><input type="number" class="form-control form-filter input-sm" name="processing_fees_amount" placeholder="Processing Fees Amount"></td>
                                        <td><input type="number" class="form-control form-filter input-sm" name="tenure_in_months" placeholder="Tenure"></td>
                                        <td><div class="fullWidth">
                                            <input type="text"   required class="form-control dobDatepicker__table" name="emi_start_date" placeholder="Loan Start Date"/>
                                        </div></td>
                                        <td><input type="number" class="form-control form-filter input-sm" name="no_of_installment_paid" placeholder="Paid Installments"></td>
                                        <td><input type="number" class="form-control form-filter input-sm" name="no_of_installment_balance" placeholder="Balance Installments"></td>
                                        <td><input type="number" class="form-control form-filter input-sm" name="emi_amt" placeholder="Emi Amount"></td>
                                        <td><select class="form-control" name="disbursement_type" id="DisbsType" required>
                                                    <option value="">Please Select</option>
                                                    <option value="partially"  >Partially Disbursed</option>
                                                    <option value="disbursed" >Fully Disbursed</option>
                                                </select></td> -->
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
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>