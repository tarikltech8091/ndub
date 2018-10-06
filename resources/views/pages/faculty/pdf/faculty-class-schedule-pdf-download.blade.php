

<style type="text/css">
	table, td, th {
		border: 1px solid black;
		font-family: "Times New Roman", Times, serif;
	}

	table {
		border-collapse: collapse;
		width: 100%;
	}

	span{
		font-family: "Times New Roman", Times, serif;
	}

	.header_table{
		background-color:#f8f9f9;
	}

	.header_th_logo{
		text-align:left;
		width:30%
	}

	.header_th_logo img{
		width:200px;
		height:70px;
	}

	.header_nb{
		width:30%;
		text-align:right;
		font-size: 11px;
		vertical-align: bottom;
	}

	.header_title_p{
		font-size:13px;
		padding:0px;
		margin:0;
	}


</style>


<table border="0" class="header_table">
	<tr>
		<th class="header_th_logo">
			<img src="{{asset('images/banner-form.png')}}">
		</th>
		<th style="width:40%;">
			@if(!empty($faculty_basic))
			<p class="header_title_p">{{strtoupper($faculty_basic->first_name.' '.$faculty_basic->middle_name.' '.$faculty_basic->last_name)}}</p>
			<p class="header_title_p">{{$faculty_basic->program_title}}</p>
			<p class="header_title_p">Faculty Class Schedule</p>
			<p class="header_title_p">{{ucfirst($univ_academic_calender->semester_title)}}-{{$univ_academic_calender->academic_calender_year}}</p>
			@endif
		</th>
		<td class="header_nb">
			<span>[NB: Schedule may change if need.]</span>
		</td>
	</tr>
</table>

<table style="margin-top:10px">
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
