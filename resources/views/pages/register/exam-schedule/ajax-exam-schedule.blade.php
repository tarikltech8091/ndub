
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<?php
			$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for','midterm_exam_time_slot')->orderBy('univ_time_slot','asc')->get();

			?>
			<th class="text-center">Room/Slot</th>
			@if(!empty($time_slots))
			@foreach($time_slots as $key => $slot_list)
			<th class="text-center">{{$slot_list->univ_time_slot_slug}}</th>

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

			if(!empty($univ_academic_calender)){
				$existing_exam=\DB::table('univ_class_schedule')
				->where('class_schedule_status', $exam_type)
				->where('class_schedule_day_of_week', $exam_date)
				->where('class_schedule_room', $rooms->room_code)
				->where('class_schedule_time_slot', $slot_list->univ_time_slot)
				->where('class_schedule_semester', $univ_academic_calender->academic_calender_semester)
				->where('class_schedule_year', $univ_academic_calender->academic_calender_year)
				->first();
			}

			?>
			@if(!empty($existing_exam))
			<td class="text-center">
				<b>SC: {{$existing_exam->class_schedule_course}}</b><br>
				<i>Program: {{$existing_exam->class_schedule_program}}</i>
			</td>
			@else
			<td class="text-center">
				<button class="btn btn-primary btn-xs exam_schedule_modal" style="padding:0" data-toggle="modal" data-target="#add_course" data-room="{{$rooms->room_code}}" data-time-slot="{{$slot_list->univ_time_slot}}" data-exam-type="{{$exam_type}}" data-exam-date="{{$exam_date}}">Add Course</button>
			</td>
			@endif
			@endforeach
			@endif

		</tr>
		@endforeach
		@endif
	</tbody>
</table>




<div class="modal fade" id="add_course" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Exam Schedule</h4>
			</div>

			<div class="ajax_exam_schedule_modal"></div>

			
		</div>
	</div>
</div>


<script type="text/javascript">
	
	/*###########################
# Register Exam Schedule Modal
#############################
*/ 

jQuery(function(){

	jQuery('.exam_schedule_modal').click(function(){

		var room_code = jQuery(this).data('room');
		var exam_type = jQuery(this).data('exam-type');
		var exam_date = jQuery(this).data('exam-date');
		var time_slot = jQuery(this).data('time-slot');

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/schedule/exam-schedule-modal/'+room_code+'/'+exam_type+'/'+exam_date+'/'+time_slot;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_exam_schedule_modal').html(data);
			}
		});
		
	});
});
</script>