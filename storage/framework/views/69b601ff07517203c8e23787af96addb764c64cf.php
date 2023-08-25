<!DOCTYPE html>
<html lang="en" class="ie8 no-js">
<html lang="en" class="ie9 no-js">
<html lang="en">
	<head>
    	<meta charset="utf-8"/>
	    <title><?php if(isset($title)): ?> <?php echo e($title); ?> <?php endif; ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
	    <!-- styles Starts -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/font-awesome/css/font-awesome.min.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/simple-line-icons/simple-line-icons.min.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/bootstrap/css/bootstrap.min.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/bootstrap/css/formValidation.min.css')); ?>" />
		<link href="<?php echo asset('css/backend_css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo asset('css/backend_css/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/tasks.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/components-rounded.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/plugins.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/layout.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/light.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/custom.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/profile.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/bootstrap-select.min.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/backend_css/datetimepicker/bootstrap-datetimepicker.min.css')); ?>" />
		<link rel="stylesheet" href="<?php echo e(URL::asset('css/shiv.css?v='.date('his'))); ?>" />
		<!-- styles ends here -->
		<script src="<?php echo asset('js/backend_js/jquery.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/bootstrap.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/bootstrap-hover-dropdown.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/formValidation.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/Framework/bootstrap.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/jquery.cokie.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/bootstrap-fileinput.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/datatable.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/table-ajax.js?v='.date('his')); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/metronic.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/layout.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/demo.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/tasks.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/bootstrap-select.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo e(asset('js/backend_js/datetimepicker/moment.js')); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/datetimepicker/bootstrap-datetimepicker.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo asset('js/backend_js/admin-script.js?v='.date('his')); ?>" type="text/javascript"></script>
	</head>
	<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
		<?php echo $__env->make('layouts.adminLayout.adminheader', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="clearfix">
		</div>
		<div class="page-container">
			<?php echo $__env->make('layouts.adminLayout.adminsidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			<?php echo $__env->yieldContent('content'); ?>
		</div>
		<?php echo $__env->make('layouts.adminLayout.admin-footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="loadingDiv" style="display:none;">
	</body>
</html>