@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Lead Status's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('MasterController@allleadStatus') }}">Lead Status</a>
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
                        <form  role="form"  id="addEditLeadStatusForm" class="form-horizontal" method="post" @if(empty($getStatusdetails)) action="{{ url('s/admin/add-edit-lead-status') }}" @else  action="{{ url('s/admin/add-edit-lead-status/'.$getStatusdetails['id']) }}" @endif> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Name" name="name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getStatusdetails['name']))?$getStatusdetails['name']: '' }}" @if(!empty($getStatusdetails)) readonly @endif />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Type :</label>
                                    <?php $typeArray = array('None','Appointment Date & Time'); ?>
                                    <div class="col-md-5">
                                        <select name="type" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($typeArray as $key=> $tarr)
                                                <option value="{{$key}}" @if(!empty($getStatusdetails) && $getStatusdetails['type'] == $key) selected @endif>{{$tarr}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Lead Behaviour :</label>
                                    <?php $behaviourArr = array('Reminder','Inactive','Closed'); ?>
                                    <div class="col-md-5">
                                        <select name="lead_behaviour" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($behaviourArr as $key=> $arr)
                                                <option value="{{$arr}}" @if(!empty($getStatusdetails) && $getStatusdetails['lead_behaviour'] == $arr) selected @endif>{{$arr}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Visible in Add Lead :</label>
                                    <?php $yesNoArray = array('1'=>'Yes','0'=>'No'); ?>
                                    <div class="col-md-5">
                                        <select name="add_lead_status" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($yesNoArray as $key=> $arr)
                                                <option value="{{$key}}" @if(!empty($getStatusdetails) && $getStatusdetails['add_lead_status'] == $key) selected @endif>{{$arr}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Visible in Update Lead Status :</label>
                                    <?php $updateyesNoArray = array('1'=>'Yes','0'=>'No'); ?>
                                    <div class="col-md-5">
                                        <select name="update_lead_status" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($updateyesNoArray as $key=> $arr)
                                                <option value="{{$key}}" @if(!empty($getStatusdetails) && $getStatusdetails['update_lead_status'] == $key) selected @endif>{{$arr}}</option>
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