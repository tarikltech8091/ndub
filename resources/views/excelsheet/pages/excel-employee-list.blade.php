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
			<th class="heading" colspan="7">EMPLOYEE LIST</th>					
		</tr>
		<tr>
			<th>SL No.</th>
			<th>Faculty ID</th>
			<th>Faculty Name</th>
			<th>Employee Department</th>
			<th>Designation</th>
			<th>Joining Date</th>
			<th>Mobile</th>
			<th>Email</th>
			
		</tr>
	</thead>
	<tbody>
		@if(count($employee_list)>0)
		
		@foreach($employee_list as $key => $list)
		
		<tr>
			<td >{{($key+1)}}</td>
			<td >{{$list->employee_id}}</td>
			<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
			<td>{{$list->pro_designation}}</td>
			<td>{{$list->employee_designation}}</td>
			<td>{{$list->employee_join_date}}</td>
			<td>{{$list->mobile}}</td>
			<td>{{$list->email}}</td>
		</tr>

		@endforeach
		@else
		<tr class="text-center">
			<td colspan="8">No Data available</td>
		</tr>
		
		@endif
	</tbody>
</table>
@stop