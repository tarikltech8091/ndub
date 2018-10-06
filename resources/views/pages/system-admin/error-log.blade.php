@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-default">

			<form action="{{url('/system-admin/error-logs')}}" class="form-inline errorlog_date" role="search" method="get">
				<div class="form-group col-md-3">
					<label>From</label>
					<div class="input-group date form_date_search col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date('Y-m-d')}}" name="form_search_date" placeholder="From">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<div class="form-group col-md-3"> 
					<label>To</label>
					<div class="input-group date to_date_search col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date('Y-m-d')}}" name="to_search_date" placeholder="To">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
				
				<button type="submit" class="btn btn-primary errorlog_search_btn" data-toggle="tooltip" title="Search Log By Date To Date">Search</button>
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
							<th>Page URL</th>
							<th>Error Data</th>
						</tr>
					</thead>

					<tbody>
						
						@if(count($error_log_list) > 0)
						@foreach($error_log_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->created_at}}</td>
							<td>{{$list->error_client_ip}}</td>
							<td>{{$list->error_user_id =='guest' ? 'Guest' : $list->name}}</td>
							<td>{{$list->error_request_url}}</td>
							<td>{{$list->error_data}}</td>

						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6">No data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($error_pagination) ? $error_pagination:""}}
			</div>
		</div>
	</div>
</div>

@stop