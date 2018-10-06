<div style="background-color:#99a3a4;color:white;padding:5px;font-weight:bold">
	<span >Name: {{$students_info->first_name}} {{$students_info->middle_name}} {{$students_info->last_name}}</span>
	<span style="margin-left:3%">ID: {{$students_info->student_serial_no}}</span>
	<span style="margin-left:3%">Program: {{$students_info->program_code}}</span>
</div>
<table class="table table-bordered table-hover">
	<form action="{{url('/faculty/pre-advising-submit')}}" method="POST" >
		<thead style="background-color:#e5e8e8">
			<tr>
				<th>SL</th>
				<th>Course Code</th>
				<th>Course Title</th>
				<th>Course Type</th>
				<th>Credit</th>
				<th>
					<!-- <span>All <input type="checkbox" id="checkAll" value="" /></span> -->
					Action
				</th>
			</tr>
		</thead>
		<tbody>
			
			<?php
			$detail=unserialize($taken_courses->temp_preadvising_detail);
			?>

			@if(($taken_courses->temp_preadvising_status=='1') && !empty($detail))
			<?php 
			$total_credit = 0;
			$aa=0;
			?>
			@foreach($detail as $key => $course)
			<?php 
			$total_credit = $total_credit+number_format($course['temp_credit_hours'],1,'.','');
			?>
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$course['temp_course_code']}}</td>
				<td>{{$course['temp_course_title']}}</td>
				<td>{{$course['temp_course_type']}}</td>
				<td>{{number_format($course['temp_credit_hours'],1,'.','')}}</td>
				<td>
					<input type="checkbox" class="faculty_approved" name="temp_course_code[]" value="{{$course['temp_course_code']}}" credit="{{number_format($course['temp_credit_hours'],1,'.','')}}" />
				</td>
			</tr>
			
			@endforeach

			<tr>
				<td colspan="3">
					<b class="pull-right">Credit Taken: </b>
				</td>
				<td><b>{{$taken_courses->temp_preadvising_total_credit}}</b></td>
				<td>
					<span class="faculty_approved_total_credit">0.0</span>
				</td>
				<td colspan="1">
					<input type="hidden" name="temp_tran_code" value="{{$temp_tran_code}}" />
					<input type="hidden" name="pre_advising_status" value="2" />
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="submit" class="btn btn-primary btn-small pull-right" value="Advise" />
				</td>
			</tr>


			
			@elseif(($taken_courses->temp_preadvising_status=='3') && !empty($detail))
			<?php 
			$total_credit = 0;
			?>
			@foreach($detail as $key => $course)
			<?php 
			$total_credit = $total_credit+number_format($course['temp_credit_hours'],1,'.','');
			?>
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$course['temp_course_code']}}</td>
				<td>{{$course['temp_course_title']}}</td>
				<td>{{$course['temp_course_type']}}</td>
				<td>{{number_format($course['temp_credit_hours'],1,'.','')}}</td>
				<td>
					<input type="checkbox" class="faculty_approved" name="temp_course_code[]" value="{{$course['temp_course_code']}}" credit="{{number_format($course['temp_credit_hours'],1,'.','')}}" />
				</td>
			</tr>
			
			@endforeach

			<tr>
				<td colspan="3">
					<b class="pull-right">Credit Taken: </b>
				</td>
				<td><b>{{$taken_courses->temp_preadvising_total_credit}}</b></td>
				<td>
					<span class="faculty_approved_total_credit">0.0</span>
				</td>
				<td colspan="1">
					<input type="hidden" name="temp_tran_code" value="{{$temp_tran_code}}" />
					<input type="hidden" name="pre_advising_status" value="4" />
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="submit" class="btn btn-primary btn-small pull-right" value="Advise" />
				</td>
			</tr>
			
			@else
			<tr><td colspan="6">
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">Advised !</h3></center>
				</div>
			</td>
		</tr>

		@endif
	</tbody>
</table>

<script type="text/javascript">
	$("#checkAll").change(function () {
		$("input:checkbox").prop('checked', $(this).prop("checked"));
	});


/*##########################################
# Faculty Advising Courses
############################################
*/

jQuery(function () {
	$(".faculty_approved").change(function(){
		var credit = 0;
		$(".faculty_approved:checked").each(function(){        
			credit += parseFloat($(this).attr('credit'));  
		});
		jQuery('.faculty_approved_total_credit').html(credit);
	});
});

</script>




