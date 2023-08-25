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
        @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form id="AllocateLeadForm"  role="form"  class="form-horizontal" method="post" @if(isset($_GET['t'])) action="{{ url('s/admin/allocate-lead/'.$getLeadDetails['id'].'?t=r')}}" @else action="{{ url('s/admin/allocate-lead/'.$getLeadDetails['id'])}}" @endif> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Lead Id :</label>
                                    <div class="col-md-5">
                                        <p style="margin-top: 8px;"><a title="View Full Lead Details" class="btn btn-sm blue  getLeadDetails" href="javascript:;" data-companyname={{$getLeadDetails['company_name']}} data-leadid={{$getLeadDetails['id']}}>{{$getLeadDetails['lead_id']}}</a></p>
                                    </div>
                                </div>
                                @if(Session::get('empSession')['refer_to_dept'] =="yes")
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Refer to Department :</label>
                                        <div class="col-md-5">
                                            <select name="is_refer" class="selectbox getReferStatus">
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div id="AppendEmpResults">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Allocate Lead to :</label>
                                        <div class="col-md-5">
                                            <select name="allocate_to" class="selectbox" required>
                                                <option value="">Select</option>
                                                @foreach($geteamLevels as $key => $level)
                                                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); ?>
                                                    <option value="{{$level['id']}}">&#9679;&nbsp;{{$level['name']}} - {{$getEmpType->full_name}}</option>
                                                    @foreach($level['getemps'] as $skey => $sublevel1)
                                                        <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first(); ?>
                                                        <option value="{{$sublevel1['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;{{$sublevel1['name']}} - {{$getEmpType->full_name}}</option>
                                                        @foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                                                            <?php $getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); ?>
                                                            <option value="{{$sublevel2['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo; &nbsp;{{$sublevel2['name']}} - {{$getEmpType->full_name}}</option>
                                                        @endforeach
                                                    @endforeach
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