@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row page_row">
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

	</div>
</div>
<!--end of error message*************************************-->


<div class="row page_row">
	<div class="col-md-12 semester profile_tab">

		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
			<ul id="myTab" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="{{$tab=='course_categoty' ? 'active' :''}}"><a href="#course_categoty" role="tab" id="course_categoty-tab" data-toggle="tab" aria-controls="course_categoty"><i class="fa fa-file-word-o"></i>Course Category</a></li>

				<li role="presentation" class="{{$tab=='course_add' ? 'active' :''}}"><a href="#course_add" id="course_add-tab" role="tab" data-toggle="tab" aria-controls="course_add" aria-expanded="true"><i class="fa fa-plus-square-o"></i>Course Add</a></li>

				<li role="presentation" class="{{$tab=='course_catalogue' ? 'active' :''}}"><a href="#course_catalogue" role="tab" id="course_catalogue-tab" data-toggle="tab" aria-controls="course_catalogue"><i class="fa fa-newspaper-o"></i>Course Catalouge</a></li>

				<li role="presentation" class="{{$tab=='degree_plan' ? 'active' :''}}"><a href="#degree_plan" role="tab" id="degree_plan-tab" data-toggle="tab" aria-controls="degree_plan"><i class="fa fa-newspaper-o"></i>Degree Plan</a></li>

				<!-- <li role="presentation" class="{{$tab=='course_categoty' ? 'active' :''}}"><a href="#course_plan" role="tab" id="course_plan-tab" data-toggle="tab" aria-controls="course_plan"><i class="fa fa-map"></i>Course Plan</a></li>	 -->

				<!-- <li role="presentation"><a href="#4th" role="tab" id="4th-tab" data-toggle="tab" aria-controls="4th"><i class="fa fa-file-word-o"></i>Course</a></li>	 -->		 
			</ul>


			<div id="myTabContent" class="tab-content"><!--main tab content-->
				<div role="tabpanel" class="tab-pane fade {{$tab=='course_categoty' ? 'in active' :''}}" id="course_categoty" aria-labelledby="course_categoty-tab"><!--course_categoty tab-->
					

					<div class="row">
						<div class="col-md-4">
							<form action="{{url('/academic/course/category/add')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="form-group">
									<label>Course Category Name</label>
									<input type="text" name="course_category_name" class="form-control" value="{{old('course_category_name')}}" required/>	
								</div>

								<div class="form-group">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
							</form>
						</div>
						<div class="col-md-8">
							<table class="table table-bordered  table-hover">
								<thead>
									<tr>
										<th>SL</th>
										<th>Category Name</th>
										<th>Created at</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($all_course_category))
									@foreach($all_course_category as $key => $category)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$category->course_category_name}}</td>
										<td>{{$category->created_at}}</td>
										<td class="manage_course">
											<a data-toggle="modal" data-target="#courseModal" data-slug="{{$category->course_category_slug}}" data-type="course_categoty" class="course_settings_edit btn btn-default btn-xs" data-toggle1="tooltip" title="Edit Course Category"><i class="fa  fa-pencil-square-o"></i></a>

											<a data-confirm-url="{{url('/academic/course-settings/delete/course_categoty/'.$category->course_category_slug)}}" class="course_settings_delete btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Course Category"><i class="fa  fa-trash-o"></i></a>
										</td>
									</tr>
									@endforeach

									@else
									<tr>
										<td colspan="3" >No Data available</td>
									</tr>
									@endif


								</tbody>
							</table>
							{{isset($course_pagination) ? $course_pagination :''}}
						</div>
					</div>
				</div><!--/course_categoty tab-->
				<div role="tabpanel" class="tab-pane fade {{$tab=='course_add' ? 'in active' :''}}" id="course_add" aria-labelledby="course_add-tab"><!--/course_add tab-->

					<div class="row">
						<form action="{{url('/academic/course/entry')}}" method="post">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="col-md-4">

								<div class="form-group">
									<label>Program</label>
									<select class="form-control" name="course_program" required>
										<option value="">Select Program</option>
										@if(!empty($program_list))
										@foreach($program_list as $key => $list)
										<option {{(old('course_program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_code}}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Course Title</label>
									<input type="text" name="course_title" class="form-control" value="{{old('course_title')}}"  required/>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Course Code</label>
										<input type="text" name="course_code" class="form-control" value="{{old('course_code')}}"  required/>	
									</div>
									<div class="form-group col-md-6">
										<label>Course Type</label>
										<select name="course_type" class="form-control"   required>
											<option value="">Select Type</option>
											<option value="Theory">Theory</option>
											<option value="Lab work">Lab work</option>
											<option value="Field work">Field work</option>
										</select>	
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Credit</label>
										<input type="text" name="credit_hours" class="form-control"  value="{{old('credit_hours')}}" required/>	
									</div>
									<div class="form-group col-md-3">
										<label>Year</label>
										<input type="number" min="1" max="4" name="level" step="1" class="form-control" value="{{old('level')}}" required/>		
									</div>
									<div class="form-group col-md-3">
										<label>Trimester</label>
										<input type="number" min="1" max="3" name="term" step="1" class="form-control"  value="{{old('term')}}" required/>	
									</div>
								</div>


<!-- 							<div class="row">
									<div class="form-group col-md-6">
										<label>Per Credit Fee</label>
										<input type="text" name="per_credit_fees_amount" class="form-control" value="{{old('per_credit_fees_amount')}}"  required/>	
									</div>
									<div class="form-group col-md-6">
										<label>Total Credit Fee</label>
										<input type="text" name="total_credit_fees_amount" class="form-control" value="{{old('total_credit_fees_amount')}}"  required/>	
									</div>
								</div> -->
								
								<div class="form-group">
									<label>Course Description</label>
									<textarea class="form-control" name="course_description" rows="4" required>{{old('course_description')}}</textarea>
								</div>
								<div class="form-group">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
							</div>
						</form>

						<div class="col-md-8" style="border-left: 1px solid #ddd;"><!---Course table -->


							<div class="panel panel-default">
								<div class="sorting_form">
									<form method="get" action="{{url('/academic/course-settings')}}" enctype="multipart/form-data">
										<input type="hidden" name="tab" value="course_add">
										<?php 
										$program_list =\App\Applicant::ProgramList();

										?>
										<div class="form-group col-md-6" style="padding-left:0px;">
											<label for="Program">Program</label>
											<select class="form-control" name="program" >
												<option value="">Select Program</option>
												@if(!empty($program_list))
												@foreach($program_list as $key => $list)
												<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
												@endforeach
												@endif
											</select>
										</div>

										<div class="form-group col-md-1" style="margin-top:20px;">
											<button class="btn btn-danger " data-toggle="tooltip" title="Search Program">Search</button>
										</div>
									</form>

								</div>
							</div>

							<table class="table table-bordered  table-hover">
								<thead>
									<tr>
										<th>Course Code</th>
										<th>Title </th>
										<th>Program</th>
										<th>Credit</th>
										<!-- <th>Per Credit Fee</th> -->
										<!-- <th>Total Credit Fee</th> -->
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($all_course))
									@foreach($all_course as $key => $course)
									<tr>
										<td>{{$course->course_code}}</td>
										<td>{{$course->course_title}}</td>
										<td>{{$course->program_code}}</td>
										<td>{{$course->credit_hours}}</td>
										<!-- <td>{{$course->per_credit_fees_amount}}</td> -->
										<!-- <td>{{$course->total_credit_fees_amount}}</td> -->
										<td class="manage_course">
											<a data-toggle="modal" data-target="#courseModal" data-slug="{{$course->course_slug}}" data-type="course_add" class="course_settings_edit btn btn-default btn-xs" data-toggle1="tooltip" title="Edit Course"><i class="fa  fa-pencil-square-o"></i></a>

											<a data-confirm-url="{{url('/academic/course-settings/delete/course_add/'.$course->course_slug)}}" class="course_settings_delete btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Course"><i class="fa  fa-trash-o"></i></a>
										</td>
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="5" >No Data available</td>
									</tr>
									@endif
								</tbody>
							</table>
							{{isset($course_basic_pagination) ? $course_basic_pagination :''}}
						</div><!---Course table -->

					</div>
				</div><!--/course_add tab-->

				<div role="tabpanel" class="tab-pane fade {{$tab=='course_catalogue' ? 'in active' :''}}" id="course_catalogue" aria-labelledby="course_catalogue-tab"><!--/course_catalogue tab-->
					
					<div class="row" style="padding-bottom:20px;">
						<div class="col-md-6">
							<form action="{{url('/academic/course-category/update')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="row">
									<div class="form-group col-md-6">
										<label>Program</label>
										<select class="form-control catalouge_program" name="category_program" required>
											<option value="">Select Program</option>
											@if(!empty($program_list))
											@foreach($program_list as $key => $list)
											<option  value="{{$list->program_id}}">{{$list->program_code}}</option>
											@endforeach
											@endif
										</select>
									</div>

									<div class="form-group col-md-6">
										<label>Category</label>
										<select class="form-control" name="course_category" required>
											<option value="">Select Category</option>
											@if(!empty($all_course_category_info))
											@foreach($all_course_category_info as $key => $category)
											<option  value="{{$category->course_category_slug}}">{{$category->course_category_name}}</option>
											@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Minimum no. of Courses</label>
										<input type="text" name="no_of_courses" class="form-control" value="{{old('no_of_courses')}}"  required/>	
									</div>
									<div class="form-group col-md-6">
										<label>Minimum Credit</label>
										<input type="text" name="total_credit_hours" class="form-control" value="{{old('total_credit_hours')}}"  required/>	
									</div>
								</div>

								<div class="row" id="course_catalouge_list"></div>

								<div class="row">
									<div class="col-md-12">
										<br>
										<input type="submit" class="btn btn-success pull-right cours_catalogue_save" value="Add ">
									</div>
								</div>

							</form>
						</div>


						<div class="col-md-6"  style="border-left: 1px solid #ddd;">

							<div class="row">
								<div class="col-md-12">
									<div class="sorting_form"><!--header inline form-->
										<form method="get" action="{{url('/academic/course-settings')}}" enctype="multipart/form-data">
											<input type="hidden" name="tab" value="course_catalogue">
											<?php 
											$program_list =\App\Applicant::ProgramList();

											?>
											<div class="form-group col-md-6" style="padding-left:0px;">
												<label for="Program">Program</label>
												<select class="form-control program select_program" name="program" >
													<option value="">Select Program</option>
													@if(!empty($program_list))
													@foreach($program_list as $key => $list)
													<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
													@endforeach
													@endif
												</select>
											</div>

											<div class="form-group col-md-1" style="margin-top:20px;">
												<button class="btn btn-danger " data-toggle="tooltip" title="Search Program">Search</button>
											</div>
										</form>

									</div>
								</div>
								<div class="col-md-12">

									<label><b>Catalouge List</b></label>
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>SL</th>
												<th>Program</th>
												<th>Catalouge Category</th>
												<th>Total Course</th>
												<th>Total Credit</th>
												<th>Action</th>
											</tr>
										</thead>

										<tbody>
											@if(!empty($catalogue_list))
											@foreach($catalogue_list as $key => $catalogues)
											<tr>
												<td>{{$key+1}}</td>
												<td>{{$catalogues->program_code}}</td>
												<td>{{$catalogues->course_category_name}}</td>
												<td>{{$catalogues->no_of_courses}}</td>
												<td>{{$catalogues->total_credit_hours}}</td>
												<td>
													<button data-confirm-url="{{url('/academic-settings/delete-course-catalouge/'.$catalogues->course_catalogue_tran_code)}}" class="btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Course Catalouge"><i class="fa fa-trash-o"></i></button>
												</td>
											</tr>
											@endforeach
											@endif
										</tbody>
									</table>
									{{isset($catalogue_list_pagination) ? $catalogue_list_pagination :''}}
								</div>
							</div>

						</div>
					</div>

				</div><!--/course_catalogue tab-->



				<div role="tabpanel" class="tab-pane fade {{$tab=='course_plan' ? 'in active' :''}}" id="course_plan" aria-labelledby="course_plan-tab"><!--/course_plan tab-->
					<div class="row">
						<div class="col-md-5">
							<form action="{{url('/academic/course-catalogue/entry')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="row">
									<div class="form-group col-md-6">
										<label>Program</label>
										<select class="form-control" name="catalouge_program" required>
											<option value="">Select Program</option>
											@if(!empty($program_list))
											@foreach($program_list as $key => $list)
											<option  value="{{$list->program_id}}">{{$list->program_code}}</option>
											@endforeach
											@endif
										</select>
									</div>

									<div class="forn-group col-md-6">
										<label>Course Category</label>
										<select class="form-control" name="catalouge_category" required>
											<option value="">Select Category</option>
											@if(!empty($all_course_category))
											@foreach($all_course_category as $key => $category)
											<option  value="{{$category->course_category_slug}}">{{$category->course_category_name}}</option>
											@endforeach
											@endif
										</select>
									</div>
								</div>

								
							</form>
						</div>
						<div class="col-md-7">
							<table class="table table-striped  table-hover">
								<thead>
									<tr>
										<th>Program</th>
										<th>Type of Courses</th>
										<th>No. of Courses</th>
										<th>Total Credit Hours</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($all_course_plan))
									@foreach($all_course_plan as $key => $course_plan)
									<tr>
										<td>{{$course_plan->program_code}}</td>
										<td>{{$course_plan->course_category_name}}</td>
										<td>{{$course_plan->no_of_courses}}</td>
										<td>{{$course_plan->total_credit_hours}}</td>
										<td class="manage_course">
											<a data-toggle="modal" data-target="#courseModal" data-slug="{{$course_plan->course_catalogue_slug}}"  data-type="course_plan" class="course_settings_edit" data-toggle1="tooltip" title="Edit Course Plan"><i class="fa  fa-pencil-square-o"></i></a>

											<a href="{{url('/academic/course-settings/delete/course_plan/'.$course_plan->course_catalogue_slug)}}" class="course_settings_delete" data-toggle="tooltip" title="Delete Course Plan"><i class="fa  fa-trash-o"></i></a>
										</td>
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="5" >No Data available</td>
									</tr>
									@endif
								</tbody>
							</table>
							{{isset($course_plan_pagination)? $course_plan_pagination:''}}
						</div>
					</div>
				</div><!--/campus tab-->




				<div role="tabpanel" class="tab-pane fade {{$tab=='degree_plan' ? 'in active' :''}}" id="degree_plan" aria-labelledby="degree_plan-tab"><!--/degree_plan tab-->

					<div class="row">
						<div class="col-md-6">
							<form action="{{url('/academic/catalouge-list/store-degree-plan')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="row">
									<div class="form-group col-md-6">
										<label>Degree</label>
										<select class="form-control" name="plan_degree" required>
											<option value="">Select Degree</option>
											@if(!empty($degree_list))
											@foreach($degree_list as $key => $list)
											<option  value="{{$list->degree_slug}}">{{$list->degree_title}}</option>
											@endforeach
											@endif
										</select>
									</div>
									<div class="form-group col-md-6">
										<label>Program</label>
										<select class="form-control catalouge_list_program" name="plan_program" required>
											<option value="">Select Program</option>
											@if(!empty($program_list))
											@foreach($program_list as $key => $list)
											<option  value="{{$list->program_id}}">{{$list->program_code}}</option>
											@endforeach
											@endif
										</select>
									</div>
								</div>

								<div class="row" id="course_catalouge_list_program">

								</div>

								<div class="row">
									<div class="col-md-12">
										<input type="submit" class="pull-right btn btn-success cours_catalogue_save" value="Submit ">
									</div>
								</div>
							</form>
						</div>



						<div class="col-md-6">
							<label><b>Degree Plan Lists</b></label>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>SL</th>
										<th>Program</th>
										<th>Degree</th>
										<th>Tri Min Credit</th>
										<th>Tri Max Credit</th>
										<th>Total Course</th>
										<th>Total Credit</th>
										<th> Action </th>
									</tr>
								</thead>

								<tbody>
									@if(!empty($degree_plan_list))
									@foreach($degree_plan_list as $key => $degree_plans)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$degree_plans->program_title}}</td>
										<td>{{$degree_plans->degree_title}}</td>
										<td>{{$degree_plans->trimester_min_credit}}</td>
										<td>{{$degree_plans->trimester_max_credit}}</td>
										<td>{{$degree_plans->plan_total_no_course}}</td>
										<td>{{$degree_plans->plan_total_credit}}</td>
										<td>
											<button data-toggle="modal" data-target="#view_degree_plan" data-degree-plan-tran="{{$degree_plans->degree_plan_tran_code}}" class="btn btn-primary btn-xs padding_0 degree_plan_view" data-toggle1="tooltip" title="View Degree Plan"><i class="fa fa-eye"></i></button>

											<button data-confirm-url="{{url('/academic-settings/delete-degree-plan/'.$degree_plans->degree_plan_tran_code)}}" class="btn btn-danger btn-xs padding_0 confirm_box" data-toggle="tooltip" title="Delete Degree Plan"><i class="fa fa-trash-o"></i></button>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
							{{isset($degree_plan_pagination) ? $degree_plan_pagination :''}}
						</div>


					</div>
					

				</div><!--/degree_plan tab-->

				
			</div><!--/main tab content-->
		</div>
	</div>
</div>

<!-- Modal -->
<div id="courseModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content course_setting_form">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit</h4>
			</div>
			<div class="modal-body course_setting_form">
				<div class="ajax_loader loading_icon"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div><!-- /Modal content-->
	</div>
</div><!-- /Modal -->



<!-- degree plan view modal -->
<div class="modal fade " id="view_degree_plan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Degree Plan Detail</h4>
			</div>
			<div class="modal-body">
				<div class="ajax_degree_plan_detail_modal"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


@stop