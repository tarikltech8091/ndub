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
		<!--end of error message*************************************-->
	</div>
</div>
<div class="row page_row">

	<div class="col-md-5 profile_tab">

		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">

			<ul class="nav nav-tabs" id="myTab">
				<li class="{{$tab=='faculty_notice' ? 'active' :''}}"><a data-toggle="tab" aria-expanded="true" aria-controls="faculty_notice" href="#faculty_notice"><i class="fa fa-newspaper-o"></i> Faculty Notice</a></li>
				<li class="{{$tab=='student_notice' ? 'active' :''}}"><a data-toggle="tab" aria-expanded="true" aria-controls="student_notice" href="#student_notice"><i class="fa fa-newspaper-o"></i> Student Notice</a></li>
			</ul>


			

			<div id="myTabContent" class="tab-content"><!--main tab content-->
				

				<div role="tabpanel"  id="faculty_notice"  class="tab-pane fade in active" aria-labelledby="faculty_notice-tab"><!--/faculty_notice tab-->
					<div class="panel panel-info">
						<div class="panel-body">
							<form action="{{url('/register/notice-board')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">


								<?php 
								$program_list =\App\Register::ProgramList();
								?>
								<div class="form-group program_form">
									<label for="Program">Program <span class="required-sign">*</span></label>
									<select class="form-control" name="notice_program">
										<option  value="all">All Program</option>
										@if(!empty($program_list))
										@foreach($program_list as $key => $list)
										<option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
										@endforeach
										@endif
									</select> 
								</div>


								<?php 
								$faculty_list =\DB::table('faculty_basic')->select('faculty_basic.*')->get();
								?>
								<div class="form-group faculty_form">
									<label for="Faculty">Faculty <span class="required-sign">*</span></label>
									<select class="form-control" name="notice_to">
										<option  value="">Select Faculty</option>
										<option  value="all">All</option>

										@if(!empty($faculty_list))
										@foreach($faculty_list as $key => $list)
										<option {{(old('faculty_name')== $list->faculty_id) ? "selected" :''}} value="{{$list->faculty_id}}">{{$list->faculty_id}} {{$list->first_name}} {{$list->last_name}}</option>
										@endforeach
										@endif
									</select> 
								</div>

								<div class="form-group">
									<label>Subject<span class="required-sign">*</span></label>
									<input type="text" name="notice_subject" class="form-control" placeholder="Title" />
								</div>

								<div class="form-group">
									<label>Description<span class="required-sign">*</span></label>
									<textarea name="notice_message" rows="10" class="form-control" id=""></textarea>
								</div>


								<div class="row">
									<div class="col-md-12">
										<div class="form-group pull-right">
											<input type="reset" class="btn btn-danger" value="Reset">
											<input type="submit" class="btn btn-success" value="Submit ">
										</div>
									</div>
								</div>
								<input type="hidden" name="notice_to_type" value="register_to_faculty">

							</form>
						</div>
					</div>

				</div><!-- tab-->



				<div role="tabpanel" id="student_notice" class="tab-pane fade" aria-labelledby="student_notice-tab"><!--/student_notice tab-->
					<div class="panel panel-info">
						<div class="panel-body">
							<form action="{{url('/register/notice-board')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">

								
								<?php 
								$program_list =\App\Register::ProgramList();
								?>
								<div class="form-group program_form">
									<label for="Program">Program <span class="required-sign">*</span></label>
									<select class="form-control" name="notice_program">
										<option  value="all">All</option>
									
										@if(!empty($program_list))
										@foreach($program_list as $key => $list)
										<option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
										@endforeach
										@endif
									</select> 
								</div>

								<div class="form-group selected_notice_type" >
									<label>Select Student</label>
									<input type="text" name="notice_to" placeholder="Student Id" id="hide_single_text_input" /> 
									All <input type="checkbox" name="notice_to" value="all" class="select_type"/>

								</div>



								<div class="form-group">
									<label>Subject<span class="required-sign">*</span></label>
									<input type="text" name="notice_subject" class="form-control" placeholder="Title" />
								</div>

								<div class="form-group">
									<label>Description<span class="required-sign">*</span></label>
									<textarea name="notice_message" rows="10" class="form-control" id=""></textarea>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group pull-right ">
											<input type="reset" class="btn btn-danger" value="Reset">	
											<input type="submit" class="btn btn-success" value="Submit ">
										</div>
									</div>
								</div>
								<input type="hidden" name="notice_to_type" value="register_to_student">

							</form>
						</div>
					</div>
				</div><!--/ tab-->

				
			</div><!--/main tab content-->
		</div>
	</div>



	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Register All Notice</div>
			<div class="panel-body">
				<div class="row">
					<div class="form-group col-md-9"  style="margin-left:15px;">
						<label for="AcademicYear">Notice Type</label>
						<select class="form-control notice_to_type" name="notice_to_type" >
							<option value="">All Type Notice</option>
							<option {{(isset($_GET['notice_to_type']) && ($_GET['notice_to_type']=='register_to_faculty')) ? 'selected':''}} value="register_to_faculty">Register To Faculty</option>
							<option {{(isset($_GET['notice_to_type']) && ($_GET['notice_to_type']=='register_to_student')) ? 'selected':''}} value="register_to_student">Register To Student</option>
						</select>
					</div>
					<div class="col-md-2" style="margin-top:25px;margin-left:35px;">
						<button class="btn btn-primary register_notice_search" data-toggle="tooltip" title="Search Notice">Serach</button>
					</div>
				</div>

				<div class="col-md-12">
					<table class="table table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>SL</th>
								<!-- <th>Date</th> -->
								<th>Notice Subject</th>
								<th>Notice For</th>
								<th>Program</th>
								<th>Sem.</th>
								<th>Year</th>
								<th>Details</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($all_notice_list) && count($all_notice_list) > 0)
							@foreach($all_notice_list as $key => $list)
							<tr>
								<!-- <td>{{$list->created_at}}</td> -->
								<td>{{$key+1}}</td>
								<td>{{$list->notice_subject}}</td>
								<td>{{$list->notice_to}}</td>
								<td>{{(isset($list) && ($list->program_title)) ? $list->program_title : 'all'}}</td>
								<td>{{$list->semester_title}}</td>
								<td>{{$list->notice_year}}</td>
								<td>{{str_limit($list->notice_message, 10)}}</td>
								<td>
									<a href="{{url('/register/edit/'.$list->notice_to_type.'/'.$list->notice_tran_code)}}" data-type="{{$list->notice_to_type}}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Notice"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a data-confirm-url="{{url('/register/notice-board/delete',$list->notice_tran_code)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Notice"><i class="fa  fa-trash-o"></i></a>
								</a>
							</td>
						</tr>
						@endforeach
						@else
						<tr class="text-center">
							<td colspan="8">No Data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($notice_pagination) ? $notice_pagination:""}}
			</div>


			<input type="hidden" class="site_url" value="{{url('/')}}">
			<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
		</div>
	</div>
</div>
</div>


@stop