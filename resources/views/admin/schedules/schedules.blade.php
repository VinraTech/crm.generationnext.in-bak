@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Schedule's Management</h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
            </li>
        </ul>
        <div class="row" style="overflow: visible !important;">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form  role="form" class="form-horizontal" method="post" action="{{url('/s/admin/schedules')}}"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Employee :</label>
                                    <div class="col-md-5 jquerySelectbox">
                                        <select name="emp_id" class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%" required>
                                            <option value="">Select</option>
                                            @foreach($getTeams as $key => $level)
                                                <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); ?>
                                                <option value="{{$level['id']}}">&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                                                @foreach($level['getemps'] as $skey => $sublevel1)
                                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                                                    <option value="{{$sublevel1['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - {{$getEmpType->full_name}}</option>
                                                    @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                                                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                                                        <option value="{{$sublevel2['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                 <div class="form-group new--dateTime dateTimeDown">
                                    <label class="col-md-3 control-label">Select Date:</label>
                                    <div class="col-md-5">
                                        <div class="input-group input-append date s_date">
                                            <input autocomplete="off" type="text" placeholder="Select Date" class="form-control" name="s_date" required />
                                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet blue-hoki box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Schedule Details @if($name!="") of {{$name}} @if($date!="") ({{date('d F Y',strtotime($date))}}) @endif @endif
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Lead Id
                                                    </th>
                                                    <th>
                                                        Schedule Date & Time
                                                    </th>
                                                    <th>
                                                        Description
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($getschedules))
                                                    @foreach($getschedules as $schedule)
                                                        <tr>
                                                            <td>
                                                                <span class="caption-subject font-green-sharp bold uppercase">
                                                                    <a title="Lead Details" class="btn btn-sm green getLeadDetails" href="javascript::void(0);" data-companyname="{{$schedule['leaddetail']['company_name']}}" data-leadid="{{$schedule['lead_id']}}">{{$schedule['leaddetail']['lead_id']}}
                                                                </a>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{date('d F Y h:ia',strtotime($schedule['appoint_date_time']))}}
                                                            </td>
                                                            <td>
                                                                {{$schedule['message']}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="3" style="text-align: center">
                                                        No Schedules found.
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop