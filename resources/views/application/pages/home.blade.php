@extends('application.layout.master')
@section('content')
<!-- <div class="row"> -->

<div class="row">

	<div class="col-md-4">
		<div class="thumbnail thumb-border">
			<div onclick="location.href='http://www.ndub.edu.bd/';" class="icon-size">
				<center><i class="fa fa-university" aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='http://www.ndub.edu.bd/';" class="form_name">
				<center><h3>NDUB</h3></center>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="thumbnail thumb-border">
			<div onclick="location.href='{{url('/online-application')}}';" class="icon-size">
				<center><i class="fa fa-file-text-o" aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='{{url('/online-application')}}';" class="form_name">
				<center><h3>Online Application</h3></center>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="thumbnail thumb-border">
			<div onclick="location.href='{{url('/login')}}';" class="icon-size">
				<center><i class="fa fa-home"  aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='{{url('/login')}}';"  class="form_name">
				<center><h3>Dashboard</h3></center>
			</div>
		</div>	
	</div>
	</div>

<!-- </div> -->
@stop