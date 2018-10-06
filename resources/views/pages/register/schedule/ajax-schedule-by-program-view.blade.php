<table class="table table-bordered">
	<thead >

		<?php 
		$i=1;  
		$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
		?>

		<tr>
			<th>Day <i class="fa fa-long-arrow-right" style="color:green" aria-hidden="true"></i></th>
			@for($i=0;$i<=6;$i++)
			<th colspan="3">{{$days[$i]}}</th>
			@endfor

		</tr>
		<tr>
		<th>Time <i class="fa fa-long-arrow-down" style="color:green" aria-hidden="true"></i></th>
			@for($i=0;$i<=6;$i++)
			<th>SC</th>
			<th>FC</th>
			<th>RC</th>
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
			<th>{{$list->univ_time_slot_slug}}</th>

			@for($i=0;$i<=6;$i++)

			<td colspan="3">
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
						->where('class_schedule_time_slot',$list->univ_time_slot_slug)
						->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
						->get();

					}else{
						$existing_class=\DB::table('univ_class_schedule')
						->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
						->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
						->where('class_schedule_day_of_week',$day)
						->where('class_schedule_time_slot',$list->univ_time_slot_slug)
						->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
						->get();
					}

				}

				?>

				<table class="table table-bordered" style="margin-top:-5px;border-top:hidden;margin-bottom:-5px;border-bottom:hidden;margin-left:-5px;border-left:hidden;border-right:hidden;margin-right:-5px; width:100%" >

					@if(!empty($existing_class))
					@foreach($existing_class as $key => $schedule_list)


					<tr style="text-align:center">
						<td style="width:33%;">{{$schedule_list->class_schedule_course}}</td>
						<td style="width:33%;">{{strtoupper(substr($schedule_list->first_name,0,1))}}{{strtoupper(substr($schedule_list->middle_name,0,1))}}{{strtoupper(substr($schedule_list->last_name,0,1))}}</td>
						<td style="width:33%">{{$schedule_list->class_schedule_room}}</td>
					</tr>

					@endforeach

					@endif
				</table>

			</td>

			@endfor

		</tr>

		@endforeach
		@endif


	</tbody>
</table>