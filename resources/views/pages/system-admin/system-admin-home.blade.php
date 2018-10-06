@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<!--error message*******************************************-->
<div class="row page_row">
	<div class="col-md-12">
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
				<div class="row page_row_dash">
					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/systemadmin/student-account')}}';">
							<p>	
								<a href="{{url('/systemadmin/student-account')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/systemadmin/student-account')}}">Student Registration</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/faculty-account')}}';">
							<p>	
								<a href="{{url('/system-admin/faculty-account')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/faculty-account')}}">Faculty Registration</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/employee-account')}}';">
							<p>	
								<a href="{{url('/system-admin/employee-account')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/employee-account')}}">Employee Registration</a>
							</p>
						</div>
					</div><!--/reprtcard-->

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/system-users')}}';">
							<p>	
								<a href="{{url('/system-admin/system-users')}}"><i class="fa fa-list" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/system-users')}}">System Users</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/error-logs')}}';">
							<p>	
								<a href="{{url('/system-admin/error-logs')}}"><i class="fa fa-minus-circle" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/error-logs')}}">Error Logs</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/auth-logs')}}';">
							<p>	
								<a href="{{url('/system-admin/auth-logs')}}"><i class="fa fa-key" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/auth-logs')}}">Auth Logs</a>
							</p>
						</div>
					</div><!--/reprtcard-->

				</div>

				<div class="row page_row_dash">

					<div class="col-md-2">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-plus-circle" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Today Visitor</br> {{$today_count}}
							</p>
						</div>
					</div>


					<div class="col-md-2">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-user" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Weekly Visitor</br>{{$weekly_count}}
							</p>
						</div>
					</div>


					<div class="col-md-2">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-user-plus" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Monthly Visitor</br>{{$monthly_count}}
							</p>
						</div>
					</div>


					<div class="col-md-2">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<a href="">
									<i class="fa fa-users" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								Yearly Visitor </br> {{$yearly_count}}
							</p>
						</div>
					</div>

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/access-logs')}}';">
							<p>	
								<a href="{{url('/system-admin/access-logs')}}"><i class="fa fa-area-chart" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/access-logs')}}">Access Logs</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/system-admin/event-logs')}}';">
							<p>	
								<a href="{{url('/system-admin/event-logs')}}"><i class="fa fa-cubes" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/system-admin/event-logs')}}">Event Logs</a>
							</p>
						</div>
					</div><!--/reprtcard--> 


				</div>

				<div class="row page_row_dash">


				</div>

			</div>
		</div>
	</div>
	
	@stop