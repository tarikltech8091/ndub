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
	<div class="col-md-12 ">
		<div class="panel panel-body padding_0 ">
			<div class="col-md-3 form-group">
				<label>Program</label>
				<select name="program" class="form-control program" required>
					@foreach($program as $key => $list)
					<option {{(isset($_GET['program']) && ($_GET['program']==$list->program_id)) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-2 form-group">
				<label>Trimester</label>
				<select name="semester" class="form-control semester" required>
					@if(!empty($semester))
					@foreach($semester as $key => $list)
					<option {{(isset($_GET['semester']) && ($_GET['semester']==$list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
					@endforeach
					@endif
				</select>
			</div>

			<div class="col-md-2 form-group">
				<label>Year</label>
				<select class="form-control academic_year" name="academic_year" >

					@if(!empty($year_info))
					<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==$year_info)) ? 'selected':''}} value="{{$year_info}}">{{$year_info}}</option>
					@endif
				</select>
			</div>

			<div class="col-md-2 form-group">
				<label>Student Year</label>
				<select name="level" class="form-control level" required>
					<option {{(isset($_GET['level']) && ($_GET['level']==1)) ? 'selected':''}}  value="1">1</option>
					<option {{(isset($_GET['level']) && ($_GET['level']==2)) ? 'selected':''}}  value="2">2</option>
					<option {{(isset($_GET['level']) && ($_GET['level']==3)) ? 'selected':''}} value="3">3</option>
					<option {{(isset($_GET['level']) && ($_GET['level']==4)) ? 'selected':''}} value="4">4</option>
				</select>
			</div>

			<div class="col-md-2 form-group">
				<label>Student Trimester</label>
				<select name="term" class="form-control term" required>
					<option {{(isset($_GET['term']) && ($_GET['term']==1)) ? 'selected':''}}  value="1">1</option>
					<option {{(isset($_GET['term']) && ($_GET['term']==2)) ? 'selected':''}}  value="2">2</option>
					<option {{(isset($_GET['term']) && ($_GET['term']==3)) ? 'selected':''}} value="3">3</option>
				</select>
			</div>

			<div class="col-md-1 text-right margin_top_27">
				<button  class="btn btn-info faculty_course_assign_search" data-toggle="tooltip" title="Search Courses">Search</button>
				<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
				<input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">
			</div>
		</div>
	</div>
</div>

@if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year']) && isset($_GET['level']) && isset($_GET['term']))

<div class="page_row row">

	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">Assign Course For Faculty</div>
			<div class="panel-body"><!--info body-->

				

				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Course Title</th>
							<th>Course Code</th>
							<th>Course Category</th>
							<th>Course Program</th>
							<th>Course Credit</th>
							<th>Course Type</th>
							<th>Faculty Assign</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($all_courses))
						@foreach($all_courses as $key => $all_courses)
						<?php
						$faculty_assigned_course=\DB::table('faculty_assingned_course')
						->where('assigned_course_id', $all_courses->course_code)
						->where('assigned_course_program', $all_courses->program_id)
						->where('assigned_course_semester', $_GET['semester'])
						->where('assigned_course_year', $_GET['academic_year'])
						->where('assigned_course_level', $_GET['level'])
						->where('assigned_course_term', $_GET['term'])
						->first();
						?>
						@if(!empty($faculty_assigned_course) && ($faculty_assigned_course->assigned_course_id==$all_courses->course_code))

						<form action="{{url('/register/faculty-assigned-course','delete')}}" method="post" enctype="multipart/form-data">
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$all_courses->course_title}}</td>
								<td>{{$all_courses->course_code}}</td>
								<td>{{$all_courses->course_category_name}}</td>
								<td>{{$all_courses->program_code}}</td>
								<td>{{$all_courses->credit_hours}}</td>
								<td>{{$all_courses->course_type}}</td>

								<td style="width:210px">
									{{$faculty_assigned_course->assigned_course_faculties}}
								</td>
								<td>
								<button type="submit" class="btn btn-danger"><i class="fa fa-undo" aria-hidden="true"></i></button>

								</td>
							</tr>
							<input type="hidden" name="course_code" value="{{$all_courses->course_code}}">
							<input type="hidden" name="assigned_course_tran_code" value="{{$faculty_assigned_course->assigned_course_tran_code}}">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
						</form>

						@else

						<form action="{{url('/register/faculty-assigned-course','insert')}}" method="post" enctype="multipart/form-data">
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$all_courses->course_title}}</td>
								<td>{{$all_courses->course_code}}</td>
								<td>{{$all_courses->course_category_name}}</td>
								<td>{{$all_courses->program_code}}</td>
								<td>{{$all_courses->credit_hours}}</td>
								<td>{{$all_courses->course_type}}</td>

								<td style="width:210px">

									<select name="faculties[]" class="multipleSelectExample pull-right" data-placeholder="Select Faculty ID" multiple>
										@foreach($faculty as $key => $faculties)
										<option value="{{$faculties->faculty_id}}">{{$faculties->first_name}} {{$faculties->middle_name}} {{$faculties->last_name}}({{$faculties->faculty_id}})</option>
										@endforeach
									</select>
									<input type="hidden" name="course_code" value="{{$all_courses->course_code}}">
									<input type="hidden" name="assigned_course_semester" value="{{$_GET['semester']}}">
									<input type="hidden" name="assigned_course_year" value="{{$_GET['academic_year']}}">

								</td>
								<td>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="submit" value="Assign" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Assign Faculty">
								</td>
							</tr>
						</form>
						@endif

						@endforeach

						@else
						<!-- empty message -->
						<tr>
							<td colspan="5">
								<!-- empty message -->
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>
							</td>
						</tr>

						@endif
					</tbody>

				</table>

			</div><!--/info body-->
		</div>
	</div>


	@endif

	@stop