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
			<th class="heading" colspan="7">TOTAL APPLICANT LIST</th>					
		</tr>
		<tr>
			<th>SL</th>
			<th>Applicant ID</th>
			<th>Name</th>
			<th>Mobile</th>
			<th>Email</th>
			<th>Program</th>
			<th>Trimester</th>
			<th>Year</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($applicant_list))
		<?php
		$total_amount=0;
		$total_applicant=0;
		?>
		@foreach($applicant_list as $key => $list)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$list->applicant_serial_no}}</td>
			<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
			<td>{{$list->mobile}}</td>
			<td>{{$list->email}}</td>
			<td>{{$list->program_code}}</td>
			<td>{{$list->semester_title}}</td>
			<td>{{$list->academic_year}}</td>
			<td>{{$list->applicant_fees_amount}}</td>
			
			<?php 
			$total_amount=$total_amount+$list->applicant_fees_amount;
			$total_applicant=$total_applicant+1;
			?>
		</tr>
		@endforeach
		
		@else
		<tr>
			<td colspan="9">
				<div class="alert alert-success">
					<center>No Data Available !</center>
				</div>
			</td>
		</tr>
		@endif
	</tbody>
</table>
@stop