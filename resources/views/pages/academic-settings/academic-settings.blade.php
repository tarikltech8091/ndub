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

	<div class="col-md-9 semester profile_tab">

		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
			
			<ul id="myTab" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="{{$tab=='degree' ? 'active':''}}"><a href="#degree" role="tab" id="degree-tab" data-toggle="tab" aria-controls="degree"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Degree</a></li>

				<li role="presentation" class="{{$tab=='department' ? 'active':''}}"><a href="#department" role="tab" id="department-tab" data-toggle="tab" aria-controls="department"><i class="fa fa-map-o"></i>Department</a></li>

				<li role="presentation" class="{{$tab=='program' ? 'active':''}}"><a href="#program" id="program-tab" role="tab" data-toggle="tab" aria-controls="program" aria-expanded="true"><i class="fa fa-map-signs"></i>Programs</a></li>
				<li role="presentation" class="{{$tab=='semester' ? 'active':''}}"><a href="#semester" id="semester-tab" role="tab" data-toggle="tab" aria-controls="semester" aria-expanded="true"><i class="fa fa-map-signs"></i>Trimester</a></li>

				<li role="presentation" class="{{$tab=='campus' ? 'active':''}}"><a href="#campus" role="tab" id="campus-tab" data-toggle="tab" aria-controls="campus"><i class="fa fa-university" aria-hidden="true"></i>Campus</a></li>

				<li role="presentation" class="{{$tab=='building' ? 'active':''}}"><a href="#building" role="tab" id="building-tab" data-toggle="tab" aria-controls="building"><i class="fa fa-building-o" aria-hidden="true"></i>Building</a></li>	
				<li role="presentation" class="{{$tab=='room' ? 'active':''}}"><a href="#room" role="tab" id="room-tab" data-toggle="tab" aria-controls="room"><i class="fa fa-home" aria-hidden="true"></i>Room</a></li>
				

				<!-- <li role="presentation"><a href="#4th" role="tab" id="4th-tab" data-toggle="tab" aria-controls="4th"><i class="fa fa-file-word-o"></i>Course</a></li>	 -->		 
			</ul>

			<div id="myTabContent" class="tab-content"><!--main tab content-->

				<!--degree tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='degree' ? 'active':''}}" id="degree" aria-labelledby="degree-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','degree')}}" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label>Degree Title</label>
									<input type="text" name="degree_title" class="form-control" value="{{old('degree_title')}}" />	
								</div>
								<div class="form-group">
									<label>Degree Code</label>
									<input type="text" name="degree_code" class="form-control" value="{{old('degree_code')}}" />	
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<div class="col-md-7">
							<label>Degree Lists</label>
							<table class="table table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Code</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($degree_list))
									@foreach($degree_list as $key => $list)
									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->degree_title}}</td>
										<td>{{$list->degree_code}}</td>
										<td>

											<button data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->degree_slug}}" data-type="degree" data-toggle1="tooltip" title="Edit Degree"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											<a data-confirm-url="{{URL::route('Degree Delete',$list->degree_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Degree"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>

							{{isset($pagination) ? $pagination:""}}
						</div>

					</div>
				</div>
				<!--/degree tab-->




				<!--department tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='department' ? 'active':''}}" id="department" aria-labelledby="department-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','department')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Department Title</label>
									<input type="text" name="department_title" class="form-control" value="{{old('department_title')}}" />	
								</div>
								<div class="form-group">
									<label>Department No.</label>
									<input type="number" min="1" max="30" name="department_no" class="form-control" value="{{old('department_no')}}" />	
								</div>
								<div class="form-group">
									<label>Department Dean/Chairperson</label>
									<input type="text" name="department_dean_chairperson" class="form-control" value="{{old('department_dean_chairperson')}}" />	
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<!-- department lists -->
						<div class="col-md-7"> 
							<label>Department Lists</label>
							<table class="table table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Department No.</th>
										<th>Dean/Chairperson</th>
										<th style="width:16%">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($department_list))
									@foreach($department_list as $key => $list)
									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->department_title}}</td>
										<td>{{$list->department_no}}</td>
										<td>{{$list->department_dean_chairperson}}</td>
										<td>
											<button data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->department_slug}}" data-type="department" data-toggle1="tooltip" title="Edit Department"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											<a data-confirm-url="{{URL::route('Department Delete',$list->department_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Department"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
							{{isset($pagination_department) ? $pagination_department:""}}
						</div>
					</div>
				</div>
				<!--/department tab-->



				<!--program tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='program' ? 'active':''}}" id="program" aria-labelledby="program-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','program')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Program Title</label>
									<input type="text" name="program_title" class="form-control" value="{{old('program_title')}}" />	
								</div>
								<div class="form-group">
									<label>Program ID</label>
									<input type="text" name="program_id" class="form-control" value="{{old('program_id')}}" />	
								</div>
								<div class="form-group">
									<label>Program Code</label>
									<input type="text" name="program_code" class="form-control" value="{{old('program_code')}}" />	
								</div>
								<div class="form-group">
									<label>Program Head</label>
									<input type="text" name="program_head" class="form-control" value="{{old('program_head')}}" />	
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label>Program Duration</label>
										<input type="number" name="program_duration" class="form-control" value="{{old('program_duration')}}" />	
									</div>
									<div class="form-group col-md-4">
										<label>Duration Type</label>
										<select name="program_duration_type" class="form-control">
											<option value="year">Years</option>
											<!-- <option value="month">Months</option> -->
										</select>	
									</div>
									<div class="form-group col-md-4">
										<label>Total Credit Hours</label>
										<input type="number" name="program_total_credit_hours" class="form-control" value="{{old('program_total_credit_hours')}}" />	
									</div>
								</div>

								<div class="form-group">
									<label>Degree</label>
									<select name="program_degree_code" class="form-control">
										<?php 
										$degree=\App\Academic::DegreeList();
										?>
										@if(!empty($degree))
										@foreach($degree as $key => $degree)
										<option value="{{$degree->degree_code}}">{{$degree->degree_title}}</option>
										@endforeach
										@endif
									</select>	
								</div>

								<div class="form-group">
									<label>Department</label>
									<?php 
									$department=\App\Academic::DepartmentList();
									?>
									<select name="department_no" class="form-control">
										@if(!empty($department))
										@foreach($department as $key => $department)
										<option value="{{$department->department_no}}">{{$department->department_title}}</option>
										@endforeach
										@endif
									</select>	
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<!-- program lists -->
						<div class="col-md-7"> 
							<label>Program Lists</label>
							<table class="table table-bordered  table-hover" >
								<thead>
									<tr>
										<th>ID</th>
										<th>Title</th>
										<th>Code</th>
										<th>Head</th>
										<th>Department</th>
										<th>Degree</th>

										<th >Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($program_list))
									@foreach($program_list as $key => $list)
									<tr >
										<td>{{$list->program_id}}</td>
										<td>{{$list->program_title}}</td>
										<td>{{$list->program_code}}</td>
										<td>{{$list->program_head}}</td>

										<td>{{$list->department_title}}</td>
										<td>{{$list->degree_title}}</td>

										<td>
											<a data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->program_slug}}" data-type="program" data-toggle1="tooltip" title="Edit Program"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

											<a data-confirm-url="{{URL::route('Program Delete',$list->program_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Program"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>

							{{isset($pagination_program) ? $pagination_program :''}}
						</div>
					</div>
				</div>
				<!--/programe tab-->




				<!--Trimester tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='semester' ? 'active':''}}" id="semester" aria-labelledby="semester-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','semester')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Trimester Title</label>
									<input type="text" name="semester_title" class="form-control" value="{{old('semester_title')}}" />	
								</div>
								<div class="form-group">
									<label>Trimester Code</label>
									<input type="text" name="semester_code" class="form-control" value="{{old('semester_code')}}" />	
								</div>
								<div class="form-group">
									<label>Trimester Sequence</label>
									<select name="semester_sequence" class="form-control">	
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
									</select>
								</div>
								<div class="form-group">
									<label>Trimester Duration</label>
									<input name="semester_duration" class="form-control" value="{{old('semester_duration')}}" />
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<!-- Trimester lists -->
						<div class="col-md-7"> 
							<label>Trimester Lists</label>
							<table class="table table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Code</th>
										<th>Sequence</th>
										<th>Duration</th>
										<th style="width:16%">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($semester_list))
									@foreach($semester_list as $key => $list)
									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->semester_title}}</td>
										<td>{{$list->semester_code}}</td>
										<td>{{$list->semester_sequence}}</td>
										<td>{{$list->semester_duration}}</td>
										<td>
											<button data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->semester_slug}}" data-type="semester" data-toggle1="tooltip" title="Edit Semester"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											<a data-confirm-url="{{URL::route('Semester Delete',$list->semester_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Semester"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!--/semester tab-->




				<!--/campus tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='campus' ? 'active':''}}" id="campus" aria-labelledby="campus-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','campus')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Campus Title</label>
									<input type="text" name="campus_title" class="form-control" value="{{old('campus_title')}}" />	
								</div>
								<div class="form-group">
									<label>Campus Location</label>
									<textarea name="campus_location" class="form-control">{{old('campus_location')}}</textarea>
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>


						<!-- Campus lists -->
						<div class="col-md-7"> 
							<label>Campus Lists</label>
							<table class="table table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Code</th>
										<th>Location</th>
										<th style="width:16%">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($campus_list))
									@foreach($campus_list as $key => $list)
									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->campus_title}}</td>
										<td>{{$list->campus_code}}</td>
										<td>{{$list->campus_location}}</td>
										<td>
											<button data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->campus_slug}}" data-type="campus" data-toggle1="tooltip" title="Edit Campus"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											<a data-confirm-url="{{URL::route('Campus Delete',$list->campus_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Campus"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!--/campus tab-->




				<!--building tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='building' ? 'active':''}}" id="building" aria-labelledby="building-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','building')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Building Title</label>
									<input type="text" name="building_title" class="form-control" value="{{old('building_title')}}" />	
								</div>
								<div class="form-group">
									<label>Building No.</label>
									<input type="number" min="1" max="20" name="building_no" class="form-control" value="{{old('building_code')}}" />	
								</div>

								<div class="form-group">
									<label>Campus</label>
									<select name="campus_code" class="form-control">
										@if(!empty($campus_list))
										@foreach($campus_list as $key => $campus)
										<option value="{{$campus->campus_code}}">{{$campus->campus_title}}</option>
										@endforeach
										@endif
									</select>
								</div>

								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<!-- Building lists -->
						<div class="col-md-7"> 
							<label>Building Lists</label>
							<table class="table table-hover table-bordered table-striped nopadding" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Code</th>
										<th>Campus</th>
										<th style="width:16%">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($building_list))
									@foreach($building_list as $key => $list)
									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->building_title}}</td>
										<td>{{$list->building_code}}</td>
										<td>{{$list->campus_title}}</td>


										<td>
											<button data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->building_slug}}" data-type="building" data-toggle1="tooltip" title="Edit Building"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											<a data-confirm-url="{{URL::route('Building Delete',$list->building_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Building"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>

							{{isset($pagination_building_list) ? $pagination_building_list :''}}
						</div>
					</div>
				</div>
				<!--/building tab-->




				<!--room tab-->
				<div role="tabpanel" class="tab-pane fade in {{$tab=='room' ? 'active':''}}" id="room" aria-labelledby="room-tab">
					<div class="row">
						<div class="col-md-5">

							<form action="{{URL::route('Academic Settings Form Submit','room')}}" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label>Building Name</label>
									<select name="building_code"  class="form-control" required>
										<option value="">Select Building</option>
										@if(!empty($building_list))
										@foreach($building_list as $key => $building)
										<option value="{{$building->building_code}}">{{$building->building_title}}</option>
										@endforeach
										@endif
									</select>
								</div>

								<div class="row">
									<div class="form-group col-md-6">
										<label>Room Title</label>
										<input type="text" name="room_title" class="form-control" value="{{old('room_title')}}" required/>	
									</div>
									<div class="form-group col-md-6">
										<label>Room Type</label>
										<select name="room_type" class="form-control" required>
											<option value="">Select Type</option>
											<option value="Class Room">Class Room</option>
											<option value="Lab">Lab</option>
											<option value="Seminar">Seminar</option>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-md-6">
										<label>Room No.</label>
										<input type="number" min="1" max="100" name="room_no" class="form-control" value="{{old('room_no')}}" required/>	
									</div>
									<div class="form-group col-md-6">
										<label>Floor No.</label>
										<input type="number" min="1" max="50" name="floor_no" class="form-control" value="{{old('floor_no')}}" required/>	
									</div>
								</div>

								<div class="form-group">
									<label>Room Capacity</label>
									<input type="number" name="room_capacity" class="form-control" min="20" max="1000" value="{{old('room_capacity')}}" required/>
								</div>
								<div class="form-group">
									<label>Room Facilities</label>
									<textarea name="room_facilities" class="form-control">{{old('room_facilities')}}</textarea>
								</div>



								<div class="form-group">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<a href="{{url('/academic-settings/home')}}" class="btn btn-default">Cancel</a>
									<input type="submit" class="btn btn-primary" value="Save">
								</div>
							</form>
						</div>

						<!-- Room lists -->
						<div class="col-md-7"> 
							<label>Room Lists</label>
							<table class="table table-hover table-bordered table-striped" >
								<thead>
									<tr>
										<th>SL</th>
										<th>Title</th>
										<th>Room No</th>
										<th>Type</th>
										<th>Capacity</th>
										<th>Building</th>
										<th >Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($room_list))
									@foreach($room_list as $key => $list)

									<tr >
										<td>{{$key+1}}</td>
										<td>{{$list->room_title}}</td>
										<td>{{$list->room_code}}</td>
										<td>{{$list->room_type}}</td>
										<td>{{$list->room_capacity}}</td>
										<td>{{$list->building_title}}</td>

										<td>
											<a data-toggle="modal" data-target="#editModal" class="btn btn-default btn-xs edit_academic_settings" data-id="{{$list->room_slug}}" data-type="room" data-toggle1="tooltip" title="Edit Room"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

											<a data-confirm-url="{{URL::route('Room Delete',$list->room_slug)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Room"><i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
							{{ (isset($pagination_room_list)) ? $pagination_room_list: ''}}
						</div>
					</div>
				</div>
				<!--/room tab-->



			</div><!--/main tab content-->
		</div>
	</div>
</div>

<input type="hidden" class="site_url" value="{{url('/')}}">



<!-- Modal -->
<div id="editModal" class="modal fade" rtabindex="-1" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-body edit_view">
				<!-- dynamic content-->
				<div class="ajax_loader loading_icon"></div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div><!-- /Modal content-->
	</div>
</div><!-- /Modal -->




@stop