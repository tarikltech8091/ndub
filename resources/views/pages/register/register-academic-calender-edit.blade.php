 @extends('layout.master')
 @section('content')
 @include('layout.bradecrumb')

 <!--error message*******************************************-->
 <div class="row page_row">
 	<div class="col-md-12">
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

 	</div>
 </div>
 <!--end of error message*************************************-->

 <div class="row page_row">
 	<div class="col-md-12">
 		<div class="panel panel-info">
 			<div class="panel-heading">Academic Calendar Edit</div>
 			<div class="panel-body">
 				<form action="{{url('/register/academic-calender/update',$edit_academic_calender->academic_calender_tran_code)}}" method="post">
 					<input type="hidden" name="_token" value="{{csrf_token()}}">

 					<div class="row">
 						<div class="form-group col-md-6">
 							<label for="Calender Year">Calendar Year <span class="required-sign">*</span></label>
 							@if(!empty($student_study_level_info))
 								<input type="text" class="form-control" name="academic_calender_year" value="{{$edit_academic_calender->academic_calender_year}}" readonly>
 							@else
 							<select class="form-control academic_year" name="academic_calender_year" required>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-2 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-2 year"))}}">{{date("Y",strtotime("-2 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-3 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-3 year"))}}">{{date("Y",strtotime("-3 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-4 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-4 year"))}}">{{date("Y",strtotime("-4 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-5 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-5 year"))}}">{{date("Y",strtotime("-5 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-6 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-6 year"))}}">{{date("Y",strtotime("-6 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-7 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-7 year"))}}">{{date("Y",strtotime("-7 year"))}}</option>
 								<option {{(isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_year==date('Y',strtotime('-8 year')))) ? 'selected':''}} value="{{date("Y",strtotime("-8 year"))}}">{{date("Y",strtotime("-8 year"))}}</option>
 							</select> 
							@endif
 						</div>


 						<div class="form-group col-md-6">
 							<label class="control-label">Academic Trimester<span class="required-sign">*</span></label>
 							 <?php 
 								$semester_list=\DB::table('univ_semester')->get();
 								$semester_info=\DB::table('univ_semester')
 								    ->where('semester_code', $edit_academic_calender->academic_calender_semester)
 								    ->first(); 
 							?>
 							@if(!empty($student_study_level_info))
 								<input type="text" class="form-control" value="{{$semester_info->semester_title}}" readonly>
 								<input type="hidden" name="academic_calender_semester" value="{{$semester_info->semester_code}}" readonly>

 							@else
 							<select class="form-control" name="academic_calender_semester">
 								@if(!empty($semester_list))
 								@foreach($semester_list as $key => $list)
 								<option {{isset($edit_academic_calender) && ($edit_academic_calender->academic_calender_semester == $list->semester_code) ? 'selected' : ''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
 								@endforeach
 								@endif
 							</select>
							@endif

 						</div>
 					</div>


 					<div class="row">
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester Start <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_start" size="16" type="text" value="{{$edit_academic_calender->semester_start}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester End <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_end" size="16" type="text" value="{{$edit_academic_calender->semester_end}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 					</div>


 					<div class="row">
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester Course Reg Start <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_course_reg_start" size="16" type="text" value="{{$edit_academic_calender->semester_course_reg_start}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester Course Reg End <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_course_reg_end" size="16" type="text" value="{{$edit_academic_calender->semester_course_reg_end}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 					</div>


 					<div class="row">
 						<div class="form-group col-md-6">
 							<label class="control-label">Midterm Exam Start <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="midterm_exam_start" size="16" type="text" value="{{$edit_academic_calender->midterm_exam_start}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 						<div class="form-group col-md-6">
 							<label class="control-label">Midterm Exam End <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="midterm_exam_end" size="16" type="text" value="{{$edit_academic_calender->midterm_exam_end}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 					</div>


 					<div class="row">
 						<div class="form-group col-md-6">
 							<label class="control-label">Final Exam Start <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="final_exam_start" size="16" type="text" value="{{$edit_academic_calender->final_exam_start}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 						<div class="form-group col-md-6">
 							<label class="control-label">Final Exam End <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="final_exam_end" size="16" type="text" value="{{$edit_academic_calender->final_exam_end}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 					</div>


 					<div class="row">
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester Break Start <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_break_start" size="16" type="text" value="{{$edit_academic_calender->semester_break_start}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 						<div class="form-group col-md-6">
 							<label class="control-label">Trimester Break End <span class="required-sign">*</span></label>
 							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
 								<input class="form-control" name="semester_break_end" size="16" type="text" value="{{$edit_academic_calender->semester_break_end}}" readonly>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
 								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
 							</div>
 						</div>
 					</div>


 					<div class="pull-right">
 						<a href="{{url('/register/academic-calender-registration')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
 						<input type="submit" class="btn btn-primary " value="Update" data-toggle="tooltip" title="Update Academic Calender">
 					</div>

 				</form>
 			</div>
 		</div>
 	</div>
 </div>

 @stop