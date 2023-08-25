@extends('layouts.adminLayout.backendLayout')

@section('content')

<div class="page-content-wrapper">

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Designation Management </h1>

            </div>

        </div>

        <ul class="page-breadcrumb breadcrumb">

            <li>

                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>

                <i class="fa fa-circle"></i>

            </li>

            <li>

                <a href="{{ action('MasterController@banks') }}">Designation</a>

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

                        <form  role="form"  id="addEditDesignationform" class="form-horizontal" method="post" @if(empty($getdesignationdetails)) action="{{ url('s/admin/add-edit-designation') }}" @else  action="{{ url('s/admin/add-edit-designation/'.$getdesignationdetails['id']) }}" @endif> 

                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                            <div class="form-body">

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Full Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Full Name" name="full_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getdesignationdetails['full_name']))?$getdesignationdetails['full_name']: '' }}"/>

                                    </div>

                                </div>
                                <div class="form-group">

                                    <label class="col-md-3 control-label">Short Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Short Name" name="short_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getdesignationdetails['short_name']))?$getdesignationdetails['short_name']: '' }}"/>

                                    </div>

                                </div> 
                                <!-- <div class="form-group" >
                                        <label class="col-md-3 control-label">File Action: </label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="file_action">
                                                <?php $file_actionstatusArr = array('direct','indirect'); ?>
                                                <option value="">Select</option>
                                                @foreach($file_actionstatusArr as $ckey => $file_actionstatus)
                                                    <option value="{{$file_actionstatus}}" @if(!empty($getdesignationdetails['file_action']) && $getdesignationdetails['file_action'] == $file_actionstatus) selected @endif >{{ucwords($file_actionstatus)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->

                                   

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