@extends('layout.master')
@section('content')
@include('layout.bradecrumb')
	<div class="row page_row">
		<div class="col-md-12">
		<div class="panel panel-default ">
			<form action="{{url('/system-admin/access-logs')}}" class="form-inline accesslog_date" role="search" method="get">
				<div class="form-group col-md-4">
					<label>From</label>
	        		<div class="input-group date from_date_search_access col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date('Y-m-d')}}" name="form_search_date" placeholder="From">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<div class="form-group col-md-4">
					<label>To</label>
	        		<div class="input-group date to_date_search_access col-md-5" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date('Y-m-d')}}" name="to_search_date" placeholder="To">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
				 
				<button type="submit" class="btn btn-primary accesslog_search_btn" data-toggle="tooltip" title="Search Log By Date To Date">Search</button>
			</form>
		</div>
	</div>
		<div class="col-md-12">
			<div class="panel panel-info">
			  <div class="panel-body"><!--info body-->

			  	
					<table class="table table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>SL</th>
								<th>Date & Time</th>
								<th>Client IP</th>
								<th>User</th>
								<th>Browser</th>
								<th>Platform</th>
								<th>City</th>
								<th>Country</th>
								<th>Visited</th>
							</tr>
						</thead>

						<tbody>
							@if(!empty($access_log_list) && count($access_log_list)> 0)
								@foreach($access_log_list as $key => $list)
								<tr >
									<td>{{$key+1}}</td>
									<td>{{$list->created_at}}</td>
									<td>{{$list->access_client_ip}}</td>
									<td>{{$list->access_user_id =='guest' ? 'Guest' : $list->name}}</td>
									<td>{{$list->access_browser}}</td>
									<td>{{$list->access_platform}}</td>
									<td>{{$list->access_city}}</td>
									<td>{{$list->access_country}}</td>
									<td>{{$list->access_message}}</td>
								</tr>
								@endforeach
							@else
								<tr>
									<td colspan="9">No data available</td>
								</tr>
							@endif
						</tbody>
					</table>
					{{isset($pagination) ? $pagination:""}}

			  </div><!--/info body-->
			</div>
		</div>
		
	</div>

@stop