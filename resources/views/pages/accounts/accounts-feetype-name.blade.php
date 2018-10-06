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
			<div class="panel-heading">Accounts fee types Add</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/accounts/fee-types')}}" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Fee Types</label>
						<input type="text" name="fee_types_name" class="form-control" value="{{old('fee_types_name')}}" />	
					</div>
					<div class="form-group">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="reset" class="btn btn-default" value="Reset">
						<input type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>

			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Accounts Fee Types</div>
			<div class="panel-body">

				<label>Fee Lists</label>
				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Accounts Fee</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($account_fee_list))
						@foreach($account_fee_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->fee_types_name}}</td>
							<td>
								<a href="{{URL::route('Edit Accounts Fee Types',$list->fee_types_name_slug)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a href="{{URL::route('Accounts Fee Types Delete',$list->fee_types_name_slug)}}"><i class="fa  fa-trash-o"></i></a>
							</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
				{{isset($fee_pagination) ? $fee_pagination:""}}
			</div>
		</div>
	</div>

</div>

@stop