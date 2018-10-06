@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="page_row row"><!--message-->
	<div class="col-md-12">
		<!--error message*******************************************-->
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-12 course">
		<div class="panel panel-info">
			<div class="panel-body">
				<form method="get" action="{{url('/register/student/supplimentry/course')}}">
					<div class="form-group col-md-3">
						<label for="Semester">Trimester</label>

						<select class="form-control " name="semester" >
							<option value="0">All</option>

							@if(!empty($semester_list))
							@foreach($semester_list as $key => $semester)
							<option {{(isset($_GET['semester']) && ($semester->semester_code==$_GET['semester'])) ? 'selected':''}} value="{{$semester->semester_code}}">{{$semester->semester_title}}</option>

							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="AcademicYear">Academic Year</label>
						<select class="form-control academic_year" name="academic_year" >
							<option value="0">All</option>
							@if(!empty($univ_academic_calender))
							@foreach($univ_academic_calender as $key => $year_list)
							<option {{(isset($_GET['academic_year']) && ($year_list->academic_calender_year==$_GET['academic_year'])) ? 'selected':''}} value="{{$year_list->academic_calender_year}}">{{$year_list->academic_calender_year}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="Semester">Student Serial Id</label>
						<input type="text" name="student_serial_no" class="form-control" value="<?php echo isset($_GET['student_serial_no'])?$_GET['student_serial_no'] :''; ?>">
					</div>
					
					<div class="form-group col-md-2" style="margin-top:27px;">
						<button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Search Academic Result">Search</button>
					</div>
				</form>
			</div>
		</div>

		<div class="panel panel-info">
			@if(!empty($theory_courses) || !empty($lab_courses))
			<div class="panel-heading text-center">
				<span class="text-left">Student ID : {{isset($student_info) ? $student_info->student_serial_no : '' }}</span>
				<span class="text-right">Name : {{isset($student_info) ? $student_info->first_name : ''}} {{isset($student_info) ? $student_info->middle_name : ''}} {{isset($student_info) ? $student_info->last_name : ''}}</span>
			</div>
			@else
			<div class="panel-heading text-center">
				<span>Invalid Student</span>
			</div>
			@endif
			<div class="panel-body">

							@if(!empty($theory_courses))
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Year</th>
										<th>Trimester</th>
										<th>CT 1(10%)</th>
										<th>CT 2(10%)</th>
										<th>CT 3(10%)</th>
										<th>CT 4(10%)</th>
										<th>Attendance(10%)</th>
										<th>Participation(5%)</th>
										<th>Presentation(15%)</th>
										<th>MidTerm(20%)</th>
										<th>Tri Final(40%)</th>
										<th>Total</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>

									@if(!empty($theory_courses))
									@foreach($theory_courses as $key => $list)
										<form action="{{url('/register/supplimentry-result-update/'.$list->student_serial_no.'/'.$list->course_code.'/'.$list->program.'/'.$list->class_semster.'/'.$list->class_year)}}" method="post" enctype="multipart/form-data">
											
											<tr class="{{isset($list->class_grand_total) && (($list->class_grand_total > 100) || ($list->class_grand_total < 0)) ? 'error_tr' : ''}}">
												<td>{{$key+1}}</td>
												<td>{{$list->course_code}}</td>
												<td>{{$list->course_title}}</td>
												<td>{{$list->class_year}}</td>
												<td>{{$list->semester_title}}</td>
												<td><input type="text" class="form-control width_50" name="class_quiz_1" value="{{(($list->class_quiz_1)!=NULL)?$list->class_quiz_1:'0'}}" /></td>
												<td><input type="text" class="form-control width_50"  name="class_quiz_2" value="{{(($list->class_quiz_2)!=NULL)?$list->class_quiz_2:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_quiz_3" value="{{(($list->class_quiz_3)!=NULL)?$list->class_quiz_3:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_quiz_4" value="{{(($list->class_quiz_4)!=NULL)?$list->class_quiz_4:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_attendance" value="{{(($list->class_attendance)!=NULL)?$list->class_attendance:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_participation" value="{{(($list->class_participation)!=NULL)?$list->class_participation:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_presentaion" value="{{(($list->class_presentaion)!=NULL)?$list->class_presentaion:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_mid_term_exam" value="{{(($list->class_mid_term_avg_total)!=NULL)?$list->class_mid_term_avg_total:'0'}}" /></td>
												<td><input type="text" class="form-control width_50 "  name="class_final_exam" value="{{(($list->class_final_avg_total)!=NULL)?$list->class_final_avg_total:'0'}}"/></td>
												<td>{{$list->class_grand_total}}</td>
												<?php
													$student_supplimentry_course_info=\DB::table('student_supplimentry_course')
			            							->where('supplimentry_student_course_code',$list->course_code)
			            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
			            							->where('supplimentry_student_semster', $univ_current_academic_calender->academic_calender_semester)
			            							->where('supplimentry_student_year', $univ_current_academic_calender->academic_calender_year)
			            							->where('supplimentry_course_semster', $list->class_semster)
			            							->where('supplimentry_course_year', $list->class_year)
			            							->first();
	            								?>
												<td>
												@if(\Auth::check())
													@if((\Auth::user()->user_type == 'register') && (\Auth::user()->user_role == 'head'))
														<input type="submit" class="btn btn-primary btn-xs padding_0" value="save" data-toggle="tooltip" title="Update Student Result">
													@else

														@if(!empty($student_supplimentry_course_info))
															<input type="submit" class="btn btn-primary btn-xs padding_0" value="save" data-toggle="tooltip" title="Update Student Result">
														@else
															<span class="btn btn-primary btn-xs" data-toggle="tooltip" title="Please booking supllimentry course"><i class="fa fa-info" aria-hidden="true"></i></span>
														@endif
													@endif
												@endif
												</td>
											</tr>
										</form>

									@endforeach
									@endif

									<input type="hidden" name="course_type" value="Theory" />
								</tbody>

							</table>
							@endif

							@if(!empty($lab_courses))
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Year</th>
										<th>Trimester</th>
										<th>Lab Attendance</th>
										<th>Lab Performance</th>
										<th>Lab Report</th>
										<th>Lab Verbal</th>
										<th>Lab Final</th>
										<th>Total</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lab_courses))
									@foreach($lab_courses as $key => $list)

									<form action="{{url('/register/supplimentry-result-update/'.$list->student_serial_no.'/'.$list->course_code.'/'.$list->program.'/'.$list->lab_semster.'/'.$list->lab_year)}}" method="post" enctype="multipart/form-data">
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->lab_year}}</td>
										<td>{{$list->semester_title}}</td>
										<td><input type="text" class="form-control width_50" name="lab_attendance" value="{{(($list->lab_attendance)!=NULL)?$list->lab_attendance:'0'}}" /></td>
										<td><input type="text" class="form-control width_50"  name="lab_performance" value="{{(($list->lab_performance)!=NULL)?$list->lab_performance:'0'}}" /></td>
										<td><input type="text" class="form-control width_50"  name="lab_reprot" value="{{(($list->lab_reprot)!=NULL)?$list->lab_reprot:'0'}}" /></td>
										<td><input type="text" class="form-control width_50"  name="lab_verbal" value="{{(($list->lab_verbal)!=NULL)?$list->lab_verbal:'0'}}" /></td>
										<td><input type="text" class="form-control width_50"  name="lab_final" value="{{(($list->lab_final)!=NULL)?$list->lab_final:'0'}}" /></td>
										<td>{{$list->lab_result_total}}</td>
										<?php
											$student_supplimentry_lab_course_info=\DB::table('student_supplimentry_course')
	            							->where('supplimentry_student_course_code',$list->course_code)
	            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
	            							->where('supplimentry_student_semster', $univ_current_academic_calender->academic_calender_semester)
	            							->where('supplimentry_student_year', $univ_current_academic_calender->academic_calender_year)
	            							->where('supplimentry_course_semster', $list->lab_semster)
			            					->where('supplimentry_course_year', $list->lab_year)
	            							->first();
        								?>
										<td>
												@if(\Auth::check())
													@if((\Auth::user()->user_type == 'register') && (\Auth::user()->user_role == 'head'))
														<input type="submit" class="btn btn-primary btn-xs padding_0" value="save" data-toggle="tooltip" title="Update Student Result">
													@else

														@if(!empty($student_supplimentry_lab_course_info))
															<input type="submit" class="btn btn-primary btn-xs padding_0" value="save" data-toggle="tooltip" title="Update Student Result">
														@else
															<span class="btn btn-primary btn-xs" data-toggle="tooltip" title="Please booking supllimentry course"><i class="fa fa-info" aria-hidden="true"></i></span>
														@endif
													@endif
												@endif

										</td>

									</tr>
									</form>
									@endforeach
									@endif
									<input type="hidden" name="course_type" value="lab_field" />
								</tbody>

							</table>

							@endif

						
			</div><!--/info body-->
		</div>

	</div>

</div>

@stop