@extends('application.layout.master')
@section('content')

<div class="row"><!--message-->
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

@if(isset($from_part)&&($from_part !=5))
<div class="row"><!--breadcrumb-->
    <div class="breadcrumb flat">
        <a href="#" class="{{(isset($from_part) && ($from_part >1)) ? 'active':''}}">Basic Info</a>
        <a href="#" class="{{(isset($from_part) && ($from_part >2)) ? 'active':''}}" >Academic Info</a>
        <a href="#" class="{{(isset($from_part) && ($from_part >3)) ? 'active':''}}">Personal Info </a>
        <a href="#" class="">Contact Info</a>
        
    </div>
</div><!--/breadcrumb-->
@endif


@if(isset($from_part)&&($from_part==1))

<div class="basic_block_form" ><!--basic block form-->
    
    <div class="row"><!-- Basiincformation -->
        <div class="col-md-12 page_heading">
            <h2>Basic Information</h2>
        </div>
        
        <form action="{{url('/online-application/form/basic-info')}}" method="post">

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">
            <div class="col-md-10">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="First Name">First Name <span class="required-sign">*</span></label>
                        <input type="text" class="form-control uppercase_name" name="first_name" placeholder="First Name" value="{{old('first_name')}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Middle Name">Middle Name</label>
                        <input type="text" class="form-control uppercase_name" name="middle_name" placeholder="Middle Name" value="{{old('middle_name')}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Last Name">Last Name <span class="required-sign">*</span></label>
                        <input type="text" class="form-control uppercase_name" name="last_name" placeholder="Last Name" value="{{old('last_name')}}">
                    </div>
                </div>
                <?php 
                $program_list =\App\Applicant::ProgramList();

                ?>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Program">Program <span class="required-sign">*</span></label>
                        <select class="form-control program" name="program" >
                            @if(!empty($program_list))
                            @foreach($program_list as $key => $list)
                            <option {{(old('program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <?php 
                    $mslist = \App\Applicant::MSProgramList();
                    foreach ($mslist as $key => $list) {
                        $ms_data[] = $list->program_id;
                    }
					
			if(!empty($ms_data)){
				$mslist = implode(',', $ms_data);
			}else{
				$mslist='';
			}
                    ?>
                    
                    <input type="hidden" name="mslist" class="mslist" value="{{$mslist}}">
                    <div class="form-group col-md-2">
                        <label for="Semester">Trimester <span class="required-sign">*</span></label><br>
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

                    <div class="form-group col-md-2">
                        <label for="AcademicYear">Academic Year <span class="required-sign">*</span></label>
                        <select class="form-control" name="academic_year">
                            <option {{(isset($academic_year) && ($academic_year==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
                            <option {{(isset($academic_year) && ($academic_year==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 ssc_check">
                        <label for="ssc">SSC/O' Level Roll <span class="required-sign">*</span></label>
                        <input type="text" class="form-control ssc_roll" name="ssc_roll" placeholder="Roll Number" value="{{old('ssc_roll')}}"></span>
                        <input type="hidden" name="ssc_roll_valid" class="ssc_roll_valid" value="{{old('ssc_roll_valid')}}">
                    </div>
                </div>

                <!-- For MBA Student -->
                <div class="row" id="mba_form" style="display:none;">
                    <div id="id"></div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Graduate Program Major</label>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-2">Management</label>
                            <label class="col-md-10 checkbox-inline">
                                <input type = "radio" name="management" value= "1">1
                                <input type = "radio" name="management" value = "2">2
                                <input type = "radio" name="management" value = "3">3
                                <input type = "radio" name="management" value = "4">4
                                <input type = "radio" name="management" value = "5">5
                                <input type = "radio" name="management" value = "6">6
                                <input type = "radio" name="management" value = "7">7
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Marketing</label>
                            <label class="col-md-10 checkbox-inline">
                                <input type = "radio" name="marketing" value="1">1
                                <input type = "radio" name="marketing" value="2">2
                                <input type = "radio" name="marketing" value = "3">3
                                <input type = "radio" name="marketing" value = "4">4
                                <input type = "radio" name="marketing" value = "5">5
                                <input type = "radio" name="marketing" value = "6">6
                                <input type = "radio" name="marketing" value = "7">7
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Finance</label>
                            <label class="col-md-10 checkbox-inline">
                                <input type = "radio" name="finance" value= "1">1
                                <input type = "radio" name="finance" value= "2">2
                                <input type = "radio" name="finance" value= "3">3
                                <input type = "radio" name="finance" value= "4">4
                                <input type = "radio" name="finance" value = "5">5
                                <input type = "radio" name="finance" value = "6">6
                                <input type = "radio" name="finance" value = "7">7
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Accounting</label>
                            <label class="col-md-10 checkbox-inline">
                                <input type = "radio" name="accounting" value= "1">1
                                <input type = "radio" name="accounting" value= "2">2
                                <input type = "radio" name="accounting" value= "3">3
                                <input type = "radio" name="accounting" value= "4">4
                                <input type = "radio" name="accounting" value = "5">5
                                <input type = "radio" name="accounting" value = "6">6
                                <input type = "radio" name="accounting" value = "7">7
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4">HR Management</label>
                            <label class="col-md-8 checkbox-inline">
                                <input type = "radio" name="hr_management" value= "1">1
                                <input type = "radio" name="hr_management" value= "2">2
                                <input type = "radio" name="hr_management" value= "3">3
                                <input type = "radio" name="hr_management" value= "4">4
                                <input type = "radio" name="hr_management" value = "5">5
                                <input type = "radio" name="hr_management" value = "6">6
                                <input type = "radio" name="hr_management" value = "7">7
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Bank Management</label>
                            <label class="col-md-8 checkbox-inline">
                                <input type = "radio" name="bank_management" value= "1">1
                                <input type = "radio" name="bank_management" value= "2">2
                                <input type = "radio" name="bank_management" value= "3">3
                                <input type = "radio" name="bank_management" value= "4">4
                                <input type = "radio" name="bank_management" value = "5">5
                                <input type = "radio" name="bank_management" value = "6">6
                                <input type = "radio" name="bank_management" value = "7">7
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">International Bussiness</label>
                            <label class="col-md-8 checkbox-inline">
                                <input type = "radio" name="int_bussiness" value= "1">1
                                <input type = "radio" name="int_bussiness" value= "2">2
                                <input type = "radio" name="int_bussiness" value= "3">3
                                <input type = "radio" name="int_bussiness" value= "4">4
                                <input type = "radio" name="int_bussiness" value = "5">5
                                <input type = "radio" name="int_bussiness" value = "6">6
                                <input type = "radio" name="int_bussiness" value = "7">7
                            </label>
                        </div>
                    </div>

                </div>
                <!-- For MBA Student -->

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="Bank Name">Payment Name</label>
                        <select class="form-control" name="bank_name">
                            <option value="">--Select Type--</option>
                            <option {{old('bank_name')=='MBL'?'selected':''}} value="MBL">Mercantile Bank Ltd</option>
<!--                            <option {{old('bank_name')=='bKash'?'selected':''}} value="bKash">bKash</option>
                            <option {{old('bank_name')=='UCash'?'selected':''}} value="UCash">UCash</option>
                            <option {{old('bank_name')=='Rocket'?'selected':''}} value="Rocket">Rocket Account(DBBL)</option>
                            <option {{old('bank_name')=='Mastercard'?'selected':''}} value="Mastercard">Master Card</option>
                            <option {{old('bank_name')=='Visa'?'selected':''}} value="Visa">Visa Card</option>
                            <option {{old('bank_name')=='MCash'?'selected':''}} value="MCach">M Cash</option>
                            <option {{old('bank_name')=='MyCash'?'selected':''}} value="MyCash">My Cash</option> -->
                        </select>
                        <!-- <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="{{old('bank_name')}}"> -->
                    </div>
                    <div class="form-group col-md-3">
                        <label for="Slip Number">Slip No.</label><br>
                        <input type="text" class="form-control" name="bank_slip_number" placeholder="Slip Number" value="{{old('bank_slip_number')}}" >
                    </div>

                    <div class="form-group col-md-3">
                        <label for="Amount">Paid Amount</label>
                        <input type="text" class="form-control" name="applicant_fees_amount" placeholder="Amount" value="{{old('applicant_fees_amount')}}" >
                    </div>
                </div>
                <input type="hidden" name="image_url" id="image_url" value="{{old('image_url')}}">
                <hr style="border-width: 1px;">
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary ">Next</button>
                        </div>
                    </div>
                </div>  
            </div>
        </form>
        
        <!--Content Image-->
        <div class="form-group col-md-2" >
            <div id="validation-errors"></div>
            <label>Passport Size Colored Photo (max-size:50KB)<span class="required-sign">*</span></label>    
            <div id="demo">

                <img  src="{{old('image_url') ? asset(old('image_url')):asset('images/profile.png')}}" style="height:168px" alt="img">
            </div>
            <div class="uploader">
                <form class="example" id="upload" enctype="multipart/form-data" method="post" action="{{ url('/online-application/form/image') }}" autocomplete="off">
                    <div class="fileinputs">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                        <span class="btn btn-primary btn-file span-photo"> 
                            Browse Photo<input name="image" id="image" noscript="true" type="file" name="photo" class="form-control btn-file-browse-photo">
                        </span>
                    </div>

                </form>
                <div class='image_loader'></div>
            </div>
            
            <!--/Content Image-->
        </div>

    </div><!-- /Basiincformation -->
</div><!--basic block form-->
@endif

@if(isset($from_part)&&($from_part==2))
<div class="contact_block_info multi_field_ssc_grade_wrapper multi_field_hsc_grade_wrapper"><!--academic block form-->
    <form action="{{url('/online-application/form/academic-info')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-md-12 page_heading">
                <h2>Academic Information</h2>
            </div>
        </div>


        <h4>SSC/'O' Level/Equivalent</h4>
        <div class="row">
            <div class="form-group col-md-2">
                <label >Exam Type <span class="required-sign">*</span></label>
                <select class="form-control" name="ssc_olevel_exam_type" >
                    <option  {{(old('ssc_olevel_exam_type')== "SSC") ? "selected" :''}} value="SSC">SSC/Equivalent</option>
                    <option  {{(old('ssc_olevel_exam_type')== "olevel") ? "selected" :''}} value="olevel">O level</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label >Group <span class="required-sign">*</span></label>
                <select class="form-control" name="ssc_olevel_group">
                    <option {{(old('ssc_olevel_group')== "science") ? "selected" :''}} value="science">Science</option>
                    <option {{(old('ssc_olevel_group')== "arts") ? "selected" :''}} value="arts">Arts</option>
                    <option {{(old('ssc_olevel_group')== "commerce") ? "selected" :''}} value="commerce">Commerce</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label >Roll Number <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="ssc_olevel_rollnumber" placeholder="Roll Number" value="{{old('ssc_olevel_rollnumber')}}">
            </div>
            

            <div class="form-group col-md-2">
                <label >Board <span class="required-sign">*</span></label>
                <select  class="form-control" name="ssc_olevel_board">
                    <option  {{(old('ssc_olevel_board')== 0) ? "selected" :''}} value="0"selected>Select One</option>
                    <option  {{(old('ssc_olevel_board')== "barisal") ? "selected" :''}} value="barisal">Barisal</option>
                    <option  {{(old('ssc_olevel_board')== "chittagong") ? "selected" :''}} value="chittagong">Chittagong</option>
                    <option  {{(old('ssc_olevel_board')== "comilla") ? "selected" :''}} value="comilla">Comilla</option>
                    <option  {{(old('ssc_olevel_board')== "dhaka") ? "selected" :''}} value="dhaka">Dhaka</option>
                    <option  {{(old('ssc_olevel_board')== "dinajpur") ? "selected" :''}} value="dinajpur">Dinajpur</option>
                    <option  {{(old('ssc_olevel_board')== "jessore") ? "selected" :''}} value="jessore">Jessore</option>
                    <option  {{(old('ssc_olevel_board')== "rajshahi") ? "selected" :''}} value="rajshahi">Rajshahi</option>
                    <option  {{(old('ssc_olevel_board')== "sylhet") ? "selected" :''}} value="sylhet">Sylhet</option>
                    <option  {{(old('ssc_olevel_board')== "madrasah") ? "selected" :''}} value="madrasah">Madrasah</option>
                    <option  {{(old('ssc_olevel_board')== "technical") ? "selected" :''}} value="technical">Technical</option>
                    <option  {{(old('ssc_olevel_board')== "dibs") ? "selected" :''}} value="dibs">DIBS(Dhaka)</option>
                    <option  {{(old('ssc_olevel_board')== "olevel") ? "selected" :''}} value="olevel">O Level</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label>SSC Institute Name<span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="ssc_institute_name" placeholder="SSC Institute Name" value="{{old('ssc_institute_name')}}">
            </div>

            <div class="form-group col-md-2">
                <label >Passing Year <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="ssc_olevel_year" placeholder="Year" value="{{old('ssc_olevel_year')}}">
            </div>
            
        </div>

        <div class="row">

            <div class="form-group col-md-7">
                <label >Subjects <span class="required-sign">*</span></label>
                <input type="text" class="form-control uppercase_name"  name="ssc_olevel_subject_1" placeholder="Subject 1" value="{{old('ssc_olevel_subject_1')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="ssc_olevel_subject_2" placeholder="Subject 2" value="{{old('ssc_olevel_subject_2')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="ssc_olevel_subject_3" placeholder="Subject 3" value="{{old('ssc_olevel_subject_3')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="ssc_olevel_subject_4" placeholder="Subject 4" value="{{old('ssc_olevel_subject_4')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="ssc_olevel_subject_5" placeholder="Subject 5" value="{{old('ssc_olevel_subject_5')}}">
                
            </div>
            <div class="form-group col-md-2">
                <label >Grade Point <span class="required-sign">*</span></label>
                <input type="text"  class="form-control select_point" data-subgp='1'  name="ssc_olevel_subject_gpa_1" placeholder="Ex:- 5.00" value="{{old('ssc_olevel_subject_gpa_1')}}">
                <input type="text"  class="form-control margin-top-10 select_point" data-subgp='2'  name="ssc_olevel_subject_gpa_2" placeholder="Ex:- 5.00" value="{{old('ssc_olevel_subject_gpa_2')}}">
                <input type="text" class="form-control margin-top-10 select_point" data-subgp='3'  name="ssc_olevel_subject_gpa_3" placeholder="Ex:- 5.00" value="{{old('ssc_olevel_subject_gpa_3')}}">
                <input type="text" class="form-control margin-top-10 select_point"  data-subgp='4' name="ssc_olevel_subject_gpa_4" placeholder="Ex:- 5.00" value="{{old('ssc_olevel_subject_gpa_4')}}">
                <input type="text" class="form-control margin-top-10 select_point" data-subgp='5'  name="ssc_olevel_subject_gpa_5" placeholder="Ex:- 5.00" value="{{old('ssc_olevel_subject_gpa_5')}}">
                
            </div>
            <div class="form-group col-md-3" id="">
                <label >Grade <span class="required-sign">*</span></label>
                <input type="text" class="form-control uppercase_name" data-subgrd='1' name="ssc_olevel_subject_grade_1" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_1" value="{{old('ssc_olevel_subject_grade_1')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='2' name="ssc_olevel_subject_grade_2" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_2" value="{{old('ssc_olevel_subject_grade_2')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='3'  name="ssc_olevel_subject_grade_3" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_3" value="{{old('ssc_olevel_subject_grade_3')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='4' name="ssc_olevel_subject_grade_4" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_4" value="{{old('ssc_olevel_subject_grade_4')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='5' name="ssc_olevel_subject_grade_5" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_5" value="{{old('ssc_olevel_subject_grade_5')}}">

            </div>

        </div>
        <div class="row multi_ssc_subject_row">
            <div class="multi_ssc_subject" >
            </div>
            <div class="col-md-12" style="margin-top:5px">
                <button type="button" class="btn btn-primary btn-sm add_ssc_subject" style="float:left"><i class="fa fa-plus" aria-hidden="true"></i> Add Subject</button>
                <input type="hidden" class="site_url" value="{{url('/')}}">
            </div>
            <input type="hidden" name="multi_ssc_subject_count" class="multi_ssc_subject_count" value="5" >
        </div>

        <div class="row ">
            <div class="col-md-8">
                <label style="float:right;margin-top:10px">SSC GPA (With optional Subject):</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="total_ssc_olevel_gpa" placeholder="Ex:- 5.00" value="{{old('total_ssc_olevel_gpa')}}">
            </div>
        </div>
        <br>
        <h4>HSC/'A' Level/Equivalent</h4>
        <div class="row">
            <div class="form-group col-md-2">
                <label >Exam Type <span class="required-sign">*</span></label>
                <select class="form-control" name="hsc_alevel_exam_type" >
                    <option {{(old('hsc_alevel_exam_type')== "HSC") ? "selected" :''}} value="HSC">HSC/Equivalent</option>
                    <option {{(old('hsc_alevel_exam_type')== "Alevel") ? "selected" :''}} value="Alevel">A level</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label >Group <span class="required-sign">*</span></label>
                <select class="form-control" name="hsc_alevel_group">
                    <option {{(old('hsc_alevel_group')== "science") ? "selected" :''}} value="science">Science</option>
                    <option {{(old('hsc_alevel_group')== "arts") ? "selected" :''}} value="arts">Arts</option>
                    <option {{(old('hsc_alevel_group')== "commerce") ? "selected" :''}} value="commerce">Commerce</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label >Roll Number <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="hsc_alevel_rollnumber" placeholder="Roll Number" value="{{old('hsc_alevel_rollnumber')}}">
            </div>
            <div class="form-group col-md-2">
                <label >Board <span class="required-sign">*</span></label>
                <select  class="form-control" name="hsc_alevel_board">
                    <option  {{(old('hsc_alevel_board')== 0) ? "selected" :''}} value="0"selected>Select One</option>
                    <option  {{(old('hsc_alevel_board')== "barisal") ? "selected" :''}} value="barisal">Barisal</option>
                    <option  {{(old('hsc_alevel_board')== "chittagong") ? "selected" :''}} value="chittagong">Chittagong</option>
                    <option  {{(old('hsc_alevel_board')== "comilla") ? "selected" :''}} value="comilla">Comilla</option>
                    <option  {{(old('hsc_alevel_board')== "dhaka") ? "selected" :''}} value="dhaka">Dhaka</option>
                    <option  {{(old('hsc_alevel_board')== "dinajpur") ? "selected" :''}} value="dinajpur">Dinajpur</option>
                    <option  {{(old('hsc_alevel_board')== "jessore") ? "selected" :''}} value="jessore">Jessore</option>
                    <option  {{(old('hsc_alevel_board')== "rajshahi") ? "selected" :''}} value="rajshahi">Rajshahi</option>
                    <option  {{(old('hsc_alevel_board')== "sylhet") ? "selected" :''}} value="sylhet">Sylhet</option>
                    <option  {{(old('hsc_alevel_board')== "madrasah") ? "selected" :''}} value="madrasah">Madrasah</option>
                    <option  {{(old('hsc_alevel_board')== "technical") ? "selected" :''}} value="technical">Technical</option>
                    <option  {{(old('hsc_alevel_board')== "dibs") ? "selected" :''}} value="dibs">DIBS(Dhaka)</option>
                    <option  {{(old('hsc_alevel_board')== "alevel") ? "selected" :''}} value="alevel">A Level</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label>HSC Institute Name<span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="hsc_institute_name" placeholder="HSC Institute Name" value="{{old('hsc_institute_name')}}">
            </div>

            <div class="form-group col-md-2">
                <label >Passing Year <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="hsc_alevel_year" placeholder="Year" value="{{old('hsc_alevel_year')}}" >
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-7">
                <label >Subjects <span class="required-sign">*</span></label>
                <input type="text" class="form-control uppercase_name" name="hsc_alevel_subject_1" placeholder="Subject 1" value="{{old('hsc_alevel_subject_1')}}" >
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="hsc_alevel_subject_2" placeholder="Subject 2" value="{{old('hsc_alevel_subject_2')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" name="hsc_alevel_subject_3" placeholder="Subject 3" value="{{old('hsc_alevel_subject_3')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" name="hsc_alevel_subject_4" placeholder="Subject 4" value="{{old('hsc_alevel_subject_4')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  name="hsc_alevel_subject_5" placeholder="Subject 5" value="{{old('hsc_alevel_subject_5')}}">
            </div>
            <div class="form-group col-md-2">
                <label >Grade Point <span class="required-sign">*</span></label>
                <input type="text" class="form-control select_hsc_point"  data-hscsubgp='1' name="hsc_alevel_subject_gpa_1" placeholder="Ex:- 5.00" value="{{old('hsc_alevel_subject_gpa_1')}}">
                <input type="text" class="form-control margin-top-10 select_hsc_point" data-hscsubgp='2'  name="hsc_alevel_subject_gpa_2" placeholder="Ex:- 5.00" value="{{old('hsc_alevel_subject_gpa_2')}}">
                <input type="text" class="form-control margin-top-10 select_hsc_point" data-hscsubgp='3'   name="hsc_alevel_subject_gpa_3" placeholder="Ex:- 5.00" value="{{old('hsc_alevel_subject_gpa_3')}}">
                <input type="text" class="form-control margin-top-10 select_hsc_point"  data-hscsubgp='4'  name="hsc_alevel_subject_gpa_4" placeholder="Ex:- 5.00" value="{{old('hsc_alevel_subject_gpa_4')}}">
                <input type="text" class="form-control margin-top-10 select_hsc_point"  data-hscsubgp='5' name="hsc_alevel_subject_gpa_5" placeholder="Ex:- 5.00" value="{{old('hsc_alevel_subject_gpa_5')}}">
            </div>
            <div class="form-group col-md-3">
                <label >Grade <span class="required-sign">*</span></label>
                <input type="text" class="form-control uppercase_name" data-subgrd='1'  id="hsc_alevel_subject_grade_1" name="hsc_alevel_subject_grade_1" placeholder="Ex:- A+" value="{{old('hsc_alevel_subject_grade_1')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='2'  id="hsc_alevel_subject_grade_2" data-subgrd='2' name="hsc_alevel_subject_grade_2" placeholder="Ex:- A+" value="{{old('hsc_alevel_subject_grade_2')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='3'  id="hsc_alevel_subject_grade_3" name="hsc_alevel_subject_grade_3" placeholder="Ex:- A+" value="{{old('hsc_alevel_subject_grade_3')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name" data-subgrd='4'  id="hsc_alevel_subject_grade_4" name="hsc_alevel_subject_grade_4" placeholder="Ex:- A+" value="{{old('hsc_alevel_subject_grade_4')}}">
                <input type="text" class="form-control margin-top-10 uppercase_name"  id="hsc_alevel_subject_grade_5" data-subgrd='5' name="hsc_alevel_subject_grade_5" placeholder="Ex:- A+" value="{{old('hsc_alevel_subject_grade_5')}}">
            </div>

        </div>

        <div class="row multi_hsc_subject_row">
            <div class="multi_hsc_subject" >
            </div>
            <div class="col-md-12" style="margin-top:5px">
                <button type="button" class="btn btn-primary btn-sm add_hsc_subject" style="float:left"><i class="fa fa-plus" aria-hidden="true"></i> Add Subject</button>
                <input type="hidden" class="site_url" value="{{url('/')}}">
            </div>
            <input type="hidden" name="multi_hsc_subject_count" class="multi_hsc_subject_count" value="5" >
        </div>

        <div class="row ">
            <div class="col-md-8">
                <label style="float:right;margin-top:10px">HSC GPA (With optional Subject):</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="total_hsc_alevel_gpa" placeholder="Ex:- 5.00" value="{{old('total_hsc_alevel_gpa')}}">
            </div>
        </div>

        <?php
        if(\Session::has('application_basic_form')){
            $application_basic_form = \Session::get('application_basic_form');
            $program_id = $application_basic_form['program'];

            $program_code = \App\Applicant::GetProgramCode($program_id);
        }

        ?>

        @if(($program_code->program_degree_code=='02') && !($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')) 
        <br><br>
        <h4>Hon's Qualification</h4>
        <div class="row">

            <input type="hidden" value="Hons" name="hons_qualification" />
            <div class="col-md-4">
                <label>Hon's Subject/Program<span class="required-sign">*</span></label>
                <input type="text" name="hons_subject" value="{{old('hons_subject')}}" placeholder="Hon's Subject/Program" class="form-control">
            </div>

            <div class="col-md-4">
                <label>University/College <span class="required-sign">*</span></label>
                <input type="text" name="hons_university_college" value="{{old('hons_university_college')}}" placeholder="University/College" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Passing Year <span class="required-sign">*</span></label>
                <input type="text" name="hons_passing_year" value="{{old('hons_passing_year')}}" placeholder="Passing Year" class="form-control">
            </div>

            <div class="col-md-2">
                <label>CGPA/Grade/Division <span class="required-sign">*</span></label>
                <input type="text" name="hons_grade_division" value="{{old('hons_grade_division')}}" placeholder="Grade/Division" class="form-control">
            </div>

        </div>

        <br><br>
        <h4>Masters Qualification</h4>
        <div class="row">

            <input type="hidden" value="Masters" name="masters_qualification" />
            <div class="col-md-4">
                <label>Masters Subject/Program</label>
                <input type="text" name="masters_subject" value="{{old('masters_subject')}}" placeholder="Masters Subject/Program" class="form-control">
            </div>

            <div class="col-md-4">
                <label>University/College</label>
                <input type="text" name="masters_university_college" value="{{old('masters_university_college')}}" placeholder="University/College" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Passing Year</label>
                <input type="text" name="masters_passing_year" value="{{old('masters_passing_year')}}" placeholder="Passing Year" class="form-control">
            </div>

            <div class="col-md-2">
                <label>CGPA/Grade/Division</label>
                <input type="text" name="masters_grade_division" value="{{old('masters_grade_division')}}" placeholder="Grade/Division" class="form-control">
            </div>

        </div>
        @endif

        @if($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')
        <br><br>
        <h4>Hons/Pass Course</h4>
        <div class="row">
            <div class="col-md-2">
                <label>Exam Type <span class="required-sign">*</span></label>
                <select class="form-control" name="exam_type_for_mba">
                    <option {{old('exam_type_for_mba')=='pass course(3years)'? 'selected':''}} value="pass course(3years)">Pass Course (3 years)</option>
                    <option {{old('exam_type_for_mba')=='hons(4years)'?'selected':''}} value="hons(4years)">Hon's (4 years)</option>
                    <option {{old('exam_type_for_mba')=='bba(4years)'?'selected':''}} value="bba(4years)">BBA (4 years)</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Area in Major</label>
                <input type="text" name="area_major" value="{{old('area_major')}}" placeholder="Area in Major" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Roll/ID <span class="required-sign">*</span></label>
                <input type="text" name="roll_number" value="{{old('roll_number')}}" placeholder="Roll/ID" class="form-control">
            </div>

            <div class="col-md-2">
                <label>University/College <span class="required-sign">*</span></label>
                <input type="text" name="university_college" value="{{old('university_college')}}" placeholder="University/College" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Passing Year <span class="required-sign">*</span></label>
                <input type="text" name="passing_year" value="{{old('passing_year')}}" placeholder="Passing Year" class="form-control">
            </div>

            <div class="col-md-2">
                <label>CGPA/Grade/Division <span class="required-sign">*</span></label>
                <input type="text" name="cgpa" value="{{old('cgpa')}}" placeholder="CGPA" class="form-control">
            </div>

        </div>
        <br><br>

        <h4>Masters Qualification</h4>
        <div class="row">

            <input type="hidden" value="Masters" name="masters_qualification" />
            <div class="col-md-4">
                <label>Masters Subject/Program</label>
                <input type="text" name="masters_subject" value="{{old('masters_subject')}}" placeholder="Masters Subject/Program" class="form-control">
            </div>

            <div class="col-md-4">
                <label>University/College</label>
                <input type="text" name="masters_university_college" value="{{old('masters_university_college')}}" placeholder="University/College" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Passing Year</label>
                <input type="text" name="masters_passing_year" value="{{old('masters_passing_year')}}" placeholder="Passing Year" class="form-control">
            </div>

            <div class="col-md-2">
                <label>CGPA/Grade/Division</label>
                <input type="text" name="masters_grade_division" value="{{old('masters_grade_division')}}" placeholder="Grade/Division" class="form-control">
            </div>

        </div>
        <br><br>
        @if($program_code->program_id=='97')
        <h4>Professional Experience</h4>
        <div class="row">
            <div class="multi-field-wrapper">
                <div class="multi-fields">
                    <div class="multi-field pro_div_1">
                        <div class="col-md-2">
                            <label>Organization <span class="required-sign">*</span></label>
                            <input type="text" name="organization_1" class="form-control" placeholder="Organization 1" value="{{old('organization_1')}}">
                        </div>
                        <div class="col-md-2">
                            <label>Position <span class="required-sign">*</span></label>
                            <input type="text" name="position_held_1" class="form-control" placeholder="Position" value="{{old('position_held_1')}}">
                        </div>
                        

                        <div class="col-md-3">
                            <label class="control-label">From Date</label>
                            <div class="input-group form_date_group date form_date_1 col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="period_from_1" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" name="period_from_1" id="period_from_1" value="" /><br/>
                        </div>
                        <div class="col-md-1">
                            <div style="margin-top:27px;font-size:12px;">
                                <label><input type="checkbox" name="period_to_1" data-till="1" value="0000-00-00" /> <span>Till Now</span></label>
                            </div>
                        </div>
                        <div class="col-md-3 1">
                            <label class="control-label">To Date</label>
                            <div class="input-group form_date_group date form_date_1 col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="period_to_1" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" value="{{old('period_to_1')}}" readonly >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <input type="hidden" name="period_to_1" id="period_to_1" value="" /><br/>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-sm add-field" style="float:left"><i class="fa fa-plus" aria-hidden="true"></i> Experience</button>
                </div>

            </div><br>
            <input type="hidden" name="multi_count" class="multi_count" value="1" >
            <input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">
            
        </div>

        @endif

        @endif

        <!-- Academic Information ends-->
        <br><br><br>


        <hr style="border-width: 1px;">
        <br>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="pull-right">
                    <a href="{{url('/online-application/form/remove-session')}}" class="btn btn-danger">Close</a>
                    <button type="reset" class="btn btn-success">Reset</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
        
    </form>
</div><!--academic block form-->
@endif

@if(isset($from_part)&&($from_part==3))

<div class="personal_block_info" ><!--personal block form-->
    <div class="row">
        <div class="col-md-12 page_heading">
            <h2>Personal Information</h2>
        </div>
    </div>
    <div class="row">
        <form action="{{url('/online-application/form/personal-info')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label for="Gender">Gender <span class="required-sign">*</span></label>
                    <select class="form-control" name="gender">
                        <option {{(old('gender')== "male") ? "selected" :''}} value="male">Male</option>
                        <option {{(old('gender')== "female") ? "selected" :''}} value="female">Female</option>
                    </select>
                </div>


              <div class="col-md-3">
                <label class="control-label">Date of Birth <span class="required-sign">*</span></label>
                <div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="{{old('date_of_birth')}}" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
                <input type="hidden" name="date_of_birth" id="date_of_birth" value="{{old('date_of_birth')}}" /><br/>
              </div>

              <div class="form-group col-md-3">
                <label >Blood Group</label>
                <select class="form-control" name="blood_group">
                    <option {{(old('blood_group')== "") ? "selected" :''}}  value="">--Select Blood Group--</option>
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
                <label >Marital Status <span class="required-sign">*</span></label>
                <select class="form-control" name="marital">
                    <option  {{(old('marital')== "single") ? "selected" :''}} value="single">Single</option>
                    <option {{(old('marital')== "married") ? "selected" :''}} value="married">Married</option>
                    <option {{(old('marital')== "other") ? "selected" :''}} value="other">Other</option>
                </select>
              </div>
          </div>

          <div class="col-md-12">
            <div class="form-group col-md-3">
                <label for="City">Birth Place: City/District <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="birth_city" placeholder="City" value="{{old('birth_city')}}"> 
            </div>
            <div class="form-group col-md-3">
                <label for="Country">Birth Place: Country <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="birth_country" value="{{old('birth_country')}}" placeholder="Country" >   
            </div>
            <div class="form-group col-md-3">
                <label >Nationality <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="nationality" placeholder="Nationality" value="{{old('nationality')}}">
            </div>

            <div class="form-group required col-md-3">
                <label for="City">Religion <span class="required-sign">*</span></label>
                <span class="control-label"></span>
                <select class="form-control" name="religion">
                    <option {{(old('religion')== "") ? "selected" :''}}  value="">--Select Religion--</option>
                    <option {{(old('religion')== "islam") ? "selected" :''}}  value="islam">Islam</option>
                    <option {{(old('religion')== "christianity") ? "selected" :''}} value="christianity">Christianity</option>
                    <option {{(old('religion')== "hinduism") ? "selected" :''}} value="hinduism">Hinduism</option>
                    <option {{(old('religion')== "buddhism") ? "selected" :''}} value="buddhism">Buddhism</option>
                </select> 
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-4">
                <label for="Email">Email <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="applicant_email" placeholder="example@example.com" value="{{old('applicant_email')}}" >   
            </div>
            <div class="form-group col-md-4">
                <label for="Phone">Phone</label>
                <input type="text" class="form-control" name="applicant_phone" placeholder="Ex:- 9300000" value="{{old('applicant_phone')}}" >  
            </div>
            <div class="form-group col-md-4">
                <label >Mobile <span class="required-sign">*</span></label>
                <input type="text" class="form-control" name="applicant_mobile" placeholder="Ex:- 01XXXXXXXXX" value="{{old('applicant_mobile')}}">
            </div>
          </div>
          <hr style="border-width: 1px;">
          <br>

          <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="{{url('/online-application/form/remove-session')}}" class="btn btn-danger">Close</a>
                    <a href="{{\Request::url()}}" class="btn btn-success">Reset</a>
                    <button type="submit" class="btn btn-primary ">Next</button>
                </div>
            </div>
          </div>
          
        </form>
    </div>
</div> <!--personal block form-->
@endif

@if(isset($from_part)&&($from_part==4))
<div class="contact_block_info" ><!--contact/gurdian block form-->
    <form action="{{url('/online-application/form/contact-info')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-md-12 page_heading">
                <h2>Contact Information</h2>
            </div>
            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label >Present Address <span class="required-sign">*</span></label>
                    <textarea class="form-control" name="present_address_detail" rows="1">{{old('present_address_detail')}}</textarea>

                </div>
                <div class="form-group col-md-3">
                    <label>Postal Code <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="present_postal_code" value="{{old('present_postal_code')}}" placeholder="Postal Code" >
                </div>
                <div class="form-group col-md-3">
                    <label >City/District <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="present_city" placeholder="City" value="{{old('present_city')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label>Country <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="present_country" value="{{old('present_country')}}" placeholder="Country" >
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label >Permanent Address <span class="required-sign">*</span></label>
                    <textarea class="form-control" name="permanent_address_detail" rows="1">{{old('permanent_address_detail')}}</textarea>

                </div>
                <div class="form-group col-md-3">
                    <label>Postal Code <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="permanent_postal_code" placeholder="Postal Code" value="{{old('permanent_postal_code')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label >City/District <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="permanent_city" placeholder="City" value="{{old('permanent_city')}}">
                </div>
                <div class="form-group col-md-3">
                    <label>Country <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="permanent_country" placeholder="Country" value="{{old('permanent_country')}}">
                </div>
            </div>

            <div class="col-md-12 page_heading">
                <h2>Guardian Information</h2>
            </div>

            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label >Father's Name <span class="required-sign">*</span></label>
                    <input type="text" class="form-control uppercase_name" name="father_name" placeholder="Fathers Name" value="{{old('father_name')}}">
                </div>
                <div class="form-group col-md-3">
                    <label >Father's Occupation <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="father_occupation" placeholder="Fathers Occupation" value="{{old('father_occupation')}}">
                </div>
                
                <div class="form-group col-md-3">
                    <label>Mobile <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="father_contact_mobile" placeholder="Ex:- 01XXXXXXXXX" value="{{old('father_contact_mobile')}}">
                </div>
                <div class="form-group col-md-3">
                    <label>Email</label>
                    <input type="text" class="form-control" name="father_contact_email" placeholder="example@example.com" value="{{old('father_contact_email')}}" >
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label >Mother's Name <span class="required-sign">*</span></label>
                    <input type="text" class="form-control uppercase_name" name="mother_name" placeholder="Mother's Name" value="{{old('mother_name')}}">
                </div>
                <div class="form-group col-md-3">
                    <label >Mother's Occupation <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="mother_occupation" placeholder="Mother's Occupation" value="{{old('mother_occupation')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label>Mobile <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="mother_contact_mobile" placeholder="Ex:- 01XXXXXXXXX" value="{{old('mother_contact_mobile')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label>Email</label>
                    <input type="text" class="form-control" name="mother_contact_email" placeholder="example@example.com" value="{{old('mother_contact_email')}}" >
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-md-3">
                    <label >Local Guardian Name <span class="required-sign">*</span></label>
                    <input type="text" class="form-control uppercase_name" name="local_guardian_name" placeholder="Local Guardian Name" value="{{old('local_guardian_name')}}">
                </div>
                <div class="form-group col-md-3">
                    <label >Local Guardian Occupation <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="local_guardian_occupation" placeholder="Local Guardian Occupation" value="{{old('local_guardian_occupation')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label>Mobile <span class="required-sign">*</span></label>
                    <input type="text" class="form-control" name="local_guardian_contact_mobile" placeholder="Ex:- 01XXXXXXXXX" value="{{old('local_guardian_contact_mobile')}}" >
                </div>
                <div class="form-group col-md-3">
                    <label>Email</label>
                    <input type="text" class="form-control" name="local_guardian_contact_email" placeholder="example@example.com" value="{{old('local_guardian_contact_email')}}" >
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-3">
                    <h4>Emergency contact:</h4>                     
                </div>
                <div class="form-group col-md-9" style="margin-top:10px;">
                    <span style="font-size:15px;">
                        <input type="radio" name="emergency_contact"  value="Father"> Father                
                        <input type="radio" name="emergency_contact"  value="Mother"> Mother    
                        <input type="radio" name="emergency_contact"  value="Local_Guardian" checked> Local Guardian
                    </span>                                 
                </div>
            </div>
            <div class="col-md-6 pull-right">
                <div class="com-md-12">
                    <p class="declaration">I hereby declare that the information provided is true and correct</p>
                    <p class="declaration_confirm">Confirm <input type="checkbox" required/></p>
                </div>
            </div>
            <hr style="border-width: 1px;">
            <br>
            <div class="form-group col-md-12">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="{{url('/online-application/form/remove-session')}}" class="btn btn-danger">Close</a>
                        <a href="{{\Request::url()}}" class="btn btn-success">Reset</a>
                        <button type="submit" data-loading-text="Saving..." class="btn btn-primary loadingButton">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div><!--contact/gurdian block form-->
@endif

@stop
