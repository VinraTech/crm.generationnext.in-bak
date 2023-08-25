@extends('layouts.adminLayout.admin_login')
@section('content')
<div class="content">
	<form id="admin-login-form" class="login-form"  action="{{url('s/2018/admin')}}" method="post">
		@include('common.errors')
		@if(Session::has('flash_message_error'))
			<div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
		@endif
		@if(Session::has('flash_message_success'))
		    <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
		@endif
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<h3 class="form-title">Login to your account</h3>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Email</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" <?php if(isset($stayTuned['email'])){ ?> value="<?php echo  $stayTuned['email'] ?>" <?php } ?> placeholder="Email" name="email"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" value="<?php if(isset($stayTuned['password'])){ echo $stayTuned['password']; }?>" placeholder="Password" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox">
			@if(isset($stayTuned))
				<input type="checkbox" name="remember" value="1" checked="checked"/>
			@else
				<input type="checkbox" name="remember" value="1"/>
			@endif Remember me </label>
			<button type="submit" class="btn blue pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		<div class="forget-password">
			<h4>Forgot your password?</h4>
			<h5><a style="color:blue" href="javascript:;" id="forget-password">
				Click here  to reset your password.</a></h5>
		</div>
	</form>
	<form class="forget-form" id="admin-forget-form" action="{{url('/reset-password')}}" method="post">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<h3>Forget Password ?</h3>
		<p>Enter your e-mail address below to reset your password.</p>
		<div class="form-group">
			<div class="input-icon">
				<i class="fa fa-envelope"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="button" id="back-btn" class="btn">
			<i class="m-icon-swapleft"></i> Back </button>
			<button type="submit" class="btn blue pull-right">
			Submit <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	</form>
</div>
<div class="copyright">
	 <?php echo date('Y'); ?> &copy; Express Paisa
</div>
@endsection
