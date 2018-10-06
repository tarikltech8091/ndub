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
			<div class="sorting_form"><!--header inline form-->
				<?php 
				$department_list =\DB::table('univ_department')->get();

				?>
				<div class="form-group col-md-4">
					<label >Department</label>
					<select class="form-control department" name="department" >
						<option value="0">All</option>
						@if(!empty($department_list))
						@foreach($department_list as $key => $list)
						<option {{(isset($_GET['department']) && ($list->department_no==$_GET['department'])) ? 'selected':''}} value="{{$list->department_no}}">{{$list->department_title}}</option>
						@endforeach
						@endif
					</select>
				</div>

				<div class="form-group col-md-4">
					<?php 
					$program_list =\DB::table('univ_program')->get();

					?>
					<label for="Program">Program</label>
					<select class="form-control program" name="program" >
						<option value="0">All</option>
						@if(!empty($program_list))
						@foreach($program_list as $key => $list)
						<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
						@endforeach
						@endif
					</select>
				</div>


				<div class="form-group col-md-1 margin_top_20">
					<button class="btn btn-danger total_faculty_search" data-toggle="tooltip" title="Search Faculty List">Search</button>
				</div>
				<div class="col-md-1 margin_top_20">
					<span class="btn btn-warning register_faculty_list_print" data-toggle="tooltip" title="Download Faculty List"><i class="fa fa-print"></i></span>
				</div>
			</div>
		</div><!--/header inline form-->
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Faculty List</div>
				<div class="panel-body"><!--info body-->

					@if(!empty($faculty_list))
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Faculty ID</th>
								<th>Faculty Name</th>
								<th>Department</th>
								<th>Program</th>
								<th>Joining Date</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>

							@foreach($faculty_list as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->faculty_id}}</td>
								<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
								<td>{{$list->department_title}}</td>
								<td>{{$list->program_title}}</td>
								<td>{{$list->faculty_join_date}}</td>
								<td>{{$list->mobile}}</td>
								<td>{{$list->email}}</td>
								@if(($list->faculty_status) == 1)
								<td>Waiting</td>
								@elseif(($list->faculty_status) == 2)
								<td>Active</td>
								@elseif(($list->faculty_status) == -5)
								<td>Block</td>
								@else
								<td></td>
								@endif
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