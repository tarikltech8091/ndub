

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">{{isset($course_category_name) ? $course_category_name : ''}}</h4>
</div>
<div class="modal-body">
	@if(!empty($completed_course))
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>SL</th>
				<th>Course Code</th>
				<th>Course Title</th>
				<th>Credit</th>
				<th>Completed</th>
			</tr>
		</thead>
		<tbody>
			<?php $total_credits=0; ?>
			@foreach($completed_course as $key => $list)
			<?php 
			$course_status=\DB::table('student_academic_tabulation')->where('tabulation_course_id', $list->course_code)->where('student_serial_no', \Auth::user()->user_id)->where('tabulation_status',1)->first();
			$total_credits=$total_credits+$list->credit_hours;
			?>
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$list->course_code}}</td>
				<td>{{$list->course_title}}</td>
				<td>{{$list->credit_hours}}</td>
				<td>
					@if(!empty($course_status))
					<center><span class="approved_mark"><i class="fa fa-check"></i></span></center>
					@else
					<center>No</center>
					@endif
				</td>
			</tr>
			@endforeach
			<tr>
				<th colspan="3">Courses</th>
				<th colspan="3">{{isset($total_credits) ? $total_credits : ''}} Credits (Total)</th>
			</tr>
		</tbody>
	</table>

	@else
	<div class="alert alert-success">
		<center><h3 style="font-style:italic">No course completed yet !</h3></center>
	</div>
	@endif
</div>
<br>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>