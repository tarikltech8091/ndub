<!DOCTYPE HTML>
<html>
<head>
	<title>{{ isset($page_title) ? $page_title.'|': ''}} NDUB</title>
	<meta name="viewport" content="wnameth=device-wnameth, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- @if(Session::has('applicant_serial_no'))
	<meta http-equiv="refresh" content="3;url={{url('/online-application/applicant-info')}}">
	@endif -->


	<!-- Bootstrap Core CSS -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel='stylesheet' type='text/css' />
	<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel='stylesheet' type='text/css' />

	<!-- font-awesome CSS -->
	<link href="{{asset('css/font-awesome.css')}}" rel="stylesheet"> 
	<!-- jQuery -->
	<!-- lined-icons -->
	<link rel="stylesheet" href="{{asset('css/icon-font.min.css')}}" type='text/css' />
	<link rel="shortcut icon" href="{{asset('fav.png')}}">


	<!-- Custom Application form Css -->
	<link href="{{asset('css/custom-application-form.css')}}" rel='stylesheet' type='text/css' />
	<!-- Custom Application form Js -->


</head> 

<body >
	<div class="container application_container"><!--application container-->

		<div class="page-header header_color">
			<img src="{{asset('images/banner-form.png')}}">
		</div>
		
		@if($page_title=='Applicant Information' || $page_title=='Applicant Admission Result' || (isset($from_part)&&($from_part==1)))
		<div class="panel panel-default">
			<div class="panel-body" style="padding:10px">

				<div class="row online-menu">
				
				<div class="col-md-4 padding-left-margin-right-0">
					<a href="{{url('/online-application/form')}}" type="button" class="btn btn-{{isset($from_part)&&($from_part==1)?'primary':'info'}} btn-sm btn-block font-18"><i class="fa fa-pencil-square-o"  aria-hidden="true"></i><span style="margin-left:10px">Apply Online Now</span></a>
				</div>
				<div class="col-md-4" style="padding-left:0;">
					<a href="{{url('/online-application/applicant')}}" type="button" class="btn btn-{{$page_title=='Applicant Information'?'primary':'info'}} btn-sm btn-block font-18"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span style="margin-left:10px">Applied Forms</span></a>
				</div>
				<div class="col-md-4 padding-left-right-0">
					<a href="{{url('/online-application/applicant/admission-result')}}" type="button" class="btn btn-{{$page_title=='Applicant Admission Result'?'primary':'info'}} btn-sm btn-block font-18"><i class="fa fa-folder-open" aria-hidden="true"></i><span style="margin-left:10px">Admission Result</span></a>
				</div>

				</div>
			</div>
		</div>
		@endif

		<div class="panel panel-default">
			<div class="panel-body">
				@yield('content')
			</div>
		</div>
	</div><!--/application container-->
		<!-- <footer>
		    <p>&copy; <?php echo date('Y'); ?> Live Entertainment Ltd. All Rights Reserved | Developed by <a target="_blank" href="#">Live Entertainment</a></p>
		</footer> -->

		<script src="{{asset('js/jquery-1.12.3.min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>

		<script src="{{asset('js/custom-application-form.js')}}"></script>
		<script src="{{asset('js/jquery.form.js')}}"></script>
		<script src="{{asset('js/image-uploader.js')}}"></script>

		<!-- Include Date Picker -->
		<script src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
		
	

	</body>
	</html>
