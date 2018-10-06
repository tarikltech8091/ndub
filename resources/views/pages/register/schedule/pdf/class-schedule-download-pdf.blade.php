
<style type="text/css">
	table, td, th {
		border: 1px solid black;
		font-family: "Times New Roman", Times, serif;
	}

	table {
		border-collapse: collapse;
		width: 100%;
		font-size: 7px;
	}

	.font_bold{
		font-weight: bold;
	}

	span{
		font-family: "Times New Roman", Times, serif;
	}

	.header_table{
		background-color:#f8f9f9;
	}

	.header_th_logo{
		text-align:left;
		width:25%
	}

	.header_title{
		font-size:13px;
	}

	.header_trimester{
		font-size:11px;
		padding-top:5px;
		margin:0;
	}

	.header_nb{
		width:25%;
		text-align:right;
		vertical-align: bottom;
	}

	.schedule_table{
		width:100%;
		margin-top:5px;
	}

</style>

<table border="0" class="header_table">
	<tr>
		<th class="header_th_logo">
			<img src="{{asset('images/banner-form.png')}}" style="width:160px;height:50px;padding:0">
		</th>
		<th style="width:50%;">
			<?php
			$schedule_program=\DB::table('univ_program')->where('program_id',$program_id)->first();
			?>
			@if(!empty($schedule_program))
			<span class="header_title">{{$schedule_program->program_title}}</span>
			<p class="header_trimester">Class Schedule For Trimester {{ucfirst($semester_title)}}-{{$academic_year}}</p>
			@else
			<span class="header_title">All Program</span>
			<p class="header_trimester">Class Schedule For Trimester {{ucfirst($semester_title)}}-{{$academic_year}}</p>
			@endif
		</th>
		<td class="header_nb">
			<span>[NB: Schedule may change if need.]</span>
		</td>
	</tr>
</table>

<table class="schedule_table" >
	<thead>
		
		<?php 
		$i=1;  
		$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
		?>
		
		<tr>
			<th>Day/Time</th>
			@for($i=0;$i<=6;$i++)
			<th colspan="3">{{$days[$i]}}</th>
			@endfor
			
		</tr>
		<tr>
			<th style="border:hidden"></th>
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

				<table style="width:100%;border-collapse: collapse;margin-top:-1px;border-top:hidden;margin-bottom:-1px;border-bottom:hidden;margin-left:-1px;border-left:hidden;margin-right:-1px;border-right:hidden" border="1" >

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


