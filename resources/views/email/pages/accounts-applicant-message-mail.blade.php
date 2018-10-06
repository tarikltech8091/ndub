@extends('email.layout.master')
@section('content') 
@if(!empty($applicant_info))
<h1 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 16px;	margin-bottom: 14px; line-height: 24px;">Hello,</h1>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><b>{{$applicant_info->first_name}} {{$applicant_info->middle_name}} {{$applicant_info->last_name}}</b></p>


<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><b>{{$applicants_message}}</b></p>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">Your Applicant ID: <b>{{$applicant_info->applicant_serial_no}}</b></p>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you have any query, feel free to contact our addmission support team : <a href="mailto:support@ndub.edu.bd" style="text-decoration: none;"> support@ndub.edu.bd </a></p>

 
<div class="footer" style="padding-top: 25px;">
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">Thanks</h2>
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">NDUB Admission Support Team.</h2>
</div> 
@endif
@stop