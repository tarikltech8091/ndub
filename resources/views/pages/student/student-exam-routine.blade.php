@extends('layout.master')
@section('content')

@include('layout.bradecrumb')

<div class="row page_row">

	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading ">
				@if(!empty($univ_academic_calender))
				{{$univ_academic_calender->semester_title}} {{$univ_academic_calender->academic_calender_year}}
				@endif
				<span><a href="{{url('/student/exam-routine-download')}}"><i class="fa fa-print" data-toggle="tooltip" title="Download Exam Routine"></i></a></span>
			</div>
			<div class="panel-body"><!--info body-->
				<h4>Trimester Midterm Exam Schedule</h4>
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time</th>
							<th>Course Code</th>
							<th>Course Tittle</th>
							<th>Program</th>
							<th>Exam Room</th>

						</tr>
					</thead>
					<tbody>
						@if(!empty($midterm_exam_schedule))
						@foreach($midterm_exam_schedule as $key => $mid_exam_schedule)
						<tr>
							<td>{{$mid_exam_schedule->exam_schedule_date}}</td>
							<td>{{$mid_exam_schedule->univ_time_slot_slug}}</td>
							<td>{{$mid_exam_schedule->exam_schedule_course}}</td>
							<td>{{$mid_exam_schedule->tabulation_course_title}}</td>
							<td>{{$mid_exam_schedule->program_code}}</td>
							<td>{{$mid_exam_schedule->exam_schedule_room}}</td>

						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">Schedule Not Published Yet !</h3></center>
								</div>
							</td>
						</tr>
						@endif

					</tbody>
				</table><br><br>

				<h4>Trimester Final Exam Schedule</h4>
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time</th>
							<th>Course Code</th>
							<th>Course Tittle</th>
							<th>Program</th>
							<th>Exam Room</th>

						</tr>
					</thead>
					<tbody>
						@if(!empty($final_exam_schedule))
						@foreach($final_exam_schedule as $key => $finalexam_schedule)
						<tr>
							<td>{{$finalexam_schedule->exam_schedule_date}}</td>
							<td>{{$finalexam_schedule->univ_time_slot_slug}}</td>
							<td>{{$finalexam_schedule->exam_schedule_course}}</td>
							<td>{{$finalexam_schedule->tabulation_course_title}}</td>
							<td>{{$finalexam_schedule->program_code}}</td>
							<td>{{$finalexam_schedule->exam_schedule_room}}</td>

						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">Schedule Not Published Yet !</h3></center>
								</div>
							</td>
						</tr>
						@endif

					</tbody>
				</table>
			</div><!--/info body-->
		</div>
	</div>
	<!--sidebar widget-->
	<div class="col-md-3 schedule">
		@include('pages.student.student-widget')
	</div>
	<!--/sidebar widget-->
</div>

@stop