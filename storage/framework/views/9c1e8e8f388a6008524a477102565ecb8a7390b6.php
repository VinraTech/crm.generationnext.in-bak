<?php $__env->startSection('content'); ?>
<?php use App\FileDropdown; use App\Employee; use App\File; 
if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['is_access']=="full"){
	$clients = DB::table('clients')->where('status',1)->get();
	$clients = json_decode(json_encode($clients),true);
}else{
	$arr_id = Employee::empsiddata();
    $ch_id = array();
    $chdata = DB::table('channel_partners')->whereIn('emp_id',$arr_id)->get();
    $chdata = json_decode(json_encode($chdata),true);
    foreach($chdata as $chp){
    	array_push($ch_id, $chp['id']);
    }
    
	$clients = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->where('status',1)->get();
    $clients = json_decode(json_encode($clients),true);
    // dd($clients);

}
?>
<div class="page-content-wrapper">
<div class="page-content">
<div class="page-head">
<div class="page-title">
    <h1>File's Management </h1>
</div>
</div>
<ul class="page-breadcrumb breadcrumb">
<li>
    <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
    <i class="fa fa-circle"></i>
</li>
<li>
    <a href="<?php echo e(action('FileController@files')); ?>">Files</a>
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
	        <form  class="form-horizontal" method="get" action="<?php echo e(url('/s/admin/add-file')); ?>"><?php echo csrf_field(); ?>
	        	<div class="form-body">
	                <div class="row">
            			<div class="form-group">
                            <label class="col-md-3 control-label">Select Client:</label>
                            <div class="col-md-4">
                                <select name="client_id" class="selectpicker form-control getfileNo" required data-live-search="true" data-size="7" data-width="100%"> 

                                    <option value="">Select</option>
                                    
                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($client['id']); ?>" <?php if(isset($_GET['client_id']) && $_GET['client_id']==$client['id']): ?>  selected <?php endif; ?>><?php echo e($client['customer_name']); ?> (<?php echo e($client['company_name']); ?>-<?php echo e($client['mobile']); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                </select>
                            </div>
                       	</div>
                       	<?php if(isset($_GET['client_id']) && !empty($_GET['client_id'])): ?>
	                       	<div class="form-group">
	                            <label class="col-md-3 control-label">View Details:</label>
	                            <div class="col-md-4">
	                                <a target="_blank" data-toggle="modal" data-target="#ClientDetailModal" class="btn btn-sm green" style="margin-top: 7px;" href="javascript:;">View Details</a>
	                            </div>
	                       	</div>
	                    <?php endif; ?>
	                </div>
	            </div>
	            <div class="form-actions right1 text-center">
	                <button class="btn green" type="submit">Go</button>
	            </div>
	        </form>
	    </div>
	</div>
	<?php if($clientdetail): ?>	
		<div class="portlet-body">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="portlet blue-hoki box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i>Files of <?php echo e($clientdetail->customer_name); ?>

                            </div>
                            <a target="_blank" class="btn btn-sm grey pull-right" style="margin-top: 7px;" href="<?php echo e(url('/s/admin/generate-file/'.$clientdetail->id)); ?>">Generate new File for <?php echo e($clientdetail->customer_name); ?></a>
                        </div>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                File No
                                            </th>
                                            <th>
                                                Department
                                            </th>
                                            <th>
                                                Facility Type
                                            </th>
                                            <th>
                                                File Creation Date
                                            </th>
                                            <th>
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php if(!empty($clientfiles)): ?>
                                        <?php $__currentLoopData = $clientfiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><a target="_blank" title="View Details" class="btn btn-sm blue" href=<?php echo e(url('/s/admin/create-applicants/'.$file['id'].'?open=modal')); ?>><?php echo e($file['file_no']); ?></a></td>
                                                <td>
                                                    <?php echo e($file['department']); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($file['facility_type']); ?>

                                                </td>
                                                <td>
                                                <?php echo e(date('d M Y',strtotime($file['created_at']))); ?></td>
                                                <td><a target="_blank" title="View Details" class="btn btn-sm green" href="<?php echo e(url('/s/admin/generate-file/'.$file['client_id'].'?fileid='.$file['id'])); ?>">Copy</a></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center">
                                            No Files found.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
<?php if(isset($_GET['client_id']) && !empty($_GET['client_id'])): ?>
<div class="modal fade" id="ClientDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">View Client Details</h5>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-striped text-center">
			<tbody>
				<tr>
					<td>
						Client Id
					</td>
					<td>
						<?php echo e($clientdetail->client_id); ?>

					</td>
				</tr>
				<tr>
					<td>
						Company Name
					</td>
					<td>
						<?php echo e($clientdetail->company_name); ?>

					</td>
				</tr>
				<tr>
					<td>
						Applicant Name
					</td>
					<td>
						<?php echo e($clientdetail->name); ?>

					</td>
				</tr>
				<tr>
					<td>
						D.O.B
					</td>
					<td>
						<?php echo e($clientdetail->dob); ?>

					</td>
				</tr>
				<tr>
					<td>
						Co-Applicant Name
					</td>
					<td>
						<?php echo e($clientdetail->co_applicant_name); ?>

					</td>
				</tr>
				<tr>
					<td>
						Co-Applicant D.O.B
					</td>
					<td>
						<?php echo e($clientdetail->co_applicant_dob); ?>

					</td>
				</tr>
				<tr>
					<td>
						Email
					</td>
					<td>
						<?php echo e($clientdetail->email); ?>

					</td>
				</tr>
				<tr>
					<td>
						Mobile
					</td>
					<td>
						<?php echo e($clientdetail->mobile); ?>

					</td>
				</tr>
				<tr>
					<td>
						PAN
					</td>
					<td>
						<?php echo e($clientdetail->pan); ?>

					</td>
				</tr>
				<tr>
					<td>
						Sale Officer
					</td>
					<td>
						<?php $saleofficer = Employee::getemployee($clientdetail->sale_officer) ?>
						<?php echo e($saleofficer['name']); ?>

					</td>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>