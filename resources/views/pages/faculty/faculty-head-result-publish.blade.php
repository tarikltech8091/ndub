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
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">Program Head Result Publishing</div>
			<div class="panel-body"><!--info body-->

				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Course Ttile</th>
							<th>Course Code</th>
							<th>Program</th>
							<th>Semester</th>
							<th>Year</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						@if(!empty($course_tabulation))
						@foreach($course_tabulation as $key => $course_tabulation_list)
						<?php
						$univ_program=\DB::table('univ_program')->where('program_id',$course_tabulation_list->tabulation_program)->first();

						$univ_semester=\DB::table('univ_semester')->where('semester_code',$course_tabulation_list->tabulation_semester)->first();
						$course_info=\DB::table('course_basic')->where('course_code',$course_tabulation_list->tabulation_course_id)->first();
						?>
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$course_info->course_title}}</td>
							<td>{{$course_tabulation_list->tabulation_course_id}}</td>
							<td>{{isset($univ_program->program_title) ? $univ_program->program_title : ''}}</td>
							<td>{{isset($univ_semester->semester_title) ? $univ_semester->semester_title : ''}}</td>
							<td>{{$course_tabulation_list->tabulation_year}}</td>
							@if(($course_tabulation_list->tabulation_status == 2) || ($course_tabulation_list->tabulation_status == 1))
							<td><button class="btn btn-success btn-xs padding_0 program_head_result_publish" data-course-code="{{$course_tabulation_list->tabulation_course_id}}"  data-toggle="modal" data-target=".bs-example-modal-lg" data-toggle1="tooltip" title="Result Published">Published</button></td>
							@else
							<td><button class="btn btn-primary btn-xs padding_0 program_head_result_publish" data-course-code="{{$course_tabulation_list->tabulation_course_id}}"  data-toggle="modal" data-target=".bs-example-modal-lg" data-toggle1="tooltip" title="Finally Publish Result By Course">Publish</button></td>
							@endif

						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>

							</td>
						</tr>
						@endif
					</tbody>
				</table>

				{{isset($course_tabulation_pagination)?$course_tabulation_pagination:''}}
			</div><!--/info body-->
		</div>
	</div>


</div>




<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Head Result Publish</h4>
			</div>
			<div class="ajax_program_head_result_publish"></div>
		</div>
	</div>
</div>


@stop