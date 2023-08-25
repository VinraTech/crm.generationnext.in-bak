@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\State; use App\City; ?>
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
				        <form  class="form-horizontal" method="post" @if(!empty($nonapplicantdetails)) action="{{url('/s/admin/add-non-individual-applicant/'.$filedetails['id'].'/'.$nonapplicantdetails['id'])}}" @else action="{{url('/s/admin/add-non-individual-applicant/'.$filedetails['id'])}}" @endif enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('applicant type'); ?>
					                    <label class="col-md-6 control-label">Applicant Type:</label>
					                    <div class="col-md-6">
					                        <select name="applicant_type" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($nonapplicantdetails && $nonapplicantdetails['applicant_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Company Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['company_name']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
				                	<div class="form-group col-md-6">
										<?php $dropdown = FileDropdown::getfiledropdown('company'); ?>
									    <label class="col-md-6 control-label">Company Type :</label>
									    <div class="col-md-6">
									        <select name="company_type" class="selectbox" required> 
									            <option value="">Select</option>
									        	@foreach($dropdown as $key => $dropval)
									            	<option value="{{$dropval['value']}}" {{((!empty($nonapplicantdetails['company_type']) && $nonapplicantdetails['company_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
									            @endforeach
									        </select>
									    </div>
									</div>
									<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">No of yrs:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="No of yrs in current office" name="no_of_yrs" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['no_of_yrs']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Office Address:</label>
					                    <div class="col-md-6">
					                        <textarea  placeholder="Company Address" name="office_address" style="color:gray" autocomplete="off" class="form-control" required>{{(!empty($nonapplicantdetails)?$nonapplicantdetails['office_address']:'' )}}</textarea>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">District:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="District" name="district" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['district']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
									<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">State :</label>
					                    <div class="col-md-6">
					                    	<?php $states = State::getstates(); ?>
					                        <select name="state" class="selectbox getState"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($nonapplicantdetails) && $nonapplicantdetails['state']==$state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">City :</label>
					                    <div class="col-md-6">
					                       <select name="city" class="selectbox" id="AppendCities" required> 
                                            <option value="">Select</option>
                                            @if(!empty($nonapplicantdetails['city']))
                                            	<?php $cities = City::getcities($nonapplicantdetails['state']); ?>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($nonapplicantdetails['city']) && $nonapplicantdetails['city'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Pin:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Pin" name="pin" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['pin']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Landmark:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Landmark" name="landmark" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['landmark']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Country:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Country" name="country" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['country']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Contact Person:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Contact Person" name="contact_person" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['contact_person']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Designation:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Designation" name="designation" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['designation']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
									    <label class="col-md-6 control-label">Date of Incorporation:</label>
									    <div class="col-md-6">
									        <div class="input-group input-append date dobDatepicker">
									            <input type="text" class="form-control" placeholder="Date of Incorporation" name="date_of_incorportaion" autocomplete="off" value="{{(!empty($nonapplicantdetails['date_of_incorportaion'])?$nonapplicantdetails['date_of_incorportaion']:'' )}}">
									            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
									        </div>
									    </div>
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-md-6">
										<?php $dropdown = FileDropdown::getfiledropdown('nature'); ?>
									    <label class="col-md-6 control-label">Nature of Business:</label>
									    <div class="col-md-6">
									        <select name="nature" class="selectbox" required> 
									            <option value="">Select</option>
									        	@foreach($dropdown as $key => $dropval)
									            	<option value="{{$dropval['value']}}" {{((!empty($nonapplicantdetails['nature']) && $nonapplicantdetails['nature'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
									            @endforeach
									        </select>
									    </div>
									</div>
									<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Tel No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Tel No" name="tel_no" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['tel_no']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Mobile No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Mobile No" name="mobile_no" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['mobile_no']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">FAX No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="FAX No" name="fax_no" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['fax_no']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Email:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Email" name="email" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['email']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">GST No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="GST No" name="gst" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['gst']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">PAN No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="PAN No" name="pan" style="color:gray" autocomplete="off" class="form-control"  value="{{(!empty($nonapplicantdetails)?$nonapplicantdetails['pan']:'' )}}">
					                    </div>
					                </div>
				                </div>
				                @if(!empty($getpartners))
					                <div class="row">
					                	<div class="form-group">
										    <label class="col-md-1 control-label"></label> 
										    <div class="col-md-10">
										        <table class="table table-hover table-bordered table-striped">
										        	<thead>
										                <tr>
										                    <th width="18%"></th>
										                    <th>Name</th>
										                    <th>DOB</th>
										                    <th>Nationality</th>
										                    <th>Occupation</th>
										                    <th>Share Holding %</th>
										                </tr>
									                </thead>
										            <tbody>
										            	@foreach($getpartners as $partner)
										            	<input type="hidden" name="partner_id[]" value="{{$partner['id']}}">
										                <tr>
												            <td>
												                Partner / Director/ MD
												            </td>
												            <td>
												                <input type="text" placeholder="Enter Name" name="partner_name[]" style="color:gray" autocomplete="off" class="form-control" value="{{$partner['name']}}">
												            </td>
												            <td>
												                <input type="text" placeholder="Enter DOB" name="partner_dob[]" style="color:gray" autocomplete="off" class="form-control dobDatepicker" value="{{$partner['dob']}}">
												            </td>
												            <td>
												                <input type="text" placeholder="Enter Nationality" name="partner_nationality[]" style="color:gray" autocomplete="off" class="form-control" value="{{$partner['nationality']}}">
												            </td>
												            <td>
												                <input type="text" placeholder="Enter Occupation" name="partner_occupation[]" style="color:gray" autocomplete="off" class="form-control" value="{{$partner['occupation']}}">
												            </td>
												            <td>
												                <input type="text" placeholder="Enter Shareholding" name="partner_shareholding[]" style="color:gray" autocomplete="off" class="form-control" value="{{$partner['shareholding']}}">
												            </td>
												        </tr>
					                					@endforeach
										            </tbody>
										        </table>
										    </div>
										</div>
					                </div>
					            @endif
				                <div class="row">
				                	<div class="form-group"> 
									    <div class="col-md-10 col-md-offset-1">
									        <table id="tblAddRow" class="table table-hover table-bordered table-striped">
									        	<thead>
									                <tr>
									                    <th width="18%"></th>
									                    <th>Name</th>
									                    <th>DOB</th>
									                    <th>Nationality</th>
									                    <th>Occupation</th>
									                    <th>Share Holding %</th>
									                </tr>
								                </thead>
									            <tbody>
									                <tr>
											            <td>
											                Partner / Director/ MD
											            </td>
											            <td>
											                <input type="text" placeholder="Enter Name" name="name[]" style="color:gray" autocomplete="off" class="form-control">
											            </td>
											            <td>
											                <input type="text" placeholder="Enter DOB" name="dob[]" style="color:gray" autocomplete="off" class="form-control dobDatepicker">
											            </td>
											            <td>
											                <input type="text" placeholder="Enter Nationality" name="nationality[]" style="color:gray" autocomplete="off" class="form-control">
											            </td>
											            <td>
											                <input type="text" placeholder="Enter Occupation" name="occupation[]" style="color:gray" autocomplete="off" class="form-control">
											            </td>
											            <td>
											                <input type="text" placeholder="Enter Shareholding" name="shareholding[]" style="color:gray" autocomplete="off" class="form-control">
											            </td>
											        </tr>
									            </tbody>
									        </table>
									        <div class="fullWidth text-center">
									        	<input type="button" id="btnAddRow" value="Add More">
										        <button id="btnDelLastRow" type="button">Delete Last Row</button>
									        </div>
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
// For select all checkbox in table
$('#checkedAll').click(function (e) {
	//e.preventDefault();
    $(this).closest('#tblAddRow').find('td input:checkbox').prop('checked', this.checked);
});
// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRow tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRow tbody tr:last input').val('');
    $('.dobDatepicker').datetimepicker({
        format:'YYYY-MM-DD',
        useCurrent: false,
        allowInputToggle: true
    });
});
// Delete last row in the table
$('#btnDelLastRow').on('click', function() {
    var lenRow = $('#tblAddRow tbody tr').length;
    //alert(lenRow);
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $('#tblAddRow tbody tr:last').remove();
    }
});
// Delete row on click in the table
$('#tblAddRow').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRow tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});
</script>
@stop