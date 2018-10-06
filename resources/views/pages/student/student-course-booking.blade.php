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
		<!--**************End of error message****************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-9">

		@if(!empty($preadvising) && ($preadvising->pre_advising_status=='0'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				<form method="post" action="{{url('/student/pre-advising-submit')}}" enctype="multipart/form-data">
					<div style="height: 250px; overflow: auto;">

						<table class="table table-bordered table-hover">
							
							<thead>
								<tr>
									<th>Course ID</th>
									<th>Course Titile</th>
									<th>Credit</th>
									<th><!-- <input type="checkbox" class="checkAll" /> --> Action</th>
								</tr>
							</thead>

							<tbody>
									@if(!empty($preadvising_courses))
									<tr><th colspan="4" class="text-center">Course for current trimester</th></tr>
									@foreach($preadvising_courses as $key => $courses)
									<?php
									$student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();

									$tabulation_passed_courses=\DB::table('student_academic_tabulation')
									->where('student_serial_no', $student_basic->student_serial_no)
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


									<!-- ########### 10-12-2017 ################# -->

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
									<th colspan="3">Taken Credit</th>
									<th>
										<input type="text" value="0.0" class="pre_advising_total_credit" name="credit_taken" style="width:20px; border:none;" readonly="">
									</th>
								</tr>
								<tr>
									<td colspan="4">
										<input type="hidden" name="_token" value="{{csrf_token()}}" />
										<input type="hidden" class="pre_advising_total_credit" name="credit_taken">
										<input type="hidden" name="term" value="{{$term}}" />
										<input type="hidden" name="level" value="{{$level}}" />
										<button type="submit" data-loading-text="Saving..." class="btn btn-success pull-right loadingButton">Submit</button>
									</td>
								</tr>
						</tbody>
					</table>
				</form>


			</div>
		</div>



		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='1'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				@if(!empty($temp_preadvising_detail))
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


						@foreach($temp_preadvising_detail as $key => $courses)
						@foreach($courses as $key => $list)
						<tr>
							<td>{{$list['temp_course_code']}}</td>
							<td>{{$list['temp_course_title']}}</td>
							<td>{{number_format($list['temp_credit_hours'],1,'.','')}}</td>
							<td>Waiting for Aproval</td>
						</tr>
						@endforeach
						@endforeach



					</tbody>
				</table>
				@else
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Course Available !</h3></center>
				</div>
				@endif

			</div>
		</div>


		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='2'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				@if(!empty($temp_preadvising_detail))
				<form action="{{url('/student/pre-advising-payment')}}" method="post" enctype="multipart/form-data">
					<div style="height: 250px; overflow: auto;">
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

									@foreach($temp_preadvising_detail as $key => $courses)
									@foreach($courses as $key => $list)
									<tr>
										<td>{{$list['temp_course_code']}}</td>
										<td>{{$list['temp_course_title']}}</td>
										<td>{{number_format($list['temp_credit_hours'],1,'.','')}}</td>
										<td>Advised by Faculty</td>
									</tr>
									<input type="hidden" name="course_code[]" value="{{$list['temp_course_code']}}" />
									<input type="hidden" name="credit_hours[]" value="{{number_format($list['temp_credit_hours'],1,'.','')}}" />
									@endforeach
									@endforeach

									<tr>
										<th colspan="2">Total Credit Taken: </th>
										<th colspan="1">{{isset($preadvised_courses->temp_preadvising_total_credit) ? $preadvised_courses->temp_preadvising_total_credit : ''}}</th>

										<th colspan="1">

										<button type="submit" data-loading-text="Saving..." class="btn btn-warning btn-sm loadingButton"><i class="fa fa-forward" aria-hidden="true"></i> Proced-Registration</button>

										<input type="hidden" name="_token" value="{{csrf_token()}}" />
										<input type="hidden" name="term" value="{{$term}}" />
										<input type="hidden" name="level" value="{{$level}}" />

											<a href="#myModal" role="button" class="btn btn-primary btn-xm preadvising_resubmit" data-temptrancode="{{$preadvised_courses->temp_preadvising_tran_code}}" data-level="{{$level}}" data-term="{{$term}}" data-toggle="modal">Re-Submit</a>
										</th>
									</tr>



							</tbody>
						</table>
					</div>
				</form>

				@else
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Course Available !</h3></center>
				</div>
				@endif

			</div>
		</div>


		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='3'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				@if(!empty($temp_preadvising_detail))
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


						@foreach($temp_preadvising_detail as $key => $courses)
						@foreach($courses as $key => $list)
						<tr>
							<td>{{$list['temp_course_code']}}</td>
							<td>{{$list['temp_course_title']}}</td>
							<td>{{number_format($list['temp_credit_hours'],1,'.','')}}</td>
							<td>Pre-Advising Re Submitted</td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<th colspan="2">Total Credit Taken: </th>
							<th colspan="2">{{isset($preadvised_courses->temp_preadvising_total_credit) ? $preadvised_courses->temp_preadvising_total_credit : ''}}</th>
						</tr>


					</tbody>
				</table>
				@else
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Course Available !</h3></center>
				</div>
				@endif
			</div>
		</div>


		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='4'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				@if(!empty($temp_preadvising_detail))
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

						<form action="{{url('/student/pre-advising-payment')}}" method="post" enctype="multipart/form-data">
							@foreach($temp_preadvising_detail as $key => $courses)
							@foreach($courses as $key => $list)
							<tr>
								<td>{{$list['temp_course_code']}}</td>
								<td>{{$list['temp_course_title']}}</td>
								<td>{{number_format($list['temp_credit_hours'],1,'.','')}}</td>
								<td>Re-Advised by Faculty</td>
							</tr>
							<input type="hidden" name="course_code[]" value="{{$list['temp_course_code']}}" />
							<input type="hidden" name="credit_hours[]" value="{{$list['temp_credit_hours']}}" />
							@endforeach
							@endforeach
							<tr>
								<th colspan="2">Total Credit Taken: </th>
								<th colspan="1">{{number_format($preadvised_courses->temp_preadvising_total_credit,1,'.','')}}</th>

								<th colspan="1">

									<button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-forward" aria-hidden="true"></i> Proced-Registration</button>

									<input type="hidden" name="_token" value="{{csrf_token()}}" />
									<input type="hidden" name="term" value="{{$term}}" />
									<input type="hidden" name="level" value="{{$level}}" />
									<input type="hidden" name="action" value="payment_checkout" />
									</form>

									</th>
								</tr>



					</tbody>
				</table>
				@else
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Course Available !</h3></center>
				</div>
				@endif
			</div>
		</div>


		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='5'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking">
				
				<table class="table table-bordered table-hover">

					<thead>
						<tr>
							<th>Course ID</th>
							<th>Course Titile</th>
							<th>Credit</th>
							<th>Faculty</th>
						</tr>
					</thead>

					<tbody>
						@if(!empty($registered_class_course) || !empty($registered_lab_course))

						@if(!empty($registered_class_course))
						@foreach($registered_class_course as $key => $list)
						<tr>
							<td>{{$list->course_code}}</td>
							<td>{{$list->course_title}}</td>
							<td>{{number_format($list->credit_hours,1,'.','')}}</td>
							<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
						</tr>
						@endforeach
						@endif

						@if(!empty($registered_lab_course))
						@foreach($registered_lab_course as $key => $list)
						<tr>
							<td>{{$list->course_code}}</td>
							<td>{{$list->course_title}}</td>
							<td>{{number_format($list->credit_hours,1,'.','')}}</td>
							<td>{{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</td>
						</tr>
						@endforeach
						@endif

						<tr>
							<th colspan="2">Total Credit Taken: </th>
							<th colspan="1">{{number_format($preadvised_courses->temp_preadvising_total_credit,1,'.','')}}</th>
							<th colspan="1">
								<?php

								$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

								$class_teacher=\DB::table('program_coordinator_assigned')->where('program_coordinator_level', $level)->where('program_coordinator_term', $term)->where('coordinator_program',$preadvising->program)->where('program_coordinator_semester', $univ_academic_calender->academic_calender_semester)->where('program_coordinator_year', $univ_academic_calender->academic_calender_year)
								->leftjoin('faculty_basic','faculty_basic.faculty_id','=','program_coordinator_assigned.coordinator_faculty_id')->first();
								?>
								Class Teacher: 
								@if(!empty($class_teacher))
								{{$class_teacher->first_name}} {{$class_teacher->middle_name}} {{$class_teacher->last_name}}
								@endif

							</th>
						</tr>
						@else
						<tr>
							<td colspan="4">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Course Available !</h3></center>
								</div>
							</td>
						</tr>

						@endif


					</tbody>
				</table>
				
			</div>
		</div>


		@elseif(!empty($preadvising) && ($preadvising->pre_advising_status=='6'))
		<div class="panel panel-info">
			<div class="panel-heading">Course Pre-advising</div>
			<div class="panel-body course_booking"><!--info body-->

				<div class="alert alert-success">
					<center><h3 style="font-style:italic">Pre-advising will start very soon for new trimester... !</h3></center>
				</div>

			</div>
		</div>

		@else
		<div class="panel panel-info">
			<div class="panel-heading">Course Pre-advising</div>
			<div class="panel-body course_booking"><!--info body-->

				<div class="alert alert-success">
					<center><h3 style="font-style:italic">You are not assigned for this Trimester yet !</h3></center>
				</div>

			</div>
		</div>
		@endif


		<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="resubmit_form">

					</div>
				</div>
			</div>
		</div>




	</div>
	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>
</div>


@stop