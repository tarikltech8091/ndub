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

		<form  action="{{url('/system-admin/system-users')}}" method="get">
			<div class="col-md-12 panel panel-body search_panel_bg_color">
				<div class="form-group col-md-6">
					@if(isset($_GET['search_id']))
					<input type="text" class="form-control search_width" name="search_id" value="{{$_GET['search_id']}}">
					@else
					<input type="text" class="form-control search_width" name="search_id" value="{{old('search_id')}}" placeholder="Search for...">
					@endif

					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="System User Search">Search !</button>

				</div>

			</div>
		</form>
	</div>



</div>

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">System User List</div>
			<div class="panel-body">

				@if(!empty($users) && count($users)>1)
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Name</th>
							<th>User ID</th>
							<th>User Type</th>
							<th>Login Status</th>
							<th>User Status</th>
						</tr>
					</thead>
					<tbody>
						
						@foreach($users as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->name}}</td>
							<td>{{$list->user_id}}</td>
							<td>{{$list->user_type}}</td>
							<td>{{(isset($list->login_status) && ($list->login_status=='1')) ? 'LoggedIn' : 'Not LoggedIn'}}</td>
							@if($list->status == 1)
							<td>
								<a href="{{url('/system/user/change/id-'.$list->user_id.'/status--1')}}" class="btn btn-success"> Active</a></td>
							@else
							<td><a href="{{url('/system/user/change/id-'.$list->user_id.'/status-1')}}" class="btn btn-danger"> Block</a></td>

							@endif
						</tr>
						@endforeach
						
					</tbody>
				</table>
				@elseif(!empty($users) && count($users)==1)
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Name</th>
							<th>User ID</th>
							<th>User Type</th>
							<th>Login Status</th>
							<th>User Status</th>
						</tr>
					</thead>
					<tbody>
						
						<tr>
							<td>#</td>
							<td>{{$users->name}}</td>
							<td>{{$users->user_id}}</td>
							<td>{{$users->user_type}}</td>
							<td>{{(isset($users->login_status) && ($users->login_status=='1')) ? 'LoggedIn' : 'Not LoggedIn'}}</td>
							@if($users->status == 1)
							<td>
								<a href="{{url('/system/user/change/id-'.$users->user_id.'/status--1')}}" class="btn btn-success"> Active</a></td>
							@else
							<td><a href="{{url('/system/user/change/id-'.$users->user_id.'/status-1')}}" class="btn btn-danger"> Block</a></td>

							@endif
						</tr>
						
					</tbody>
				</table>
				@else
				<!-- empty message -->
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Data Available !</h3></center>
				</div>
				@endif
				{{isset($users_pagination)?$users_pagination:''}}
			</div>
		</div>
	</div>
</div>

@stop