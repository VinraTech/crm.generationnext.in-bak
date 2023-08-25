@extends('layouts.adminLayout.backendLayout')

@section('content')

<?php use App\FileDropdown; use App\Employee; use App\File;
$types = FileDropdown::getfiledropdown('facility');
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

                <i class="fa fa-circle"></i>

            </li>

            <li>

                <a href="{{ action('FileController@files') }}">Files</a>

            </li>

        </ul>

        <div class="row" id="RemoveIfTypeChange">

            <div class="col-md-12 ">

                <div class="portlet blue-hoki box ">

				    <div class="portlet-title">

				        <div class="caption">

				            <i class="fa fa-gift"></i>{{$title}}

				        </div>

				    </div>

	    <div class="portlet-body form">

	        <form  class="form-horizontal" method="post" action="{{url('/s/admin/generate-file/'.$clientdetail->id)}}" enctype="multipart/form-data" autocomplete="off" onsubmit="return Generate_File_Number()">@csrf

	        	<div class="form-body">

	                <div class="row">

	                	@if(isset($_GET['fileid']) && is_numeric($_GET['fileid']))

			                <input type="hidden" name="old_file" value="{{$_GET['fileid']}}">

			            @endif

		                <div class="clearfix"></div>

	                	<div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Applicant Name :</label>

		                    <div class="col-md-6">

		                        <p style="margin-top:8px;">{{$clientdetail->customer_name}}</p>

		                    </div>

		                </div>

		                <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Company Name :</label>

		                    <div class="col-md-6">

		                        <p style="margin-top:8px;">{{$clientdetail->company_name}}</p>

		                    </div>

		                </div>

		                <div class="clearfix"></div>

		                <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Mobile :</label>

		                    <div class="col-md-6">

		                        <p style="margin-top:8px;">{{$clientdetail->mobile}}</p>

		                    </div>

		                </div>

		                <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">PAN :</label>

		                    <div class="col-md-6">

		                        <p style="margin-top:8px;">{{$clientdetail->pan}}</p>

		                    </div>

		                </div>
		                <div class="form-group col-md-6">

                                <label class="col-md-6 control-label">Address :</label>

                                <div class="col-md-6">

                                  <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="{{$clientdetail->permenant_address}}" required>

                                </div>
                            </div>

		                <div class="clearfix"></div>

		                <!-- <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Select Department:</label>

		                    <?php $departments = array('Mortgage','Car Loan','Business Loan') ?>

		                    <div class="col-md-6">

		                        <select name="department" class="selectbox"> 

		                            <option value="">Select</option>

		                        	@foreach($departments as $key => $department)

		                            	<option value="{{$department}}" @if(!empty($filedetail['department']) && $filedetail['department'] == $department) selected @endif>{{$department}}</option>

		                            @endforeach

		                        </select>

		                    </div>

		                </div> -->

		                <!-- <div class="form-group col-md-6">

		                	<?php $facilities = FileDropdown::getfiledropdown('facility'); ?>

		                    <label class="col-md-6 control-label">Type of Loan:</label>

		                    <div class="col-md-6">

		                        <select name="facility_type" class="selectbox"> 

		                            <option value="">Select</option>

		                        	@foreach($facilities as $key => $facility)

		                            	<option value="{{$facility['value']}}" @if(!empty($filedetail['facility_type']) && $filedetail['facility_type'] == $facility['value']) selected @endif>{{$facility['value']}}</option>

		                            @endforeach

		                        </select>

		                    </div>

		                </div> -->
                      
		                <div class="clearfix"></div>

						<?php $directTypes = Employee::gettypes('direct'); 
                          
                           $em_id = array();
                           if(Session::get('empSession')['type'] == "admin"){
                           if($clientdetail['tel_name'] != ""){
						   $empd = Employee::where('id',$clientdetail['tel_name'])->first();
						   $empd = json_decode(json_encode($empd),true);
						   }else{
						   	 $cli = DB::table('channel_partners')->where('id',$clientdetail['channel_partner'])->first();
						   	 $cli = json_decode(json_encode($cli),true);

						   	 $empd = Employee::where('id',$cli['emp_id'])->first();
						   	 $empd = json_decode(json_encode($empd),true);

						   }
						}else{
							$arr_id = Employee::empsiddata();
                           
							$clientdata = DB::table('clients')->whereIn('tel_name',$arr_id)->get();
							$clientdata = json_decode(json_encode($clientdata),true);
							// $clins = DB::table('clients')->where('channel_partner','!=','')->get();
							// $clins = json_decode(json_encode($clins),true);
							
							$empd = '';
                           if(count($clientdata))
                           {
                           		foreach($clientdata as $clien){
								
									  $empd = Employee::where('id',$clien['tel_name'])->first();
							          $empd = json_decode(json_encode($empd),true);
	                                 
								}
                           }
						   
							// foreach($clins as $clin){
							// 		$cli = DB::table('channel_partners')->where('id',$clin['channel_partner'])->first();
						 //   	        $cli = json_decode(json_encode($cli),true);

						 //   	        $empd = Employee::where('id',$cli['emp_id'])->first();
						 //   	        $empd = json_decode(json_encode($empd),true);
							// 	}
							
						}

						$tl = false;
						if(isset($empd['parent_id']) && $empd['parent_id'] != '')
						{
							$tl = Employee::getempldata($empd['parent_id']);

							array_push($em_id, $empd['id']);
					       if($tl == true){
		               		array_push($em_id, $empd['parent_id']);
                               $tldata = Employee::where('id',$empd['parent_id'])->first();
                               $tldata = json_decode(json_encode($tldata),true);

                              if(!empty($tldata)){

                                		$bm = Employee::getempldata($tldata['parent_id']);

                                		if($bm == true){
                                			array_push($em_id, $tldata['parent_id']);
                                			$bmdata = Employee::where('id',$tldata['parent_id'])->first();
                                			$bmdata = json_decode(json_encode($bmdata),true);

                                			if(!empty($bmdata)){
                                				$bh = Employee::getempldata($bmdata['parent_id']);
                                				if($bh == true){
                                					array_push($em_id, $bmdata['parent_id']);
                                					$bhdata = Employee::where('id',$bmdata['parent_id'])->first();
                                					$bhdata = json_decode(json_encode($bhdata),true);

                                				}
                                			}
                                		}
                                	
                                
                              }
					       }
						}
					      
        if($clientdetail->channel_partner != ""){
           $chp = DB::table('channel_partners')->where('id',$clientdetail->channel_partner)->first();    
        }

		$brh = Employee::whereIn('id',$em_id)->where('type','BH')->first();
		$brh = json_decode(json_encode($brh),true);
        $bms = Employee::whereIn('id',$em_id)->where('type','bm')->first();
        $bms = json_decode(json_encode($bms),true);                   
					   
		$tls = Employee::whereIn('id',$em_id)->where('type','TL')->first();
		$tls = json_decode(json_encode($tls),true);	

		$tel = Employee::whereIn('id',$em_id)->where('type','tel')->first();
		$tel = json_decode(json_encode($tel),true);			   
						?>
                         
						@foreach($directTypes as $dkey=> $directType)

							@if($directType['short_name'] =="bm")
                                 
								<?php $emps = Employee::getemployees('bm');?>

							@else
                                 
								<?php $emps = Employee::getemployees('all');?>

							@endif

							
                           
							<div class="form-group col-md-6">
                                
		                		<label class="col-md-6 control-label">{{$directType['full_name']}}:</label>
                                
			                    <div class="col-md-6">
                                      
			                        <select name="emps[]" class="selectbox" disabled> 
			                        	<?php if($directType['full_name'] == 'Business Manager'){ ?>

			                            <option value="{{$directType['short_name']}}-{{$bms['id']}}">{{$bms['name']}} - {{$directType['full_name']}}</option>
			                            <?php $emp1 = $directType['short_name'].'-'.$bms['id'];
			                           

			                            ?>
			                            <?php echo "required"; } elseif($directType['full_name'] == 'Branch Head'){?>
			                              <option value="{{$directType['short_name']}}-{{$brh['id']}}">{{$brh['name']}} - {{$directType['full_name']}}</option>
			                              <?php
                                           $emp2 = $directType['short_name'].'-'.$brh['id'];
                                          
			                              ?>
			                            <?php echo "required"; } elseif($directType['full_name'] == 'Telecaller'){?>

			                             <option value="{{$directType['short_name']}}-{{$tel['id']}}">{{$tel['name']}} - {{$directType['full_name']}}</option>
			                             <?php
			                                $emp3 = $directType['short_name'].'-'.$tel['id']; 
			                                
			                             ?>
			                             <?php echo "required";} elseif($directType['full_name'] == 'Channel Partner'){ ?>
			                             	@if($clientdetail->channel_partner != "")
			                             	 <option value="{{$directType['short_name']}}-{{$chp->id}}">{{$chp->name}} - {{$directType['full_name']}}</option>
			                             	 <?php 
			                             	    $emp4 = $directType['short_name'].'-'.$chp->id;
			                             	    
			                             	 ?>
			                             	@else
			                             	 <option value="">Select</option>
			                             	 <?php $emp4 = "";?>
			                             	@endif
                                          <?php echo "required";} elseif($directType['full_name'] == 'Team Leader'){?>
                                          	<option value="{{$directType['short_name']}}-{{$tls['id']}}">{{$tls['name']}} - {{$directType['full_name']}}</option>
                                          	<?php 
                                          	  $emp5 = $directType['short_name'].'-'.$tls['id'];
                                          	  
                                          	?>
			                            	<?php echo "required";} else{?>
			                            		<option value="">Select</option>
			                            		<?php echo "required"; }?>
                                        
			                        	@foreach($emps as $key => $emp)

			                        		<?php $sel=""; ?>

											@if(!empty($filedetail)) 

												<?php $sel = File::checkfoSel($filedetail['id'],$directType['short_name'],$emp['id']); ?>

											@endif
                                             
			                            	<option value="{{$directType['short_name']}}-{{$emp['id']}}" {{$sel}}>{{$emp['name']}} - {{$emp['emptype']}}</option>

			                            @endforeach

			                        </select>
                                  
			                    </div>

		               		</div>
		               		

		               		@if ($dkey % 2 == 0)

		               			<div class="clearfix"></div>

		               		@endif

						@endforeach
                         <input type="hidden" name="empa" value="{{$emp1}}">
                         <input type="hidden" name="empb" value="{{$emp2}}">
                         <input type="hidden" name="empc" value="{{$emp3}}">
                         <input type="hidden" name="empd" value="{{$emp4}}">
                         <input type="hidden" name="empe" value="{{$emp5}}">
		            	<?php $employees = Employee::getemployees('all'); ?>

		                

		                <div class="clearfix"></div>

		               <!--  <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Select Type :</label>

		                    <div class="col-md-6">

		                        <select name="file_type" class="selectbox getFiletype" required> 

		                            <option value="">Select</option>

		                        	<option value="direct" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "direct") selected @endif>Direct</option>

		                        	<option value="indirect" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "indirect") selected @endif>Indirect</option>

		                        	<option value="fse" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "fse") selected @endif>FSE (Field Sales Executive)</option>

		                        	<option value="other" @if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "other") selected @endif>Other Department</option>

		                        </select>

		                    </div>

		                </div> -->

		                <div id="Appendcrm">

		                	@if(!empty($filedetail['file_type']) && ($filedetail['file_type'] == "indirect" || $filedetail['file_type'] == "other" ))

		                	<?php $indirectTypes = Employee::gettypes('indirect'); ?>

		                	@foreach ($indirectTypes as $indkey => $indirect)

								<div class="form-group col-md-6">

				                    <label class="col-md-6 control-label">{{$indirect['full_name']}}:</label>

				                    <div class="col-md-6">

				                        <select name="emps[]" class="selectbox">

				                            <option value="">Select</option>';

				                            @foreach($employees as $key => $emp)

				                            	<?php $sel=""; ?>

												@if(!empty($filedetail)) 

													<?php $sel = File::checkfoSel($filedetail['id'],$indirect['short_name'],$emp['id']); ?>

												@endif

		                                        <option value="{{$indirect['short_name']}}-{{$emp['id']}}" {{$sel}}>{{$emp['name']}} - {{$emp['emptype']}}</option>

		                                    @endforeach

				                        </select>

				                    </div>

	                			</div>

	                			@if($indkey ==0)

	                				<div class="clearfix"></div>

	                			@endif

		                	@endforeach

		                	@if(!empty($filedetail['file_type']) && $filedetail['file_type'] == "indirect")

                    		<div class="form-group col-md-6">

			                    <label class="col-md-6 control-label">Select Channel Partner :</label>

			                    <div class="col-md-6">

			                        <select name="channel_partner_id" class="selectbox">

			                            <option value="">Select</option>';

			                            @foreach($getpartners as $key => $partner)

	                                        <option value="{{$partner['id']}}" @if(!empty($filedetail['channel_partner_id']) && $filedetail['channel_partner_id'] == $partner['id']) selected @endif>{{$partner['name'] }} - {{$partner['type']}}</option>

	                                    @endforeach

			                        </select>

			                    </div>

			                </div>

			                @endif

                    		@endif

		                </div>

		                <div class="clearfix"></div>

                    		<!-- <div class="form-group col-md-6">

			                    <label class="col-md-6 control-label">LTS Number :</label>

			                    <div class="col-md-6">

			                        <input type="text" placeholder="LTS Number" name="lts_no" style="color:gray" autocomplete="off" class="form-control" value="{{!empty($filedetail['lts_no'])?$filedetail['lts_no']:''}}" required>

			                    </div>

			                </div> -->
			                <div class="form-group col-md-6">

		                    <label class="col-md-6 control-label">Loan or Insurance :</label>

		                    <div class="col-md-6">

		                        <select id = "loan_data" name="loan_ins" class="selectbox getFiletype" required> 

		                            <option value="">Select</option>

		                        	<option value="loan" @if(!empty($filedetail['loan_ins']) && $filedetail['loan_ins'] == "loan") selected @endif>Loan</option>

		                        	<option value="insurance" @if(!empty($filedetail['loan_ins']) && $filedetail['loan_ins'] == "insurance") selected @endif>Insurance</option>

		                        </select>

		                    </div>

		                </div>
		                <div class="form-group col-md-6" style="display: none" id="type_of_loan">
		                	    <label class="col-md-6 control-label">Loan Type :</label>
		                	    <div class="col-md-6">
		                	        <select name="loan_type" id="" required class="form-control type_data">
		        			<option value="" selected="selected">Select Type</option>
		        			@foreach($types as $type)
		        				<option value="{{$type['value']}}" @if(!empty($filedetail['loan_type']) && $filedetail['loan_type'] == $type['value']) selected @endif>{{$type['value']}}</option>
		        			@endforeach
		        		</select>
		                	    </div>
		                </div>
		                @if(!empty($filedetail))
		                 <div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Loan Type :</label>
		                	    <div class="col-md-6">
		                	        <select name="loan_type" id="" required class="form-control type_data">
		        			<option value="" selected="selected">Select Type</option>
		        			@foreach($types as $type)
		        				<option value="{{$type['value']}}" @if(!empty($filedetail['loan_type']) && $filedetail['loan_type'] == $type['value']) selected @endif>{{$type['value']}}</option>
		        			@endforeach
		        		</select>
		                	    </div>
		                </div>
		                @endif

			                <div class="form-group col-md-6">

			                    <label class="col-md-6 control-label">Remarks :</label>

			                    <div class="col-md-6">

			                        <textarea type="text" placeholder="Remarks" name="remarks" style="color:gray" autocomplete="off" class="form-control" value="{{!empty($filedetail['remarks'])?$filedetail['remarks']:''}}"></textarea>

			                    </div>

			                </div>

			                
                           
			                
                            
		                <div class="form-group col-md-6" style="display: none" id="type_of_program">
		                	    <label class="col-md-6 control-label">Program :</label>
		                	    <div class="col-md-6">
		                	     <select name="program" id=""  class="form-control prog">
						        			
						        			<option value="" selected="selected">Select Program</option>
                                            
						        			
						        		</select>
		                	    </div>
		                </div>
		                @if(!empty($filedetail))
		                 <div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Program :</label>
		                	    <div class="col-md-6">
		                	     <select name="program" id=""  class="form-control prog">
						        	<?php
						        			  if($filedetail['loan_type'] == "Personal Loan"){
						        			  	$programArr = array("Income","RTR");
						        			  }elseif($filedetail['loan_type'] == "Business Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedetail['loan_type'] == "Home Loan- Construction"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","Liquid Income");
						        			  }elseif($filedetail['loan_type'] == "Home Equity - Residential" || $filedetail['loan_type'] == "Home Equity- Commercial"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Low LTV","Rental","LRD","Liquid Income");
						        			  }elseif($filedetail['loan_type'] == "Working Capital"){
                                                 $programArr = array("Income","GST","Banking","Turn Over","RTR","Rental","Liquid Income");
						        			  }elseif($filedetail['loan_type'] == "Used Car Loan" || @$filedata['loan_type'] == "New Car Loan"){
						        			  	$programArr = array("Income","GST","Banking","Turn Over","RTR","Liquid Income");
						        			  }elseif($filedetail['loan_type'] == "Credit Card" || $filedetail['loan_type'] == "Health Insurance" || $filedetail['loan_type'] == "Life Insurance" || $filedetail['loan_type'] == "General Insurance"){
						        			  	$programArr = array("Income");
						        			  }else{
						        			  	$programArr = array();
						        			  }
						        			?>
						        			<option value="" selected="selected">Select Program</option>
                                            @foreach($programArr as $program)
					        				<option value="{{$program}}" @if(!empty($filedetail['program']) && $filedetail['program'] == $program) selected @endif>{{$program}}</option>
					        			@endforeach
						        			
						        		</select>
		                	    </div>
		                </div>
		                @endif
		                <div class="form-group col-md-6" style="display: none" id="type_of_insurance">
		                	    <label class="col-md-6 control-label">Insurance Type :</label>
		                	    <div class="col-md-6">
		                	        <input type="text" class="form-control" name="insurance_type" placeholder="Insurance Type" style="color:gray">
		                	    </div>
		                </div>
		                <div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Loan Amount :</label>
		                	    <div class="col-md-6">
		                	        <input type="number" required class="form-control" name="loan_amount" placeholder="Loan Amount" style="color:gray">
		                	    </div>
		                </div>
                           
			                <div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Pan Card:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="pan_card[]" style="color:gray">
		                	    </div>
		                	</div>


		                	
		                 
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Adhaar Card:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="adhaar_card[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Salary Slip:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="salary_slip[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Bank Passbook:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="bank_passbook[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Voter Id:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="voter_id[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Passport:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="passport[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Driving Licence:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="driving_licence[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Rent Agreement:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="rent_agreement[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Letterhead:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="letterhead[]" style="color:gray">
		                	    </div>
		                	</div>
		                	<div class="form-group col-md-6">
		                	    <label class="col-md-6 control-label">Photo:</label>
		                	    <div class="col-md-6">
		                	        <input type="file" class="form-control" name="photo[]" style="color:gray">
		                	    </div>
		                	</div>

	                </div>

	            </div>

	            <div class="form-actions right1 text-center">

	                <button class="btn green" type="submit" id="generate_file_button">Procced & Generate File Number</button>

	            </div>

	        </form>

		</div>

				</div>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">

	$(document).ready(function(){

		$(document).on('change','.getFiletype',function(){

			var type = $(this).val();

			if(type =="indirect"|| type =="other" ){

				$('.loadingDiv').show();

				$.ajax({

					data : {type :type},

					url : '/s/admin/append-crm',

					type : 'post',

					success:function(resp){

						$('#Appendcrm').html(resp);

						$('.loadingDiv').hide();

					},

					error:function(){}

				})

			}else{

				$('#Appendcrm').html('');

			}

		});
		$("#loan_data").change(function () {
            if ($(this).val() == "loan") {
                $("#type_of_loan").show();
                $("#type_of_program").show();
                $("#type_of_insurance").hide();
            } else if($(this).val() == "insurance") {
                $("#type_of_insurance").show();
                $("#type_of_loan").hide();
                $("#type_of_program").hide();
            }else{
            	$("#type_of_loan").hide();
            	$("#type_of_program").hide();
            	$("#type_of_insurance").hide();
            }
        });

	});
function Generate_File_Number(){ 
	$("#generate_file_button").attr("disabled", true);
	$(".loadingDiv").show();
	return true;
}
</script>

@stop