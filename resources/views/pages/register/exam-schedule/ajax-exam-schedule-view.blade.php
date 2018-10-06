
@if(!empty($exam_schedule_data))
@foreach($exam_schedule_data as $key => $date_list)
<table class="table table-bordered table-hover">

	<thead>
		<tr>
			<td colspan="9" class="text-center"><h3>{{$date_list->exam_schedule_date}}</h3></td>
		</tr>
		<tr>
			<?php
			$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for', $exam_type)->orderBy('univ_time_slot','asc')->get();

			?>
			<th class="text-center">Room/Slot</th>
			@if(!empty($time_slots))
			@foreach($time_slots as $key => $slot_list)
			<th class="text-center">
				<table class="table-bordered " style="margin-bottom:-5px;width:100%;float:right;border:hidden;">
					<tr>
						<td colspan="2">{{$slot_list->univ_time_slot_slug}}</td>
					</tr>
					<tr>
						<td style="width:50%">SC</td>
						<td>Program</td>
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
			<th class="text-center">{{$rooms->room_code}}</th>


			@if(!empty($time_slots))
			@foreach($time_slots as $key => $slot_list)
			<?php
			$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

			if($program=="all"){
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
			@if(!empty($existing_exam))
			<td>
				<table class="table-bordered" style="margin-top:-5px;width:100%;float:right;border-top:hidden;border-left:hidden;border-right:hidden" >
					@foreach($existing_exam as $key => $course_list)
					<tr>
						<td style="width:50%">{{$course_list->exam_schedule_course}}</td>
						<td>{{$course_list->program_code}}</td>
					</tr>
					@endforeach
				</table>
			</td>
			@else
			<td>

			</td>
			@endif
			@endforeach
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
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<center><h3 style="font-style:italic">No Schedule Found !</h3></center>
</div>
@endif

<script type="text/javascript">
	
	/*###########################
	# Confirm Box
	#############################
	*/ 
	jQuery(function(){

		jQuery('.confirm_box').click(function(){

			var confirm_url=jQuery(this).data('confirm-url');
			if (confirm("Do You Want To Delete ?") == true) {
				window.location.href=confirm_url;
			}
		});

	})
</script>