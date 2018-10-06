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
	<div class="col-md-12  profile_tab">
		<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
			
			<ul id="myTab" class="nav nav-tabs" role="tablist">

				<li role="presentation" class="{{$tab=='student_info' ? 'active' :''}}"><a href="#student_info" role="tab" id="student_info-tab" data-toggle="tab" aria-controls="student_info"><i class="fa fa-info-circle" aria-hidden="true"></i>Student Info</a></li>
				<li role="presentation" class="{{$tab=='accepted_course' ? 'active' :''}}"><a href="#accepted_course" role="tab" id="accepted_course-tab" data-toggle="tab" aria-controls="accepted_course"><i class="fa fa-book"></i>Accepted Course</a></li>

			</ul>


			<div id="myTabContent" class="tab-content"><!--main tab content-->
				<div role="tabpanel"  id="student_info"  class="tab-pane fade {{$tab=='student_info' ? 'in active' :''}}" aria-labelledby="student_info-tab">
					<div class="row">
						<div class="col-md-10">
							<div class="panel panel-body page_row">
								<div class="alert alert-info">
									<h3>Student Information</h3><br>

									<form action="{{url('/register/existing/student/submit')}}" method="post" enctype="multipart/form-data">
										<div class="row"><!--basic block form -->
											<div class="col-md-12">
												<div class="row">
													<div class="form-group col-md-4">
														<label for="First Name">First Name<span class="required-sign">*</span></label>
														<input type="text" class="form-control uppercase_name" name="first_name" placeholder="First Name" value="{{old('first_name')}}">
													</div>
													<div class="form-group col-md-4">
														<label for="Middle Name">Middle Name</label>
														<input type="text" class="form-control uppercase_name" name="middle_name" placeholder="Middle Name" value="{{old('middle_name')}}">
													</div>
													<div class="form-group col-md-4">
														<label for="Last Name">Last Name<span class="required-sign">*</span></label>
														<input type="text" class="form-control uppercase_name" name="last_name" placeholder="Last Name" value="{{old('last_name')}}">
													</div>
												</div>

												<?php 
												$program_list =\App\Register::ProgramList();
												?>
												<div class="row">
													<div class="form-group col-md-4">
														<label for="Program">Program<span class="required-sign">*</span></label>
														<select class="form-control" name="program">
															@if(!empty($program_list))
															@foreach($program_list as $key => $list)
															<option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
															@endforeach
															@endif
														</select> 
													</div>
													<div class="form-group col-md-4">
														<label for="Semester">Trimester<span class="required-sign">*</span></label>
														<?php
														$semester_list=\DB::table('univ_semester')->get();
														?>
														<select class="form-control" name="semester">
															@if(!empty($semester_list))
															@foreach($semester_list as $key => $semester)
															<option {{(old('semester')== $semester->semester_code) ? "selected" :''}} value="{{$semester->semester_code}}">{{$semester->semester_title}}</option>
															@endforeach
															@endif
														</select>
													</div>

													<div class="form-group col-md-4">
														<label for="AcademicYear">Academic Year<span class="required-sign">*</span></label>
														<select class="form-control" name="academic_year">
															<option {{(old('academic_year')) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
															<option {{(old('academic_year')) ? 'selected':''}} value="{{date("Y",strtotime('-1 year'))}}">{{date('Y',strtotime("-1 year"))}}</option>
															<option {{(old('academic_year')) ? 'selected':''}} value="{{date("Y",strtotime('-2 year'))}}">{{date('Y',strtotime("-2 year"))}}</option>
															<option {{(old('academic_year')) ? 'selected':''}} value="{{date("Y",strtotime('-3 year'))}}">{{date('Y',strtotime("-3 year"))}}</option>
															<option {{(old('academic_year')) ? 'selected':''}} value="{{date("Y",strtotime('-4 year'))}}">{{date('Y',strtotime("-4 year"))}}</option>
															<option {{(old('academic_year')) ? 'selected':''}} value="{{date("Y",strtotime('-5 year'))}}">{{date('Y',strtotime("-5 year"))}}</option>
															
														</select>
													</div>
												</div>
											</div>

										</div><!-- /basic block form -->

										<hr style="border-width: 1px;">
										<br>
										<div class="row"><!--academic form-->
											<div class="col-md-12">
												<div class="form-group col-md-2">
													<label >Exam Type<span class="required-sign">*</span></label>
													<select class="form-control" name="ssc_exam_type" >
														<option  {{(old('ssc_exam_type')== "SSC") ? "selected" :''}} value="SSC">SSC</option>
														<option  {{(old('ssc_exam_type')== "olevel") ? "selected" :''}} value="olevel">O level</option>
													</select>
												</div>
												<div class="form-group col-md-2">
													<label >Group<span class="required-sign">*</span></label>
													<select class="form-control" name="ssc_group_name">
														<option {{(old('ssc_group_name')== "science") ? "selected" :''}} value="science">Science</option>
														<option {{(old('ssc_group_name')== "arts") ? "selected" :''}} value="arts">Arts</option>
														<option {{(old('ssc_group_name')== "commerce") ? "selected" :''}} value="commerce">Commerce</option>
													</select>
												</div>

												<div class="form-group col-md-2">
													<label >Roll Number<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="ssc_roll_number"  value="{{old('ssc_roll_number')}}" >
												</div>
												<div class="form-group col-md-2">
													<label >Board<span class="required-sign">*</span></label>
													<select  class="form-control" name="ssc_board_name">
														<option  {{(old('ssc_board_name')== 0) ? "selected" :''}} value="0">Select One</option>
														<option  {{(old('ssc_board_name')== "barisal") ? "selected" :''}} value="barisal">Barisal</option>
														<option  {{(old('ssc_board_name')== "chittagong") ? "selected" :''}} value="chittagong">Chittagong</option>
														<option  {{(old('ssc_board_name')== "comilla") ? "selected" :''}} value="comilla">Comilla</option>
														<option  {{(old('ssc_board_name')== "dhaka") ? "selected" :''}} value="dhaka">Dhaka</option>
														<option  {{(old('ssc_board_name')== "dinajpur") ? "selected" :''}} value="dinajpur">Dinajpur</option>
														<option  {{(old('ssc_board_name')== "jessore") ? "selected" :''}} value="jessore">Jessore</option>
														<option  {{(old('ssc_board_name')== "rajshahi") ? "selected" :''}} value="rajshahi">Rajshahi</option>
														<option  {{(old('ssc_board_name')== "sylhet") ? "selected" :''}} value="sylhet">Sylhet</option>
														<option  {{(old('ssc_board_name')== "madrasah") ? "selected" :''}} value="madrasah">Madrasah</option>
														<option  {{(old('ssc_board_name')== "technical") ? "selected" :''}} value="technical">Technical</option>
														<option  {{(old('ssc_board_name')== "dibs") ? "selected" :''}} value="dibs">DIBS(Dhaka)</option>
													</select>
												</div>
												<div class="form-group col-md-2">
													<label >Passing Year<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="ssc_olevel_year" placeholder="Year" value="{{old('ssc_olevel_year')}}" >
												</div>
												<div class="form-group col-md-2">
													<label class="control-label ">Total GPA<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="total_ssc_olevel_gpa" placeholder="Total GPA" value="{{old('total_ssc_olevel_gpa')}}" >
												</div>
											</div>


											<hr style="border-width: 1px;">
											<br>

											<div class="col-md-12">
												<div class="form-group col-md-2">
													<label >Exam Type<span class="required-sign">*</span></label>
													<select class="form-control" name="hsc_exam_type" >
														<option  {{(old('hsc_exam_type')== "HSC") ? "selected" :''}} value="HSC">HSC</option>
														<option  {{(old('hsc_exam_type')== "Alevel") ? "selected" :''}} value="alevel">A level</option>
													</select>
												</div>
												<div class="form-group col-md-2">
													<label >Group<span class="required-sign">*</span></label>
													<select class="form-control" name="hsc_group_name">
														<option {{(old('hsc_group_name')== "science") ? "selected" :''}} value="science">Science</option>
														<option {{(old('hsc_group_name')== "arts") ? "selected" :''}} value="arts">Arts</option>
														<option {{(old('hsc_group_name')== "commerce") ? "selected" :''}} value="commerce">Commerce</option>
													</select>
												</div>

												<div class="form-group col-md-2">
													<label >Roll Number<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="hsc_roll_number" value="{{old('hsc_roll_number')}}">
												</div>
												<div class="form-group col-md-2">
													<label >Board<span class="required-sign">*</span></label>
													<select  class="form-control" name="hsc_board_name">
														<option  {{(old('hsc_board_name')== 0) ? "selected" :''}} value="0"selected>Select One</option>
														<option  {{(old('hsc_board_name')== "barisal") ? "selected" :''}} value="barisal">Barisal</option>
														<option  {{(old('hsc_board_name')== "chittagong") ? "selected" :''}} value="chittagong">Chittagong</option>
														<option  {{(old('hsc_board_name')== "comilla") ? "selected" :''}} value="comilla">Comilla</option>
														<option  {{(old('hsc_board_name')== "dhaka") ? "selected" :''}} value="dhaka">Dhaka</option>
														<option  {{(old('hsc_board_name')== "dinajpur") ? "selected" :''}} value="dinajpur">Dinajpur</option>
														<option  {{(old('hsc_board_name')== "jessore") ? "selected" :''}} value="jessore">Jessore</option>
														<option  {{(old('hsc_board_name')== "rajshahi") ? "selected" :''}} value="rajshahi">Rajshahi</option>
														<option  {{(old('hsc_board_name')== "sylhet") ? "selected" :''}} value="sylhet">Sylhet</option>
														<option  {{(old('hsc_board_name')== "madrasah") ? "selected" :''}} value="madrasah">Madrasah</option>
														<option  {{(old('hsc_board_name')== "technical") ? "selected" :''}} value="technical">Technical</option>
														<option  {{(old('hsc_board_name')== "dibs") ? "selected" :''}} value="dibs">DIBS(Dhaka)</option>
													</select>
												</div>
												<div class="form-group col-md-2">
													<label >Passing Year<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="hsc_olevel_year" placeholder="Year" value="{{old('hsc_olevel_year')}}">
												</div>
												<div class="form-group col-md-2">
													<label class="control-label ">Total GPA<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="total_hsc_olevel_gpa" placeholder="Total GPA" value="{{old('total_hsc_olevel_gpa')}}" >
												</div>
											</div>

										</div><!--/academic form-->
										<hr style="border-width: 1px;">
										<br>

										<div class="row"><!--personal form-->

											<div class="col-md-12">


												<div class="form-group col-md-3">
													<label for="Roll No">Student Batch<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="batch" placeholder="Batch" value="{{old('batch')}}" >	
												</div>

												<div class="form-group col-md-3">
													<label for="Roll No">University Roll No<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="univ_roll_no" placeholder="University Roll No" value="{{old('univ_roll_no')}}" >	
												</div>

												<div class="form-group col-md-3">
													<label for="City">Birth Place: City<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="place_of_birth" placeholder="Place Of Birth" value="{{old('place_of_birth')}}" >	
												</div>

												<div class="form-group col-md-3">
													<label >Nationality<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="nationality" placeholder="Nationality" value="{{old('nationality')}}" >
												</div>

											</div>
											<div class="col-md-12">

												<div class="form-group col-md-3">
													<label for="Gender">Gender<span class="required-sign">*</span></label>
													<select class="form-control" name="gender">
														<option {{(old('gender')== "male") ? "selected" :''}} value="male">Male</option>
														<option {{(old('gender')== "female") ? "selected" :''}} value="female">Female</option>
													</select>
												</div>


												<div class="form-group col-md-3">
													<div class="control-group">
														<label class="control-label">Date of Birth <span class="required-sign">*</span></label>
														<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
															<input class="form-control" name="date_of_birth"  size="16" type="text" value="{{old('date_of_birth')}}" readonly>
															<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
														</div>
													</div>
												</div>

												<div class="form-group col-md-3">
													<label >Blood Group</label>
													<select class="form-control" name="blood_group">
														<option {{(old('blood_group')== "") ? "selected" :''}}  value="">Select Blood Group</option>
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
												<div class="form-group col-md-3">
													<label >Marital Status</label>
													<select class="form-control" name="marital_status">
														<option {{(old('marital_status')== "single") ? "selected" :''}} value="single">Single</option>
														<option {{(old('marital_status')== "married") ? "selected" :''}} value="married">Married</option>
														<option {{(old('marital_status')== "other") ? "selected" :''}} value="other">Other</option>
													</select>
												</div>
											</div>


											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label for="Religion">Religion<span class="required-sign">*</span></label>
													<select class="form-control" name="religion">
														<option {{(old('religion')== "") ? "selected" :''}}  value="">Select Religion</option>
														<option {{(old('religion')== "islam") ? "selected" :''}}  value="islam">Islam</option>
														<option {{(old('religion')== "christianity") ? "selected" :''}} value="christianity">Christianity</option>
														<option {{(old('religion')== "hinduism") ? "selected" :''}} value="hinduism">Hinduism</option>
														<option {{(old('religion')== "buddhism") ? "selected" :''}} value="buddhism">Buddhism</option>
														<option {{(old('religion')== "others") ? "selected" :''}} value="others">Others</option>

													</select> 	
												</div>
												<div class="form-group col-md-3">
													<label for="Email">Email<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="email" placeholder="abcd@gmail.com" value="{{old('email')}}">	
												</div>
												<div class="form-group col-md-3">
													<label for="Phone">Phone</label>
													<input type="text" class="form-control" name="phone" placeholder="9300000" value="{{old('phone')}}" >	
												</div>
												<div class="form-group col-md-3">
													<label >Mobile<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="mobile" placeholder="01000000000" value="{{old('mobile')}}" >
												</div>
												<input type="hidden" name="image_url" id="image_url">
											</div>

										</div><!--/personal form-->

										<hr style="border-width: 1px;">
										<br>
										<div class="row"><!--contact and address form-->
											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label >Present Address<span class="required-sign">*</span></label>
													<textarea class="form-control" name="present_address_detail" rows="1">{{old('present_address_detail')}}</textarea>

												</div>
												<div class="form-group col-md-3">
													<label>Postal Code<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="present_postal_code" value="{{old('present_postal_code')}}" placeholder="Postal Code" >
												</div>
												<div class="form-group col-md-3">
													<label >City<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="present_city" placeholder="City" value="{{old('present_city')}}" >
												</div>
												<div class="form-group col-md-3">
													<label>Country<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="present_country" value="{{old('present_country')}}" placeholder="Country" >
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label >Permanent Address<span class="required-sign">*</span></label>
													<textarea class="form-control" name="permanent_address_detail" rows="1" >{{old('permanent_address_detail')}} </textarea>
												</div>
												<div class="form-group col-md-3">
													<label>Postal Code<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="permanent_postal_code" placeholder="Postal Code" value="{{old('permanent_postal_code')}}" >
												</div>
												<div class="form-group col-md-3">
													<label >City<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="permanent_city" placeholder="City" value="{{old('permanent_city')}}" >
												</div>
												<div class="form-group col-md-3">
													<label>Country<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="permanent_country" placeholder="Country" value="{{old('permanent_country')}}" >
												</div>
											</div>


											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label >Father's Name<span class="required-sign">*</span></label>
													<input type="text" class="form-control uppercase_name" name="father_name" placeholder="Fathers Name" value="{{old('father_name')}}" >
												</div>
												<div class="form-group col-md-3">
													<label >Father's Occupation<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="father_occupation" placeholder="Fathers Occupation" value="{{old('father_occupation')}}" >
												</div>

												<div class="form-group col-md-3">
													<label>Father's Mobile<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="father_contact_mobile" placeholder="Ex:- 01722000000" value="{{old('father_contact_mobile')}}" >
												</div>
												<div class="form-group col-md-3">
													<label>Father's Email</label>
													<input type="text" class="form-control" name="father_contact_email" placeholder="example@example.com" value="{{old('father_contact_email')}}" >
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label >Mother's Name<span class="required-sign">*</span></label>
													<input type="text" class="form-control uppercase_name" name="mother_name" placeholder="Mothers Name" value="{{old('mother_name')}}" >
												</div>
												<div class="form-group col-md-3">
													<label >Mother's Occupation<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="mother_occupation" placeholder="Mother's Occupation" value="{{old('mother_occupation')}}" >
												</div>
												<div class="form-group col-md-3">
													<label>Mother's Mobile<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="mother_contact_mobile" placeholder="Ex:- 01000000000" value="{{old('mother_contact_mobile')}}" >
												</div>
												<div class="form-group col-md-3">
													<label>Mother's Email</label>
													<input type="text" class="form-control" name="mother_contact_email" placeholder="example@example.com" value="{{old('mother_contact_email')}}" >
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group col-md-3">
													<label >Local Guardian Name<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="local_guardian_name"  value="{{old('local_guardian_name')}}">
												</div>
												<div class="form-group col-md-3">
													<label > Guardian Occupation<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="local_guardian_occupation" placeholder="Local Guardian Occupation" value="{{old('local_guardian_occupation')}}" >
												</div>
												<div class="form-group col-md-3">
													<label> Guardian Mobile<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="local_guardian_contact_mobile" placeholder="Ex:- 01722000000" value="{{old('local_guardian_contact_mobile')}}" >
												</div>
												<div class="form-group col-md-3">
													<label> Guardian Email</label>
													<input type="text" class="form-control" name="local_guardian_contact_email" placeholder="example@example.com" value="{{old('local_guardian_contact_email')}}" >
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group col-md-4">
													<label>Paid Tution Fees<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="paid_tution_fees" placeholder="EX : 10000" value="{{old('paid_tution_fees')}}" >								
												</div>

												<div class="form-group col-md-4">
													<label>Paid Trimester Fees<span class="required-sign">*</span></label>
													<input type="text" class="form-control" name="paid_trimester_fees" placeholder="EX : 10000" value="{{old('paid_trimester_fees')}}" >								
												</div>

												<div class="form-group col-md-4">
													<label>Paid Others Fees</label>
													<input type="text" class="form-control" name="paid_others_fees" placeholder="EX : 10000" value="{{old('paid_others_fees')}}" >								
												</div>

											</div>

											<div class="col-md-12">
												<div class="form-group col-md-3">
													<h4>Emergency contact:<span class="required-sign">*</span></h4>						
												</div>
												<div class="form-group col-md-6">
													<input type="radio" name="emergency_contact"  value="Father" > Father				
													<input type="radio" name="emergency_contact"  value="Mother" > Mother
													<input type="radio" name="emergency_contact"  value="Local_Guardian" checked> Local Guardian										
												</div>

												<?php
												$waiver_list=\DB::table('waivers')->get();
												?>
												<!-- 												
												<div class="form-group col-md-3">
													<label>Waiver : </label>
													<select class="form-control" name="waiver">
														<option value="">Select Waiver Type</option>
														@if(!empty($waiver_list))
														@foreach($waiver_list as $key => $list)
														<option {{(old('waiver')== $list->waiver_name_slug) ? "selected" :''}} value="{{$list->waiver_name_slug}}">{{$list->waiver_name}}</option>
														@endforeach
														@endif
													</select> 
												</div> -->

											</div>
										</div>
										<hr>
										<div class="text-right" style="margin-top:30px;">
											<div class="col-md-12">
												
												<a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
												<input type="submit" class="btn btn-primary" value="Submit">
											</div>
										</div><br><br>
									</form>
								</div>	
							</div> <!-- /panel body -->
						</div>



						<div class="col-md-2" style="margin-top:10px;">
							<div class="panel panel-default">
								<div class="panel-body">
									<div id="validation-errors"></div>
									<label>Passposrt Size Photo (Colored) <span class="required-sign">*</span></label>    
									<div id="demo">
										<img  src="{{old('image_url') ? asset(old('image_url')):asset('images/profile.png')}}" alt="img">
									</div>
									<div class="uploader">
										<form class="example" id="upload" role="form" enctype="multipart/form-data" method="POST" action="{{url('/register/student/credit/transfer/image-upload')}}" >
											<div class="fileinputs">
												<input type="hidden" name="_token" value="{{csrf_token()}}" />

												<span class="btn btn-primary btn-file span-photo"> 
													Browse Photo<input name="image" id="image_transfer_student" noscript="true" type="file" name="photo" class="form-control btn-file-browse-photo">
													
												</span>
												
											</div>

										</form>
										<div class='image_loader'></div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div role="tabpanel"  id="accepted_course"  class="tab-pane fade {{$tab=='accepted_course' ? 'in active' :''}}" aria-labelledby="accepted_course-tab">


					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-body padding_0">
								<?php 
								$program_list =\App\Applicant::ProgramList();
								?>
								<form method="get" action="{{url('/register/existing/student')}}">

									<div class="form-group col-md-3">
										<label for="Program">Program<span class="required-sign">*</span></label>
										<select class="form-control select_program" name="program" >
											<option value="">Select Program</option>
											@if(!empty($program_list))
											@foreach($program_list as $key => $list)

											<option {{(isset($_GET['program']) && ($_GET['program']==$list->program_id)) ? 'selected' : ''}} value="{{$list->program_id}}">{{$list->program_title}}</option>

											@endforeach
											@endif
										</select>
									</div>


										<div class="form-group col-md-4">
											<label>Student Batch<span class="required-sign">*</span></label>
											<select class="form-control get_batch select_batch" name="batch" id="student_program">
												<option value="">Select Batch</option>
											</select>									
										</div>



										<div class="form-group col-md-4">
											<label>Student List<span class="required-sign">*</span></label>
											<select class="form-control get_student" name="student_no" id="student_serial_no">
												<option value="">Select Student</option>

											</select>									
										</div>

									<div class="col-md-1 margin_top_20" style="margin-top:25px;">
										<button class="btn btn-danger" data-toggle="tooltip" title="Search Courses">Serach</button>
									</div>
								</form>

							</div>
						</div>
					</div>

					@if(!empty($_GET['program']) && !empty($_GET['batch']) && !empty($_GET['student_no']))
					<div class="row">
						<div class="col-md-12">
							
								<?php
									$student_info=\DB::table('student_basic')->where('student_serial_no',$_GET['student_no'])->first();
									$program_info=\DB::table('univ_program')->where('program_id',$_GET['program'])->first();
								?>

								@if(!empty($student_info) && !empty($program_info))


									<div class="row">

											<div class="form-group col-md-3">
												<span>Program : </span>{{isset($_GET['program']) && $_GET['program'] == $program_info->program_id ? $program_info->program_code : ''}}
											</div>

											<div class="col-md-3">
												<span>Student Id : </span>{{isset($_GET['student_no']) && $_GET['student_no'] == $student_info->student_serial_no ? $student_info->student_serial_no  : ''}}
											</div>

											<div class="col-md-3">
												<span>Name: </span>{{isset($_GET['student_no']) && $_GET['student_no'] == $student_info->student_serial_no ? $student_info->first_name  : ''}}
											</div>

											<div class="col-md-3">
												<span>Batch : </span>{{isset($_GET['batch']) && $_GET['batch'] == $student_info->batch_no ? $student_info->batch_no : ''}}
											</div>
											
										<div class="col-md-12">
										<caption><strong>Instruction:</strong>Here all value is to be numeric.</caption>
										</div>

									</div>

									<table class="table table-bordered table-hover ">
										@if(!empty($all_theory_course))

											<thead>
												<tr>
													<th class="text-center">SL</th>
													<th class="text-center">Course Title</th>
													<th colspan="3" class="text-center">AcademicYear</th>
													<th colspan="2" class="text-center">AcademicSemester</th>
													<th class="text-center">CT-1(10%)</th>
													<th class="text-center">CT-2(10%)</th>
													<th class="text-center">CT-3(10%)</th>
													<th class="text-center">CT-4(10%)</th>
													<th class="text-center">Attendance(10%)</th>
													<th class="text-center">Participation(5%)</th>
													<th class="text-center">Presentaion(15%)</th>
													<th class="text-center">Midterm(20%)</th>
													<th class="text-center">Final(40%)</th>
													<th>Action</th>
												</tr>
											</thead>

											<tbody>
												@foreach($all_theory_course as $key => $course_list)
												<tr>
												<form method="get" action="{{url('/register/existing/student/marks/submit')}}" enctype="multipart/form-data">
												
													<td>{{$key+1}}</td>
													<td>({{$course_list->course_code}}) {{$course_list->course_title}}</td>

													<?php 
														$student_class_register_info=\DB::table('student_class_registers')
	                                                       ->where('student_tran_code',$student_info->student_tran_code)
	                                                       ->where('student_class_registers.class_course_code',$course_list->course_code)
	                                                       ->where('student_class_registers.class_result_status','1')
	                                                       ->first();

	                                                ?>

													<td colspan="3">
														<select class="form-control" id="course_year_{{$course_list->course_code}}" name="course_year">
															<option value="">Select Year </option>
															@if(!empty($univ_academic_calender))
															@foreach($univ_academic_calender as $key => $list)
															<option  {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("-5 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("-5 year"))}}">{{date("Y",strtotime("-5 year"))}}</option>
															<option  {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("-4 year")) ? 'selected' :''}}  value="{{date("Y",strtotime("-4 year"))}}">{{date("Y",strtotime("-4 year"))}}</option>
															<option  {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("-3 year")) ? 'selected' :''}}  value="{{date("Y",strtotime("-3 year"))}}">{{date("Y",strtotime("-3 year"))}}</option>
															<option  {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("-2 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("-2 year"))}}">{{date("Y",strtotime("-2 year"))}}</option>
															<option  {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("-1 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
															<option {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date('Y') ? 'selected' :''}}  value="{{date('Y')}}">{{date('Y')}}</option>
															<option {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_year == date("Y",strtotime("+1 year")) ? 'selected' :''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>

															@endforeach
															@endif
														</select>	
													</td>
												
													<td colspan="2">
														<select class="form-control" id="course_semester_{{$course_list->course_code}}" name="course_semester" >
															<option value="">Select Semester</option>
															@if(!empty($all_semester))
															@foreach($all_semester as $key => $list)

															<option {{isset($student_class_register_info) && !empty($student_class_register_info) && $student_class_register_info->class_semster == $list->semester_code? 'selected' :''}}
															  value="{{$list->semester_code}}">{{$list->semester_title}}</option>

															@endforeach
															@endif
														</select>	
													</td>

													<input type="hidden" class="form-control" id="student_serial_no_{{$course_list->course_code}}" name="student_serial_no" value="{{$student_info->student_serial_no}}" />
													<input type="hidden" class="form-control" id="course_type_{{$course_list->course_code}}" name="course_type" value="{{$course_list->course_type}}" />
													<input type="hidden" class="form-control" name="course_code" value="{{$course_list->course_code}}" />
													<td><input type="text" id="ct_1_{{$course_list->course_code}}" class="form-control" name="ct_1" value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_quiz_1 :''}}"/></td>
													<td><input type="text" id="ct_2_{{$course_list->course_code}}" class="form-control" name="ct_2"  value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_quiz_2 :''}}"/></td>
													<td><input type="text" id="ct_3_{{$course_list->course_code}}" class="form-control" name="ct_3"   value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_quiz_3 :''}}"/></td>
													<td><input type="text" id="ct_4_{{$course_list->course_code}}" class="form-control" name="ct_4" value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_quiz_4 :''}}"/>
													</td>
													
													<td><input type="text" id="class_attendance_{{$course_list->course_code}}" class="form-control" name="class_attendance"   value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_attendance :''}}"/></td>
													<td><input type="text" id="class_participation_{{$course_list->course_code}}" class="form-control" name="class_participation"   value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_participation :''}}"/></td>
													<td><input type="text" id="class_presentaion_{{$course_list->course_code}}" class="form-control" name="class_presentaion" value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_presentaion :''}}"/></td>
													<td><input type="text" id="mid_term_{{$course_list->course_code}}" class="form-control" name="mid_term" value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_mid_term_exam :''}}"/></td>
													<td><input type="text" id="class_final_exam_{{$course_list->course_code}}" class="form-control" name="class_final_exam" value="{{isset($student_class_register_info) && !empty($student_class_register_info)? $student_class_register_info->class_final_exam :''}}"/></td>


	                                                @if(empty($student_class_register_info))

														<td>
															<!-- <button type="submit" data-loading-text="Saving..." data-course="{{$course_list->course_code}}" class="btn btn-primary btn-sm loadingButton ExamMarksStore" data-toggle="tooltip" title="Store Marks">Save</button> -->
															<input data-loading-text="Saving..." type="submit" value="Save" class="btn btn-primary btn-sm loadingButton" data-toggle="tooltip" title="Store Marks">
														</td>
													@else
														<td>
															<span><i class="fa fa-check" aria-hidden="true"></i><a  data-confirm-url="{{url('/register/existing/student/delete/course/'.$_GET['student_no'].'/'.$course_list->course_code.'/'.$course_list->course_type.'/'.$student_class_register_info->class_year.'/'.$student_class_register_info->class_semster)}}" class="btn btn-danger btn-xs confirm_box" data-toggle="tooltip" title="Undo Course"><i class="fa fa-undo" aria-hidden="true"></i></a></span>
														</td>
													@endif

												</form>
													
												</tr>
												@endforeach

											</tbody>
										@endif

										@if(!empty($all_lab_course))

										<thead>
											<tr>
												<th class="text-center">SL</th>
												<th class="text-center">Course Title</th>
												<th colspan="3" class="text-center">Academic Year</th>
												<th colspan="2" class="text-center">Academic Semester</th>
												<th class="text-center">Lab Attendance</th>
												<th colspan="2" class="text-center">Lab Performance</th>
												<th colspan="2" class="text-center">Lab Report</th>
												<th colspan="2" class="text-center">Lab Verbal</th>
												<th colspan="2" class="text-center">Lab Final</th>
												<th>All</th>
											</tr>
										</thead>

										<tbody>
											@foreach($all_lab_course as $key => $course_list)
											<tr>
											<form method="get" action="{{url('/register/existing/student/lab/marks/submit')}}" enctype="multipart/form-data">

												<td>{{$key+1}}</td>
												<td>({{$course_list->course_code}}) {{$course_list->course_title}}</td>

												<?php 
														$student_lab_register_info=\DB::table('student_lab_register')
	                                                       ->where('student_tran_code',$student_info->student_tran_code)
	                                                       ->where('student_lab_register.lab_course_code',$course_list->course_code)
	                                                       ->first();

                                                ?>

												<td colspan="3">
													<select class="form-control" id="course_year_{{$course_list->course_code}}" name="course_year">
														<option value="">Select Year</option>
														@if(!empty($univ_academic_calender))
														@foreach($univ_academic_calender as $key => $list)

														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("-5 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("-5 year"))}}">{{date("Y",strtotime("-5 year"))}}</option>


														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("-4 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("-4 year"))}}">{{date("Y",strtotime("-4 year"))}}</option>
														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("-3 year")) ? 'selected' :''}}  value="{{date("Y",strtotime("-3 year"))}}">{{date("Y",strtotime("-3 year"))}}</option>
														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("-2 year")) ? 'selected' :''}}  value="{{date("Y",strtotime("-2 year"))}}">{{date("Y",strtotime("-2 year"))}}</option>
														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("-1 year")) ? 'selected' :''}}  value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y") ? 'selected' :''}} value="{{date('Y')}}">{{date('Y')}}</option>
														<option{{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_year == date("Y",strtotime("+1 year")) ? 'selected' :''}}   value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>

														@endforeach
														@endif
													</select>	
												</td>
											
												<td colspan="2">
													<select class="form-control" id="course_semester_{{$course_list->course_code}}" name="course_semester" >
														<option value="">Select Semester</option>
														@if(!empty($all_semester))
														@foreach($all_semester as $key => $list)

														<option {{isset($student_lab_register_info) && !empty($student_lab_register_info) && $student_lab_register_info->lab_semster == $list->semester_code? 'selected' :''}}
															  value="{{$list->semester_code}}">{{$list->semester_title}}</option>

														@endforeach
														@endif
													</select>	
												</td>

												<input type="hidden" class="form-control" id="student_serial_no_{{$course_list->course_code}}" name="student_serial_no" value="{{$student_info->student_serial_no}}" />
												<input type="hidden" class="form-control" id="course_type_{{$course_list->course_code}}" name="course_type" value="{{$course_list->course_type}}" />
												<input type="hidden" class="form-control" name="course_code" value="{{$course_list->course_code}}" />
												<td><input type="text" id="lab_attendance_{{$course_list->course_code}}" class="form-control" name="lab_attendance" value="{{isset($student_lab_register_info)? $student_lab_register_info->lab_attendance :''}}"/></td>
												<td colspan="2"><input type="text" id="lab_performance_{{$course_list->course_code}}" class="form-control" name="lab_performance" value="{{isset($student_lab_register_info)? $student_lab_register_info->lab_performance :''}}"/></td>
												<td colspan="2"><input type="text" id="lab_reprot_{{$course_list->course_code}}" class="form-control" name="lab_reprot" value="{{isset($student_lab_register_info)? $student_lab_register_info->lab_reprot :''}}"/></td>
												<td colspan="2"><input type="text" id="lab_verbal_{{$course_list->course_code}}" class="form-control" name="lab_verbal" value="{{isset($student_lab_register_info)? $student_lab_register_info->lab_verbal :''}}"/></td>
												<td colspan="2"><input type="text" id="lab_final_{{$course_list->course_code}}" class="form-control" name="lab_final" value="{{isset($student_lab_register_info)? $student_lab_register_info->lab_final :''}}"/></td>

                                                @if(empty($student_lab_register_info))
													<td>
														<!-- <button type="submit" data-loading-text="Saving..." data-course="{{$course_list->course_code}}" class="btn btn-primary btn-sm loadingButton ExamMarksStore" data-toggle="tooltip" title="Store Marks">Save</button> -->
														<input data-loading-text="Saving..." type="submit" value="Save" class="btn btn-primary btn-sm loadingButton" data-toggle="tooltip" title="Store Marks">
														
													</td>
												@else
													<td>
														<span><i class="fa fa-check" aria-hidden="true"></i><a data-confirm-url="{{url('/register/existing/student/delete/course/'.$_GET['student_no'].'/'.$course_list->course_code.'/'.$course_list->course_type.'/'.$student_lab_register_info->lab_year.'/'.$student_lab_register_info->lab_semster)}}" class="btn btn-danger btn-xs confirm_box" data-toggle="tooltip" title="Undo Course"><i class="fa fa-undo" aria-hidden="true"></i></a></span>
														
													</td>
												@endif

											</form>
											</tr>
											@endforeach
										</tbody>
										@endif

									</table>
									<br><br>

								@endif

						</div>
					</div>
					@endif
				</div>


			</div>

		</div>
	</div>
</div>



@stop