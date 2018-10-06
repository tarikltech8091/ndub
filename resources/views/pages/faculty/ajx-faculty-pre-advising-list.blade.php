
<table class="table table-bordered  table-hover">
	<thead>
		<tr>
			<th style="background-color:#99a3a4;color:white" colspan="5">Students List</th>
		</tr>
		<tr>
			<th>SL</th>
			<th>Student ID: </th>
			<th>Student Name: </th>
			<th>Program: </th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

		@if(!empty($pre_advised_students))
		@foreach($pre_advised_students as $key => $student)
		<?php
		$study_level=\DB::table('student_study_level')
		->where('student_tran_code',$student->student_tran_code)
		->where('study_level_status',1)
		->first();
		?>

		@if(!empty($study_level))
		@if($study_level->study_level_status=='1')
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
			<td>{{$student->student_serial_no}}</td>
			<td>{{$student->program_code}}</td>
			<td>
				<center>
					<button data-toggle="modal" data-loading-text="Saving..." data-target="#myModal" style="padding-top:0;padding-bottom:0;" type="button" class="btn btn-primary btn-xs student_pre_advising_course_lists loadingButton" data-temp-tran-code="{{$student->temp_preadvising_tran_code}}">Advise</button>
				</center>
			</td>
		</tr>
		@endif
		@endif
		@endforeach
		@else
		<tr>
			<td colspan="5">
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No pre-advising found !</h3></center>
				</div>
			</td>
		</tr>

		@endif


	</tbody>
</table>



<!-- Modal -->
<div id="myModal" class="modal fade bs-example-modal-lg" rtabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Pre Advised Courses</h4>
			</div>
			<div class="modal-body">
				<form action="{{URL::route('Faculty Pre Advising Submit')}}" method="post" enctype="multipart/form-data">
					<div id="pre_advising_course_list">

					</div>
				</form>


			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>

	</div>
</div>