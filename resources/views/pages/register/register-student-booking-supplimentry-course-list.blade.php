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
		<div class="panel panel-body padding_0">
			<div class=" sorting_form"><!--header inline form-->
				<form method="get" action="{{url('/register/student/booking/supplimentry/course/list')}}" enctype="multipart/form-data">
					<?php 
					$program_list =\App\Applicant::ProgramList();

					?>
					<div class="form-group col-md-3">
						<label for="Program">Program</label>
						<select class="form-control program_code" name="program" >
							<option value="">Select Program</option>
							@if(!empty($program_list))
							@foreach($program_list as $key => $list)
							<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
							@endforeach
							@endif
						</select>
					</div>


					<?php $course_list=\DB::table('course_basic')->get(); ?>

					<div class="form-group col-md-3">
						<label for="Course">Course</label>
						<select class="form-control ajax_course_list" name="course">

						</select>
					</div>

					<div class="form-group col-md-2">
						<label for="Semester">Trimester</label>
						<?php
						$semester_list=\DB::table('univ_semester')->get();
						?>
						<select class="form-control" name="semester" >
							@if(!empty($semester_list))
							@foreach($semester_list as $key => $list)
							<option {{(isset($_GET['semester']) && ($list->semester_code==$_GET['semester'])) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-md-2">
						<label for="AcademicYear">Academic Year</label>
						<select class="form-control" name="academic_year" >
							@if(!empty($univ_academic_calender))
							@foreach($univ_academic_calender as $key => $list)
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==$list->academic_calender_year)) ? 'selected':''}} value="{{$list->academic_calender_year}}">{{$list->academic_calender_year}}</option>
							@endforeach
							@endif
						</select>

					</div>


					<div class="form-group col-md-1" style="margin-top:20px;">
						<button class="btn btn-danger" data-toggle="tooltip" title="Search Students">Search</button>
					</div>
				</form>
			</div>
		</div>
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				@if(!empty($supplimentry_booking_course_info))

					<div class="panel-heading"><span class="pull-left">Course : {{$_GET['course']}}</span><span class="text-center">Semester : {{$semester_info->semester_title}}</span><span class="pull-right">Year : {{$_GET['academic_year']}}</span></div>
					<div class="panel-body"><!--info body-->

						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>SL</th>
									<th>Student ID</th>
									<th>Course</th>
									<th>Student Name</th>
									<th>Program</th>
									<th>Batch</th>
								</tr>
							</thead>
							<tbody>

								@foreach($supplimentry_booking_course_info as $key => $list)
								<tr>
									<td>{{$key+1}}</td>
									<td>{{$list->student_serial_no}}</td>
									<td>{{$list->course_title}}</td>
									<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
									<td>{{$list->program_code}}</td>
									<td>{{$list->batch_no}}</td>
								</tr>
								@endforeach

							</tbody>
						</table>

					</div><!--/info body-->
				@else
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Found !</h3></center>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>



@stop