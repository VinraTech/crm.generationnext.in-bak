@extends('layouts.adminLayout.backendLayout')
@section('content')
<style>
.table-scrollable table tbody tr td{
    vertical-align: middle;
}
</style>
<?php use App\Employee; ?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>User's Management</h1>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Clients</span>
                            <span class="caption-helper">manage records...</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                                <a href="{{url('/s/admin/export-clients')}}" class="btn btn-primary">Export Clients</a>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <div class="btn green">
                                            <a href="{{action('ClientController@addeditClient')}}" style="text-decoration:none; color:white">Add Client</a> <i class="fa fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>
                                            Client Id.
                                        </th>
                                        <th>
                                            Client Name
                                        </th>
                                        <th>
                                            Company Name
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th >
                                            Mobile
                                        </th>
                                        <th >
                                            PAN Individual
                                        </th>
                                        <th >
                                            Sale Officer
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th width="12%">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td><input type="text" class="form-control form-filter input-sm" name="client_id" placeholder="Client Id"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="customer_name" placeholder="Customer Name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="company_name" placeholder="Name"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="email" placeholder="Email"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="mobile" placeholder="Mobile"></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="pan" placeholder="Pan"></td>
                                        <td><?php $getallemps = Employee::getemployees('all') ?>
                                            <select class="form-control form-filter input-sm" name="salesofficer">
                                                <option value="">Select</option>
                                                @foreach($getallemps as $getemp)
                                                    <option value="{{$getemp['id']}}">{{$getemp['name']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td></td>
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





