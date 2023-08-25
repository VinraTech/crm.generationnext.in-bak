<!DOCTYPE html>
<html lang="en" class="ie8 no-js">
<html lang="en" class="ie9 no-js">
<html lang="en">
	<head>
    	<meta charset="utf-8"/>
	    <title>@if(isset($title)) {{$title}} @endif</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<meta name="csrf-token" content="{{ csrf_token() }}" />
	    <!-- styles Starts -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/font-awesome/css/font-awesome.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/simple-line-icons/simple-line-icons.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/bootstrap/css/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/bootstrap/css/formValidation.min.css') }}" />
		<link href="{!! asset('css/backend_css/bootstrap-switch.min.css') !!}" rel="stylesheet" type="text/css"/>
		<link href="{!! asset('css/backend_css/bootstrap-fileinput.css') !!}" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/tasks.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/components-rounded.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/plugins.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/layout.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/light.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/custom.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/profile.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/bootstrap-select.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/datetimepicker/bootstrap-datetimepicker.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/shiv.css?v='.date('his')) }}" />
		<!-- styles ends here -->
		<script src="{!! asset('js/backend_js/jquery.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap-hover-dropdown.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/jquery.slimscroll.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/jquery.blockui.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/formValidation.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/Framework/bootstrap.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/jquery.cokie.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap-switch.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap-fileinput.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/jquery.dataTables.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/dataTables.bootstrap.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/datatable.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/table-ajax.js?v='.date('his')) !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/metronic.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/layout.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/demo.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/tasks.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap-select.min.js') !!}" type="text/javascript"></script>
		<script src="{{ asset('js/backend_js/datetimepicker/moment.js')}}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/datetimepicker/bootstrap-datetimepicker.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/admin-script.js?v='.date('his')) !!}" type="text/javascript"></script>
	</head>
	<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
		@include('layouts.adminLayout.adminheader')
		<div class="clearfix">
		</div>
		<div class="page-container">
			@include('layouts.adminLayout.adminsidebar')
			@yield('content')
		</div>
		@include('layouts.adminLayout.admin-footer')
		<div class="loadingDiv" style="display:none;">
	</body>
</html>