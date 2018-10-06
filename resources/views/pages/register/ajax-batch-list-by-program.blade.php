@if(!empty($student_batch))
	<option value="0">Select Batch</option>
	@foreach($student_batch as $key => $list)
		<option  {{(isset($_GET['batch']) && ($_GET['batch']==$list->batch_no)) ? 'selected' : ''}} value="{{$list->batch_no}}">{{$list->batch_no}}</option>
	@endforeach
@else
	<option value="0">Select Batch</option>
@endif
