
<style type="text/css">
	table {
		border-collapse: collapse;
		width: 100%;
	}

	table, td, th {
		border: 1px solid black;
	}

	span{
		font-family: "Times New Roman", Times, serif;
	}

	.header_table{
		background-color:#f8f9f9;
		border-bottom: 1px solid black;
	}

	.header_th_logo{
		text-align:left;
		width:30%
	}

	.header_th_logo img{
		width:200px;
		height:70px;
	}

	.header_nb{
		width:30%;
		text-align:right;
		font-size: 11px;
		vertical-align: bottom;
	}

	.header_title_p{
		font-size:13px;
		padding:0px;
		margin:0;
	}

</style>

<div style="background-color:#f8f9f9;padding:5px">
	<table border="0" class="header_table">
		<tr>
			<th class="header_th_logo">
				<img src="{{asset('images/banner-form.png')}}">
			</th>
			<th style="width:40%;">
				@if(!empty($student_basic))
				<p class="header_title_p">{{$student_basic->first_name}} {{$student_basic->middle_name}} {{$student_basic->last_name}}</p>
				<p class="header_title_p">{{$student_basic->program_title}}</p>
				<p class="header_title_p">{{ucfirst($univ_academic_calender->semester_title)}}-{{$univ_academic_calender->academic_calender_year}}</p>
				@endif
			</th>
			<td class="header_nb">
				<span>[NB: Schedule may change if need.]</span>
			</td>
		</tr>
	</table><br>

	<center><h4>Trimester Midterm Exam Schedule</h4></center>
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Course Code</th>
				<th>Course Tittle</th>
				<th>Program</th>
				<th>Exam Room</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($midterm_exam_schedule))
			@foreach($midterm_exam_schedule as $key => $mid_exam_schedule)
			<tr>
				<td>{{$mid_exam_schedule->exam_schedule_date}}</td>
				<td>{{$mid_exam_schedule->univ_time_slot_slug}}</td>
				<td>{{$mid_exam_schedule->exam_schedule_course}}</td>
				<td>{{$mid_exam_schedule->tabulation_course_title}}</td>
				<td>{{$mid_exam_schedule->program_code}}</td>
				<td>{{$mid_exam_schedule->exam_schedule_room}}</td>

			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="6">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">Schedule Not Published Yet !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table><br><br>

	<center><h4>Trimester Final Exam Schedule</h4></center>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Course Code</th>
				<th>Course Tittle</th>
				<th>Program</th>
				<th>Exam Room</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($final_exam_schedule))
			@foreach($final_exam_schedule as $key => $finalexam_schedule)
			<tr>
				<td>{{$finalexam_schedule->exam_schedule_date}}</td>
				<td>{{$finalexam_schedule->univ_time_slot_slug}}</td>
				<td>{{$finalexam_schedule->exam_schedule_course}}</td>
				<td>{{$finalexam_schedule->tabulation_course_title}}</td>
				<td>{{$finalexam_schedule->program_code}}</td>
				<td>{{$finalexam_schedule->exam_schedule_room}}</td>

			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="6">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">Schedule Not Published Yet !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table>

</div>