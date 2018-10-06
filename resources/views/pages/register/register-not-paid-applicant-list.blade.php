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
			<form method="get" action="{{url('/register/not-paid/applicant')}}">
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
				<div class="form-group col-md-4">
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

					<input type="text" class="form-control academic_year" name="academic_year" value="{{isset($_GET['academic_year']) ? $_GET['academic_year'] : ''}}" placeholder="year">
				</div>

				
				<div class="form-group col-md-1" style="margin-top:20px;">
					<button class="btn btn-danger applicant_total_amount_search"  data-toggle="tooltip" title="Search Applicants By Program,Trimester,Year">Search</button>
				</div>
			</form>

		</div><!--/header inline form-->
	</div>
	
	<div class="col-md-12">
		<div class="panel panel-body">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>SL</th>
						<th>Applicant ID</th>
						<th>Name</th>
						<th>Mobile</th>
						<th>Email</th>
						<th>Program</th>
						<th>Trimester</th>
						<th>Year</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($applicant_list))
					<?php
					$total_amount=0;
					$total_applicant=0;
					?>
					@foreach($applicant_list as $key => $list)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$list->applicant_serial_no}}</td>
						<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
						<td>{{$list->mobile}}</td>
						<td>{{$list->email}}</td>
						<td>{{$list->program_code}}</td>
						<td>{{$list->semester_title}}</td>
						<td>{{$list->academic_year}}</td>
						<td><a data-confirm-url="{{url('/register/not-paid/applicant/no-'.$list->applicant_serial_no)}}" class="btn btn-danger btn-xs confirm_box" data-toggle="tooltip" title="Delete Applicant"><i class="fa fa-trash-o"></i></a></td>

					</tr>
					@endforeach
					
					@else
					<tr>
						<td colspan="9">
							<div class="alert alert-success">
								<center>No Data Available !</center>
							</div>
						</td>
					</tr>
					@endif
				</tbody>	
			</table>

		</div>

	</div>


</div>

@stop