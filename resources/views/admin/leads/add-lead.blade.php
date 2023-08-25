@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Lead's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('LeadController@leads') }}">Leads</a>
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
                        <form id="AddleadForm"  role="form"  class="form-horizontal" method="post" action="{{ url('s/admin/add-lead') }}" enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Department:</label>
                                    <div class="col-md-5">
                                        <select name="department" class="selectbox">
                                            <?php $depArray = array('S'=>'Secured','US'=>'Unsecured','CC'=>'Credit Card','I'=>'Insurance','AL'=>'Autoloan');?>
                                            <option value="">Select</option>
                                            @foreach($depArray as $dkey => $dep)
                                                <option value="{{$dkey}}-{{$dep}}">{{$dep}}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Contact Person :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Contact Person" name="contact_person" style="color:gray" autocomplete="off" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Profile :</label>
                                    <div class="col-md-5">
                                        <select name="profile" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($getprofiles as $profile)
                                                <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Loan Amount :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Loan Amount" name="loan_amt" style="color:gray" autocomplete="off" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone No. :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Phone Number" name="phone_no" style="color:gray" autocomplete="off" class="form-control"/>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cell No. :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Cell Number" name="cell_no" style="color:gray" autocomplete="off" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group" id="SelectAppender">
                                    <label class="col-md-3 control-label">Lead Type :</label>
                                    <div class="col-md-5">
                                        <select name="lead_type" class="selectbox getleadType"> 
                                            <option value="">Select</option>
                                            <option value="Direct">Direct</option>
                                            <option value="Indirect">Indirect</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group collapse" id="AppendCrm">
                                </div>
                                <div class="form-group collapse" id="AppendChannelPartners">
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Product :</label>
                                    <div class="col-md-5">
                                        <select name="product" class="selectbox"> 
                                            <?php $products = array('LAP','Used Car Loan','New Car Loan','Business Loan','Personal Loan','Credit Card','Auto Loan','Insurance'); ?>
                                            <option value="">Select</option>
                                            @foreach($products as $productInfo)
                                                <option value="{{$productInfo}}">{{$productInfo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Status :</label>
                                    <div class="col-md-5">
                                        <select name="last_status" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($getleadstatuses as $status)
                                                <option value="{{$status['id']}}">{{$status['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group dateTimeDown">
                                    <label class="col-md-3 control-label">Select Appointment Date & Time :</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date leadAppointmentDateTime ">
                                            <input type="text" placeholder="Select Appointment Date Time" class="form-control" name="appoint_date_time"  />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Lead Priority :</label>
                                    <div class="col-md-5">
                                        <select name="priority" class="selectbox"> 
                                            <option value="">Select</option>
                                            <option value="Urgent">Urgent</option>
                                            <option value="Normal">Normal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Lead Files :</label>
                                    <div class="col-md-5" style="margin-top: 8px;">
                                        <input type="file" name="leadfiles[]" multiple>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Comments :</label>
                                    <div class="col-md-5">
                                        <textarea type="text" placeholder="Enter Comments" name="comments" style="color:gray" autocomplete="off" class="form-control" rows="5"/></textarea>
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