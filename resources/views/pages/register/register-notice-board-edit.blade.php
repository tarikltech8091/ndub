@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<div class="row"><!--message-->
	<div class="col-md-6">
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
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->



<div class="row page_row">

	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">Update Notice</div>
			<div class="panel-body"><!--info body-->

				<div class="col-md-12">
					@if($form_name=='register_to_faculty')
					<div class="panel panel-info">
						<div class="panel-body">
							<form action="{{url('/register/notice-board/edit/'.$edit_register_notice->notice_tran_code)}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">

								<?php 
								$program_list =\App\Register::ProgramList();
								?>
								<div class="form-group">
									<label for="Program">Program <span class="required-sign">*</span></label>
										<select class="form-control" name="notice_program">
											<option  value="all">All</option>

											@if(!empty($program_list))
											@foreach($program_list as $key => $list)
											<option {{($edit_register_notice->notice_program == $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
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
											<option  value="all">All</option>
											@if(!empty($faculty_list))
											@foreach($faculty_list as $key => $list)
											<option {{($edit_register_notice->notice_to== $list->faculty_id) ? "selected" :''}} value="{{$list->faculty_id}}">{{$list->faculty_id}}</option>
											@endforeach
											@endif

										</select> 
									</div>

									<div class="form-group">
										<label>Title</label>
										<input type="text" name="notice_subject" class="form-control" value="{{$edit_register_notice->notice_subject}}" />
									</div>

									<div class="form-group">
										<label>Description</label>
										<textarea name="notice_message" rows="10" class="form-control" value="" id="noticeboard">{{$edit_register_notice->notice_message}}</textarea>
									</div>

									<div class="form-group pull-right">
									<a class="btn btn-danger" href="{{url('/register/notice-board')}}" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
										<input type="submit" class="btn btn-success" data-toggle="tooltip" title="Update Notice" value="Update" >
									</div>
								</form>
							</div>
						</div>
						@endif


						@if($form_name=='register_to_student')
						<div class="panel panel-info">
							<div class="panel-body">
								<form action="{{url('/register/notice-board/edit/'.$edit_register_notice->notice_tran_code)}}" method="post">
									<input type="hidden" name="_token" value="{{csrf_token()}}">

									<?php 
									$program_list =\App\Register::ProgramList();
									?>
									<div class="form-group">
										<label for="Program">Program <span class="required-sign">*</span></label>
											<select class="form-control" name="notice_program">
												<option  value="all">All</option>

												@if(!empty($program_list))
												@foreach($program_list as $key => $list)
												<option {{($edit_register_notice->notice_program == $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
												@endforeach
												@endif
											</select> 
										</div>


										<div class="form-group" >
											<label>Select Student</label>
											<input type="text" name="notice_to" value="{{(($edit_register_notice->notice_to)!='all_student')?$edit_register_notice->notice_to:''}}" />
											All <input type="checkbox" name="notice_to" value="all_student" {{(($edit_register_notice->notice_to)=='all_student')? 'checked' :''}}/>

										</div>

										<div class="form-group">
											<label>Title</label>
											<input type="text" name="notice_subject" class="form-control" value="{{$edit_register_notice->notice_subject}}" />
										</div>

										<div class="form-group">
											<label>Description</label>
											<textarea name="notice_message" rows="10" class="form-control" value="" id="noticeboard">{{$edit_register_notice->notice_message}}</textarea>
										</div>

										<div class="form-group pull-right">
											<a class="btn btn-danger" href="{{url('/register/notice-board')}}" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
											<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Notice ">
										</div>
									</form>
								</div>
							</div>
							@endif
						</div>

					</div><!--/info body-->
				</div>
			</div>


		</div>


		@stop
