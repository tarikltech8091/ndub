<style type="text/css">
	.width_50{
		width: 50px;
	}
	.error_tr{
		background-color: #ecadad;
	}
</style>
<form action="{{url('/faculty/head-result-publish')}}" method="post" enctype="multipart/form-data">
	<div class="modal-body">

		@if($course_type =='Theory')
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>SL</th>
					<th>Student ID</th>
					<th>CT 1</th>
					<th>CT 2</th>
					<th>CT 3</th>
					<th>CT 4</th>
					<th>Attendance</th>
					<th>Participation</th>
					<th>Presentation</th>
					<th>MidTerm</th>
					<th>Tri Final</th>
					<th>Total</th>
					<th>Action</th>
					<th><input type="checkbox" class="checkAll" checked="" /></th>
				</tr>
			</thead>

			<tbody>

				@if(!empty($courses))
				@foreach($courses as $key => $list)
				
				<tr class="{{isset($list->class_grand_total) && (($list->class_grand_total > 100) || ($list->class_grand_total < 0)) ? 'error_tr' : ''}}">
					<td>{{$key+1}}</td>
					<td>{{$list->student_serial_no}}</td>
					<td><input type="text" class="width_50 class_quiz_1_{{$list->student_serial_no}}" name="" value="{{$list->class_quiz_1}}" /></td>
					<td><input type="text" class="width_50 class_quiz_2_{{$list->student_serial_no}}"  name="" value="{{$list->class_quiz_2}}" /></td>
					<td><input type="text" class="width_50 class_quiz_3_{{$list->student_serial_no}}"  name="" value="{{$list->class_quiz_3}}" /></td>
					<td><input type="text" class="width_50 class_quiz_4_{{$list->student_serial_no}}"  name="" value="{{$list->class_quiz_4}}" /></td>
					<td><input type="text" class="width_50 class_attendance_{{$list->student_serial_no}}"  name="" value="{{$list->class_attendance}}" /></td>
					<td><input type="text" class="width_50 class_participation_{{$list->student_serial_no}}"  name="" value="{{$list->class_participation}}" /></td>
					<td><input type="text" class="width_50 class_presentaion_{{$list->student_serial_no}}"  name="" value="{{$list->class_presentaion}}" /></td>
					<td><input type="text" class="width_50 class_mid_term_exam_{{$list->student_serial_no}}"  name="" value="{{$list->class_mid_term_avg_total}}" /></td>
					<td><input type="text" class="width_50 class_final_exam_{{$list->student_serial_no}}"  name="" value="{{$list->class_final_avg_total}}" /></td>
					<td>{{$list->class_grand_total}}</td>
					<td>
						<a type="button" class="btn btn-primary btn-xs padding_0 program_head_result_update" data-student="{{$list->student_serial_no}}" data-course-code="{{$list->class_course_code}}" data-program="{{$list->class_program}}" data-type="Theory" data-toggle="tooltip" title="Update Student Result">Update</a>
						<span id="{{$list->student_serial_no}}"></span>
					</td>
					<td><input type="checkbox" class="checkAll" checked="" /></td>

					<input type="hidden" name="student_serial_no[]" value="{{$list->student_serial_no}}" />
					<input type="hidden" name="program" value="{{$list->class_program}}" />
					<input type="hidden" name="course_code" value="{{$list->class_course_code}}" />
				</tr>

				@endforeach
				@endif

				<input type="hidden" name="course_type" value="Theory" />
			</tbody>

		</table>

		@else
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>SL</th>
					<th>Student ID</th>
					<th>Lab Attendance</th>
					<th>Lab Performance</th>
					<th>Lab Reprot</th>
					<th>Lab Verbal</th>
					<th>Lab Final</th>
					<th>Total</th>
					<th>Action</th>
					<th><input type="checkbox" class="checkAll" checked="" /></th>
				</tr>
			</thead>

			<tbody>
				@if(!empty($courses))
				@foreach($courses as $key => $list)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$list->student_serial_no}}</td>
					<td><input type="text" class="width_50 lab_attendance_{{$list->student_serial_no}}" name="" value="{{$list->lab_attendance}}" /></td>
					<td><input type="text" class="width_50 lab_performance_{{$list->student_serial_no}}"  name="" value="{{$list->lab_performance}}" /></td>
					<td><input type="text" class="width_50 lab_reprot_{{$list->student_serial_no}}"  name="" value="{{$list->lab_reprot}}" /></td>
					<td><input type="text" class="width_50 lab_verbal_{{$list->student_serial_no}}"  name="" value="{{$list->lab_verbal}}" /></td>
					<td><input type="text" class="width_50 lab_final_{{$list->student_serial_no}}"  name="" value="{{$list->lab_final}}" /></td>
					<td>{{$list->lab_result_total}}</td>
					<td>
						<a type="button" class="btn btn-primary btn-xs padding_0 program_head_result_update" data-student="{{$list->student_serial_no}}" data-course-code="{{$list->lab_course_code}}" data-program="{{$list->lab_program}}" data-type="lab_field" data-toggle="tooltip" title="Update Student Result">Update</a>
						<span id="{{$list->student_serial_no}}"></span>
					</td>
					<td><input type="checkbox" class="checkAll" checked="" /></td>

					<input type="hidden" name="student_serial_no[]" value="{{$list->student_serial_no}}" />
					<input type="hidden" name="program" value="{{$list->lab_program}}" />
					<input type="hidden" name="course_code" value="{{$list->lab_course_code}}" />
				</tr>

				@endforeach
				@endif
				<input type="hidden" name="course_type" value="lab_field" />
			</tbody>

		</table>

		@endif

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Publish Trimester Course Result">Publish Now</button>
	</div>
</form>



<script type="text/javascript">
	/*###########################
# program head result update
#############################
*/ 
jQuery(function(){

	jQuery('.program_head_result_update').click(function(){
		var course_type=jQuery(this).data('type');
		var student_serial_no=jQuery(this).data('student');
		var program=jQuery(this).data('program');
		var course_code=jQuery(this).data('course-code');

		if(course_type=='Theory'){
			
			var class_quiz_1 = jQuery(".class_quiz_1_"+student_serial_no).val();
			var class_quiz_2 = jQuery(".class_quiz_2_"+student_serial_no).val();
			var class_quiz_3 = jQuery(".class_quiz_3_"+student_serial_no).val();
			var class_quiz_4 = jQuery(".class_quiz_4_"+student_serial_no).val();
			var class_attendance = jQuery(".class_attendance_"+student_serial_no).val();
			var class_participation = jQuery(".class_participation_"+student_serial_no).val();
			var class_presentaion = jQuery(".class_presentaion_"+student_serial_no).val();
			var class_mid_term_exam = jQuery(".class_mid_term_exam_"+student_serial_no).val();
			var class_final_exam = jQuery(".class_final_exam_"+student_serial_no).val();

			var current_page_url = jQuery('.current_page_url').val();
			var site_url = jQuery('.site_url').val();

			var request_url ='';
			var parameter = 0;

			if(class_quiz_1 !=''){

				parameter=1;
				request_url += 'class_quiz_1='+class_quiz_1;
			}

			if(class_quiz_2 !=''){
				if(parameter==1)
					request_url += '&class_quiz_2='+class_quiz_2;
				else{
					request_url += 'class_quiz_2='+class_quiz_2;
					parameter=1;
				}

			}
			if(class_quiz_3 !=''){
				if(parameter==1)
					request_url += '&class_quiz_3='+class_quiz_3;
				else{
					request_url += 'class_quiz_3='+class_quiz_3;
					parameter=1;
				}

			}
			if(class_quiz_4 !=''){
				if(parameter==1)
					request_url += '&class_quiz_4='+class_quiz_4;
				else{
					request_url += 'class_quiz_4='+class_quiz_4;
					parameter=1;
				}

			}

			if(class_attendance !=''){
				if(parameter==1)
					request_url += '&class_attendance='+class_attendance;
				else{
					request_url += 'class_attendance='+class_attendance;
					parameter=1;
				}

			}

			if(class_participation !=''){
				if(parameter==1)
					request_url += '&class_participation='+class_participation;
				else{
					request_url += 'class_participation='+class_participation;
					parameter=1;
				}

			}
			if(class_presentaion !=''){
				if(parameter==1)
					request_url += '&class_presentaion='+class_presentaion;
				else{
					request_url += 'class_presentaion='+class_presentaion;
					parameter=1;
				}

			}
			if(class_mid_term_exam !=''){
				if(parameter==1)
					request_url += '&class_mid_term_exam='+class_mid_term_exam;
				else{
					request_url += 'class_mid_term_exam='+class_mid_term_exam;
					parameter=1;
				}

			}

			if(class_final_exam !=''){

				if(parameter==1)
					request_url += '&class_final_exam='+class_final_exam;
				else
					request_url += 'class_final_exam='+class_final_exam;

			}

			if(request_url.length !=0){
				var request_url=site_url+'/faculty/program-head-result-update/'+student_serial_no+'/'+course_code+'/'+program+'?'+request_url;
			}else{
				var request_url=site_url+'/faculty/program-head-result-update/'+student_serial_no+'/'+course_code+'/'+program;
			}


			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					if(data==1)
						jQuery('#'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');
				}
			});	


		}else{

			var lab_attendance = jQuery(".lab_attendance_"+student_serial_no).val();
			var lab_performance = jQuery(".lab_performance_"+student_serial_no).val();
			var lab_reprot = jQuery(".lab_reprot_"+student_serial_no).val();
			var lab_verbal = jQuery(".lab_verbal_"+student_serial_no).val();
			var lab_final = jQuery(".lab_final_"+student_serial_no).val();
			var current_page_url = jQuery('.current_page_url').val();
			var site_url = jQuery('.site_url').val();
			var request_url ='';
			var parameter = 0;

			if(lab_attendance !=''){

				parameter=1;
				request_url += 'lab_attendance='+lab_attendance;
			}

			if(lab_performance !=''){
				if(parameter==1)
					request_url += '&lab_performance='+lab_performance;
				else{
					request_url += 'lab_performance='+lab_performance;
					parameter=1;
				}

			}
			if(lab_reprot !=''){
				if(parameter==1)
					request_url += '&lab_reprot='+lab_reprot;
				else{
					request_url += 'lab_reprot='+lab_reprot;
					parameter=1;
				}

			}
			if(lab_verbal !=''){
				if(parameter==1)
					request_url += '&lab_verbal='+lab_verbal;
				else{
					request_url += 'lab_verbal='+lab_verbal;
					parameter=1;
				}

			}

			if(lab_final !=''){

				if(parameter==1)
					request_url += '&lab_final='+lab_final;
				else
					request_url += 'lab_final='+lab_final;

			}

			if(request_url.length !=0){
				var request_url=site_url+'/faculty/program-head-result-update/'+student_serial_no+'/'+course_code+'/'+program+'?'+request_url;
			}else{
				var request_url=site_url+'/faculty/program-head-result-update/'+student_serial_no+'/'+course_code+'/'+program;
			}

			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					if(data==1)
						jQuery('#'+student_serial_no).html('<span class="approved_mark"><i class="fa fa-check"></i></span>');
				}
			});	





		}

		
	});
});


/*###########################
# checkAll
#############################
*/ 

jQuery(".checkAll").change(function () {
	jQuery("input:checkbox").prop('checked', jQuery(this).prop("checked"));
});


/*###########################
# tooltip
#############################
*/ 
jQuery(document).ready(function(){
    jQuery('[data-toggle="tooltip"]').tooltip();   
});
jQuery(document).ready(function(){
    jQuery('[data-toggle1="tooltip"]').tooltip();   
});

</script>