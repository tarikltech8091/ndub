@extends('email.layout.master')
@section('content')
 @if(!empty($applicant_info))
<h1 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 16px;	margin-bottom: 14px; line-height: 24px;ext-align: center;">Hello,</h1>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><b>{{$applicant_info->first_name}} {{$applicant_info->middle_name}} {{$applicant_info->last_name}},</b> You are eligible for admission test examination of Notre Dame University Bangladesh.</p>

<span style="font-size: 14px; line-height: 22px; color: #7c7e7f; font-family: Georgia,serif;">Applicant Information:</span>

<table style="width:100%;margin-top:7px;font-size: 14px;font-family:serif;border: 1px solid #62856e;border-collapse: collapse;">
	<tr style="color: #2e4053;font-family:serif;text-align:left;padding-left: 10px;">
		<th style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">Applicant ID</th>
		<td style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">{{$applicant_info->applicant_serial_no}}</td>
	</tr>
	<tr style="color: #2e4053;font-family:serif;text-align:left;padding-left: 10px;">
		<th style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">Program</th>
		<td style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">{{$applicant_info->program_title}}</td>
	</tr>
	<tr style="color: #2e4053;font-family:serif;text-align:left;padding-left: 10px;">
		<th style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">Trimester</th>
		<td style="padding-left: 10px;border: 1px solid #62856e;border-collapse: collapse;">{{$applicant_info->semester_title}} {{$applicant_info->academic_year}}</td>
	</tr>
	
</table>

<p style="margin-top: 30; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; "><b>Download and print the attached Admit Card.</b></p>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">For details: <a href="http://103.4.145.105/project/" target="_blank">Click the link...</a></p>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you have any query, feel free to contact our addmission support team :<a href="mailto:support@ndub.edu.bd" style="text-decoration: none;"> support@ndub.edu.bd </a></p>


<div class="footer" style="padding-top: 25px;">
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">Thanks</h2>
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">NDUB Admission Support Team.</h2>
</div>
@endif
@stop