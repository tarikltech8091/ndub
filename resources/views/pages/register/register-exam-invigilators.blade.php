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
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Exam Invigilators Registration</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/register/exam/invigilators/submit')}}" method="post" enctype="multipart/form-data">

					<div class="form-group">
			         <label for="Exam Date">Exam Date<span class="required-sign">*</span></label>
						<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="invigilators_exam_date" size="16" type="text" value="" readonly>
							<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
			        </div>

			        <div class="form-group">
						<label for="Exam Type">Exam Type<span class="required-sign">*</span></label>
							<select class="form-control time_slot_list" name="invigilators_exam_type">
								<option value="">Select Exam Type</option>
								<option {{(old('invigilators_exam_type')== "Mid") ? "selected" :''}}  value="Mid">Mid Term Exam</option>
								<option {{(old('invigilators_exam_type')== "Final") ? "selected" :''}} value="Final">Final Exam</option>
							</select>
					</div>

					<?php 
						$time_slot_list = \DB::table('univ_time_slot')
										// ->where('univ_time_slot_for','=','2')
										->get();
					?>
					<div class="form-group">
						<label for="Exam Time Slot">Exam Time Slot <span class="required-sign">*</span></label>
						<select class="form-control time_slot" name="invigilators_exam_time_slot">

							@foreach($time_slot_list as $key => $faculties)
							<option value="{{$faculties->univ_time_slot_slug}}">{{$faculties->univ_time_slot_slug}} </option>
							@endforeach

						</select> 

					</div>


					<?php 
					$faculty_list = \DB::table('faculty_basic')->select('faculty_basic.*')->get();
					?>
					<div class="form-group">
						<label for="Invigilators">Invigilators <span class="required-sign">*</span></label>
						<select name="invigilators_ID[]" style="width:100%;" class="multipleSelectExample" data-placeholder="Select Invigilators ID" multiple>
							@foreach($faculty_list as $key => $faculties)
							<option value="{{$faculties->faculty_id}}">{{$faculties->faculty_id}} {{$faculties->first_name}} {{$faculties->last_name}}</option>
							@endforeach
						</select>
						
					</div>


				<div class="form-group">
					<label for="Semester">Exam Trimester<span class="required-sign">*</span></label>
					<?php
					$semester_list=\DB::table('univ_semester')->get();
					?>
					<select class="form-control" name="invigilators_exam_semester" >
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						@if(isset($invigilators_exam_semester))
						<option {{(isset($list) && ($list->semester_code==$list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>

						@else
						<option value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endif

						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group">
					<label for="AcademicYear">Exam Year<span class="required-sign">*</span></label>
					<select class="form-control" name="invigilators_exam_year" >
						<option {{(isset($invigilators_exam_year) && ($invigilators_exam_year==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
						<option {{(isset($invigilators_exam_year) && ($invigilators_exam_year==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
					</select>
				</div>


					<?php 
					$room_list = \DB::table('univ_room')->select('univ_room.*')->get();
					?>
					<div class="form-group">
						<label for="University Room">University Room <span class="required-sign">*</span></label>
						<select class="form-control" name="invigilators_exam_room">
							@if(!empty($room_list))
							@foreach($room_list as $key => $list)
							<option {{(old('invigilators_exam_room')== $list->room_code) ? "selected" :''}} value="{{$list->room_code}}">{{$list->room_code}}</option>
							@endforeach
							@endif

						</select> 
					</div>




					<div class="form-group pull-right">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="reset" class="btn btn-default" value="Reset">
						<input type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>

			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Exam Invigilators List</div>
			<div class="panel-body">
				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Exam Date</th>
							<th>Exam Type</th>
							<th>Semester</th>
							<th>Invigilator ID</th>
							<th>Room</th>
							<th>Time</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($univ_invigilators_exam) && count($univ_invigilators_exam) > 0)
						@foreach($univ_invigilators_exam as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->invigilators_exam_date}}</td>
							<td>{{$list->invigilators_exam_type}}</td>
							<td>{{$list->semester_title}}</td>
							<td>{{$list->invigilators_ID}}</td>
							<td>{{$list->invigilators_exam_room}}</td>
							<td>{{$list->invigilators_exam_time_slot}}</td>
							<td>
								<a href="{{url('/register/exam/invigilators/edit/'.$list->invigilators_exam_tran_code)}}"><i class="fa  fa-pencil-square-o"></i></a>
								<a href="{{url('/register/exam/invigilators/delete/'.$list->invigilators_exam_tran_code)}}"><i class="fa  fa-trash-o"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="7">No Data available</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>


@stop