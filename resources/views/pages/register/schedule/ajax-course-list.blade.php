

@if(!empty($course_list))

<option value="0">Select Course</option>
@foreach($course_list as $key => $list)
<option value="{{$list->course_code}}">{{$list->course_code}} {{$list->course_title}}</option>
@endforeach
@else
<option value="0">No Course</option>

@endif


