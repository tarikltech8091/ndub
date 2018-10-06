
<form action="{{url('/faculty/pre-advising-submit')}}" method="post" enctype="multipart/form-data">
<table class="table table-striped  table-hover">
	<thead>
		@foreach($student_basic as $key => $student_basic)
		<tr class="serach_result">
			<th>Student ID : {{$student_basic->student_serial_no}}</th>
			<th>Sutdent Name: {{$student_basic->first_name}} {{$student_basic->middle_name}} {{$student_basic->last_name}}</th>
			<th>Trimester: {{$student_basic->semester_title}} {{$student_basic->temp_preadvising_year}}</th>
			<th></th>
		</tr>
		<input type="hidden" value="{{$student_basic->student_tran_code}}" name="student_tran_code" />
		@endforeach
		<tr class="result">
			<th>Course ID</th>
			<th>Course Titile</th>
			<th>Credit</th>
			<th><input type="checkbox" checked /> All</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$total_credit=0;
		?>
		@foreach($faculty_pre_advising_confirm as $key => $pre_advising_detail)

		<tr>
			<td>{{$pre_advising_detail->temp_course_code}}</td>
			<td>{{$pre_advising_detail->temp_course_title}}</td>
			<td>{{$pre_advising_detail->temp_credit_hours}}</td>
			<td><input type="checkbox" name="advised_course[]" value="{{$pre_advising_detail->temp_course_code}}" checked/>

			</td>
		</tr>
		<?php 
		$total_credit = $total_credit+$pre_advising_detail->temp_credit_hours;
		?>
		@endforeach

		<tr>
			<th colspan="2" class="text-left">Total Credit</th>
			<th >{{$total_credit}}</th>
		</tr>

	</tbody>

</table>


<div class="form-group text-right">
	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">View Details</button>
	<button class="btn btn-default">Cancel</button>
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<button type="submit" class="btn btn-success">Confirm</button>	
</div>
</form>