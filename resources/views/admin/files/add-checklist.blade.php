@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileChecklist; use App\Employee;?>
<style>
    .form-control-feedback {
      top: 9px !important;
    }
</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-head">
            <div class="page-title">
                <h1>File's Management </h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ url('admin/dashboard') }}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('s/admin/create-applicants/'.$fileid) }}">Files</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a class="btn green" href="{{ url('s/admin/create-applicants/'.$fileid) }}">Back</a>
            </li>
        </ul>
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="portlet blue-hoki box ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>{{ $title }}
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="{{'/s/admin/add-checklist/'.$fileid}}" role="form" class="form-horizontal" method="post"> 
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <div class="form-body">
                                @foreach($getchecklists as $checklist)
                                    <div class="form-group formCustomBody">
                                        <label class="col-md-3 control-label"><b>{{$checklist['name']}}</b></label>
                                        <div class="col-md-6">
                                            @foreach($checklist['subchecklist'] as $subkey=> $subchecklist)
                                                <div class="checkbox-wrapper">
                                                    @if($subchecklist['name'] !="Basics")
                                                        <p class="full--width"><b>{{$subchecklist['name']}}</b></p>
                                                    @endif
                                                    @foreach($subchecklist['subchecklist'] as $subsubchecklist)
                                                        <?php $ischecked = FileChecklist::checkSelection($fileid,$subsubchecklist['id']);?>
                                                        <label>
                                                            <input type="checkbox" name="checklist[]" value="{{$subsubchecklist['id'] }}" {{$ischecked}}/>{{ $subsubchecklist['name'] }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                         </div>
                                    </div>  
                                @endforeach            
                            </div>
                            <div class="form-actions right1 text-center">
                                <button class="btn green"  name="save" value="Save" type="submit">Save</button>
                                <?php $access = Employee::checkAccess(); ?>
                                
                                @if($checkFileAlreadyMoved ==0 && ($access =="true"))
                                    <button class="btn green" name="save" value="Move"  type="submit">Save & Move to Operations</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.form-control-feedback{
    top:8px! important;
}
.form-horizontal .form-group {
    margin-left: 0px !important;
}
.form-group select {
    float:left;
    display: inline-block;
    width:100%;
    padding: 6px 12px;
}
.properalign {
  margin-left: -25px;
  margin-top: 7px;
}
/*Css file*/
.checkbox-wrapper{float: left; display: inline-block; width: 100%; padding: 25px 15px 10px; text-align: left; border: 1px solid #999; -webkit-border-radius: 4px; border-radius: 4px; -moz-border-radius: 4px; background-clip: padding-box;}
.checkbox-wrapper > label{display: inline-block; min-width: 125px; font-size: 14px; letter-spacing: 1px; margin-bottom: 15px; margin-right: 10px; word-wrap: break-word;}
.checkbox-wrapper > label input[type="checkbox"]{ display: inline-block; float: left; margin-right: 5px; outline: none;}
/*Navbar Logo BRand */
body .page-header.navbar{height: inherit;}
.page-header-inner{float: left; width: 100%;display: inline-block;}
.page-header.navbar .page-header-inner .page-logo{height: 64px; width: 305px; max-height: 64px;}
.page-header.navbar .page-header-inner .page-logo a:not(.navbar-brand){float: none; display: inline-block; margin-top: 50px;}
body .page-header.navbar .page-logo a.navbar-brand.logo-default{float: left; max-width: 200px;display: inline-block; margin-top: 10px; margin-right: 0; padding: 0; height: inherit;}
a.navbar-brand img{max-width: 100%;}
/*Navbar Logo BRand */
/* IFrame Video */
.fullWidthCustom{float: left; display: inline-block; width: 100%; position: relative; overflow: hidden; padding-bottom: 250px;}
.full--width{float: left; display: inline-block; width: 100%; position: relative;}
.form-group.formCustomBody:not(:last-of-type),
.formCustomBody .checkbox-wrapper:not(:last-of-type){margin-bottom: 20px;}
.formCustomBody p.full--width > b{padding: 4px 6px; background-color: #4d4d4d; color: #fff; text-align: center; margin-bottom: 5px; display: inline-block;}
.formCustomBody .checkbox-wrapper{padding-top: 15px;}
</style>
@endsection