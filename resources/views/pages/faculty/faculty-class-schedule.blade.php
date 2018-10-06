@extends('layout.master')
@section('content')

@include('layout.bradecrumb')
	
<div class="row page_row">
	
	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading">
				@if(!empty($univ_academic_calender))
				{{$univ_academic_calender->semester_title}} {{$univ_academic_calender->academic_calender_year}}
				@endif
				<span><a href="{{url('/faculty/pdf/class-schedule-download')}}" onclick="PrintContent('printableArea')"><i class="fa fa-print" data-toggle="tooltip" title="Download Class Schedule"></i></a></span>
			</div>

			<div class="panel-body"><!--info body-->

				<table class="table table-bordered">
					<thead >

						<?php 
						$i=1;  
						$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
						?>

						<tr>
							<th style="background-color: #fbeee6">Day/Time</th>
							@for($i=0;$i<=6;$i++)
							<th style="background-color: #fbeee6">{{$days[$i]}}</th>
							@endfor
						</tr>
					</thead>



					<tbody>
						<?php
						$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for',1)->orderBy('univ_time_slot','asc')->get();

						$i=1;  
						$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');

						?>
						@if(!empty($time_slots))
						@foreach($time_slots as $key => $list)

						<tr>
							<th style="background-color: #fbeee6">{{$list->univ_time_slot_slug}}</th>

							@for($i=0;$i<=6;$i++)
							<?php
							$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();
							$faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)->where('faculty_status','>',0)->first();


							if(!empty($univ_academic_calender)){
								$class_schedule=\DB::table('faculty_assingned_course')
								->where('assigned_course_faculties', $faculty_basic->faculty_id)
								->where('assigned_course_semester', $univ_academic_calender->academic_calender_semester)
								->where('assigned_course_year', $univ_academic_calender->academic_calender_year)
								->leftjoin('univ_class_schedule','faculty_assingned_course.assigned_course_id','=','univ_class_schedule.class_schedule_course')
								->where('class_schedule_time_slot', $list->univ_time_slot_slug)
								->where('class_schedule_day_of_week', $days[$i])
								->first();
							}

							?>


							<td style="background-color: {{isset($class_schedule->assigned_course_id) ? '#aed6f1' : '#f8f9f9'}}">
								<center>
									@if(!empty($class_schedule))
									<b>SC: {{$class_schedule->assigned_course_id}}</b><br>
									<i>RC: {{$class_schedule->class_schedule_room}}</i>
									@else
									@endif
								</center>
							</td>

							@endfor

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