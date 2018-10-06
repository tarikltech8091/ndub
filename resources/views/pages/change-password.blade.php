
@extends('layout.master')
@section('content')
<!-- @include('layout.bradecrumb') -->

<style type="text/css">
	.int_pass{
		height: 40px;
		border-radius: 0;
	}


</style>

<div class="col-md-5 col-md-offset-3" style="margin-top:100px">
	<div class="panel panel-info">
		<div class="panel-heading text-center">Change Your Password !</div>
		<div class="panel-body">
			<div class="col-md-12">
				@if($errors->count() > 0 )
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
			<form action="{{url('/update-password/'.\Auth::user()->user_type.'/'.\Auth::user()->user_id)}}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="col-md-12 form-group">
					<label>Current Password</label>
					<input type="password" name="current_password" class="form-control int_pass" placeholder="Current Password">
				</div>
				<div class="col-md-6 form-group">
					<label>New Password</label>
					<input type="password" name="new_password" class="form-control int_pass" placeholder="New Password">
				</div>
				<div class="col-md-6 form-group">
					<label>Confirm Password</label>
					<input type="password" name="confirm_password" class="form-control int_pass" placeholder="Confirm Password">
				</div>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary col-md-12 int_pass">Change Password</button>
				</div>
			</form>
		</div>
	</div>
</div>


@stop