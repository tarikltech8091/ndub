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

	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Employee Account Creation</div>
			<div class="panel-body">

				<div class="panel panel-body search_panel_bg_color form-inline">
					<form action="{{url('/system-admin/employee-account')}}" method="get">

						<div class="form-group col-md-12">
							<input type="text" name="employee_id" value="{{isset($_GET['employee_id']) ? $_GET['employee_id'] : ''}}" class="form-control search_width" placeholder="Search Employee ID...">

							<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Search Employee">Search !</button>
						</div>

					</form>
				</div>
				


				
				@if(isset($_GET['employee_id']))
				@if(!empty($employee_info->employee_status))
				<div class="col-md-12 alert alert-warning" style="margin-top:30px;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<h4 class="text-center">Employee Information</h4>
					<hr>
					<div class="col-md-3 ">
						<div class="profile_image_show">
							<img src="{{asset($employee_info->employee_image_url)}}">
						</div>
					</div>

					<div class="col-md-9 profile_info">
						<table class="table">
							<tbody>
								<tr >
									<th>Name</th>
									<th>{{$employee_info->first_name}} {{$employee_info->middle_name}} {{$employee_info->last_name}}</th>
									<th><button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#employeeRegisterModal" style="padding:0" data-toggle1="tooltip" title="For Online ID and Password">Register Now !</button></th>
								</tr>
								<tr>
									<th>ID</th>
									<th colspan="2">{{$employee_info->employee_id}}</th>
								</tr>
								<tr>
									<th>Gender</th>
									<td colspan="2">{{$employee_info->gender}}</td>
								</tr>
								<tr>
									<th>Join Date</th>
									<td colspan="2">{{$employee_info->employee_join_date}}</td>
								</tr>
								<tr>
									<th>Mobile</th>
									<td colspan="2">{{$employee_info->mobile}}</td>
								</tr>
								<tr>
									<th>Email</th>
									<td colspan="2">{{$employee_info->email}}</td>
								</tr>

							</tbody>
						</table>
					</div>

				</div>



				<!--Employee Registration Modal -->
				<div id="employeeRegisterModal" class="modal fade" rtabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4>Employee Registration</h4>
							</div>
							<div class="modal-body">
								<label>Name: <span style="color:#5cb85c;">{{$employee_info->first_name}} {{$employee_info->middle_name}} {{$employee_info->last_name}}</span></label>


								<form action="{{url('/system-admin/employee-account-submit')}}" method="post" enctype="multipart/form-data">

									<div class="form-group">
										<label>User Type</label>
										<select class="form-control" name="user_type">
											<option value="">--Select Type--</option>
											<option value="accounts">Account Office</option>
											<option value="register">Register Office</option>
											<option value="systemadmin">System Admin</option>
											<option value="academic">Academic Administration</option>
										</select>
									</div>

									<div class="form-group">
										<label>User Role</label>
										<select class="form-control" name="user_role">
											<option value="">--Select Role--</option>
											<option value="head">Head</option>
											<option value="stuff">Staff</option>
										</select>
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control" placeholder="Enter Password">
									</div>
									<center>
										<div class="form-group">
											<input type="hidden" name="employee_id" value="{{$employee_info->employee_id}}" >
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<input type="submit" class="btn btn-primary col-md-12" value="Register"><br>
										</div>
									</center>
								</form>

							</div>
							<div class="modal-footer"></div>
						</div><!-- /Modal content-->
					</div>
				</div><!-- /Employee Registration Modal -->
				@else
				<div class="col-md-12 alert alert-success" style="margin-top:30px;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<center><h3 style="font-style:italic">Employee Not Found !</h3></center>
				</div>
				@endif
				
				@endif
				<!-- </div> -->

				
			</div>

		</div>
	</div>


	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">Waiting For Employee User Registration</div>
			<div class="panel-body">

				@if(!empty($employee_list))
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Employee ID</th>
							<th>Employee Name</th>
							<th>Department</th>
							<th>Mobile</th>
						</tr>
					</thead>
					<tbody>

						@foreach($employee_list as $key => $list)
						<tr>

							<td>{{$key+1}}</td>
							<td>{{$list->employee_id}}</td>
							<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
							<td>{{strtoupper($list->pro_designation)}}</td>
							<td>{{$list->mobile}}</td>
							
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
		</div>
	</div>

</div>



@stop