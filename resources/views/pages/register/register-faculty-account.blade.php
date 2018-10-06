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


<!--Content Image-->
<div class="row page_row">
	<form action="{{url('/register/faculty-account-registration')}}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{csrf_token()}}">

		<div class="form-group col-md-10">

			<div class="panel panel-default">
				<div class="panel-body ">
					<div class="alert alert-success">
						<div class="row">


							<?php 
							$department_list =\App\Register::DepartmentList();
							?>
							<div class="form-group col-md-6">
								<label for="Department">Department <span class="required-sign">*</span></label>
								<select class="form-control department_code" name="department">
									<option value="">Select Department</option>

									@if(!empty($department_list))
									@foreach($department_list as $key => $list)
									<option {{(old('department')== $list->department_no) ? "selected" :''}} value="{{$list->department_no}}">{{$list->department_title}}</option>
									@endforeach
									@endif
								</select>
							</div>

							<?php 
							$program_list =\App\Register::ProgramList();
							?>
							<div class="form-group col-md-6">
								<label for="Program">Program <span class="required-sign">*</span></label>
								<select class="form-control ajax_program_list" name="program">
									<option value="">Select Program</option>

<!-- 							@if(!empty($program_list))
									@foreach($program_list as $key => $list)
									<option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
									@endforeach
									@endif -->
								</select> 

							</div>
						</div>


						<div class="row">
							<div class="form-group col-md-4">
								<label for="First Name">First Name <span class="required-sign">*</span></label>
								<input type="text" class="form-control uppercase_name" name="first_name" placeholder="First Name" value="{{old('first_name')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Middle Name">Middle Name </label>
								<input type="text" class="form-control uppercase_name" name="middle_name" placeholder="Middle Name" value="{{old('middle_name')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Last Name">Last Name <span class="required-sign">*</span></label>
								<input type="text" class="form-control uppercase_name" name="last_name" placeholder="Last Name" value="{{old('last_name')}}">
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">
								<label for="Mobile">Mobile <span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="mobile" placeholder="Ex:- 01XXXXXXXXX" value="{{old('mobile')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Email">Email <span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="email" placeholder="Ex:- example@example.com" value="{{old('email')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Gender">Gender <span class="required-sign">*</span></label>
								<select class="form-control" name="gender">
									<option  {{(old('gender')== "male") ? "selected" :''}} value="male">Male</option>
									<option {{(old('gender')== "female") ? "selected" :''}} value="female">Female</option>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">
								<label for="Marital Status">Marital Status <span class="required-sign">*</span></label>
								<select class="form-control" name="marital_status">
									<option  {{(old('marital_status')== "single") ? "selected" :''}} value="single">Single</option>
									<option {{(old('marital_status')== "married") ? "selected" :''}} value="married">Married</option>
									<option {{(old('marital_status')== "other") ? "selected" :''}} value="other">Other</option>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="Nationality">Nationality <span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="nationality" placeholder="Nationality" value="{{old('nationality')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Religion">Religion <span class="required-sign">*</span></label>
								<select class="form-control" name="religion">
									<option value="">--Select Religion--</option>
									<option {{(old('religion')== "islam") ? "selected" :''}}  value="islam">Islam</option>
									<option {{(old('religion')== "christianity") ? "selected" :''}} value="christianity">Christianity</option>
									<option {{(old('religion')== "hinduism") ? "selected" :''}} value="hinduism">Hinduism</option>
									<option {{(old('religion')== "buddhism") ? "selected" :''}} value="buddhism">Buddhism</option>
								</select> 
							</div>
						</div>


						<div class="row">
							<div class="form-group col-md-4">
								<label for="Blood Group">Blood Group</label>
								<select class="form-control" name="blood_group">
									<option {{(old('blood_group')== "A+") ? "selected" :''}}  value="A+">A (+)</option>
									<option {{(old('blood_group')== "A-") ? "selected" :''}} value="A-">A (-)</option>
									<option {{(old('blood_group')== "AB+") ? "selected" :''}} value="AB+">AB (+)</option>
									<option {{(old('blood_group')== "AB-") ? "selected" :''}} value="AB-">AB (-)</option>
									<option {{(old('blood_group')== "O+") ? "selected" :''}}  value="O+">O (+)</option>
									<option {{(old('blood_group')== "O-") ? "selected" :''}} value="O-">O (-)</option>
									<option {{(old('blood_group')== "B+") ? "selected" :''}} value="B+">B (+)</option>
									<option {{(old('blood_group')== "B-") ? "selected" :''}} value="B-">B (-)</option>
								</select>
							</div>

							<div class="col-md-4">
								<label class="control-label">Date of Birth <span class="required-sign">*</span></label>
								<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="date_of_birth"  size="16" type="text" value="" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>

							</div>


							<div class="col-md-4">
								<label class="control-label">Faculty Join Date <span class="required-sign">*</span></label>
								<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="faculty_join_date" size="16" type="text" value="" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>

							</div>

						</div>


						<div class="row">
							<div class="form-group col-md-4">
								<label for="Postal Code">Postal Code <span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="postal_code" placeholder="Postal Code" value="{{old('postal_code')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="City">City<span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="city" placeholder="City" value="{{old('city')}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Country">Country <span class="required-sign">*</span></label>
								<input type="text" class="form-control" name="country" placeholder="Country" value="{{old('country')}}">
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="Professor Designation">Professional Designation <span class="required-sign">*</span></label>
								
								<input type="text" class="form-control" name="pro_designation" placeholder="Professional Designation" value="{{old('pro_designation')}}">
							</div>
							<div class="form-group col-md-6">
								<label for="Contact Type">Contact Type<span class="required-sign">*</span></label>
								<select class="form-control" name="contact_type">
									<option {{(old('contact_type')== "Present") ? "selected" :''}}  value="Present">Present</option>
									<option {{(old('contact_type')== "Permanent") ? "selected" :''}} value="Permanent">Permanent</option>
								</select>
							</div>
						</div>



						<div class="row">
							<div class="form-group col-md-6">
								<label for="Others Designation">Others Designation</label>
								<textarea class="form-control" name="others_designation" value="{{old('others_designation')}}"  placeholder="Others Designation"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label for="Contact Detail">Contact Detail<span class="required-sign">*</span></label>
								<textarea class="form-control" name="contact_detail" placeholder="Contact Detail" value="{{old('contact_detail')}}"></textarea>

							</div>

						</div>



						<input type="hidden" name="image_url" id="image_url" value="">

						<br>
						<div class="row">
							<div class="form-group col-md-12 ">
								<div class="pull-right">
									<a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
									<input type="submit" class="btn btn-primary " value="Submit">
								</div>
							</div>
						</div>


					</div>
				</div>
			</div>

		</form>
	</div>


	<div class="col-md-2" >
		<div class="panel panel-default">
			<div class="panel-body">

				<div id="validation-errors"></div>
				<label>Passposrt Size Photo (Colored) <span class="required-sign">*</span></label>    
				<div id="demo">
					<img  src="{{old('image_url') ? asset(old('image_url')):asset('images/profile.png')}}" alt="img">
				</div>
				<div class="uploader">
					<form class="example" id="upload" role="form" enctype="multipart/form-data" method="POST" action="{{url('/register/register-faculty-account/image-upload')}}" >
						<div class="fileinputs">
							<input type="hidden" name="_token" value="{{csrf_token()}}" />

							<span class="btn btn-primary btn-file span-photo"> 
								Browse Photo<input name="image" id="image" noscript="true" type="file" name="photo" class="form-control btn-file-browse-photo" value="">
							</span>
							<input type="hidden" name="image_url" id="image_url" value="">

						</div>

					</form>
					<div class='image_loader'></div>

				</div>
			</div>
		</div>
	</div>
	<!--/Content Image-->



</div>
</div>


@stop