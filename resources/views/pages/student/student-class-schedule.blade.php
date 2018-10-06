@extends('layout.master')
@section('content')

@include('layout.bradecrumb')
<style type="text/css">
	/*td,th{
		height: 50px;
	}

	.schedule_table table, td, th{
		text-align: center;
	}*/
</style>
<div class="row page_row">

	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading">
				@if(!empty($univ_academic_calender))
				{{$univ_academic_calender->semester_title}} {{$univ_academic_calender->academic_calender_year}}
				@endif
				<span><a href="{{url('/student/class/schedule/download')}}" ><i class="fa fa-print" data-toggle="tooltip" title="Download Class Schedule"></i></a></span>
			</div>

			<div class="panel-body"><!--info body-->
				<!-- <table id="printableArea" class="table table-striped table-bordered table-hover"> -->

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
							$student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();

							if(!empty($univ_academic_calender)){
								$class_schedule=\DB::table('student_academic_tabulation')
								->where('student_serial_no', $student_basic->student_serial_no)
								->where('tabulation_program', $student_basic->program)
								->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
								->where('tabulation_year', $univ_academic_calender->academic_calender_year)
								->leftjoin('univ_class_schedule','student_academic_tabulation.tabulation_course_id','=','univ_class_schedule.class_schedule_course')
								->where('class_schedule_time_slot', $list->univ_time_slot_slug)
								->where('class_schedule_day_of_week', $days[$i])
								->first();
							}

							?>


							<td style="background-color: {{isset($class_schedule->tabulation_course_id) ? '#aed6f1' : '#f8f9f9'}}">
								<center>
									@if(!empty($class_schedule) && !empty($class_schedule->tabulation_course_id))
									<b>SC: {{$class_schedule->tabulation_course_id}}</b><br>
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
		@include('pages.student.student-widget')
	</div>
	<!--/sidebar widget-->
</div>

@stop