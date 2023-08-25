@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\LeadStatus;?>
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Lead's Management</h1>
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
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-green-sharp bold uppercase">@if(isset($pagettype)) Inactive Leads @else Allocated Leads @endif</span>
                        </div>
                    </div>
                    @if(!isset($pagettype))
                        <?php $activecount = LeadStatus::getstatuscount('active'); ?>
                        <a href="{{url('/s/admin/allocated-leads')}}" class="btn btn-circle @if(isset($_GET['t']) && ($_GET['t'] =="inactive" || $_GET['t'] =="refer") ) default  @else green  @endif">Active Leads ({{$activecount}})</a>
                        <?php $refercount = LeadStatus::getstatuscount('refer'); ?>
                        <a href="{{url('/s/admin/allocated-leads?t=refer')}}" class="btn btn-circle @if(isset($_GET['t']) && $_GET['t'] =="refer") green @else default @endif"">Refer Leads ({{$refercount}})</a>
                    @endif 
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="10%" >
                                            Lead Id
                                        </th>
                                        <th width="10%">
                                            Company Name
                                        </th>
                                        <th width="20%">
                                           Sales Person
                                        </th >
                                        <th width="15%">
                                          Source
                                        </th>
                                        @if(!isset($pagettype))
                                            <th width="15%">
                                              Next Appointment
                                            </th>
                                        @endif
                                        <th>
                                          Current Status
                                        </th>
                                        <th width="20%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td><input type="text" class="form-control form-filter input-sm" name="lead_id" placeholder="Lead Id"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="company_name" placeholder="Company Name"></td>
                                        <td>
                                            <select class="form-control form-filter input-sm" name="allocate_to">
                                                <option value="">Select</option>
                                                @foreach($getTeamLevels as $key => $level)
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
                                        </td>
                                        <td></td>
                                        @if(!isset($pagettype))
                                            <td></td>
                                        @endif
                                        <td>
                                            @if(!isset($_GET['t']))
                                                <select class="form-control form-filter input-sm" name="last_status">
                                                    <option value="">Select</option>
                                                    @foreach($getleadstatuses as $status)
                                                        <option value="{{$status['id']}}">{{$status['name']}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i title="Search" class="fa fa-search"></i></button>
                                                <button class="btn btn-sm red filter-cancel"><i title="Reset" class="fa fa-refresh"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop





