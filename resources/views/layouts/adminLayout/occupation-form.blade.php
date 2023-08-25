<?php use App\FileDropdown; use App\Employee; ?>
<hr>
@if($applicantdetails['occupation']=="Salaried")
<div class="form-group col-md-6">
	<?php $dropdown = FileDropdown::getfiledropdown('company'); ?>
    <label class="col-md-6 control-label">Company Type :</label>
    <div class="col-md-6">
        <select name="company_type" class="selectbox" required> 
            <option value="">Select</option>
        	@foreach($dropdown as $key => $dropval)
            	<option value="{{$dropval['value']}}" {{((!empty($applicantdetails['company_type']) && $applicantdetails['company_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group col-md-6">
	<?php $dropdown = FileDropdown::getfiledropdown('occupation type'); ?>
    <label class="col-md-6 control-label">Occupation Type :</label>
    <div class="col-md-6">
        <select name="occupation_type" class="selectbox" required> 
            <option value="">Select</option>
        	@foreach($dropdown as $key => $dropval)
            	<option value="{{$dropval['value']}}" {{((!empty($applicantdetails['occupation_type']) && $applicantdetails['occupation_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Company Name :</label>
    <div class="col-md-6">
        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($applicantdetails['company_name'])?$applicantdetails['company_name']:'' )}}">
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Department:</label>
    <div class="col-md-6">
        <input type="text" placeholder="Department" name="department" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['department'])?$applicantdetails['department']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Designation :</label>
    <div class="col-md-6">
        <input type="text" placeholder="Designation" name="designation" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($applicantdetails['designation'])?$applicantdetails['designation']:'' )}}">
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Work Experience :</label>
    <div class="col-md-6">
        <input type="text" placeholder="Work Experience" name="work_experience" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['work_experience'])?$applicantdetails['work_experience']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Spouse Working:</label>
    <div class="col-md-6">
        <select name="spouse_working" class="selectbox" required> 
            <option value="No" {{((!empty($applicantdetails['spouse_working']) && $applicantdetails['spouse_working'] == 'No')? 'selected':'')}}>No</option>
            <option value="Yes" {{((!empty($applicantdetails['spouse_working']) && $applicantdetails['spouse_working'] == 'Yes')? 'selected':'')}}>Yes</option>
        </select>
    </div>
</div>
@elseif($applicantdetails['occupation']=="Self Employed")
<div class="form-group col-md-6">
	<?php $dropdown = FileDropdown::getfiledropdown('in business'); ?>
    <label class="col-md-6 control-label">In Business :</label>
    <div class="col-md-6">
        <select name="in_business" class="selectbox" required> 
            <option value="">Select</option>
        	@foreach($dropdown as $key => $dropval)
            	<option value="{{$dropval['value']}}" {{((!empty($applicantdetails['in_business']) && $applicantdetails['in_business'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group col-md-6">
	<?php $dropdown = FileDropdown::getfiledropdown('professional'); ?>
    <label class="col-md-6 control-label">Professional :</label>
    <div class="col-md-6">
        <select name="professional" class="selectbox" required> 
            <option value="">Select</option>
        	@foreach($dropdown as $key => $dropval)
            	<option value="{{$dropval['value']}}" {{((!empty($applicantdetails['professional']) && $applicantdetails['professional'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">No. of yrs in Business :</label>
    <div class="col-md-6">
        <input type="text" placeholder="No. of yrs in Business" name="no_of_yrs_business" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['no_of_yrs_business'])?$applicantdetails['no_of_yrs_business']:'' )}}" required>
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Company Name:</label>
    <div class="col-md-6">
        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_name'])?$applicantdetails['company_name']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Office Address:</label>
    <div class="col-md-6">
        <input type="text" placeholder="Office Address" name="office_address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['office_address'])?$applicantdetails['office_address']:'' )}}" required>
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Company Landmark :</label>
    <div class="col-md-6">
        <input type="text" placeholder="Company Landmark" name="company_landmark" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_landmark'])?$applicantdetails['company_landmark']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">State:</label>
    <div class="col-md-6">
        <input type="text" placeholder="State" name="company_state" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_state'])?$applicantdetails['company_state']:'' )}}" required>
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">City:</label>
    <div class="col-md-6">
        <input type="text" placeholder="City" name="company_city" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_city'])?$applicantdetails['company_city']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Pin :</label>
    <div class="col-md-6">
        <input type="text" placeholder="Pin" name="company_pin" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_pin'])?$applicantdetails['company_pin']:'' )}}" required>
    </div>
</div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Offical Email id :</label>
    <div class="col-md-6">
        <input type="email" placeholder="Offical Email id" name="company_email" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($applicantdetails['company_email'])?$applicantdetails['company_email']:'' )}}" required>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-md-6">
    <label class="col-md-6 control-label">Date of Incorporation:</label>
    <div class="col-md-6">
        <div class="input-group input-append date dobDatepicker">
            <input type="text" class="form-control" placeholder="Date of Incorporation" name="date_of_incorporation" autocomplete="off" value="{{(!empty($applicantdetails['date_of_incorporation'])?$applicantdetails['date_of_incorporation']:'' )}}">
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
    </div>
</div>
@elseif($applicantdetails['occupation']=="Others")
<div class="form-group col-md-6">
	<?php $dropdown = FileDropdown::getfiledropdown('if others'); ?>
    <label class="col-md-6 control-label">If Others :</label>
    <div class="col-md-6">
        <select name="occupation_other" class="selectbox" required> 
            <option value="">Select</option>
        	@foreach($dropdown as $key => $dropval)
            	<option value="{{$dropval['value']}}" {{((!empty($applicantdetails['occupation_other']) && $applicantdetails['occupation_other'] == $dropval['value'])? 'selected':'')}} >{{$dropval['value']}}</option>
            @endforeach
        </select>
    </div>
</div>
@endif