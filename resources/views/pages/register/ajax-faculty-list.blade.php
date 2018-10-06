@if(!empty($faculty_list))
		<option value="">Select Faculty</option>
	@foreach($faculty_list as $key => $list)
		<option value="{{$list->faculty_id}}">{{trim($list->first_name.' '.$list->middle_name.' '.$list->last_name)}} {{$list->faculty_id}}</option>
	@endforeach
@else
	<option value="">Select Faculty</option>
@endif