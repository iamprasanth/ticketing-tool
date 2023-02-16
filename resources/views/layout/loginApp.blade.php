<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>FAKTENHAUS</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Data table CSS -->
	<link href="{{ asset('themes/dist/css/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css">


	<!-- Custom CSS -->
	<link href="{{asset('themes/dist/css/style.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
	<!-- Preloader -->
	<div class="preloader-it">
		<div class="la-anim-1"></div>
	</div>
	<!-- /Preloader -->
    <div class="wrapper login-wrap theme-1-active pimary-color-blue">
		<div class=" login-wrapper pa-0 ma-0">
			@yield('content')

			@include('layout.footer')
		</div>
	</div>

	<script src="{{ asset('vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/jquery.slimscroll.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/dropdown-bootstrap-extended.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/echarts/dist/echarts-en.min.js') }}"></script>
	<script src="{{ asset('vendors/echarts-liquidfill.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/dashboard-data.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/jquery.multi-select.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/form-advance-data.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/jquery.bootstrap-touchspin.min.js') }}"></script>
	<script src="{{ asset('themes/dist/js/init.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.matchHeight.min.js') }}"></script>
	<script src="{{ asset('/js/custom.js') }}"></script>
	<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>

</body>

</html>
