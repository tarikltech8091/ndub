@if(!empty($time_slot_list))
	@foreach($time_slot_list as $key => $list)
		<option value="{{$list->univ_time_slot_slug}}">{{$list->univ_time_slot_slug}}</option>
	@endforeach
@else
	<option value="">Select Time</option>
@endif