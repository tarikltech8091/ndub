<?php $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>

<div class="logo-icon text-center cursor" onclick="location.href='{{isset($url)?$url:''}}';">
	<h4><a href="{{isset($url)?$url:''}}"><i class="fa fa-refresh" aria-hidden="true"></i></a></h4>
</div>

@if(\Auth::check() && \Auth::user()->user_type=='accounts')

<!--logo and iconic logo start-->
<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<h4><a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}">Dashboard <span class="cursor">{{\Auth::user()->user_type}}</span></a></h4>
</div>

<div class="logo-icon text-center cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
</div>

<!--logo and iconic logo end-->
<div class="left-side-inner">

	<!--sidebar nav start-->
	<ul class="nav nav-pills nav-stacked custom-nav">

		<li class="cursor"><a href="{{url('/accounts/applicant/total-amount')}}"><i class="fa fa-list" aria-hidden="true"></i><span>Applicant List</span></a></li>

		<li class="menu-list cursor"><a><i class="lnr lnr-menu"></i> <span>Applicant Payment</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/accounts/applicant/payment')}}">Payment List</a> </li>
				<li><a href="{{url('/accounts/applicant/cash-payment')}}">Cash Payment</a></li>
			</ul>
		</li>
		<li class="cursor"><a href="{{url('/accounts/admission/payement/list')}}"><i class="lnr lnr-spell-check"></i> <span>Addmission Payment</span></a></li>

		<li class="cursor"><a href="{{url('/accounts/account-summery')}}"><i class="fa fa-th-large" aria-hidden="true"></i><span>Accounts Summary</span></a></li>

		@if((\Auth::user()->user_type) == 'accounts' && (\Auth::user()->user_role) == 'head') 
		
			<li class="menu-list cursor"><a><i class="fa fa-credit-card"></i> <span>Fees</span></a>
				<ul class="sub-menu-list">
					<li><a href="{{url('/accounts/fee-category')}}">Accounts Fee Category</a></li>
					<li><a href="{{url('/accounts/fee-payment')}}">Accounts Fee Payment</a></li>
				</ul>
			</li>

			
			<li class="cursor"><a href="{{url('/accounts/waiver')}}"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Waiver List</span></a></li>

		@endif

		<li class="cursor"><a href="{{url('/accounts/student-payment-transaction')}}"><i class="fa fa-money" aria-hidden="true"></i><span>Student Accounts Transaction</span></a></li>

		<li class="cursor"><a href="{{url('/accounts/student/payment/summery')}}"><i class="fa fa-credit-card" aria-hidden="true"></i><span>Student Payment Summary</span></a></li>

	</ul>
	<!--sidebar nav end-->
</div>
@endif


@if(\Auth::check() && \Auth::user()->user_type=='register')
<!--logo and iconic logo start-->
<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<h4><a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}">Dhashboard <span class="cursor">{{isset(\Auth::user()->user_type)? 'Registrar' :''}}</span></a></h4>
</div>

<div class="logo-icon text-center cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
</div>

<!--logo and iconic logo end-->
<div class="left-side-inner">

	<!--sidebar nav start-->
	<ul class="nav nav-pills nav-stacked custom-nav">

		<li class="menu-list cursor"><a><i class="fa fa-user-plus"></i> <span>Registration</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/register/faculty-account-registration')}}">Faculty Registration</a></li>
				<li><a href="{{url('/register/employee-registration')}}">Employee Registration</a></li>
				<li><a href="{{url('/register/student/credit/transfer')}}">Student Credit Transfer</a></li>
				<li><a href="{{url('/register/student-list')}}">Student List</a></li>
				<li><a href="{{url('/register/block/student-list')}}">Block Student List</a></li>

				@if((\Auth::user()->user_type) == 'register' && (\Auth::user()->user_role) == 'head') 

					<li><a href="{{url('/register/faculty-list')}}">Faculty List</a></li>
					<li><a href="{{url('/register/employee-list')}}">Employee List</a></li>
					<li><a href="{{url('/register/account-summery')}}">Account Summary</a></li>
					<li><a href="{{url('/register/student/certificate/list')}}">Student Certificate List</a></li>
					<li><a href="{{url('/register/student/grade-sheet')}}">Student Grade Sheet</a></li>

				@endif

				<li><a href="{{url('/register/attendance/percent')}}">Student Attendance List</a></li>
				<li><a href="{{url('/register/existing/student')}}">Registrar Existing Student</a></li>
				<li><a href="{{url('/register/not-paid/applicant')}}">Not Paid Applicant</a></li>
				<li><a href="{{url('/register/all-summery')}}">Register All Summary</a></li>

			</ul>
		</li>

		<li class="cursor"><a href="{{url('/register/applicant/list')}}"><i class="lnr lnr-menu"></i> <span>Applicant List</span></a></li>

		<li class="menu-list cursor"><a><i class="lnr lnr-enter"></i> <span>Admission</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/register/admission/list')}}">Admission List</a> </li>
				<li><a href="{{url('/register/admission/confirm')}}">Admission Confirm</a></li>
			</ul>
		</li>

		<li class="menu-list cursor"><a><i class="fa fa-book" aria-hidden="true"></i><span>Supplementary</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/register/student/booking/supplimentry/course')}}">Registrar Booking Supplementary Course</a></li>
				<li><a href="{{url('/register/student/booking/supplimentry/course/list')}}">Supplementary Course List</a></li>
				<li><a href="{{url('/register/student/supplimentry/course')}}">Supplementary Course</a></li>

			</ul>
		</li>
		@if((\Auth::user()->user_type) == 'register' && (\Auth::user()->user_role) == 'head') 

			<li class="cursor"><a href="{{url('/register/academic-calender-registration')}}"><i class="fa fa-calendar"></i> <span>Academic Calendar</span></a></li>

			<li class="cursor"><a href="{{url('/register/student-grade-equivalent')}}"><i class="fa fa-exchange"></i> <span>Academic Grading System</span></a></li>

		@endif

		<li class="cursor"><a href="{{url('/register/notice-board')}}"><i class="fa fa-calendar"></i> <span>Notice Board</span></a></li>

		<li class="menu-list cursor"><a><i class="fa fa-book" aria-hidden="true"></i> <span>Assign</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/register/class-teacher-assign')}}">Class Teacher Assign</a></li>
				<li><a href="{{url('/register/faculty-assigned-course')}}">Faculty Course Assign</a> </li>
				<li><a href="{{url('/register/trimester-student-assign')}}">Trimester Student Assign</a></li>
			</ul>
		</li>

		<li class="menu-list cursor"><a><i class="fa fa-clock-o" aria-hidden="true"></i> <span>Scheduling</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/register/univ-time-slot')}}">Time Slot</a></li>
				<li><a href="{{url('/register/class-schedule')}}">Class Schedule</a></li>
				<li><a href="{{url('/register/schedule/exam-schedule')}}">Exam Schedule</a></li>
			</ul>
		</li>

		<li class="cursor"><a href="{{url('/register/student/attendance/list')}}"><i class="fa fa-list-ol"></i> <span>Student Class Attendance</span></a></li>

		<li class="cursor"><a href="{{url('/register/student/withdraw/course')}}"><i class="fa fa-minus-square"></i> <span>Student Course Withdraw</span></a></li>



	</ul>
	<!--sidebar nav end-->
</div>
@endif

@if(\Auth::check() && \Auth::user()->user_type=='academic')
<!--logo and iconic logo start-->
<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<h4><a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}">Dhasboard <span class="cursor">{{\Auth::user()->user_type}}</span></a></h4>
</div>

<div class="logo-icon text-center cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
</div>

<!--logo and iconic logo end-->
<div class="left-side-inner">

	<!--sidebar nav start-->
	<ul class="nav nav-pills nav-stacked custom-nav">	
		<li class="cursor"><a href="{{url('/academic-settings/home')}}"><i class="lnr lnr-menu"></i> <span>Academic Settings</span></a></li>
		<li class="cursor"><a href="{{url('/academic/course-settings')}}"><i class="lnr lnr-spell-check"></i> <span>Course Settings</span></a></li>


	</ul>
	<!--sidebar nav end-->
</div>
@endif


@if(\Auth::check() && \Auth::user()->user_type=='student')
<!--logo and iconic logo start-->
<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<h4><a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}">Dhasboard <span class="cursor">{{\Auth::user()->user_type}}</span></a></h4>
</div>

<div class="logo-icon text-center cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
</div>

<!--logo and iconic logo end-->
<div class="left-side-inner">

	<!--sidebar nav start-->
	<ul class="nav nav-pills nav-stacked custom-nav">

		<li class="cursor"><a href="{{url('/student/class-schedule')}}"><i class='fa fa-newspaper-o'></i> <span>Class Schedule</span></a></li>

		<li class="cursor"><a href="{{url('/student/pre-advising')}}"><i class='lnr lnr-layers'></i> <span>Pre Advising</span></a></li>

		<li class="cursor"><a href="{{url('/student/academic-course-plan')}}"><i class='lnr lnr-book'></i> <span>Course Plan</span></a></li>

		<li class="cursor"><a href="{{url('/student/grade-sheet')}}"><i class='lnr lnr-license'></i> <span>Grade Sheet</span></a></li>

		<li class="cursor"><a href="{{url('/student/payment-history')}}"><i class='lnr lnr-indent-increase'></i> <span>Payment History</span></a></li>

		<li class="cursor"><a href="{{url('/student/exam-routine')}}"><i class='lnr lnr-clock'></i> <span>Exam Routine</span></a></li>

	</ul>
	<!--sidebar nav end-->
</div>
@endif


@if(\Auth::check() && \Auth::user()->user_type=='faculty')
<!--logo and iconic logo start-->
<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
	<h4><a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}">Dhasboard <span class="cursor">{{\Auth::user()->user_type}}</span></a></h4>
</div>
<div class="logo-icon text-center cursor">
	<a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
</div>

<!--logo and iconic logo end-->
<div class="left-side-inner">

	<!--sidebar nav start-->
	<ul class="nav nav-pills nav-stacked custom-nav">

		<li><a href="{{url('/faculty/class-schedule')}}"><i class='fa fa-newspaper-o'></i> <span>Class Schedule</span></a></li>

		<li><a href="{{url('/faculty/course-advising')}}"> <i class='lnr lnr-layers'></i><span>Course Advising</span></a> </li>

		<li><a href="{{url('/faculty/assigned-courses')}}"><i class='fa fa-check-square-o'></i> <span>Assigned Course</span></a></li>

		<li><a href="{{url('/faculty/result-processing')}}"> <i class='lnr lnr-license'></i><span>Result Processing</span></a></li>

		<li><a href="{{url('/faculty/exam-schedule')}}"><i class='lnr lnr-clock'></i> <span>Exam Schedule</span></a></li>

		<li><a href="{{url('/faculty/notice-board')}}"><i class='fa fa-th-large'></i> <span>Notice Board</span></a></li>

		<li class="menu-list cursor"><a><i class="fa fa-th-list" aria-hidden="true"></i> <span>Attendance</span></a>
			<ul class="sub-menu-list">
				<li><a href="{{url('/faculty/student/attendance/list')}}">Student Attendance List</a></li>
				<li><a href="{{url('/faculty/student/attendance/percent')}}">Student Attendance Percent</a></li>
			</ul>
		</li>

		@if(\Auth::user()->user_role=='head')
		<li><a href="{{url('/faculty/program-head-result-publish')}}"><i class='fa fa-eye'></i> <span>Trimester Result Publish</span></a></li>
		@endif


	</ul>
	<!--sidebar nav end-->
</div>
@endif


@if(\Auth::check() && \Auth::user()->user_type=='systemadmin')

<!-- left side start-->
<div class="left-side sticky-left-side">

	<!--logo and iconic logo start-->
	<div class="logo cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
		<h4><a>Dhasboard <span class="cursor">{{\Auth::user()->user_type}}</span></a></h4>
	</div>
	<div class="logo-icon text-center cursor" onclick="location.href='{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}';">
		<a><i class="lnr lnr-home"></i> </a>
	</div>

	<!--logo and iconic logo end-->
	<div class="left-side-inner">

		<!--sidebar nav start-->
		<ul class="nav nav-pills nav-stacked custom-nav">

			<li class="menu-list cursor"><a><i class="fa fa-user-plus" aria-hidden="true"></i> <span>Registration</span></a>
				<ul class="sub-menu-list">
					<li><a href="{{url('/systemadmin/student-account')}}">Student Registration</a> </li>
					<li><a href="{{url('/system-admin/faculty-account')}}">Faculty Registration</a></li>
					<li><a href="{{url('/system-admin/employee-account')}}">Employee Registration</a></li>
				</ul>
			</li>
			<li class="cursor">  
				<a onclick="location.href='{{url('/system-admin/system-users')}}';"><i class="fa fa-list" aria-hidden="true"></i>
					<span>System Users</span></a>
			</li> 

			<li class="cursor">  
				<a onclick="location.href='{{url('/system-admin/access-logs')}}';"><i class="fa fa-area-chart" aria-hidden="true"></i>
					<span>Access Logs</span></a>
			</li> 

			<li class="cursor">		
				<a onclick="location.href='{{url('/system-admin/event-logs')}}';"><i class="fa fa-cubes" aria-hidden="true"></i>
					<span>Event Logs</span></a>
			</li>


			<li class="cursor">		
				<a onclick="location.href='{{url('/system-admin/error-logs')}}';"><i class="fa fa-minus-circle" aria-hidden="true"></i>
					<span>Error Logs</span></a>
			</li>


			<li class="cursor">		
				<a onclick="location.href='{{url('/system-admin/auth-logs')}}';"><i class="fa fa-key" aria-hidden="true"></i>
					<span>Auth Logs</span></a>
			</li>


		</ul>
		<!--sidebar nav end-->
	</div>

</div>
<!-- left side end-->

@endif