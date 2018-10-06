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
<table class="table table-striped table-bordered table-hover applicant_register">
	<thead>

		<tr>
			<th class="heading" colspan="7">STUDENT LIST</th>					
		</tr>
		<tr>
			<th>SL</th>
			<th>Student ID</th>
			<th>Student Name</th>
			<th>Program</th>
			<th>Mobile</th>
			<th>Email</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($student_list) && count($student_list)>0)
		
		@foreach($student_list as $key => $list)
		
		<tr>
			<td >{{($key+1)}}</td>
			<td >{{$list->student_serial_no}}</td>
			<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
			<td>{{$list->program_code}}</td>
			<td>{{$list->mobile}}</td>
			<td>{{$list->email}}</td>
			<td>
				@if(($list->student_status) == '-5')
				Blocked
				@else
				Active
				@endif
			</td>
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