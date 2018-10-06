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
	<div class="col-md-12 form-inline">
		<div class="col-md-12 panel panel-body search_panel_bg_color">
			<form method="get" action="{{url('/register/student/withdraw/course')}}" enctype="multipart/form-data">
				<div class="form-group col-md-6">
					<input  class="form-control search_width" type="text" name="student_no" value="{{!empty($_GET['student_no'])? $_GET['student_no'] : old('student_no')}}" placeholder="Search Student...">

					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Search Student">Search !</button>

				</div>

			</form>
		</div>
	</div>
</div>
@if(isset($_GET['student_no']))
@if(!empty($_GET['student_no']))
<div class="row page_row">
	<div class="col-md-12">
		<div class="panel panel-body padding_0">

			<form action="{{url('/register/student/withdraw/course')}}" method="post" enctype="multipart/form-data">
				<br><h2 align="center"><strong>Student Course Withdraw</strong></h2><br>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>SL</th>
							<th>Student ID</th>
							<th>Program</th>
							<th>Course Code</th>
							<th>Course Title</th>
							<th>Course Credit Hours</th>
							<th> <input class="checkAll" type="checkbox" /> All</th>
						</tr>
					</thead>

					<tbody>
						<!-- <form action="" method="post" enctype="multipart/form-data"> -->
						@if(!empty($all_student))
						@foreach($all_student as $key => $student_list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$student_list->student_serial_no}}</td>
							<td>{{$student_list->program_title}}</td>
							<td>{{$student_list->tabulation_course_id}}</td>
							<td>{{$student_list->tabulation_course_title}}</td>
							<td>{{$student_list->tabulatation_credit_hours}}</td>
							<td><input type="checkbox" name="course_no[]" value="{{$student_list->tabulation_course_id}}"/>
								<input type="hidden" name="student_no" value="{{$student_list->student_serial_no}}"/>
							</td>

						</tr>
						@endforeach
						<tr>
							<td colspan="7"><span style="margin-left:70%;"><STRONG>Return Amount (%)  </STRONG><input  type="text" name="percentise" placeholder="How much parcent !"><button type="submit" class="pull-right btn btn-primary btn-sm">Submit</button></span></td>
						</tr>
						@else
						<tr>
							<td colspan="7">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>
							</td>
						</tr>
						@endif


						<!-- </form> -->
					</tbody>
				</table>
			</form>	
		</div>

	</div>
</div>
@else
<div class="page_row row">
	<div class="col-md-12">
		<div class="alert alert-success">
			<center><h3 style="font-style:italic">No Data Found !</h3></center>
		</div>
	</div>
</div>
@endif
@endif

@stop