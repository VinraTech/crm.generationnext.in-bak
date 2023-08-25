@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php  use App\Employee;?>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>Employees Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ action('EmployeeController@employees') }}">Employees</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{$title}}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form  class="form-horizontal" method="get" action="{{url('/s/admin/add-employee-target/'.$empdetails['id'])}}">@csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Select Year:</label>
                                        <div class="col-md-4">
                                            <select name="year" class="form-control" required> 
                                                <option value="">Select Year</option>
                                                @for($y=2019; $y<=2030; $y++)
                                                    <option value="{{$y}}" @if(isset($_GET['year']) && $_GET['year']==$y)  selected @endif>{{$y}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green" type="submit">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if(isset($_GET['year']) && is_numeric($_GET['year']))  
                    <div class="portlet-body">
                        <form  class="form-horizontal" method="post" action="{{url('/s/admin/add-employee-target/'.$empdetails['id'])}}" autocomplete="off">@csrf
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-cogs"></i>Set Target of {{$empdetails['name']}} for Year {{$_GET['year']}}
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                Month
                                                            </th>
                                                            <th>
                                                                Target Value
                                                            </th>
                                                            <th>
                                                                Description
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <input type="hidden" name="year" value="{{$_GET['year']}}">
                                                        @for ($m=1; $m<=12; $m++)
                                                        <?php $month = date('F', mktime(0,0,0,$m, 1, date('Y')));?>
                                                        <?php $getdetails = Employee::getTargetDetails($empdetails['id'],$_GET['year'],$m); ?>
                                                            <tr>
                                                                <input type="hidden" name="months[]" value="{{$m}}-{{$month}}"/>
                                                                <td>{{$month}}, {{$_GET['year']}}</td>
                                                                <td>
                                                                    <input placeholder="Enter Target" type="number" class="form-control" name="target[]" autocomplete="off" @if(!empty($getdetails)) value="{{$getdetails['target']}}" @endif>
                                                                </td>
                                                                <td>
                                                                    <input placeholder="Enter Description if any..." type="text" name="description[]" class="form-control" autocomplete="off" @if(!empty($getdetails)) value="{{$getdetails['description']}}" @endif/>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-actions right1 text-center">
                                                <button class="btn green" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop