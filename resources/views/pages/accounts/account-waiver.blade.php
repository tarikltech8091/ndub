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
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Accounts Waiver</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/accounts/waiver')}}" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Waiver Name</label>
						<input type="text" name="waiver_name" class="form-control" value="{{old('waiver_name')}}" placeholder="Ex:- Financial Waiver" />	
					</div>

					<div class="form-group">
						<label>Waiver Rate (%)</label>
						<input type="text" name="waiver_rate" class="form-control" value="{{old('waiver_rate')}}" placeholder="Ex:- 20"/>	
					</div>
					<div class="form-group">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="reset" class="btn btn-danger" value="Reset">
						<input type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>

			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Accounts Waiver List</div>
			<div class="panel-body">

				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Waiver Name</th>
							<th>Waiver Rate (%)</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($account_waiver_list) && count($account_waiver_list) > 0)
						@foreach($account_waiver_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->waiver_name}}</td>
							<td>{{$list->waiver_rate}}</td>
							<td>
								<a href="{{URL::route('Edit Account Waiver Page',$list->waiver_name_slug)}}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Waiver"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a data-confirm-url="{{URL::route('Accounts Waiver Delete',$list->waiver_name_slug)}}" class="btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Waiver"><i class="fa  fa-trash-o"></i></a>
							</td>
						</tr>
						@endforeach
						@else
						<tr class="text-center">
							<td colspan="3">No Data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($waiver_pagination) ? $waiver_pagination:""}}
			</div>
		</div>
	</div>

</div>

@stop