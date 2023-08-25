@extends('layouts.adminLayout.backendLayout')
@section('content')
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
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('FileController@files') }}">Files</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a class="btn green" href="{{ action('FileController@files') }}">Back</a>
            </li>
        </ul>
         @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
         <?php
            $dat = DB::table('files')->where('id',$filedetails['id'])->first();
            $dat = json_decode(json_encode($dat),true);

         ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-green-sharp bold uppercase">Create Applicants ({{$filedetails['file_no']}})</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                               <b>File Login Date &amp; Time : {{date('d F Y h:ia',strtotime($filedetails['created_at']))}}</b>
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
                                    <!-- @if($applicantAccess == "yes")
	                                    <div class="btn-group">
	                                    	<a href="{{url('/s/admin/add-individual-applicant/'.$filedetails['id'])}}" class="btn btn-primary">Add Indvidual Applicant</a>
	                                    </div>
	                                   
	                                  
	                                @endif -->
	                                
                                </div>
                          		<div class="clearfix"></div><br>
                                <div class="col-md-12">
                                	<!-- @if($filedetails['move_to'] !="login")
                                		<div class="btn-group">
		                                    <a href="{{url('s/admin/update-eligibility-details/'.$filedetails['id'])}}" class="btn btn-primary">Eligibility Details</a>
		                                </div>
	                                @endif -->
	                                @if($applicantAccess == "yes")
	                                	<div class="btn-group">
	                                    	<a href="{{url('s/admin/add-loan-details/'.$filedetails['id'])}}" class="btn btn-primary">Add Loan Details</a>
	                                    </div>
	                                @endif
	                                @if($dat['move_to'] == "approved" || $dat['move_to'] == "partially" || $dat['move_to'] == "disbursement") 
	                                  <div class="btn-group">
	                                    	<a href="{{url('s/admin/add-bank-details/'.$filedetails['id'])}}" class="btn btn-primary">Add Bank Details</a>
	                                   </div>
	                                @endif
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
								    	@foreach($getIndApplicants as $ikey=> $iapplicant)
									    	<tr>
									    		<td>{{++$ikey}}</td>
									    		<td>{{$iapplicant['name']}}</td>
									    		<td>{{$iapplicant['uid']}}</td>
									    		<td>{{$iapplicant['residental_status']}}</td>
									    		<td>{{$iapplicant['nationality']}}</td>
									    		<td>{{$iapplicant['occupation']}}</td>
									    		<td>{{$iapplicant['tel_no']}}</td>
									    		<td>{{$iapplicant['mobile_no']}}</td>
									    		<td>
									    			@if($applicantAccess == "yes")
									    				<a title="Edit Applicant" class="btn btn-sm green" href="{{url('/s/admin/add-individual-applicant/'.$filedetails['id'].'/'.$iapplicant['id'])}}"> <i class="fa fa-edit"></i></a>
									    				<!-- <a title="Financial Details" class="btn btn-sm blue UpdateFinancial" href="javascript:;" data-type="individual" data-fileid="{{$filedetails['id']}}" data-applicantid="{{$iapplicant['id']}}"> <i class="fa fa-plus"></i></a> -->
									    				<!-- @if($filedetails['move_to'] !="disbursement")
									    					<a  onclick=" return ConfirmDelete()"; title="Delete Individual Applicant" class="btn btn-sm red" href="{{url('/s/admin/delete-applicant/individual/'.$iapplicant['id'])}}"> <i class="fa fa-times"></i></a>
									    				@endif -->
									    			<!-- @endif
									    		</td>
									    	</tr>
									    @endforeach
									   
								    </tbody>
								     <?php }?>
							</table>
                        </div>  -->
                      
                      
                       
                        <?php $statusNotallowed = array('login','operations','bank') ?>
                        @if(!in_array($filedetails['move_to'] , $statusNotallowed))
                        
                        	<hr>
                       		@include('layouts.adminLayout.bank-tracker')
                       	@endif
                       
                    </div>
                </div>
            	<div class="form-actions right1 text-center">

            		@if($filedetails['move_to'] == "operations")
            		@foreach($fileappr as $fileap)
            		<a title="Approve & Move to Bank" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="{{url('/s/admin/approve-move-file/'.$fileap['id'])}}">Approve</a>
            		<a title="Decline & Move to Decline Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="{{url('/s/admin/decline-move-file/'.$fileap['id'])}}">Decline</a>
            		<!-- <a title="Move to WIP Files" class="btn btn-sm blue margin-top-10" onclick=" return ConfirmDelete()" href="{{url('/s/admin/wip-move-file/'.$fileap['id'])}}">Move to WIP Files</a> -->
            		@endforeach
            		@endif


            		@if($filedetails['move_to'] == "login")
						
					<?php
					$mode = '';
					$fileid = $filedetails['id'];
					$loanDetailsCount = FileLoanDetail::where('file_id',$fileid)->count();
					if($loanDetailsCount > 0){
						$filecheck = DB::table('file_approvals')->where('file_id',$fileid)->get();
						$filecheck = json_decode(json_encode($filecheck),true);
						if($filecheck){
						 if($filecheck[0]['status'] == "login"){
							 $mode = 'move_to_operations';
						 }
						}
					}
					
					?>
					
					
            		@if($mode == 'move_to_operations')
            		<!-- <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> You cannot move to pending approval because you have not added Loan Details</div> -->
                	<a href="{{url('/s/admin/create-applicants/'.$filedetails['id'])}}?mode={{$mode}}"  class="btn btn-primary" id="save_and_move_to_operations" onclick="Goto_Form_submit()"  >Save and Move to Operations</a>
                	@else
                	<a href="{{url('/s/admin/pending-approvals')}}"  class="btn btn-primary">Save and Move to Operations</a>
                	@endif
                	@endif
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
								@if(isset($filedetails['file_no']))
								{{$filedetails['file_no']}}
								@endif
							</td>
						</tr>
						<?php $clientdetails = Client::clientdetails($filedetails['client_id']) ?>
						<tr>
							<td>
								 Applicant Name
							</td>
							<td>
								{{$clientdetails['customer_name']}}
							</td>
						</tr>
						<tr>
							<td>
								 Company Name
							</td>
							<td>
								{{$clientdetails['company_name']}}
							</td>
						</tr>
						<tr>
							<td>
								 Mobile
							</td>
							<td>
								{{$clientdetails['mobile']}}
							</td>
						</tr>
						<tr>
							<td>
								 PAN
							</td>
							<td>
								@if(!empty($clientdetails['pan']))
								  {{$clientdetails['pan']}}
								@else
								   Applied for PAN
								@endif
							</td>
						</tr>
						<!-- <tr>
							<td>
								 Department
							</td>
							<td>
								{{$filedetails['department']}}
							</td>
						</tr> -->
						<tr>
							<td>
								 Loan Type
							</td>
							<td>
								{{$filedetails['loan_type']}}
							</td>
						</tr>
						<tr>
							<td>
								 Amount Requested (Loan Amt.)
							</td>
							<td>
								@if(!empty($filedetails['loan_amount']))
									<b>Rs {{FileLoanDetail::format($filedetails['loan_amount'])}}</b>
								@else
									Not Entered Yet
								@endif
							</td>
						</tr>
						<?php $directTypes = Employee::gettypes('direct'); ?>
						@foreach($directTypes as $dtype)
							<?php $emp = Employee::empdetail($filedetails['id'],$dtype['short_name']); ?>
							<tr>
								<td>
									{{$dtype['full_name']}}
								</td>
								<td>
									@if($dtype['short_name'] != 'chp')
										{{$emp}}
									@else
									<?php
									$channel_partner = ChannelPartner::where('id',$clientdetails['channel_partner'])->first();
									?>
									{{$channel_partner['name']}}
									@endif
								</td>
							</tr>
						@endforeach
                  
						
						<tr>
							<td>
								Source
							</td>
							
							<td>
                                @foreach($emp_source as $emps)
								{{$emps}}<br>
								@endforeach
							</td>
							
						</tr>
						
						<!-- <tr>
							<td>
								File Type
							</td>
							<td>
								@if(isset($filedetails['file_type']))
								{{ucwords($filedetails['file_type'])}}
								@endif
							</td>
						</tr> -->
						<!-- @if(isset($filedetails['file_type']) && ($filedetails['file_type'] =="indirect" || $filedetails['file_type'] =="other") )
							<?php $indirectTypes = Employee::gettypes('indirect'); ?>
							@foreach ($indirectTypes as $indkey => $indirect)
								<?php $emp = Employee::empdetail($filedetails['id'],$indirect['short_name']); ?>
								<tr>
									<td>
										{{$indirect['full_name']}}
									</td>
									<td>
										{{$emp}}
									</td>
								</tr>
							@endforeach
							@if(isset($filedetails['file_type']) && ($filedetails['file_type'] =="indirect"))
								<?php $partner = ChannelPartner::partnerdetail($filedetails['channel_partner_id']) ?>
								<tr>
									<td>
										Channel Partner
									</td>
									<td>
										{{$partner['name']}} - {{$partner['type']}}
									</td>
								</tr>
							@endif
						@endif -->
						<!-- <tr>
							<td>
								LTS Number
							</td>
							<td>
								{{$filedetails['lts_no']}}
							</td>
						</tr> -->
						<tr>
							<td>
								Pan Card
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['pan_card'])}}">Click here to view your PAN Card</a>
							</td>
						</tr>
						<tr>
							<td>
								Adhaar Card
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['adhaar_card'])}}">Click here to view your Adhaar Card</a>
							</td>
						</tr>
						<tr>
							<td>
								Salary Slip
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['salary_slip'])}}">Click here to view your Salary Slip</a>
							</td>
						</tr>
						<tr>
							<td>
								Bank Passbook
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['bank_passbook'])}}">Click here to view your Bank Passbook</a>
							</td>
						</tr>
						<tr>
							<td>
								Voter Id
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['voter_id'])}}">Click here to view your Voter Id</a>
							</td>
						</tr>
						<tr>
							<td>
								Passport
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['passport'])}}">Click here to view your Passport</a>
							</td>
						</tr>
						<tr>
							<td>
								Driving Licence
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['driving_licence'])}}">Click here to view your Driving Licence</a>
							</td>
						</tr>
						<tr>
							<td>
								Rent Agreement
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['rent_agreement'])}}">Click here to view your Rent Agreement</a>
							</td>
						</tr>
						<tr>
							<td>
								Letterhead
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['letterhead'])}}">Click here to view your Letterhead</a>
							</td>
						</tr>
						<tr>
							<td>
								Photo
							</td>
							<td>
								<a target="_blank" href="{{asset('images/FileDetails/'.$filedetails['photo'])}}">Click here to view your Photo</a>
							</td>
						</tr>
						<tr>
							<td>
								Remarks
							</td>
							<td>
								{{$filedetails['remarks']}}
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
            <form action="{{url('/s/admin/update-valuations')}}" method="post" autocomplete="off">@csrf
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
            <form action="{{url('/s/admin/update-asset-valuations')}}" method="post" autocomplete="off">@csrf
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
            <form action="{{url('/s/admin/update-applicant-financial-details')}}" method="post" autocomplete="off">@csrf
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
@stop





