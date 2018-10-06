
<div class="col-md-12">
	<h4 class="text-center alert alert-success">{{isset($program) ? $program : ''}}</h4>
</div>

<div class="col-md-12">
	@if(!empty($degree_plan_details))
	@foreach($degree_plan_details as $key => $details)

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="7" class="text-center" style="background-color:aqua">{{$details->course_category_name}}</th>
			</tr>
			<tr>
				<th>SL</th>
				<th>Course Title</th>
				<th>Course Code</th>
				<th>Type</th>
				<th>Level</th>
				<th>Term</th>
				<th>Credit</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$course_list=\DB::table('course_basic')->where('course_category',$details->course_category_slug)->where('course_program', $details->course_catalogue_program)->orderBy('level','asc')->get();
			?>
			@if(!empty($course_list))
			@foreach($course_list as $key => $courses)
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$courses->course_title}}</td>
				<td>{{$courses->course_code}}</td>
				<td>{{$courses->course_type}}</td>
				<td>{{$courses->level}}</td>
				<td>{{$courses->term}}</td>
				<td>{{$courses->credit_hours}}</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="7" class="alert alert-success text-center">No Course Available !</td>
			</tr>
			@endif
		</tbody>
	</table>
	<br>
	@endforeach
	@else
	<div class="alert alert-success text-center">Plan Not Set Yet !</div>
	@endif
</div>
