 @extends('layout.master')
 @section('content')
 @include('layout.bradecrumb')
 
 <div class="row page_row"><!--message-->
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
 		<!--*******************************End of error message*************************************-->
 	</div>
 </div><!--/message-->

 <div class="row page_row">
 	<div class="col-md-12">
 		<div class="panel panel-info">
 			<div class="panel-body">
 				<form action="{{url('/faculty/notice-board/edit/'.$edit_faculty_notice->notice_tran_code)}}" method="post">
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
 							<option {{($edit_faculty_notice->notice_program == $list->program_id) ? "selected" :''}} 
 								value="{{$list->program_id}}">{{$list->program_title}}</option>
 								@endforeach
 								@endif
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
 								<option  value="all">All</option>
 								@if(!empty($select_course))
 								@foreach($select_course as $key => $list)
 								<option {{($edit_faculty_notice->notice_to == $list->assigned_course_id) ? "selected" :''}} value="{{$list->assigned_course_id}}">{{$list->assigned_course_title}}</option>
 								@endforeach
 								@endif

 							</select>
 						</div>

 						<div class="form-group">
 							<label>Title</label>
 							<input type="text" name="notice_subject" class="form-control" value="{{$edit_faculty_notice->notice_subject}}" />
 						</div>

 						<div class="form-group">
 							<label>Description</label>
 							<textarea name="notice_message" rows="10" class="form-control" value="" id="noticeboard">{{$edit_faculty_notice->notice_message}}</textarea>
 						</div>

 						<div class="modal-footer">
 							<a href="{{url('/faculty/notice-board')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
 							<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Notice">
 						</div>
 					</form>
 				</div>
 			</div>
 		</div>
 	</div>

 	@stop