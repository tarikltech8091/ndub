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
			<th class="heading" colspan="7">ADMISSION LIST</th>					
		</tr>
		<tr>
			<th>SL No.</th>
			<th>Applicant ID</th>
			<th>Applicant Name</th>
			<th>Program</th>
			<th>Trimester</th>
			<th>Academic Year</th>
			<th>Remarks</th>
			
		</tr>
	</thead>
	<tbody>
		@if(count($all_applicant)>0)
		
		@foreach($all_applicant as $key => $applicant)
		
		<tr>
			<td >{{($key+1)}}</td>
			<td >{{$applicant->applicant_serial_no}}</td>
			<td>{{$applicant->first_name}} {{$applicant->middle_name}} {{$applicant->last_name}}</td>
			<td>{{$applicant->program_code}}</td>
			<td>{{strtoupper($applicant->semester_title)}}</td>
			<td>{{$applicant->academic_year}}</td>

			@if($applicant->applicant_eligiblity==5)
			<td >Admitted Student</td>
			@endif
			
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