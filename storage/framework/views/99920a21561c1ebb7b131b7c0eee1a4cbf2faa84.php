<?php $__env->startSection('content'); ?>
<?php use App\FileDropdown; use App\Employee; use App\ChannelPartner; use App\Client; use App\FileLoanDetail; use App\FileApproval;
$fileappr = FileApproval::where('file_id',$filedetails['id'])->get();
$fileappr = json_decode(json_encode($fileappr),true);

?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<?php $notaccess = array('sales','salesmanager'); ?>
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
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?php echo e(action('FileController@files')); ?>">Files</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a class="btn green" href="<?php echo e(action('FileController@files')); ?>">Back</a>
            </li>
        </ul>
         <?php if(Session::has('flash_message_error')): ?>
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> <?php echo session('flash_message_error'); ?> </div>
        <?php endif; ?>
        <?php if(Session::has('flash_message_success')): ?>
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> <?php echo session('flash_message_success'); ?> </div>
        <?php endif; ?>
         <?php
            $dat = DB::table('files')->where('id',$filedetails['id'])->first();
            $dat = json_decode(json_encode($dat),true);

         ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-green-sharp bold uppercase">Create Applicants (<?php echo e($filedetails['file_no']); ?>)</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                               <b>File Login Date &amp; Time : <?php echo e(date('d F Y h:ia',strtotime($filedetails['created_at']))); ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                         <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group">
                                    	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ViewFileData">View File Details</button>
                                    </div>
                                    <!-- <?php if($applicantAccess == "yes"): ?>
	                                    <div class="btn-group">
	                                    	<a href="<?php echo e(url('/s/admin/add-individual-applicant/'.$filedetails['id'])); ?>" class="btn btn-primary">Add Indvidual Applicant</a>
	                                    </div>
	                                   
	                                  
	                                <?php endif; ?> -->
	                                
                                </div>
                          		<div class="clearfix"></div><br>
                                <div class="col-md-12">
                                	<!-- <?php if($filedetails['move_to'] !="login"): ?>
                                		<div class="btn-group">
		                                    <a href="<?php echo e(url('s/admin/update-eligibility-details/'.$filedetails['id'])); ?>" class="btn btn-primary">Eligibility Details</a>
		                                </div>
	                                <?php endif; ?> -->
	                                <?php if($applicantAccess == "yes"): ?>
	                                	<div class="btn-group">
	                                    	<a href="<?php echo e(url('s/admin/add-loan-details/'.$filedetails['id'])); ?>" class="btn btn-primary">Add Loan Details</a>
	                                    </div>
	                                <?php endif; ?>
	                                <?php if($dat['move_to'] == "approved" || $dat['move_to'] == "partially" || $dat['move_to'] == "disbursement"): ?> 
	                                  <div class="btn-group">
	                                    	<a href="<?php echo e(url('s/admin/add-bank-details/'.$filedetails['id'])); ?>" class="btn btn-primary">Add Bank Details</a>
	                                   </div>
	                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                       

                        <!-- <div class="table-container">
							<table class="table table-striped table-bordered table-hover" >
								  <caption><b>Individual Applicants</b></caption>
							    <thead>
							        <tr>
							        	<th>
							                Sr No.
							            </th>
							            <th>
							                Name
							            </th>
							            <th>
							               UID
							            </th>
							            <th>
							               Residental Status
							            </th>
							            <th>
							               Nationality
							            </th>
							            <th>
							               Occupation
							            </th>
							            <th>
							               Tel No
							            </th>
							            <th>
							               Mobile No
							            </th>
							            <th>
							               Actions
							            </th>
							        </tr>
							        
							        <?php if(isset($getIndApplicants) && $getIndApplicants != ''){?>
								    <tbody>
								    	<?php $__currentLoopData = $getIndApplicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ikey=> $iapplicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									    	<tr>
									    		<td><?php echo e(++$ikey); ?></td>
									    		<td><?php echo e($iapplicant['name']); ?></td>
									    		<td><?php echo e($iapplicant['uid']); ?></td>
									    		<td><?php echo e($iapplicant['residental_status']); ?></td>
									    		<td><?php echo e($iapplicant['nationality']); ?></td>
									    		<td><?php echo e($iapplicant['occupation']); ?></td>
									    		<td><?php echo e($iapplicant['tel_no']); ?></td>
									    		<td><?php echo e($iapplicant['mobile_no']); ?></td>
									    		<td>
									    			<?php if($applicantAccess == "yes"): ?>
									    				<a title="Edit Applicant" class="btn btn-sm green" href="<?php echo e(url('/s/admin/add-individual-applicant/'.$filedetails['id'].'/'.$iapplicant['id'])); ?>"> <i class="fa fa-edit"></i></a>
									    				<!-- <a title="Financial Details" class="btn btn-sm blue UpdateFinancial" href="javascript:;" data-type="individual" data-fileid="<?php echo e($filedetails['id']); ?>" data-applicantid="<?php echo e($iapplicant['id']); ?>"> <i class="fa fa-plus"></i></a> -->
									    				<!-- <?php if($filedetails['move_to'] !="disbursement"): ?>
									    					<a  onclick=" return ConfirmDelete()"; title="Delete Individual Applicant" class="btn btn-sm red" href="<?php echo e(url('/s/admin/delete-applicant/individual/'.$iapplicant['id'])); ?>"> <i class="fa fa-times"></i></a>
									    				<?php endif; ?> -->
									    			<!-- <?php endif; ?>
									    		</td>
									    	</tr>
									    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									   
								    </tbody>
								     <?php }?>
							</table>
                        </div>  -->
                      
                      
                       
                        <?php $statusNotallowed = array('login','operations','bank') ?>
                        <?php if(!in_array($filedetails['move_to'] , $statusNotallowed)): ?>
                        
                        	<hr>
                       		<?php echo $__env->make('layouts.adminLayout.bank-tracker', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                       	<?php endif; ?>
                       
                    </div>
                </div>
            	<div class="form-actions right1 text-center">

            		<?php if($filedetails['move_to'] == "operations"): ?>
            		<?php $__currentLoopData = $fileappr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            		<a title="Approve & Move to Bank" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="<?php echo e(url('/s/admin/approve-move-file/'.$fileap['id'])); ?>">Approve</a>
            		<a title="Decline & Move to Decline Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="<?php echo e(url('/s/admin/decline-move-file/'.$fileap['id'])); ?>">Decline</a>
            		<!-- <a title="Move to WIP Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="<?php echo e(url('/s/admin/wip-move-file/'.$fileap['id'])); ?>">Move to WIP Files</a> -->
            		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            		<?php endif; ?>


            		<?php if($filedetails['move_to'] == "login"): ?>
            		<?php if(count($fileloandetails) == 0): ?>
            		<div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> You cannot move to pending approval because you have not added Loan Details</div>
                	<a href="<?php echo e(url('/s/admin/create-applicants/'.$filedetails['id'])); ?>"  class="btn btn-primary" id="save_and_move_to_operations" onclick="Goto_Form_submit()"  >Save and Move to Operations</a>
                	<?php else: ?>
                	<a href="<?php echo e(url('/s/admin/pending-approvals')); ?>"  class="btn btn-primary">Save and Move to Operations</a>
                	<?php endif; ?>
                	<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View File Details -->

<?php
$emp_source = array();

foreach($emp as $emp_id){
 
 $source = Employee::getemployee($emp_id);

  array_push($emp_source, $source['name']);
 }
 
  ?>


<div class="modal fade" id="ViewFileData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">View File Details</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped text-left">
					<tbody>
						<tr>
							<td>
								 File Number
							</td>
							<td>
								<?php if(isset($filedetails['file_no'])): ?>
								<?php echo e($filedetails['file_no']); ?>

								<?php endif; ?>
							</td>
						</tr>
						<?php $clientdetails = Client::clientdetails($filedetails['client_id']) ?>
						<tr>
							<td>
								 Applicant Name
							</td>
							<td>
								<?php echo e($clientdetails['customer_name']); ?>

							</td>
						</tr>
						<tr>
							<td>
								 Company Name
							</td>
							<td>
								<?php echo e($clientdetails['company_name']); ?>

							</td>
						</tr>
						<tr>
							<td>
								 Mobile
							</td>
							<td>
								<?php echo e($clientdetails['mobile']); ?>

							</td>
						</tr>
						<tr>
							<td>
								 PAN
							</td>
							<td>
								<?php if(!empty($clientdetails['pan'])): ?>
								  <?php echo e($clientdetails['pan']); ?>

								<?php else: ?>
								   Applied for PAN
								<?php endif; ?>
							</td>
						</tr>
						<!-- <tr>
							<td>
								 Department
							</td>
							<td>
								<?php echo e($filedetails['department']); ?>

							</td>
						</tr> -->
						<tr>
							<td>
								 Loan Type
							</td>
							<td>
								<?php echo e($filedetails['loan_type']); ?>

							</td>
						</tr>
						<tr>
							<td>
								 Amount Requested (Loan Amt.)
							</td>
							<td>
								<?php if(!empty($filedetails['loan_amount'])): ?>
									<b>Rs <?php echo e(FileLoanDetail::format($filedetails['loan_amount'])); ?></b>
								<?php else: ?>
									Not Entered Yet
								<?php endif; ?>
							</td>
						</tr>
						<?php $directTypes = Employee::gettypes('direct'); ?>
						<?php $__currentLoopData = $directTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dtype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php $emp = Employee::empdetail($filedetails['id'],$dtype['short_name']); ?>
							<tr>
								<td>
									<?php echo e($dtype['full_name']); ?>

								</td>
								<td>
									<?php if($dtype['short_name'] != 'chp'): ?>
										<?php echo e($emp); ?>

									<?php else: ?>
									<?php
									$channel_partner = ChannelPartner::where('id',$clientdetails['channel_partner'])->first();
									?>
									<?php echo e($channel_partner['name']); ?>

									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  
						
						<tr>
							<td>
								Source
							</td>
							
							<td>
                                <?php $__currentLoopData = $emp_source; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo e($emps); ?><br>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</td>
							
						</tr>
						
						<!-- <tr>
							<td>
								File Type
							</td>
							<td>
								<?php if(isset($filedetails['file_type'])): ?>
								<?php echo e(ucwords($filedetails['file_type'])); ?>

								<?php endif; ?>
							</td>
						</tr> -->
						<!-- <?php if(isset($filedetails['file_type']) && ($filedetails['file_type'] =="indirect" || $filedetails['file_type'] =="other") ): ?>
							<?php $indirectTypes = Employee::gettypes('indirect'); ?>
							<?php $__currentLoopData = $indirectTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indkey => $indirect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php $emp = Employee::empdetail($filedetails['id'],$indirect['short_name']); ?>
								<tr>
									<td>
										<?php echo e($indirect['full_name']); ?>

									</td>
									<td>
										<?php echo e($emp); ?>

									</td>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php if(isset($filedetails['file_type']) && ($filedetails['file_type'] =="indirect")): ?>
								<?php $partner = ChannelPartner::partnerdetail($filedetails['channel_partner_id']) ?>
								<tr>
									<td>
										Channel Partner
									</td>
									<td>
										<?php echo e($partner['name']); ?> - <?php echo e($partner['type']); ?>

									</td>
								</tr>
							<?php endif; ?>
						<?php endif; ?> -->
						<!-- <tr>
							<td>
								LTS Number
							</td>
							<td>
								<?php echo e($filedetails['lts_no']); ?>

							</td>
						</tr> -->
						<tr>
							<td>
								Pan Card
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['pan_card'])); ?>">Click here to view your PAN Card</a>
							</td>
						</tr>
						<tr>
							<td>
								Adhaar Card
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['adhaar_card'])); ?>">Click here to view your Adhaar Card</a>
							</td>
						</tr>
						<tr>
							<td>
								Salary Slip
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['salary_slip'])); ?>">Click here to view your Salary Slip</a>
							</td>
						</tr>
						<tr>
							<td>
								Bank Passbook
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['bank_passbook'])); ?>">Click here to view your Bank Passbook</a>
							</td>
						</tr>
						<tr>
							<td>
								Voter Id
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['voter_id'])); ?>">Click here to view your Voter Id</a>
							</td>
						</tr>
						<tr>
							<td>
								Passport
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['passport'])); ?>">Click here to view your Passport</a>
							</td>
						</tr>
						<tr>
							<td>
								Driving Licence
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['driving_licence'])); ?>">Click here to view your Driving Licence</a>
							</td>
						</tr>
						<tr>
							<td>
								Rent Agreement
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['rent_agreement'])); ?>">Click here to view your Rent Agreement</a>
							</td>
						</tr>
						<tr>
							<td>
								Letterhead
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['letterhead'])); ?>">Click here to view your Letterhead</a>
							</td>
						</tr>
						<tr>
							<td>
								Photo
							</td>
							<td>
								<a target="_blank" href="<?php echo e(asset('images/FileDetails/'.$filedetails['photo'])); ?>">Click here to view your Photo</a>
							</td>
						</tr>
						<tr>
							<td>
								Remarks
							</td>
							<td>
								<?php echo e($filedetails['remarks']); ?>

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
<!-- View File Details -->

<!-- Valuation Modal -->
<div class="modal fade" id="ValuationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Enter Valuation Details</h5>
            </div>
            <form action="<?php echo e(url('/s/admin/update-valuations')); ?>" method="post" autocomplete="off"><?php echo csrf_field(); ?>
	            <div class="modal-body">
	                <table class="table table-bordered table-striped text-center">
	                	<thead>
	                		<th width="30%">Bank</th>
	                		<th>Val-1</th>
	                		<th>Val-2</th>
	                	</thead>
		                <tbody id="AppendValuationModal">
		                	
		                </tbody>
	                </table>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Submit</button>
	            </div>
            </form>
        </div>
    </div>
</div>
<!-- Valuation Modal -->
<!-- Valuation Modal -->
<div class="modal fade" id="AssetValuationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Enter Valuation Details</h5>
            </div>
            <form action="<?php echo e(url('/s/admin/update-asset-valuations')); ?>" method="post" autocomplete="off"><?php echo csrf_field(); ?>
	            <div class="modal-body">
	                <table class="table table-bordered table-striped text-center">
	                	<thead>
	                		<th width="30%">Bank</th>
	                		<th>Val-1</th>
	                	</thead>
		                <tbody id="AppendAssetValuationModal">
		                	
		                </tbody>
	                </table>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Submit</button>
	            </div>
            </form>
        </div>
    </div>
</div>
<!-- Valuation Modal -->
<!-- Financial Details -->
<div class="modal fade" id="FinancialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Update Financial Details</h5>
            </div>
            <form action="<?php echo e(url('/s/admin/update-applicant-financial-details')); ?>" method="post" autocomplete="off"><?php echo csrf_field(); ?>
	            <div class="modal-body">
	                <table class="table table-bordered table-striped text-center">
	                	<thead>
	                		<th width="30%" class="text-center">Heading</th>
	                		<th class="text-center">Last Year</th>
	                		<th class="text-center">Previous Year</th>
	                		<th class="text-center">Gst Return</th>
	                	</thead>
		                <tbody id="AppendFinancialData">
		                	
		                </tbody>
	                </table>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Submit</button>
	            </div>
            </form>
        </div>
    </div>
</div>
<!-- Financial Details -->

<script type="text/javascript">
	<?php if(isset($_GET['open'])){?>
		$('#ViewFileData').modal('show');
	<?php }?>
	$(document).ready(function(){
		$(document).on('click','.updateValuations',function(){
			$('.loadingDiv').show();
			var fileid = $(this).data('fileid');
			var propertyid = $(this).data('propertyid');
			$.ajax({
				data : {fileid : fileid,propertyid: propertyid},
				url : '/s/admin/update-valuations',
				type  : 'post',
				success:function(resp){
					$('#AppendValuationModal').html(resp);
					$('#ValuationModal').modal('show');
					$('.loadingDiv').hide();
				},
				error:function(){
					alert('Error');
				}
			})
		})

		$(document).on('click','.updateAssetValuations',function(){
			$('.loadingDiv').show();
			var fileid = $(this).data('fileid');
			var assetid = $(this).data('assetid');
			$.ajax({
				data : {fileid : fileid,assetid: assetid},
				url : '/s/admin/update-asset-valuations',
				type  : 'post',
				success:function(resp){
					$('#AppendAssetValuationModal').html(resp);
					$('#AssetValuationModal').modal('show');
					$('.loadingDiv').hide();
				},
				error:function(){
					alert('Error');
				}
			})
		})

		$(document).on('click','.UpdateFinancial',function(){
			$('.loadingDiv').show();
			var type = $(this).data('type');
			var applicantid = $(this).data('applicantid');
			var fileid = $(this).data('fileid');
			$.ajax({
				data : {type: type,applicantid:applicantid,fileid:fileid},
				type : 'post',
				url : '/s/admin/update-applicant-financial-details',
				success:function(resp){
					$('#AppendFinancialData').html(resp);
					$('#FinancialModal').modal('show');
					$('.loadingDiv').hide();
				},
				error:function(){
					alert('Error');
				}
			})
		})
		
	})
function Goto_Form_submit(){ 
	$(".loadingDiv").show();
	return true;
}
</script>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>