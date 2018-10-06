@if(($course_type->course_type=='Theory'))
@if(($course_type->course_type=='Theory') && !empty(!empty($student_class_registers)))
<div class="col-md-12" >

	<div class="form-group" style="margin-top:10px">
		<div class="col-md-12">
			<center>
				<label>Result Type: </label>
				<label><input type="radio" name="colorRadio" value="ct">CT</label>
				<label><input type="radio" name="colorRadio" value="mt">MT</label>
				<label><input type="radio" name="colorRadio" value="tf">TF</label>
			</center>
		</div>
	</div>




	<!--Serach Result-->
	<div class="row ct box" hidden>
		<table  class="table table-striped table-bordered table-hover">
			<thead>
				@if(!empty($class_course_info))
				<tr style="background-color:#f4f6f6;">
					<th colspan="2">Program: {{$class_course_info->program_title}}</th>
					<th colspan="1">Trimester: {{$class_course_info->semester_title}}</th>
					<th colspan="1">Year: {{$class_course_info->class_year}}</th>
					<th colspan="2">Course ID: {{$class_course_info->course_code}}</th>
					<th colspan="1">Credit :{{$class_course_info->credit_hours}}</th>
				</tr>
				@endif
				<tr >
					<th>Student ID</th>
					<th>Student Name</th>
					<th>Class Test 1 <br> (out of 10)</th>
					<th>Class Test 2 <br> (out of 10)</th>
					<th>Class Test 3 <br> (out of 10)</th>
					<th>Class Test 4 <br> (out of 10)</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				
				@foreach($student_class_registers as $key => $student_courses)
				<tr>
					<td>{{$student_courses->student_serial_no}}</td>
					<td>{{$student_courses->first_name}} {{$student_courses->middle_name}} {{$student_courses->last_name}}</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="ct_1" id="ct_1_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_quiz_1}}">
							@if(!empty($student_courses->class_quiz_1) || ($student_courses->class_quiz_1=='0.00'))
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@endif
						</span>
					</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="ct_2" id="ct_2_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_quiz_2}}">
							@if(!empty($student_courses->class_quiz_2) || ($student_courses->class_quiz_2=='0.00'))
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@endif
						</span>
					</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="ct_3" id="ct_3_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_quiz_3}}">
							@if(!empty($student_courses->class_quiz_3) || ($student_courses->class_quiz_3=='0.00'))
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@endif
						</span>
					</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="ct_4" id="ct_4_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_quiz_4}}">
							@if(!empty($student_courses->class_quiz_4) || ($student_courses->class_quiz_4=='0.00'))
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@endif
						</span>
						<!-- <span></span> -->
					</td>
					<td>
						<button type="button" data-loading-text="Saving..." data-program="{{$student_courses->class_program}}" data-semester="{{$student_courses->class_semster}}" data-year="{{$student_courses->class_year}}" data-course="{{$student_courses->class_course_code}}" data-student="{{$student_courses->student_serial_no}}" style="padding:2px;" class="btn btn-primary btn-sm classTestStore loadingButton" data-toggle="tooltip" title="Store Marks">Save</button>
						@if(!empty($student_courses->class_quiz_avg_total))
						<span class="approved_mark"><i class="fa fa-check"></i></span>
						@else
						<span id="{{$student_courses->student_serial_no}}"></span>
						@endif
					</td>
				</tr>
				@endforeach
				
			</tbody>
		</table>
	</div>



	<div class="row mt box" hidden>
		<table  class="table table-striped table-bordered table-hover">
			<thead>

				@if(!empty($class_course_info))
				<tr style="background-color:#f4f6f6;">
					<th colspan="1">Program: {{$class_course_info->program_title}}</th>
					<th colspan="1">Trimester: {{$class_course_info->semester_title}}</th>
					<th colspan="1">Year: {{$class_course_info->class_year}}</th>
					<th colspan="1">Course ID: {{$class_course_info->course_code}}</th>
					<th colspan="1">Credit :{{$class_course_info->credit_hours}}</th>
				</tr>
				@endif
				<tr>
					<th>Student ID</th>
					<th colspan="2">Student Name</th>
					<th>Mid Term (out of 20)</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>

				@foreach($student_class_registers as $key => $student_courses)
				<?php
				if(!empty($student_courses->class_mid_term_exam) && !empty($student_courses->class_mid_term_avg_total)){
					$mid_term_outof_find=(($student_courses->class_mid_term_exam)*'20')/($student_courses->class_mid_term_avg_total);
					$mid_term_outof_find=round($mid_term_outof_find,0);
				}else{
					$mid_term_outof_find='';
				}
				
				?>
				<tr>
					<td>{{$student_courses->student_serial_no}}</td>
					<td colspan="2">{{$student_courses->first_name}} {{$student_courses->middle_name}} {{$student_courses->last_name}}</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="mid_term" id="mid_term_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_mid_term_exam}}"><span>outof</span>
							<input type="text" style="width:60px" name="mid_term_outof" id="mid_term_outof_{{$student_courses->student_serial_no}}" value="{{$mid_term_outof_find}}">

							@if(!empty($student_courses->class_mid_term_avg_total) || ($student_courses->class_mid_term_avg_total=='0.00'))
							<span>= {{$student_courses->class_mid_term_avg_total}}</span>
							<span class="approved_mark"><i class="fa fa-check"></i></span>
							@endif

						<!-- @if(!empty($student_courses->class_mid_term_exam))
						<span class="approved_mark"><i class="fa fa-check"></i></span>
						@endif -->
					</span>
				</td>

				<td>
					<button type="button" data-loading-text="Saving..." data-program="{{$student_courses->class_program}}" data-semester="{{$student_courses->class_semster}}" data-year="{{$student_courses->class_year}}" data-course="{{$student_courses->class_course_code}}" data-student="{{$student_courses->student_serial_no}}" style="padding:2px;" class="btn btn-primary btn-sm midTermStore loadingButton" data-toggle="tooltip" title="Store Marks">Save</button>
					@if(!empty($student_courses->class_mid_term_avg_total))
					<span class="approved_mark"><i class="fa fa-check"></i></span>
					@else
					<span id="mid_{{$student_courses->student_serial_no}}"></span>
					@endif
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
</div>


<div class="row tf box" hidden>
	<table class="table table-striped table-bordered table-hover">

		<thead>
			@if(!empty($class_course_info))
			<tr style="background-color:#f4f6f6;">
				<th colspan="2">Program: {{$class_course_info->program_title}}</th>
				<th colspan="2">Trimester: {{$class_course_info->semester_title}}</th>
				<th colspan="1">Year: {{$class_course_info->class_year}}</th>
				<th colspan="2">Course ID: {{$class_course_info->course_code}}</th>
				<th colspan="1">Credit :{{$class_course_info->credit_hours}}</th>
			</tr>
			@endif
			<tr>
				<th>Student ID</th>
				<th>Student Name</th>
				<th>Attendance <br>(out of 10)</th>
				<th>Participation <br>(out of 5)</th>
				<th>Presentation <br>(out of 15)</th>
				<th>Final Exam <br>(out of 40)</th>
				<th>Action</th>
				<th><input type="checkbox" id="checkAll" checked /></th>
			</tr>
		</thead>
		<tbody>
			

			@foreach($student_class_registers as $key => $student_courses)
			<tr>
				<td>{{$student_courses->student_serial_no}}</td>
				<td>{{$student_courses->first_name}} {{$student_courses->middle_name}} {{$student_courses->last_name}}</td>
				
				<?php
				$class_quiz=\DB::table('student_class_registers')->where('student_tran_code',$student_courses->student_tran_code)->where('class_course_code',$student_courses->class_course_code)->where('class_result_status',0)->first();

				if(!empty($student_courses->class_final_exam) && !empty($student_courses->class_final_avg_total)){
					$final_exam_outof_find=(($student_courses->class_final_exam)*'40')/($student_courses->class_final_avg_total);
					$final_exam_outof_find=round($final_exam_outof_find,0);
				}else{
					$final_exam_outof_find='';
				}

				?>
				
				
				<td>
					<span>
						<input type="text" style="width:40px" name="class_attendance" id="class_attendance_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_attendance}}">
					</span>
				</td>
				<td>
					<span>
						<input type="text" style="width:40px" name="class_participation" id="class_participation_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_participation}}">
					</span>
				</td>
				<td>
					<span>
						<input type="text" style="width:40px" name="class_presentaion" id="class_presentaion_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_presentaion}}">
					</span>
				</td>
				
				<td>
					<span>
						<input type="text" style="width:40px" name="class_final_exam" id="class_final_exam_{{$student_courses->student_serial_no}}" value="{{$student_courses->class_final_exam}}"><span>outof</span>
						<input type="text" style="width:40px" name="final_outof" id="final_outof_{{$student_courses->student_serial_no}}" value="{{$final_exam_outof_find}}">
						@if(!empty($student_courses->class_final_avg_total) || $student_courses->class_final_avg_total=='0.00')
						<span>= {{$student_courses->class_final_avg_total}}</span>
						@endif
					</span>
				</td>
				<td>
					<button type="button" data-loading-text="Saving..." data-program="{{$student_courses->class_program}}" data-semester="{{$student_courses->class_semster}}" data-year="{{$student_courses->class_year}}" data-course="{{$student_courses->class_course_code}}" data-student="{{$student_courses->student_serial_no}}" style="padding:2px;" class="btn btn-primary btn-sm finalExamStore loadingButton" data-toggle="tooltip" title="Store Marks">Save</button>
					
					@if(!empty($student_courses->class_grand_total))
					<span class="approved_mark"><i class="fa fa-check"></i></span>
					@else
					<span id="final_{{$student_courses->student_serial_no}}"></span>
					@endif

				</td>

				<td>
					<span>
						<input type="checkbox" name="student_tran_code[]" value="{{$student_courses->student_tran_code}}" checked />
					</span>
				</td>
			</tr>
			@endforeach


			<tr>
				<td colspan="10">
					@if(!empty($class_course_info))
					<input type="hidden" name="program" value="{{$class_course_info->class_program}}" />
					<input type="hidden" name="semester" value="{{$class_course_info->class_semster}}" />
					<input type="hidden" name="year" value="{{$class_course_info->class_year}}" />
					<input type="hidden" name="course_code" value="{{$class_course_info->class_course_code}}" />
					@endif
					<input type="hidden" name="course_type" value="Theory" />
					<input type="hidden" name="_token" value="{{csrf_token()}}" />
					<button type="submit" class="pull-right btn btn-primary btn-sm" data-toggle="tooltip" title="Publish Result">Publish Result</button>
				</td>
			</tr>

		</tbody>
	</table>

</div>

@else
	<div class="col-md-12" style="margin-top:20px;">
		<div class="alert alert-success">
			<center><h3 style="font-style:italic">Result processing student list empty !</h3></center>
		</div>
	</div>

@endif
@endif
</div>

@if($course_type->course_type=='Lab work')
@if(!empty($student_lab_register))
<div class="col-md-12" style="margin-top:20px;">

	<!--Serach Result-->
	<div class="row" >
		<table  class="table table-striped table-bordered table-hover">
			<thead>
				@if(!empty($lab_course_info))
				<tr style="background-color:#f4f6f6;">
					<th colspan="3">Program: {{$lab_course_info->program_title}}</th>
					<th colspan="2">Trimester: {{$lab_course_info->semester_title}}</th>
					<th colspan="1">Year: {{$lab_course_info->lab_year}}</th>
					<th colspan="2">Course ID: {{$lab_course_info->lab_course_code}}</th>
					<th colspan="1">Credit : {{$lab_course_info->credit_hours}}</th>
				</tr>
				@endif
				<tr >
					<th>Student ID</th>
					<th>Student Name</th>
					<th>Lab Attendance</th>
					<th>Lab Performance</th>
					<th>Lab Report</th>
					<th>Lab Verbal</th>
					<th>Lab Final</th>
					<th>Action</th>
					<th><input type="checkbox" id="checkAll" checked /></th>
				</tr>
			</thead>

			<tbody>

				@foreach($student_lab_register as $key => $lab_register)
				<tr>
					<td>{{$lab_register->student_serial_no}}</td>
					<td>{{$lab_register->first_name}} {{$lab_register->middle_name}} {{$lab_register->last_name}}</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="lab_attendance" id="lab_attendance_{{$lab_register->student_serial_no}}" value="{{$lab_register->lab_attendance}}">
							
						</span>
					</td>

					<td>
						<span>
							<input type="text" style="width:60px" name="lab_performance" id="lab_performance_{{$lab_register->student_serial_no}}" value="{{$lab_register->lab_performance}}">
							
						</span>
					</td>

					<td>
						<span>
							<input type="text" style="width:60px" name="lab_reprot" id="lab_reprot_{{$lab_register->student_serial_no}}" value="{{$lab_register->lab_reprot}}">
							
						</span>
					</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="lab_verbal" id="lab_verbal_{{$lab_register->student_serial_no}}" value="{{$lab_register->lab_verbal}}">
							
						</span>
					</td>
					<td>
						<span>
							<input type="text" style="width:60px" name="lab_final" id="lab_final_{{$lab_register->student_serial_no}}" value="{{$lab_register->lab_final}}">
							
						</span>
					</td>

					<td>
						<button type="button" data-loading-text="Saving..." data-program="{{$lab_register->lab_program}}" data-semester="{{$lab_register->lab_semster}}" data-year="{{$lab_register->lab_year}}" data-course="{{$lab_register->lab_course_code}}" data-student="{{$lab_register->student_serial_no}}" style="padding:2px;" class="btn btn-primary btn-sm labResultStore loadingButton">Save</button>
						
						@if(!empty($lab_register->lab_result_total))
						<span class="approved_mark"><i class="fa fa-check"></i></span>
						@else
						<span id="{{$lab_register->student_serial_no}}"></span>
						@endif
					</td>

					<td>
						<span>
							<input type="checkbox" name="student_tran_code[]" value="{{$lab_register->student_tran_code}}" checked />
						</span>
					</td>
				</tr>

				@endforeach


				<tr>
					<td colspan="10">
						@if(!empty($lab_course_info))
						<input type="hidden" name="program" value="{{$lab_course_info->lab_program}}" />
						<input type="hidden" name="semester" value="{{$lab_course_info->lab_semster}}" />
						<input type="hidden" name="year" value="{{$lab_course_info->lab_year}}" />
						<input type="hidden" name="course_code" value="{{$lab_course_info->lab_course_code}}" />
						@endif
						<input type="hidden" name="course_type" value="Lab" />
						<input type="hidden" name="_token" value="{{csrf_token()}}" />
						<button type="submit" class="pull-right btn btn-primary btn-sm">Publish Result</button>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>

</div>
@else
<div class="col-md-12" style="margin-top:20px;">
	<div class="alert alert-success">
		<center><h3 style="font-style:italic">Result processing student list empty !</h3></center>
	</div>
</div>
@endif
@endif

@if(($course_type->course_type=='Lab work') || ($course_type->course_type=='Lab work'))
	@if(!empty($student_lab_register) && !empty($student_class_registers))
		<div class="col-md-12" style="margin-top:20px;">
			<div class="alert alert-success">
				<center><h3 style="font-style:italic">Result processing student list empty !</h3></center>
			</div>
		</div>
	@endif
@endif




<script type="text/javascript">

	/*##############################
	# RadioButtonSelect
	############################# */
	$(document).ready(function () {
		$('input[type="radio"]').click(function () {
			if ($(this).attr("value") == "ct") {
				$(".box").hide();
				$(".ct").show();
			}
			if ($(this).attr("value") == "mt") {
				$(".box").hide();
				$(".mt").show();
			}
			if ($(this).attr("value") == "tf") {
				$(".box").hide();
				$(".tf").show();
			}
		});
	});



	/*##############################
	# classTestStore
	############################# */

	jQuery(function(){
		jQuery(".classTestStore").click(function(){

			var student_serial_no = jQuery(this).data('student');

			var course_code = jQuery(this).data('course');
			var program = jQuery(this).data('program');
			var semester = jQuery(this).data('semester');
			var year = jQuery(this).data('year');

			var ct_1 = jQuery("#ct_1_"+student_serial_no).val();
			var ct_2 = jQuery("#ct_2_"+student_serial_no).val();
			var ct_3 = jQuery("#ct_3_"+student_serial_no).val();
			var ct_4 = jQuery("#ct_4_"+student_serial_no).val();


			if(ct_1==''){
				var ct_1='101';
			}
			if(ct_2==''){
				var ct_2='101';
			}
			if(ct_3==''){
				var ct_3='101';
			}
			if(ct_4==''){
				var ct_4='101';
			}


			var site_url = jQuery('.site_url').val();

			var request_url = site_url+'/faculty/result-class-test-store/'+student_serial_no+'/'+program+'/'+semester+'/'+year+'/'+course_code+'/'+ct_1+'/'+ct_2+'/'+ct_3+'/'+ct_4;

			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){
					if(data==1)
						jQuery('#'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');

				}

			});


		});
});


/*##############################
# midTermStore
############################# */ 

jQuery(function(){
	jQuery(".midTermStore").click(function(){

		var student_serial_no = jQuery(this).data('student');

		var mid_term = jQuery("#mid_term_"+student_serial_no).val();
		var mid_term_outof = jQuery("#mid_term_outof_"+student_serial_no).val();

		if(mid_term==''){
			var mid_term = '101';
		}if(mid_term_outof==''){
			var mid_term_outof = '101';
		}

		
		var course_code = jQuery(this).data('course');
		var program = jQuery(this).data('program');
		var semester = jQuery(this).data('semester');
		var year = jQuery(this).data('year');
		var site_url = jQuery(".site_url").val();

		var request_url=site_url+'/faculty/result-mid-term-store/'+student_serial_no+'/'+program+'/'+semester+'/'+year+'/'+course_code+'/'+mid_term+'/'+mid_term_outof;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success: function(data){
				if(data==1)
					jQuery('#mid_'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');
			}
		});
	});
});


/*##############################
# finalExamStore
############################# */ 

jQuery(function(){
	jQuery(".finalExamStore").click(function(){

		var student_serial_no = jQuery(this).data('student');

		
		var class_attendance = jQuery("#class_attendance_"+student_serial_no).val();
		var class_participation = jQuery("#class_participation_"+student_serial_no).val();
		var class_presentaion = jQuery("#class_presentaion_"+student_serial_no).val();
		var class_final_exam = jQuery("#class_final_exam_"+student_serial_no).val();
		var final_outof = jQuery("#final_outof_"+student_serial_no).val();

		
		if(class_attendance==''){
			var class_attendance = '101';
		}if(class_participation==''){
			var class_participation = '101';
		}if(class_presentaion==''){
			var class_presentaion = '101';
		}

		if(class_final_exam==''){
			var class_final_exam = '101';
		}if(final_outof==''){
			var final_outof = '101';
		}

		
		var course_code = jQuery(this).data('course');
		var program = jQuery(this).data('program');
		var semester = jQuery(this).data('semester');
		var year = jQuery(this).data('year');
		var site_url = jQuery(".site_url").val();

		var request_url=site_url+'/faculty/result-final-store/'+student_serial_no+'/'+program+'/'+semester+'/'+year+'/'+course_code+'/'+class_attendance+'/'+class_participation+'/'+class_presentaion+'/'+class_final_exam+'/'+final_outof;
		

		jQuery.ajax({
			url: request_url,
			type: "get",
			success: function(data){
				if(data==1)
					jQuery('#final_'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');
			}
		});
	});
});



/*##############################
# labResultStore
############################# */ 

jQuery(function(){
	jQuery(".labResultStore").click(function(){

		var student_serial_no = jQuery(this).data('student');

		var lab_attendance = jQuery("#lab_attendance_"+student_serial_no).val();
		var lab_performance = jQuery("#lab_performance_"+student_serial_no).val();
		var lab_reprot = jQuery("#lab_reprot_"+student_serial_no).val();
		var lab_verbal = jQuery("#lab_verbal_"+student_serial_no).val();
		var lab_final = jQuery("#lab_final_"+student_serial_no).val();


		if(lab_attendance==''){
			var lab_attendance = '101';
		}if(lab_performance==''){
			var lab_performance = '101';
		}if(lab_reprot==''){
			var lab_reprot = '101';
		}if(lab_verbal==''){
			var lab_verbal = '101';
		}if(lab_final==''){
			var lab_final = '101';
		}

		
		var course_code = jQuery(this).data('course');
		var program = jQuery(this).data('program');
		var semester = jQuery(this).data('semester');
		var year = jQuery(this).data('year');
		var site_url = jQuery(".site_url").val();

		var request_url=site_url+'/faculty/lab-result-store/'+student_serial_no+'/'+program+'/'+semester+'/'+year+'/'+course_code+'/'+lab_attendance+'/'+lab_performance+'/'+lab_reprot+'/'+lab_verbal+'/'+lab_final;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success: function(data){
				if(data==1)
					jQuery('#'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');
			}
		});
	});
});



/*##############################
# checkAll
############################# */ 
$("#checkAll").change(function () {
	$("input:checkbox").prop('checked', $(this).prop("checked"));
});


jQuery(document).ready(function(){
	jQuery('[data-toggle="tooltip"]').tooltip();   
});
jQuery(document).ready(function(){
	jQuery('[data-toggle1="tooltip"]').tooltip();   
});

</script>