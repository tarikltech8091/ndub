@extends('layout.master')
@section('content')
@include('layout.bradecrumb')



<div class="row page_row">
	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Sutdent Account Creation</div>

			<div class="panel-body">
				@if(Session::has('message'))
				<div class="row page_row">
				<div class="col-md-12 text-center">
						<div class="alert alert-warning alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							{{ Session::get('message') }}
						</div>
					</div>
				</div>
				@endif
				<div class="col-md-12 form-inline">
					<div class="form-group col-md-12 panel panel-body padding_0">
						<form action="{{URL::route('Student Account Search')}}" class="navbar-form" role="search" method="get">
							@if(isset($_GET['user_id']))
							<div class="form-group">
								<input type="text" name="user_id" value="{{$_GET['user_id']}}" class="form-control" placeholder="Search">
							</div>
							@else
							<div class="form-group">
								<input type="text" name="user_id" value="{{old('user_id')}}" class="form-control" placeholder="Search">
							</div>
							@endif
							<button type="submit" class="btn btn-primary">Search</button>
						</form>
					</div>
				</div>
			</div>

			<div class="panel-body">
				@if(isset($_GET['user_id']))
				@if(isset($student_status))
				<div class="page_row">
					<div class="col-md-12">
						<div class="alert alert-success">
							<h3>Student Already Registered!</h3><br>
							<h4>Student ID : {{$student_status->user_id}}</h4>
							<h4>Name : {{$student_status->name}}</h4>
						</div>
					</div>
				</div>

				@elseif($_GET['user_id']==0)
				<div class="row page_row">
					<div class="col-md-6 text-center">
						<div class="alert alert-info">Please Enter An ID</div>
					</div>
				</div>
				@else
				<div class="row page_row">
					@if($errors->count() > 0 )
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h6>The following errors have occurred:</h6>
						<ul>
							@foreach( $errors->all() as $message )
							<li>{{ $message }}</li>
							@endforeach
						</ul>
					</div>
					@endif

					<div class="col-md-12 alert alert-success page_row">
						<div class="row">
							<div class="page-header" >
								<center class="header-name">Student Account Creation</center>
							</div>
						</div>
						<div class="col-md-2 photo-div">
							<center>
								<img src="{{asset($student_info->student_image_url)}}" class="image" title="{{asset($student_info->middle_name)}}" alt="{{asset($student_info->student_serial_no)}}" />
							</center>
						</div>
						<div class="col-md-6">
							<h3>Student Not Registered!</h3><br>
							<h4>Student ID : {{$student_info->student_serial_no}}</h4>
							<h4>Name : {{$student_info->first_name}} {{$student_info->middle_name}} {{$student_info->last_name}}</h4>
							<button class="btn btn-primary" data-id="{{$_GET['user_id']}}" data-toggle="modal" data-target="#studentRegisterModal" data-type="degree">Register Now !</button>
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
										<?php
										$program_list=\App\Academic::ProgramList();
										?>
										@foreach($program_list as $key => $program_list)
										@if($program_list->program_id==$student_info->program)
										<label>Program: <span style="color:#5cb85c;">{{$program_list->program_title}}</span></label>
										@endif
										@endforeach


										<form action="{{URL::route('Student Registration')}}" method="post" enctype="multipart/form-data">
											<div class="form-group"><br>
												<input type="text" name="password" class="form-control" placeholder="Enter Password">
											</div>
											<center>
												<div class="form-group">
													<input type="hidden" name="user_id" value="{{$_GET['user_id']}}" >
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
					</div>
				</div> <!-- /Main Row -->

				@endif
				@endif
			</div>


		</div>
	</div>

	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">Student List</div>
			<div class="panel-body">

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
					@if(!empty($student_list))
					@foreach($student_list as $key => $list)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$list->student_serial_no}}</td>
						<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
						<td>{{$list->program_title}}</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>

			</div>
		</div>
	</div>

</div>




@stop