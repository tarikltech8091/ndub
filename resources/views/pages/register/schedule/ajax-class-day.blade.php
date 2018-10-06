
@if(!empty($room_code))
<div class="form-group">
	<label>Select Day</label> 
	<select class="form-control class_day_week" name="class_schedule_day_of_week" data-room-code="{{$room_code}}">
	<option value="">--Select Day--</option>
		<option value="Saturday">Saturday</option>
		<option value="Sunday">Sunday</option>
		<option value="Monday">Monday</option>
		<option value="Tuesday">Tuesday</option>
		<option value="Wednesday">Wednesday</option>
		<option value="Thursday">Thursday</option>
		<option value="Friday">Friday</option>
		
	</select>
</div>

<div class="ajax_time_slot">
	
</div>

@endif


<script type="text/javascript">

	/*
	###########################
	# Ajax Faculty List
	############################
	*/
	jQuery(function(){
		jQuery('.class_day_week').change(function(){

			var class_day_week = jQuery(this).val();
			var room_code = jQuery(this).data('room-code');

			var site_url = jQuery('.site_url').val();
			var request_url  = site_url+'/register/ajax-time-slot/'+room_code+'/'+class_day_week;
			
			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){

					jQuery('.ajax_time_slot').html(data);

				}
			});
		});
	});

</script>