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

	<div class="col-md-12 form-inline">
		<div class="col-md-12 panel panel-body search_panel_bg_color">
			<form action="{{url('/accounts/applicant/cash-payment')}}" method="get">
				<div class="form-group col-md-6">
					<input type="text" class="form-control search_width" name="applicant_serial_no"  placeholder="Applicant Serial ID" value="{{isset($_GET['applicant_serial_no']) ? $_GET['applicant_serial_no']:''}}">
					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Applicants Cash Payment">Search !</button>
				</div>
			</form>
		</div>
	</div>
	<input type="hidden" class="site_url" value="{{url('/')}}">
</div>
@if(!empty($applicant_info))
<div class="page_row row">
	<div class="col-md-12">
		<div class="panel panel-body">
			<div class="col-md-12 alert alert-info">
				<a href="{{url('/accounts/applicant/cash-payment')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<div class="row">
					<div class="page-header" >
						<center class="header-search">Search Result</center>
					</div>
				</div>
				<div class="row main-border">
					<div class="col-md-2 photo-div" style="border-right:1px solid #bdc3c7">
						<center>
							<img src="{{asset($applicant_info->app_image_url)}}" class="image" title="{{asset($applicant_info->middle_name)}}" alt="{{asset($applicant_info->applicant_serial_no)}}" />
						</center>
					</div>


					<div class="col-md-10 serial-gender-div">
						<div class="col-md-6">
							<span class="serial_input_label"  for="inputColor">Serial No : {{$applicant_info->applicant_serial_no}}</span>
						</div>
						<div class="col-md-6">
							<span class="serial_input_label">Gender : {{ucfirst($applicant_info->gender)}}</span>
						</div>

					</div>
					<div class="col-md-10 program-div">
						<table  class="serial">
							<tr>
								<td><b>Program : </b></td>
								<td>{{$applicant_info->program_title}}</td>
							</tr>
						</table>
					</div>

					<div class="col-md-9 applicant-info">
						<input type="hidden" class="site_url" value="{{url('/')}}">
						<input type="hidden" id="applicant_serial_no" value="{{$applicant_info->applicant_serial_no}}">
						<table class="info-table">
							<tr>
								<td style="width:25%">Applicant's Name</td>
								<td>:</td>		
								<td>{{$applicant_info->first_name.' '.$applicant_info->middle_name.' '.$applicant_info->last_name}} </td>
							</tr>
							<tr>
								<td>Trimester</td>
								<td>:</td>
								<td>{{$applicant_info->semester_title}}</td>
							</tr>
							<tr>
								<td>Academic Year</td>
								<td>:</td>		
								<td>{{$applicant_info->academic_year}}</td>
							</tr>
							<tr>
								<td>Contact</td>
								<td>:</td>		
								<td>{{$applicant_info->mobile}}</td>
							</tr>
							@if($applicant_info->payment_status==1)
							<tr>
								<td>Payment Status</td>
								<td>:</td>
								<td>Paid</td>
							</tr>
							@elseif($applicant_info->payment_status==2)
							<tr>
								<td>Payment Status</td>
								<td>:</td>
								<td>Waiting For Approval</td>
							</tr>

							@elseif(($applicant_info->payment_status==0) || ($applicant_info->payment_status==''))

							<tr>
								<td>Payment Status</td>
								<td>:</td>		
								<td class="payment_status">
									To be Paid <a data-toggle="modal" data-target="#cashpaymentModal" data-id="{{$applicant_info->applicant_serial_no}}" class="btn btn-warning cashpayment" >Cash Payment </a>
								</td>
							</tr>
							@endif

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="cashpaymentModal" class="modal fade " role="dialog">
	<div class="modal-dialog modal-sm">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cash Payment</h4>
			</div>
			<div class="modal-body">
				Are you sure?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
				<button type="button" data-loading-text="Saving..." class="btn btn-primary loadingButton" data-id="{{$applicant_info->applicant_serial_no}}" id="cash_payment">OK</button>
			</div>
		</div><!-- /Modal content-->
	</div>
</div><!-- /Modal -->
@else
@if(isset($_GET['applicant_serial_no']))
<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-body">
			<div class="page-header" >
				<center class="header-search">Search Result</center>
			</div>
			<div class="col-md-12 alert alert-danger text-center">
				<strong>No Applicant's Found</strong>
			</div>
		</div>
	</div>
</div>
@endif
@endif


@stop