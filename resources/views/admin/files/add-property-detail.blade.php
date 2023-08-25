@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; ?>
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
				        <form  class="form-horizontal" method="post" @if(!empty($propertydetails)) action="{{url('/s/admin/add-property-detail/'.$filedetails['id'].'/'.$propertydetails['id'])}}" @else action="{{url('/s/admin/add-property-detail/'.$filedetails['id'])}}" @endif enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Address:</label>
					                    <div class="col-md-6">
					                        <textarea  placeholder="Enter Address" name="address" style="color:gray" autocomplete="off" class="form-control" required>{{(!empty($propertydetails)?$propertydetails['address']:'' )}}</textarea>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Area :</label>
					                    <div class="col-md-3">
					                        <input type="text" placeholder="Area" name="area" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['area']:'' )}}">
					                    </div>
					                    <div class="col-md-3">
					                    <select name="area_type" class="form-control">
					                    	<option value="sq mtr" {{(!empty($propertydetails && $propertydetails['area_type'] == 'sq mtr')? 'selected':'')}}>Sq Mtr</option>
					                    	<option value="sq" {{(!empty($propertydetails && $propertydetails['area_type'] == 'sq')? 'selected':'')}}>Sq Ft.</option>
					                    	<option value="yd" {{(!empty($propertydetails && $propertydetails['area_type'] == 'yd')? 'selected':'')}}>Sq yds</option>
					                    </select>
					                </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Pin :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Pin" name="pin" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['pin']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">State :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="State" name="state" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['state']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Disctrict :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Disctrict" name="district" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['district']:'' )}}">
					                    </div>
					                </div>
				                	<div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('purchased'); ?>
					                    <label class="col-md-6 control-label">Purchased From:</label>
					                    <div class="col-md-6">
					                        <select name="purchased_from" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($propertydetails && $propertydetails['purchased_from'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('construction'); ?>
					                    <label class="col-md-6 control-label">Construction Stage:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Specify Stage %" name="construction_stage" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['construction_stage']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Land Area :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Land Area" name="land_area" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['land_area']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Built Up Area :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Built Up Area" name="built_up_area" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['built_up_area']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('property type'); ?>
					                    <label class="col-md-6 control-label">Property Type:</label>
					                    <div class="col-md-6">
					                        <select name="property_type" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($propertydetails && $propertydetails['property_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('property status'); ?>
					                    <label class="col-md-6 control-label">Property Status:</label>
					                    <div class="col-md-6">
					                        <select name="property_status" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($propertydetails && $propertydetails['property_status'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Property Age :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Property Age in years" name="property_age" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['property_age']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Property Value :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Property Value" name="property_value" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['property_value']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Owner of  Property :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Owner of  Property" name="property_title" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($propertydetails)?$propertydetails['property_title']:'' )}}">
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
@stop