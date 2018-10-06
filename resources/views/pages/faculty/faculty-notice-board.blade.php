@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
	<div class="col-md-12">
		<!--error message*******************************************-->
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
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
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			{{ Session::get('errormessage') }}
		</div>
		@endif
	</div>
</div>
<!--end of error message*************************************-->


<div class="row page_row">
	
	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">Notice</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/faculty/notice-board')}}" method="post">

					<?php 
					$program_list =\App\Register::ProgramList();
					?>
					<div class="form-group program_form">
						<label for="Program">Program <span class="required-sign">*</span></label>
						<select class="form-control" name="notice_program">
							@if(!empty($program_list))
							@foreach($program_list as $key => $list)
							<option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
							@endforeach
							@endif
							<option  value="all_course_student">All</option>
						</select> 
					</div>


					<?php
					$faculty_id=\Auth::user()->user_id;
					$select_course=\DB::table('faculty_assingned_course')
					->where('assigned_course_faculties','like',$faculty_id)
					->select('faculty_assingned_course.*', \DB::raw('count(*) as total'))
            		->groupBy('assigned_course_id')
					->get();
					?>
					<div class="form-group">
						<label for="Course">Course <span class="required-sign">*</span></label>
						<select  name="notice_to" class="form-control" required>
							<option value="">Select Course</option>
							@if(!empty($select_course))
							@foreach($select_course as $key => $list)
							<option {{(old('notice_to')== $list->assigned_course_id) ? "selected" :''}} value="{{$list->assigned_course_id}}">{{$list->assigned_course_title}}</option>
							@endforeach
							@endif
							<option  value="all">All</option>
						</select>
					</div>

					<div class="form-group">
						<label>Title</label>
						<input type="text" name="notice_subject" class="form-control" placeholder="Title" />
					</div>

					<div class="form-group">
						<label>Description</label>
						<textarea name="notice_message" rows="10" class="form-control" id="noticeboard"></textarea>
					</div>
					<input type="hidden" name="notice_to_type" value="faculty_to_student">

					<div class="form-group text-right">
						<input type="reset" class="btn btn-danger" value="Reset">
						<button class="btn btn-success">Submit</button>
						
					</div>
				</form>

			</div><!--/info body-->
		</div>
	</div>

	<!-- view -->
	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Faculty All Notice</div>
			<div class="panel-body">
				
				<label>Notice List</label>
				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<!-- <th>Date</th> -->
							<th>Notice Subject</th>
							<th>Notice For</th>
							<th>Program</th>
							<th>Trimester</th>
							<th>Year</th>
							<th>Details</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($faculty_notice_list) && count($faculty_notice_list) > 0)
						@foreach($faculty_notice_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<!-- <td>{{$list->created_at}}</td> -->
							<td>{{$list->notice_subject}}</td>
							<td>{{$list->notice_to}}</td>
							<td>{{(isset($list) && ($list->program_title)) ? $list->program_title : 'all'}}</td>
							<td>{{$list->semester_title}}</td>
							<td>{{$list->notice_year}}</td>
							<td>{{str_limit($list->notice_message, 20)}}</td>
							<td>
								<a onclick="location.href='{{URL::route('Faculty Notice Edit',$list->notice_tran_code)}}';" class="cursor" data-toggle="tooltip" title="Edit Notice"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a data-confirm-url="{{URL::route('Faculty Notice Delete',$list->notice_tran_code)}}" class="cursor confirm_box" data-toggle="tooltip" title="Delete Notice"><i class="fa fa-trash-o"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="9">No Data available</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($faculty_notice_pagination) ? $faculty_notice_pagination:""}}
		</div>
	</div>
</div>

</div>

@stop