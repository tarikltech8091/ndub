@extends('layout.master')
@section('content')

@include('layout.bradecrumb')

<div class="row page_row">
	
	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading">
				Assigned Courses For {{isset($univ_academic_calender) ? $univ_academic_calender->semester_title.' '.$univ_academic_calender->academic_calender_year : ''}}

			</div>

			<div class="panel-body">
				<table class="table table-bordered table-hover">
					<thead >
						<tr>
							<th>SL</th>
							<th>Course Title</th>
							<th>Course Code</th>
							<th>Class Teacher</th>
							<th>Program</th>
							<th>Level</th>
							<th>Term</th>
							<th>Semester</th>
							<th>Year</th>
						</tr>
					</thead>


					<tbody>
						@if(!empty($faculty_assingned_course))
						@foreach($faculty_assingned_course as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->assigned_course_title}}</td>
							<td>{{$list->assigned_course_id}}</td>
							<td>
								<?php
								$class_teacher=\DB::table('program_coordinator_assigned')
								->where('coordinator_program',$list->assigned_course_program)
								->where('program_coordinator_semester',$list->assigned_course_semester)
								->where('program_coordinator_year',$list->assigned_course_year)
								->where('program_coordinator_level',$list->assigned_course_level)
								->where('program_coordinator_term',$list->assigned_course_term)
								->leftjoin('faculty_basic','faculty_basic.faculty_id','=','program_coordinator_assigned.coordinator_faculty_id')
								->first();

								?>
								{{isset($class_teacher) ? $class_teacher->first_name.' '.$class_teacher->middle_name.' '.$class_teacher->last_name : ''}}
							</td>
							<td>{{$list->program_title}}</td>
							<td>{{$list->assigned_course_level}}</td>
							<td>{{$list->assigned_course_term}}</td>
							<td>{{$list->semester_title}}</td>
							<td>{{$list->assigned_course_year}}</td>

						</tr>
						@endforeach
						@endif
					</tbody>


				</table>
				

			</div><!--/info body-->
		</div>
	</div>
	<!--sidebar widget-->
	<div class="col-md-3 schedule">
		@include('pages.faculty.faculty-notice')
	</div>
	<!--/sidebar widget-->
</div>

@stop