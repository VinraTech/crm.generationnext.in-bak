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
				        <form  class="form-horizontal" method="post" @if(!empty($referdetail)) action="{{url('/s/admin/add-reference/'.$filedetails['id'].'/'.$referdetail['id'])}}" @else action="{{url('/s/admin/add-reference/'.$filedetails['id'])}}" @endif enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Name :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Name" name="name" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($referdetail)?$referdetail['name']:'' )}}">
					                    </div>
					                </div>
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Address:</label>
					                    <div class="col-md-6">
					                        <textarea  placeholder="Enter Address" name="address" style="color:gray" autocomplete="off" class="form-control" required>{{(!empty($referdetail)?$referdetail['address']:'' )}}</textarea>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Pin :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Pin" name="pin" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($referdetail)?$referdetail['pin']:'' )}}">
					                    </div>
					                </div>
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Tel No.:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Tel No." name="tel_no" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($referdetail)?$referdetail['tel_no']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Mobile No:</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Mobile No." name="mobile_no" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($referdetail)?$referdetail['mobile_no']:'' )}}">
					                    </div>
					                </div>
					                 <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('relationship'); ?>
					                    <label class="col-md-6 control-label">Relationship with Reference:</label>
					                    <div class="col-md-6">
					                        <select name="relationship" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($referdetail && $referdetail['relationship'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                        </select>
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