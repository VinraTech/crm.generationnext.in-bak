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
				        <form  class="form-horizontal" method="post" @if(!empty($assetdetails)) action="{{url('/s/admin/add-asset-detail/'.$filedetails['id'].'/'.$assetdetails['id'])}}" @else action="{{url('/s/admin/add-asset-detail/'.$filedetails['id'])}}" @endif enctype="multipart/form-data" autocomplete="off">@csrf
				        	<div class="form-body">
				                <div class="row">
				                	<div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Asset Type:</label>
					                    <div class="col-md-6">
					                        <select name="asset_type" class="form-control" required>
					                        <?php $dropdown = FileDropdown::getfiledropdown('asset type'); ?>
						                    	@foreach($dropdown as $key => $dropval)
					                            	<option value="{{$dropval['value']}}" {{(!empty($assetdetails && $assetdetails['asset_type'] == $dropval['value'])? 'selected':'')}}>{{$dropval['value']}}</option>
					                            @endforeach
					                    	</select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Registeration No. :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="Registeration No" name="reg_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($assetdetails)?$assetdetails['reg_no']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">No of Owners. :</label>
					                    <div class="col-md-6">
					                        <input type="text" placeholder="No of Owners" name="no_of_owners" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($assetdetails)?$assetdetails['no_of_owners']:'' )}}">
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Vehicle Cost:</label>
					                    <div class="col-md-6">
					                        <input type="number" placeholder="Vehicle Cost" name="vehicle_cost" style="color:gray" autocomplete="off" class="form-control" required value="{{(!empty($assetdetails)?$assetdetails['vehicle_cost']:'' )}}">
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Make:</label>
					                    <div class="col-md-3">
					                        <select name="make_year" class="form-control" required>
					                        	<option>Year</option>
					                        	@for($i=2002; $i<=2050;$i++)
					                        		<option value="{{$i}}" @if(!empty($assetdetails) && $assetdetails['make_year'] == $i) selected @endif>{{$i}}</option>
					                        	@endfor
					                    	</select>
					                    </div>
					                    <div class="col-md-3">
					                        <select name="make_month" class="form-control" required>
					                        	<option>Month</option>
					                        	@for ($m=1; $m<=12; $m++)
											    	<?php $month = date('F', mktime(0,0,0,$m, 1, date('Y')));?>
											     	<option value="{{$month}}" @if(!empty($assetdetails) && $assetdetails['make_month'] == $month) selected @endif >{{$month}}</option>
											    @endfor
					                    	</select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Insurance Expiry Date:</label>
					                    <div class="col-md-6">
					                        <div class="input-group input-append date dobDatepicker">
		                                        <input type="text" class="form-control" placeholder="Select Insurance Expiry Date" name="insurance_expiry_date" autocomplete="off" value="{{(!empty($assetdetails)?$assetdetails['insurance_expiry_date']:'' )}}" required>
		                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		                                    </div>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Select Company:</label>
					                    <div class="col-md-6">
					                        <select name="company_id" class="form-control" required id="getcompany">
					                        	<option>Select Company</option>
					                        	@foreach($companies as $key => $company)
                                                	<option value="{{$company['id']}}" @if(!empty($assetdetails['company_id']) && $assetdetails['company_id'] == $company['id']) selected @endif>{{$company['name']}}</option>
                                           	 	@endforeach
					                    	</select>
					                    </div>
					                </div>
					                <div class="form-group col-md-6">
					                    <label class="col-md-6 control-label">Select Model:</label>
					                    <div class="col-md-6">
					                        <select name="company_model_id" class="form-control" required id="AppendModels">
					                        	<option>Select Model</option>
					                        	@foreach($modeldetails as $key => $model)
                                                	<option value="{{$model['id']}}" @if(!empty($assetdetails['company_model_id']) && $assetdetails['company_model_id'] == $model['id']) selected @endif>{{$model['model']}} - {{$model['variant']}} - {{$model['type']}}</option>
                                           	 	@endforeach
					                    	</select>
					                    </div>
					                </div>
					                <div class="clearfix"></div>
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
	$(document).on('change','#getcompany',function(){
		$('.loadingDiv').show();
		var companyid = $(this).val();
		$.ajax({
			type : 'post',
			url : '/s/admin/get-company-models',
			data : {companyid: companyid},
			success:function(resp){
				$('#AppendModels').html(resp);
				$('.loadingDiv').hide();
			},
			error:function(){}
		})
	});
</script>
@stop