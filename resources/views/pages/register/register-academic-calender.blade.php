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
			<div class="panel-heading">Academic Calender Registration</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/register/academic-calender-registration')}}" method="post">

					<div class="row">
						<div class="form-group col-md-6">
							<label for="Calender Year">Calendar Year <span class="required-sign">*</span></label>
							<select class="form-control" name="academic_calender_year" required>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('+1 year'))) ? "selected" :''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
								<option {{(old('program_coordinator_year') == date('Y'))? "selected" :''}} value="{{date('Y')}}">{{date('Y')}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-1 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-2 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-2 year"))}}">{{date("Y",strtotime("-2 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-3 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-3 year"))}}">{{date("Y",strtotime("-3 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-4 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-4 year"))}}">{{date("Y",strtotime("-4 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-5 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-5 year"))}}">{{date("Y",strtotime("-5 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-6 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-6 year"))}}">{{date("Y",strtotime("-6 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-7 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-7 year"))}}">{{date("Y",strtotime("-7 year"))}}</option>
								<option {{( old('program_coordinator_year')== date('Y',strtotime('-8 year'))) ? "selected" :''}} value="{{date("Y",strtotime("-8 year"))}}">{{date("Y",strtotime("-8 year"))}}</option>
							</select>
						</div>

						<?php 
						$semester_list =\DB::table('univ_semester')->select('univ_semester.*')->get();
						?>
						<div class="form-group col-md-6">
							<label class="control-label">Academic Trimester<span class="required-sign">*</span></label>
							<select class="form-control" name="academic_calender_semester">
								<option  value="{{old('academic_calender_semester')}}">Select Trimester</option>
								@if(!empty($semester_list))
								@foreach($semester_list as $key => $list)
								<option {{(old('academic_calender_semester')== $list->semester_code) ? "selected" :''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
								@endforeach
								@endif
							</select> 
						</div>
					</div>


					<div class="row">
						<div class="form-group col-md-6">
							<label class="control-label">Trimester Start <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_start" size="16" type="text" value="{{old('semester_start')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Trimester End <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_end" size="16" type="text" value="{{old('semester_end')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="form-group col-md-6">
							<label class="control-label">Trimester Course reg start <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_course_reg_start" size="16" type="text" value="{{old('semester_course_reg_start')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Trimester Course reg End <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_course_reg_end" size="16" type="text" value="{{old('semester_course_reg_end')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="form-group col-md-6">
							<label class="control-label">Midterm Exam Start <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="midterm_exam_start" size="16" type="text" value="{{old('midterm_exam_start')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Midterm Exam End <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="midterm_exam_end" size="16" type="text" value="{{old('midterm_exam_end')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="form-group col-md-6">
							<label class="control-label">Final Exam Start <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="final_exam_start" size="16" type="text" value="{{old('final_exam_start')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Final Exam End <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="final_exam_end" size="16" type="text" value="{{old('final_exam_end')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="form-group col-md-6">
							<label class="control-label">Trimester Break Start <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_break_start" size="16" type="text" value="{{old('semester_break_start')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Trimester Break End <span class="required-sign">*</span></label>
							<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="semester_break_end" size="16" type="text" value="{{old('semester_break_end')}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</div>

					<div class="pull-right">
						<a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
						<input type="submit" class="btn btn-primary " value="Submit">
					</div>

				</form>

			</div><!--/info body-->
		</div>
	</div>

	<!-- view -->
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">Academic Calendar</div>
			<div class="panel-body">

				<label>Academic Calendar List</label>
				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Year</th>
							<th>Trimester</th>
							<th>Trimester Start</th>
							<th>Trimester End</th>
							<th>Reg. Start</th>
							<th>Reg. End</th>
							<th>Midterm Exam Start</th>
							<th>Midterm Exam End</th>
							<th>Final Exam Start</th>
							<th>Final Exam End</th>
							<th>Trimester Break Start</th>
							<th>Trimester Break End</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($calender_list) && count($calender_list) > 0)
						@foreach($calender_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->academic_calender_year}}</td>
							<td>{{$list->semester_title}}</td>
							<td>{{$list->semester_start}}</td>
							<td>{{$list->semester_end}}</td>
							<td>{{$list->semester_course_reg_start}}</td>
							<td>{{$list->semester_course_reg_end}}</td>
							<td>{{$list->midterm_exam_start}}</td>
							<td>{{$list->midterm_exam_end}}</td>
							<td>{{$list->final_exam_start}}</td>
							<td>{{$list->final_exam_end}}</td>
							<td>{{$list->semester_break_start}}</td>
							<td>{{$list->semester_break_end}}</td>
							<td>
								<a href="{{url('/register/academic-calender/edit',$list->academic_calender_tran_code)}}" data-toggle="tooltip" title="Edit Calender"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a data-confirm-url="{{url('/register/academic-calender/delete',$list->academic_calender_tran_code)}}" data-toggle="tooltip" title="Delete Calender" class="confirm_box cursor"><i class="fa fa-trash-o"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
					<td colspan="14">
							<!-- empty message -->
							<div class="alert alert-success">
								<center><h3 style="font-style:italic">No Data Available !</h3></center>
							</div>
						</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($calender_pagination) ? $calender_pagination:""}}
		</div>
	</div>
</div>

</div>

@stop