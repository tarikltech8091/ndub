

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
		border-bottom:1px solid black;
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

<div style="background-color:#f8f9f9;padding:5px;">
	<table border="0" class="header_table">
		<tr>
			<th class="header_th_logo">
				<img src="{{asset('images/banner-form.png')}}">
			</th>
			<th style="width:40%;">
				
				<p class="header_title_p">Exam Invigilators Schedule</p>
				<p class="header_title_p">{{ucfirst($univ_academic_calender->semester_title)}}-{{$univ_academic_calender->academic_calender_year}}</p>
			</th>
			<td class="header_nb">
				<span>[NB: Schedule may change if need.]</span>
			</td>
		</tr>
	</table>

	<center><h4>Trimester Midterm Exam Invigilators</h4></center>
	<table id="" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Exam Room</th>
				<th>Invigilators ID</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($invigilators_mid))
			@foreach ($invigilators_mid as $key => $invigilator_mid) 
			<tr>
				<td>{{$invigilator_mid->invigilators_exam_date}}</td>
				<td>{{$invigilator_mid->invigilators_exam_time_slot}}</td>
				<td>{{$invigilator_mid->invigilators_exam_room}}</td>
				<td>{{$invigilator_mid->invigilators_ID}}</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="4">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Available !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table><br><br>

	<center><h4>Trimester Final Exam Invigilators</h4></center>
	<table id="" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Exam Room</th>
				<th>Invigilators ID</th>

			</tr>
		</thead>
		<tbody>
			@if(!empty($invigilators_final))
			@foreach ($invigilators_final as $key => $invigilator_final) 
			<tr>
				<td>{{$invigilator_final->invigilators_exam_date}}</td>
				<td>{{$invigilator_final->invigilators_exam_time_slot}}</td>
				<td>{{$invigilator_final->invigilators_exam_room}}</td>
				<td>{{$invigilator_final->invigilators_ID}}</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="4">
					<div class="alert alert-success">
						<center><h3 style="font-style:italic">No Data Available !</h3></center>
					</div>
				</td>
			</tr>
			@endif

		</tbody>
	</table>

</div>