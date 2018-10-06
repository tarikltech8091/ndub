
<style type="text/css">
	table {
		border-collapse: collapse;

	}

	table, td, th{
		font-size: 12px;
		vertical-align: text-top;
	}

	td, th {
		border: 1px solid black;

	}

	.header_table{
		background-color:#f8f9f9;
		width: 100%;
		padding-top: 20px;
		padding-bottom: 20px;
		border-bottom:1px solid black
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
		font-size: 10px;
		vertical-align: bottom;
	}
</style>

<div style="background-color:#f8f9f9;padding-left:5px;padding-right:5px">
	<table border="0" class="header_table">
		<tr>
			<th class="header_th_logo">
				<img src="{{asset('images/banner-form.png')}}" style="width:160px;height:50px;padding:0">
			</th>
			<th style="width:50%;">

				<span class="header_title">{{$program_title}}

				</span>
				<p class="header_trimester">
					@if($exam_type=='2')
					Trimester Midterm Exam Schedule

					@elseif($exam_type=='3')
					Trimester Final Exam Schedule
					@endif
				</p>
				<p class="header_trimester">{{ucfirst($univ_semester->semester_title)}}-{{$year}}</p>
			</th>
			<td class="header_nb">
				<span>[NB: Schedule may change if need.]</span>
			</td>
		</tr>
	</table>
	<!-- <hr style="border-bottom:1px solid black"> -->
	<br>
	@if(!empty($exam_schedule_data))
	@foreach($exam_schedule_data as $key => $date_list)
	<table style="width:100%">

		<thead>
			<tr>
				<td colspan="5"><center><h3>{{$date_list->exam_schedule_date}}</h3></center></td>
			</tr>
			<tr>
				<?php
				$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for', $exam_type)->orderBy('univ_time_slot','asc')->get();

				?>
				<th>Room/Slot</th>
				@if(!empty($time_slots))
				@foreach($time_slots as $key => $slot_list)
				<th>
					<table style="width:100%;border-top:hidden;border-right:hidden;border-left:hidden;border-bottom:hidden;margin-left:-1px;margin-right:-2px;margin-bottom:-1px">
						<tr>
							<td colspan="2">{{$slot_list->univ_time_slot_slug}}</td>
						</tr>
						<tr>
							<td style="width:50%">SC</td>
							<td style="width:50%">Program</td>
						</tr>
					</table>
				</th>

				@endforeach
				@endif
			</tr>
		</thead>

		<tbody>
			<?php
			$room_list=\DB::table('univ_room')->get();
			?>

			@if(!empty($room_list))
			@foreach($room_list as $key => $rooms)
			<tr>
				<th>{{$rooms->room_code}}</th>


				@if(!empty($time_slots))
				@foreach($time_slots as $key => $slot_list)
				<?php
				$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

				if($program=='all'){
					$existing_exam=\DB::table('univ_exam_schedule')
					->where('exam_schedule_type', $exam_type)
					->where('exam_schedule_room', $rooms->room_code)
					->where('exam_schedule_time_slot', $slot_list->univ_time_slot)
					->where('exam_schedule_semester', $trimester)
					->where('exam_schedule_year', $year)
					->where('exam_schedule_date', $date_list->exam_schedule_date)
					->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
					->get();

				}else{

					$existing_exam=\DB::table('univ_exam_schedule')
					->where('exam_schedule_type', $exam_type)
					->where('exam_schedule_program', $program)
					->where('exam_schedule_room', $rooms->room_code)
					->where('exam_schedule_time_slot', $slot_list->univ_time_slot)
					->where('exam_schedule_semester', $trimester)
					->where('exam_schedule_year', $year)
					->where('exam_schedule_date', $date_list->exam_schedule_date)
					->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
					->get();
				}
				
				?>
				
				<td>
					@if(!empty($existing_exam))
					<table style="width:100%;border-top:hidden;border-right:hidden;border-left:hidden;border-bottom:hidden;margin-left:-1px;margin-right:-2px;margin-top:-1px;margin-bottom:-1px" >
						@foreach($existing_exam as $key => $course_list)
						<tr>
							<td style="width:50%; ">{{$course_list->exam_schedule_course}}</td>
							<td style="width:50%">{{$course_list->program_code}}</td>
						</tr>
						@endforeach
					</table>

					@endif
					@endforeach
				</td>

				
				@endif

			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
	<br><br>

	@endforeach
	@else
	<div class="col-md-12 alert alert-success">
		<center><h3 style="font-style:italic">No Schedule Found !</h3></center>
	</div>
	@endif

</div>