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
			<th class="heading" colspan="7">ACCOUNTS STUDENT PAYMENT LIST</th>					
		</tr>
		<tr>
			<th>SL No.</th>
			<th>Student ID</th>
			<th>Program</th>
			<th>Trimester</th>
			<th>Year</th>
			<th>Fee Type</th>
			<th>Receive Type</th>
			<th>Amount</th>
			<th>Created By</th>
			<th>Created At</th>
			
		</tr>
	</thead>
	<tbody>

		@if(!empty($student_payment_transaction_detail) && count($student_payment_transaction_detail)>0)
		@foreach($student_payment_transaction_detail as $key => $list)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$list->payment_student_serial_no}}</td>
			<td>{{$list->program_code}}</td>
			<td>{{$list->semester_title}}</td>
			<td>{{$list->payment_year}}</td>
			<td>{{($list->fee_category_name)? ($list->fee_category_name) : 'Others Fee' }}</td>
			<td>{{$list->payment_receive_type}}</td>
			<td>{{$list->payment_amounts}}</td>
			<td>{{$list->updated_by}}</td>
			<td>{{$list->updated_at}}</td>
		</tr>
		@endforeach
		<tr></tr>
		<tr></tr>
		<tr>
			<th colspan="7" align="center">Total Amount</th>
			<th colspan="3">{{isset($total_amount)? $total_amount :0}}</th>
		</tr>
		@else
		<tr>
			<td colspan="10" align="center">No Data Found !</td>
		</tr>
		@endif
	</tbody>
</table>
@stop