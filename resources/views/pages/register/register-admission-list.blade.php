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
			<div class="form-group col-md-3">
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

			<div class="col-md-1 margin_top_20">
				<button class="btn btn-danger admission_register_list_search" data-toggle="tooltip" title="Search Admission List">Search</button>
			</div>
			<div class="col-md-1 margin_top_20">
				<span class="btn btn-warning register_admission_list_print" data-toggle="tooltip" title="Download Admission List"><i class="fa fa-print"></i></span>
			</div>
		</div><!--/header inline form-->
	</div>
	<div class="col-md-12 applicant_payment_table">
		<div class="panel panel-default">

			<table class="table table-striped table-bordered table-hover applicant_register">
				<thead>
					<tr>
						<th>SL No.</th>
						<th>Applicant ID</th>
						<th>Applicant Name</th>
						<th>Program</th>
						<th>Trimester</th>
						<th>Academic Year</th>
						<th>Details</th>
						<th>Remarks</th>

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
							<button data-toggle="modal" data-target="#deatailModal" class="btn btn-success view_details" data-id="{{$applicant->applicant_serial_no}}" data-toggle1="tooltip" title="View Admission Paid Applicants Detail" >View</button>
						</td>

						@if($applicant->applicant_eligiblity==5)
						<td >Admitted Student</td>
						@endif

					</tr>

					@endforeach
					@else
					<!-- empty message -->
					<tr>
						<td colspan="11">
							<div class="alert alert-success">
								<center><h3 style="font-style:italic">No Data Found !</h3></center>
							</div>
						</td>
					</tr>

					@endif
				</tbody>
			</table>
			{{isset($pagination) ? $pagination:""}}
			<input type="hidden" class="site_url" value="{{url('/')}}">
			<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
		</div>
	</div>
</div>

<!-- Modal -->
<div id="deatailModal" class="modal fade bs-example-modal-lg" rtabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Applicant Profile</h4>
			</div>
			<div class="modal-body details_view">
				<!-- dynamic content-->
				<div class="ajax_loader loading_icon"></div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div><!-- /Modal content-->

	</div>
</div><!-- /Modal -->

@stop