@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\FileLoanDetail;use App\PartialFile;?>
<?php
$isView = 1;
if($filedetails['move_to'] =="partially")
{
	$partial = PartialFile::where('file_id',$filedetails['id'])->get();
	$partial=json_decode( json_encode($partial), true);
	if(count($partial) == 1)
	{
		$isView = 0;
	}
}else{
	if(isset($_GET['action']) && $_GET['action'] == 'view'){
		$isView = 0;
	}
	
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
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
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
				        <form  class="form-horizontal" method="post" action="{{url('/s/admin/update-disbursement-details/'.$fileid)}}"  enctype="multipart/form-data" autocomplete="off">@csrf
                            @foreach($bankdetails as $bankdetail)
				        	<div class="form-body">
				        		@if($filedetails['move_to'] =="approved" || $filedetails['move_to'] =="partially")
				        		
				        			<?php $readonly =""; ?>
				        			@if($filedetails['move_to'] =="partially")
				        				<?php $readonly ="readonly"; ?>
				        			@endif
					                <div class="row">
					                	<input type="hidden" name="chk" value="1">
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">LAN Number :</label>
						                    <div class="col-md-6">
						                        <input type="text" <?php echo ($isView == 0)?'readonly':''; ?> placeholder="LAN Number" name="lan_no" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['lan']}}" {{$readonly}}>
						                    </div>
						                </div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Amount :</label>
						                    <div class="col-md-6">
						                        <input type="number" <?php echo ($isView == 0)?'readonly':''; ?> min="0" placeholder="Amount" name="amount" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['loan_amt']}}" >
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">ROI :</label>
						                    <div class="col-md-6">
						                        <input type="number" <?php echo ($isView == 0)?'readonly':''; ?> step="0.01" min="0" placeholder="Rate of Interest" name="roi" style="color:gray" autocomplete="off" class="form-control"  value="{{$bankdetail['roi']}}">
						                    </div>
						                </div>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Tenure :</label>
						                    <div class="col-md-6">
						                        <input type="number" <?php echo ($isView == 0)?'readonly':''; ?> min="0" placeholder="Tenure in months" name="tenure" style="color:gray" autocomplete="off" class="form-control" value="{{$bankdetail['tenure_in_months']}}">
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Processing Fees (%) :</label>
						                    <div class="col-md-6">
						                        <input type="number" <?php echo ($isView == 0)?'readonly':''; ?> step="0.01" min="0" placeholder="Processing Fees" name="pf_per" style="color:gray" autocomplete="off" class="form-control" value="{{$bankdetail['processing_fees_percent']}}" {{$readonly}}>
						                    </div>
						                </div>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Processing Fees :</label>
						                    <div class="col-md-6">
						                        <input type="number" <?php echo ($isView == 0)?'readonly':''; ?> min="0" placeholder="Processing Fees" name="pf_amt" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['processing_fees_amount']}}" {{$readonly}}>
						                    	<p>incluse all taxes</p>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Disbursement Type :</label>
						                    <div class="col-md-6">
						                        <select class="form-control DisbType" <?php echo ($isView == 0)?'disabled':''; ?> name="disb_type" id="DisbType-<?php echo $bankdetail['id']; ?>" required>
						                        	<option value="">Please Select</option>
						                        	<option value="partially" {{(!empty($bankdetail['disbursement_type'] && $bankdetail['disbursement_type'] =="Partially Disbursed")? 'selected':'' )}}>Partially Disbursed</option>
						                        	<option value="disbursed" {{(!empty($bankdetail['disbursement_type'] && $bankdetail['disbursement_type'] =="Fully Disbursed")? 'selected':'' )}}>Fully Disbursed</option>
						                        </select>
						                    </div>
						                </div>
						                <?php
						                $disb_value = "";
						                if(!empty($bankdetail['disbursement_type'] && $bankdetail['disbursement_type'] =="Partially Disbursed"))
						                {
						                	$disb_value = "partially";
						                } else if(!empty($bankdetail['disbursement_type'] && $bankdetail['disbursement_type'] =="Fully Disbursed"))
						                {
						                	$disb_value = "disbursed"; 
						                }
						                ?>
						                <script type="text/javascript">
						                	$(document).ready(function() {
						                		console.log("#DisbType-<?php echo $bankdetail['id']; ?>")
							                	console.log("<?php echo $disb_value; ?>")
							                	$("#DisbType-<?php echo $bankdetail['id']; ?>").val("<?php echo $disb_value; ?>").trigger('change');
						                	});
						                </script>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">EMI Amount :</label>
						                    <div class="col-md-6">
						                        <input type="number" min="0" <?php echo ($isView == 0)?'readonly':''; ?> placeholder="EMI Amount" name="emi_amt" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['emi_amount']}}" >
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
										    <label class="col-md-6 control-label">First EMI Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input type="text" <?php echo ($isView == 0)?'readonly':''; ?> class="form-control" placeholder="First EMI Date" name="first_emi_date" autocomplete="off" value="{{$bankdetail['emi_start_date']}}" required {{$readonly}}>
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>
										<div class="form-group col-md-6">
										    <label class="col-md-6 control-label">Last EMI Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input type="text" <?php echo ($isView == 0)?'readonly':''; ?> class="form-control" placeholder="Last EMI Date" name="last_emi_date" autocomplete="off" value="{{$bankdetail['emi_end_date']}}" required {{$readonly}}>
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6" id="FinalDisburse<?php echo $bankdetail['id']; ?>" style="display: none;">
										    <label class="col-md-6 control-label">Final Disbursement Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input id="FinalDisburseDate<?php echo $bankdetail['id']; ?>" type="text" <?php echo ($isView == 0)?'readonly':''; ?> class="form-control" placeholder="Final Disbursement Date" name="final_disbursement_date" autocomplete="off" value="{{(!empty($disbursementdata['final_disbursement_date'])?$disbursementdata['final_disbursement_date']:'' )}}">
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>

										<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Select Status :</label>
						                    <div class="col-md-6">
						                    	@if(empty($disbursementdata['status']))
							                        <select class="form-control StatusType" <?php echo ($isView == 0)?'disabled':''; ?> name="status" id="StatusType-<?php echo $bankdetail['id']; ?>" required>
							                        	<option value="">Please Select</option>
							                        	<option value="approved-partial">Approved</option>
							                        	<option value="declined">Declined (File will be moved to declined files) </option>
							                        </select>
							                    @else
							                    	<input type="hidden" name="status" value="{{$disbursementdata['status']}}">
							                    	<p style="margin-top: 8px;">{{ucwords($disbursementdata['status'])}}</p>
							                   	@endif
						                    </div>
						                </div>
										
					                </div>
					            @elseif($filedetails['move_to'] =="disbursement")
					            <input type="hidden" name="chk" value="1">
					            	<div class="row">
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">LAN Number :</label>
						                    <div class="col-md-6">
						                        <input type="text" placeholder="LAN Number" name="lan_no" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($disbursementdata)?$disbursementdata['lan_no']:'' )}}" readonly <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Amount :</label>
						                    <div class="col-md-6">
						                        <input type="number" min="0" placeholder="Amount" name="amount" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['loan_amt']}}" <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">ROI :</label>
						                    <div class="col-md-6">
						                        <input type="number" step="0.01" min="0" placeholder="Rate of Interest" name="roi" style="color:gray" autocomplete="off" class="form-control"  value="{{$bankdetail['roi']}}" <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Tenure :</label>
						                    <div class="col-md-6">
						                        <input type="number" min="0" placeholder="Tenure in months" name="tenure" style="color:gray" autocomplete="off" class="form-control"  value="{{$bankdetail['tenure_in_months']}}" <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Processing Fees (%) :</label>
						                    <div class="col-md-6">
						                        <input type="number" step="0.01" min="0" placeholder="Processing Fees" name="pf_per" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['processing_fees_percent']}}" readonly <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Processing Fees :</label>
						                    <div class="col-md-6">
						                        <input type="number" min="0" placeholder="Processing Fees" name="pf_amt" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['processing_fees_amount']}}" readonly <?php echo ($isView == 0)?'disabled':''; ?>>
						                    	<p>incluse all taxes</p>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Disbursement Type :</label>
						                    <div class="col-md-6">
						                    	@if(!empty($bankdetail['disbursement_type']))
						                    	<p style="margin-top: 8px;">{{ucwords($bankdetail['disbursement_type'])}}</p>
						                    	@endif
						                    	<input type="hidden" name="disb_type" value="{{$bankdetail['disbursement_type']}}" <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
					                	<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">EMI Amount :</label>
						                    <div class="col-md-6">
						                        <input type="number" min="0" placeholder="EMI Amount" name="emi_amt" style="color:gray" autocomplete="off" class="form-control" required value="{{$bankdetail['emi_amount']}}" <?php echo ($isView == 0)?'disabled':''; ?>>
						                    </div>
						                </div>
						                <div class="clearfix"></div>
						                <div class="form-group col-md-6">
										    <label class="col-md-6 control-label">First EMI Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input type="text" class="form-control" placeholder="First EMI Date" name="first_emi_date" autocomplete="off" value="{{$bankdetail['emi_start_date']}}" required readonly <?php echo ($isView == 0)?'disabled':''; ?>>
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>
										<div class="form-group col-md-6">
										    <label class="col-md-6 control-label">Last EMI Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input type="text" class="form-control" placeholder="Last EMI Date" name="last_emi_date" autocomplete="off" value="{{$bankdetail['emi_end_date']}}" required readonly <?php echo ($isView == 0)?'disabled':''; ?>>
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
										    <label class="col-md-6 control-label">Final Disbursement Date:</label>
										    <div class="col-md-6">
										        <div class="input-group input-append date dobDatepicker">
										            <input type="text" class="form-control" placeholder="Final Disbursement Date" name="final_disbursement_date" autocomplete="off" value="{{(!empty($disbursementdata['final_disbursement_date'])?$disbursementdata['final_disbursement_date']:'' )}}" <?php echo ($isView == 0)?'disabled':''; ?>>
										            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
										        </div>
										    </div>
										</div>
										<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Select Status :</label>
						                    <div class="col-md-6">
						                    	@if(empty($disbursementdata['status']))
							                        <select class="form-control StatusType" name="status" id="StatusType-<?php echo $bankdetail['id']; ?>" required <?php echo ($isView == 0)?'disabled':''; ?>>
							                        	<option value="">Please Select</option>
							                        	<option value="approved">Approved</option>
							                        	<option value="declined">Declined (File will be moved to declined files) </option>
							                        </select>
							                    @else
							                    	<input type="hidden" name="status" value="{{$disbursementdata['status']}}">
							                    	<p style="margin-top: 8px;">{{ucwords($disbursementdata['status'])}}</p>
							                   	@endif
						                    </div>
						                </div>
						                <div id="AppendOtherDisburse<?php echo $bankdetail['id']; ?>" @if($disbursementdata['status'] !="approved") style="display: none;" @endif>
							                <div class="clearfix"></div>
											<!-- <div class="form-group col-md-6">
											    <label class="col-md-6 control-label">Transaction Date:</label>
											    <div class="col-md-6">
											        <div class="input-group input-append date dobDatepicker">
											            <input type="text" class="form-control requiredClass" placeholder="Transaction Date" name="transaction_date" autocomplete="off" value="{{(!empty($disbursementdata['transaction_date'])?$disbursementdata['transaction_date']:'' )}}">
											            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											        </div>
											    </div>
											</div> -->
											<div class="form-group col-md-6">
											    <label class="col-md-6 control-label">PDD Date:</label>
											    <div class="col-md-6">
											        <div class="input-group input-append date dobDatepicker">
											            <input type="text" class="form-control" placeholder="PDD Date" name="pdd" autocomplete="off" value="{{(!empty($disbursementdata['pdd'])?$disbursementdata['pdd']:'' )}}" <?php echo ($isView == 0)?'disabled':''; ?>>
											            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											        </div>
											    </div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group col-md-6">
											    <label class="col-md-6 control-label">Welcome Kits:</label>
											    <div class="col-md-6">
											        <div class="input-group input-append date dobDatepicker">
											            <input type="text" class="form-control" placeholder="Welcome Kits Date" name="welcome_kits" autocomplete="off" value="{{(!empty($disbursementdata['welcome_kits'])?$disbursementdata['welcome_kits']:'' )}}" <?php echo ($isView == 0)?'disabled':''; ?> >
											            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											        </div>
											    </div>
											</div>
											<div class="form-group col-md-6">
											    <label class="col-md-6 control-label">LOD Date:</label>
											    <div class="col-md-6">
											        <div class="input-group input-append date dobDatepicker">
											            <input type="text" class="form-control" placeholder="PDD Date" name="lod" autocomplete="off" value="{{(!empty($disbursementdata['lod'])?$disbursementdata['lod']:'' )}}" <?php echo ($isView == 0)?'disabled':''; ?>>
											            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											        </div>
											    </div>
											</div>
										</div>
										<div class="form-group col-md-6">
						                    <label class="col-md-6 control-label">Remarks :</label>
						                    <div class="col-md-6">
						                        <textarea type="text" placeholder="Remarks" name="remarks" style="color:gray" autocomplete="off" class="form-control" required <?php echo ($isView == 0)?'disabled':''; ?>>{{(!empty($disbursementdata)?$disbursementdata['remarks']:'' )}}</textarea>
						                    </div>
						                </div>
					                </div>
					            @endif
					            @if(!empty($partialdetails))
						            <div class="form-group">
									    <label class="col-md-3 control-label"></label>
									    <div class="col-md-5">
									        <table class="table table-hover table-bordered table-striped">
									            <tbody>
									                <tr>
									                    <th>Partial Date</th>
									                    <th>Partial Disburse Amount</th>
									                </tr>
									                @foreach($partialdetails as $partialdetail)
										                <tr class="blockIdWrap">
										                    <td>{{date('d F Y',strtotime($partialdetail['partial_date']))}}</td>
										                    <td>Rs. {{FileLoanDetail::format($partialdetail['partial_amount'])}}</td>
										                </tr>
									                @endforeach
									            </tbody>
									        </table>
									    </div>
									</div>
								@endif
								
								@if(isset($_GET['action']) && $_GET['action'] == 'view')
								@if($filedetails['move_to'] == 'disbursement')
									<?php
								    $file_disb = DB::table('file_disbursements')->where('file_id',$filedetails['id'])->first();
									$file_disb = json_decode(json_encode($file_disb),true);
									
									?>
								  <div class="form-group">
									    <label class="col-md-3 control-label"></label>
									    <div class="col-md-5">
									        <table class="table table-hover table-bordered table-striped">
									            <tbody>
									                <tr>
									                    <th>Disbursement Date</th>
									                    <th>Disburse Amount</th>
									                </tr>
									                
										                <tr class="blockIdWrap">
										                    <td>{{date('d F Y',strtotime($file_disb['created_at']))}}</td>
										                    <td>Rs. {{FileLoanDetail::format($file_disb['amount'])}}</td>
										                </tr>
									                
									            </tbody>
									        </table>
									    </div>
									</div>
								@endif
								@endif
								
								
								
								
								
								
					            <div id="AppendPartialDisburse<?php echo $bankdetail['id']; ?>">
					            </div>
				            </div>
				            @endforeach
				            <div class="form-actions right1 text-center">
				                <button class="btn green" <?php echo ($isView == 0)?'disabled':''; ?> type="submit">Submit</button>
				            </div>
				        </form>
				    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var isView = "<?php echo $isView; ?>";
	$(document).on('click',"#addrow",function() {        
        $('#AppendPartiallyTr').append('<tr class="blockIdWrap"><td><div class="input-group input-append date dobDatepicker"><input type="text" class="form-control" placeholder="Select Date" name="partial_date[]" autocomplete="off"><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div></td><td><input type="number" step="0.01" min="0" placeholder="Enter Amount" name="partial_amount[]" style="color:gray" autocomplete="off" class="form-control"></td><td><a title="Remove" class="btn btn-sm red remove" href="javascript:;"><i class="fa fa-times"></i></a></td></tr>');
        $('.dobDatepicker').datetimepicker({
	        format:'YYYY-MM-DD',
	        useCurrent: false,
	        allowInputToggle: true
	    });        
    });
    $(document).on("click",'.remove', function() {
        $(this).parents("tr").remove();
    });
	$(document).on('change','.DisbType',function(){
		var value = $(this).val();
		var id = this.id.split('-')[1];
		if(value=="disbursed"){
			$('#FinalDisburse'+id).show();
			$('#AppendPartialDisburse'+id).html('');
			$('#FinalDisburseDate'+id).prop('required',true);
		}else if(value=="partially"){
			console.log(isView)
			if(isView == 1)
			{
				$('#AppendPartialDisburse'+id).html('<div class="form-group"><label class="col-md-3 control-label"></label><div class="col-md-6"><table id="dynamicTable1" class="table table-hover table-bordered table-striped"><tbody id="AppendPartiallyTr"><tr><th>Date</th><th>Partial Disburse Amount</th><th>Actions</th></tr><tr class="blockIdWrap"><td><div class="input-group input-append date dobDatepicker"><input type="text" class="form-control" required placeholder="Select Date" name="partial_date[]" autocomplete="off"><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div></td><td><input type="number" required step="0.01" min="0" placeholder="Enter Amount" name="partial_amount[]" style="color:gray" autocomplete="off" class="form-control" ></td><td></td></tr></tbody></table><input type="button" id="addrow" value="Add More"></div></div>');
			} else {
				$('#AppendPartialDisburse'+id).html('<div class="form-group"><label class="col-md-3 control-label"></label><div class="col-md-6"><table id="dynamicTable1" class="table table-hover table-bordered table-striped"><tbody id="AppendPartiallyTr"><tr><th>Date</th><th>Partial Disburse Amount</th><th>Actions</th></tr><tr class="blockIdWrap"><td><div class="input-group input-append date dobDatepicker"><input type="text" class="form-control" disabled placeholder="Select Date" name="partial_date[]" autocomplete="off"><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div></td><td><input type="number" readonly step="0.01" min="0" placeholder="Enter Amount" name="partial_amount[]" style="color:gray" autocomplete="off" class="form-control" ></td><td></td></tr></tbody></table></div></div>');
			}
			$('.dobDatepicker').datetimepicker({
		        format:'YYYY-MM-DD',
		        useCurrent: false,
		        allowInputToggle: true
		    });
			$('#FinalDisburse'+id).hide();
			$('#FinalDisburseDate'+id).prop('required',false);
		}else{
			$('#AppendPartialDisburse'+id).html('');
			$('#FinalDisburse'+id).hide();
			$('#FinalDisburseDate'+id).prop('required',false);
		}
	})
	$(document).on('change','.StatusType',function(){
		var value = $(this).val();
		var id = this.id.split('-')[1];
		if(value=="approved"){
			$('#AppendOtherDisburse'+id).show();
			$('.requiredClass').prop('required',true);
		}else{
			$('#AppendOtherDisburse'+id).hide();
			$('.requiredClass').prop('required',false);
		}
	})
</script>
@stop