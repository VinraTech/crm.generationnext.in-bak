@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\Bank; use App\FileDropdown; use App\FileLoanDetail; use App\Banker;
$banks = Bank::banks();
$types = FileDropdown::getfiledropdown('facility');?>
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
         @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
       <!--  <div id ="err_warn" role="alert" class="alert alert-warning alert-dismissible fade in" style="display: none;"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Warning!You have entered more amount than client's requirement.</strong>  </div> -->
	 
		<input type="hidden" id="tot_amt" value="{{$filedata['loan_amount']}}"> 
         
        <div class="row">
            <div class="col-md-12">
                <div class="portlet blue-hoki box ">
				    <div class="portlet-title">
				        <div class="caption">
				            <i class="fa fa-gift"></i>{{$title}}
				        </div>
				    </div>
			    <div class="portlet-body form">
			        <form action="{{url('/s/admin/add-loan-details/'.$fileid)}}"  class="fullWidth" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return Goto_Form_submit()">@csrf
			        	<div class="form-body tableWrapper--main">
						    <div class="fullWidth text-left tableFlow">
				        <table id="tab" class="table table-bordered table-condensed table--main" valign="top">
				        	<thead>
					        	<tr>
						        	<th data-no>Sr. No.</th>
						        	<!-- <th data-lan>LAN</th> -->
						        	@if($filedata['move_to'] == "bank")
						        	<th data-date>Date</th>
						        	@endif
						        	<th data-customerName>Customer Name</th>
						        	<th data-bank>Bank Name</th>
						        	<th data-banker>Banker Name</th>
						        	<th data-type>Type</th>
						        	<th data-program>Program</th>
						        	<th data-loan-amt>Loan Amt.</th>
						        	<!-- <th data-foir>FOIR</th> -->
						        	<th data-remarks>Remarks</th>
						        	<th data-action class="text-center">Actions</th>
						        </tr>
				        	</thead>
				        	
		        	<tbody>
						@if(!empty($loandetails))
						
						  
                            <?php $bankname = array();
                                  $ltype = array();
                                  $lprog = array();
                            ?>
							@foreach($loandetails as $lkey => $loandetail)

								<input type="hidden" name="loan_id[]" value="{{$loandetail['id']}}">
								<tr data-row>
						        	<td data-no class="text-center">{{++$lkey}}</td>
						        	<!-- <td data-lan>
						        		<input type="text" required class="form-control" name="lan[]" placeholder="Enter LAN" value="{{$loandetail['lan']}}"/>
						        	</td> -->
						        	@if($filedata['move_to'] == "bank")
						        	<td data-date  style="height: 250px;">
						        	   <div class="fullWidth">
						        		<input type="text" required class="form-control dobDatepicker__table" name="date[]" placeholder="Select Date" value="{{$loandetail['date']}}"/>
						        	   </div>
						        	</td>
						        	@endif
						        	<td data-customerName>
						        		@if($filedata['move_to'] != "login")
						        		<input type="text" required class="form-control" name="customer_name[]" placeholder="Customer Name" value="{{$clientdata['customer_name']}}" readonly/>
						        		@else
						        		<input type="text" required class="form-control" name="customer_name[]" placeholder="Customer Name" value="{{$clientdata['customer_name']}}" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif />
						        		@endif
						        	</td>
						        	<td data-bank>
						        		@if($filedata['move_to'] != "login")
						        		<select id="bank-<?php echo $lkey; ?>" data-id="<?php echo $lkey; ?>" required class="form-control bankname" name="bank_name[]" readonly>
						        			
											<option value="" selected="selected">Select Bank</option>
						        			@foreach($banks as $bank)
						        				<option value="{{$bank['short_name']}}" @if($bank['short_name'] == $loandetail['bank_name']) selected="selected" @endif>{{$bank['short_name']}}</option>
						        			@endforeach
						        			<?php array_push($bankname, $loandetail['bank_name']);?>
						        		</select>
						        		
						        			
						        		</select>
						        		@else
						        		<select id="bank-<?php echo $lkey; ?>" required class="form-control bankname" name="bank_name[]">
						        			<option value="" selected="selected">Select Bank</option>
						        			@foreach($banks as $bank)
						        				<option value="{{$bank['short_name']}}" @if($bank['short_name'] == $loandetail['bank_name']) selected="" @endif>{{$bank['short_name']}}</option>
						        			@endforeach
						        		
						        		</select>
						        		
						        		@endif	
						        	</td>
						        	<td data-banker>
						        		<?php
						        		$bank_short_name = $loandetail['bank_name'];
						        		$bank_details = Bank::where('short_name',$bank_short_name)->first();
						        		$bank_details = json_decode(json_encode($bank_details),true);
						        		$bank_id = $bank_details['id'];
						        		$bankers = Banker::where('bank_id',$bank_id)->orderby('banker_name','asc')->get();
						        		$bankers= json_decode(json_encode($bankers),true);
						        		?>

						        		@if($filedata['move_to'] != "login")
						        		
						        		<select id="banker-<?php echo $lkey; ?>" class="form-control bankername" name="banker_name[]" readonly>
						        			@foreach($bankers as $banker)
						        				<option value="{{$banker['banker_name']}}" @if($banker['banker_name'] == $loandetail['banker_name']) selected="" @endif>{{$banker['banker_name']}}</option>
						        			@endforeach
						        		</select>
						        		@else
						        		
						        		<select id="banker-<?php echo $lkey; ?>" class="form-control bankername" name="banker_name[]">
						        			@foreach($bankers as $banker)
						        				<option value="{{$banker['banker_name']}}" @if($banker['banker_name'] == $loandetail['banker_name']) selected="" @endif>{{$banker['banker_name']}}</option>
						        			@endforeach
						        		</select>
						        		@endif	
						        	</td>
						        	<td data-type>
						        		@if($filedata['move_to'] != "login")
						        		<select name="type[]" id="" required class="form-control type_data" readonly>
						        			<option value="" selected="selected">Select Type</option>

						        			@foreach($types as $type)
						        				<option value="{{$type['value']}}" @if($type['value'] == $loandetail['type']) selected="" @endif>{{$type['value']}}</option>
						        				
						        			@endforeach
                                            <?php array_push($ltype, $loandetail['type']);?>
						        		</select>
						        		@else
						        		<select name="type[]" id="" required class="form-control type_data" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif>
						        			<option value="" selected="selected">Select Type</option>

						        			@foreach($types as $type)
						        				<option value="{{$type['value']}}" @if($type['value'] == $loandetail['type']) selected="" @endif>{{$type['value']}}</option>
						        				
						        			@endforeach
                                            
						        		</select>
						        		@endif
						        	</td>

						        	<td data-program>
						        		@if($filedata['move_to'] != "login")
						        		<select name="program[]" id=""  class="form-control prog" readonly>
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
						        		<?php array_push($lprog, $loandetail['program']);?>
						        		</select>
						        		@else
						        		<select name="program[]" id=""  class="form-control prog" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif>
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
						        	 @endif
						        	</td>
						        	
						        	<td data-loan-amt>
						        		
						        		@if($filedata['move_to'] != "login")
						        		<input type="number" placeholder="Loan Amount" readonly required  class="form-control txtdata" name="loan_amt[]" value="{{$filedata['loan_amount']}}" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif ><br>
						        		@else
						        		<input type="number" placeholder="Loan Amount" required  class="form-control txtdata" name="loan_amt[]" value="{{$filedata['loan_amount']}}" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif><br>
						        		@endif
						        		
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
					        		<input type="text" placeholder="Remarks"  class="form-control" name="remarks[]" value="{{$loandetail['remarks']}}" />
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
						<?php Session::put('bankname', $bankname);
						      Session::put('ltype', $ltype);
						      Session::put('lprog', $lprog);
						?>
						@else
							<tr data-row>
					        	<td class="text-center" data-no>1</td>
					        	<!-- <td data-lan>
					        		<input type="text" required class="form-control" name="lan[]" placeholder="Enter LAN" />
					        	</td> -->
					        	@if($filedata['move_to'] != "login")
					        	<td data-date style="height: 250px;">
						        		<input type="text" required class="form-control dobDatepicker__table" name="date[]" placeholder="Select Date"/>
						        </td>
						        @endif
					        	<td data-customerName>
                                  <div class="fullWidth">
					        		<input type="text" required class="form-control" name="customer_name[]" placeholder="Customer Name" value="{{$clientdata['customer_name']}}"  @if($filedata['move_to'] == "login") readonly @endif />
					        	   </div>
					        	</td>
					        	<td data-bank>
					        		<select id="bank-1" data-id="1" required class="form-control bankname" name="bank_name[]" >
					        			<option value="" selected="selected">Select Bank</option>
					        			@foreach($banks as $bank)
					        				<option value="{{$bank['short_name']}}">{{$bank['short_name']}}</option>
					        			@endforeach
					        		</select>
					        	</td>
					        	<td data-banker>
					        		<select id="banker-1" data-id="1" class="form-control bankername" name="banker_name[]">
					        		</select>
					        	</td>
					        	<td data-type>
					        		<select name="type[]" id="" required class="form-control type_data ltypes" @if($filedata['move_to'] == "login") readonly @endif>
					        			<option value="" selected="selected">Select Type</option>
					        			@foreach($types as $type)
					        				<option value="{{$type['value']}}" @if(!empty($filedata['loan_type']) && $filedata['loan_type'] == $type['value']) selected @endif>{{$type['value']}}</option>
					        			@endforeach
					        		</select>
					        	</td>
                                <td data-program>
						        		<select name="program[]" id=""  class="form-control prog lprogm" @if($filedata['move_to'] == "login") readonly @endif>
						        		
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
					        				<option value="{{$program}}" @if(!empty($filedata['program']) && $filedata['program'] == $program) selected @endif>{{$program}}</option>
					        			@endforeach
                                          
						        			
						        		</select>
						        </td>

					        	<td data-loan-amt>
					        		<input type="number" placeholder="Loan Amount" required class="form-control txtdata" id="lnamt" name="loan_amt[]" value="{{$filedata['loan_amount']}}" @if(!empty($loandetail)) style="pointer-events:none" readonly  @endif @if($filedata['move_to'] == "login") readonly @endif />
					        		
					        
					        	</td>
					        	

					        	<!-- <td data-foir>
					        		<select id="" required class="form-control" name="foir[]">
					        			<option value="No">
					        				No
					        			</option>
					        			<option value="Yes">Yes</option>
					        		</select> 
					        	</td> -->
					        	<td data-remarks>
					        		<input type="text" placeholder="Remarks"  class="form-control" name="remarks[]"  />
					        	</td>
					        	 
					        	<td data-action>
					        		<div class="fullWidth text-center">
					        			<a href="javascript:void(0);" class="btn btn-danger deleteRowBtn">
					        				<span class="glyphicon glyphicon-remove"></span>
					        			</a>		
					        		</div>
					        	</td>
					        </tr>
					    @endif
		        	</tbody>
				        </table>
						    </div>
			            </div>
			            <!-- @if(!empty($totalemiamt))
				            <h3 class="fullWidth text-right" style="padding: 10px 15px; background-color: #fff;">
				            	Total: <span style="display:inline-block;margin-left:10px;font-weight:bold;">
				            		{{FileLoanDetail::format($totalemiamt)}}
				            	</span>
				            </h3>
				        @endif -->
			            <div class="form-actions right1 text-center">
			            	<span class="btn_err" style="display: none;font-size: 15px;">Warning!! Requested amount has filled. Do you want to add more?</span>
			            	<!-- <span class="btn_errs" style="display: none;font-size: 15px;">Required loan amount hasn't filled. Enter loan amount properly!</span> -->
			                <button id="btn_sub" class="btn green" type="submit">Submit</button>
		        			<a href="javascript:void(0);" class="btn btn-primary addRowBtn pull-right" id="btn_data">
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
		 
		$('.addRowBtn').on('click', function (e) {
			e.preventDefault();
			$('.table--main').find('tr[data-row]').last().attr('data-row', '');
			var cloned = $('.table--main').find('tr[data-row]').last().clone();

			$('.table--main').find('tbody').append(cloned);
			$('[data-row]').each(function (index) {
				$(this).attr('data-row', (index + 1));
				$(this).find('td[data-no]').html(index + 1);

			});			
			$('tr[data-row]:last-of-type').find('input.form-control').each(function () {
				if(this.name != 'customer_name[]')
					$(this).val('');
			});	
			$('tr[data-row]:last-of-type').find('select.form-control').each(function () {
				if(this.name == 'bank_name[]')
				{
					console.log($(this).attr('id'))
					var index = $(this).attr('id').split('-')[1];
					var newindex = parseInt($(this).attr('id').split('-')[1])+1;
					console.log(newindex)
					$(this).attr('data-id', (newindex));
					$(this).removeAttr('bank-'+index);
					$(this).attr('id','bank-'+newindex);
				}
				if(this.name == 'banker_name[]')
				{
					var index = $(this).attr('id').split('-')[1];
					var newindex = parseInt($(this).attr('id').split('-')[1])+1;
					$(this).attr('data-id', newindex);
					$(this).removeAttr('banker-'+index);
					$(this).attr('id','banker-'+newindex);
					$("#banker-"+newindex).empty();
				}

				$('.bankname').on('change',function(){
					var id = $(this).data('id');
					var bankid = $("#bank-"+id).val();
					console.log(bankid)
					$.ajax({
						url: '/s/admin/add-banker',
						data: {bankid: bankid},
						type: 'POST',
						success:function (resp) {
							console.log(resp)
							console.log(JSON.parse(resp))
							$("#banker-"+id).empty();
							$.each(JSON.parse(resp), function(i, item) {
								console.log(item)
								console.log("#banker-"+id)
								console.log(item.banker_name)
		                        $("#banker-"+id).append($('<option>', {
		                            value: item.banker_name,
		                            text: item.banker_name
		                        }));
		                    });
						},
						error: function () {
							alert("error");
						}
					})
				});

			});	
			$('tr[data-row]:last-of-type').find('select.form-control.ltypes').each(function () {
				if(this.name != 'type[]')
				{
					$(this).find('option:first-of-type').attr('selected');
					$(this).find('option:first-of-type').siblings().removeAttr('selected');
				}

			});
			$('tr[data-row]:last-of-type').find('select.form-control.lprogm').each(function () {
                 
				if(this.name != 'program[]')
				{
					$(this).find('option:first-of-type').attr('selected');
                     
					$(this).find('option:first-of-type').siblings().removeAttr('selected');
				}
			});
			$('tr[data-row]:last-of-type').find('a.deleteRowBtn').attr('style', ' ');
			$('.dobDatepicker__table').datetimepicker({
		        format:'YYYY-MM-DD',
		        useCurrent: false,
		        allowInputToggle: true
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
			
			$('#btn_data').attr("disabled",false);
			// $('.btn_errs').css({
   //              	     'padding-left' : '15px',
			// 			 'color': 'red',
			// 			 'display' : 'block' 
			// 		}).show();
			$('[data-row]').each(function (index) {
				$(this).attr('data-row', (index + 1));
				$(this).find('td[data-no]').html(index + 1);
			});	
		});
	});
	function funcDateTimecalc () {
		$('.dobDatepicker__table').each(function (key) {
			$(this).attr('data-id', 'dateTimePicker-' + key);
		});
		var idsDateTimePicker = $('.dobDatepicker__table').map(function (key) {
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

		$('.bankname').on('change',function(){
			var id = $(this).data('id');
			var bankid = $("#bank-"+id).val();
			console.log(bankid)
			$.ajax({
				url: '/s/admin/add-banker',
				data: {bankid: bankid},
				type: 'POST',
				success:function (resp) {
					console.log(resp)
					console.log(JSON.parse(resp))
					$("#banker-"+id).empty();
					$.each(JSON.parse(resp), function(i, item) {
						console.log(item)
						console.log("#banker-"+id)
						console.log(item.banker_name)
                        $("#banker-"+id).append($('<option>', {
                            value: item.banker_name,
                            text: item.banker_name
                        }));
                    });
				},
				error: function () {
					alert("error");
				}
			})
		});
		
		
		var tot = $("#tot_amt").val();
	     
        $("#tab").on('input', '.txtdata', function () {
           var calculated_total_sum = 0;
     
           $("#tab .txtdata").each(function () {
             var get_textbox_value = $(this).val();

            if ($.isNumeric(get_textbox_value)) {
               calculated_total_sum += parseFloat(get_textbox_value);
               }                  
            });

           if(tot == calculated_total_sum){ 
				
                // $('#btn_data').attr("disabled","disabled");
                $('.btn_err').css({
                	     'padding-left' : '15px',
						 'color': 'red',
						 'display' : 'block' 
					}).show();
					// $("#err_warn").css({
					// 	'display' : 'block'
					// }).show();
     //            $('.btn_errs').css({
     //            	     'padding-left' : '15px',
					// 	 'color': 'red',
					// 	 'display' : 'block' 
					// }).hide();
		    }
		    // else{
		    	 // $("#err_warn").hide();
		    	
		    	// $('#btn_data').attr("disabled",false);
		   //  	$('.btn_errs').css({
     //            	     'padding-left' : '15px',
					// 	 'color': 'red',
					// 	 'display' : 'block' 
					// }).show();
		    // }
        });
            

	});
function Goto_Form_submit(){ 
	$("#btn_sub").attr("disabled", true);
	$(".loadingDiv").show();
	return true;
}
</script>	
@stop