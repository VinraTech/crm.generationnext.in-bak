@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Notification's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('NotificationController@notifications') }}">Notifications</a>
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
                        <form  role="form" class="form-horizontal" method="post" @if(empty($notifydata)) action="{{ url('s/admin/add-edit-notification') }}" @else  action="{{ url('s/admin/add-edit-notification/'.$notifydata['id']) }}" @endif> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Select Employee :</label>
                                    <div class="col-md-4">
                                        <select name="emp_ids[]" class="selectpicker" data-width="100%" data-actions-box="true" data-size="8" multiple data-live-search="true" required>
                                            @foreach($geteamLevels as $key => $level)
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
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title :</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Title" name="title" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($notifydata['title']))?$notifydata['title']: '' }}" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description :</label>
                                    <div class="col-md-4">
                                        <textarea  rows="6" type="text" placeholder="Description" name="description"  autocomplete="off" class="form-control" required />{{(!empty($notifydata['description']))?$notifydata['description']: '' }}</textarea>
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