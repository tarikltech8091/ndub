@extends('layout.master')
@section('content')

@include('layout.bradecrumb')
<div class="row page_row">

	<div class="col-md-9">
		<?php
		$tran_code=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->select('student_tran_code')->first();

		$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

		$study_level=\DB::table('student_study_level')->where('student_tran_code',$tran_code->student_tran_code)->where('study_level_status', 1)
		->where('study_level_semester', $univ_academic_calender->academic_calender_semester)
        ->where('study_level_year', $univ_academic_calender->academic_calender_year)
        ->first();

		$student_study_level=\DB::table('student_study_level')->where('student_tran_code',$tran_code->student_tran_code)->get();
		$count=0;
		foreach ($student_study_level as $key => $student_study) {
			$count=$count+1;
		}


		if(!empty($study_level)){
			$semester=\DB::table('univ_semester')->where('semester_code',$study_level->study_level_semester)->select('semester_title')->first();

		$term=(int)$count%3;
		if($term==0){
			$term=3;
		}
		$level=ceil((int)$count/3);

		

		}

		?>
		@if(!empty($study_level) && ($study_level->pre_advising_status=='0'))
		<div class="panel panel-info">
			<div class="panel-heading">Course</div>
			<div class="panel-body course_booking"><!--info body-->

				<table id="" class="table table-striped table-bordered table-hover">
					@if(Session::has('message'))
					<div class="alert alert-warning alert-dismissible" role="alert" style="margin-top:10px">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ Session::get('message') }}
					</div>
					@endif
					<thead>
						<tr>
							<th colspan="3">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
							<th colspan="3" class="text-right">Credit Taken: <input type="text" value="0" class="pre_advising_total_credit" name="credit_taken" style="width:20px; border:none;" readonly=""></th>
						</tr>
						<tr>
							<th>Course ID</th>
							<th>Course Titile</th>
							<th>Credit</th>
							<th>Book</th>
						</tr>
					</tr>
				</thead>
				<tbody>

					@if(!empty($pre_advising))
					<?php 
					$total_credit = 0;
					?>
					@foreach($pre_advising as $key => $pre_advising)
					@if(($level==$pre_advising->level) && ($term==$pre_advising->term))
					<tr>
						<?php 
						$total_credit = $total_credit+$pre_advising->credit_hours;
						?>
						<td>{{$pre_advising->course_code}}</td>
						<td>{{$pre_advising->course_title}}</td>
						<td>{{$pre_advising->credit_hours}}</td>
						<form action="{{url('/student/pre-advising-submit',$pre_advising->student_tran_code)}}" method="post" enctype="multipart/form-data">
							<td>
								<input type="checkbox" credit="{{$pre_advising->credit_hours}}" name="pre_advising_selected_checkbox[]" class="check" value="{{$pre_advising->course_slug}}">
								<input type="hidden" class="pre_advising_total_credit" name="credit_taken">
							</td>
						</tr>
						@endif
						@endforeach

						<tr>
							<th colspan="2">Total Credit</th>
							<th>
								<span>{{$total_credit}}</span>
							</th>
							<th>
								<input type="hidden" name="_token" value="{{csrf_token()}}" />
								<input type="hidden" name="term" value="{{$term}}" />
								<input type="hidden" name="level" value="{{$level}}" />
								<button class="btn btn-default">Cancel</button>
								<button type="submit" class="btn btn-success">Submit</button>
							</th>
						</tr>
						@else
						<h3>No Course Available !</h3>
						@endif

						
					</tbody>
					<input type="hidden" name="action" value="pre_advising" />
				</form>
			</table>
		</div><!--/info body-->
	</div>


	@elseif(!empty($study_level) && ($study_level->pre_advising_status=='1'))
	<div class="panel panel-info">
		<div class="panel-heading">Course</div>
		<div class="panel-body course_booking"><!--info body-->

			<table id="" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th colspan="5">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
					</tr>
					<tr>
						<th>Course ID</th>
						<th>Course Titile</th>
						<th>Credit</th>
						<th>Status</th>
					</tr>
				</tr>
			</thead>
			<tbody>
				<?php 
				$temp_preadvising=\DB::table('temp_preadvising')->where('student_tran_code',$tran_code->student_tran_code)->where('temp_preadvising_status',1)->first();
				$temp_preadvising_courses[]=unserialize($temp_preadvising->temp_preadvising_detail);
				?>

				@if(!empty($temp_preadvising) && !empty($temp_preadvising_courses))
				@foreach($temp_preadvising_courses as $key => $courses)

				@foreach($courses as $key => $course_data)
				<tr>
					<td>{{$course_data['temp_course_code']}}</td>
					<td>{{$course_data['temp_course_title']}}</td>
					<td>{{$course_data['temp_credit_hours']}}</td>
					<td><span>Waiting For Approval</span></td>
				</tr>
				@endforeach
				@endforeach
				@endif

				<tr>
					<th colspan="2"><span>Credit Taken</span></th>
					<th colspan="3"><span>{{$temp_preadvising->temp_preadvising_total_credit}}</span></th>
				</tr>

			</tbody>
		</table>
	</div><!--/info body-->
</div>

@elseif(!empty($study_level) && ($study_level->pre_advising_status=='2'))
<div class="panel panel-info">
	<div class="panel-heading">Course</div>
	<div class="panel-body course_booking"><!--info body-->

		<table id="" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="5">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
				</tr>
				<tr>
					<th>Course ID</th>
					<th>Course Titile</th>
					<th>Credit</th>
					<th>Status</th>
				</tr>
			</tr>
		</thead>
		<tbody>
			<?php 
			// $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

			$temp_preadvising=\DB::table('temp_preadvising')->where('student_tran_code',$tran_code->student_tran_code)
			->where('temp_preadvising_status',2)
			// ->where('assigned_course_semester', $univ_academic_calender->academic_calender_semester)
			// ->where('assigned_course_year', $univ_academic_calender->academic_calender_year)
			->first();
			$temp_preadvising_courses[]=unserialize($temp_preadvising->temp_preadvising_detail);
			?>


			@if(!empty($temp_preadvising) && !empty($temp_preadvising_courses))
			<form action="{{url('/student/pre-advising-payment',$tran_code->student_tran_code)}}" method="post" enctype="multipart/form-data">
			@foreach($temp_preadvising_courses as $key => $courses)

			@foreach($courses as $key => $course_data)
			<tr>
				<td>{{$course_data['temp_course_code']}}</td>
				<td>{{$course_data['temp_course_title']}}</td>
				<td>{{$course_data['temp_credit_hours']}}</td>
				<td><span>Advised by Faculty</span></td>
			</tr>
			<input type="hidden" name="course_code[]" value="{{$course_data['temp_course_code']}}" />
			<input type="hidden" name="credit_hours[]" value="{{$course_data['temp_credit_hours']}}" />
			@endforeach
			@endforeach
			@endif

			<tr>
				<th colspan="2"><span>Credit Taken</span></th>
				<th><span>{{$temp_preadvising->temp_preadvising_total_credit}}</span></th>
				<th>
					
					<button type="submit" class="btn btn-warning btn-sm">Proced-Payment</button>

					<input type="hidden" name="_token" value="{{csrf_token()}}" />
					<input type="hidden" name="semester" value="{{$study_level->study_level_semester}}" />
					<input type="hidden" name="year" value="{{$study_level->study_level_year}}" />
					<input type="hidden" name="action" value="payment_checkout" />
					<form>
					
					<button type="button" class="btn btn-primary btn-sm resubmit">Re-Submit</button>
				</th>
			</tr>

		</tbody>
	</table>



	<table id="resubmit_form" class="table table-striped table-bordered table-hover" style="margin-top:30px;" hidden>
		@if(Session::has('message'))
		<div class="alert alert-warning alert-dismissible" role="alert" style="margin-top:10px">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ Session::get('message') }}
		</div>
		@endif
		<thead>
			<tr>
				<th style="background-color:#d6dbdf" colspan="4">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}} <span class="pull-right">Credit Taken: <input type="text" value="0" class="pre_advising_total_credit" name="credit_taken" style="width:20px; border:none;" readonly=""></span></th>
			</tr>
			<tr>
				<th>Course ID</th>
				<th>Course Titile</th>
				<th>Credit</th>
				<th>Book</th>
			</tr>
		</tr>
	</thead>
	<tbody>

		@if(!empty($pre_advising))
		<?php 
		$total_credit = 0;
		?>
		@foreach($pre_advising as $key => $pre_advising)
		@if(($level==$pre_advising->level) && ($term==$pre_advising->term))
		<tr>
			<?php 
			$total_credit = $total_credit+$pre_advising->credit_hours;
			?>
			<td>{{$pre_advising->course_code}}</td>
			<td>{{$pre_advising->course_title}}</td>
			<td>{{$pre_advising->credit_hours}}</td>
			<form action="{{url('/student/pre-advising-submit',$pre_advising->student_tran_code)}}" method="post" enctype="multipart/form-data">
				<td>
					<input type="checkbox" credit="{{$pre_advising->credit_hours}}" name="pre_advising_selected_checkbox[]" class="check" value="{{$pre_advising->course_slug}}">
					<input type="hidden" class="pre_advising_total_credit" name="credit_taken">
				</td>
			</tr>
			@endif
			@endforeach

			<tr>
				<th colspan="2">Total Credit</th>
				<th>
					<span>{{$total_credit}}</span>
				</th>
				<th>
					<input type="hidden" name="level" value="{{$level}}" />
					<input type="hidden" name="term" value="{{$term}}" />
					<input type="hidden" name="_token" value="{{csrf_token()}}" />
					<button class="btn btn-default">Cancel</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</th>
			</tr>
			@else
			<h3>No Course Available !</h3>
			@endif


		</tbody>
		<input type="hidden" name="action" value="pre_advising_resubmit" />
	</form>
</table>

</div><!--/info body-->
</div>
@elseif(!empty($study_level) && ($study_level->pre_advising_status=='3'))
	<div class="panel panel-info">
		<div class="panel-heading">Course</div>
		<div class="panel-body course_booking"><!--info body-->

			<table id="" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th colspan="5">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
					</tr>
					<tr>
						<th>Course ID</th>
						<th>Course Titile</th>
						<th>Credit</th>
						<th>Status</th>
					</tr>
				</tr>
			</thead>
			<tbody>
				<?php 
				$temp_preadvising=\DB::table('temp_preadvising')->where('student_tran_code',$tran_code->student_tran_code)->first();
				$temp_preadvising_courses[]=unserialize($temp_preadvising->temp_preadvising_detail);
				?>

				@if(!empty($temp_preadvising) && !empty($temp_preadvising_courses))
				@foreach($temp_preadvising_courses as $key => $courses)

				@foreach($courses as $key => $course_data)
				<tr>
					<td>{{$course_data['temp_course_code']}}</td>
					<td>{{$course_data['temp_course_title']}}</td>
					<td>{{$course_data['temp_credit_hours']}}</td>
					<td><span>Pre-advising Re Submitted</span></td>
				</tr>
				@endforeach
				@endforeach
				@endif

				<tr>
					<th colspan="2"><span>Credit Taken</span></th>
					<th colspan="3"><span>{{$temp_preadvising->temp_preadvising_total_credit}}</span></th>
				</tr>

			</tbody>
		</table>
	</div><!--/info body-->
</div>
@elseif(!empty($study_level) && ($study_level->pre_advising_status=='4'))

<div class="panel panel-info">
	<div class="panel-heading">Course</div>
	<div class="panel-body course_booking"><!--info body-->

		<table id="" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="5">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
				</tr>
				<tr>
					<th>Course ID</th>
					<th>Course Titile</th>
					<th>Credit</th>
					<th>Status</th>
				</tr>
			</tr>
		</thead>
		<tbody>
			<?php 
			$temp_preadvising=\DB::table('temp_preadvising')->where('student_tran_code',$tran_code->student_tran_code)->where('temp_preadvising_level',$level)->where('temp_preadvising_term',$term)->where('temp_preadvising_status',4)->first();
			$temp_preadvising_courses[]=unserialize($temp_preadvising->temp_preadvising_detail);
			?>

			<form action="{{url('/student/pre-advising-payment',$tran_code->student_tran_code)}}" method="post" enctype="multipart/form-data">
			@if(!empty($temp_preadvising) && !empty($temp_preadvising_courses))
			@foreach($temp_preadvising_courses as $key => $courses)

			@foreach($courses as $key => $course_data)

			<tr>
				<td>{{$course_data['temp_course_code']}}</td>
				<td>{{$course_data['temp_course_title']}}</td>
				<td>{{$course_data['temp_credit_hours']}}</td>
				<td><span>Pre-Advised Courses</span></td>
			</tr>
			<input type="hidden" name="course_code[]" value="{{$course_data['temp_course_code']}}" />
			<input type="hidden" name="credit_hours[]" value="{{$course_data['temp_credit_hours']}}" />
			@endforeach
			@endforeach
			@endif

			<tr>
				<th colspan="2"><span>Credit Taken</span></th>
				<th><span>{{$temp_preadvising->temp_preadvising_total_credit}}</span></th>
				<th>
					<button type="submit" class="btn btn-warning btn-sm">Proced-Payment</button>
				</th>
			</tr>
			<input type="hidden" name="semester" value="{{$study_level->study_level_semester}}" />
			<input type="hidden" name="year" value="{{$study_level->study_level_year}}" />

			<input type="hidden" name="_token" value="{{csrf_token()}}" />
			<input type="hidden" name="action" value="payment_checkout" />
			</form>
		</tbody>
	</table>

</div><!--/info body-->
</div>


@elseif(!empty($study_level) && ($study_level->pre_advising_status=='5'))

<div class="panel panel-info">
	<div class="panel-heading">Course</div>
	<div class="panel-body course_booking"><!--info body-->

		<table id="" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="5">Trimester: {{$semester->semester_title}} {{$study_level->study_level_year}}</th>
				</tr>
				<tr>
					<th>Course ID</th>
					<th>Course Titile</th>
					<th>Credit</th>
					<th>Status</th>
				</tr>
			</tr>
		</thead>
		<tbody>
			<?php 
			$temp_preadvising=\DB::table('temp_preadvising')->where('student_tran_code',$tran_code->student_tran_code)->where('temp_preadvising_level',$level)->where('temp_preadvising_term',$term)->where('temp_preadvising_status',5)->first();
			$temp_preadvising_courses[]=unserialize($temp_preadvising->temp_preadvising_detail);
			?>

			@if(!empty($temp_preadvising) && !empty($temp_preadvising_courses))
			@foreach($temp_preadvising_courses as $key => $courses)

			@foreach($courses as $key => $course_data)

			<tr>
				<td>{{$course_data['temp_course_code']}}</td>
				<td>{{$course_data['temp_course_title']}}</td>
				<td>{{$course_data['temp_credit_hours']}}</td>
				<td><span>Registration Complete</span></td>
			</tr>
			@endforeach
			@endforeach
			@endif

			<tr>
				<th colspan="2"><span class="pull-right">Credit Taken</span>
				</th>
				<th colspan="2"><span class="pull-left">{{$temp_preadvising->temp_preadvising_total_credit}}</span></th>
			</tr>
		</tbody>
	</table>

</div><!--/info body-->
</div>


@else
<div class="panel panel-info">
	<div class="panel-heading">Course</div>
	<div class="panel-body course_booking"><!--info body-->
		<h3>Pre-Advising Will Start Very Soon For New Trimester... !</h3>
	</div>
</div>
</tr>
@endif




</div>

<!--sidebar widget-->
<div class="col-md-3">
	@include('pages.student.student-widget')
</div>
<!--/sidebar widget-->
</div>

@stop

