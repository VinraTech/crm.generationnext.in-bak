<!DOCTYPE html>
<html lang="en" class="ie8 no-js">
<html lang="en" class="ie9 no-js">
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Login Panel -  Express Paisa</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta content="Login Panel - Express Paisa" name="description"/>
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/font-awesome/css/font-awesome.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/bootstrap/css/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/bootstrap/css/formValidation.min.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/login-soft.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/components-rounded.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('css/backend_css/layout.css') }}" />
		<!-- Login page styles ends here -->
		<link rel="shortcut icon" href="{{ asset('images/metro.ico') }}"/>
	</head>
	<body class="login">
		<div class="logo">
			<a href="index.html">
				<img src="" alt=""/>
			</a>
		</div>
		<div class="menu-toggler sidebar-toggler">
		</div>
		@yield('content')
	<!-- Scripts starts from here -->
		<script src="{!! asset('js/backend_js/jquery.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/formValidation.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/Framework/bootstrap.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/jquery.backstretch.min.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/metronic.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/layout.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('js/backend_js/demo.js') !!}" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {     
	  			Metronic.init(); // init metronic core components
				Layout.init(); // init current layout
	  			Demo.init();
	       		$.backstretch([
		       		'{{ URL::asset('/images/LoginImages/first.jpg') }}',
			        '{{ URL::asset('/images/LoginImages/1.jpg') }}',
			        '{{ URL::asset('/images/LoginImages/3.jpg') }}'
		        ], {
		         	fade: 1000,
		          	duration: 1000
		    		}
		    	);

    			$.ajaxSetup({
			        headers:{
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        }
			    });

			    jQuery('#forget-password').click(function () {
			        jQuery('.login-form').hide();
			        jQuery('.forget-form').show();
			    });

			    jQuery('#back-btn').click(function () {
			        jQuery('.login-form').show();
			        jQuery('.forget-form').hide();
    			});

				$('#admin-login-form').formValidation({
			        framework: 'bootstrap',
			        excluded: [':disabled'],
			        message: 'This value is not valid',
			        icon:{
			            valid: 'glyphicon glyphicon-ok',
			            invalid: 'glyphicon glyphicon-remove',
			            validating: 'glyphicon glyphicon-refresh'
			        },
			        err:{
			            container: 'popover'
			        },
        			fields:{
            			"email":{
                			validators:{
                    			notEmpty:{
                        			message: 'Please enter your email'
                    			}
                			}
            			},
			            "password":{
			                validators:{
			                    notEmpty:{
			                        message: 'Please enter your password'
			                    }
			                }
            			}
        			}
    			});

				    $('#admin-forget-form').formValidation({
			        framework: 'bootstrap',
			        excluded: [':disabled'],
			        message: 'This value is not valid',
			        icon: 
			        {
			            valid: 'glyphicon glyphicon-ok',
			            invalid: 'glyphicon glyphicon-remove',
			            validating: 'glyphicon glyphicon-refresh'
			        },
			        err: 
			        {
			            container: 'popover'
			        },
			        fields:
			        {
			            "email": 
			            {
			                validators: 
			                {
			                    notEmpty: 
			                    {
			                        message: 'Email is required'
			                    },
			                    emailAddress: 
			                    {
			                        message: 'This email is not a valid email address'
			                    },
			                    remote: 
			                    {
			                        message: 'This email not exists. Please contact system administrator',
			                        url: '/verify-email',
			                        type: 'POST',
			                        delay: 2000     // Send Ajax request every 2 seconds
			                    }
			                }
			            }
			        }
			    });
			});
    	</script>
	</body>	
</html>