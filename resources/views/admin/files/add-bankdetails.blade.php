@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php  use App\Bank; use App\BankDetail; use App\FileDropdown; use App\FileLoanDetail; use App\File;
$banks = Bank::banks();
$types = FileDropdown::getfiledropdown('facility');
$move_to = File::where('id',$fileid)->first();
$move_to = $move_to->move_to;
error_reporting(0);
?>
<style>
	td,th{position: relative;}
</style>
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
            	<a href="{{url('/s/admin/create-applicants/'.$fileid)}}">Files</a>
            	<i class="fa fa-circle"></i>
            </li>
            <li>
            	<a class="green btn" href="{{url('/s/admin/create-applicants/'.$fileid)}}">Back</a>
            </li>
        </ul>
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        
   
	 
		<input type="hidden" id="tot_amt" value="{{$filedata['loan_amount']}}"> 
        <input type="hidden" id="tot_id"  value="{{$filedata['id']}}">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i>{{$title}}
				        </div>
				    </div>
			    <div class="portlet-body form">
			        <form action="{{url('/s/admin/add-bank-details/'.$fileid)}}"  class="fullWidth" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return Goto_Form_submit()">@csrf
			        	<div class="form-body tableWrapper--main">
						    <div class="fullWidth text-left tableFlow">
				        <table id="tab_bank" class="table table-bordered table-condensed table--main" valign="top">
				        	<thead>
					        	<tr>
						        	<th data-no>Sr. No.</th>
						        	
						        	<th data-customerName>Customer Name*</th>
						        	<th data-bank>Bank Name*</th>
						        	<th data-type>Type*</th>
						        	<th data-program>Program*</th>
						        	<th data-loan-amt>Loan Amt*</th>
						        	<th data-status>Status*</th>
						        	<th data-lan>LAN*</th>
						        	<th data-approved-amt>Approved Amt*</th>
						        	<th data-date>Date*</th>
						        	<th data-roi>ROI</th>
						        	<th data-processing-fees-percent>Processing Fees %</th>
						        	<th data-processing-fees-amount>Processing Fees Amount</th>
						        	<th data-disbursement-type>Disbursement Type*</th>
						        	<th data-emi-amt>EMI Amt</th>
						        	<th data-loan-start-date>First EMI Date</th>
						        	<th data-loan-end-date>Last EMI Date</th>
						        	<th data-tenure>Tenure ( in Months )</th>
						        	<!-- <th data-foir>FOIR</th> -->
						        	<th data-remarks>Remarks*</th>
						        	<th data-agree>Customer Agree*</th>
						        	<th data-action class="text-center">Actions</th>
						        </tr>
				        	</thead>
		        	<tbody>
						@if(!empty($bankdetails))

							@foreach($bankdetails as $lkey => $bankdetail)

								<input type="hidden" name="loan_id[]" value="{{$bankdetail['id']}}">
								<tr data-row>
						        	<td data-no class="text-center">{{++$lkey}} 
						        	</td>
						        	
						        	<td data-customerName>
						        		<input type="text" required class="form-control" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> name="customer_name[]"placeholder="Customer Name" value="{{$clientdata['customer_name']}}"/>
						        	</td>
						        	<td data-bank>
						        		<select id="" required class="form-control" name="bank_name[]" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?> onchange="BankOnChange(this)">
						        			<option data-old-bank-name="" value="" selected="selected">Select Bank</option>
						        			@foreach($banks as $bank)
						        				<option data-old-bank-name="{{ $loandetail['bank_name']}}" value="{{$bank['short_name']}}" @if($bank['short_name'] == $bankdetail['bank_name']) selected="" @endif>{{$bank['short_name']}}</option>
						        			@endforeach
						        		</select>
						        		
						        		@if($move_to == 'disbursement' || $move_to == 'partially')
						        		<input type="hidden" name="bank_name[]" id="" value="{{$bankdetail['bank_name']}}" onchange="BankOnChange(this)" />
						        		@endif
						        	</td>
						        	<td data-type>
						        		<select name="type[]" id="" required class="form-control type_data" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?>>
						        			<option value="" selected="selected">Select Type</option>
						        			@foreach($types as $type)
						        				<option value="{{$type['value']}}" @if($type['value'] == $bankdetail['type']) selected="" @endif>{{$type['value']}}</option>
						        			@endforeach
						        		</select>
						        		@if($move_to == 'disbursement' || $move_to == 'partially')
						        		<input type="hidden" name="type[]" id="" value="{{$bankdetail['type']}}" />
						        		@endif
						        	</td>
						        	<td data-program>
						        		<select name="program[]" id=""  class="form-control prog" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?>>
						        			<?php
						        			  if($filedata['loan_type'] == "Personal Loan"){
						        			  	$programArr = array("Income","RTR");
						        			  }elseif($filedata['loan_type'] == "Business Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Home Loan- Construction"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Home Equity - Residential" || @$filedata['loan_type'] == "Home Equity- Commercial"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","LRD","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Working Capital"){
                                                 $programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Used Car Loan" || @$filedata['loan_type'] == "New Car Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Credit Card" || @$filedata['loan_type'] == "Health Insurance" || $filedata['loan_type'] == "Life Insurance" || $filedata['loan_type'] == "General Insurance"){
						        			  	$programArr = array("Income");
						        			  }else{
						        			  	$programArr = array();
						        			  }
											    sort($programArr);
						        			?>
						        			<option value="" selected="selected">Select Program</option>
                                              @foreach($programArr as $program)
					        				<option value="{{$program}}" @if(!empty($bankdetail['program']) && $bankdetail['program'] == $program) selected @endif>{{$program}}</option>
					        			@endforeach
						        			
						        		</select>
						        		@if($move_to == 'disbursement' || $move_to == 'partially')
						        		<input type="hidden" name="program[]" id="" value="{{$bankdetail['program']}}" />
						        		@endif
						        	</td>
						        	<td data-loan-amt>
						        		
						        		<input type="number" placeholder="Loan Amount" required  class="form-control txtdata" name="loan_amt[]" value="{{$bankdetail['loan_amt']}}" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?>/><br>
						        		
						        	</td>
						        	<td data-status>
					        		<!-- <input type="text" placeholder="Status" required class="form-control" name="status[]" value="{{$bankdetail['status']}}"/><br> -->
					        		<select required class="form-control status" name="status[]" id="status-<?php echo $lkey; ?>" <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?>>
					        			<option value="" selected="selected">Select</option>
					        			<option value="Approved" @if('Approved' == $bankdetail['status']) selected="" @endif>
					        				Approved
					        			</option>
					        			<option value="Declined" @if('Declined' == $bankdetail['status']) selected="" @endif>Declined</option>
					        		</select>
					        		@if($move_to == 'disbursement' || $move_to == 'partially')
						        		<input type="hidden" name="status[]" id="" value="{{$bankdetail['status']}}" />
						        	@endif
					        
					        	</td>
					        	<td data-lan>
						        		<input type="text" required class="form-control" name="lan[]" placeholder="Enter LAN" value="{{$bankdetail['lan']}}" id="lan-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
						        	</td>
					        	<td   data-approved-amt>
					        		<input type="number" placeholder="Approved Amount" required class="form-control"  name="approved_amount[]" value="{{$bankdetail['approved_amount']}}" id="approved_amount-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> /><br>
					        	</td>

					        	<td data-date>
					        		<div class="fullWidth">
					        			<input type="text" required  class="form-control dobDatepicker__table date-<?php echo $lkey; ?>" name="date[]" placeholder="Date" value="{{$bankdetail['date']}}" id="date-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		</div>
					        	</td>
					        	<td data-roi>
					        		
					        			<input type="text" class="form-control" name="roi[]" placeholder="ROI" value="{{$bankdetail['roi']}}" id="roi-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		
					        	</td>
					        	<td data-processing-fees-percent>
					        		
					        			<input type="number" class="form-control" name="processing_fees_percent[]" placeholder="Processing Fees %" value="{{$bankdetail['processing_fees_percent']}}" id="processing_fees_percent-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		
					        	</td>
					        	<td data-processing-fees-amount>
					        		
					        			<input type="number" class="form-control" name="processing_fees_amount[]" placeholder="Processing Fees Amount" value="{{$bankdetail['processing_fees_amount']}}" id="processing_fees_amount-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> />
					        		
					        	</td>
					        	<td data-disbursement-type>
					        		<select required class="form-control" name="disbursement_type[]" id="disbursement_type-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?>  <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?>>
					        			<option value="" selected="selected">Select</option>
					        			<option value="Partially Disbursed" @if('Partially Disbursed' == $bankdetail['disbursement_type']) selected="" @endif>
					        				Partially Disbursed
					        			</option>
					        			<option value="Fully Disbursed" @if('Fully Disbursed' == $bankdetail['disbursement_type']) selected="" @endif>Fully Disbursed</option>
					        		</select> 
					        		<input type="hidden" name="disbursement_type[]" id="disbursement_type-<?php echo $lkey; ?>" value="{{$bankdetail['disbursement_type']}}" />
					        	</td>
					        	<td data-emi-amt>
					        		
					        			<input type="number" required class="form-control" name="emi_amount[]" placeholder="EMI Amount" value="{{$bankdetail['emi_amount']}}" id="emi_amount-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		
					        	</td>
					        	<td data-loan-start-date>
					        		<div class="fullWidth">
					        			<input type="text" required class="form-control dobDatepicker__table emi_start_date-<?php echo $lkey; ?>" name="emi_start_date[]" placeholder="First EMI Date" value="{{$bankdetail['emi_start_date']}}" id="emi_start_date-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		</div>
					        	</td>
					        	<td data-loan-end-date>
					        		<div class="fullWidth">
					        			<input type="text" required class="form-control dobDatepicker__table emi_end_date-<?php echo $lkey; ?>" name="emi_end_date[]" placeholder="Last EMI Date" value="{{$bankdetail['emi_end_date']}}" id="emi_end_date-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		</div>
					        	</td>
					        	<td data-tenure>
					        		
					        			<input type="number" required class="form-control" name="tenure_in_months[]" placeholder="Tenure(in months)" value="{{$bankdetail['tenure_in_months']}}" id="tenure_in_months-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        		
					        	</td>
						        	
						        	<!-- <td data-foir>
						        		<select id="" required class="form-control" name="foir[]">
						        			<option value="No" @if('No' == $bankdetail['foir']) selected="" @endif>
						        				No
						        			</option>
						        			<option value="Yes" @if('Yes' == $bankdetail['foir']) selected="" @endif>Yes</option>
						        		</select> 
						        	</td> -->
						        	<td data-remarks>
					        		<input type="text" placeholder="Remarks" required class="form-control" name="remarks[]" value="{{$bankdetail['remarks']}}"id="remarks-<?php echo $lkey; ?>"  <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'readonly':''; ?> />
					        	    </td>
					        	    <td data-agree class="row_agree">
						        		<select required class="form-control agree_status agree" name="agree[]" id="agree-<?php echo $lkey; ?>" <?php echo ($bankdetail['status'] == 'Declined')?'readonly':''; ?> <?php echo ($move_to == 'disbursement' || $move_to == 'partially')?'disabled':''; ?> >
						        			<option value="" selected="selected">Select</option>
						        			<option value="No" @if('No' == $bankdetail['agree']) selected="" @endif>
						        				No
						        			</option>
						        			<option value="Yes" @if('Yes' == $bankdetail['agree']) selected="" @endif>Yes</option>
						        		</select> 
						        		<input type="hidden" name="agree[]" id="agree-<?php echo $lkey; ?>" value="{{$bankdetail['agree']}}" />
						        	</td>
						        	<td data-action>
						        		<div class="fullWidth text-center">
						        			<a href="javascript:void(0);" class="btn btn-danger deleteRowBtn">
						        				<span class="glyphicon glyphicon-remove"></span>
						        			</a>		
						        		</div>	
						        	</td>
				        		</tr>
							@endforeach
						
						@else

						   @foreach(@$loandetails as $lkey => $loandetail)

                                <input type="hidden" id="ldate" name="lndate[]" value="{{$loandetail['date']}}">

								<input type="hidden" name="loan_id[]" value="{{$loandetail['id']}}">
							<tr data-row>
					        	<td data-no class="text-center">{{++$lkey}}
					        	</td>
					        	
					        	<td data-customerName>
					        		<input type="text" required class="form-control" name="customer_name[]"placeholder="Customer Name" value="{{$clientdata['customer_name']}}" readonly/>
					        	</td>
					        	<td data-bank>
					        		<select id="" required class="form-control" name="bank_name[]" onchange="BankOnChange(this)">
					        			<option data-old-bank-name="" value="" selected="selected">Select Bank</option>
					        			@foreach($banks as $bank)
					        				<option data-old-bank-name="{{ $loandetail['bank_name']}}" value="{{$bank['short_name']}}" @if($bank['short_name'] == $loandetail['bank_name']) selected="" @endif>{{$bank['short_name']}}</option>
					        			@endforeach
					        		</select>
					        	</td>
					        	<td data-type>
					        		<select name="type[]" id="" required class="form-control type_data">
					        			<option value="" selected="selected">Select Type</option>
					        			@foreach($types as $type)
					        				<option value="{{$type['value']}}" @if(!empty($loandetail['type']) && $loandetail['type'] == $type['value']) selected @endif>{{$type['value']}}</option>
					        			@endforeach
					        		</select>
					        	</td>
                                <td data-program>
						        		<select name="program[]" id=""  class="form-control prog">
						        			<?php
						        			  if($filedata['loan_type'] == "Personal Loan"){
						        			  	$programArr = array("Income","RTR");
						        			  }elseif($filedata['loan_type'] == "Business Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Home Loan- Construction"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Home Equity - Residential" || $filedata['loan_type'] == "Home Equity- Commercial"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","LRD","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Working Capital"){
                                                 $programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Used Car Loan" || $filedata['loan_type'] == "New Car Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Liquid Income");
						        			  }elseif($filedata['loan_type'] == "Credit Card" || $filedata['loan_type'] == "Health Insurance" || $filedata['loan_type'] == "Life Insurance" || $filedata['loan_type'] == "General Insurance"){
						        			  	$programArr = array("Income");
						        			  }else{
						        			  	$programArr = array();
						        			  }
											    sort($programArr);
						        			?>
						        			<option value="" selected="selected">Select Program</option>
                                              @foreach($programArr as $program)
					        				<option value="{{$program}}" @if(!empty($loandetail['program']) && $loandetail['program'] == $program) selected @endif>{{$program}}</option>
					        			@endforeach
						        			
						        		</select>
						        </td>

					        	<td data-loan-amt>
					        		<input type="number" placeholder="Loan Amount" required class="form-control txtdata" id="lnamt" name="loan_amt[]" value="{{$loandetail['loan_amt']}}" readonly/><br>
					        		
					        
					        	</td>
					        	<td data-status>
					        		<!-- <input type="text" placeholder="Status" required class="form-control" name="status[]" /><br> -->
					        		<select required class="form-control status" name="status[]" id="status-<?php echo $lkey; ?>">
					        			<option value="" selected="selected">Select</option>
					        			<option value="Approved">
					        				Approved
					        			</option>
					        			<option value="Declined">Declined</option>
					        		</select> 
					        		
					        
					        	</td>
					        	<td data-lan>
					        		<input type="text" required class="form-control"  name="lan[]" placeholder="Enter LAN" value="" id="lan-<?php echo $lkey; ?>"/>
					        	</td>
					        	<td   data-approved-amt>
					        		<input type="number" placeholder="Approved Amount" required class="form-control"  name="approved_amount[]" value="{{$loandetail['loan_amt']}}" id="approved_amount-<?php echo $lkey; ?>" /><br>
					        	</td>

					        	<td data-date style="height: 200px;">
					        		<div class="fullWidth">
					        			<input type="date" required class="form-control lnap_date date-<?php echo $lkey; ?>" id="appr_date date-<?php echo $lkey; ?>" name="date[]" placeholder="Date" />
					        		</div>
					        	</td>
					        	<td data-roi>
					        		
					        			<input type="text" class="form-control"  name="roi[]" placeholder="ROI" id="roi-<?php echo $lkey; ?>" />
					        		
					        	</td>
					        	<td data-processing-fees-percent>
					        		
					        			<input type="number" class="form-control"  name="processing_fees_percent[]" placeholder="Processing Fees %" id="processing_fees_percent-<?php echo $lkey; ?>" />
					        		
					        	</td>
					        	<td data-processing-fees-amount>
					        		
					        			<input type="number" class="form-control"  name="processing_fees_amount[]" placeholder="Processing Fees Amount" id="processing_fees_amount-<?php echo $lkey; ?>" />
					        		
					        	</td>
					        	<td data-disbursement-type>
					        		<select required class="form-control" name="disbursement_type[]" id="disbursement_type-<?php echo $lkey; ?>">
					        			<option value="" selected="selected">Select</option>
					        			<option value="Partially Disbursed">
					        				Partially Disbursed
					        			</option>
					        			<option value="Fully Disbursed">Fully Disbursed</option>
					        		</select> 
					        		<input type="hidden" name="disbursement_type[]" id="disbursement_type-<?php echo $lkey; ?>" value />
					        	</td>
					        	<td data-emi-amt>
					        		
					        			<input type="number" class="form-control"  name="emi_amount[]" placeholder="EMI Amount" id="emi_amount-<?php echo $lkey; ?>" />
					        		
					        	</td>
					        	<td data-loan-start-date>
					        		<div class="fullWidth">
					        			<input type="date" id="datefrom emi_start_date-<?php echo $lkey; ?>" class="form-control emi_start emi_start_date-<?php echo $lkey; ?>" name="emi_start_date[]" placeholder="First EMI Date" />
					        		</div>
					        	</td>
					        	<td data-loan-end-date>
					        		<div class="fullWidth">
					        			<input type="date" id="dateto emi_end_date-<?php echo $lkey; ?>" class="form-control emi_end emi_end_date-<?php echo $lkey; ?>" name="emi_end_date[]" placeholder="Last EMI Date" />
					        		</div>
					        	</td>
					        	<td data-tenure>
					        		
					        			<input type="number" class="form-control" name="tenure_in_months[]" placeholder="Tenure(in months)" id="tenure_in_months-<?php echo $lkey; ?>" />
					        		
					        	</td>
					        	

					        	<!-- <td data-foir>
					        		<select id="" required class="form-control" name="foir[]">
					        			<option value="No" @if('No' == $loandetail['foir']) selected="" @endif>
					        				No
					        			</option>
					        			<option value="Yes" @if('Yes' == $loandetail['foir']) selected="" @endif>Yes</option>
					        		</select> 
					        	</td> -->
					        	<td data-remarks>
					        		<input type="text" placeholder="Remarks" required class="form-control"  name="remarks[]" value="{{$loandetail['remarks']}}"  id="remarks-<?php echo $lkey; ?>" />
					        	</td>
					        	<td data-agree class="row_agree">
					        		<select required class="form-control agree" name="agree[]" id="agree-<?php echo $lkey; ?>">
					        			<option value="" selected="selected">Select</option>
					        			<option value="No">
					        				No
					        			</option>
					        			<option value="Yes">Yes</option>
					        		</select> 
					        		<input type="hidden" name="agree[]" id="agree-<?php echo $lkey; ?>" value />
					        	</td>
					        	 
					        	<td data-action>
					        		<div class="fullWidth text-center">
					        			<a href="javascript:void(0);" class="btn btn-danger deleteRowBtn">
					        				<span class="glyphicon glyphicon-remove"></span>
					        			</a>		
					        		</div>
					        	</td>
					        </tr>
					        @endforeach
					    @endif
		        	</tbody>
				        </table>
						    </div>
			            </div>
			            <?php
			               $arr = array();
                           foreach(@$loandetails as $loandetail){
                              array_push($arr,$loandetail['loan_amt']);
                           }
                           $tot = array_sum($arr);

			            ?>
			            
			            <!-- @if(!empty($totalemiamt))
				            <h3 class="fullWidth text-right" style="padding: 10px 15px; background-color: #fff;">
				            	Total: <span style="display:inline-block;margin-left:10px;font-weight:bold;">
				            		{{FileLoanDetail::format($totalemiamt)}}
				            	</span>
				            </h3>
				        @endif -->
			            <div class="form-actions right1 text-center">
			            	<span class="btn_err" style="display: none;">Required loan amount has filled. You can’t enter more!</span>
			                <button id="btn_sub" class="btn green" type="submit">Submit</button>
		        			<a href="javascript:void(0);" data-id="<?php echo $lkey; ?>" class="btn btn-primary addRowBtn pull-right" id="btn_data">
		        				Add Row
		        			</a>
			            </div>
			        </form>
			    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<script>
	window.addEventListener('DOMContentLoaded', function () {
	    $('.dobDatepicker__table').datetimepicker({
	        format:'YYYY-MM-DD',
	        useCurrent: false,
	        allowInputToggle: true
	    });

	    funcDateTimecalc();
		$('.table--main').find('td[data-loan-start-date]').each(function (index) {
			$(this).attr('id', 'datetimepickerTd-' + (index+1));
		});
		$('.table--main').find('td[data-loan-start-date]').each(function (index) {
			 // $('.dobDatepicker__tablest').datetimepicker({
		  //           format:'YYYY-MM-DD',
		  //           useCurrent: false,
		  //           allowInputToggle: true,
		           
		            
		  //       });
		 
		  // document.getElementById('datefrom').onchange = function () {
    //       document.getElementById('dateto').setAttribute('min',  this.value);
			

			$(this).find('input').on('focus', function () {
				$(this).closest('.tableWrapper--main').css({
					'padding-bottom': '200px'
				});
				$('.tableFlow').scrollLeft($('[data-loan-start-date]').first().position().left);
				$(this).closest('.tableFlow').children('table').css({
					'margin-bottom': '305px' 
				});
			});
			$(this).find('input').on('blur', function () {
				$(this).closest('.tableFlow').attr('style','');
				$(this).closest('.tableFlow table').attr('style','');
				$(this).closest('.tableWrapper--main').attr('style','');
			});
		});
		// $('.table--main').find('td[data-loan-end-date]').each(function (index) {

		// 	$('.dobDatepicker__tablend').datetimepicker({
		//             format:'YYYY-MM-DD',
		//             useCurrent: false,
		//             allowInputToggle: true
		            
		//         });
		// });
		 
		$('.addRowBtn').on('click', function (e) {
			e.preventDefault();
			$('.table--main').find('tr[data-row]').first().attr('data-row', '');
			var cloned = $('.table--main').find('tr[data-row]').last().clone();

			$('.table--main').find('tbody').append(cloned);
			$('[data-row]').each(function (index) {
				$(this).attr('data-row', (index + 1));
				$(this).find('td[data-no]').html(index + 1);

			});			
			$('tr[data-row]:last-of-type').find('input.form-control').each(function () {
				console.log(this.id)
				var id = this.id
				if(id)
				{
					console.log(id.split('-')[1])
					var name = id.split('-')[0];
					var id_num = parseInt(id.split('-')[1]) + parseInt(1);
					$(this).removeAttr('id');
					console.log(name+"-"+id_num)
					$(this).attr('id',name+"-"+id_num);
				}
				$(this).val('');
			});		
			$('tr[data-row]:last-of-type').find('select.form-control').each(function () {
				console.log(this.id)
				var id = this.id
				if(id)
				{
					console.log(id.split('-')[1])
					var name = id.split('-')[0];
					var id_num = parseInt(id.split('-')[1]) + parseInt(1);
					$(this).removeAttr('id');
					console.log(name+"-"+id_num)
					$(this).attr('id',name+"-"+id_num);
				}
			});
			// $('tr[data-row]:last-of-type').find('select.form-control.lbtypes').each(function () {
			// 	if(this.name != 'type[]')
			// 	{
			// 		$(this).find('option:first-of-type').attr('selected');
			// 		$(this).find('option:first-of-type').siblings().removeAttr('selected');
			// 	}

			// });
			// $('tr[data-row]:last-of-type').find('select.form-control.lbprogm').each(function () {
                 
			// 	if(this.name != 'program[]')
			// 	{
			// 		$(this).find('option:first-of-type').attr('selected');
                     
			// 		$(this).find('option:first-of-type').siblings().removeAttr('selected');
			// 	}
			// });
			$('tr[data-row]:last-of-type').find('a.deleteRowBtn').attr('style', ' ');
			$('.dobDatepicker__table').datetimepicker({
		        format:'YYYY-MM-DD',
		        useCurrent: false,
		        allowInputToggle: true
		    });

		    $(".status").on('change',function(){
                var id = this.id.split('-')[1];
                console.log($("#status-"+id).val())
                if($("#status-"+id).val() == 'Approved')
                {
                	$("#lan-"+id).removeAttr("readonly"); 
                	$("#approved_amount-"+id).removeAttr("readonly"); 
                	$(".date-"+id).removeAttr("disabled"); 
                	$("#roi-"+id).removeAttr("readonly"); 
                	$("#processing_fees_percent-"+id).removeAttr("readonly"); 
                	$("#processing_fees_amount-"+id).removeAttr("readonly"); 
                	$("#disbursement_type-"+id).val("");
                	$("#disbursement_type-"+id).removeAttr("readonly");
                	$("#emi_amount-"+id).removeAttr("readonly"); 
                	$(".emi_start_date-"+id).removeAttr("disabled"); 
                	$(".emi_end_date-"+id).removeAttr("disabled"); 
                	$("#tenure_in_months-"+id).removeAttr("readonly"); 
                	$("#remarks-"+id).removeAttr("readonly"); 
                	$("#agree-"+id).val("");
                	$("#agree-"+id).removeAttr("readonly");
                } else {
                	$("#lan-"+id).attr("readonly", true);
                	$("#approved_amount-"+id).attr("readonly", true);
                	$(".date-"+id).attr("disabled", true);
                	console.log($("#date-"+id))
                	$("#roi-"+id).attr("readonly", true);
                	$("#processing_fees_percent-"+id).attr("readonly", true);
                	$("#processing_fees_amount-"+id).attr("readonly", true);
                	$("#disbursement_type-"+id).attr("readonly", true);
                	$("#disbursement_type-"+id).val("Fully Disbursed");
                	$("#emi_amount-"+id).attr("readonly", true);
                	$(".emi_start_date-"+id).attr("disabled", true);
                	$(".emi_end_date-"+id).attr("disabled", true);
                	$("#tenure_in_months-"+id).attr("readonly", true);
                	$("#remarks-"+id).attr("readonly", true);
                	$("#agree-"+id).attr("readonly", true);
                	$("#agree-"+id).val("No");
                }
		 });

            
		    funcDateTimecalc();
			$('.table--main').find('td[data-loan-start-date]').each(function (index) {
                
				$(this).find('input').on('focus', function () {

					$(this).closest('.tableWrapper--main').css({
						'padding-bottom': '200px'
					});
					
					$(this).closest('.tableFlow').children('table').css({
						'margin-bottom': '305px' 
					});
				});
				$(this).find('input').on('blur', function () {
					$(this).closest('.tableFlow').attr('style','');
					$(this).closest('.tableFlow table').attr('style','');
					$(this).closest('.tableWrapper--main').attr('style','');
				});
			});
		});
		$(document).on('click', '.deleteRowBtn' , function (e) {
			e.preventDefault();		
			if ($(this).closest('tr[data-row]').is(':only-child')) {
				alert('You can not remove all rows');
			}
			$(this).closest('tr[data-row]:not(:only-child)').remove();
			$('[data-row]').each(function (index) {
				$(this).attr('data-row', (index + 1));
				$(this).find('td[data-no]').html(index + 1);
			});	
		});
		$(".status").on('change',function(){
                var id = this.id.split('-')[1];
                console.log($("#status-"+id).val())
                if($("#status-"+id).val() == 'Approved')
                {
                	$("#lan-"+id).removeAttr("readonly"); 
                	$("#approved_amount-"+id).removeAttr("readonly"); 
                	$(".date-"+id).removeAttr("disabled"); 
                	$("#roi-"+id).removeAttr("readonly"); 
                	$("#processing_fees_percent-"+id).removeAttr("readonly"); 
                	$("#processing_fees_amount-"+id).removeAttr("readonly"); 
                	$("#disbursement_type-"+id).val("");
                	$("#disbursement_type-"+id).removeAttr("readonly");
                	$("#emi_amount-"+id).removeAttr("readonly"); 
                	$(".emi_start_date-"+id).removeAttr("disabled"); 
                	$(".emi_end_date-"+id).removeAttr("disabled"); 
                	$("#tenure_in_months-"+id).removeAttr("readonly"); 
                	$("#remarks-"+id).removeAttr("readonly"); 
                	$("#agree-"+id).val("");
                	$("#agree-"+id).removeAttr("readonly");
                } else {
                	$("#lan-"+id).attr("readonly", true);
                	$("#approved_amount-"+id).attr("readonly", true);

                	$(".date-"+id).attr("disabled", true);
                	console.log("00")

                	// console.log($("#date-"+id))
                	// console.log($("#emi_start_date-"+id))
                	// $(".emi_start").prop('disabled', true);
                	//$('.dobDatepicker__table').datetimepicker().disable();
              //   	$('.dobDatepicker__table').data("DateTimePicker").disable();
              //   	$(".lnap_date").css("diplay","none");
            		// $("#date-"+id).prop('disabled', true);





                	$("#roi-"+id).attr("readonly", true);
                	$("#processing_fees_percent-"+id).attr("readonly", true);
                	$("#processing_fees_amount-"+id).attr("readonly", true);
                	$("#disbursement_type-"+id).attr("readonly", true);
                	$("#disbursement_type-"+id).val("Fully Disbursed");
                	$("#emi_amount-"+id).attr("readonly", true);
                	$(".emi_start_date-"+id).attr("disabled", true);
                	$(".emi_end_date-"+id).attr("disabled", true);
                	$("#tenure_in_months-"+id).attr("readonly", true);
                	$("#remarks-"+id).attr("readonly", true);
                	$("#agree-"+id).attr("readonly", true);
                	$("#agree-"+id).val("No");
                }
		 });
	});
	function funcDateTimecalc () {
		$('.dobDatepicker__tables').each(function (key) {

			$(this).attr('data-id', 'dateTimePicker-' + key);
		});
		var idsDateTimePicker = $('.dobDatepicker__tables').map(function (key) {
			return $(this).attr('data-id');
		}).get();

		for (var i = 0; i<idsDateTimePicker.length; i++) {
			$('[data-id="dateTimePicker-' + i + '"]').on("dp.change", function (e) {
				var $this = $(this);
				var date = $this.data('date');
				var $target = $this.closest('td').siblings('td[data-tenure]').find('input');
				var tenure = $target.val();
				if($target.val().length > 0) {
					$.ajax({
						url: '/s/admin/calculate-installments',
						data: {emidate: date, tenure:tenure},
						type: 'POST',
						success:function (resp) {
							var r = parseInt(resp['diff']);
							var diff = parseInt(resp['tenure']);
							$this.closest('td').siblings('td[data-paid-installments]').find('input').val(r);
							$this.closest('td').siblings('td[data-balance-installments]').find('input').val(diff);
						},
						error: function () {
							alert("error");
						}
					})
				} else {
					alert('Please enter value of Tenure (in months)');
				}	
			});
		 } 
	}

	$(document).ready(function() {

		
		$('#btn_data').attr("disabled","disabled");
		var tot = $("#tot_amt").val();
	     
        $("#tab_bank").on('input', '.txtdata', function () {
           var calculated_total_sum = 0;
     
           $("#tab_bank .txtdata").each(function () {
             var get_textbox_value = $(this).val();

            if ($.isNumeric(get_textbox_value)) {
               calculated_total_sum += parseFloat(get_textbox_value);
               }                  
            });
           if(tot == calculated_total_sum){ 
				$('#btn_sub').prop('disabled', false);
                $('#btn_data').attr("disabled","disabled");
                $('.btn_err').css({
                	     'padding-left' : '15px',
						 'color': 'red',
						 'display' : 'block' 
					}).show();
		    }else{
		    	$('#btn_sub').prop('disabled', true);
		    	$('#btn_data').attr("disabled",false);
		    }
        });

        
         $("#tab_bank .emi_start").each(function () {
          $(this).on('change',function(){
                $("#tab_bank .emi_end").attr('min', $(this).val());
		  });
         });
        
        $('#tab_bank .lnap_date').each(function (index) {
		 	  var arr = $('input[type="hidden"][name="lndate[]"]').map(function(){
               return this.getAttribute("value");
               }).get();
		 	  for(var i=0;i<arr.length;i++){
		 	  	$(this).attr('min', arr[i]);
		 	  }
		 });

        
        

	});
	function BankOnChange(_this){
	    var old_bank_name = $(_this).find('option:selected').attr('data-old-bank-name');
        var bank_name = $(_this).val();
		if(bank_name != ''){
			if(old_bank_name != bank_name){
				alert('Before change the bank name, First update the bank name in the loan details');
			    $(_this).val(old_bank_name).change();
	
			}
		}
	}
function Goto_Form_submit(){ 
	$("#btn_sub").attr("disabled", true);
	$(".loadingDiv").show();
	return true;
}
</script>	
@stop