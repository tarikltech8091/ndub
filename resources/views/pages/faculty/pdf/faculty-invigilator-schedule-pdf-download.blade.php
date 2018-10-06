

<style type="text/css">
	table, td, th {
		border: 1px solid black;
		font-family: "Times New Roman", Times, serif;
	}

	table {
		border-collapse: collapse;
		width: 100%;
	}

	span{
		font-family: "Times New Roman", Times, serif;
	}

	.header_table{
		background-color:#f8f9f9;
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
				@if(!empty($faculty_basic))
				<p class="header_title_p">{{strtoupper($faculty_basic->first_name.' '.$faculty_basic->middle_name.' '.$faculty_basic->last_name)}}</p>
				<p class="header_title_p">{{$faculty_basic->program_title}}</p>
				<p class="header_title_p">Faculty Invigilator Schedule</p>
				<p class="header_title_p">{{ucfirst($univ_academic_calender->semester_title)}}-{{$univ_academic_calender->academic_calender_year}}</p>
				@endif
			</th>
			<td class="header_nb">
				<span>[NB: Schedule may change if need.]</span>
			</td>
		</tr>
	</table>

	<center><h4>Trimester Midterm Exam Invigilator</h4></center>
	<table id="" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Exam Room</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($invigilator_list_mid))
			@foreach($invigilator_list_mid as $key => $list_mid)
			<?php
			$invigilators_mid=explode(',', $list_mid->invigilators_ID);


			?>
			@foreach ($invigilators_mid as $key => $invigilator_mid) 
			@if($invigilator_mid==\Auth::user()->user_id)
			<tr>
				<td>{{$list_mid->invigilators_exam_date}}</td>
				<td>{{$list_mid->invigilators_exam_time_slot}}</td>
				<td>{{$list_mid->invigilators_exam_room}}</td>
			</tr>
			@endif
			@endforeach

			@endforeach
			@else
			<tr>
				<td colspan="3">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Available !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table><br><br>

	<center><h4>Trimester Final Exam Invigilator</h4></center>
	<table id="" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Exam Room</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($invigilator_list_final))
			@foreach($invigilator_list_final as $key => $list_final)
			<?php
			$invigilators_final=explode(',', $list_final->invigilators_ID);


			?>
			@foreach ($invigilators_final as $key => $invigilator_final) 
			@if($invigilator_final==\Auth::user()->user_id)
			<tr>
				<td>{{$list_final->invigilators_exam_date}}</td>
				<td>{{$list_final->invigilators_exam_time_slot}}</td>
				<td>{{$list_final->invigilators_exam_room}}</td>
			</tr>
			@endif
			@endforeach

			@endforeach
			@else
			<tr>
				<td colspan="3">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Available !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table>

</div>