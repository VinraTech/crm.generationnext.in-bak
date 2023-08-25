@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\Bank; ?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>File's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('/s/admin/create-applicants/'.$filedetails['id']) }}">Applicants</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i>{{$title}}
				        </div>
				    </div>
				    <div class="portlet-body form">
				        <form  class="form-horizontal" method="post" action="{{url('/s/admin/update-eligibility-details/'.$filedetails['id'])}}" autocomplete="off" enctype="multipart/form-data">@csrf
				        	<div class="form-body">
				                <div class="row">
					                <?php /* @if($filedetails['move_to'] == "operations" && (Session::get('empSession')['type'] !="salesmanager" || Session::get('empSession')['type'] !="sales"))*/?>
						                <div class="clearfix"></div>
						               	<div class="form-group col-md-12">
					                	    <label class="col-md-2 control-label">Upload Eligibilty File:</label>
					                	    <div class="col-md-4">
					                	        <input type="file" class="form-control" name="eligibility_file" style="color:gray">
					                	    </div>
					                	</div>
					                	@if(!empty($filedetails['eligibilityfiles']))
						                	<div class="form-group col-md-12">
						                	    <label class="col-md-2 control-label">Eligiblity Files:</label>
						                	    <div class="col-md-4">
						                	        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewEligiblityFiles" data-whatever="@mdo">View Eligibility Files</button>
						                	    </div>
						                	</div>
						                @endif
					                	@if($filedetails['file_status'] !=" Data Entry")
						                	<div class="form-group col-md-12">
						                	    <label class="col-md-2 control-label">History:</label>
						                	    <div class="col-md-4">
						                	        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ViewStatusDetails" data-whatever="@mdo">View History</button>
						                	    </div>
						                	</div>
						                @endif
					                	@if(empty($filedetails['filebanks']))
						                	<div class="clearfix"></div>
							               	<div class="form-group col-md-12">
						                	    <label class="col-md-2 control-label">File Status  :</label>
						                	    <div class="col-md-4">
						                	        <select class="form-control" id="getFileStatus" name="file_status">
						                	        	<option value="On Hold">On Hold</option>
						                	        	<?php $moveAccess = array('bm','opm'); ?>
						                	        	<?php $access = Employee::checkAccess(); ?>
						                	        	@if($access =="true" || in_array(Session::get('empSession')['type'],$moveAccess))
						                	        		<option value="Move to Bank">Move to Bank</option>
						                	        	@endif
						                	        </select>
						                	    </div>
						                	</div>
						                	<div class="clearfix"></div>
						                	<?php $banks = Bank::banks(); ?>
							               	<div class="form-group col-md-12" id="BankList" style="display: none;">
						                	    <label class="col-md-2 control-label">Select Banks :</label>
						                	    <div class="col-md-4">
						                	        <select class="selectpicker" data-live-search="true" class="form-control" name="banks[]" data-width="100%" data-size="5">
									        			@foreach($banks as $bank)
									        				<option value="{{$bank['id']}}">{{$bank['short_name']}}</option>
									        			@endforeach
									        		</select>
						                	    </div>
						                	</div>
						                	<div class="clearfix"></div>
						                	<div class="form-group col-md-12">
						                	    <label class="col-md-2 control-label">Enter Comments :</label>
						                	    <div class="col-md-4">
						                	        <textarea  placeholder="Enter Comments" name="comments" style="color:gray" autocomplete="off" class="form-control" required ></textarea>
						                	    </div>
						                	</div>
						                @endif
						            <?php /* @endif	*/?>
				                </div>
				            </div>
				            <div class="form-actions right1 text-center">
				                <button class="btn green" id="Save" name="save" value="Save" type="submit">Save</button>
				                <button class="btn green" id="SaveMove" style="display:none;" name="save" value="Move" type="submit">Save & Move to Bank</button>
				            </div>
				        </form>
				    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- View File Details -->
<div class="modal fade" id="ViewStatusDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">View File Details</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped text-center">
                	<thead>
						<tr>
							<th class="text-center">Status</th>
							<th class="text-center">Comments</th>
							<th class="text-center">Banks</th>
							<th class="text-center">Updated By</th>
							<th class="text-center">Updated Date & Time</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($filedetails['filestatus']))
							@foreach($filedetails['filestatus'] as $filestatus)
								<tr>
									<td>{{$filestatus['status']}}</td>
									<td>{{$filestatus['comments']}}</td>
									<td>
										@if($filestatus['status'] =="Move to Bank" && !empty($filedetails['filebanks']))
											@foreach($filedetails['filebanks'] as $filebank)
												{{$filebank['bankdetail']['short_name']}}, 
											@endforeach
										@else
											------------
										@endif
									</td>
									<?php $getname = Employee::getemployee($filestatus['updated_by']); ?>
									<td>{{$getname['name']}} - {{$getname['emptype']}}</td>
									<td>{{date('d M Y h:ia',strtotime($filestatus['created_at']))}}</td>
								</tr>
							@endforeach
						@endif
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
<!-- View Eligibility Details -->
<div class="modal fade" id="viewEligiblityFiles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">View Eligibility Details</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped text-center">
                	<thead>
						<tr>
							<th class="text-center">File</th>
							<th class="text-center">Created By</th>
						</tr>
					</thead>
					<tbody>
						@foreach($filedetails['eligibilityfiles'] as $eligiblefile )
							<tr>
								<td><a href="{{url('s/admin/download-eligibility/'.$eligiblefile['filename'])}}">{{$eligiblefile['filename']}}</a></td>
								<?php $getname = Employee::getemployee($eligiblefile['created_by']); ?>
								<td>{{$getname['name']}} - {{$getname['emptype']}}</td>

							</tr>
						@endforeach
					</tbody>
				</table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Eligibility Details -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#getFileStatus').change(function(){
			var status =$('#getFileStatus').val();
			if(status =="Move to Bank"){
				$('#BankList').show();
				$('#SaveMove').show();
				$('#Save').hide();
				$(".selectpicker").prop('required',true);
			}else{
				$('#Save').show();
				$('#SaveMove').hide();
				$('#BankList').hide();
				$(".selectpicker").prop('required',false);
			}
		})
	})
</script>
@stop