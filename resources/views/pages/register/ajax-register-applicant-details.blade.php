

@if(!empty($applicant_info))
<div class="row main-border">
	<div class="col-md-3" style="border-right:1px solid #bdc3c7">
		<center>
			<img src="{{asset($applicant_info[0]->app_image_url)}}" class="image" title="{{asset($applicant_info[0]->middle_name)}}" alt="{{asset($applicant_info[0]->applicant_serial_no)}}" />
		</center>
	</div>


	<div class="col-md-9 serial-gender-div">
		<div class="col-md-6">
			<span for="inputColor"><b>Serial No : {{$applicant_info[0]->applicant_serial_no}}</b></span>
		</div>
		<div class="col-md-6">
			<span class="serial_input_label">Gender : {{ucfirst($applicant_info[0]->gender)}}</span>
		</div>
	</div>
	<div class="col-md-9 program-div">
		<table  class="serial">
			<tr>
				<td><b>Program : </b></td>
				<td>{{$applicant_info[0]->program_title}}</td>
			</tr>
		</table>
	</div>

	<div class="col-md-9 applicant-info">
		<table class="info-table">
			<tr>
				<td style="width:25%">Applicant's Name</td>
				<td>:</td>		
				<td>{{$applicant_info[0]->first_name.' '.$applicant_info[0]->middle_name.' '.$applicant_info[0]->last_name}} </td>
			</tr>
			<tr>
				<td>Trimester</td>
				<td>:</td>
				<td>{{$applicant_info[0]->semester_title}}</td>
			</tr>
			<tr>
				<td>Academic Year</td>
				<td>:</td>		
				<td>{{$applicant_info[0]->academic_year}}</td>
			</tr>
			<tr>
				<td>Contact</td>
				<td>:</td>		
				<td>{{$applicant_info[0]->mobile}}</td>
			</tr>
			<tr>
				<td>Applicant Status</td>
				<td>:</td>
				@if(($applicant_info[0]->applicant_eligiblity==2))
					<td>Waiting Listed</td>
				@elseif($applicant_info[0]->applicant_eligiblity==3)
					<td>Merit Listed</td>
				@elseif($applicant_info[0]->applicant_eligiblity==4)
					<td>Special Listed</td>
				@elseif($applicant_info[0]->applicant_eligiblity==5)
					<td>Admitted Student</td>
				@else
					<td>Eligible</td>
				@endif
				
			</tr>
			
		</table><br>
		
		<div class="col-md-12 applicant-info">
		<b>Applicant Academic Information:</b>
		<table class="table table-striped table-bordered table-hover academic_result">
			<thead>
				<th>Exam Name</th>
				<th>Group</th>
				<th>Board</th>
				<th>Roll Number</th>
				<th>Passing Year</th>
				<th>Result(GPA)</th>
			</thead>
			<tbody>
				@foreach($applicant_info as $key => $applicant)
					<tr>
						<td>{{$applicant->exam_type}}</td>
						<td>{{$applicant->exam_group}}</td>
						<td>{{$applicant->exam_board}}</td>
						<td>{{$applicant->exam_roll_number}}</td>
						<td>{{$applicant->passing_year}}</td>
						<td>{{$applicant->result_gpa}}</td>
					</tr>
				@endforeach
				
			</tbody>
		</table>
		</div>
	</div>
		
</div>
@endif