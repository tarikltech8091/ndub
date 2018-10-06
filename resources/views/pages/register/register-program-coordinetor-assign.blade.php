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
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Class Teacher Assign</div>
			<div class="panel-body"><!--info body-->
				<form action="{{url('/register/class-teacher-assign')}}" method="post" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<?php 
					$department_list =\App\Register::DepartmentList();
					?>
					
					<div class="row">
						<div class="form-group col-md-6">
							<label for="Department">Department <span class="required-sign">*</span></label>
							<select class="form-control department_list" name="department" required>
								<option value="">Select Department</option>
								@if(!empty($department_list))
								@foreach($department_list as $key => $list)
								<option {{(old('department')== $list->department_no) ? "selected" :''}} value="{{$list->department_no}}">{{$list->department_title}}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="Program">Program <span class="required-sign">*</span></label>
							<select class="form-control coordinator_program" name="coordinator_program" required>
							</select> 
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="Faculty">Faculty <span class="required-sign">*</span></label>
							<select class="form-control coordinator_faculty_id" name="coordinator_faculty_id" required>
							</select> 
						</div>
						<div class="form-group col-md-6">
							<label for="Year">Year <span class="required-sign">*</span></label>
							<select class="form-control" name="program_coordinator_year" required>
								<option {{(old('program_coordinator_year') == date('Y'))? "selected" :''}} value="{{date('Y')}}">{{date('Y')}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('+1 year'))) ? "selected" :''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>

							</select>
						</div>
					</div>
					<div class="row">

						<div class="form-group col-md-4">
							<label for="Semester">Trimester <span class="required-sign">*</span></label>
							<select class="form-control" name="program_coordinator_semester" required>
								@if(!empty($semester_list))
								@foreach($semester_list as $key => $list)
								<option {{(old('program_coordinator_semester')== $list->semester_code) ? "selected" :''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
								@endforeach
								@endif
							</select> 
						</div>
						<div class="form-group col-md-4">
							<label for="Level">Level <span class="required-sign">*</span></label>
							<select class="form-control" name="program_coordinator_level" required>
								<option {{(old('program_coordinator_level')== "1") ? "selected" :''}} value="1">1</option>
								<option {{(old('program_coordinator_level')== "2") ? "selected" :''}} value="2">2</option>
								<option {{(old('program_coordinator_level')== "3") ? "selected" :''}} value="3">3</option>
								<option {{(old('program_coordinator_level')== "4") ? "selected" :''}} value="4">4</option>
							</select>
						</div>
						<div class="form-group col-md-4">
							<label for="Term">Term <span class="required-sign">*</span></label>
							<select class="form-control" name="program_coordinator_term" required>
								<option {{(old('program_coordinator_term')== "1") ? "selected" :''}} value="1">1</option>
								<option {{(old('program_coordinator_term')== "2") ? "selected" :''}} value="2">2</option>
								<option {{(old('program_coordinator_term')== "3") ? "selected" :''}} value="3">3</option>
							</select>
						</div>
					</div>
					<div class="form-group pull-right">
						<input type="submit" class="btn btn-primary " value="Submit" >
					</div>
				</form>
			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Class Teacher List</div>
			<div class="panel-body">
				<div>
					<div class="sorting_form"><!--header inline form-->
						<?php 
						$program_list =\App\Applicant::ProgramList();

						?>

						<div class="form-group col-md-4">
							<label for="Program">Program</label>
							<select class="form-control program" name="program" >
								<option value="0">All</option>
								@if(!empty($program_list))
								@foreach($program_list as $key => $list)
								@if(isset($program))
								<option {{($program==$list->program_id) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
								@else
								<option value="{{$list->program_id}}">{{$list->program_code}}</option>
								@endif
								
								@endforeach
								@endif
							</select>
						</div>
						<div class="form-group col-md-3">
							<label for="Semester">Trimester</label>
							<select class="form-control semester" name="semester" >
								<option value="0">All</option>
								@if(!empty($semester_list))
								@foreach($semester_list as $key => $list)
								<option {{isset($_GET['semester']) && ($_GET['semester'] == $list->semester_code) ? "selected" :''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div class="form-group col-md-3">
							<label for="AcademicYear">Year</label>
							<select class="form-control academic_year" name="academic_year" >
								<option value="0">All</option>
								<option {{isset($_GET['academic_year']) && ($_GET['academic_year'] == date('Y'))? "selected" :''}} value="{{date('Y')}}">{{date('Y')}}</option>
								<option {{isset($_GET['academic_year']) && ($_GET['academic_year']== date('Y',strtotime('-1 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
								<option {{isset($_GET['academic_year']) && ($_GET['academic_year']== date('Y',strtotime('+1 year'))) ? "selected" :''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-primary program_coordinator_search" data-toggle="tooltip" title="Search Class Teacher">Search</button>
						</div>
					</div><!--/header inline form-->
				</div>
				<div class="col-md-12 program_coordinator_table">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>SL</th>
								<th>Faculty</th>
								<th>Program</th>
								<th>Trimester</th>
								<th>Year</th>
								<th>Level</th>
								<th>Term</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($all_faculty))
							@foreach($all_faculty as $key => $faculty)
							<tr>
								<td>{{($key+1)}}</td>
								<td>{{$faculty->first_name.' '.$faculty->middle_name}} ({{$faculty->faculty_id}})</td>
								<td>{{$faculty->program_code}}</td>
								<td>{{$faculty->semester_title}}</td>
								<td>{{$faculty->program_coordinator_year}}</td>
								<td>{{$faculty->program_coordinator_level}}</td>
								<td>{{$faculty->program_coordinator_term}}</td>
								<td>
									<a  onclick="location.href='{{url('/register/class-teacher-edit',$faculty->program_coordinator_tran_code)}}';" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Class Teacher"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
									</a>

									<a data-confirm-url="{{url('/register/class-teacher-del',$faculty->program_coordinator_tran_code)}}" class="btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Class Teacher"><i class="fa fa-trash-o" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="8">
									<div class="alert alert-success">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<center><h3 style="font-style:italic">No Data Available !</h3></center>
									</div>
								</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($pagination) ? $pagination:''}}
				</div>
				
			</div>
		</div>
	</div>
</div>

@stop