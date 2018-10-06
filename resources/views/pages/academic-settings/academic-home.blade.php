@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<!--error message*******************************************-->
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif

	</div>
</div>
<!--end of error message*************************************-->

<div class="row page_row">
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success dash_pad_0">

			<div class="row page_row_dash">
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/academic-settings/home')}}';">
						<p>	
							<a href="{{url('/academic-settings/home')}}"><i class="fa fa-cog" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/academic-settings/home')}}">Academic Settings</a>
						</p>
					</div>
				</div><!--/reprtcard-->
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/academic/course-settings')}}';">
						<p>	
							<a href="{{url('/academic/course-settings')}}"><i class="fa fa-sliders" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/academic/course-settings')}}">Course Settings</a>
						</p>
					</div>
				</div><!--/reprtcard-->

			</div>

		</div>
	</div>
</div>

@stop