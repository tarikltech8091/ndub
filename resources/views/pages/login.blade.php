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
	.remember_me :hover{
		background: yellow;
	}
	.banner_background{
		background-color: #2D343E;
	}
</style>
<div class="sign-in-form"><!--login form-->
	<div class="banner_background">
		<!-- <p><span>Sign In to</span> <a href="index.html">Admin</a></p> -->
		<img src="{{asset('images/banner.png')}}" style="width:85%">
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
		<form action="{{url('/login')}}" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<div class="log-input">
				<div class="col-md-12">
					<input type="text" name="user_id" onblur="if (this.value == '') {this.value = 'USER ID';}" onfocus="this.value = '';" value="{{old('user_id')}}" class="user" required>
				</div>
			</div>
			<div class="log-input">
				<div class="col-md-12">
					<input type="password" name="password" onblur="if (this.value == '') {this.value = 'USER ID';}" onfocus="this.value = '';" value="{{old('password')}}" class="lock" required >
				</div>
			</div>
			<!-- <div class="log-input">
				<div class="col-md-9"  style="margin-bottom:20px">
					{!! app('captcha')->display(); !!}

				</div>
				<div class="col-md-3 remember_me text-center">
					<a href="{{url('/forget/password')}}" style="width:100%;font-size:12px;">Forget Password ?</a>
				</div>
			</div> -->
			<center>
				<div class="row">
					<input type="submit" value="Login to your account">
				</div>
			</center>
			<div class="col-md-12">
				<a href="{{url('/forget/password')}}" style="font-size:12px;float:right">Forget Password ?</a>
			</div>
			
		</form>	 
	</div>
</div><!--/login form-->

@stop