@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<form action="{{url('/system-admin/event-logs')}}" class="form-inline eventlog_date" role="search" method="get">
				<div class="form-group col-md-3">
					<label>From</label>
					<div class="input-group date from_date_search_event col-md-5" data-date="" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date('Y-m-d')}}" name="form_search_date" placeholder="From">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<div class="form-group col-md-3">
					<label>To</label>
					<div class="input-group date to_date_search_event col-md-5" data-date-format="yyyy-mm-dd">
						<input class="form-control" size="16" type="text" value="{{isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date('Y-m-d')}}" name="to_search_date" placeholder="To">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>

				<button type="submit" class="btn btn-primary eventlog_search_btn" data-toggle="tooltip" title="Search Log By Date To Date">Search</button>
			</form>
		</div>
	</div>
</div>	


<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-body">

			<div class="col-md-12 ">
				<table class="table table-hover table-bordered table-striped">
					<thead>
						<tr>
							<th>SL</th>
							<th>Date & Time</th>
							<th>Client IP</th>
							<th>USER</th>
							<th>Page URL</th>
							<th>Event Type</th>
							<th>Event Data</th>
						</tr>
					</thead>

					<tbody>
						@if(count($event_log_list) > 0)
						@foreach($event_log_list as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->created_at}}</td>
							<td>{{$list->event_client_ip}}</td>
							<td>{{$list->event_user_id =='guest' ? 'Guest' : $list->name}}</td>
							<td>{{$list->event_request_url}}</td>
							<td>{{$list->event_type}}</td>
							<td>{{wordwrap($list->event_data, 10, '\n', true)}}</td>	
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7">No data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($event_pagination) ? $event_pagination:""}}
			</div>
		</div>
	</div>
</div>

@stop