

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Course Re-Advising: {{isset($temp_preadvising_info) ? $temp_preadvising_info->semester_title : ''}} {{isset($temp_preadvising_info) ? $temp_preadvising_info->temp_preadvising_year : ''}}</h4>
</div>
<div class="modal-body">
	<form action="{{url('/student/re-advising/submit',$temp_preadvising_tran_code)}}" method="post" enctype="multipart/form-data">
		<div style="height: 200px; overflow: auto;">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Course ID</th>
						<th>Course Titile</th>
						<th>Credit</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					@if(!empty($preadvising_courses))
					@foreach($preadvising_courses as $key => $courses)
					<?php
					$student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();

					$tabulation_passed_courses=\DB::table('student_academic_tabulation')->where('student_serial_no', $student_basic->student_serial_no)
					->where('tabulation_program', $student_basic->program)
					->where('tabulation_course_id', $courses->course_code)
					->where('tabulation_status', 1)
					->first();
					?>

					@if(!empty($tabulation_passed_courses) && ($courses->course_code == $tabulation_passed_courses->tabulation_course_id))

					@else
					<tr>
						<td>{{$courses->course_code}}</td>
						<td>{{$courses->course_title}}</td>
						<td>{{number_format($courses->credit_hours,1,'.','')}}</td>
						<td><input type="checkbox" credit="{{number_format($courses->credit_hours,1,'.','')}}" name="pre_advising_selected_checkbox[]" class="check" value="{{$courses->course_code}}"></td>
					</tr>
					@endif
					@endforeach
					@endif

					<!-- ################### 10-12-2017 ##################### -->

					@if(!empty($student_passed_courses))
						<tr><th colspan="4" class="text-center">Your passed course</th></tr>

						@foreach($student_passed_courses as $key => $passed_courses)

				            <?php 
				            	$student_passed_course_info=\DB::table('student_academic_tabulation')
				                    ->where('student_serial_no', $student_basic->student_serial_no)
				                    ->where('tabulation_program', $student_basic->program)
				                    ->where('tabulation_course_id', $passed_courses->tabulation_course_id)
				                    ->where('tabulation_status', 1)
				                    ->leftjoin('course_basic','course_basic.course_code','=','student_academic_tabulation.tabulation_course_id')
				                    ->first();
				            ?>
							@if(!empty($student_passed_course_info))
								<tr>
									<td>{{$student_passed_course_info->course_code}}</td>
									<td>{{$student_passed_course_info->course_title}}</td>
									<td>{{number_format($student_passed_course_info->credit_hours,1,'.','')}}</td>
									<td><input type="checkbox" credit="{{number_format($student_passed_course_info->credit_hours,1,'.','')}}" name="pre_advising_selected_checkbox[]" class="check" value="{{$student_passed_course_info->course_code}}"></td>
								</tr>

							@endif
						@endforeach
					@endif
					<!-- ################### 10-12-2017 ##################### -->
				</tbody>
			</table>
		</div>
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
					<th colspan="2">Taken Credit</th>
					<th colspan="2">
						<span class="pre_advising_total_credit">0.0</span>

						<input type="hidden" name="_token" value="{{csrf_token()}}" />
						<input type="hidden" value="0.0" class="pre_advising_total_credit_val" name="credit_taken">
						<input type="hidden" name="term" value="{{$term}}" />
						<input type="hidden" name="level" value="{{$level}}" />
						<button type="submit" class="btn btn-success pull-right">Submit</button>
					</th>

				</tr>

			</tbody>
		</table>
	</form>


</div>
<br>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<script type="text/javascript">
/*##########################################
# Pre Advising Selectbox
############################################
*/

jQuery(function () {
	$(".check").change(function(){
		var credit = 0;
		$(".check:checked").each(function(){        
			credit += parseFloat($(this).attr('credit'));  
		});
		jQuery('.pre_advising_total_credit').html(credit);
		jQuery('.pre_advising_total_credit_val').val(credit);
	});
});
</script>