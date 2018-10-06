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
	<div class="col-md-12 profile_tab">

		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
			<ul id="myTab" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="{{$tab=='add_exam_schedule' ? 'active':''}}"><a href="#add_exam_schedule" role="tab" id="add_exam_schedule-tab" data-toggle="tab" aria-controls="add_exam_schedule"><i class="fa fa-plus"></i>Add Exam Schedule</a></li>

				<li role="presentation" class="{{$tab=='download_exam_schedule' ? 'active':''}}"><a href="#download_exam_schedule" role="tab" id="download_exam_schedule-tab" data-toggle="tab" aria-controls="download_exam_schedule"><i class="fa fa-download"></i>Download Exam Schedule</a></li>

				<li role="presentation" class="{{$tab=='exam_invigilators' ? 'active':''}}"><a href="#exam_invigilators" role="tab" id="exam_invigilators-tab" data-toggle="tab" aria-controls="exam_invigilators"><i class="fa fa-pencil"></i>Exam Invigilators</a></li>

			</ul>


			<div id="myTabContent" class="tab-content">

				<div role="tabpanel" class="tab-pane fade in {{$tab=='add_exam_schedule' ? 'active':''}}" id="add_exam_schedule" aria-labelledby="add_exam_schedule-tab">


					<div class="row page_row">

						<div class="col-md-12 alert alert-info">
							<form method="get">
								<div class="form-group col-md-4">
									<label>Exam Type</label>
									<select class="form-control" name="exam_type">
										<option value="0">--Select Exam Type--</option>
										<option {{isset($_GET['exam_type']) && ($_GET['exam_type']=='2') ? 'selected' : ''}} value="2">Midterm Exam Schedule</option>
										<option {{isset($_GET['exam_type']) && ($_GET['exam_type']=='3') ? 'selected' : ''}} value="3">Final Exam Schedule</option>
									</select>
								</div>


								<div class="form-group col-md-4">
									<label for="Semester">Exam Date</label>
									<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
										<input class="form-control" name="exam_date"  size="16" value="{{isset($_GET['exam_date']) ? $_GET['exam_date'] : ''}}" type="text"  readonly>
										<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
								<div class="form-group col-md-4" style="margin-top:28px">
									<button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Search Schedule Availability">Search</button>
								</div>
							</form>


						</div>


						@if(isset($_GET['exam_type']) && (!empty($_GET['exam_type'])) && isset($_GET['exam_date']) && !empty($_GET['exam_date']))

						<table class="table table-bordered table-hover">

							<thead>
								<tr>
									<?php
									$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for', $exam_type)->orderBy('univ_time_slot','asc')->get();

									?>
									<th class="text-center">Room/Slot</th>
									@if(!empty($time_slots))
									@foreach($time_slots as $key => $slot_list)
									<th class="text-center">
										<table class="table-bordered " style="margin-bottom:-5px;width:100%;float:right;border:hidden;">
											<tr>
												<td colspan="2">{{$slot_list->univ_time_slot_slug}}</td>
											</tr>
											<tr>
												<td style="width:50%">SC</td>
												<td>Program</td>
											</tr>
										</table>
									</th>

									@endforeach
									@endif
								</tr>
							</thead>

							<tbody>
								<?php
								$room_list=\DB::table('univ_room')->get();
								?>

								@if(!empty($room_list))
								@foreach($room_list as $key => $rooms)
								<tr>
									<th class="text-center">{{$rooms->room_code}}</th>


									@if(!empty($time_slots))
									@foreach($time_slots as $key => $slot_list)
									<?php
									$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

									if(!empty($univ_academic_calender)){
										$existing_exam=\DB::table('univ_exam_schedule')
										->where('exam_schedule_type', $exam_type)
										->where('exam_schedule_date', $exam_date)
										->where('exam_schedule_room', $rooms->room_code)
										->where('exam_schedule_time_slot', $slot_list->univ_time_slot)
										->where('exam_schedule_semester', $univ_academic_calender->academic_calender_semester)
										->where('exam_schedule_year', $univ_academic_calender->academic_calender_year)
										->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
										->get();
									}

									?>
									@if(!empty($existing_exam))
									<td>
										<table class="table-bordered" style="margin-top:-5px;width:100%;float:right;border-top:hidden;border-left:hidden;border-right:hidden" >
											@foreach($existing_exam as $key => $course_list)
											<tr>
												<td style="width:50%">{{$course_list->exam_schedule_course}}</td>
												<td>{{$course_list->program_code}} 
													<a data-confirm-url="{{url('/register/exam/schedule-delete', $course_list->exam_schedule_tran_code)}}" class="confirm_box cursor" data-toggle="tooltip" title="Delete Schedule" style="float:right;"><i class="fa fa-minus-square"></i></a>
												</td>
											</tr>
											@endforeach
										</table>
										<button class="btn btn-primary btn-xs exam_schedule_modal" style="padding:0;" data-toggle="modal" data-target="#add_course" data-room="{{$rooms->room_code}}" data-time-slot="{{$slot_list->univ_time_slot}}" data-exam-type="{{$exam_type}}" data-exam-date="{{$exam_date}}" data-toggle="tooltip" title="Add Course For This Time Slot">Add Course</button>
									</td>
									@else
									<td>
										<button class="btn btn-primary btn-xs exam_schedule_modal" style="padding:0" data-toggle="modal" data-target="#add_course" data-room="{{$rooms->room_code}}" data-time-slot="{{$slot_list->univ_time_slot}}" data-exam-type="{{$exam_type}}" data-exam-date="{{$exam_date}}" data-toggle="tooltip" title="Add Course For This Time Slot">Add Course</button>
									</td>
									@endif
									@endforeach
									@endif

								</tr>
								@endforeach
								@endif
							</tbody>
						</table>




						<div class="modal fade" id="add_course" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="exampleModalLabel">Exam Schedule</h4>
									</div>

									<form action="{{url('/register/schedule/exam-schedule-submit')}}" method="post" enctype="multipart/form-data">
										<div class="ajax_exam_schedule_modal"></div>
									</form>


								</div>
							</div>
						</div>

						@else

						<div class="col-md-12 alert alert-success">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<center><h3 style="font-style:italic">No Data Found !</h3></center>
						</div>

						@endif

					</div>



				</div>


				<div role="tabpanel" class="tab-pane fade in {{$tab=='download_exam_schedule' ? 'active':''}}" id="download_exam_schedule" aria-labelledby="download_exam_schedule-tab">

					<div class="row page_row">
						<div class="col-md-12 alert alert-info">
							<div class="form-group col-md-3">
								<label>Select Exam Type</label>
								<select class="form-control exam_schedule_view exam_type">
									<option value="0">--Select Exam Type--</option>
									<option value="2">Trimester Midterm Exam Schedule</option>
									<option value="3">Trimester Final Exam Schedule</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Select Program</label>
								<select class="form-control exam_schedule_view program">
									<option value="0">--Select Program--</option>
									<option value="all">ALL</option>
									@if(!empty($program_list))
									@foreach($program_list as $key => $programs)
									<option value="{{$programs->program_id}}">{{$programs->program_title}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>Select Trimester</label>
								<select class="form-control exam_schedule_view trimester">
									<option value="0">--Select Trimester--</option>
									@if(!empty($semester_list))
									@foreach($semester_list as $key => $trimester)
									<option value="{{$trimester->semester_code}}">{{$trimester->semester_title}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="form-group col-md-2">
								<label>Select Year</label>
								<input type="text" value="{{date('Y')}}" class="form-control exam_schedule_view year" />
							</div>
							<div class="form-group col-md-1" >
								<a class="btn btn-default exam_schedule_pdf_download" style="margin-top:27px" data-toggle="tooltip" title="Download Exam Shcedule"><i class="fa fa-download"></i></a>
							</div>
						</div>

						<div class="ajax_exam_schedule_view">
							
						</div>

					</div>

				</div>



				<div role="tabpanel" class="tab-pane fade in {{$tab=='exam_invigilators' ? 'active':''}}" id="exam_invigilators" aria-labelledby="exam_invigilators-tab">

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
												<option {{(old('invigilators_exam_type')== "2") ? "selected" :''}}  value="2">Mid Term Exam</option>
												<option {{(old('invigilators_exam_type')== "3") ? "selected" :''}} value="3">Final Exam</option>
											</select>
										</div>

										<div class="form-group">
											<label for="Exam Time Slot">Exam Time Slot <span class="required-sign">*</span></label>
											<select class="form-control time_slot" name="invigilators_exam_time_slot">


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
								<div class="panel-heading">Exam Invigilators List <a href="{{url('/register/exam/invigilators-download')}}" class="btn btn-default pull-right" style="padding-top:0;padding-bottom:0" data-toggle="tooltip" title="Download Exam Invigilator List"><i class="fa fa-download" aria-hidden="true"></i></a></div>
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
												<td>
													@if($list->invigilators_exam_type=='2')
													Trimester Midterm
													@elseif($list->invigilators_exam_type=='3')
													Trimester Final
													@endif
												</td>
												<td>{{$list->semester_title}}</td>
												<td>{{$list->invigilators_ID}}</td>
												<td>{{$list->invigilators_exam_room}}</td>
												<td>{{$list->invigilators_exam_time_slot}}</td>
												<td>
													<a href="{{url('/register/exam/invigilators/edit/'.$list->invigilators_exam_tran_code)}}" data-toggle="tooltip" title="Edit Invigilator"><i class="fa  fa-pencil-square-o"></i></a>
													<a data-confirm-url="{{url('/register/exam/invigilators/delete/'.$list->invigilators_exam_tran_code)}}" data-toggle="tooltip" title="Delete Invigilator" class="confirm_box cursor"><i class="fa  fa-trash-o"></i></a>
												</a>
											</td>
										</tr>
										@endforeach
										@else
										<tr class="text-center">
											<td colspan="8">No Data available</td>
										</tr>
										@endif
									</tbody>
								</table>
								{{isset($pagination) ? $pagination:""}}
							</div>
						</div>
					</div>

				</div>


			</div> 
			<!-- invigilators end -->




		</div>

	</div>
</div>
</div>


@stop

