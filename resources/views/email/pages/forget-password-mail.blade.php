@extends('email.layout.master')
@section('content') 
<h1 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 16px;	margin-bottom: 14px; line-height: 24px;">Hello,</h1>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><b>{{$user_info->name}}</b> Your request is accepted for change user password of Notre Dame University Bangladesh. </p>

<center>
<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><a href="{{$reset_url}}"><button style="height:40px;width:200px;background-color:#337ab7;font-size:20px;color:white;">Reset Password</button></a></p>
</center>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you didn't make this request then ignore this email. </p>

<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you have any query, feel free to contact our support team :<a href="mailto:support@ndub.edu.bd" style="text-decoration: none;"> support@ndub.edu.bd </a></p>


<div class="footer" style="padding-top: 25px;">
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">Thanks</h2>
	<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">NDUB Support Team.</h2>
</div>

@stop