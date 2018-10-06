@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="page_row row"><!--message-->
	<div class="col-md-12">
		<!--error message*******************************************-->
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->


<div class="row page_row">

	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading">Payment Ledger
				
			</div>
			<div class="panel-body"><!--info body-->
				<div class="student_payment">
					<div class="col-md-4">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Name</th>
									<th>{{isset($student_info->first_name) ? $student_info->first_name : ''}} {{isset($student_info->middle_name) ? $student_info->middle_name : ''}} {{isset($student_info->last_name) ? $student_info->last_name : ''}}</th>
								</tr>
								<tr>
									<th>ID</th>
									<th>{{isset($student_info->student_serial_no) ? $student_info->student_serial_no : ''}}</th>
								</tr>
								<tr>
									<th>Program</th>
									<th>{{isset($student_info->program_code) ? $student_info->program_code : ''}}</th>
								</tr>
							</thead>
						</table>

					</div>

					<div class="col-md-4">
						<center><h2>Student Ledger</h2></center>
						<select name="semester" class="form-control" id="student_payment_history_search">
							<option value="all">ALL</option>
							@if(!empty($univ_academic_calender))
							@foreach($univ_academic_calender as $key => $list)
							<option value="{{$list->semester_code.'.'.$list->academic_calender_year}}">{{$list->semester_title}} {{$list->academic_calender_year}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="col-md-4 right_side pull-right">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Total Receivable</th>
									<th>{{isset($total_payment_receivable) ? $total_payment_receivable : ''}} Tk</th>
								</tr>
								<tr>
									<th>Total Paid</th>
									<th>{{isset($total_payment_paid) ? $total_payment_paid : ''}} Tk</th>
								</tr>
								<tr>
									<th>Total Others Paid</th>
									<th>{{isset($total_payment_others) ? $total_payment_others : ''}} Tk</th>
								</tr>
								<tr>
									<th>Total Due</th>
									<th>{{isset($total_payment_due) ? $total_payment_due : ''}} Tk</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>

				<div id="payment_history" class="cursor" style="margin-top:20px;">
					

					<div class="col-md-12" style="height: 500px; overflow: auto; padding: 5px">
						<table class="table table-striped table-bordered table-hover table-condensed" style="background-color:#EDEDED" >
							<thead>
								<tr>
									<th colspan="10"><center>Payment Ledger: ALL</center></th>
								</tr>
								<tr>
									<th class="text-center">Tran Date</th>
									<th class="text-center">Semester</th>
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

									<td>{{isset($student_payment_transaction->accounts_transaction_date)?$student_payment_transaction->accounts_transaction_date : date("Y-m-d",strtotime($student_payment_transaction->transaction_date))}}</td>
									<td>{{$student_payment_transaction->semester_title}}</td>
									<td>{{$student_payment_transaction->payment_year}}</td>
									<td>
										{{$student_payment_transaction->payment_receive_type}}
									</td>
									<td>
										<!-- {{isset($student_payment_transaction->fee_category_name) ? $student_payment_transaction->fee_category_name : 'Waiver'}} -->

										<?php
										$fee_category=DB::table('fee_category')->where('fee_category_name_slug',$student_payment_transaction->payment_transaction_fee_type)->first();
										?>
										@if(!empty($fee_category))
											{{$fee_category->fee_category_name}}
										@elseif($student_payment_transaction->payment_transaction_fee_type=='other_fees')
											Other Fees
										@else

											@if(($student_payment_transaction->payment_transaction_fee_type) == 'Waiver')
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
											<span data-toggle="tooltip" title="{{$student_payment_transaction->payment_details}}">{{$student_payment_transaction->payment_receivable}}</span>
										@else
											{{$student_payment_transaction->payment_receivable}}
										@endif
									</td>
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

				</div>


			</div><!--/info body-->
		</div>
	</div>
	<!--sidebar widget-->
	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>
	<!--/sidebar widget-->	
</div>

@stop