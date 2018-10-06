<!-- left side start-->
<div class="left-side sticky-left-side">

	<!--logo and iconic logo start-->
	<div class="logo">
		<h1><a href="{{url('/dashboard/faculty/'.\Auth::user()->name_slug.'/home')}}">FACULTY</a></h1>
	</div>
	<div class="logo-icon text-center">
		<a href="{{url('/dashboard/faculty/'.\Auth::user()->name_slug.'/home')}}"><i class="lnr lnr-home"></i> </a>
	</div>

	<!--logo and iconic logo end-->
	<div class="left-side-inner">

		<!--sidebar nav start-->
			<ul class="nav nav-pills nav-stacked custom-nav">
					
				<li class="">		
					<a href="{{url('/dashboard/faculty/class-schedule')}}"><i class="lnr lnr-calendar-full"></i>
						<span>Class Schedule</span></a>
				</li>

				<li class="">		
					<a href="{{url('/dashboard/faculty/course-advising')}}"><i class="lnr lnr-layers"></i>
						<span>Course Advising</span></a>
				</li>

				<li class="">		
					<a href="{{url('/dashboard/faculty/result-processing')}}"><i class="lnr lnr-license"></i>
						<span>Result Processing</span></a>
				</li>
				<li class="">		
					<a href="{{url('/dashboard/faculty/exam-schedule')}}"><i class="lnr lnr-clock"></i>
						<span>Exam Schedule</span></a>
				</li>
			</ul>
		<!--sidebar nav end-->
	</div>
</div>
    <!-- left side end-->