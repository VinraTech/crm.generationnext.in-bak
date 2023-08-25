@extends('layouts.adminLayout.backendLayout')

@section('content')

<div class="page-content-wrapper">

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Bank Management </h1>

            </div>

        </div>

        <ul class="page-breadcrumb breadcrumb">

            <li>

                <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>

                <i class="fa fa-circle"></i>

            </li>

            <li>

                <a href="{{ action('MasterController@banks') }}">Bankers</a>

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

                        <form  role="form"  id="addEditBankform" class="form-horizontal" method="post" @if(empty($getbankdetails)) action="{{ url('s/admin/add-edit-banks') }}" @else  action="{{ url('s/admin/add-edit-banks/'.$getbankdetails['id']) }}" @endif> 

                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                            <div class="form-body">

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Full Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Full Name" name="full_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['full_name']))?$getbankdetails['full_name']: '' }}"/>

                                    </div>

                                </div> 

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Short Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Short Name" name="short_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['short_name']))?$getbankdetails['short_name']: '' }}"/>

                                    </div>

                                </div>

                                

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Type :</label>

                                    <div class="col-md-5">

                                        <?php 

                                                $bankselected="";

                                                $nbfcselected ="";   

                                            ?>

                                        @if(!empty($getbankdetails['type']))

                                            @if($getbankdetails['type'] =="Bank")

                                                <?php 

                                                $bankselected="selected";

                                                $nbfcselected ="";   

                                            ?>

                                            @else

                                                <?php 

                                                $bankselected="";

                                                $nbfcselected ="selected";   

                                            ?>

                                            @endif

                                         @endif

                                        <select name="type" class="selectbox"> 

                                            <option value="">Select</option>

                                            <option value="Bank" {{$bankselected}}>Bank</option>

                                            <option value=" Non-Banking Financial Company" {{$nbfcselected}}> Non-Banking Financial Company</option>

                                        </select>

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