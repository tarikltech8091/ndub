@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<form action="{{url('/system-admin/auth-logs')}}" class="form-inline authlog_date" role="search" method="get">
				<div class="form-group col-md-3">
					<label>From</label>
					<div class="input-group date from_date_search_auth col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date('Y-m-d')}}" name="form_search_date" placeholder="From">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<div class="form-group col-md-3"> 
					<label>To</label>
					<div class="input-group date to_date_search_auth col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date('Y-m-d')}}" name="to_search_date" placeholder="To">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
				
				<button type="submit" class="btn btn-primary authlog_search_btn" data-toggle="tooltip" title="Search Log By Date To Date">Search</button>
			</form>
		</div>
	</div>	
</div>

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-body">
			<div class="col-md-12">
				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Date & Time</th>
							<th>Client IP</th>
							<th>USER</th>
							<th>User Type</th>
							<th>Browser</th>
							<th>Platform</th>
							<th>City</th>
							<th>Country</th>
						</tr>
					</thead>

					<tbody>

						@if(count($auth_log_list) > 0)
						@foreach($auth_log_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->created_at}}</td>
							<td>{{$list->auth_client_ip}}</td>
							<td>{{$list->auth_user_id =='guest' ? 'Guest' : $list->name}}</td>
							<td>{{$list->auth_type}}</td>
							<td>{{$list->auth_browser}}</td>
							<td>{{$list->auth_platform}}</td>
							<td>{{$list->auth_city}}</td>
							<td>{{$list->auth_country}}</td>								
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="8">No data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($auth_pagination) ? $auth_pagination:""}}
			</div>
		</div>
	</div>
</div>

@stop