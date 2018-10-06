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
	<div class="col-md-9 profile_tab">
		<div data-example-id="togglable-tabs" role="tabpanel" class="bs-example bs-example-tabs">
			<ul role="tablist" class="nav nav-tabs" id="myTab">
				<li class="active" role="presentation"><a aria-expanded="true" aria-controls="profile" data-toggle="tab" role="tab" id="profile-tab" href="#profile"><i class="fa fa-sun-o"></i>Profile</a></li>
				<li role="presentation"><a aria-controls="contacts" data-toggle="tab" id="contacts-tab" role="tab" href="#contacts"><i class="fa fa-thumb-tack"></i> Contacts</a></li>
				<li role="presentation"><a aria-controls="parents" data-toggle="tab" id="parents-tab" role="tab" href="#parents"><i class="fa fa-user-plus"></i>Parents</a></li>
				<li role="presentation"><a aria-controls="gurdian" data-toggle="tab" id="gurdian-tab" role="tab" href="#gurdian"><i class="fa fa-user"></i>Guardian</a></li>
				<li role="presentation"><a aria-controls="qualification" data-toggle="tab" id="qualification-tab" role="tab" href="#qualification"><i class="fa fa-graduation-cap"></i>Educational Qualification</a></li>
			</ul>
			@foreach($student_profile_edit as $key => $edit)
			<div class="tab-content" id="myTabContent">
				<div aria-labelledby="profile-tab" id="profile" class="tab-pane fade in active" role="tabpanel"><!--Profile Tab-->
					<form action="{{url('/student/basic-info-update')}}" method="post">
						<div class="row page_row">
							<div class="form-group col-md-3">
								<label>Full Name</label>
								<input type="text" name="title" class="form-control" value="{{$edit->first_name}} {{$edit->middle_name}} {{$edit->last_name}}"  disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Student ID</label>
								<input type="text" name="student_serial_no" class="form-control" value="{{$edit->student_serial_no}}"  disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Date of Birth</label>
								<div class="input-group date to_date col-md-9" data-date="" data-date-format="yyyy-mm-dd" data-link-field="to_dtp_input" data-link-format="yyyy-mm-dd">
									<input class="form-control" size="16" type="text" placeholder="Birth Date" value="{{$edit->date_of_birth}}" readonly disabled>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
								<input type="hidden" id="to_dtp_input" value="" /><br/>
							</div>
							<div class="form-group col-md-3">
								<label>Gender</label>
								<input type="text" name="gender" class="form-control" value="{{$edit->gender}}" disabled/>
							</div>

						</div>
						<div class="row page_row">
							<div class="form-group col-md-3">
								<label>Department</label>
								<input type="text" name="department_title" class="form-control" value="{{$edit->department_title}}"  disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Program</label>
								<input type="text" name="program_title" class="form-control" value="{{$edit->program_title}}"  disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Batch</label>
								<input type="text" name="title" class="form-control" value="{{$edit->semester_title}} {{$edit->academic_year}}"  disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Blood Group</label>
								<input type="text" name="title" class="form-control" value="{{$edit->blood_group}}" disabled/>
							</div>

						</div>
						<div class="row page_row">


							<div class="form-group col-md-3">
								<label>Relegion</label>
								<input type="text" name="religion" class="form-control" value="{{strtoupper($edit->religion)}}" disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Nationality</label>
								<input type="text" name="nationality" class="form-control" value="{{$edit->nationality}}" disabled/>
							</div>
							<div class="form-group col-md-3">
								<label>Mobile</label>
								<input type="text" name="mobile" class="form-control" value="{{$edit->mobile}}" />
							</div>

							<div class="form-group col-md-3">
								<label>Email</label>
								<input type="text" name="email"  class="form-control" value="{{$edit->email}}" />
							</div>
							<div class="form-group col-md-12">
								<div class="pull-right">
									<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Profile Basic Information">
								</div>
							</div>
						</div>
					</form>			
				</div><!--/Profile Tab-->
				<div aria-labelledby="contacts-tab" id="contacts" class="tab-pane fade" role="tabpanel"><!--Contacts Tab-->

					<div class="row page_row">
						<form action="{{url('/student/contact-info-update', $edit->student_tran_code)}}" method="post">
							@foreach($student_contact as $key => $student_contact)
							@if((($student_contact->student_tran_code) == ($edit->student_tran_code)) && (($student_contact->contact_type)=='present'))


							<div class="form-group col-md-4">
								<label>Country</label>
								<input type="text" name="country" class="form-control" value="{{$student_contact->country}}" />
							</div>


							<div class="form-group col-md-4">
								<label>City</label>
								<input type="text" name="city" class="form-control" value="{{$student_contact->city}}" />
							</div>


							<div class="form-group col-md-4">
								<label>Postal Code</label>
								<input type="text" name="postal_code" class="form-control" value="{{$student_contact->postal_code}}" />
							</div>


							<div class="form-group col-md-12">
								<label>Persent Address</label>
								<textarea rows="3" name="contact_detail"  class="form-control">{{$student_contact->contact_detail}}</textarea>			
							</div>


							@endif
							@endforeach
							<div class="form-group col-md-12">
								<div class="pull-right">
									<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Profile Contact Information">
								</div>
							</div>
						</form>
					</div><!--/contact details-->
					
				</div><!--/Contacts Tab-->
				<div aria-labelledby="gurdian-tab" id="gurdian" class="tab-pane fade" role="tabpanel"><!--Gurdian Tab-->

					@if(!empty($student_info))
					<div class="row page_row">
						<form action="{{url('/student/gurdian-profile/update', $student_info->student_tran_code)}}" method="post">
							<div class="form-group col-md-6">
								<label>Gurdian Name</label>
								<input type="text" name="gurdian_name" class="form-control" value="{{$student_info->gurdian_name}}" />
							</div>
							
							<input type="hidden" name="relation" class="form-control" value="Local_Guardian" />					
							<div class="form-group col-md-6">
								<label>Occupation</label>
								<input type="text" name="occupation" value="{{$student_info->occupation}}" class="form-control"  />
							</div>


								<input type="hidden" name="emergency_contact"  value="{{$student_info->emergency_contact}}" class="form-control" />

							<div class="form-group col-md-6">
								<label>Mobile</label>
								<input type="text" name="mobile" value="{{$student_info->mobile}}" class="form-control"  />
							</div>

							<div class="form-group col-md-6">
								<label>Email</label>
								<input type="text" name="email" value="{{$student_info->email}}" class="form-control" /><br/>
							</div>

							<div class="form-group col-md-12">
								<div class="pull-right">
									<input type="hidden" value="update_gurdian"  name="action">
									<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Guardian Information">
								</div>
							</div>
						</form>
					</div>

					@endif


				</div><!--Gurdian Tab-->
				<div aria-labelledby="parents-tab" id="parents" class="tab-pane fade" role="tabpanel"><!--Parents Tab-->
					@foreach($student_gurdian as $key => $student_gurdian)
					@if($student_gurdian->student_tran_code==$edit->student_tran_code && $student_gurdian->relation=='Father')
					<div class="row page_row">
						<div class="form-group col-md-4">
							<label>Father Name</label>
							<input type="text" name="title" class="form-control" value="{{$student_gurdian->gurdian_name}}"  disabled/>
						</div>

						<div class="form-group col-md-4">
							<label>Father's Occupation</label>
							<input type="text" name="title" class="form-control" value="{{$student_gurdian->occupation}}" disabled/>
						</div>
						<div class="form-group col-md-4">
							<label>Father's Mobile</label>
							<input type="text" class="form-control" value="{{$student_gurdian->mobile}}" disabled/><br/>
						</div>
					</div>
					@endif

					@if($student_gurdian->student_tran_code==$edit->student_tran_code && $student_gurdian->relation=='Mother')
					<div class="row page_row">
						<div class="form-group col-md-4">
							<label>Mother Name</label>
							<input type="text" name="title" class="form-control" value="{{$student_gurdian->gurdian_name}}" disabled/>
						</div>
						<div class="form-group col-md-4">
							<label>Mother's Occupation</label>
							<input type="text" name="title" class="form-control" value="{{$student_gurdian->occupation}}" disabled/>
						</div>
						<div class="form-group col-md-4">
							<label>Mother's Mobile</label>
							<input type="text" class="form-control" value="{{$student_gurdian->mobile}}" disabled/><br/>
						</div>
					</div>
					@endif
					@endforeach
				</div><!--/Parents Tab-->

				
				<div aria-labelledby="qualification-tab" id="qualification" class="tab-pane fade" role="tabpanel">
					@foreach($student_academic as $key => $student_academic)
					@if($student_academic->student_tran_code==$edit->student_tran_code && ($student_academic->result_gpa != ''))
					<div class="row page_row">
						<div class="form-group col-md-2">
							<label>Result Type</label>
							<input type="text" class="form-control" value="{{strtoupper($student_academic->exam_type)}}" disabled />
						</div>

						<div class="form-group col-md-2">
							<label>Institution</label>
							<input type="text" class="form-control" value="{{strtoupper($student_academic->institute_name)}}" disabled />
						</div>

						<div class="form-group col-md-2">
							<label>Roll Number</label>
							<input type="text" name="title" class="form-control" value="{{$student_academic->exam_roll_number}}" disabled />
						</div>

						<div class="form-group col-md-2">
							<label>Passing Year</label>
							<input type="text" name="title" class="form-control" value="{{$student_academic->passing_year}}" disabled />
						</div>

						<div class="form-group col-md-2">
							<label>Board</label>
							<input type="text" name="title" class="form-control" value="{{strtoupper($student_academic->exam_board)}}" disabled />
						</div>

						<div class="form-group col-md-2">
							<label>CGPA</label>
							<input type="text" name="title" class="form-control" value="{{$student_academic->result_gpa}}" disabled />
						</div>

					</div>
					@endif
					@endforeach
				</div><!--/Qualification Tab-->


			</div>
			@endforeach
		</div>
	</div>

	<!--sidebar widget-->
	<div class="col-md-3">
		@include('pages.student.student-widget')
	</div>
</div>
@stop