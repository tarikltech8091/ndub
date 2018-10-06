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
				<li role="presentation" class="{{$tab=='add_class_schedule' ? 'active':''}}"><a href="#add_class_schedule" role="tab" id="add_class_schedule-tab" data-toggle="tab" aria-controls="add_class_schedule"><i class="fa fa-plus"></i>Add CLass Schedule</a></li>

				<li role="presentation" class="{{$tab=='full_schedule' ? 'active':''}}"><a href="#full_schedule" id="full_schedule-tab" role="tab" data-toggle="tab" aria-controls="full_schedule" aria-expanded="true"><i class="fa fa-download" aria-hidden="true"></i>Download Schedule</a></li>

			</ul>


			<div id="myTabContent" class="tab-content">

				<div role="tabpanel" class="tab-pane fade in {{$tab=='add_class_schedule' ? 'active':''}}" id="add_class_schedule" aria-labelledby="add_class_schedule-tab">
					

					<div class="row page_row">

						<div class="col-md-4">
							<div class="panel panel-info">
								<div class="panel-heading">Add Class Schedule</div>
								<div class="panel-body"><!--info body-->

									<form action="{{url('/register/class-schedule-submit')}}" method="post" enctype="multipart/form-data">
										<input type="hidden" name="_token" value="{{csrf_token()}}" />
										<div class="form-group">
											<label>Select Program</label>
											<select class="form-control building_code program_code" name="class_schedule_program">
												<option value="0">--Select Program--</option>
												@if(!empty($program_list))
												@foreach($program_list as $key => $list)
												<option value="{{$list->program_id}}">{{$list->program_title}}</option>
												@endforeach
												@endif
											</select>
										</div>

										<div class="form-group">
											<label>Course Code</label>
											<select name="class_schedule_course" class="form-control ajax_course_list">
												<option value="0">--Select Course--</option>
											</select>
										</div>

										<div class="form-group">
											<label>Select Building</label>
											<select class="form-control building_code">
												<option value="0">--Select Building--</option>
												@if(!empty($building_list))
												@foreach($building_list as $key => $list)
												<option value="{{$list->building_code}}">{{$list->building_title}}</option>
												@endforeach
												@endif
											</select>
										</div>

										<div class="ajax_room_list">

										</div>
									</form>




								</div><!--/info body-->
							</div>
						</div>
						<div class="col-md-8">
							<div class="panel panel-info">
								<div class="panel-heading">Schedule List By Room</div>
								<div class="panel-body">

									<div class="col-md-4 col-md-offset-4">
										<?php
										$room_list=\DB::table('univ_room')->get();
										?>
										<select class="form-control class_schedule_view" name="room_code">
											<option >--Select Room--</option>
											@if(!empty($room_list))
											@foreach($room_list as $key => $list)
											<option value="{{$list->room_code}}">{{$list->room_code}}</option>
											@endforeach
											@endif
										</select>
									</div>

									<div class="ajax_class_schedule_view" >

									</div>

								</div>
							</div>
						</div>

					</div>



				</div>


				<div role="tabpanel" class="tab-pane fade in {{$tab=='full_schedule' ? 'active':''}}" id="full_schedule" aria-labelledby="full_schedule-tab"  style="overflow-x: auto;">

					<div class="page_row">
						<div class="col-md-4 col-md-offset-4">
							<?php
							$program_list=\DB::table('univ_program')->get();
							?>

							<select class="form-control schedule_by_program" name="program">
								<option value="0">All Schedule</option>
								@if(!empty($program_list))
								@foreach($program_list as $key => $list)
								<option value="{{$list->program_id}}">{{$list->program_title}}</option>
								@endforeach
								@endif
							</select>
						</div>

						<div class="col-md-1">
							<span class="btn btn-warning schedule_pdf_download" data-toggle="tooltip" data-placement="bottom" title="Download Schedule"><i class="fa fa-print"></i></span>
						</div>
					</div>

					
					<div class="page_row schedule_table" style="margin-top:70px">

						<div class="ajax_schedule_by_program_view">

							<table class="table table-bordered" style="color:green">
								<thead >

									<?php 
									$i=1;  
									$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
									?>

									<tr>
										<th>Day <i class="fa fa-long-arrow-right" style="color:green" aria-hidden="true"></i></th>
										@for($i=0;$i<=6;$i++)
										<th colspan="3">{{$days[$i]}}</th>
										@endfor

									</tr>
									<tr>
										<th>Time <i class="fa fa-long-arrow-down" style="color:green" aria-hidden="true"></i></th>
										@for($i=0;$i<=6;$i++)
										<th>SC</th>
										<th>FC</th>
										<th>RC</th>
										@endfor
									</tr>
								</thead>

								<tbody>
									<?php
									$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for',1)->orderBy('univ_time_slot','asc')->get();

									$i=1;  
									$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');

									?>
									@if(!empty($time_slots))
									@foreach($time_slots as $key => $list)

									<tr>
										<th>{{$list->univ_time_slot_slug}}</th>

										@for($i=0;$i<=6;$i++)

										<td colspan="3">
											<?php

											$day=$days[$i];
											$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

											if(!empty($univ_academic_calender)){

												if(!empty($program_id)){

													$existing_class=\DB::table('univ_class_schedule')
													->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
													->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
													->where('class_schedule_program',$program_id)
													->where('class_schedule_day_of_week',$day)
													->where('class_schedule_time_slot',$list->univ_time_slot_slug)
													->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
													->get();

												}else{
													$existing_class=\DB::table('univ_class_schedule')
													->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
													->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
													->where('class_schedule_day_of_week',$day)
													->where('class_schedule_time_slot',$list->univ_time_slot_slug)
													->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
													->get();
												}

											}

											?>

											<table class="table table-bordered" style="margin-top:-5px;border-top:hidden;margin-bottom:-5px;border-bottom:hidden;margin-left:-5px;border-left:hidden;border-right:hidden;margin-right:-5px" >

												@if(!empty($existing_class))
												@foreach($existing_class as $key => $schedule_list)


												<tr style="text-align:center">
													<td style="width:33%;">{{$schedule_list->class_schedule_course}}</td>
													<td style="width:33%;">{{strtoupper(substr($schedule_list->first_name,0,1))}}{{strtoupper(substr($schedule_list->middle_name,0,1))}}{{strtoupper(substr($schedule_list->last_name,0,1))}}</td>
													<td style="width:33%">{{$schedule_list->class_schedule_room}}</td>
												</tr>

												@endforeach

												@endif
											</table>

										</td>

										@endfor

									</tr>

									@endforeach
									@endif


								</tbody>
							</table>
						</div>

					</div>

				</div>



			</div>

		</div>
	</div>
</div>



@stop