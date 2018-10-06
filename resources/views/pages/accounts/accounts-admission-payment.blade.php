@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

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

		@if(Session::has('accounts_applicant_message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Message successfully sent to <b>{{ Session::get('accounts_applicant_message') }}</b>
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
	<div class="col-md-12">

		<form action="{{url('/accounts/admission/payement/list')}}" method="get">

			<div class="panel panel-body padding_0 sorting_form"><!--header inline form-->
				<?php 
				$program_list =\App\Applicant::ProgramList();

				?>
				<div class="form-group col-md-4">
					<label for="Program">Program</label>
					<select class="form-control program" name="program" >
						<option value="0">All</option>
						@if(!empty($program_list))
						@foreach($program_list as $key => $list)
						<option {{isset($_GET['program']) && ($_GET['program']==$list->program_id) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="Semester">Trimester</label>
					<?php
					$semester_list=\DB::table('univ_semester')->get();
					?>
					<select class="form-control semester" name="semester" >
						<option value="0">All</option>
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						<option {{(isset($_GET['semester']) && ($_GET['semester']==$list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="AcademicYear">Academic Year</label>
					<select class="form-control academic_year" name="academic_year" >
						<option value="0">All</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-1 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
						<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
					</select>
				</div>
				<div class="form-group col-md-1" style="margin-top:20px;">
					<button type="button" class="btn btn-danger accounts_admission_payment_search" data-toggle="tooltip" title="Search Applicants Who Paid Admission Fee">Search</button>
				</div>

				<div class="col-md-1 margin_top_20">
				<span class="btn btn-warning accounts_admission_list_print" data-toggle="tooltip" title="Download Accounts Admission Payment List"><i class="fa fa-print"></i></span>
				</div>
			</div><!--/header inline form-->
		</form>
	</div>

	<div class="col-md-12 applicant_payment_table">

		<div class="panel panel-default">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>SL No.</th>
						<th>Applicant ID</th>
						<th>Applicant Name</th>
						<th>Program</th>
						<th>Trimester</th>
						<th>Academic Year</th>
						<th>Applicant Status</th>
						<th>Payment Status</th>
						<th>Payment Type</th>
						<th>Slip No</th>
						<th>Action</th>

					</tr>
				</thead>
				<tbody>
					@if(count($all_applicant)>0)
					
					@foreach($all_applicant as $key => $applicant)

					<tr>
						<td>{{($key+1)}}</td>
						<td>{{$applicant->applicant_serial_no}}</td>
						<td>{{$applicant->first_name}} {{$applicant->middle_name}} {{$applicant->last_name}}</td>
						<td>{{$applicant->program_code}}</td>
						<td>{{strtoupper($applicant->semester_title)}}</td>
						<td>{{$applicant->academic_year}}</td>

						<td>
							@if($applicant->applicant_eligiblity==4)
							Special Permission for Admission
							@elseif($applicant->applicant_eligiblity==3)
							Merit Listed
							@elseif($applicant->applicant_eligiblity==2)
							Waiting Listed
							@elseif($applicant->applicant_eligiblity==5)
							Admitted Student
							@endif
						</td>
						<td>
							@if($applicant->payment_status==5)
							PAID
							@else
							TO BE PAID
							@endif
						</td>
						<td>
							@if($applicant->payment_status==5)

							@if(!empty($applicant->applicant_serial_no))
							<?php
							$admission_payment=\DB::table('student_basic')->where('applicant_serial_no',$applicant->applicant_serial_no)
							->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_student_serial_no','=','student_basic.student_serial_no')
							->where('transaction_fees_type','admission_fee')
							->first();
							?>
							@endif

							{{ucfirst(isset($admission_payment) ? $admission_payment->transaction_receive_types : '')}}

							@else
							<select name="payment_type" class="payment_type_{{$applicant->applicant_serial_no}}" required>
								<option value="bank">Bank</option>
								<option value="cash">Cash</option>
							</select>
							@endif
						</td>
						<td>
							@if($applicant->payment_status==5)
							
							{{ucfirst(isset($admission_payment) ? $admission_payment->transaction_slip_no : '')}}

							@else
							<input type="text" name="slip_no" class="slip_no_{{$applicant->applicant_serial_no}}" placeholder="Enter slip no.."  />
							@endif
						</td>
						<td  id="{{$applicant->applicant_serial_no}}" class="text-right">
							@if($applicant->payment_status==5)
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@if((\Auth::user()->user_role=='head') && $applicant->applicant_eligiblity==5)
							<button class="btn btn-danger admission_payment_undone" data-id="{{$applicant->applicant_serial_no}}" data-toggle="tooltip" title="Undo Admission Payment"><i class="fa fa-undo" aria-hidden="true"></i></button>
							@endif
							@else

							<button type="button" data-loading-text="Saving..." class="btn btn-info admission_payment_single loadingButton"  data-id="{{$applicant->applicant_serial_no}}" data-toggle="tooltip" title="Approve Admission Payment">Approve</button>
							@endif

							<button  type="button" class="btn btn-primary accounts_message_to_applicant" data-applicant="{{$applicant->applicant_serial_no}}" data-message-issue="admission_payment" data-toggle="modal" data-target="#message" data-toggle1="tooltip" title="Message To Applicant"><i class="fa fa-envelope-o" aria-hidden="true"></i></button>
						</td>
						
					</tr>

					@endforeach
					<tr>
						@if(!empty($pagination))
						<td colspan="11"><a href="{{$all_applicant->nextPageUrl()}}" class="btn btn-default pull-right "> Next Page</a></td>
						@endif
					</tr>
					@else
					<tr class="text-center">
						<td colspan="11">
							<div class="alert alert-success">
								<center><h3 style="font-style:italic">No Data Available !</h3></center>
							</div>
						</td>
					</tr>

					@endif
				</tbody>		
			</table>
			{{isset($pagination) ? $pagination:""}}
		</div>
		<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
		<input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">
	</div>
</div>


<!-- message modal -->
<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="write_message_to"></div>
	</div>
</div>
<!-- message modal end-->

@stop