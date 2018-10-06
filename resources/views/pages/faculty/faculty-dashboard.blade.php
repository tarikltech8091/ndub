@extends('layout.master')
@section('content')
@include('layout.bradecrumb')


<div class="page_row row"><!--message-->
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
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success">
			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus" onclick="location.href='{{url('/faculty/class-schedule')}}';">

					<p>	
						<a href="{{url('/faculty/class-schedule')}}"><i class='fa fa-newspaper-o'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/class-schedule')}}">Class Schedule</a>
					</p>

				</div>
			</div><!--/reprtcard-->
			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus centered" onclick="location.href='{{url('/faculty/course-advising')}}';">
					<p>	
						<a href="{{url('/faculty/course-advising')}}"><i class='lnr lnr-layers'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/course-advising')}}">Course Advising</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus centered" onclick="location.href='{{url('/faculty/assigned-courses')}}';">
					<p>	
						<a href="{{url('/faculty/assigned-courses')}}"><i class='fa fa-check-square-o'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/assigned-courses')}}">Assigned Course</a>
					</p>
				</div>
			</div><!--/reprtcard-->


			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus centered" onclick="location.href='{{url('/faculty/result-processing')}}';">
					<p>	
						<a href="{{url('/faculty/result-processing')}}"><i class='lnr lnr-license'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/result-processing')}}">Result Processing</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus centered" onclick="location.href='{{url('/faculty/exam-schedule')}}';">
					<p>	
						<a href="{{url('/faculty/exam-schedule')}}"><i class='lnr lnr-clock'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/exam-schedule')}}">Exam Schedule</a>
					</p>
				</div>
			</div><!--/reprtcard-->


			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus centered" onclick="location.href='{{url('/faculty/notice-board')}}';">
					<p>	
						<a href="{{url('/faculty/notice-board')}}"><i class='fa fa-th-large'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/notice-board')}}">Notice Board</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			@if(\Auth::user()->user_role=='head')
			<div class="col-md-2 margin_top_20"><!--reprtcard-->
				<div class="report_view reprt_color_2 cursor dashborad_menus" onclick="location.href='{{url('/faculty/program-head-result-publish')}}';">

					<p>	
						<a href="{{url('/faculty/program-head-result-publish')}}"><i class='fa fa-eye'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/faculty/program-head-result-publish')}}">Trimester Result Publish</a>
					</p>

				</div>
			</div><!--/reprtcard-->
			@endif


		</div>

	</div>



	<div class="row page_row">

		<div class="col-md-9">
			<div class="col-md-12 alert alert-success dashborad_heading_div">
				<span class="dashborad_heading">Personal Details</span>
			</div>
			<!-- <h3 class="page_heading">Personal Details</h3> -->
			<div class="col-md-12 alert alert-success">
				<div class="col-md-3 ">
					<div class="alert alert-info panel-body">
						@if(!empty($faculty_profile))
						<div class="profile_image_show">
							<img src="{{asset($faculty_profile->faculty_image_url)}}">

						</div>
						<div class="text-center" style="margin-top:10px;">
							<a class="btn btn-success col-md-12" href='{{url('/faculty/edit-profile', $faculty_profile->faculty_id)}}' data-toggle="tooltip" data-placement="bottom" title="Update Profile Information">Edit Profile</a>
						</div>
						@else
						<h4>Image not found</h4>
						@endif
					</div>

				</div>
				<div class="col-md-7">
					<!-- <div class="panel panel-body"> -->
					<div class="personal_info profile_info">
						<table class="table">
							<tbody>
								<tr>
									<th>Name</th>
									<th>{{isset($faculty_profile) ? $faculty_profile->first_name : ''}} {{isset($faculty_profile) ? $faculty_profile->middle_name : ''}} {{isset($faculty_profile) ? $faculty_profile->last_name : ''}}</th>
									<th>ID: {{isset($faculty_profile) ? $faculty_profile->faculty_id : ''}}</th>
								</tr>
								<tr>
									<th>Program</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->program_title : ''}}</td>
								</tr>
								<tr>
									<th>Department</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->department_title : ''}}</td>
								</tr>
								<tr>
									<th>Birth Date</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->date_of_birth : ''}}</td>
								</tr>
								<tr>
									<th>Blood Group</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->blood_group : ''}}</td>
								</tr>
								<tr>
									<th>Mobile</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->mobile : ''}}</td>
								</tr>
								<tr>
									<th>Email</th>
									<td colspan="2">{{isset($faculty_profile) ? $faculty_profile->email : ''}}</td>
								</tr>
							</tbody>
						</table>


					</div>
					<!-- </div> -->
				</div>
			</div>
		</div>


		<!--sidebar widget-->
		<div class="col-md-3 ">
			@include('pages.faculty.faculty-notice')
		</div>
		<!--/sidebar widget-->		
	</div>


	@stop