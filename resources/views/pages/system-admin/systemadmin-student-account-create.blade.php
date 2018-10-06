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
	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Student Account Creation</div>

			<div class="panel-body">

				<div class="col-md-12 panel panel-body search_panel_bg_color form-inline">
					<form action="{{url('/systemadmin/student-account')}}"  method="get">

						<div class="form-group col-md-12">
							<input type="text" name="student_serial_no" value="{{isset($_GET['student_serial_no']) ? $_GET['student_serial_no'] : ''}}" class="form-control search_width" placeholder="Search Student ID...">

							<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Search Student">Search !</button>
						</div>
					</form>
				</div>


				
				@if(isset($_GET['student_serial_no']))
				<!-- <div class="page_row" style="margin-top:30px;"> -->
				@if(!empty($student_info->student_status))
				<div class="col-md-12 alert alert-warning" style="margin-top:30px;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<h4 class="text-center">Student Information</h4>
					<hr>
					<div class="col-md-3 ">
						<div class="profile_image_show">
							<img src="{{asset($student_info->student_image_url)}}">
						</div>
					</div>

					<div class="col-md-9 profile_info">
						<table class="table">
							<tbody>
								<tr >
									<th>Name</th>
									<th>{{$student_info->first_name}} {{$student_info->middle_name}} {{$student_info->last_name}}</th>
									<th><button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#studentRegisterModal" style="padding:0" data-toggle1="tooltip" title="For Online ID and Password">Register Now !</button></th>
								</tr>
								<tr>
									<th>ID</th>
									<th colspan="2">{{$student_info->student_serial_no}}</th>
								</tr>
								<tr>
									<th>Program</th>
									<td colspan="2">{{$student_info->program_title}}</td>
								</tr>
								<tr>
									<th>Department</th>
									<td colspan="2">{{$student_info->department_title}}</td>
								</tr>
								<tr>
									<th>Batch</th>
									<td colspan="2">{{$student_info->semester_title}} {{$student_info->academic_year}}</td>
								</tr>
								<tr>
									<th>Mobile</th>
									<td colspan="2">{{$student_info->mobile}}</td>
								</tr>
								<tr>
									<th>Email</th>
									<td colspan="2">{{$student_info->email}}</td>
								</tr>

							</tbody>
						</table>
					</div>

				</div>



				<!--Student Registration Modal -->
				<div id="studentRegisterModal" class="modal fade" rtabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4>Student Registration</h4>
							</div>
							<div class="modal-body">
								<label>Name: <span style="color:#5cb85c;">{{$student_info->first_name}} {{$student_info->middle_name}} {{$student_info->last_name}}</span></label>

								<label>Program: <span style="color:#5cb85c;">{{$student_info->program_title}}</span></label>


								<form action="{{url('/systemadmin/student-account-submit')}}" method="post" enctype="multipart/form-data">
									<div class="form-group"><br>
										<input type="text" name="password" class="form-control" placeholder="Enter Password">
									</div>
									<center>
										<div class="form-group">
											<input type="hidden" name="student_serial_no" value="{{$student_info->student_serial_no}}" >
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<input type="submit" class="btn btn-primary col-md-12" value="Register"><br>
										</div>
									</center>
								</form>

							</div>
							<div class="modal-footer"></div>
						</div><!-- /Modal content-->
					</div>
				</div><!-- /Student Registration Modal -->
				@else
				<div class="col-md-12 alert alert-success" style="margin-top:30px;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<center><h3 style="font-style:italic">Student Already registered !</h3></center>
				</div>
				@endif
				
				@endif
				<!-- </div> -->

				
			</div>

		</div>
	</div>



	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">Waiting For Student User Registration</div>
			<div class="panel-body">

				@if(!empty($student_list) && count($student_list)>0)
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Student ID</th>
							<th>Student Name</th>
							<th>Program</th>
						</tr>
					</thead>
					<tbody>

						@foreach($student_list as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->student_serial_no}}</td>
							<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
							<td>{{$list->program_title}}</td>
						</tr>
						@endforeach

					</tbody>
				</table>
				@else
				<!-- empty message -->
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Data Available !</h3></center>
				</div>
				@endif
			</div>
			{{isset($student_list_pagination)? $student_list_pagination :''}}
		</div>
	</div>

</div>



@stop