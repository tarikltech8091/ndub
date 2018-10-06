
<select name="exam_schedule_course[]"  class="multipleSelectExample222" style="width:100%" placeholder="Select Courses" multiple="">
	@if(!empty($course_list))
	@foreach($course_list as $key => $list)
	<option value="{{$list->course_code}}">({{$list->course_code}}) {{$list->course_title}}</option>
	@endforeach
	@endif
</select>