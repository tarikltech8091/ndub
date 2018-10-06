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
				<div class="form-group col-md-4">
					<label >Employee Department</label>
					<select class="form-control employee_department" name="employee_department">
						<option value="0">All</option>
						<option {{isset($_GET['employee_department']) && ($_GET['employee_department']=='accounts') ? 'selected' : ''}} value="accounts">Account Office</option>
						<option {{isset($_GET['employee_department']) && ($_GET['employee_department']=='register') ? 'selected' : ''}} value="register">Register Office</option>
						<option {{isset($_GET['employee_department']) && ($_GET['employee_department']=='department') ? 'selected' : ''}} value="department">Department Office</option>
						<option {{isset($_GET['employee_department']) && ($_GET['employee_department']=='stuff') ? 'selected' : ''}} value="stuff">Stuff</option>
					</select>

				</div>

				<div class="form-group col-md-1" style="margin-top:20px;">
					<button class="btn btn-danger employee_search" data-toggle="tooltip" title="Search Employee List">Search</button>
				</div>
				<div class="col-md-1 margin_top_20">
					<span class="btn btn-warning register_employee_list_print" data-toggle="tooltip" title="Download Employee List"><i class="fa fa-print"></i></span>
				</div>

			</div>
		</div><!--/header inline form-->
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Employee List</div>
				<div class="panel-body"><!--info body-->

					@if(!empty($employee_list))
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Employee ID</th>
								<th>Employee Name</th>
								<th>Employee Department</th>
								<th>Designation</th>
								<th>Joining Date</th>
								<th>Mobile</th>
								<th>Email</th>
							</tr>
						</thead>
						<tbody>

							@foreach($employee_list as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->employee_id}}</td>
								<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
								<td>{{$list->pro_designation}}</td>
								<td>{{$list->employee_designation}}</td>
								<td>{{$list->employee_join_date}}</td>
								<td>{{$list->mobile}}</td>
								<td>{{$list->email}}</td>
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