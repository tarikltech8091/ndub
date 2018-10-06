@extends('layout.master')
@section('content')
<style type="text/css">
	.remember_me{
		border-radius: 3px;
		box-shadow: 0 0 4px 1px rgba(0, 0, 0, 0.08);
		background: #f9f9f9 none repeat scroll 0 0;
		width:22%;
		height:74px;
		border: 1px solid #d3d3d3;
	}

</style>
<div class="sign-in-form"><!--login form-->
	<div class="sign-in-form-top login_form">
		<!-- <p><span>Sign In to</span> <a href="index.html">Admin</a></p> -->
		<img src="{{asset('images/banner.png')}}">
	</div>
	<div class="signin">
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
	  	<form action="{{url('/new/password')}}" method="post">
	  		<input type="hidden" name="user_id" value="{{$user_serial_no->user_id}}">
	  		<input type="hidden" name="token" value="{{$remember_token}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<div class="log-input">
				<div class="col-md-12">
					<input type="password" name="password" placeholder="*****" class="form-control lock">
				</div>
			</div>
			<div class="log-input">
				<div class="col-md-12">
					<input  type="password" name="confirm_password" placeholder="*****" class="form-control lock">
				</div>
			</div>
			<center>
				<div class="row">
					<input type="submit" value="PASSWORD SUBMIT"  class="btn btn-primary">
				</div>
			</center>
		</form>	
	</div>
</div><!--/login form-->

@stop