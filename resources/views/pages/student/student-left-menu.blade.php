<!-- left side start-->
<div class="left-side sticky-left-side">

	<!--logo and iconic logo start-->
	<div class="logo">
		<h1><a href="{{url('/dashboard/student/'.\Auth::user()->name_slug.'/home')}}">STUDENT</a></h1>
	</div>
	<div class="logo-icon text-center">
		<a href="{{url('/dashboard/student/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
	</div>

	<!--logo and iconic logo end-->
	<div class="left-side-inner">

		<!--sidebar nav start-->
		<ul class="nav nav-pills nav-stacked custom-nav">
			
			<li class="">		
				<a href="{{url('/dashboard/student/class-schedule')}}"><i class="lnr lnr-calendar-full"></i>
					<span>Class Schedule</span></a>
				</li>

				<li class="">		
					<a href="{{url('/dashboard/student/pre-advising')}}"><i class="lnr lnr-layers"></i>
						<span>Pre Advising</span></a>
					</li>

					<li class="menu-list">		
						<a href="#"><i class="lnr lnr-book"></i>
							<span>Course Plan</span></a>
							<ul class="sub-menu-list" style="">
								<li><a href="{{url('/dashboard/student/current-course')}}">Current Course</a> </li>
								<li><a href="{{url('/dashboard/student/course-detail')}}">Course Detail</a></li>
							</ul>
						</li>
						<li class="">		
							<a href="{{url('/dashboard/student/grade-sheet')}}"><i class="lnr lnr-license"></i>
								<span>Grade Sheet</span></a>
							</li>
							<li class="">		
								<a href="{{url('/dashboard/student/payment-status')}}"><i class="lnr lnr-indent-increase"></i>
									<span>Payement Status</span></a>
								</li>
								<li class="">		
									<a href="{{url('/dashboard/student/exame-routine')}}"><i class="lnr lnr-clock"></i>
										<span>Exam Routine</span></a>
									</li>
								</ul>
								<!--sidebar nav end-->
							</div>
						</div>
    <!-- left side end-->