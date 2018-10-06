@extends('excelsheet.layout.master-excel')
@section('content')



<table >
	<thead>

		<tr>
			<th>Day/Time</th>
			<?php
			$time_slots=\DB::table('univ_class_time_slot')->orderBy('time_slot_no','asc')->get();
			?>
			@if(!empty($time_slots))
			@foreach($time_slots as $key => $list)
			<th>
				{{$list->class_time_slot}}
				<!-- <table>
					<tr>
						<th class="text-center">BC</th>
						<th class="text-center">SC</th>
						<th class="text-center">FC</th>
						<th class="text-center">RC</th>
					</tr>
				</table> -->
			</th>
			@endforeach
			@endif
		</tr>
	</thead>

	<tbody>

		<?php 
		$i=1;  
		$days = array('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
		?>
		@for($i=0;$i<=5;$i++)

		<tr>
			<td>{{$days[$i]}}</td>

			@if(!empty($time_slots))
			@foreach($time_slots as $key => $list)


			<td>

				<?php

				$day=$days[$i];
				$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

				if(!empty($univ_academic_calender)){

					if(!empty($program_id)){

						$existing_class=\DB::table('univ_class_schedule')
						->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
						->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
						->where('class_schedule_program',$program_id)
						->where('class_schedule_day_of_week',$day)
						->where('class_schedule_time_slot',$list->class_time_slot)
						->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
						->get();

					}else{
						$existing_class=\DB::table('univ_class_schedule')
						->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
						->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
						->where('class_schedule_day_of_week',$day)
						->where('class_schedule_time_slot',$list->class_time_slot)
						->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
						->get();
					}

				}

				?>
				@if(!empty($existing_class))
				@foreach($existing_class as $key => $schedule_list)
				
				<section>
				CSE-01 {{$schedule_list->class_schedule_course}} {{strtoupper(substr($schedule_list->first_name,0,1))}}{{strtoupper(substr($schedule_list->middle_name,0,1))}}{{strtoupper(substr($schedule_list->last_name,0,1))}} {{$schedule_list->class_schedule_room}}
				</section>

				@endforeach
				@endif
			</td>


			@endforeach
			@endif

		</tr>

		@endfor


	</tbody>
</table>
@stop