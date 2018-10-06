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
		<div class="panel panel-body padding_0 sorting_form">
			<form action="{{url('/register/all-summery')}}" method="get">
				<?php 
				$program_list =\App\Applicant::ProgramList();

				?>
				<div class="form-group col-md-4">
					<label for="Program">Program</label>
					<select class="form-control program" name="program" >
						<option value="0">All</option>
						@if(!empty($program_list))
						@foreach($program_list as $key => $list)
						<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="Semester">Trimester</label>
					<?php
					$semester_list=\DB::table('univ_semester')->get();
					?>
					<select class="form-control semester" name="semester" >
						<option value="0">All</option>
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						<option {{(isset($_GET['semester']) && ($list->semester_code==$_GET['semester'])) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control academic_year" name="academic_year" >
						<option value="0">All</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-1 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
					</select>
				</div>

				<div class="form-group col-md-1" style="margin-top:20px;">
					<button class="btn btn-danger total_registered_student_search" data-toggle="tooltip" title="Search Summery">Search</button>
				</div>

			</form>

		</div><!--/header inline form-->
	</div>
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success dash_pad_0">
			<div class="row page_row_dash">

				<div class="row page_row_dash">


					<div class="col-md-3">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<a href="">
									<i class="fa fa-users" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								Total Applicant </br> {{isset($applicant_list)?$applicant_list:'0'}}
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-user-plus" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Total Student</br>{{isset($student_list)?$student_list:'0'}}
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-user" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Total Faculty</br>{{isset($faculty_list)?$faculty_list:'0'}}
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-plus-circle" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Total Employee</br>{{isset($employee_list)?$employee_list:'0'}}
							</p>
						</div>
					</div>




				</div>

			</div>
		</div>
	</div>
</div>
	
@stop