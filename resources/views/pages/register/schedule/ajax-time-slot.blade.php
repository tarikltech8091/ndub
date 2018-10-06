

<div class="form-group">
	<label>Select Time Slot</label> 
	<select class="form-control time_slot" name="class_schedule_time_slot" data-class-day-week="{{$class_day_week}}">
		<option value="0">--Select Time Slot--</option>
		
		@foreach($time_slot as $key => $list)

		<?php
		$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();
		if(!empty($univ_academic_calender)){

			$univ_class_schedule=\DB::table('univ_class_schedule')
			->where('class_schedule_semester', $univ_academic_calender->academic_calender_semester)
			->where('class_schedule_year', $univ_academic_calender->academic_calender_year)
			->where('class_schedule_room',$room_code)
			->where('class_schedule_day_of_week',$class_day_week)
			->where('class_schedule_time_slot',$list->univ_time_slot_slug)
			->first();
		}

		?>
		
		@if(!empty($univ_class_schedule))

		@if($list->univ_time_slot_slug == $univ_class_schedule->class_schedule_time_slot)
		@else
		<option value="{{$list->univ_time_slot_slug}}">{{$list->univ_time_slot_slug}}</option>
		@endif

		@else
		<option value="{{$list->univ_time_slot_slug}}">{{$list->univ_time_slot_slug}}</option>
		@endif
		@endforeach

	</select>
</div>


<div class="ajax_faculty_list"></div>




<script type="text/javascript">

	/*
	###########################
	# Ajax Faculty List
	############################
	*/
	jQuery(function(){
		jQuery('.time_slot').change(function(){

			var time_slot = jQuery(this).val();
			var class_day_week = jQuery(this).data('class-day-week');

			var site_url = jQuery('.site_url').val();
			var request_url  = site_url+'/register/ajax-faculty-list/'+class_day_week+'/'+time_slot;
			
			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){

					jQuery('.ajax_faculty_list').html(data);

				}
			});
		});
	});

</script>