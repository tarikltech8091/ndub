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
		<div class="panel panel-body padding_0 ">
			<!-- sorting_form -->
			<form action="{{url('/register/attendance/percent')}}" method="get" enctype="multipart/form-data">

				<?php 
				$program_list =\App\Applicant::ProgramList();
				?>

				<div class="form-group col-md-3">
					<label for="Program">Program</label>
					<select class="form-control " name="program" >
						<option value="0">Select Program</option>
						@if(!empty($program_list))
						@foreach($program_list as $key => $list)

						<option {{(isset($_GET['program']) && ($_GET['program']==$list->program_id)) ? 'selected' : ''}} value="{{$list->program_id}}">{{$list->program_title}}</option>

						@endforeach
						@endif
					</select>
				</div>

				<?php 
				$semester_list =\DB::table('univ_semester')->get();
				?>

				<div class="form-group col-md-3">
					<label for="Semester">Trimester</label>
					<select class="form-control " name="semester" >
						<option value="0">Select Trimester</option>
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						<option {{(isset($_GET['semester']) && ($_GET['semester']==$list->semester_code)) ? 'selected' : ''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-2">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control " name="academic_year" >
						<option value="0">Select Year</option>
						@if(!empty($univ_academic_calender))
						@foreach($univ_academic_calender as $key => $list)
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==$list->academic_calender_year)) ? 'selected' : ''}} value="{{$list->academic_calender_year}}">{{$list->academic_calender_year}}</option>
						@endforeach
						@endif
					</select>
				</div>

				<?php 
				$course_list =\DB::table('course_basic')->get();
				?>

				<div class="form-group col-md-3">
					<label for="Course">Course</label>
					<select class="form-control " name="course">
						<option value="0">Select Course</option>
						@if(!empty($course_list))
						@foreach($course_list as $key => $list)
						<option  {{(isset($_GET['course']) && ($_GET['course']==$list->course_code)) ? 'selected' : ''}} value="{{$list->course_code}}">{{$list->course_code}} {{$list->course_title}}</option>
						@endforeach
						@endif
					</select>
				</div>

				<div class="col-md-1 margin_top_20" style="margin-top:26px;">
					<button class="btn btn-danger register_attendance_list_search" data-toggle="tooltip" title="Search Student">Search</button>
				</div>
			</form>

		</div>
	</div>
</div>

@if(!empty($_GET['program']) || !empty($_GET['semester']) || !empty($_GET['academic_year']) || !empty($_GET['course']))

<div class="row page_row">
	<div class="col-md-12">

		<div class="panel panel-body padding_0">

				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>SL</th>
							<th>Student ID</th>
							<th>Student Name</th>
							<th>Program</th>
							<th>Course Code</th>
							<th>Course Title</th>
							<th>Total Class</th>
							<th>Attend</th>
							<th>Absence</th>
							<th>Attend Percent (%)</th>
						</tr>
					</thead>

					<tbody>
							@if(!empty($all_student_attendance_info))
								@foreach($all_student_attendance_info as $key => $student_list)

									<?php 
										$all_student_attendance_information=unserialize($student_list);
									?>
										<tr>
											<td>{{$key+1}}</td>
									    	@foreach ($all_student_attendance_information as $key2 => $list) 
											<td>{{$all_student_attendance_information[$key2]}}</td>

									    	@endforeach
										</tr>


								@endforeach
							@else
								<tr>
									<td colspan="10">
										<div class="alert alert-success">
											<center><h3 style="font-style:italic">No Data Available !</h3></center>
										</div>
									</td>
								</tr>
							@endif



					</tbody>
				</table>

			</div>
		</div>

	</div>

	@else

	<div class="row page_row">
		<div class="col-md-12">
			<div class="alert alert-success">
				<center><h3 style="font-style:italic">No Data Available !</h3></center>
			</div>
		</div>
	</div>

	@endif

	@stop