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

<table class="table table-bordered">
	<thead>
		@if(!empty($student_info))

		<tr>
			<th class="heading" colspan="3">STUDENT INFORMATION</th>					
		</tr>

		<tr>
			<th>Student Name:</th>
			<th>{{$student_info->first_name}} {{$student_info->middle_name}} {{$student_info->last_name}}</th>
		</tr>
		<tr>
			<th>Student ID:</th>
			<th>{{$student_info->student_serial_no}}</th>
		</tr>
		<tr>
			<th>Program:</th>
			<th>{{$student_info->program_code}}</th>
		</tr>
		@endif
	</thead>
</table>

<table class="table table-bordered">
	<thead>
		<tr>
			<th class="heading" colspan="3">STUDENT ACCOUNTS SUMMERY</th>					
		</tr>
		<tr>
			<th> Receivable</th>
			<th>{{isset($total_payment_receivable) ? $total_payment_receivable : '0'}} Tk</th>
		</tr>
		<tr>
			<th> Paid</th>
			<th>{{isset($total_payment_paid) ? $total_payment_paid : '0'}} Tk</th>
		</tr>
		<tr>
			<th> Due</th>
			<th>{{isset($total_payment_due) ? $total_payment_due : '0'}} Tk</th>
		</tr>
	</thead>
</table>

<table class="table table-striped table-bordered table-hover">
	<thead>

		<tr>
			<th class="heading" colspan="7">STUDENT ACCOUNTS SUMMERY LIST</th>					
		</tr>
		<tr>
			<th>SL No.</th>
			<th>Tran Date</th>
			<th>Trimester</th>
			<th>Year</th>
			<th>Collected By</th>
			<th>Fee Type</th>
			<th>Details</th>
			<th>Receiveable </th>
			<th>Slip No</th>
			<th>Paid</th>
			<th>Other</th>
			<th>Total Paid</th>
			<th>Created By</th>
			<th>Created At</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($student_payment_transaction_detail) && count($student_payment_transaction_detail)>0)
			@foreach($student_payment_transaction_detail as $key => $student_payment_transaction)

				<tr>
					<td>{{$key+1}}</td>
					<td>{{isset($student_payment_transaction->accounts_transaction_date)?$student_payment_transaction->accounts_transaction_date : date("Y-m-d",strtotime($student_payment_transaction->transaction_date))}}</td>
					<td>{{$student_payment_transaction->semester_title}}</td>
					<td>{{$student_payment_transaction->payment_year}}</td>
					<td>{{ucfirst($student_payment_transaction->payment_receive_type)}}</td>
					<td>
						<?php
						$fee_category=DB::table('fee_category')->where('fee_category_name_slug',$student_payment_transaction->payment_transaction_fee_type)->first();
						?>
						@if(!empty($fee_category))
						{{$fee_category->fee_category_name}}

						@elseif($student_payment_transaction->payment_transaction_fee_type=='other_fees')
						Other Fees
						@else
							@if(($student_payment_transaction->payment_transaction_fee_type) == 'Waiver' && !empty($student_payment_transaction->waiver_type))

							<?php $waiver_info=\DB::table('waivers')->where('waiver_name_slug', $student_payment_transaction->waiver_type)->first(); ?>
								{{$student_payment_transaction->payment_transaction_fee_type}} ({{isset($waiver_info)? $waiver_info->waiver_name :''}})
							@else
								{{$student_payment_transaction->payment_transaction_fee_type}}
							@endif
						@endif

					</td>
					<td>{{$student_payment_transaction->payment_details}}</td>
					<td>
						@if(($student_payment_transaction->payment_transaction_fee_type == 'tution_fee') && ($student_payment_transaction->payment_receivable !=0))
							<span data-toggle="tooltip" title="{{$student_payment_transaction->payment_details}}">{{$student_payment_transaction->payment_receivable}}
							</span>
						@else
							{{$student_payment_transaction->payment_receivable}}
						@endif
					</td>
					<td>{{isset($student_payment_transaction->transaction_slip_no)? $student_payment_transaction->transaction_slip_no :''}}</td>
					<td>{{$student_payment_transaction->payment_paid}}</td>
					<td>{{$student_payment_transaction->payment_others}}</td>
					<td>{{$student_payment_transaction->payment_amounts}}</td>
					<td>{{$student_payment_transaction->updated_by}}</td>
					<td>{{$student_payment_transaction->updated_at}}</td>
				</tr>
			@endforeach
		@else
			<tr class="text-center">
				<td colspan="14">No Data available</td>
			</tr>
		@endif
	</tbody>
</table>
@stop