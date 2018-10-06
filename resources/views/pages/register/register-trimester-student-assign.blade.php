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
		<div class="panel panel-body padding_0 sorting_form"><!--header inline form-->
			<?php 
			$program_list =\App\Applicant::ProgramList();

			?>
			<form action="{{url('/register/trimester-student-assign')}}" method="get" enctype="multipart/form-data">

				<div class="form-group col-md-4">
					<label for="Program">Program</label>
					<select class="form-control select_program" name="program" >
						<option value="0">Select Program</option>
						@if(!empty($program_list))
						@foreach($program_list as $key => $list)
						<option {{(isset($_GET['program']) && ($_GET['program']==$list->program_id)) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
						@endforeach
						@endif
					</select>
				</div>

				<div class="form-group col-md-2">
					<label for="Semester">Trimester</label>

					<select class="form-control semester" name="semester" >
						
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						<option {{(isset($_GET['semester']) && ($list->semester_code==$_GET['semester'])) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-2">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control academic_year" name="academic_year" >
						<!-- <option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option> -->

						@if(!empty($year_info))
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==$year_info)) ? 'selected':''}} value="{{$year_info}}">{{$year_info}}</option>
						@endif

					</select>
				</div>

				<div class="form-group col-md-2">
					<label for="AcademicYear">Batch</label>
					<select class="form-control get_batch" name="batch_no" >

					</select>
				</div>

				<div class="col-md-1 margin_top_20">
					<button class="btn btn-danger trimester_student_assign_search" data-toggle="tooltip" title="Search Students">Search</button>
				</div>
			</form>


		</div>

	</div>
</div>

@if(isset($_GET['program']) && isset($_GET['batch_no']) && isset($_GET['semester']) && isset($_GET['academic_year']))

	<div class="row page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Trimester Student Assign</div>
					<div class="panel-body"><!--info body-->

						@if(!empty($all_student))
							<form action="{{url('/register/trimester-student-assign-submit')}}" method="post" enctype="multipart/form-data">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>SL</th>
											<th>Student ID</th>
											<th>Student Name</th>
											<th>Program</th>
											<th>Batch</th>
											<th>Email</th>
											<th>Mobile</th>
											<th> <input class="checkAll" type="checkbox" /> All</th>
										</tr>
									</thead>
									<tbody>
											
											@foreach($all_student as $key => $student_list)
											<?php
												$student_study_level=\DB::table('student_study_level')->where('student_tran_code', $student_list->student_tran_code)->where('study_level_semester', $_GET['semester'])->where('study_level_year', $_GET['academic_year'])->first();
											?>
											<tr>
												<td>{{$key+1}}</td>
												<td>{{$student_list->student_serial_no}}</td>
												<td>{{$student_list->first_name}} {{$student_list->middle_name}} {{$student_list->last_name}}</td>
												<td>{{$student_list->program_title}}</td>
												<td>{{$student_list->batch_no}}</td>
												<td>{{$student_list->email}}</td>
												<td>{{$student_list->mobile}}</td>
												<td>
						
												@if(!empty($student_study_level) && $student_study_level->student_tran_code==$student_list->student_tran_code)
												<i class="fa fa-check" style="color:green"></i>
												@else
												<input type="checkbox" name="student_serial_no[]" value="{{$student_list->student_serial_no}}"  />
												@endif
												</td>
											</tr>
											@endforeach
											
											<tr>
												<td colspan="8">
												<input type="hidden" name="semester" value="{{$_GET['semester']}}"  />
												<input type="hidden" name="academic_year" value="{{$_GET['academic_year']}}"  />
												<button type="submit" class="pull-right btn btn-primary btn-sm" data-toggle="tooltip" title="Assign Students For Trimester">Assign Students</button>
												</td>
												
											</tr>

									</tbody>
								</table>
							</form>

						@else
						<!-- empty message -->
						<div class="alert alert-success">
							<center><h3 style="font-style:italic">No Data Found !</h3></center>
						</div>
						@endif
					</div><!--/info body-->
				</div>
			</div>

		</div>
	</div>
@endif



@stop