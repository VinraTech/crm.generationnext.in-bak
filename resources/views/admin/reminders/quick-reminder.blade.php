@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Quick Reminder</h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
            </li>
        </ul>
        @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        <div class="row" style="overflow: visible !important;">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form  role="form" class="form-horizontal" method="post" action="{{url('/s/admin/quick-reminder')}}"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Lead Id:</label>
                                    <div class="col-md-4 jquerySelectbox">
                                        <select name="lead_id" class="selectbox selectpicker getleadid" data-live-search="true" data-size="7" data-width="100%"  required>
                                            <option value="">Select</option>
                                            @foreach($leadIdsArr as $key => $leadDetails)
                                            <optgroup label="{{$key}}">
                                                @foreach($leadDetails as $lkey => $lead)
                                                    <option data-source="{{$lead['source']}}" data-leadid="{{$lead['lead_id']}}" data-companyname="{{$lead['company_name']}}" value="{{$lead['id']}}">{{$lead['lead_id']}}</option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="AppendLeadLink">
                                </div>
                                <div id="AppendEmployees">
                                </div>
                                <div id="includeMeinCC">
                                </div>
                                <div class="form-group new--dateTime dateTimeDown">
                                    <label class="col-md-3 control-label">Message:</label>
                                    <div class="col-md-5">
                                        <textarea type="text" placeholder="Enter Message" name="message" style="color:gray" autocomplete="off" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>        
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop