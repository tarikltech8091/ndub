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
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success dash_pad_0">

			<div class="row page_row_dash">
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/register/applicant/list')}}';">
						<p>	
							<a href="{{url('/register/applicant/list')}}"><i class="fa fa-list" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/applicant/list')}}">Applicant List</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/admission/list')}}';">
						<p>	
							<a href="{{url('/register/admission/list')}}"><i class="fa fa-list-alt" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/admission/list')}}">Admmission List</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/admission/confirm')}}';">
						<p>	
							<a href="{{url('/register/admission/confirm')}}"><i class="fa fa-check-square-o" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/admission/confirm')}}">Admmission Confirm</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/credit/transfer')}}';">
						<p>	
							<a href="{{url('/register/student/credit/transfer')}}"><i class="fa fa-exchange" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/credit/transfer')}}">Student Credit Transfer</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/faculty-account-registration')}}';">
						<p>	
							<a href="{{url('/register/faculty-account-registration')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/faculty-account-registration')}}">Faculty Registration</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/employee-registration')}}';">
						<p>	
							<a href="{{url('/register/employee-registration')}}"><i class="fa fa-user-plus" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/employee-registration')}}">Employee Registration</a>
						</p>
					</div>
				</div><!--/reprtcard-->

			</div>


			<div class="row page_row_dash">


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/notice-board')}}';">
						<p>	
							<a href="{{url('/register/notice-board')}}"><i class='fa fa-th-large'></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/notice-board')}}">Notice Board</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/class-teacher-assign')}}';">
						<p>	
							<a href="{{url('/register/class-teacher-assign')}}"><i class="fa fa-user" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/class-teacher-assign')}}">Class Teacher Assign</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/faculty-assigned-course')}}';">
						<p>	
							<a href="{{url('/register/faculty-assigned-course')}}"><i class="fa fa-book" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/faculty-assigned-course')}}">Faculty Course Assign</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/trimester-student-assign')}}';">
						<p>	
							<a href="{{url('/register/trimester-student-assign')}}"><i class='fa fa-th-large'></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/trimester-student-assign')}}">Trimester Student Assign</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/attendance/list')}}';">
						<p>	
							<a href="{{url('/register/student/attendance/list')}}"><i class="fa fa-list-ol" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/attendance/list')}}">Student Class Attendance</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/withdraw/course')}}';">
						<p>	
							<a href="{{url('/register/student/withdraw/course')}}"><i class="fa fa-minus-square" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/withdraw/course')}}">Student Course Withdraw</a>
						</p>
					</div>
				</div><!--/reprtcard-->


			</div>

			@if((\Auth::user()->user_type) == 'register' && (\Auth::user()->user_role) == 'head') 
				<div class="row page_row_dash">

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/academic-calender-registration')}}';">
							<p>	
								<a href="{{url('/register/academic-calender-registration')}}"><i class="fa fa-calendar" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/academic-calender-registration')}}" >Academic Calender</a>
							</p>
						</div>
					</div><!--/reprtcard-->



					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student-grade-equivalent')}}';">
							<p>	
								<a href="{{url('/register/student-grade-equivalent')}}"><i class='fa fa-exchange'></i></a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/student-grade-equivalent')}}">Academic Grading System</a>
							</p>
						</div>
					</div><!--/reprtcard-->


					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/account-summery')}}';">
							<p>	
								<a href="{{url('/register/account-summery')}}"><i class="fa fa-money" aria-hidden="true"></i></a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/account-summery')}}">Account Summary</a>
							</p>
						</div>
					</div><!--/reprtcard-->

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student-list')}}';">
							<p>	
								<a href="{{url('/register/student-list')}}"><i class="fa fa-list" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/student-list')}}">Student List</a>
							</p>
						</div>
					</div><!--/reprtcard-->

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/faculty-list')}}';">
							<p>	
								<a href="{{url('/register/faculty-list')}}"><i class="fa fa-list" aria-hidden="true"></i></a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/faculty-list')}}">Faculty List</a>
							</p>
						</div>
					</div><!--/reprtcard-->

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/employee-list')}}';">
							<p>	
								<a href="{{url('/register/employee-list')}}"><i class="fa fa-list" aria-hidden="true"></i></a>
							</p>
							<p class="report_name">	
								<a href="{{url('/register/employee-list')}}">Employee List</a>
							</p>
						</div>
					</div><!--/reprtcard-->

				</div>
			@endif


			<div class="row page_row_dash">

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/grade-sheet')}}';">
						<p>	
							<a href="{{url('/register/student/grade-sheet')}}"><i class="fa fa-graduation-cap" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/grade-sheet')}}">Register Student Grade Sheet</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/all-summery')}}';">
						<p>	
							<a href="{{url('/register/all-summery')}}"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/all-summery')}}">Register All Summary</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/univ-time-slot')}}';">
						<p>	
							<a href="{{url('/register/univ-time-slot')}}"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/univ-time-slot')}}">Time Slot</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/class-schedule')}}';">
						<p>	
							<a href="{{url('/register/class-schedule')}}"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/class-schedule')}}">Class Schedule</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/schedule/exam-schedule')}}';">
						<p>	
							<a href="{{url('/register/schedule/exam-schedule')}}"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/schedule/exam-schedule')}}">Exam Schedule</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/attendance/percent')}}';">
						<p>	
							<a href="{{url('/register/attendance/percent')}}"><i class="fa fa-percent" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/attendance/percent')}}">Student Attendance</a>
						</p>
					</div>
				</div><!--/reprtcard-->


			</div>

			<div class="row page_row_dash">

										
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/booking/supplimentry/course')}}';">
						<p>	
							<a href="{{url('/register/student/booking/supplimentry/course')}}"><i class="fa fa-book" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/booking/supplimentry/course')}}">Supplimentry Booking Course</a>
						</p>
					</div>
				</div><!--/reprtcard-->				

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/booking/supplimentry/course/list')}}';">
						<p>	
							<a href="{{url('/register/student/booking/supplimentry/course/list')}}"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/booking/supplimentry/course/list')}}">Supplimentry Booking List </a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/student/supplimentry/course')}}';">
						<p>	
							<a href="{{url('/register/student/supplimentry/course')}}"><i class="fa fa-book" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/student/supplimentry/course')}}">Supplimentry Course</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/register/block/student-list')}}';">
						<p>	
							<a href="{{url('/register/block/student-list')}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
						</p>
						<p class="report_name">	
							<a href="{{url('/register/block/student-list')}}">Block Student List</a>
						</p>
					</div>
				</div><!--/reprtcard-->

			</div>



		</div>
	</div>
</div>


@stop