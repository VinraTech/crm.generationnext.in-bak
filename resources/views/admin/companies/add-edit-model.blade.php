@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Content Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('ClientController@companyModels') }}">Company Models</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form  role="form" class="form-horizontal" method="post" @if(empty($modeldata)) action="{{ url('s/admin/add-edit-model') }}" @else  action="{{ url('s/admin/add-edit-model/'.$modeldata['id']) }}" @endif enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Company :</label>
                                    <div class="col-md-4">
                                        <select name="company_id" class="selectbox" required>
                                            <option value="">Select</option>
                                            @foreach($companies as $key => $company)
                                                <option value="{{$company['id']}}" @if(!empty($modeldata['company_id']) && $modeldata['company_id'] == $company['id']) selected @endif>{{$company['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Model:</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Model" name="model" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($modeldata['model']))?$modeldata['model']: '' }}" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Variant:</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Variant" name="variant" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($modeldata['variant']))?$modeldata['variant']: '' }}" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Type :</label>
                                    <?php  $typeArr = array('Petrol','Diesel') ?>
                                    <div class="col-md-4">
                                        <select name="type" class="selectbox" required>
                                            <option value="">Select</option>
                                            @foreach($typeArr as $key => $type)
                                                <option value="{{$type}}" @if(!empty($modeldata['type']) && $modeldata['type'] == $type) selected @endif>{{$type}}</option>
                                            @endforeach
                                        </select>
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