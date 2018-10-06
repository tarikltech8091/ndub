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
		<div class="col-md-12 alert alert-danger">
			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/student/class-schedule')}}';">
					<p>	
						<a href="{{url('/student/class-schedule')}}"><i class='fa fa-newspaper-o'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/student/class-schedule')}}">Class Schedule</a>
					</p>
				</div>
			</div><!--/reprtcard-->
			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{URL::route('Student Pre Advising')}}';">
					<p>	
						<a href="{{URL::route('Student Pre Advising')}}"><i class='lnr lnr-layers'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{URL::route('Student Pre Advising')}}">Pre-Advising</a>
					</p>
				</div>
			</div><!--/reprtcard-->
			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/student/academic-course-plan')}}';">
					<p>	
						<a href="{{url('/student/academic-course-plan')}}"><i class='lnr lnr-book'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/student/academic-course-plan')}}">Course Plan</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/student/grade-sheet')}}';">
					<p>	
						<a href="{{url('/student/grade-sheet')}}"><i class='lnr lnr-license'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/student/grade-sheet')}}">Grade Sheet</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/student/payment-history')}}';">
					<p>	
						<a href="{{url('/student/payment-history')}}"><i class='lnr lnr-indent-increase'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/student/payment-history')}}">Payment History</a>
					</p>
				</div>
			</div><!--/reprtcard-->

			<div class="col-md-2"><!--reprtcard-->
				<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/student/exam-routine')}}';">
					<p>	
						<a href="{{url('/student/exam-routine')}}"><i class='lnr lnr-clock'></i></a>
					</p>
					<p class="report_name">	
						<a href="{{url('/student/exam-routine')}}">Exam Routine</a>
					</p>
				</div>
			</div><!--/reprtcard-->
		</div>
	</div>
</div>

<div class="row page_row">
	
	<div class="col-md-9">
		<div class="col-md-12 alert alert-danger dashborad_heading_div">
			<span class="dashborad_heading">Personal Details</span>
		</div>
		<div class="col-md-12 alert alert-danger">

			<div class="col-md-3">
				<div class="alert alert-info panel-body">
					@if(!empty($student_profile))
					<div class="profile_image_show">
						
						<img src="{{asset($student_profile->student_image_url)}}">
						
					</div>
					<div class="text-center" style="margin-top:10px;">
						<a href="{{url('/student/edit-profile')}}" class="btn btn-success col-md-12" data-toggle="tooltip" data-placement="bottom" title="Update Profile Information">Edit Profile</a>
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
								<th>{{isset($student_profile) ? $student_profile->first_name : ''}} {{isset($student_profile) ? $student_profile->middle_name : ''}} {{isset($student_profile) ? $student_profile->last_name : ''}}</th>
								<th>ID: {{isset($student_profile) ? $student_profile->student_serial_no : ''}}</th>
							</tr>
							<tr>
								<th>Program</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->program_title : ''}}</td>
							</tr>
							<tr>
								<th>Department</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->department_title : ''}}</td>
							</tr>
							<tr>
								<th>Batch</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->semester_title : ''}} {{isset($student_profile) ? $student_profile->academic_year : ''}}</td>
							</tr>
							<tr>
								<th>Birth Date</th>
								<td colspan="2">{{isset($student_profile) ? date_format(date_create_from_format('Y-m-d', $student_profile->date_of_birth), 'd-M-Y') : ''}}
								</td>
							</tr>
							<tr>
								<th>Blood Group</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->blood_group : ''}}</td>
							</tr>
							<tr>
								<th>Mobile</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->mobile : ''}}</td>
							</tr>
							<tr>
								<th>Email</th>
								<td colspan="2">{{isset($student_profile) ? $student_profile->email : ''}}</td>
							</tr>
						</tbody>
					</table>


				</div>
				<!-- </div> -->
			</div>
		</div>
	</div>
	

	<!--sidebar widget-->
	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>
	<!--/sidebar widget-->		
</div>



@stop
