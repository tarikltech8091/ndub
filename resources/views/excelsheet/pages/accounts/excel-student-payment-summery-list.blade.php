@extends('excelsheet.layout.master-excel')
@section('content')
<style type="text/css">
	.texting{
		background-color: #19a15f;
		border: 1px solid;
	}
	.heading{
		text-align: center;
		font-weight: bolder;
		background-color: #92cddc;
		border: 1px solid;
		color: #000;
		font-size: 18px;
	}

	
</style>
<table class="table table-striped table-bordered table-hover">
	<thead>

		<tr>
			<th class="heading" colspan="7">STUDENT ACCOUNTS SUMMERY LIST</th>					
		</tr>
		<tr>
			<th>SL</th>
			<th>Student ID</th>
			<th>Student Name</th>
			<th>Program</th>
			<th>Mobile</th>
			<th>Email</th>
			<th> Receivable</th>
			<th> Receivable Paid</th>
			<th>Others Paid</th>
			<th>Due</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($all_student_payment_summery_info) && count($all_student_payment_summery_info)>0)
			@foreach($all_student_payment_summery_info as $key => $value)
				<?php 
					$student_details=unserialize($value); 
				?>
				<tr>

					<td>{{$key+1}}</td>
					<td>{{$student_details[0]}}</td>
					<td>{{$student_details[1]}}</td>
					<td>{{$student_details[2]}}</td>
					<td>{{$student_details[3]}}</td>
					<td>{{$student_details[4]}}</td>
					<td>{{$student_details[5]}}</td>
					<td>{{$student_details[6]}}</td>
					<td>{{$student_details[7]}}</td>
					<td>{{$student_details[8]}}</td>
				</tr>
			@endforeach
		@else
			<tr class="text-center">
				<td colspan="7">No Data available</td>
			</tr>
		@endif
	</tbody>
</table>
@stop