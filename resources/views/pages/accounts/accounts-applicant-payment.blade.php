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
	<div class="col-md-12 ">
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
					@if(isset($program))
					<option {{($program==$list->program_id) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
					@else
					<option value="{{$list->program_id}}">{{$list->program_title}}</option>
					@endif
					
					@endforeach
					@endif
				</select>
			</div>
			<div class="form-group col-md-4">
				<label for="Semester">Trimester</label>
				<?php
				$semester_list=\DB::table('univ_semester')->get();
				?>
				<select class="form-control semester" name="semester" >
					<option value="0">All</option>
					@if(!empty($semester_list))
					@foreach($semester_list as $key => $list)
					<option {{(isset($semester) && ($semester==$list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
					@endforeach
					@endif
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="AcademicYear">Academic Year</label>
				<select class="form-control academic_year" name="academic_year" >
					<option value="0">All</option>
					<option {{(isset($academic_year) && ($academic_year==date('Y',strtotime('-1 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
					<option {{(isset($academic_year) && ($academic_year==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
					<option {{(isset($academic_year) && ($academic_year==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
				</select>
			</div>
			
			<div class="form-group col-md-1" style="margin-top:20px;">
				<button class="btn btn-danger applicant_payment_search" data-toggle="tooltip" title="Search Applicants By Program,Trimester,Year">Search</button>
			</div>
		</div><!--/header inline form-->
	</div>
	
	<div class="col-md-12 applicant_payment_table">

		<div class="panel panel-body">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>SL</th>
						<th>Applicant ID</th>
						<th>Applicant Name</th>
						<th>Program</th>
						<th>Trimester</th>
						<th>Academic Year</th>
						<th>Applied Date</th>
						<th>Payment Slip</th>
						<th>Payment Amount</th>
						<th>Payment Through</th>
						<th>Payment Status</th>
						<th>Action</th>
						<th>All<input type="checkbox" id="apporoved_payment_selectall" value="0" /></th>
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
						<td>{{date('Y-m-d',strtotime($applicant->created_at))}}</td>
						<td>{{$applicant->payment_slip_no}}</td>
						<td>{{$applicant->applicant_fees_amount}}</td>
						<td>{{strtoupper($applicant->payment_by)}}</td>
						<td>
							@if($applicant->payment_status==1 || $applicant->payment_status==5)
							PAID
							@elseif($applicant->payment_status==2)
							Waiting For Approval
							@elseif(($applicant->payment_status==0) || ($applicant->payment_status==''))
							TO BE PAID
							@endif
						</td>
						<td class="text-right">
							@if($applicant->payment_status==2)
							<button type="button" data-loading-text="Saving..." class="btn btn-info apporoved_payment_single loadingButton" autocomplete="off" data-id="{{$applicant->applicant_serial_no}}" data-toggle="tooltip" title="Approve Application Payment" >Approve</button>
							@elseif($applicant->payment_status==1 || $applicant->payment_status==5)
							<span class="approved_mark"><i class="fa fa-check"></i></span>
								@if((\Auth::user()->user_role=='head') && ($applicant->applicant_eligiblity == 1))
								<button class="btn btn-danger apporoved_payment_undone" data-id="{{$applicant->applicant_serial_no}}" data-payment-by="{{$applicant->payment_by}}" data-toggle="tooltip" title="Undo Applicants Payment"><i class="fa fa-undo" aria-hidden="true"></i></button>
								@endif
							@endif

							<button  type="button" class="btn btn-primary accounts_message_to_applicant" data-applicant="{{$applicant->applicant_serial_no}}" data-message-issue="application_payment" data-toggle="modal" data-target="#message" data-toggle1="tooltip" title="Message To Applicant"><i class="fa fa-envelope-o" aria-hidden="true"></i></button>



						</td>
						<td>
							@if($applicant->payment_status==2)

							<input type="checkbox" name="payment_approved_checkbox[]" class="apporoved_payment_group" value="{{$applicant->applicant_serial_no}}">
							@endif

						</td>
					</tr>

					@endforeach
					<tr>
						<td colspan="13">
							<button type="button" data-loading-text="Saving..." class="btn btn-primary pull-right apporoved_payment_submit loadingButton" autocomplete="off" data-nexturl="{{$all_applicant->nextPageUrl()}}"  data-toggle="tooltip" title="Save All and Go Next Page">Save & Next</button>
						</td>
					</tr>
					@else
					<tr class="text-center">
						<td colspan="13">
							<div class="alert alert-success">
								<center>No Data Available !</center>
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