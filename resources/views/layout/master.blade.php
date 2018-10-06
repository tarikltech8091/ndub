<!DOCTYPE HTML>
<html>
<head>
	<title>{{ isset($page_title) ? $page_title.' | ': ''}}NDUB</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


	<!-- Bootstrap Core CSS -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="{{asset('css/style.css')}}" rel='stylesheet' type='text/css' />
	<link href="{{asset('css/custom.css')}}" rel='stylesheet' type='text/css' />
	<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel='stylesheet' type='text/css' />
	<!-- Graph CSS -->
	<link href="{{asset('css/font-awesome.css')}}" rel="stylesheet"> 
	<!-- jQuery -->
	<!-- lined-icons -->
	<link rel="stylesheet" href="{{asset('css/icon-font.min.css')}}" type='text/css' />
	<link rel="shortcut icon" href="{{asset('fav.png')}}">

	<!-- select 2 css -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/select2.css')}}">
	
	<!-- Placed js at the end of the document so the pages load faster -->
</head> 

<body class="{{( isset($page_title) && $page_title=="LogIn" ) ? "sign-in-up" : "sticky-header left-side-collapsed"}}">
	<section>
		<!-- left side start-->

		<input type="hidden" class="site_url" value="{{url('/')}}">

		@if(isset($page_title) && ($page_title !="LogIn"))
		@if(isset($page_title) && ($page_title !="Forgot Password"))
		@if(isset($page_title) && ($page_title !="Forgot Password Varify"))
		<div class="left-side sticky-left-side">

			@include('layout.sidebar-menu')
		</div>
		@endif
		@endif
		<!-- left side end-->
		
		<!-- main content start-->
		<div class="main-content">
			<!-- header-starts -->
			
			<div class="header-section">
				@include('layout.top-menu')
			</div>
			
			<!-- //header-ends -->
			<div id="page-wrapper">
				@yield('content')
			</div>
		</div>
		@else
		<div id="page-wrapper" class="sign-in-wrapper">
			@yield('content')
		</div>
		@endif
		<!--footer section start-->
		<footer>
			<p>&copy <?php echo date('Y'); ?> Live Entertainment Ltd. All Rights Reserved | Developed by <a href="#" target="_blank">Live Entertainment</a></p>
		</footer>
		<!--footer section end-->

		<!-- main content end-->
	</section>

	<!-- Bootstrap Core JavaScript -->
	<script src="{{asset('js/jquery-1.12.3.min.js')}}"></script>
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
	<script src="{{asset('js/scripts.js')}}"></script>
	<script src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
	<script src="{{asset('js/custom.js')}}"></script>



	<!-- Include Date Picker -->
	<script type="text/javascript" src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/formden.js')}}"></script>

	<!-- select 2 js -->
	<script type="text/javascript" src="{{asset('js/select2.js')}}"></script>

	
</body>
</html>