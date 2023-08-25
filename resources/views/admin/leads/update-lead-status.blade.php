@extends('layouts.adminLayout.backendLayout')
@section('content')
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
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{!! action('LeadController@allocatedLeads') !!}">Allocated Leads</a>
            </li>
        </ul>
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        <div class="row">
            <div class="col-md-12" id="ScrollToThreads">
                <!-- BEGIN TODO CONTENT -->
                <div class="todo-content" style="overflow: visible !important;">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bar-chart font-green-sharp hide"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">
                                    <a title="Lead Details" class="btn btn-sm green getLeadDetails" href="javascript::void(0);" data-companyname="{{$getLeadDetails['company_name']}}" data-leadid="{{$getLeadDetails['id']}}">{{$getLeadDetails['lead_id']}}
                                    </a>
                                </span>
                            </div>
                            <!-- @if(Session::get('empSession')['type'] =="admin")
                                <div class="actions">   
                                    <div class="btn-group">
                                        <div class="btn green">
                                            <a target="_blank" data-toggle="modal" data-target="#SendReminderModal" href="javascript:;" style="text-decoration:none; color:white"><i class="fa fa-envelope-o"></i>&nbsp;Send Reminder</a>
                                        </div>
                                    </div>
                                </div>
                            @endif -->
                        </div>
                        <!-- end PROJECT HEAD -->
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-7 col-sm-4">
                                    <div class="scroller exceptionalScroller" style="max-height: 600px;" data-handle-color="#dae3e7" id="ScrollableCustomHeight">
                                        <?php $colorArray = array('red','green','purple','blue'); ?>
                                        <div class="todo-tasklist">
                                            @if(!empty($getLeadThreads))
                                            @foreach($getLeadThreads as $key => $thread)
                                                <?php $randColor = array_rand($colorArray);
                                                    $color = $colorArray[$randColor];?>
                                                <div class="todo-tasklist-item todo-tasklist-item-border-{{$color}}">
                                                    @if($thread['getemp']['image'] !="")
                                                    <img class="todo-userpic pull-left" src="{{asset('images/AdminImages/'.$thread['getemp']['image'])}}" width="27px" height="27px">
                                                    @else
                                                        <img class="todo-userpic pull-left" src="{{asset('images/user.png')}}" width="27px" height="27px">
                                                    @endif
                                                    <div class="todo-tasklist-item-title">
                                                        <?php 
                                                        $EmpName = '<a href=javascript:; id='.$thread['getemp']['id'].' class=getEmpid>'.$thread['getemp']['name'].'</a>';
                                                        echo $EmpName;
                                                        ?>
                                                        @if(!empty($thread['appoint_date_time']))
                                                        <div style="float: right;"><span class="label label-sm label-primary"><?php echo date('d F Y  h:ia',strtotime($thread['appoint_date_time'])); ?></span></div>
                                                        @endif
                                                    </div>
                                                    <div class="todo-tasklist-item-text">
                                                       {{$thread['message']}} 
                                                    </div>
                                                    <div class="todo-tasklist-controls pull-left">
                                                        <span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d F Y h:i:sa',strtotime($thread['created_at'])); ?></span>
                                                        <span class="label label-sm label-success">{{ucwords($thread['threadleadstatus']['name'])}}
                                                    </div>
                                                    @if(isset($thread['threadfiles']) && !empty($thread['threadfiles']))
                                                        <div class="fullWidth">
                                                            <a href="javascript:void(0);" class="absAttachmentLink">View Attached Files</a>
                                                            <div class="text-left padderDivAttach">
                                                                @foreach($thread['threadfiles'] as $tkey => $file)
                                                                        <a href="{{url('s/admin/download-thread-file/'.$file['id'])}}">{{$file['file']}}</a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="todo-tasklist-devider">
                                </div>
                                @if(!in_array($getLeadDetails['last_status'],$getleadstatusids))
                                    <div class="col-md-5 col-sm-8">
                                        <div class="fullWidth"> 
                                            <form action="{{url('s/admin/update-lead-status/'.$getLeadDetails['id'])}}" class="form-horizontal" method="post" enctype="multipart/form-data"> {{csrf_field()}}
                                                <div class="form">
                                                    <div class="form-group">
                                                        <div class="col-md-8 col-sm-8">
                                                            <div class="todo-taskbody-user">
                                                                @if(Session::get('empSession')['image'] !="")
                                                                    <img class="todo-userpic pull-left" src="{{asset('images/AdminImages/'.Session::get('empSession')['image'])}}" width="50px" height="50px">
                                                                @else
                                                                    <img class="todo-userpic pull-left" src="{{asset('images/user.png')}}" width="50px" height="50px">
                                                                @endif
                                                                <span class="todo-username pull-left">{{Session::get('empSession')['name']}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <select class="selectbox getLeadStatus" name="lead_status_id" required> 
                                                                <option value="">Select Status</option>
                                                                @foreach($leadStatus as $status)
                                                                   <option value="{{$status['id']}}">{{ucwords($status['name'])}}</option> 
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div id="AppendAjaxResp">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <textarea name="message" class="form-control todo-taskbody-taskdesc" rows="8" placeholder="Enter Description..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <input type="file" name="files[]" multiple>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions right todo-form-actions">
                                                        <button type="submit" class="btn btn-circle btn-sm green-haze">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END TODO CONTENT -->
            </div>
        </div>
    </div>
</div>
<!-- Send Reminder Modal -->
<div class="modal fade" id="SendReminderModal" tabindex="-1" role="dialog" aria-labelledby="SendReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="SendReminderModalLabel">Send Reminder</h5>
            </div>
            <form action="{{url('/s/admin/send-reminder-email/'.$getLeadDetails['id'])}}" method="post">{{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Employee :</label>
                        <div class="col-md-9">
                            <select name="emp_id" class="selectbox" required> 
                                <option value="">Select</option>
                                @foreach($getReminderEmps as $reminder)
                                    <option value="{{$reminder['id']}}">{{$reminder['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Message :</label>
                        <div class="col-md-9">
                            <textarea  name="message" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>
                    <br><br><br><br><br><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Reminder</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Send Reminder Modal -->
@stop