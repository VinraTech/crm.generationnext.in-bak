@extends('layouts.adminLayout.backendLayout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Notification Details</h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ action('AdminController@dashboard') }}">Dashboard</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Notification Details
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Title:
                                            </div>
                                            <div class="col-md-9 value">
                                                {{$getnotifyDetails['title']}}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-3 name">
                                                Description:
                                            </div>
                                            <div class="col-md-9 value">
                                                {{$getnotifyDetails['description']}}
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
    </div>
</div>
@stop