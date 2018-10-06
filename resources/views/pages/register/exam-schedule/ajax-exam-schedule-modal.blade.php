
<div class="modal-body bd">

	<?php
	$program_list=\DB::table('univ_program')->get();
	?>


	<div class="form-group">
		<label>Select Program</label>
		<select class="form-control building_code program_code" name="exam_schedule_program">
			<option>--Select Program--</option>
			@if(!empty($program_list))
			@foreach($program_list as $key => $list)
			<option value="{{$list->program_id}}">{{$list->program_title}}</option>
			@endforeach
			@endif
		</select>
	</div>

	<div class="form-group">
		<label>Course Code</label>
		<div class="ajax_exam_course_list">
			<select name="exam_schedule_course" class="multipleSelectExample" style="width:100%" placeholder="Select Courses" multiple="">
			</select>
		</div>

	</div>


	<input type="hidden" name="_token" value="{{csrf_token()}}" >
	<input type="hidden" name="exam_schedule_room" value="{{$room_code}}" >
	<input type="hidden" name="exam_schedule_date" value="{{$exam_date}}" >
	<input type="hidden" name="exam_schedule_type" value="{{$exam_type}}" >
	<input type="hidden" name="exam_schedule_time_slot" value="{{$time_slot}}" >


</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</button>
</div>



<script type="text/javascript">

	jQuery(function(){
		jQuery('.program_code').change(function(){

			var program_code = jQuery(this).val();

			var site_url = jQuery('.site_url').val();
			var request_url  = site_url+'/register/ajax-exam-course-list/'+program_code;

			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					jQuery('.ajax_exam_course_list').html(data);
					jQuery(".multipleSelectExample222").select2();
				}
			});

		});
	});

/*###########################
# Faculty Course Assign Select 2
#############################
*/ 
jQuery(function(){
	jQuery(".multipleSelectExample").select2();
});



</script>
