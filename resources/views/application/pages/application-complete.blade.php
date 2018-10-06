@extends('application.layout.master')
@section('content')

<div class="alert alert-success" role="alert">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	You have successfully applied to NDUB.Applicant Information was sent to <b>{{$applicant_info->email}}</b>

</div>

<div class="alert alert-warning">
	<a href="{{url('/online-application/form')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>

	<div class="row">
		<div class="page-header" >
			<center class="header-name">Applicant Information <span><a target="_blank" href="{{url('/online-application/applicant-info')}}" style="margin-left:50px;font-size:17px" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i>
			</a></span></center>
		</div>
	</div>
	<div class="row main-border">
		<div class="col-md-2 photo-div">
			<center>
				<img src="{{asset($applicant_info->app_image_url)}}" class="image" title="{{asset($applicant_info->middle_name)}}" alt="{{asset($applicant_info->applicant_serial_no)}}" />
			</center>
		</div>


		<div class="col-md-10 serial-gender-div">
			<div class="col-md-6 serial_input">
				<span class="serial_input_label"  for="inputColor">Serial No : {{$applicant_info->applicant_serial_no}}</span>
			</div>
			<div class="col-md-6">
				<table  class="serial">
					<tr>
						<td><span class="serial_input_label">Gender</span> : <span class="serial_input_label"> {{ucfirst($applicant_info->gender)}}</span></td>
						<!-- <td><input type="radio" name="gender" {{$applicant_info->gender =="female" ? "checked":""}}> <span>Female</span></td> -->
					</tr>
				</table>
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
				<tr>
					<td>Payment Status</td>
					<td>:</td>		
					<td>
						@if($applicant_info->payment_status==1)
						Paid </br>
						Instrtruction : You are eligible fo admission test.
						@elseif($applicant_info->payment_status==2)
						Waiting For Approval </br>
						Instrtruction : Please go to accounts office for approval.
						@else
						To be Paid </br>
						Instrtruction : Please go to accounts office for payment and approval.
						@endif
					</td>
				</tr>
			</table>
		</div>


	</div>
</div>

@stop