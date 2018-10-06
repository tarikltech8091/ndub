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
	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading">Result Information</div>
			<div class="panel-body"><!--info body-->


				<div class="col-md-12">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Program</th>
								<th>Course ID</th>
								<th>Course Titile</th>
								<th>Result</th>
							</tr>
						</thead>
						<tbody>

							@if(!empty($all_courses))
							@foreach($all_courses as $key => $all_courses)
							<tr>
								<td>{{$all_courses->program_title}}</td>
								<td>{{$all_courses->course_code}}</td>
								<td>{{$all_courses->course_title}}</td>
								<td>

									<button class="faculty_result_submit btn btn btn-primary btn-sm" style="padding:0;" data-program="{{$all_courses->assigned_course_program}}" data-semester="{{$all_courses->assigned_course_semester}}" data-year="{{$all_courses->assigned_course_year}}" data-course="{{$all_courses->assigned_course_id}}" data-toggle="tooltip" title="Student List To Insert Marks">Insert Marks</button>
								</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="4">
									<div class="alert alert-success">
										<center><h3 style="font-style:italic">You are not assigned for any course !</h3></center>
									</div>
								</td>
							</tr>
							@endif

						</tbody>
					</table>
				</div>


				<form action="{{url('/faculty/result-publish/')}}" method="post" enctype="multipart/form-data">
					<div class="faculty_result_entry_form">	

					</div>
				</form>


			</div>
		</div>
	</div>
	<!--sidebar widget-->
	<div class="col-md-3">
		@include('pages.faculty.faculty-notice')
	</div>
	<!--/sidebar widget-->

</div>


@stop