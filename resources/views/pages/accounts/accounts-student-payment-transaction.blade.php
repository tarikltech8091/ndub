@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<style>
	th{
		padding-left: 10px;
	}
</style>

<!--error message*******************************************-->
<div class="row page_row">
	<div class="col-md-12">
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

	</div>
</div>
<!--end of error message*************************************-->


<div class="row page_row">
	<div class="col-md-12 form-inline">

		<div class="col-md-12 panel panel-body search_panel_bg_color">

			<div class="col-md-6">
				<form  action="{{url('/accounts/student-payment-transaction')}}" method="get">
					@if(isset($_GET['student_serial_no']))
					<input type="text" class="form-control search_width" name="student_serial_no" value="{{$_GET['student_serial_no']}}" placeholder="Search Student ID..">
					@else
					<input type="text" class="form-control search_width" name="student_serial_no" value="{{old('student_serial_no')}}" placeholder="Search Student ID..">
					@endif

					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Students Payment Store">Search !</button>
				</form>
			</div>

		</div>

	</div>
</div>


@if(isset($_GET['student_serial_no']) && !empty($_GET['student_serial_no']))
<div class="row page_row">
	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="pull-left"> Student Payment </div>
				<div class="pull-right"> 
					<a data-toggle="modal" data-target="#CourseDetailsModal" class="btn btn-primary btn-xs" style="line-height:0.5;" data-toggle1="tooltip" title="Course Details">Course Details</a>
				</div>
			</div>
			<div class="panel-body" style="padding-left:0;padding-right:0"><!--info body-->

				<form action="{{url('/accounts/student-payment-submit')}}" method="post" enctype="multipart/form-data">


					<div class="col-md-4 form-group">
						<label class="control-label">Transaction Date <span class="required-sign">*</span></label>
						<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="accounts_transaction_date" size="16" type="text" value="{{date('Y-m-d')}}" readonly>
							<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>

					</div>

					<div class="col-md-4 form-group">
						<label>Academic Year<span class="required-sign">*</span></label>
						<input type="text" class="form-control" name="academic_year" value="{{date('Y')}}" />
					</div>
					<?php
						$semester=\DB::table('univ_semester')->get();
					?>
					<div class="col-md-4 form-group">
						<label>Trimester<span class="required-sign">*</span></label>
						<select name="semester" class="form-control">
							@if(!empty($semester))
							@foreach($semester as $key => $list)
							<option value="{{$list->semester_code}}">{{$list->semester_title}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="col-md-6 form-group">
						<label>Fees Type<span class="required-sign">*</span></label>
						<select name="fee_type" class="form-control fee_type">
							<option value="">Select Fee Type</option>
							@if(!empty($fee_list))
							@foreach($fee_list as $key => $list)
							
							<?php
							// $fee_category=DB::table('fee_category')->where('fee_category_name_slug', $list->accounts_fee_name_slug)->first();
							$fee_category=DB::table('fee_category')->where('fee_category_name_slug', $list->fee_category_name_slug)->first();
							?>
							<option value="{{$fee_category->fee_category_name_slug}}">{{$fee_category->fee_category_name}}</option>
							
							@endforeach
							@endif
							<option value="Waiver">Waiver</option>
							<option value="other_fees">Other Fees</option>
						</select>
					</div>

					<div class="col-md-6 form-group">
						<label>Receive Type<span class="required-sign">*</span></label>
						<select name="receive_type" class="form-control">
							<option value="bank">Bank</option>
							<option value="cash">Cash</option>
							<option value="NDUB">NDUB</option>
						</select>
					</div>

					<div class="fee_type_details">

					</div>


					<div class="col-md-12 form-group">
						<label>Payment Details</label>
						<textarea type="text" name="payment_details" placeholder="Details" class="form-control" rows="2"></textarea>
					</div>

					<div class="col-md-12" style="margin-top:10px">
						<input type="hidden" name="_token" value="{{csrf_token()}}" />
						<input type="hidden" name="student_serial_no" value="{{$student_info->student_serial_no}}" />
						<button type="submit" class="btn btn-primary btn-sm pull-right" data-toggle="tooltip" title="Add Payment For The Student">Add Payment</button>
					</div>
				</form>
			</div><!--/info body-->
		</div>
	</div>


	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="pull-left"> Student Transaction </div>
				<div class="pull-right"> 
					<a href="{{url('/accounts/student/id-'.$_GET['student_serial_no'].'/admit/mid_term_exam')}}" class="btn btn-primary btn-xs" style="line-height:0.5;" data-toggle1="tooltip" title="Mid Term Admit" target="_blank">Mid Term Admit</a>
					<a href="{{url('/accounts/student/id-'.$_GET['student_serial_no'].'/admit/final_exam')}}" class="btn btn-primary btn-xs" style="line-height:0.5;" data-toggle1="tooltip" title="Final Admit" target="_blank">Final Admit</a>
				</div>
			</div>
			<div class="panel-body cursor" style="padding-left:0;padding-right:0"><!--info body-->

				<div class="col-md-12">
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

				<div class="col-md-12">
					<label>Student Transactions</label>
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
								<th>{{isset($total_other_paid) ? $total_other_paid : ''}} Tk</th>
							</tr>
							<tr>
								<th>Total Due</th>
								<th>{{isset($total_payment_due) ? $total_payment_due : ''}} Tk</th>
							</tr>
						</thead>
					</table>
				</div>


			</div>

		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-body cursor" style="padding-left:0;padding-right:0; background-color:#ddd">
		<div class="text-center"><h4>Student Payment Search</h4></div>

			<form method="get" action="{{url('/accounts/student-payment-transaction')}}">
				<div class="col-md-3"></div>
				<?php $student_id=$_GET['student_serial_no']; ?>
				<input type="hidden" name="student_serial_no" value="{{$_GET['student_serial_no']}}">
				<div class="col-md-2">
					<select name="semester" class="form-control">
						<option value="0">Select Trimester</option>
						@if(!empty($univ_semester_info) && count($univ_semester_info)>0)
						@foreach($univ_semester_info as $key => $list)
						<option {{(isset($_GET['semester']) && ($list->semester_code==$_GET['semester'])) ? 'selected':''}}  value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="col-md-2">
					<select name="year" class="form-control">
						<option value="0">Select Year</option>
						@if(!empty($univ_academic_year_info) && count($univ_academic_year_info)>0)
						@foreach($univ_academic_year_info as $key => $list)
						<option {{(isset($_GET['year']) && ($list->academic_calender_year==$_GET['year'])) ? 'selected':''}} value="{{$list->academic_calender_year}}">{{$list->academic_calender_year}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="col-md-2">
					<input class="form control btn btn-primary" data-toggle="tooltip" title="" data-original-title="Search Student Payment" type="submit" value="Search">

						<?php
							if(isset($_GET['student_serial_no']) && isset($_GET['semester']) && isset($_GET['year'])){
								$semester_info=$_GET['semester'];
								$year_info=$_GET['year'];
							}else{
								$semester_info= 0;
								$year_info= 0;
							}
						?>

						<a href="{{url('/accounts/student/payment/excel/sno-'.$_GET['student_serial_no'].'/semester-'.$semester_info.'/year-'.$year_info)}}"><span class="btn btn-warning" data-toggle="tooltip" title="" data-original-title="Download Student Payment"><i class="fa fa-print"></i></span></a>

				</div>

				<div class="col-md-3"></div>
			</form>
		</div>
	</div>

	<div class="panel panel-body cursor" style="padding-left:0;padding-right:0;">
		<div class="col-md-12"  style="height: 500px; overflow: auto; padding: 5px">
			<table class="table table-striped table-bordered table-hover table-condensed" style=" background-color:#fff" >

				<thead>
					<tr>
						<th colspan="13"><center>Student Payment Ledger</center></th>
					</tr>
					<tr>
						<th>SL</th>
						<th>Tran Date</th>
						<th>Trimester</th>
						<th>Year</th>
						<th>Collected By</th>
						<th>Fee Type</th>
						<th>Details</th>
						<th>Receivable</th>
						<th>Slip No</th>
						<th>Paid</th>
						<th>Other</th>
						<th>Total Paid</th>
						<th>Action</th>
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
								<span data-toggle="tooltip" title="{{$student_payment_transaction->payment_details}}">{{$student_payment_transaction->payment_receivable}}
								</span>
							@else
								{{$student_payment_transaction->payment_receivable}}
							@endif
						</td>
						<td>{{$student_payment_transaction->transaction_slip_no}}</td>
						<td>{{$student_payment_transaction->payment_paid}}</td>
						<td>{{$student_payment_transaction->payment_others}}</td>
						<td>{{$student_payment_transaction->payment_amounts}}</td>
						<td>
							@if(empty($student_payment_transaction->payment_receive_type) || ($student_payment_transaction->payment_transaction_fee_type =='admission_fee'))

							@else
							<button type="button" class="btn btn-default btn-xs student_payment_edit_modal" data-payment-tran-code="{{$student_payment_transaction->payment_transaction_tran_code}}" data-toggle="modal" data-target="#exampleModal" data-toggle1="tooltip" title="Update Payment"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>

							<a data-confirm-url="{{url('/accounts/student-payment-delete',$student_payment_transaction->payment_transaction_tran_code)}}" class="btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Payment"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
							@endif
						</td>
					</tr>

					<?php
					$total_receivable=$total_receivable+$student_payment_transaction->payment_receivable;
					$total_paid=$total_paid+$student_payment_transaction->payment_paid;
					$total_other=$total_other+$student_payment_transaction->payment_others;
					$total_amount=$total_amount+$student_payment_transaction->payment_amounts;
					?>

					@endforeach
					<tr>
						<th colspan="7"><center>Total Transaction</center></th>
						<th colspan="2">{{$total_receivable}}</th>
						<th>{{$total_paid}}</th>
						<th>{{$total_other}}</th>
						<th colspan="2">{{$total_amount}}</th>
					</tr>
					@else
					<tr>
						<td colspan="13" class="text-center">No data available!</td>

					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div><!--/info body-->

</div>


<!-- Modal -->
<div id="CourseDetailsModal" class="modal fade bs-example-modal-lg" rtabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Current Trimester Course Details</h4>
			</div>

			<div class="modal-body">
				<div class="row col-md-12">
					
					@if(!empty($registered_class_course) || !empty($registered_lab_course))
					<table class="table table-bordered table-hover">

						<thead>
							<tr>
								<th>Course ID</th>
								<th>Course Titile</th>
								<th>Credit</th>
							</tr>
						</thead>

						<tbody>

						@if(!empty($registered_class_course))

							@foreach($registered_class_course as $key => $list)
								<?php 
									$registered_class_course=\DB::table('student_class_registers')
			                        ->where('student_tran_code',$list->student_tran_code)
			                        ->where('class_course_code', $list->course_code)
			                        ->get();
								?>
							<tr>
								<td>{{$list->course_code}}</td>
								<td>{{$list->course_title}}</td>
								@if(!empty($registered_class_course) && count($registered_class_course)>1)
									<td>{{number_format($list->credit_hours,1,'.','')}} (Repeat/Retake)</td>
								@else
									<td>{{number_format($list->credit_hours,1,'.','')}}</td>
								@endif
							</tr>
							@endforeach

						@endif

						@if(!empty($registered_lab_course))

							@foreach($registered_lab_course as $key => $list)
							<tr>
								<td>{{$list->course_code}}</td>
								<td>{{$list->course_title}}</td>
								@if(!empty($registered_lab_course) && count($registered_lab_course)>1)
									<td style="width:68%; padding-left:10px;">{{number_format($list->credit_hours,1,'.','')}} (Repeat/Retake)</td>
								@else
									<td style="width:68%; padding-left:10px;">{{number_format($list->credit_hours,1,'.','')}}</td>
								@endif
							</tr>
							@endforeach
							
						@endif

						</tbody>
					</table>
					@else
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Course Available !</h3></center>
					</div>
					@endif
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>

		</div>
		<!-- /Modal content-->
	</div>
</div><!-- /Modal -->




@endif


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog" role="document">

		<div class="student_payment_edit"></div>

	</div>
</div>


@stop