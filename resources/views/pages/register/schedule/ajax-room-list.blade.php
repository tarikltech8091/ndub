

@if(!empty($room_list))
<div class="form-group">
	<label>Select Room</label>
	<select class="form-control room_list" name="class_schedule_room">
		<option value="0">--Select Room--</option>
		@foreach($room_list as $key => $list)
		<option value="{{$list->room_code}}">{{$list->room_code}}</option>
		@endforeach
		
	</select>
</div>

<div class="ajax_class_day_week">

</div>

@endif


<script type="text/javascript">

	/*
	###########################
	# Ajax Room List
	############################
	*/
	jQuery(function(){
		jQuery('.room_list').change(function(){

			var room_code = jQuery(this).val();
			var site_url = jQuery('.site_url').val();
			var request_url  = site_url+'/register/ajax-class-day/'+room_code;

			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){

					jQuery('.ajax_class_day_week').html(data);

				}
			});
		});
	});

</script>