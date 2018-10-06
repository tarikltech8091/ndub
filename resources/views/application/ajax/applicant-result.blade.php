

@if(!empty($applicant_info))


<div class="alert alert-warning">
	<a href="{{url('/online-application/applicant/admission-result')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>

	<div class="row">
		<div class="page-header" >
			<center class="header-search">Search Result</center>
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
					<td>Result</td>
					<td>:</td>
					@if($applicant_info->applicant_eligiblity==3)
					<td class="alert alert-success">You have listed for Merit List <a href="{{url('/online-application/admission-payment/slip-'.$applicant_info->applicant_serial_no)}}" target="_blank">Download Payment Slip <i class="fa fa-print"></i></a><br>Please contact with NDUB office.</td>
					@elseif($applicant_info->applicant_eligiblity==2)
					<td class="alert alert-info">You have listed for Waiting List </td>
					@elseif($applicant_info->applicant_eligiblity==1)
					<td>You are eligible candidate. <br>Please contact with NDUB office.</td>
					@elseif($applicant_info->applicant_eligiblity==5)
					<td>Admitted Student </td>
					@else
					<td></td>
					@endif
				</tr>
			</table>
		</div>
		<input type="hidden" class="site_url" value="{{url('/')}}">
		<input type="hidden" class="applicant_serial_no" value="{{$applicant_info->applicant_serial_no}}">
		
	</div>
</div>
@else
<div class="row">
	<div class="page-header" >
		<center class="header-search">Search Result</center>
	</div>
	<div class="col-md-12">
		<div class="alert alert-danger text-center">
			<strong>No Result's Found</strong>
		</div>
	</div>
</div>
@endif