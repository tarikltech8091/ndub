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
			<div class="panel-heading">Student Degree Course Plan</div>
			<div class="panel-body">

				@if(!empty($student_academic_course_plan))
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>SL</th>
							<th>Type of Courses</th>
							<th>No of Courses</th>
							<th>Total Credit Hours</th>
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
						
						<?php 
						$total_no_of_courses=0; 
						$total_degree_credit_hours=0; 
						?>
						@foreach($student_academic_course_plan as $key => $list)
						<?php 
						$total_no_of_courses=$total_no_of_courses+$list->deatail_no_course;
						$total_degree_credit_hours=$total_degree_credit_hours+$list->deatail_total_credit;
						?>
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->course_category_name}}</td>
							<td>{{$list->deatail_no_course}}</td>
							<td>{{$list->deatail_total_credit}}</td>
							<td>
								<a href="#myModal" style="padding:0;" role="button" class="btn btn-primary btn-xm student_academic_course_plan_detail" data-toggle="modal" data-category="{{$list->course_category_slug}}" data-program="{{$list->plan_program}}" data-toggle1="tooltip" title="View Course List">View Details</a>
							</td>
						</tr>
						@endforeach
						
						<tr>
							<th colspan="2"><center>Type of Courses</center></th>
							<th colspan="1">{{isset($total_no_of_courses) ? $total_no_of_courses : ''}} Courses</th>
							<th colspan="2">{{isset($total_degree_credit_hours) ? $total_degree_credit_hours : ''}} Credits</th>
						</tr>
					</tbody>
				</table>
				@else
				<!-- empty message -->
				<div class="alert alert-success">
					<center><h3 style="font-style:italic">No Data Available !</h3></center>
				</div>

				@endif
			</div>
		</div>


		<div class="banner-bottom-video-grid-left ">
			<div class="panel-group"  id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title asd" >
							<a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								<span class="fa fa-plus"></span><i class="fa fa-minus"></i><label>Student Academic Course Plan Detail</label>
							</a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body panel_text today_schedule_list">


							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 1</span> <span>Term: 1</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_11))
									@foreach($lt_11 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>


							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 1</span> <span>Term: 2</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_12))
									@foreach($lt_12 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 1</span> <span>Term: 3</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_13))
									@foreach($lt_13 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>


							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 2</span> <span>Term: 1</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_21))
									@foreach($lt_21 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>


							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 2</span> <span>Term: 2</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_22))
									@foreach($lt_22 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>


							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 2</span> <span>Term: 3</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_23))
									@foreach($lt_23 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>


							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 3</span> <span>Term: 1</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_31))
									@foreach($lt_31 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 3</span> <span>Term: 2</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_32))
									@foreach($lt_32 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 3</span> <span>Term: 3</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_33))
									@foreach($lt_33 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 4</span> <span>Term: 1</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_41))
									@foreach($lt_41 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 4</span> <span>Term: 2</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_42))
									@foreach($lt_42 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

							<table class="table table-bordered table-hover" style="margin-top:40px;">
								<thead>
									<tr>
										<th colspan="6" class="lt"><span>Level: 2</span> <span>Term: 3</span></th>
									</tr>
									<tr>
										<th>SL</th>
										<th>Course Code</th>
										<th>Course Title</th>
										<th>Course Credit</th>
										<th>Course Type</th>
										<th>Course Category</th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($lt_43))
									@foreach($lt_43 as $key => $list)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$list->course_code}}</td>
										<td>{{$list->course_title}}</td>
										<td>{{$list->credit_hours}}</td>
										<td>{{$list->course_type}}</td>
										<td>{{$list->course_category_name}}</td>
									</tr>
									@endforeach
									@else
									<!-- empty message -->
									<tr>
										<td colspan="6">
											<div class="alert alert-success">
												<center><h3 style="font-style:italic">No Data Found !</h3></center>
											</div>
										</td>
									</tr>
									@endif
								</tbody>

							</table>

						</div>
					</div>

				</div>
			</div>
		</div>
		<br>



	</div>


	<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="student_academic_course_plan_detail_show"></div>
			</div>
		</div>


	</div>



	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>

</div>

@stop