@extends('layout.master')
@section('content')

@include('layout.bradecrumb')


<div class="row page_row"><!--message-->
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
</div><!--/message-->




<div class="row page_row">
	<div class="col-md-9 profile_tab">
		<div data-example-id="togglable-tabs" role="tabpanel" class="bs-example bs-example-tabs">
			<ul role="tablist" class="nav nav-tabs" id="myTab">
				<li class="active" role="presentation"><a aria-expanded="true" aria-controls="profile" data-toggle="tab" role="tab" id="profile-tab" href="#profile"><i class="fa fa-sun-o"></i>Profile</a></li>
				<li role="presentation"><a aria-controls="contacts" data-toggle="tab" id="contacts-tab" role="tab" href="#contacts"><i class="fa fa-thumb-tack"></i> Contacts</a></li>


			</ul>

			<div class="tab-content" id="myTabContent">
				<div aria-labelledby="profile-tab" id="profile" class="tab-pane fade in active" role="tabpanel"><!--Profile Tab-->
					<div class="row personal_details page_row">
						<form action="{{url('/faculty/edit-profile', $faculty_profile_edit->faculty_id)}}" method="post">
							<div class="form-group col-md-6">
								<label>Faculty ID</label>
								<input type="text" name="faculty_id" class="form-control" value="{{$faculty_profile_edit->faculty_id}}" disabled=""/>
							</div>

							<div class="form-group col-md-6">
								<label>Joining Date</label>
								<div class="input-group date to_date col-md-9" data-date="" data-date-format="yyyy-mm-dd" data-link-field="to_dtp_input" data-link-format="yyyy-mm-dd">
									<input class="form-control" size="16" type="text" name="faculty_join_date" value="{{$faculty_profile_edit->faculty_join_date}}" readonly disabled="">
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
								<input type="hidden" id="to_dtp_input" value="" /><br/>
							</div>

							<div class="form-group col-md-6">
								<label>Program</label>
								<input type="text" name="program" class="form-control" value="{{$faculty_profile_edit->program_title}}" disabled />
							</div>

							<div class="form-group col-md-6">
								<label>Designation</label>
								<input type="text" name="pro_designation" class="form-control" value="{{$faculty_profile_edit->pro_designation}}" disabled/>
							</div>

							<div class="form-group col-md-4">
								<label>First Name</label>
								<input type="text" name="first_name" class="form-control" value="{{$faculty_profile_edit->first_name}}" disabled />
							</div>

							<div class="form-group col-md-4">
								<label>Middle Name</label>
								<input type="text" name="middle_name" class="form-control" value="{{$faculty_profile_edit->middle_name}}" disabled/>
							</div>

							<div class="form-group col-md-4">
								<label>Last Name</label>
								<input type="text" name="last_name" class="form-control" value="{{$faculty_profile_edit->last_name}}" disabled />
							</div>

							<div class="form-group col-md-4">
								<label>Blood Group</label>
								<select class="form-control" name="blood_group" disabled>
									<option value="{{$faculty_profile_edit->blood_group}}">{{$faculty_profile_edit->blood_group}}</option>
								</select>
							</div>

							<div class="form-group col-md-4">
								<label>Nationality</label>
								<input type="text" name="nationality" class="form-control" value="{{$faculty_profile_edit->nationality}}" disabled/>
							</div>

							<div class="form-group col-md-4">
								<label>Relegion</label>
								<input type="text" name="religion" class="form-control" value="{{$faculty_profile_edit->religion}}" disabled/>
							</div>


							<div class="form-group col-md-6">
								<label>Email</label>
								<input type="text"  name="email" class="form-control"  value="{{$faculty_profile_edit->email}}" /><br/>
							</div>

							<div class="form-group col-md-6">
								<label>Mobile</label>
								<input type="text" name="mobile" class="form-control" value="{{$faculty_profile_edit->mobile}}" />
							</div>


							<div class="form-group col-md-12">
								<div class="pull-right">
									<input type="submit" class="btn btn-danger" value="Cancel" data-toggle="tooltip" title="Cancel">
									<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Profile Basic Information">
								</div>
							</div>
						</form>
					</div>

				</div><!--/Profile Tab-->
				<div aria-labelledby="contacts-tab" id="contacts" class="tab-pane fade" role="tabpanel"><!--Contacts Tab-->
					<div class="row contact_details page_row">
						<form action="{{url('/faculty/update-contract-profile', $faculty_profile_edit->faculty_tran_code)}}" method="post">
							<div class="form-group col-md-4">
								<label>City</label>
								<input type="text" name="city" class="form-control" value="{{$faculty_profile_edit->city}}" />
							</div>

							<div class="form-group col-md-4">
								<label>Postal Code</label>							
								<input type="text" name="postal_code" class="form-control" value="{{$faculty_profile_edit->postal_code}}" />
							</div>

							<div class="form-group col-md-4">
								<label>Country</label>
								<input type="text" name="country" class="form-control" value="{{$faculty_profile_edit->country}}"  />
							</div>

							@if($faculty_profile_edit->contact_type =='Permanent')

							<div class="form-group col-md-12">
								<label>Permanent Address</label>
								<textarea rows="3" name="contact_detail" class="form-control">{{$faculty_profile_edit->contact_detail}}</textarea>
							</div>

							@elseif($faculty_profile_edit->contact_type =='Present')
							<div class="form-group col-md-12">
								<label>Present Address</label>
								<textarea rows="3" name="contact_detail"  class="form-control">{{$faculty_profile_edit->contact_detail}}</textarea>			
							</div>
							@endif



							<div class="form-group col-md-12">
								<div class="pull-right">
									<input type="submit" class="btn btn-danger" value="Cancel" data-toggle="tooltip" title="Cancel">
									<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Profile Contact Information">
								</div>
							</div>
						</form>

					</div><!--/contact details-->
				</div><!--/Contacts Tab-->
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