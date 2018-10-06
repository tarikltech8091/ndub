@extends('application.layout.master')
@section('content')

<div class="row">
	<div class="page-header" >
		<center class="header-name">Admission Result</center>
	</div>
</div>
<div class="row">
	<div class="col-md-12 form-inline">
		<div class="col-md-12 panel panel-body search_panel_bg_color">
			<center>
				<input type="text" class="form-control search_width" id="applicant_serial_no" placeholder="Applicant Serial">
				<button type="button" class="btn btn-default applicant_resultsearch_btn">Search !</button>
			</center>
		</div>
		<input type="hidden" class="site_url" value="{{url('/')}}">
	</div>
</div>
<div class="applicant_search_result">
	<!--dynamic content goes here-->
</div>

@stop