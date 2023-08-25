@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\BankFileTracker;?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>File's Management</h1>
            </div>
        </div>
       <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a class="btn green" href="{{url('/s/admin/files?type=bank')}}">Back</a>
            </li>
        </ul>
         @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-green-sharp bold uppercase">File in Bank ({{$filedetails['file_no']}})</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                            </div>
                        </div> -->
                        <div class="table-container">
                        	@foreach($filedetails['filebanks'] as $bank)
								<table class="table table-striped table-bordered table-hover" >
									<caption><b>{{$bank['bankdetail']['short_name']}}</b></caption>
								    <thead>
								        <tr>
								        	@foreach($trackers as $tracker)
									        	<th>
									                <a href="javascript:;" data-bankid="{{$bank['bankdetail']['id']}}" data-type="{{$tracker['type']}}" class="updateBankStatus">{{ucwords($tracker['type_full_name'])}}</a>
									            </th>
								            @endforeach
								        </tr>
								    </thead>
								    <tbody>
								    	<tr>
									    	@foreach($trackers as $tracker)
									    		<?php $details = BankFileTracker::getdetails($tracker['type'],$bank['bankdetail']['id'],$filedetails['id']); ?>
									        	<td>{{$details}}</td>
								            @endforeach
								       	</tr>
								       	<tr>
									    	@foreach($trackers as $tracker)
									    		<td><a data-bankid="{{$bank['bankdetail']['id']}}" data-type="{{$tracker['type']}}" class="btn green bankStatusHistory" href="javascript:;">View History</a></td>
								            @endforeach
								       	</tr>
								    </tbody>
								</table>
							@endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            <form action="{{url('/s/admin/update-bank-file-status')}}" method="post">@csrf
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
@stop





