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
                        <form id="AddleadForm"  role="form"  class="form-horizontal" method="post" action="{{ url('s/admin/edit-lead/'.$leaddetails['id']) }}" enctype="multipart/form-data"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Company Name" name="company_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($leaddetails['company_name'])?$leaddetails['company_name']:'')}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Contact Person :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Contact Person" name="contact_person" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($leaddetails['contact_person'])?$leaddetails['contact_person']:'')}}"//>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Profile :</label>
                                    <div class="col-md-5">
                                        <select name="profile" class="selectbox"> 
                                            <option value="">Select</option>
                                            @foreach($getprofiles as $profile)
                                                <option value="{{$profile['name']}}" @if(!empty($leaddetails['profile']) && $leaddetails['profile'] == $profile['name']) selected @endif>{{$profile['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Loan Amount :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Loan Amount" name="loan_amt" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($leaddetails['loan_amt'])?$leaddetails['loan_amt']:'')}}"//>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Phone No. :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Phone Number" name="phone_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($leaddetails['phone_no'])?$leaddetails['phone_no']:'')}}"//>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cell No. :</label>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Cell Number" name="cell_no" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($leaddetails['cell_no'])?$leaddetails['cell_no']:'')}}"//>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Product :</label>
                                    <div class="col-md-5">
                                        <select name="product" class="selectbox"> 
                                            <?php $products = array('LAP','Used Car Loan','New Car Loan','Business Loan','Personal Loan','Credit Card','Auto Loan','Insurance'); ?>
                                            <option value="">Select</option>
                                            @foreach($products as $productInfo)
                                                <option value="{{$productInfo}}" @if(!empty($leaddetails['product']) && $leaddetails['product'] == $productInfo) selected @endif>{{$productInfo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group dateTimeDown">
                                    <label class="col-md-3 control-label">Appointment Date & Time :</label>
                                    <div class="col-md-5">
                                        <p style="margin-top:8px;">{{(!empty($leaddetails['appoint_date_time'])?$leaddetails['appoint_date_time']:'')}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Lead Priority :</label>
                                    <div class="col-md-5">
                                        <select name="priority" class="selectbox"> 
                                            <option value="">Select</option>
                                            <option value="Urgent" @if(!empty($leaddetails['priority']) && $leaddetails['priority'] == 'Urgent') selected @endif>Urgent</option>
                                            <option value="Normal" @if(!empty($leaddetails['priority']) && $leaddetails['priority'] == 'Normal') selected @endif>Normal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Comments :</label>
                                    <div class="col-md-5">
                                        <textarea type="text" placeholder="Enter Comments" name="comments" style="color:gray" autocomplete="off" class="form-control" rows="5">{{(!empty($leaddetails['comments'])?$leaddetails['comments']:'')}}</textarea>
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