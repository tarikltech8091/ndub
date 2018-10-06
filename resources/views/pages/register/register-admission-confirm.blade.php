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

		<form  action="{{url('/register/admission/confirm')}}" method="get">
			<div class="col-md-12 panel panel-body search_panel_bg_color">
				<div class="form-group col-md-6">
					@if(isset($_GET['applicant_serial_no']))
					<input type="text" class="form-control search_width" name="applicant_serial_no" value="{{$_GET['applicant_serial_no']}}">
					@else
					<input type="text" class="form-control search_width" name="applicant_serial_no" value="{{old('applicant_serial_no')}}" placeholder="Search for...">
					@endif

					<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Student Admission">Search !</button>

				</div>

			</div>
		</form>
	</div>



</div>



@if(isset($_GET['applicant_serial_no']) && !empty($_GET['applicant_serial_no']))
@if(isset($applicant_info_basic)  && ($applicant_info_basic->applicant_eligiblity !=6))
<div class="col-md-12">
	<div class="panel panel-body page_row">
		<div class="alert alert-info">
			<a href="{{url('/register/admission/confirm')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>

			<form action="{{\Request::fullUrl()}}" method="post">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="row basic_block_form"><!--basic block form-->
					<div class="col-md-10">
						<div class="row">
							<div class="form-group col-md-4">
								<label for="First Name">First Name</label>
								<input type="text" class="form-control" name="first_name"  value="{{$applicant_info_basic->first_name}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Middle Name">Middle Name</label>
								<input type="text" class="form-control" name="middle_name"  value="{{$applicant_info_basic->middle_name}}">
							</div>
							<div class="form-group col-md-4">
								<label for="Last Name">Last Name</label>
								<input type="text" class="form-control" name="last_name" value="{{$applicant_info_basic->last_name}}">
							</div>
						</div>
						<div class="row">


							<?php 
							$program_list =\App\Applicant::ProgramList();

							?>

							<div class="form-group col-md-4">
								<label for="Program">Program </label>
								<select class="form-control program" name="program" >
									@if(!empty($program_list))
									@foreach($program_list as $key => $list)
									<option {{(($applicant_info_basic->program)== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
									@endforeach
									@endif
								</select>
							</div>

							<div class="form-group col-md-4">
								<label for="Semester">Trimester</label><br>
								<?php
								$semester_list=\DB::table('univ_semester')->get();
								?>
								<select class="form-control" name="semester">
									@if(!empty($semester_list))
									@foreach($semester_list as $key => $semester)
									<option {{(isset($applicant_info_basic->semester) && (($applicant_info_basic->semester)==($semester->semester_code))) ? 'selected':''}} value="{{$semester->semester_code}}">{{$semester->semester_title}}</option>
									@endforeach
									@endif
								</select>
							</div>


							<div class="form-group col-md-4">
								<label for="AcademicYear">Academic Year</label>
								<select class="form-control" name="academic_year">
									<option {{(isset($applicant_info_basic->academic_year) && (($applicant_info_basic->academic_year)==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
									<option {{(isset($applicant_info_basic->academic_year) && (($applicant_info_basic->academic_year)==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
								</select>
							</div>

						</div>
					</div>

					<div class="col-md-2">
						<div id="demo">
							<img  src="{{asset($applicant_info_basic->app_image_url)}}" alt="{{$applicant_info_basic->applicant_serial_no}}" title="{{$applicant_info_basic->applicant_serial_no}}">
						</div>
					</div>

				</div><!--/basic block form-->
				<hr style="border-width: 1px;">
				<br>
				
				<div class="row basic_block_form"><!--academic form-->
					<div class="col-md-12">
						<div class="form-group col-md-2">
							<label >Exam Type</label>
							<input type="text" class="form-control" name="exam_name"  value="{{$applicant_academic[0]->exam_type}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Group</label>
							<input type="text" class="form-control" name="group_name"  value="{{$applicant_academic[0]->exam_group}}" disabled>
						</div>

						<div class="form-group col-md-2">
							<label >Roll Number</label>
							<input type="text" class="form-control" name="roll_number"  value="{{$applicant_academic[0]->exam_roll_number}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Board</label>
							<input type="text" class="form-control" name="academic_year_name"  value="{{$applicant_academic[0]->exam_board}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Institute Name</label>
							<input type="text" class="form-control" name="ssc_institute_name"  value="{{$applicant_academic[0]->institute_name}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Passing Year</label>
							<input type="text" class="form-control" name="ssc_olevel_year" placeholder="Year" value="{{$applicant_academic[0]->passing_year}}" disabled>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group col-md-8">
							<label >Subjects</label>
						</div>
						<div class="form-group col-md-4">
							<label >Grade earned</label>
						</div>
					</div>

					<?php $ssc_subject_detail = unserialize($applicant_academic[0]->academic_detail); ?>
					@if(!empty($ssc_subject_detail))
					@foreach($ssc_subject_detail as $key => $details)
					<div class="col-md-12 form-group">
						<div class=" col-md-8">
							<input type="text" class="form-control " name="ssc_olevel_year" placeholder="Year" value="{{$details['subject_name']}}" disabled>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control " name="ssc_olevel_year" placeholder="Year" value="{{$details['grade']}}" disabled>
						</div>
					</div>
					@endforeach
					@endif

					<div class="col-md-12">
						<div class="col-md-8">
							<label class="control-label float-right-margin-top-6">Total GPA (With optional subject) </label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" name="total_ssc_olevel_gpa" placeholder="Total GPA" value="{{$applicant_academic[0]->result_gpa}}" disabled>
						</div>
					</div>

					<hr style="border-width: 1px;">
					<br>

					<div class="col-md-12">
						<div class="form-group col-md-2">
							<label >Exam Type</label>
							<input type="text" class="form-control" name="exam_name"  value="{{$applicant_academic[1]->exam_type}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Group</label>
							<input type="text" class="form-control" name="group_name"  value="{{$applicant_academic[1]->exam_group}}" disabled>
						</div>

						<div class="form-group col-md-2">
							<label >Roll Number</label>
							<input type="text" class="form-control" name="roll_number"  value="{{$applicant_academic[1]->exam_roll_number}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Board</label>
							<input type="text" class="form-control" name="academic_year_name"  value="{{$applicant_academic[1]->exam_board}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Institute Name</label>
							<input type="text" class="form-control" name="hsc_institute_name"  value="{{$applicant_academic[1]->institute_name}}" disabled>
						</div>
						<div class="form-group col-md-2">
							<label >Passing Year</label>
							<input type="text" class="form-control" name="ssc_olevel_year" placeholder="Year" value="{{$applicant_academic[1]->passing_year}}" disabled>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group col-md-8">
							<label >Subjects</label>
						</div>
						<div class="form-group col-md-4">
							<label >Grade earned</label>
						</div>
					</div>

					<?php $hsc_subject_detail = unserialize($applicant_academic[1]->academic_detail); ?>
					@if(!empty($hsc_subject_detail))
					@foreach($hsc_subject_detail as $key => $details)
					<div class="col-md-12 form-group">
						<div class=" col-md-8">
							<input type="text" class="form-control " name="ssc_olevel_year" placeholder="Year" value="{{$details['subject_name']}}" disabled>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control " name="ssc_olevel_year" placeholder="Year" value="{{$details['grade']}}" disabled>
						</div>
					</div>
					@endforeach
					@endif

					<div class="col-md-12">
						<div class="col-md-8">
							<label class="control-label float-right-margin-top-6">Total GPA (With optional subject) </label> <!-- custom-application-form.css -->
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" name="total_ssc_olevel_gpa" placeholder="Total GPA" value="{{$applicant_academic[1]->result_gpa}}" disabled>
						</div>
					</div>
				</div><!--/academic form-->
				<hr style="border-width: 1px;">
				<br>

				<div class="row"><!--personal form-->
					<div class="col-md-12">
						<div class="form-group col-md-3">
							<label for="Gender">Gender</label>
							<input type="text" class="form-control " name="gender" value="{{$applicant_info_basic->gender}}" disabled>
						</div>

						<div class="form-group col-md-3">
							<div class="control-group">
								<label class="control-label">Date of Birth</label>
								<div class="input-group date date_of_birth col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
									<input class="form-control" size="16" type="text" name="birth_calender" value="{{$applicant_info_basic->date_of_birth}}" disabled>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
								<input type="hidden" id="date_of_birth" name="date_of_birth" value="{{$applicant_info_basic->date_of_birth}}" /><br/>
							</div>
						</div>

						<div class="form-group col-md-3">
							<label >Blood Group</label>
							<input type="text" class="form-control " name="blood_group" value="{{$applicant_info_basic->blood_group}}" disabled>
						</div>
						<div class="form-group col-md-3">
							<label >Marital Status</label>
							<input type="text" class="form-control " name="marital" value="{{$applicant_info_basic->marital_status}}" disabled>
						</div>
					</div>

					<?php 
					$birth_info = explode(',', $applicant_info_basic->place_of_birth);
					?>

					<div class="col-md-12">
						<div class="form-group col-md-4">
							<label for="City">Birth Place: City</label>
							<input type="text" class="form-control" name="birth_city" placeholder="City" value="{{old('birth_city') ? old('birth_city'):$birth_info[0]}}" >	
						</div>
						<div class="form-group col-md-4">
							<label for="Country">Birth Place: Country</label>
							<input type="text" class="form-control" name="birth_country" value="{{old('birth_country') ? old('birth_country'):$birth_info[1]}}" placeholder="Country" >	
						</div>
						<div class="form-group col-md-4">
							<label >Nationality</label>
							<input type="text" class="form-control" name="nationality" placeholder="Nationality" value="{{$applicant_info_basic->nationality}}">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group col-md-4">
							<label for="Email">Email</label>
							<input type="text" class="form-control" name="applicant_email" placeholder="abcd@gmail.com" value="{{$applicant_info_basic->email}}" >	
						</div>
						<div class="form-group col-md-4">
							<label for="Phone">Phone</label>
							<input type="text" class="form-control" name="applicant_phone" placeholder="9300000" value="{{$applicant_info_basic->phone}}" >	
						</div>
						<div class="form-group col-md-4">
							<label >Mobile</label>
							<input type="text" class="form-control" name="applicant_mobile" placeholder="01722000000" value="{{$applicant_info_basic->mobile}}" >
						</div>
					</div>

				</div><!--/personal form-->
				<hr style="border-width: 1px;">
				<br>
				<div class="row"><!--contact and address form-->
					<div class="col-md-12">
						<div class="form-group col-md-3">
							<label >Present Address</label>
							<textarea class="form-control" name="present_address_detail" rows="1">{{$applicant_contact[0]->contact_detail}}</textarea>

						</div>
						<div class="form-group col-md-3">
							<label>Postal Code</label>
							<input type="text" class="form-control" name="present_postal_code" value="{{$applicant_contact[0]->postal_code}}" placeholder="Postal Code" >
						</div>
						<div class="form-group col-md-3">
							<label >City</label>
							<input type="text" class="form-control" name="present_city" placeholder="City" value="{{$applicant_contact[0]->city}}" >
						</div>
						<div class="form-group col-md-3">
							<label>Country</label>
							<input type="text" class="form-control" name="present_country" value="{{$applicant_contact[0]->country}}" placeholder="Country" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group col-md-3">
							<label >Permanent Address</label>
							<textarea class="form-control" name="permanent_address_detail" rows="1" >{{$applicant_contact[1]->contact_detail}} </textarea>

						</div>
						<div class="form-group col-md-3">
							<label>Postal Code</label>
							<input type="text" class="form-control" name="permanent_postal_code" placeholder="Postal Code" value="{{$applicant_contact[1]->postal_code}}" >
						</div>
						<div class="form-group col-md-3">
							<label >City</label>
							<input type="text" class="form-control" name="permanent_city" placeholder="City" value="{{$applicant_contact[1]->city}}" >
						</div>
						<div class="form-group col-md-3">
							<label>Country</label>
							<input type="text" class="form-control" name="permanent_country" placeholder="Country" value="{{$applicant_contact[1]->country}}" >
						</div>
					</div>

					@if(!empty($applicant_guardians))
						@foreach ($applicant_guardians as $key => $guardians)
							@if($guardians->relation == 'Father')
								<div class="col-md-12">
									<div class="form-group col-md-3">
										<label >Father's Name</label>
										<input type="text" class="form-control" name="" placeholder="Fathers Name" value="{{$guardians->gurdian_name}}" disabled>
										<input type="hidden" class="form-control" name="father_name"  value="{{$guardians->gurdian_name}}">
										
									</div>
									<div class="form-group col-md-3">
										<label >Father's Occupation</label>
										<input type="text" class="form-control" name="father_occupation" placeholder="Fathers Occupation" value="{{$guardians->occupation}}" >
									</div>

									<div class="form-group col-md-3">
										<label>Father'sMobile</label>
										<input type="text" class="form-control" name="father_contact_mobile" placeholder="Ex:- 01722000000" value="{{$guardians->mobile}}" >
									</div>
									<div class="form-group col-md-3">
										<label>Father's Email</label>
										<input type="text" class="form-control" name="father_contact_email" placeholder="example@example.com" value="{{$guardians->email}}" >
									</div>
								</div>
							@elseif($guardians->relation == 'Mother')

								<div class="col-md-12">
									<div class="form-group col-md-3">
										<label >Mother's Name</label>
										<input type="text" class="form-control" name=""  value="{{$guardians->gurdian_name}}" disabled>
										<input type="hidden" class="form-control" name="mother_name"  value="{{$guardians->gurdian_name}}">

									</div>
									<div class="form-group col-md-3">
										<label >Mother's Occupation</label>
										<input type="text" class="form-control" name="mother_occupation" placeholder="Mother's Occupation" value="{{$guardians->occupation}}" >
									</div>
									<div class="form-group col-md-3">
										<label>Mother's Mobile</label>
										<input type="text" class="form-control" name="mother_contact_mobile" placeholder="Ex:- 01722000000" value="{{$guardians->mobile}}" >
									</div>
									<div class="form-group col-md-3">
										<label>Mother's Email</label>
										<input type="text" class="form-control" name="mother_contact_email" placeholder="example@example.com" value="{{$guardians->email}}" >
									</div>
								</div>
							@elseif($guardians->relation == 'Local_Guardian')

								<div class="col-md-12">
									<div class="form-group col-md-3">
										<label >Local Guardian Name</label>
										<input type="text" class="form-control" name=""  value="{{$guardians->gurdian_name}}" disabled>
										<input type="hidden" class="form-control" name="local_guardian_name"  value="{{$guardians->gurdian_name}}">
									</div>
									<div class="form-group col-md-3">
										<label >Local Guardian Occupation</label>
										<input type="text" class="form-control" name="local_guardian_occupation" placeholder="Local Guardian Occupation" value="{{$guardians->occupation}}" >
									</div>
									<div class="form-group col-md-3">
										<label>Local Guardian Mobile</label>
										<input type="text" class="form-control" name="local_guardian_contact_mobile" placeholder="Ex:- 01722000000" value="{{$guardians->mobile}}" >
									</div>
									<div class="form-group col-md-3">
										<label>Local Guardian Email</label>
										<input type="text" class="form-control" name="local_guardian_contact_email" placeholder="example@example.com" value="{{$guardians->email}}" >
									</div>
								</div>
							@endif
						@endforeach
					@endif
					<div class="col-md-12">
						<div class="col-md-2">
							<h4>Emergency contact:</h4>						
						</div>
						<div class="form-group col-md-7">
							@if(!empty($applicant_guardians))
								@foreach ($applicant_guardians as $key => $guardians)
									@if($guardians->relation == 'Father')
										<input type="radio" name="emergency_contact"  value="Father" {{$guardians->emergency_contact=='yes'? 'checked' :''}}> Father
									@elseif($guardians->relation == 'Mother')			
										<input type="radio" name="emergency_contact"  value="Mother" {{$guardians->emergency_contact=='yes'? 'checked' :''}}> Mother
									@elseif($guardians->relation == 'Local_Guardian')
										<input type="radio" name="emergency_contact"  value="Local_Guardian" {{$guardians->emergency_contact=='yes'? 'checked' :''}}> Local Guardian
									@endif
								@endforeach
							@endif
						</div>

						<div class="form-group col-md-3">
							<label >Student Batch</label>
							<input type="text" class="form-control" name="batch" placeholder="Batch" value="{{old('batch')}}" >	
						</div>
						<!-- <div class="col-md-2">
							<h4>If have any waiver:</h4>	
						</div>
						<div class="col-md-3">
							<select name="waiver_type" class="form-control">
								<option value="">Select Waiver Type</option>
								@if(!empty($waiver_list))
								@foreach($waiver_list as $key => $waivers)
								<option {{(old('waiver_type')==$waivers->waiver_name_slug) ? 'selected': ''}} value="{{$waivers->waiver_name_slug}}">{{$waivers->waiver_name}}</option>
								@endforeach
								@endif
							</select>	
						</div> -->
					</div>
				</div><!--/contact and address form-->
				<hr>
				<div style="margin-top:30px;">
					<div class="col-md-6">
						If applicant have fault
						<a data-toggle="modal" data-target="#rejectModal" class="btn btn-danger" data-toggle1="tooltip" title="Student Reject">Reject</a>

					</div>

					<div  class="text-right col-md-6">
						<span>Confirm <input type="checkbox" required/></span>
						<a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
						<button type="submit" class="btn btn-primary ">Save</button>
					</div>



				</div><br><br>
			</div>	
		</form>
	</div><!-- /panel body-->
</div>
</div>




<!-- Modal -->
<div id="rejectModal" class="modal fade bs-example-modal-lg" rtabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Reject Applicant</h4>
			</div>

			<div class="row col-md-12">
				<form action="{{url('/register/office/applicant/reject')}}" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="applicant_serial_no" value="{{$_GET['applicant_serial_no']}}">
					<div class="form-group col-md-12">
						<label> Reason Details</label>
						<textarea class="form-control" name="reject_reason" rows="2"></textarea>
					</div>
					<div class="form-group col-md-12">
						<input type="submit" class="btn btn-danger" value="Reject">
					</div>
				</form>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>

		</div><!-- /Modal content-->
	</div>
</div><!-- /Modal -->






@elseif(isset($applicant_info_basic)  && ($applicant_info_basic->applicant_eligiblity ==6))

<div class="col-md-12">
	<div class="panel panel-body page_row">
		<div class="alert alert-info">
			<a href="{{url('/register/admission/confirm')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<div class="row">
				<div class="page-header" >
					<center class="header-name">Applicant Information</center>
				</div>
			</div>
			<div class="row main-border">
				<div class="col-md-2 photo-div" style="border-right: 1px solid #bdc3c7;">
					<center>
						<img src="{{asset($applicant_info_basic->app_image_url)}}" class="image" title="{{asset($applicant_info_basic->middle_name)}}" alt="{{asset($applicant_info_basic->applicant_serial_no)}}" />
					</center>
				</div>


				<div class="col-md-10 serial-gender-div">
					<div class="col-md-6 serial_input">
						<span class="serial_input_label"  for="inputColor">Serial No : {{$applicant_info_basic->applicant_serial_no}}</span>
					</div>
					<div class="col-md-6">
						<b>Gender : {{ucfirst($applicant_info_basic->gender)}}</b>
					</div>
				</div>
				<div class="col-md-10 program-div">
					<table  class="serial">
						<tr>
							<td><b>Program : </b></td>
							<td>{{$applicant_info_basic->program_title}}</td>
						</tr>
					</table>
				</div>

				<div class="col-md-9 applicant-info">
					<table class="info-table">
						<tr>
							<td style="width:25%">Applicant's Name</td>
							<td>:</td>		
							<td>{{$applicant_info_basic->first_name.' '.$applicant_info_basic->middle_name.' '.$applicant_info_basic->last_name}} </td>
						</tr>
						<tr>
							<td>Trimester</td>
							<td>:</td>
							<td>{{$applicant_info_basic->semester_title}}</td>
						</tr>
						<tr>
							<td>Academic Year</td>
							<td>:</td>		
							<td>{{$applicant_info_basic->academic_year}}</td>
						</tr>
						<tr>
							<td>Contact</td>
							<td>:</td>		
							<td>{{$applicant_info_basic->mobile}}</td>
						</tr>
						<tr>
							<td>Status</td>
							<td>:</td>		
							<td>Registered Student</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@else
<div class="row page_row">
	<div class="alert alert-info">
		<a href="{{url('/register/admission/confirm')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<div class="col-md-6  text-center">
			<div class="alert alert-info">No Data Found</div>
		</div>
	</div>
</div>
@endif
@endif





@stop