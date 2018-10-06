<div class="col-md-12">
	<table class="table table-striped table-bordered table-hover table-condensed" style="background-color:#EDEDED" >
		<thead>
			<tr>
			<th colspan="10"><center>Payment Ledger: {{isset($semester_title) ? $semester_title : ''}} {{isset($year) ? $year : ''}}</center></th>
			</tr>
			<tr>
				<th class="text-center">Tran Date</th>
				<th class="text-center">Trimester</th>
				<th class="text-center">Year</th>
				<th class="text-center">Collected By</th>
				<th class="text-center">Fee Type</th>
				<th class="text-center">Details</th>
				<th class="text-center">Payable</th>
				<th class="text-center">Payment Paid</th>
				<th class="text-center">Payment Other</th>
				<th class="text-center">Total Amount</th>
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
					{{isset($student_payment_transaction->accounts_transaction_date)?$student_payment_transaction->accounts_transaction_date : date("Y-m-d",strtotime($student_payment_transaction->transaction_date))}}
				</td>
				<td>{{$student_payment_transaction->semester_title}}</td>
				<td>{{$student_payment_transaction->payment_year}}</td>
				<td>
					{{$student_payment_transaction->payment_receive_type}}
				</td>
				<td>{{isset($student_payment_transaction->fee_category_name) ? $student_payment_transaction->fee_category_name : 'Waiver'}}</td>
				<td>{{$student_payment_transaction->payment_details}}</td>
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
				<th colspan="6"><center>Total Transaction</center></th>
				<th>{{$total_receivable}}</th>
				<th>{{$total_paid}}</th>
				<th>{{$total_other}}</th>
				<th>{{$total_amount}}</th>
			</tr>
			@else
			<tr><th colspan="10" class="text-center">No Data Available</th></tr>
			@endif
		</tbody>
	</table>
</div>