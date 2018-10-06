@if(!empty($program_list))
		<option value="">Select Program</option>
	@foreach($program_list as $key => $list)
		<option value="{{$list->program_id}}">{{$list->program_title}}</option>
	@endforeach
@else
	<option value="">Select Program</option>
@endif