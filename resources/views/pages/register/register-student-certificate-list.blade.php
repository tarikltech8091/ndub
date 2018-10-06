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
		<div class="panel panel-body padding_0">
			<div class=" sorting_form"><!--header inline form-->
				<form method="get" action="{{url('/register/student/certificate/list')}}" enctype="multipart/form-data">
					<?php 
					$program_list =\App\Applicant::ProgramList();

					?>
					<div class="form-group col-md-6">
						<label for="Program">Program</label>
						<select class="form-control program select_program" name="program" >
							<option value="">Select Program</option>
							@if(!empty($program_list))
							@foreach($program_list as $key => $list)
							<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<input type="hidden" class="batch_no" value="{{isset($_GET['batch_no'])?$_GET['batch_no']:''}}">

					<div class="form-group col-md-4">
						<label for="AcademicYear">Batch</label>
						<select class="form-control  get_batch" name="batch_no" >
							<option value="{{isset($_GET['batch_no'])?$_GET['batch_no']:0}}">{{isset($_GET['batch_no'])?$_GET['batch_no']:'Select Student'}}</option>
						</select>
					</div>

					<div class="form-group col-md-1" style="margin-top:20px;">
						<button class="btn btn-danger total_registered_student_search" data-toggle="tooltip" title="Search Students">Search</button>
					</div>
				</form>


			</div>
		</div><!--/header inline form-->
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Sutdent List</div>
				<div class="panel-body"><!--info body-->

					@if(!empty($student_list))
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Student ID</th>
								<th>Student Name</th>
								<th>Program</th>
								<th>Batch</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Gurdian Name</th>
								<th>Gurdian Mobile</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>

							@foreach($student_list as $key => $list)
							<tr>
								<?php
									$student_list = \DB::table('student_gurdians')
									            ->where('student_gurdians.relation','Local_Guardian')
									            ->where('student_gurdians.student_tran_code',$list->student_tran_code)
									            ->first();
								?>
								<td>{{$key+1}}</td>
								<td>{{$list->student_serial_no}}</td>
								<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
								<td>{{$list->program_code}}</td>
								<td>{{$list->batch_no}}</td>
								<td>{{$list->mobile}}</td>
								<td>{{$list->email}}</td>
								<td>{{isset($student_list->gurdian_name)?$student_list->gurdian_name :''}}</td>
								<td>{{isset($student_list->mobile)?$student_list->mobile :''}}</td>
								<td>
									@if(($list->certificate_status) == '1')
									<a class="btn btn-primary loadingButton confirm_box"  data-loading-text="Saving..." autocomplete="off" data-toggle="tooltip" title="Certified Cancel" data-confirm-url="{{url('/register/certificate/student/'.$list->student_serial_no.'/0')}}">Certified</a>

									@else
									<a class="btn btn-warning  loadingButton confirm_box" data-loading-text="Saving..." autocomplete="off" data-toggle="tooltip" title="Certified Student" data-confirm-url="{{url('/register/certificate/student/'.$list->student_serial_no.'/1')}}">Confirm</a>
									@endif
								</td>
							</tr>
							@endforeach

						</tbody>
					</table>
					@else
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Found !</h3></center>
					</div>
					@endif
				</div><!--/info body-->
			</div>
		</div>
	</div>
</div>

<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
<input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">


@stop