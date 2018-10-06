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
	<div class="col-md-9 course">
		<div class="panel panel-info">
			<div class="panel-body">

				<div class="form-group col-md-5">
					<label for="Semester">Trimester</label>

					<select class="form-control semester" name="semester" >
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $semester)
						<option {{isset($_GET['semester']) && ($_GET['semester'] == $semester->semester_code) ? 'selected' : ''}}  value="{{$semester->semester_code}}">{{$semester->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-5">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control academic_year" name="academic_year" >
						@if(!empty($univ_academic_calender))
						@foreach($univ_academic_calender as $key => $year_list)
						<option {{isset($_GET['academic_year']) && ($_GET['academic_year'] == $year_list->academic_calender_year) ? 'selected' : ''}} value="{{$year_list->academic_calender_year}}">{{$year_list->academic_calender_year}}</option>
						@endforeach
						@endif
					</select>
				</div>
				
				<div class="form-group col-md-2" style="margin-top:27px;">
					<button type="submit" class="btn btn-primary student_result_search" data-toggle="tooltip" title="Search Academic Result">Search</button>
				</div>


			</div>
		</div>

		@if(isset($_GET['semester']) && isset($_GET['academic_year']))
		<div class="panel panel-info">
			
			<div class="panel-heading text-center">
				<span class="text-left">Student ID : {{isset($student_info) ? $student_info->student_serial_no : '' }}</span>
				<span class="text-right">Name : {{isset($student_info) ? $student_info->first_name : ''}} {{isset($student_info) ? $student_info->middle_name : ''}} {{isset($student_info) ? $student_info->last_name : ''}}</span>
			</div>
			<div class="panel-body"><!--info body-->
				<div class='student_info'>
					<label>CGPA : {{isset($student_cgpa)? number_format($student_cgpa, 2) : '0.00'}}</label>
				</div>
				<div class='student_credit'>

					<label>Total Credit Earned : {{isset($total_earned_credit)? $total_earned_credit : '0.0'}}</label>
					
				</div>
				<div class="grade_sheet">

					<table id="" class="table table-striped ">
						<thead>
							<tr>
								<th class="tbl_caption" colspan="6">Trimester : {{ isset($semester_info->semester_title) ? $semester_info->semester_title : ''}} {{isset($year) ? $year : ''}}</th>
							</tr>
							<tr>
								<th>Course Code</th>
								<th>Course Title</th>
								<th>Course Credit</th>
								<th>Earned Credit</th>
								<th>Grade</th>
								<th>Points</th>
							</tr>
						</thead>
						<tbody>

							@if(!empty($student_result))
							<?php 
							$total_credit=0;
							$total_credit_earned=0;
							$total_point=0;
							?>
							@foreach($student_result as $key => $result)
							<tr>
								<?php
								$total_credit=$total_credit+$result->tabulatation_credit_hours;
								$total_credit_earned=$total_credit_earned+$result->tabulation_credit_earned;
								$total_point=$total_point+($result->tabulation_credit_earned)*($result->tabulation_grade_point);
								?>
								<td>{{$result->tabulation_course_id}}</td>
								<td>{{$result->tabulation_course_title}}</td>
								<td>{{$result->tabulatation_credit_hours}}</td>
								<td>{{$result->tabulation_credit_earned}}</td>
								<td>{{$result->tabulation_grade}}</td>
								<td>{{$result->tabulation_grade_point}}</td>
							</tr>
							@endforeach
							

							
							<tr>
								<th colspan="2" class="text-center">Trimester Total</th>
								<th>{{isset($total_credit) ? $total_credit: ''}}</th>
								<th>{{isset($total_credit_earned) ? $total_credit_earned: ''}}</th>
								<th>GPA</th>
								<th>{{(isset($total_point) && ($total_credit_earned)) ? number_format($total_point/$total_credit_earned, 2) : ''}}</th>
							</tr>
							@endif

						</tbody>
					</table>
					
				</div>
			</div><!--/info body-->
		</div>

		@endif
	</div>

	<!--sidebar widget-->
	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>
</div>

@stop