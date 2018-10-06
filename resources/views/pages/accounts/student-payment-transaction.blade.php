@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<style>
	th{
		padding-left: 10px;
	}
</style>

<div class="row page_row">
	<div class="col-md-12">

		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			{{ Session::get('message') }}
		</div> 
		@endif
		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			{{ Session::get('errormessage') }}
		</div>
		@endif
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<div class="col-md-12 panel panel-body">

			<div class="col-md-6 form-inline">
				<form  action="{{url('/accounts/student-payment-transaction')}}" method="get">
					<div class="form-group col-md-12 panel panel-body">
						@if(isset($_GET['student_serial_no']))
						<input type="text" class="form-control" name="student_serial_no" value="{{$_GET['student_serial_no']}}">
						@else
						<input type="text" class="form-control" name="student_serial_no" value="{{old('student_serial_no')}}">
						@endif

						<button type="submit" class="btn btn-warning">Submit</button>
					</div>
				</form>
			</div>

		</div>

	</div>
</div>


@if(isset($_GET['student_serial_no']) && !empty($_GET['student_serial_no']))
<div class="row page_row">
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Student Payment</div>
			<div class="panel-body" style="padding-left:0;padding-right:0"><!--info body-->

			<form action="{{url('/accounts/student-payment-submit')}}" method="post" enctype="multipart/form-data">
					<div class="col-md-3">
						<label>Fees Type</label>
						<select name="fee_type" class="form-control">
						@if(!empty($fee_list))
						@foreach($fee_list as $key => $fee_lists)
						<option value="{{$fee_lists->accounts_fee_name_slug}}">{{$fee_lists->accounts_fee_name_slug}}</option>
						@endforeach
						@endif
						<option value="other_fees">Other Fees</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Amount</label>
						<input type="text" name="amount" placeholder="Amount" class="form-control"/>
					</div>
					<div class="col-md-3">
						<label>Receive Type</label>
						<select name="receive_type" class="form-control">
							<option value="bank">Bank</option>
							<option value="cash">Cash</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Slip No.</label>
						<input type="text" name="slip_no" placeholder="Slip no.." class="form-control"/>
					</div>
					<div class="col-md-12" style="margin-top:10px">
					<input type="hidden" name="_token" value="{{csrf_token()}}" />
					<input type="hidden" name="student_serial_no" value="{{$student_info->student_serial_no}}" />
						<button type="submit" class="btn btn-primary btn-sm pull-right">Add Payment</button>
					</div>
					</form>
			</div><!--/info body-->
		</div>
	</div>


	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Student Transaction</div>
			<div class="panel-body" style="padding-left:0;padding-right:0"><!--info body-->

				<div class="col-md-6">
				<label>Students Information</label>
					<table class="table table-bordered text-right">
						@if(!empty($student_info))
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
					</table>
				</div>

				<div class="col-md-6">
				<label>Transactions Information</label>
					<table class="table table-bordered text-right">
						<tr>
							<th>Total Receivable:</th>
							<th>{{$student_info->first_name}} {{$student_info->middle_name}} {{$student_info->last_name}}</th>
						</tr>
						<tr>
							<th>Total Paid:</th>
							<th>{{$student_info->student_serial_no}}</th>
						</tr>
						<tr>
							<th>Total Due:</th>
							<th>{{$student_info->program_code}}</th>
						</tr>
						<tr>
							<th>Total Other:</th>
							<th>{{$student_info->program_code}}</th>
						</tr>
					</table>
				</div>
				<div class="col-md-12">
				<label>Transaction Detail</label>
				<table class="table table-striped table-bordered table-hover table-condensed" style="background-color:#EDEDED" >
						<thead>
							<tr>
								<th>Tran Date</th>
								<th>Collected By</th>
								<th>Fee Type</th>
								<th>Payment Receivable</th>
								<th>Payment Paid</th>
								<th>Payment Other</th>
								<th>Total Amount</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($student_payment_transaction_detail))
							<?php
								$total_receivable=0;
								$total_paid=0;
								$total_other=0;
								$total_amount=0;
							?>
							@foreach($student_payment_transaction_detail as $key => $student_payment_transaction)
							
							<tr>
								<td>
									@if(!empty($student_payment_transaction->created_at))
									{{$student_payment_transaction->transaction_date}}
									@else
									<span style="color:red">Not Paid</span>
									@endif
								</td>
								<td>
									@if(!empty($student_payment_transaction->payment_receive_type))
									{{$student_payment_transaction->payment_receive_type}}
									@else
									<span style="color:red">Not Paid</span>
									@endif
								</td>
								<td>{{$student_payment_transaction->payment_transaction_fee_type}}</td>
								<td>{{$student_payment_transaction->payment_receivable}}</td>
								<td>{{$student_payment_transaction->payment_paid}}</td>
								<td>{{$student_payment_transaction->payment_others}}</td>
								<td>{{$student_payment_transaction->payment_amounts}}</td>
							</tr>

							<?php
								$total_receivable=$total_receivable+$student_payment_transaction->payment_receivable;
								$total_paid=$total_paid+$student_payment_transaction->payment_paid;
								$total_other=$total_other+$student_payment_transaction->payment_others;
								$total_amount=$total_amount+$student_payment_transaction->payment_amounts;
							?>

							@endforeach
							<tr>
								<th colspan="3"><center>Total Transaction</center></th>
								<th>{{$total_receivable}}</th>
								<th>{{$total_paid}}</th>
								<th>{{$total_other}}</th>
								<th>{{$total_amount}}</th>
							</tr>
							@endif
						</tbody>
					</table>
				</div>

			</div><!--/info body-->
		</div>
	</div>
</div>


@endif

@stop