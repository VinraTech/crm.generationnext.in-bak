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
				        <form  class="form-horizontal" method="post" action="{{url('/s/admin/add-facility-requirement/'.$filedetails['id'])}}" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Amount Requested :</label>
					                    <div class="col-md-6">
					                        <input type="number" placeholder="Amount Requested" name="amount_requested" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($filedetails)?$filedetails['amount_requested']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Term:</label>
					                    <div class="col-md-3">
					                        <input type="number" placeholder="Term" name="term" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($filedetails)?$filedetails['term']:'' )}}">
					                    </div>
					                    <div class="col-md-3">
						                    <select name="term_type" class="form-control">
						                    	<option value="years" {{(!empty($filedetails && $filedetails['term_type'] == 'years mtr')? 'selected':'')}}>Years</option>
						                    	<option value="months" {{(!empty($filedetails && $filedetails['term_type'] == 'months')? 'selected':'')}}>Months</option>
						                    </select>
						                </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                	<?php $dropdown = FileDropdown::getfiledropdown('interest type'); ?>
					                    <label class="col-md-6 control-label">Type of Interest:</label>
					                    <div class="col-md-6">
					                        <select name="type_of_interest" class="selectbox" required> 
					                            <option value="">Select</option>
					                        	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($filedetails && $filedetails['type_of_interest'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
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