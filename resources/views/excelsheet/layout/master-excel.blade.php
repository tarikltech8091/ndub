<!DOCTYPE HTML>
<html>
<head>
	<title>{{ isset($page_title) ? $page_title: 'Sheet'}}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


	 <!-- Bootstrap Core CSS -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="{{asset('css/style.css')}}" rel='stylesheet' type='text/css' />
	<link href="{{asset('css/excel.css')}}" rel='stylesheet' type='text/css' />
	<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel='stylesheet' type='text/css' />
	<!-- Graph CSS -->
	<link href="{{asset('css/font-awesome.css')}}" rel="stylesheet"> 
	<!-- jQuery -->
	<!-- lined-icons -->
	<link rel="stylesheet" href="{{asset('css/icon-font.min.css')}}" type='text/css' />
	<link rel="shortcut icon" href="{{asset('fav.png')}}">


<!-- Placed js at the end of the document so the pages load faster -->
</head> 
<body>


	<div class="container">
		@yield('content')
	</div>
		

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('js/jquery-1.12.3.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
</body>
</html>
