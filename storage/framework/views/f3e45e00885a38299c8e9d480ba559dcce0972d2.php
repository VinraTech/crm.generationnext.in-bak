<?php $__env->startSection('content'); ?>

<?php use App\FileDropdown; use App\Employee; use App\File;?>

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

				        <form  class="form-horizontal" method="get" action="<?php echo e(url('/s/admin/search-files')); ?>"><?php echo csrf_field(); ?>

				        	<div class="form-body">

				                <div class="row">

                        			<div class="form-group">

			                            <label class="col-md-3 control-label">Select Search Type:</label>

			                            <div class="col-md-4">

			                                <select name="search_by" class=" form-control"> 

			                                    <?php $searchByAarr = array('company'=>'Company Name','applicant'=>'Applicant Name','file_no'=>'File Number'); ?>

			                                    <?php $__currentLoopData = $searchByAarr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skey=> $search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			                                    	<option value="<?php echo e($skey); ?>" <?php if(isset($_GET['search_by'])  && $_GET['search_by']==$skey): ?> selected <?php endif; ?>><?php echo e($search); ?></option>

			                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

			                                </select>

			                            </div>

			                       	</div>

			                       	<div class="form-group">

	                                    <label class="col-md-3 control-label">Enter Search Query:</label>

	                                    <div class="col-md-4">

	                                        <input type="text" placeholder="Enter Search Query" name="search" style="color:gray" autocomplete="off" class="form-control" <?php if(isset($_GET['search'])): ?> value="<?php echo e($_GET['search']); ?>" <?php endif; ?>/>

	                                    </div>

	                                </div>

				                </div>

				            </div>

				            <div class="form-actions right1 text-center">

				                <button class="btn green" type="submit">Submit</button>

				            </div>

				        </form>

				    </div>

				</div>

				<?php if($files): ?>	

					<div class="portlet-body">

	                    <div class="row">

	                        <div class="col-md-12 col-sm-12">

	                            <div class="portlet blue-hoki box">

	                                <div class="portlet-title">

	                                    <div class="caption">

	                                        <i class="fa fa-cogs"></i>Searched Files

	                                    </div>

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

	                                                        Applicant Name

	                                                    </th>

	                                                    <th>

	                                                        Company Name

	                                                    </th>

	                                                    <th>

	                                                        Facility Type

	                                                    </th>

	                                                    <th>

	                                                        Loan Amt.

	                                                    </th>

	                                                    <th>

	                                                       Bank

	                                                    </th>

	                                                    <th>

	                                                        Login Date

	                                                    </th>

	                                                    <!-- <th>

	                                                        Sales Officer

	                                                    </th> -->

	                                                     <th>

	                                                        File Status

	                                                    </th>

	                                                    <th>

	                                                        Actions

	                                                    </th>

	                                                </tr>

	                                            </thead>

	                                            <tbody>

                                                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                	<?php 
                                                	// $salesofficer = Employee::getemployee($file['salesofficer']); 
                                                	?>

                                                    <tr>

                                                        <td><a target="_blank" title="View Details" class="btn btn-sm blue" href=<?php echo e(url('/s/admin/create-applicants/'.$file['id'].'?open=modal')); ?>><?php echo e($file['file_no']); ?></a></td>

                                                        <td>

                                                            <?php echo e($file['department']); ?>


                                                        </td>

                                                        <td>

                                                            <?php echo e($file['client_name']); ?>


                                                        </td>

                                                        <td>

                                                            <?php echo e($file['company_name']); ?>


                                                        </td>

                                                        <td>

                                                            <?php echo e($file['loan_type']); ?>


                                                        </td>

                                                        <td>

                                                            <?php 
                                                            // echo File::getLoanAmt($file);
                                                            ?>
                                                            <?php echo e($file['loan_amount']); ?>


                                                        </td>

                                                        <td>

                                                        	<?php if(!empty($file['getbank'])): ?>

                                                        		<?php echo e($file['getbank']['bankdetail']['short_name']); ?>


                                                        	<?php else: ?>

                                                        		Not Moved yet

                                                        	<?php endif; ?>

                                                        </td>

                                                        <td>

                                                        <?php echo e(date('d M Y h:ia',strtotime($file['created_at']))); ?></td>

                                                        

                                                        <td><?php echo e(ucwords($file['move_to'])); ?></td>

                                                        <td><a data-fileid="<?php echo e($file['id']); ?>" title="View Summary" class="btn btn-sm green getSummary" href="javascript:;">View Summary</a>

                                                        <?php if($file['move_to'] == "partially" || $file['move_to'] == "disbursement"): ?>

                                                        	<a target="_blank" title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="<?php echo e(url('/s/admin/update-disbursement-details/'.$file['id'])); ?>">Update Disbursement</a>

                                                        <?php endif; ?>

                                                        <?php if($file['move_to'] == "partially"): ?>

                                                        	<a title="Export History" class="btn btn-sm blue margin-top-10" href="<?php echo e(url('s/admin/export-partially-files/'.$file['id'])); ?>">Export History</a>

                                                        <?php endif; ?>

                                                        <?php if($file['move_to'] == "disbursement"): ?>

                                                        	<a title="Export History" class="btn btn-sm blue margin-top-10" href="<?php echo e(url('/s/admin/export-file-history/'.$file['id'])); ?>">Export History</a>

                                                        <?php endif; ?>

                                                    	</td>

                                                    </tr>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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

<!-- summary Details -->

<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span>

                </button>

                <h5 class="modal-title" id="exampleModalLabel">File Summary Details</h5>

            </div>

            <div class="modal-body">

                <table class="table table-bordered table-striped text-center">

	                <tbody id="AppendsummaryData">

	                	

	                </tbody>

                </table>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>

<!-- summary Details -->

<script type="text/javascript">

	$(document).ready(function(){

		$(document).on('click','.getSummary',function(){

			$('.loadingDiv').show();

			var fileid = $(this).data('fileid');

			$.ajax({

				url : '/s/admin/get-file-summary',

				data : {fileid:fileid},

				type : 'post',

				success:function(resp){

					$('#AppendsummaryData').html(resp);

					$('#summaryModal').modal('show');

					$('.loadingDiv').hide();

				},

				error:function(){

					alert('error');

				}

			})

		})

	})

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>