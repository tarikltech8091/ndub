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

			<form action="{{url('/faculty/student/attendance/list')}}" method="get" enctype="multipart/form-data">


				<div class="form-group col-md-3">
					<label for="Program">Program</label>
					<select class="form-control " name="program" >
						@if(!empty($program_list))

						<option {{(isset($_GET['program']) && ($_GET['program']==$program_list->program_id)) ? 'selected' : ''}} value="{{$program_list->program_id}}">{{$program_list->program_title}}</option>

						@endif
					</select>
				</div>

				<?php 
				$calender_info =\DB::table('univ_academic_calender')
							->where('univ_academic_calender.academic_calender_status','1')
							->leftJoin('univ_semester','univ_semester.semester_code','like','univ_academic_calender.academic_calender_semester')
							->first();
				?>

				<div class="form-group col-md-2">
					<label for="Semester">Trimester</label>
					<select class="form-control" name="semester" >
						@if(!empty($calender_info))
							<option {{(isset($_GET['semester']) && ($_GET['semester']==$calender_info->semester_code)) ? 'selected' : ''}} value="{{$calender_info->semester_code}}">{{$calender_info->semester_title}}</option>
						@endif
					</select>
				</div>
				<div class="form-group col-md-2">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control " name="academic_year" >
						@if(!empty($calender_info))
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==$calender_info->academic_calender_year)) ? 'selected' : ''}} value="{{$calender_info->academic_calender_year}}">{{$calender_info->academic_calender_year}}</option>
						@endif
					</select>
				</div>



				<div class="form-group col-md-2">
					<label for="Course">Course</label>
					<select class="form-control " name="course">
						<option value="0">Select Course</option>
						@if(!empty($course_list) && count($course_list)>1)
						@foreach($course_list as $key => $list)
						<option  {{(isset($_GET['course']) && ($_GET['course']==$list->assigned_course_id)) ? 'selected' : ''}} value="{{$list->assigned_course_id}}">{{$list->assigned_course_id}} {{$list->assigned_course_title}}</option>
						@endforeach
						@elseif(!empty($course_list) && count($course_list) == 1)
							<option  {{(isset($_GET['course']) && ($_GET['course']==$course_list->assigned_course_id)) ? 'selected' : ''}} value="{{$course_list->assigned_course_id}}">{{$course_list->assigned_course_id}} {{$course_list->assigned_course_title}}</option>
						@endif
					</select>
				</div>

				<div class="form-group col-md-2">
					<label for="Date">Date</label>
					<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
						<input class="form-control" name="attendance_date_value"  size="16" type="text" value="{{isset($_GET['attendance_date_value'])? $_GET['attendance_date_value'] : date('Y-m-d')}}" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<div class="col-md-1 margin_top_20" style="margin-top:26px;">
					<button class="btn btn-danger register_attendance_list_search" data-toggle="tooltip" title="Search Student">Search</button>
				</div>
			</form>

		</div>
	</div>	
</div>

	@if(!empty($_GET['program']) && !empty($_GET['semester']) && !empty($_GET['academic_year']) && !empty($_GET['course']) && !empty($_GET['attendance_date_value']))
	<div class="row page_row">
		<div class="col-md-12">
			@if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year']) && isset($_GET['course']) && isset($_GET['attendance_date_value']))


				<div class="panel panel-body padding_0">

					<form action="{{url('/faculty/student/attendance/list/submit')}}" method="post" enctype="multipart/form-data">

						<div class="form-group col-md-3" style="margin-left:400px;">
							<label for="Date">Date</label>
								<input class="form-control" name="attendance_date"  size="16" type="text" value="{{$_GET['attendance_date_value']}}" readonly>
						</div>
						
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>SL</th>
									<th>Student ID</th>
									<th>Student Name</th>
									<th>Program</th>
									<th>Course Code</th>
									<th>Course Title</th>
									<th> All</th>
								</tr>
							</thead>

							<tbody>
									<?php 
										if(!empty($all_students_attendance_info)){
											$all_students_attendance_info;
										}else{
											$all_students_attendance_info=array();
										}

									?>

									@if(!empty($all_student) && count($all_student)>0)
									@foreach($all_student as $key => $student_list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$student_list->student_serial_no}}</td>
										<td>{{$student_list->first_name}} {{$student_list->middle_name}} {{$student_list->last_name}}</td>
										<td>{{$student_list->program_title}}</td>
										<td>{{$student_list->course_code}}</td>
										<td>{{$student_list->course_title}}</td>

										@if(!in_array(($student_list->student_serial_no), $all_students_attendance_info))
										<td><input type="checkbox" name="student_no[]" value="{{$student_list->student_serial_no}}"/>
											<input type="hidden" name="course_code" value="{{$student_list->course_code}}">
										</td>
										@else
										<td><i class="fa fa-check" aria-hidden="true"></i></td>
										@endif
									</tr>
									@endforeach
									<tr>
										<td colspan="7"><button type="submit" class="pull-right btn btn-primary btn-sm">Submit</button></td>
									</tr>
									
									@else
									<tr>
										<td colspan="7">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Available !</h3></center>
											</div>
										</td>
									</tr>
									@endif


							</tbody>
						</table>

					</form>

				</div>
			@elseif(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year']) || isset($_GET['course'])  || isset($_GET['attendance_date_value']))
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">Select All Dropdown List !</h3></center>
				</div>
			@else
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Data Found !</h3></center>
				</div>
			@endif

		</div>
	</div>
	@endif


@stop