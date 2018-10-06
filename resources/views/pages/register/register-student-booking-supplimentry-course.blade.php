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
		<!--*****************End of error message**********************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-12 course">
		<div class="panel panel-info">
			<div class="panel-body">
				<form method="get" action="{{url('/register/student/booking/supplimentry/course')}}">

					<div class="form-group col-md-4">
						<label for="Semester">Student Serial Id</label>
						<input type="text" name="student_serial_no" class="form-control" value="<?php echo isset($_GET['student_serial_no'])?$_GET['student_serial_no'] :''; ?>">
					</div>
					
					<div class="form-group col-md-2" style="margin-top:27px;">
						<button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Search Supplimentry Course">Search</button>
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

				<div class="panel-body">
			
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Course Code</th>
								<th>Course Title</th>
								<th>Course Type</th>
								<th>Year</th>
								<th>Trimester</th>
								<th>Slip No</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>

							@if(!empty($theory_courses))
							@foreach($theory_courses as $key => $list)
							
								<form action="{{url('/register/student/booking/supplimentry/course/confirm')}}" method="post" enctype="multipart/form-data">

									<?php

										$student_class_supplimentry_course_info=\DB::table('student_supplimentry_course')
	            							->where('class_or_lab_register_tran_code', $list->class_register_tran_code)
	            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
	            							->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
	            							->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
	            							->first(); 

	            						$student_class_supplimentry_course_initial_info=\DB::table('student_supplimentry_course')
	            							->where('supplimentry_student_course_status','2')
	            							->where('class_or_lab_register_tran_code', $list->class_register_tran_code)
	            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
	            							->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
	            							->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
	            							->first(); 
            						?>

									<tr>
										<td>T-{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->class_year}}</td>
										<td>{{$list->semester_title}}</td>
										<input type="hidden" class="form-control" name="class_or_lab_register_tran_code" value="{{(($list->class_register_tran_code)!=NULL)?$list->class_register_tran_code:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_student_tran_code" value="{{(($list->student_tran_code)!=NULL)?$list->student_tran_code:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_student_department" value="{{(($list->class_department)!=NULL)?$list->class_department:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_student_program" value="{{(($list->class_program)!=NULL)?$list->class_program:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_student_course_code" value="{{(($list->class_course_code)!=NULL)?$list->class_course_code:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_course_semster" value="{{(($list->class_semster)!=NULL)?$list->class_semster:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_course_year" value="{{(($list->class_year)!=NULL)?$list->class_year:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_student_course_type" value="{{(($list->course_type)!=NULL)?$list->course_type:''}}" />
										<td>
											<input type="text" class="form-control" name="supplimentry_student_payment_slip_no" value="{{isset($student_class_supplimentry_course_info)?$student_class_supplimentry_course_info->supplimentry_student_payment_slip_no : old('supplimentry_student_payment_slip_no')}}" />
										</td>

										<td>
											@if(empty($student_class_supplimentry_course_info))
												<input type="submit" class="btn btn-primary btn-xs padding_0" name="save" value="save">
											@else
												<span><i class="fa fa-check" aria-hidden="true"></i></span>
												@if(empty($student_class_supplimentry_course_initial_info))
													<a href="{{url('/register/supplimentry/delete/'.$student_class_supplimentry_course_info->student_supplimentry_course_tran_code)}}"><span class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
												@endif
											@endif
										</td>

									</tr>
								</form>

							@endforeach
							@endif

							@if(!empty($lab_courses))
							@foreach($lab_courses as $key => $list)

							<form action="{{url('/register/student/booking/supplimentry/course/confirm')}}" method="post" enctype="multipart/form-data">
								<tr>
									<?php 
										$student_supplimentry_course_info=\DB::table('student_supplimentry_course')
	            							->where('class_or_lab_register_tran_code', $list->lab_register_tran_code)
	            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
	            							->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
	            							->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
	            							->first();

	            						$student_supplimentry_course_initial_info=\DB::table('student_supplimentry_course')
	            							->where('supplimentry_student_course_status','2')
	            							->where('class_or_lab_register_tran_code', $list->lab_register_tran_code)
	            							->where('supplimentry_student_tran_code', $student_info->student_tran_code)
	            							->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
	            							->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
	            							->first(); 
            						?>
									<td>L-{{$key+1}}</td>
									<td>{{$list->course_code}}</td>
									<td>{{$list->course_title}}</td>
									<td>{{$list->course_type}}</td>
									<td>{{$list->lab_year}}</td>
									<td>{{$list->semester_title}}</td>
									<input type="hidden" class="form-control" name="class_or_lab_register_tran_code" value="{{(($list->lab_register_tran_code)!=NULL)?$list->lab_register_tran_code:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_student_tran_code" value="{{(($list->student_tran_code)!=NULL)?$list->student_tran_code:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_student_department" value="{{(($list->lab_department)!=NULL)?$list->lab_department:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_student_program" value="{{(($list->lab_program)!=NULL)?$list->lab_program:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_student_course_code" value="{{(($list->	lab_course_code)!=NULL)?$list->lab_course_code:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_course_semster" value="{{(($list->lab_semster)!=NULL)?$list->lab_semster:''}}" />
										<input type="hidden" class="form-control" name="supplimentry_course_year" value="{{(($list->lab_year)!=NULL)?$list->lab_year:''}}" />
									<input type="hidden" class="form-control" name="supplimentry_student_course_type" value="{{(($list->course_type)!=NULL)?$list->course_type:''}}" />
									<td>
										<input type="text" class="form-control" name="supplimentry_student_payment_slip_no" value="{{isset($student_supplimentry_course_info)?$student_supplimentry_course_info->supplimentry_student_payment_slip_no : old('supplimentry_student_payment_slip_no')}}" />
									</td>

									<td>
										@if(empty($student_supplimentry_course_info))
											<input type="submit" data-loading-text="Saving..." class="btn btn-primary btn-xs padding_0 loadingButton" name="save" value="save">
										@else
											<span><i class="fa fa-check" aria-hidden="true"></i></span>
											@if(empty($student_supplimentry_course_initial_info))
												<a href="{{url('/register/supplimentry/delete/'.$student_supplimentry_course_info->student_supplimentry_course_tran_code)}}"><span class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
											@endif
										@endif
									</td>

								</tr>
							</form>
							@endforeach
							@endif

						</tbody>

					</table>
		
				</div><!--/info body-->
			@else
				<div class="panel-body text-center">
					<span class="text-center">Invalid Student</span>
					
				</div>
			@endif

		</div>

	</div>

</div>

@stop