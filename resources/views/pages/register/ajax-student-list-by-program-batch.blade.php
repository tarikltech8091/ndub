@if(!empty($student_list))
	<option value="0">Select Student</option>
	@foreach($student_list as $key => $list)
		<option {{(isset($_GET['student_no']) && ($_GET['student_no'] == $list->student_serial_no)) ? 'selected' : ''}} value="{{$list->student_serial_no}}">{{$list->student_serial_no}}</option>
	@endforeach
@else
	<option value="0">Select Student</option>
@endif
