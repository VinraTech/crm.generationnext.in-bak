<?php use App\BankFileTracker;?>
<?php if(!empty($filedetails['filebanks'])): ?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<div class="table-container">
	<?php $__currentLoopData = $filedetails['filebanks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<table class="table table-striped table-bordered table-hover" >
			<caption><b>Bank Tracker - <?php echo e($bank['bankdetail']['short_name']); ?></b></caption>
		    <thead>
		        <tr>
		        	<?php $__currentLoopData = $trackers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tracker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			        	<th>
			        		<?php if($filedetails['move_to'] =="disbursement"): ?>
			                	<h5><b><?php echo e(ucwords($tracker['type_full_name'])); ?></b></h5>
			                <?php else: ?>
			                	<a href="javascript:;" data-bankid="<?php echo e($bank['bankdetail']['id']); ?>" data-type="<?php echo e($tracker['type']); ?>" class="updateBankStatus"><?php echo e(ucwords($tracker['type_full_name'])); ?></a>
			                <?php endif; ?>
			            </th>
		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		        </tr>
		    </thead>
		    <tbody>
		    	<tr>
			    	<?php $__currentLoopData = $trackers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tracker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			    		<?php $details = BankFileTracker::getdetails($tracker['type'],$bank['bankdetail']['id'],$filedetails['id']); ?>
			        	<td><?php echo e($details); ?></td>
		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		       	</tr>
		       	<tr>
			    	<?php $__currentLoopData = $trackers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tracker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			    		<td><a data-bankid="<?php echo e($bank['bankdetail']['id']); ?>" data-type="<?php echo e($tracker['type']); ?>" class="btn green bankStatusHistory" href="javascript:;">View History</a></td>
		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		       	</tr>
		    </tbody>
		</table>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<!-- VBank Form Modal -->
<div class="modal fade" id="BankFormdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
            </div>
            <form action="<?php echo e(url('/s/admin/update-bank-file-status')); ?>" method="post"><?php echo csrf_field(); ?>
	            <div class="modal-body" id="AppendFormdata">
	                
	            </div>
	            <div class="modal-footer">
	                <button type="submit" class="btn btn-primary">Submit</button>
	            </div>
	        </form>
        </div>
    </div>
</div>
<!-- View Bank File History Details -->
<div class="modal fade" id="BankHistoryModal" tabindex="-1" role="dialog" aria-labelledby="ViewHistoryText" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="ViewHistoryText">View History</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped text-center" id="AppendBankFileHistory">
                	
				</table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Bank File History Details -->
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','.updateBankStatus',function(){
			$('.loadingDiv').show();
			var type = $(this).data('type');
			var fileid = "<?php echo $filedetails['id']; ?>";
			var bankid = $(this).data('bankid');
			$.ajax({
				data : {type : type, fileid: fileid,bankid:bankid},
				url : '/s/admin/append-file-status-form',
				type : 'post',
				success:function(resp){
					$('#exampleModalLabel').html("Update Status ("+resp['type']+")");
					$('#AppendFormdata').html(resp['appendform']);
					$('#BankFormdata').modal('show');
					$('.loadingDiv').hide();
				},
				error:function(){

				}
			})
		})

		$(document).on('change','#getStatus',function(){
			$('.loadingDiv').show();
	    	var selected = $(this).find('option:selected');
	       	var isdate = selected.data('isdate'); 
	       	var isamount = selected.data('isamount'); 
	       	if(isdate =="yes"){
	       		var dateinput = '<div class="form-group"><label class="col-form-label">Select Date:</label><div class="input-group input-append date datePicker"><input type="text" class="form-control" placeholder="Select Date" name="date" autocomplete="off" required><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div>';
	       		$('#AppendDateData').html(dateinput);
	       		$('.datePicker').datetimepicker({
			        format:'YYYY-MM-DD',
			        useCurrent: false,
			        allowInputToggle: true
			    });
	       	}else{
	       		$('#AppendDateData').html('');
	       	}

	       	if(isamount=="yes"){
	       		var amountinput = '<div class="form-group"><label class="col-form-label">Enter Amount:</label><input type="number" class="form-control" placeholder="Enter Amount" name="amount" required></div>';
	       		$('#AppendAmountData').html(amountinput);
	       	}else{
	       		$('#AppendAmountData').html('')
	       	}
	       	$('.loadingDiv').hide();
	    });

	    $(document).on('click','.bankStatusHistory',function(){
	    	$('.loadingDiv').show();
	    	var type = $(this).data('type');
			var fileid = "<?php echo $filedetails['id']; ?>";
			var bankid = $(this).data('bankid');
			$.ajax({
				data : {type : type,fileid:fileid,bankid:bankid},
				url : '/s/admin/get-bank-file-history',
				type : 'post',
				success:function(resp){
					$('#ViewHistoryText').html("View History of "+ resp['type']);
					$('#AppendBankFileHistory').html(resp['appendData']);
					$('#BankHistoryModal').modal('show');
					$('.loadingDiv').hide();
				},
				error:function(){
				}
			})
	    });
	})
</script>
<?php endif; ?>




