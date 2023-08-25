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

                <h1>File's Management</h1>

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

                            <span class="caption-subject font-green-sharp bold uppercase">Pending Approvals</span>

                        </div>

                    </div>

                    <div class="portlet-body">

                        <div class="table-container">

                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                                <thead>

                                    <tr role="row" class="heading">

                                        <th width="10%">

                                            File No

                                        </th>

                                        <!-- <th width="15%">

                                            Faility Type

                                        </th> -->

                                        <th>

                                           Applicant Name

                                        </th>

                                        <th>

                                           Company Name

                                        </th>

                                        <th>

                                          Approval From

                                        </th>

                                        <th>

                                          Approved By

                                        </th>

                                        <th width="15%">

                                            Actions

                                        </th>

                                    </tr>

                                    <tr role="row" class="filter">

                                        <td><input type="text" class="form-control form-filter input-sm" name="file_no" placeholder="File No"></td>

                                        <!-- <td><input type="text" class="form-control form-filter input-sm" name="facility_type" placeholder="Facility Type"></td> -->

                                        <td><input type="text" class="form-control form-filter input-sm" name="name" placeholder="Client Name"></td>

                                        <td><input type="text" class="form-control form-filter input-sm" name="company_name" placeholder="Company Name"></td>

                                        <td></td>

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











