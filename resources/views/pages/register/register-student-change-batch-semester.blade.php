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
	<div class="col-md-12 form-inline">
		<div class="col-md-12 panel panel-body search_panel_bg_color">
			<form method="get" action="{{url('/register/student/batch-semester/change')}}" enctype="multipart/form-data">
				<div class="form-group col-md-6">
					<input  class="form-control search_width" type="text" name="student_no" value="{{!empty($_GET['student_no'])? $_GET['student_no'] : old('student_no')}}" placeholder="Search Student...">

					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Search Student">Search !</button>

				</div>

			</form>
		</div>
	</div>
</div>
@if(isset($_GET['student_no']))
@if(!empty($_GET['student_no']))
<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-body padding_0">

				<br><h2 align="center"><strong>Student Change Info</strong></h2><br>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Student ID</th>
							<th>Program</th>
							<th>Admission Year</th>
							<th>Admission Semester</th>
							<th>Batch</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						@if(!empty($select_student))
						<form action="{{url('/register/student/batch-semester/change/confirm')}}" method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{csrf_token()}}">

							<tr>
								<td>{{$select_student->student_serial_no}}</td>
								<td>{{$select_student->program_title}}</td>
								<td><input type="text" class="form-control" name="change_year" value="{{$select_student->academic_year}}"/></td>
								<td>
									<?php
									$semester_list=\DB::table('univ_semester')->get();
									?>
									<select class="form-control" name="change_semester" >
										@if(!empty($semester_list))
										@foreach($semester_list as $key => $list)
										<option {{(isset($select_student->semester) && ($select_student->semester==$list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
										@endforeach
										@endif
									</select>

								</td>
								<td><input type="text" class="form-control" name="change_batch" value="{{$select_student->batch_no}}"/>
									<input type="hidden" name="student_no" value="{{$select_student->student_serial_no}}"/>
								</td>
								<td><button type="submit" class="btn btn-primary btn-sm">Submit</button></td>

							</tr>
						</form>

						@else
						<tr>
							<td colspan="5">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>
							</td>
						</tr>
						@endif


					</tbody>
				</table>
		</div>

	</div>
</div>
@else
<div class="page_row row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<center><h3 style="font-style:italic">No Data Found !</h3></center>
		</div>
	</div>
</div>
@endif
@endif

@stop