@extends('layouts.adminLayout.backendLayout')

@section('content')
<?php 
use App\FileDropdown; 
$types = FileDropdown::getfiledropdown('facility');

?>
<div class="page-content-wrapper">

    <div class="page-content">

        <div class="page-head">

            <div class="page-title">

                <h1>Banker's Management </h1>

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

                        <form  role="form"  id="addEditBankerform" class="form-horizontal" method="post" @if(empty($getbankdetails)) action="{{ url('s/admin/add-edit-bank') }}" @else  action="{{ url('s/admin/add-edit-bank/'.$getbankdetails['id']) }}" @endif> 

                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                            <div class="form-body">
                             
                             <div class="form-group">

                                    <label class="col-md-3 control-label">Banker Name :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Name" name="banker_name" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['banker_name']))?$getbankdetails['banker_name']: '' }}"/>

                                    </div>

                                </div>

                                 <div class="form-group">

                                    <label class="col-md-3 control-label">Bank Name :</label>

                                    <div class="col-md-5 jquerySelectbox">
                    
									  <select name="bank_name"  class="selectbox selectpicker" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($banks as $bank)
                                                <option data-stateid="{{$bank['id']}}" value="{{ $bank['full_name'] }}" @if(!empty($getbankdetails['bank_name']) && $getbankdetails['bank_name'] == $bank['full_name']) selected @endif>{{ $bank['full_name'] }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                               
                                <div class="form-group">

                                    <label class="col-md-3 control-label">RM Code :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="RM Code" name="rm_code" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['rm_code']))?$getbankdetails['rm_code']: '' }}"/>

                                    </div>

                                </div>
                                <div class="form-group">

                                    <label class="col-md-3 control-label">Product List :</label>

                                    <div class="col-md-5">

                                        <select name="product[]" class="selectpicker" multiple  data-live-search="true" data-size="7" data-width="100%">
                                        <option selected="selected">    All Products
                                        </option>
                                        @foreach($types as $type)
                                          <?php if(!empty($banPids) && in_array($type['id'],$banPids)) {
                                                    $selected = "selected";
                                                }else{
                                                    $selected="";
                                                }
                                                ?>
                                            <option value="{{ $type['id'] }}" {{$selected}}>{{ ucwords($type['value']) }}</option>
                                        @endforeach
                                    </select>

                                    </div>

                                </div>


                                

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Email :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Email" name="email" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['email']))?$getbankdetails['email']: '' }}"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Phone Number :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Phone Number" name="phone_number" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['phone_number']))?$getbankdetails['phone_number']: '' }}"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">Address :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="Address" name="address" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['address']))?$getbankdetails['address']: '' }}"/>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">State :</label>

                                    <div class="col-md-5 jquerySelectbox">

                                       <select name="state" class="selectbox selectpicker getState" data-live-search="true" data-size="7" data-width="100%"> 
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                <option data-stateid="{{$state['id']}}" value="{{ $state['state'] }}" @if(!empty($getbankdetails['state']) && $getbankdetails['state'] == $state['state']) selected @endif>{{ $state['state'] }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">District :</label>

                                    <div class="col-md-5">

                                       <select name="district" class="selectbox" id="AppendCities"> 
                                            <option value="">Select</option>
                                            @if(!empty($getbankdetails['district']))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['city'] }}" @if(!empty($getbankdetails['district']) && $getbankdetails['district'] == $city['city']) selected @endif>{{ $city['city'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-3 control-label">City :</label>

                                    <div class="col-md-5">

                                        <input type="text" placeholder="City" name="city" style="color:gray" autocomplete="off" class="form-control" value="{{(!empty($getbankdetails['city']))?$getbankdetails['city']: '' }}"/>

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