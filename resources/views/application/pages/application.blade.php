@extends('application.layout.master')
@section('content')
<div class="row">
	<div class="col-md-4">
		
		<div class="thumbnail thumb-border">
			<div onclick="location.href='{{url('/online-application/form')}}';" class="icon-size">
				<center><i class="fa fa-pencil-square-o"  aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='{{url('/online-application/form')}}';"  class="form_name">
				<center><h3>Apply Online Now</h3></center>
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="thumbnail thumb-border">
			<div onclick="location.href='{{url('/online-application/applicant')}}';" class="icon-size">
				<center><i class="fa fa-file-text-o" aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='{{url('/online-application/applicant')}}';" class="form_name">
				<center><h3>Applied Forms</h3></center>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="thumbnail thumb-border">
			<div onclick="location.href='{{url('/online-application/applicant/admission-result')}}';" class="icon-size">
				<center><i class="fa fa-folder-open" aria-hidden="true"></i></center>
			</div>
			<div onclick="location.href='{{url('/online-application/applicant/admission-result')}}';" class="form_name">
				<center><h3>Admission Result</h3></center>
			</div>
		</div>
	</div>
</div>
@stop