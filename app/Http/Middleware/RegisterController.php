<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\System;

/*******************************
#
## Register Controller
#
*******************************/

class RegisterController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## RegisterOfficeDashboardPage 
    *********************************************/
    public function RegisterOfficeDashboardPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-office-home',$data);
    }


    /********************************************
    ## RegisterApplicantPage 
    *********************************************/
    public function RegisterApplicantPage(){


    	/*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

           $all_applicant = \DB::table('applicant_basic')->where('payment_status',1)->where(function($query){

                           if(isset($_GET['program'])){
                                $query->where(function ($q){
                                    $q->where('program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester'])){
                                $query->where(function ($q){
                                    $q->where('semester', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year'])){
                                $query->where(function ($q){
                                    $q->where('academic_year', $_GET['academic_year']);
                                  });
                            }

                        })         
                        ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title')
                        ->orderBy('applicant_basic.created_at','asc')->paginate(10);

            if(isset($_GET['program']))
                $program = $_GET['program'];
            else $program = null;

            if(isset($_GET['semester']))
                $semester = $_GET['semester'];
            else $semester = null;

            if(isset($_GET['academic_year']))
                $academic_year = $_GET['academic_year'];
            else $academic_year = null;

            $all_applicant->setPath(url('/register/applicant/list'));

            $pagination = $all_applicant->appends(['program' => $program, 'semester'=> $semester,'academic_year'=> $academic_year])->render();

            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
            $data['program'] =$program;
            $data['semester'] =$semester;
            $data['academic_year'] = $academic_year;

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{

            $all_applicant = \DB::table('applicant_basic')->where('payment_status',1)
    					->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title')
                        ->orderBy('applicant_basic.created_at','asc')->paginate(10);

            $all_applicant->setPath(url('/register/applicant/list'));
            $pagination = $all_applicant->render();
            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
        }

        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-applicant-list',$data);
    }

    /********************************************
    ## ApplicantDetailsAjaxPage 
    *********************************************/
    public function ApplicantDetailsAjaxPage($applicant_serial_no){

    	$applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('applicant_academic','applicant_basic.applicant_tran_code','like','applicant_academic.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_academic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->get();

         $data['applicant_info'] =$applicant_info;

        return \View::make('pages.register.ajax-register-applicant-details',$data);
    }

    /********************************************
    ## RegisterCandidateApprovedByList 
    *********************************************/
    public function RegisterCandidateApprovedByList($applicant_serial_list,$status){

    	if(!empty($applicant_serial_list)){
            $applicant_serial_list = explode(',', $applicant_serial_list);

            if(($key = array_search('0', $applicant_serial_list)) !== false) {
                unset($applicant_serial_list[$key]);
            }

            $now = date('Y-m-d H:i:s');
            if(!empty($applicant_serial_list)){
                foreach ($applicant_serial_list as $key => $applicant) {
                    $applicant_basic_info = \DB::table('applicant_basic')->where('applicant_serial_no',$applicant)->first();
                    if(!empty($applicant_basic_info)){

                        if($applicant_basic_info->payment_status==1){

                            $payment_data = array(
                                                'applicant_eligiblity' =>(int)$status,
                                                'updated_at' =>$now,
                                                'updated_by' =>\Auth::user()->user_id,
                                            );

                           
                            try{

                                 $update_payment = \DB::table('applicant_basic')->where('applicant_serial_no',$applicant)->update($payment_data);

                               \App\System::EventLogWrite('update,applicant_basic',json_encode($payment_data));

                               \Session::flash('message','Data has been Saved Successfully');

                            }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);

                            \Session::flash('errormessage','Something wrong!!!.');
                            }
                           
                        }
                    }
                }
            }
        }

        return 1;
    }

    /********************************************
    ## RegisterAdmissionPage 
    *********************************************/

    public function RegisterAdmissionPage(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year']) || isset($_GET['religion']) || isset($_GET['gender'])){

           $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.applicant_eligiblity',5)->where(function($query){

                           if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('semester', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                                $query->where(function ($q){
                                    $q->where('academic_year', $_GET['academic_year']);
                                  });
                            }

                        })         
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                        ->orderBy('applicant_basic.updated_at','desc')->paginate(10);

            if(isset($_GET['program'])&&($_GET['program'] !=0))
                $program = $_GET['program'];
            else $program = null;

            if(isset($_GET['semester'])&&($_GET['semester'] !=0))
                $semester = $_GET['semester'];
            else $semester = null;

            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0))
                $academic_year = $_GET['academic_year'];
            else $academic_year = null;

            $all_applicant->setPath(url('/register/admission/list'));

            $pagination = $all_applicant->appends(['program' => $program, 'semester'=> $semester,'academic_year'=> $academic_year])->render();

            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
            $data['program'] =$program;
            $data['semester'] =$semester;
            $data['academic_year'] = $academic_year;

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{

            $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.applicant_eligiblity',5)
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                        ->orderBy('applicant_basic.updated_at','desc')->paginate(10);

            $all_applicant->setPath(url('/register/admission/list'));
            $pagination = $all_applicant->render();
            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
        }



        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-admission-list',$data);
    }

    /********************************************
    ## AdmissionListExcelDownload 
    *********************************************/

    public function AdmissionListExcelDownload(){

        $excel_name = 'admission_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                 if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

                   $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.applicant_eligiblity',5)->where(function($query){

                                   if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                        $query->where(function ($q){
                                            $q->where('program', $_GET['program']);
                                          });
                                    }
                                    if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                                        $query->where(function ($q){
                                            $q->where('semester', $_GET['semester']);
                                          });
                                    }

                                    if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                                        $query->where(function ($q){
                                            $q->where('academic_year', $_GET['academic_year']);
                                          });
                                    }

                                })         
                                ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                                ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                                ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                                ->orderBy('applicant_basic.updated_at','desc')->get();

                    
                    $data['all_applicant'] = $all_applicant;

                    
                  

                 }else{

                    $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.applicant_eligiblity',5)
                                ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                                ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                                ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                                ->orderBy('applicant_basic.updated_at','desc')->get(10);

                   
                    $data['all_applicant'] = $all_applicant;

                 }

                 $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.excel-admission-list',$data);
            });
        })->export('xlsx');

    }

    /********************************************
    ## RegisterAdmissionConfirmPage 
    *********************************************/

    public function RegisterAdmissionConfirmPage(){


        if(isset($_GET['applicant_serial_no'])&&(!empty($_GET['applicant_serial_no']))){

            $applicant_serial_no = $_GET['applicant_serial_no'];



            $applicant_info_basic = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)->where('applicant_basic.payment_status','>=',5)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();



            if(!empty($applicant_info_basic)){

                $applicant_contact = \App\Register::ApplicantContactInfo($applicant_serial_no);
                $applicant_guardians = \App\Register::ApplicantGurdianInfo($applicant_serial_no);
               $applicant_academic = \App\Register::ApplicantAcademicDetail($applicant_serial_no);
               $waiver_list = \App\Register::WaiverList();
               
                if(!empty($applicant_contact)&& !empty($applicant_guardians)){

                    $data['applicant_info_basic'] = $applicant_info_basic;
                    $data['applicant_contact'] = $applicant_contact;
                    $data['applicant_guardians'] = $applicant_guardians;
                    $data['applicant_academic'] = $applicant_academic; 
                    $data['waiver_list'] = $waiver_list; 

                }else return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!!'); 
 
            }else return \Redirect::to('/register/admission/confirm')->with('errormessage','No Data Found');

        }


       $data['page_title'] = $this->page_title;
       return \View::make('pages.register.register-admission-confirm',$data);
        
    }



    /********************************************
    ## StudentAdmissionSubmit
    *********************************************/

    public function StudentAdmissionSubmit(){


        if(isset($_GET['applicant_serial_no'])){

            $v = \App\Register::StudentAdmissionValidation(\Request::all());

            $applicant_serial_no = $_GET['applicant_serial_no'];


            if($v->passes()){

                $student_input['first_name'] =  \Request::input('first_name');
                $student_input['middle_name'] = \Request::input('middle_name');
                $student_input['last_name'] = \Request::input('last_name');
                $student_input['birth_city'] = \Request::input('birth_city');
                $student_input['birth_country'] = \Request::input('birth_country');
                $student_input['applicant_email'] = \Request::input('applicant_email');
                $student_input['applicant_phone'] = \Request::input('applicant_phone');
                $student_input['applicant_mobile'] = \Request::input('applicant_mobile');
                $student_input['present_address_detail'] = \Request::input('present_address_detail');
                $student_input['present_postal_code'] = \Request::input('present_postal_code');
                $student_input['present_city'] = \Request::input('present_city');
                $student_input['present_country'] = \Request::input('present_country');
                $student_input['permanent_address_detail'] = \Request::input('permanent_address_detail');
                $student_input['permanent_postal_code'] = \Request::input('permanent_postal_code');
                $student_input['permanent_city'] = \Request::input('permanent_city');
                $student_input['permanent_country'] = \Request::input('permanent_country');
                $student_input['father_occupation'] = \Request::input('father_occupation');
                $student_input['father_contact_email'] = \Request::input('father_contact_email');
                $student_input['father_contact_mobile'] = \Request::input('father_contact_mobile');
                $student_input['mother_occupation'] = \Request::input('mother_occupation');
                $student_input['mother_contact_email'] = \Request::input('mother_contact_email');
                $student_input['mother_contact_mobile'] = \Request::input('mother_contact_mobile');
                $student_input['emergency_contact'] = \Request::input('emergency_contact');
                




                $applicant_info = \App\Applicant::ApplicantBasicInfo($applicant_serial_no);
                $student_basic_info=\DB::table('student_basic')->where('applicant_serial_no',$applicant_serial_no)->first();

                $student_serial_no=$student_basic_info->student_serial_no;

                $student_tran_code = $student_basic_info->student_tran_code;


                # Image Move from applicant to Student
                $student_image_url = \App\Register::StudentImageUrl($applicant_info->program_code,$applicant_info->semester_title,$applicant_info->academic_year,$student_serial_no,$applicant_info->app_image_url);

                /*------------------------Student Basic-------------------------------*/
                    
                    $now = date('Y-m-d H:i:s');
        
                    $basic_data = array(

                            'first_name'=> $student_input['first_name'],
                            'middle_name'=> $student_input['middle_name'],
                            'last_name'=> $student_input['last_name'],
                            'program' =>$applicant_info->program,
                            'semester' =>$applicant_info->semester,
                            'academic_year' =>$applicant_info->academic_year,
                            'student_image_url' =>$student_image_url,
                            'mobile' =>$student_input['applicant_mobile'],
                            'email' =>$student_input['applicant_email'],
                            'gender' =>$applicant_info->gender,
                            'religion' => $applicant_info->religion,

                            'student_status' =>1,
                            'admission_date' =>$now,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->user_id,
                            'updated_by' =>\Auth::user()->user_id,
                        );  

                     $register_data = array(
                                        'applicant_eligiblity' =>6,
                                        'updated_at' =>$now,
                                        'updated_by' =>\Auth::user()->user_id,
                                    );
                    

                     try{

                        $basic_table_update = \DB::table('student_basic')->where('applicant_serial_no',$applicant_serial_no)->update($basic_data);

                        $update_applicant = \DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($register_data);

                        \App\System::EventLogWrite('update,student_basic',json_encode($basic_data));
                        \App\System::EventLogWrite('update,applicant_basic',json_encode($register_data));

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                    }

            /*------------------------Student Personal-----------------------------------*/

                $student_personal_tran_code = \Uuid::generate(4);
                $personal_form_data = array(
                    'student_personal_tran_code' =>$student_personal_tran_code->string,
                    'student_tran_code' =>$student_tran_code,
                    'date_of_birth'=>$applicant_info->date_of_birth,
                    'blood_group'=>$applicant_info->blood_group,
                    'place_of_birth' =>$student_input['birth_city'].','.$student_input['birth_country'],
                    'marital_status' =>$applicant_info->marital_status,
                    'nationality' =>$applicant_info->nationality,
                    'phone' =>$student_input['applicant_phone'],
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );

                try{
                    $personal_form_data_insert = \DB::table('student_personal')->insert($personal_form_data);
                   \App\System::EventLogWrite('insert,student_personal',json_encode($personal_form_data));

                }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                }

                    

            /*------------------------------student contact ---------------------------------*/
                $student_contacts_tran_code_present = \Uuid::generate(4);
                $contact_peresent = array(
                        'student_contacts_tran_code' =>$student_contacts_tran_code_present->string,
                        'student_tran_code' =>$student_tran_code,
                        'contact_type' => 'present',
                        'contact_detail' => $student_input['present_address_detail'],
                        'postal_code' =>$student_input['present_postal_code'],
                        'city' =>$student_input['present_city'],
                        'country' =>$student_input['present_country'],
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                    );

                try{
                    $contact_peresent_insert = \DB::table('student_contacts')->insert($contact_peresent);
                   \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_peresent));

                    }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                }
                    

                $student_contacts_tran_code_permanent = \Uuid::generate(4);
                $contact_permanent = array(
                        'student_contacts_tran_code' =>$student_contacts_tran_code_permanent->string,
                        'student_tran_code' =>$student_tran_code,
                        'contact_type' => 'permanent',
                        'contact_detail' => $student_input['permanent_address_detail'],
                        'postal_code' =>$student_input['permanent_postal_code'],
                        'city' =>$student_input['permanent_city'],
                        'country' =>$student_input['permanent_country'],
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                    );


                try{
                    $contact_permanent_insert = \DB::table('student_contacts')->insert($contact_permanent);
                   \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_permanent));

                   }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                }

                    

            /*------------------------------student gurdianinfo ---------------------------------*/
                $applicant_guardians = \App\Register::ApplicantGurdianInfo($applicant_serial_no);

                if($student_input['emergency_contact']=='Father'){

                    $father_emergency='yes';
                    $mother_emergency='no';
                }
                else{

                    $father_emergency='no';
                    $mother_emergency='yes';
                }

                $student_gurdians_tran_code_father = \Uuid::generate(4);
                $gurdian_father = array(
                        'student_gurdians_tran_code' =>$student_gurdians_tran_code_father->string,
                        'student_tran_code' =>$student_tran_code,
                        'relation' => 'Father',
                        'gurdian_name' => $applicant_guardians[1]->gurdian_name,
                        'occupation' =>$student_input['father_occupation'],
                        'mobile' =>$student_input['father_contact_mobile'],
                        'email' =>$student_input['father_contact_email'],
                        'emergency_contact' =>$father_emergency,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                    );


                try{
                    $gurdian_father_insert = \DB::table('student_gurdians')->insert($gurdian_father);
                   \App\System::EventLogWrite('insert,student_gurdians',json_encode($gurdian_father));

                   }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                }

                
               
                $student_gurdians_tran_code_mother = \Uuid::generate(4);
                $gurdian_mother = array(
                        'student_gurdians_tran_code' =>$student_gurdians_tran_code_mother->string,
                        'student_tran_code' =>$student_tran_code,
                        'relation' => 'Mother',
                        'gurdian_name' => $applicant_guardians[0]->gurdian_name,
                        'occupation' =>$student_input['mother_occupation'],
                        'mobile' =>$student_input['mother_contact_mobile'],
                        'email' =>$student_input['mother_contact_email'],
                        'emergency_contact' =>$mother_emergency,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                    );

                try{
                    $gurdian_mother_insert = \DB::table('student_gurdians')->insert($gurdian_mother);
                   \App\System::EventLogWrite('insert,student_gurdians',json_encode($gurdian_mother));

                   }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                }

                

            /*------------------------------student qualification ---------------------------------*/
                $applicant_academic = \App\Register::ApplicantAcademicDetail($applicant_serial_no);

                if(!empty($applicant_academic)){

                    foreach ($applicant_academic as $key => $applicant) {
                        $student_qualification_tran_code = \Uuid::generate(4);
                        $academic_data = array(
                                'student_qualification_tran_code' =>$student_qualification_tran_code->string,
                                'student_tran_code' =>$student_tran_code,
                                'exam_type' => $applicant->exam_type,
                                'exam_group' => $applicant->exam_group,
                                'exam_board' => $applicant->exam_board,
                                'result_type' =>'passed',
                                'exam_roll_number' =>$applicant->exam_roll_number,
                                'passing_year' =>$applicant->passing_year,
                                'result_gpa' =>$applicant->result_gpa,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                            );


                        try{
                            $academic_insert = \DB::table('student_academic_qualification')->insert($academic_data);
                           \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($academic_data));

                           }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                        }
                                                
                    }
                }



                #----student accounts info insert----#
                
                $accounts_info_tran_code=\Uuid::generate(4);
                $program_details=\DB::table('univ_program')->where('program_id',$applicant_info->program)->first();
                $accounts_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$applicant_info->program)
                 ->where('accounts_fee_payment_type','Receivable')
                ->select('accounts_fee_name_slug','accounts_fee_slug','accounts_fee_amount')
                ->get();

                $accounts_fee_deatail=serialize($accounts_fee_deatails);

                (float)$total_fees=0;
                (float)$total_tution_fee=0;
                (float)$total_credit=$program_details->program_total_credit_hours;

                if(!empty($accounts_fee_deatails)){
                    foreach ($accounts_fee_deatails as $key => $accounts) {

                        if($accounts->accounts_fee_name_slug=='tution_fee'){
                            $total_tution_fee=(float)$accounts->accounts_fee_amount*$total_credit;
                        }
                        if($accounts->accounts_fee_name_slug!='tution_fee'){
                            $total_fees=$total_fees+(float)$accounts->accounts_fee_amount;
                        }
                    }
                }


                $accounts_total_fees=$total_tution_fee+$total_fees;

                $waiver_type = \Request::input('waiver_type');

                $student_accounts_info_data=array(
                    'accounts_info_tran_code' => $accounts_info_tran_code->string,
                    'accounts_student_tran_code' => $student_tran_code,
                    'accounts_student_serial_no' => $student_serial_no,
                    'accounts_program' => $applicant_info->program,
                    'program_duration_in_year' => $program_details->program_duration,
                    'no_of_semester_in_year' => 3,
                    'accounts_total_credit' => $program_details->program_total_credit_hours,
                    'accounts_fee_deatails' => $accounts_fee_deatail,
                    'waiver_type' => $waiver_type,
                    'accounts_total_fees' => $accounts_total_fees,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );

                
                try{
                    $student_accounts_info=\DB::table('student_accounts_info')->insert($student_accounts_info_data);
                    \App\System::EventLogWrite('insert,student_accounts_info',json_encode($student_accounts_info_data));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }

                #----end student accounts info insert----#



               return \Redirect::to('/register/admission/confirm')->with('message','Student has been Successfully Registered.');

            }else return \Redirect::to('/register/admission/confirm?applicant_serial_no='.$_GET['applicant_serial_no'])->withInput(\Request::all())->withErrors($v->messages());

        }else return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
    }




    /********************************************
    ## FacultyRegistration
    *********************************************/
    public function FacultyRegistration(){
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-faculty-account',$data);
    }


    /********************************************
    ## FacultyRegistrationSubmit
    *********************************************/

    public function FacultyRegistrationSubmit(){

        $data['page_title'] = $this->page_title;
        $v = \App\Register::FacultyBasicFormValidation(\Request::all());

        if($v->passes()){

         $now=date('Y-m-d H:i:s');


         #Faculty last Count by department
         $department = \Request::input('department');
         $facult_last_number = \App\Register::FacultyCount($department);

         $faculty_serial = str_pad(($facult_last_number+1), 3,0,STR_PAD_LEFT);
         $faculty_join_year = date('y',strtotime(\Request::input('faculty_join_date')));
         $faculty_id = $faculty_join_year.$department.$faculty_serial;

         $faculty_tran_code =\Uuid::generate(4);
         $faculty_image_url = \App\Register::FacultyImageUrl($department,$faculty_id,\Request::input('image_url'));

    
          $faculty_basic_form = array(
                'faculty_tran_code' => $faculty_tran_code,
                'faculty_id' => $faculty_id ,
                'department'=> \Request::input('department'),
                'program'=> \Request::input('program'),
                'first_name'=> strtoupper(\Request::input('first_name')),
                'middle_name' => strtoupper(\Request::input('middle_name')),
                'last_name' => strtoupper(\Request::input('last_name')),
                'mobile' =>\Request::input('mobile'),
                'date_of_birth' =>\Request::input('date_of_birth'),
                'faculty_image_url'=>$faculty_image_url,
                'email' =>\Request::input('email'),
                'gender' =>\Request::input('gender'),
                'marital_status' =>\Request::input('marital_status'),
                'nationality' =>\Request::input('nationality'),
                'religion' =>\Request::input('religion'),
                'blood_group' =>\Request::input('blood_group'),
                'faculty_status' =>'1',
                'faculty_join_date' =>\Request::input('faculty_join_date'),
                'pro_designation' =>\Request::input('pro_designation'),
                'others_designation' =>\Request::input('others_designation'),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );


             $faculty_contacts_tran_code =\Uuid::generate(4);
         
                $faculty_contacts_form = array(
                'faculty_contacts_tran_code' => $faculty_contacts_tran_code->string,
                'faculty_tran_code' => $faculty_tran_code,
                'contact_type'=> \Request::input('contact_type'),
                'contact_detail'=> \Request::input('contact_detail'),
                'postal_code'=> \Request::input('postal_code'),
                'city' =>\Request::input('city'),
                'country' =>\Request::input('country'),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );
          


                try{
                    
                    $save_new_faculty=\DB::table('faculty_basic')->insert($faculty_basic_form);
                    \App\System::EventLogWrite('insert,faculty_basic',json_encode($faculty_basic_form));

                    $save_new_faculty_contracts=\DB::table('faculty_contacts')->insert($faculty_contacts_form);
                    \App\System::EventLogWrite('insert,faculty_contacts',json_encode($faculty_contacts_form));

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/faculty-account-registration')->with('message','Something wrong !!');
                }


         return \Redirect::to('/register/faculty-account-registration')->with('message','Faculty has been added successfully');
       }else return \Redirect::to('/register/faculty-account-registration')->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    ## EmployeeRegistration
    *********************************************/
    public function EmployeeRegistration(){
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-employee-account',$data);
    }



    /********************************************
    ## EmployeeImageUpload
    *********************************************/
    public function EmployeeImageUpload(){

        //passpost 413x531 ,450x558
        $maxwidth = 455;
        $maxheight = 560;

        $file = \Request::file('image');  

        $input = array('image' => $file);

        $rules = array(
            'image' => 'image|max:100'
        );

        $validator = \Validator::make($input, $rules);
        $sizevalidator = \App\Register::EmployeeImageSize($maxwidth,$maxheight,$file); 

        if($validator->fails())
        {
            return \Response::json(['success' => 'invalid_format']);
        }
        else if(! $sizevalidator) {

            return \Response::json(['success' => 'filesize']);
        }else {

            $destinationPath = 'EMPLOYEE/';
            $filename = time()."-".$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            return \Response::json(['success' => true, 'file' => ($destinationPath.$filename)]);
        }
    }



    /********************************************
    ## EmployeeRegistrationSubmit
    *********************************************/

    public function EmployeeRegistrationSubmit(){

        $data['page_title'] = $this->page_title;
        $v = \App\Register::EmployeeBasicFormValidation(\Request::all());

        if($v->passes()){

         $now=date('Y-m-d H:i:s');

        
         $employee_last_number = \App\Register::EmployeeCount();

         $emlpoyee_serial = str_pad(($employee_last_number+1), 3,0,STR_PAD_LEFT);
         $employee_join_year = date('y',strtotime(\Request::input('employee_join_date')));
         $employee_id = $employee_join_year.$emlpoyee_serial;


         $employee_tran_code =\Uuid::generate(4);
         $employee_image_url = \App\Register::EmployeeImageUrl($employee_id,\Request::input('image_url'));

    
          $employee_basic_form = array(
                'employee_tran_code' => $employee_tran_code,
                'employee_id' => $employee_id ,
                'first_name'=> strtoupper(\Request::input('first_name')),
                'middle_name' => strtoupper(\Request::input('middle_name')),
                'last_name' => strtoupper(\Request::input('last_name')),
                'mobile' =>\Request::input('mobile'),
                'date_of_birth' =>\Request::input('date_of_birth'),
                'employee_image_url'=>$employee_image_url,
                'email' =>\Request::input('email'),
                'gender' =>\Request::input('gender'),
                'marital_status' =>\Request::input('marital_status'),
                'nationality' =>\Request::input('nationality'),
                'religion' => \Request::input('religion'),
                'blood_group' =>\Request::input('blood_group'),
                'employee_status' =>'1',
                'employee_join_date' =>\Request::input('employee_join_date'),
                'pro_designation' =>\Request::input('pro_designation'),
                'employee_designation' =>\Request::input('employee_designation'),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );


             $employee_contacts_tran_code =\Uuid::generate(4);
         
                $employee_contacts_form = array(
                'employee_contacts_tran_code' => $employee_contacts_tran_code->string,
                'employee_tran_code' => $employee_tran_code,
                'contact_type'=> \Request::input('contact_type'),
                'contact_detail'=> \Request::input('contact_detail'),
                'postal_code'=> \Request::input('postal_code'),
                'city' =>\Request::input('city'),
                'country' =>\Request::input('country'),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );
          


                 try{
                    
                    $save_new_employee_basic=\DB::table('employee_basic')->insert($employee_basic_form);
                    \App\System::EventLogWrite('insert',json_encode($employee_basic_form));

                    $save_new_employee_contracts=\DB::table('employee_contacts')->insert($employee_contacts_form);
                    \App\System::EventLogWrite('insert',json_encode($employee_contacts_form));

                 }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                     return \Redirect::to('/register/employee-registration')->with('message','Something wrong !!');
                 }


         return \Redirect::to('/register/employee-registration')->with('message','Employee has been added successfully');
       }else return \Redirect::to('/register/employee-registration')->withInput(\Request::all())->withErrors($v->messages());
    }



     /********************************************
    ## StudentList
    *********************************************/
    public function StudentList(){
        $data['page_title'] = $this->page_title;

        if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

        $student_list = \DB::table('student_basic')->where(function($query){

                           if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('semester', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                                $query->where(function ($q){
                                    $q->where('academic_year', $_GET['academic_year']);
                                  });
                            }

                        })
                        ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                        ->select('student_basic.*','univ_program.*')
                        ->get();
                        $data['student_list']= $student_list;
                    }

                    else{

                        $student_list = \DB::table('student_basic')
                        ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                        ->select('student_basic.*','univ_program.*')
                        ->get();

                        $data['student_list']= $student_list;
                    }

        return \View::make('pages.register.register-student-list',$data);
    }


    /********************************************
    ## Register Block Student Confirm 
    *********************************************/
    public function RegisterBlockStudentConfirm($student_serial_list, $action){
        $now=date('Y-m-d H:i:s');
        
        $student_block_data=array(
            'student_status' =>$action,
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->user_id,
            );          
        try{
            \DB::table('student_basic')->where('student_serial_no',$student_serial_list)->update($student_block_data);
            \App\System::EventLogWrite('update,student_basic',json_encode($student_block_data));
        }catch(\Exception  $e){
           $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
           \App\System::ErrorLogWrite($message);

           return \Redirect::to('/register/block/student')->with('message','Something wrong !!');
       }
   }



    /********************************************
    ## RegisterAcademicCalender
    *********************************************/
    public function RegisterAcademicCalender(){
        $calender_list =\DB::table('univ_academic_calender')
                        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','like','univ_semester.semester_code')
                        ->select('univ_academic_calender.*','univ_semester.*')
                        ->orderBy('univ_academic_calender.created_at','desc')->paginate(10);

        $calender_list->setPath(url('/register/academic-calender-registration'));
        $calender_pagination = $calender_list->render();
        $data['calender_pagination'] = $calender_pagination;
        $data['calender_list']=$calender_list;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-academic-calender',$data);
    }

    
    /********************************************
    ## RegisterAcademicCalenderSubmit 
    *********************************************/

    public function RegisterAcademicCalenderSubmit(){

            $v = \App\Register::AcademicCalenderFormValidation(\Request::all());

            if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $academic_calender_data = [
                                'academic_calender_tran_code' => $uuid,
                                'academic_calender_year' =>\Request::input('academic_calender_year'),
                                'academic_calender_semester' =>\Request::input('academic_calender_semester'),
                                'semester_start' =>\Request::input('semester_start'),
                                'semester_end' =>\Request::input('semester_end'),
                                'semester_course_reg_start' =>\Request::input('semester_course_reg_start'),
                                'semester_course_reg_end'=>\Request::input('semester_course_reg_end'),
                                'midterm_exam_start' => \Request::input('midterm_exam_start'),
                                'midterm_exam_end' =>\Request::input('midterm_exam_end'),
                                'final_exam_start'=>\Request::input('final_exam_start'),
                                'final_exam_end' =>\Request::input('final_exam_end'),
                                'semester_break_start'=>\Request::input('semester_break_start'),
                                'semester_break_end'=>\Request::input('semester_break_end'),
                                'academic_calender_status'=> 1,
                                'created_at' =>$now,
                                'updated_at' =>$now ,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,

                            ];

                $last_semester_status_update=array(
                    'academic_calender_status' => 2,
                    );

                try{

                    \DB::table('univ_academic_calender')->where('academic_calender_status',1)->update($last_semester_status_update);
                    \DB::table('univ_academic_calender')->insert($academic_calender_data);
                    \App\System::EventLogWrite('insert',json_encode($academic_calender_data));
                 }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                     return \Redirect::to('/register/academic-calender-registration')->with('message','Something wrong !!');
                 }

                return \Redirect::to('/register/academic-calender-registration')->with('message','Academic Calender has been added.');
                 }else return \Redirect::to('/register/academic-calender-registration')->withInput(\Request::all())->withErrors($v->messages());
    }    

    /********************************************
    ## RegisterAcademicCalenderDelete
    *********************************************/

    public function RegisterAcademicCalenderDelete($academic_calender_tran_code){

        $notice_data=\DB::table('univ_academic_calender')->where('academic_calender_tran_code', $academic_calender_tran_code)->delete();
        return \Redirect::to('/register/academic-calender-registration')->with('message'," Deleted Successfully!");
    }



    /*******************************************
    # EditAcademicCalender
    ********************************************/
    public function EditAcademicCalender($academic_calender_tran_code){

             $edit_academic_calender=\DB::table('univ_academic_calender')
                                    ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','like','univ_semester.semester_code')
                                    ->where('academic_calender_tran_code',$academic_calender_tran_code)->first();
            if(!empty($edit_academic_calender)){
                $data['page_title'] = $this->page_title;
                $data['edit_academic_calender']=$edit_academic_calender;
                return \View::make('pages.register.register-academic-calender-edit',$data);
            }else return \Redirect::to('/register/academic-calender-registration')->with('errormessage',"Invalid Notice");
        
    }




    /********************************************
    # UpdateAcademicCalender
    *********************************************/
    public function UpdateAcademicCalender($academic_calender_tran_code){
        


        $v = \App\Register::AcademicCalenderFormValidation(\Request::all());
        if($v->passes()){   

        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
            $update_calender_data = [
                                'academic_calender_year' =>\Request::input('academic_calender_year'),
                                'academic_calender_semester' =>\Request::input('academic_calender_semester'),
                                'semester_start' =>\Request::input('semester_start'),
                                'semester_end' =>\Request::input('semester_end'),
                                'semester_course_reg_start' =>\Request::input('semester_course_reg_start'),
                                'semester_course_reg_end'=>\Request::input('semester_course_reg_end'),
                                'midterm_exam_start' => \Request::input('midterm_exam_start'),
                                'midterm_exam_end' =>\Request::input('midterm_exam_end'),
                                'final_exam_start'=>\Request::input('final_exam_start'),
                                'final_exam_end' =>\Request::input('final_exam_end'),
                                'semester_break_start'=>\Request::input('semester_break_start'),
                                'semester_break_end'=>\Request::input('semester_break_end'),
                                'updated_at' =>$now,
                                'updated_by' =>\Auth::user()->user_id,   

                            ];
                try{
                    $update_data = \DB::table('univ_academic_calender')->where('academic_calender_tran_code',$academic_calender_tran_code)->update($update_calender_data);
                    \App\System::EventLogWrite('update',json_encode($update_calender_data));
                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                     return \Redirect::to('/register/academic-calender-registration')->with('message','Something wrong !!');
                 }
                 return \Redirect::to('/register/academic-calender-registration')->with('message','Academic Calender has been updated successfully.');

                }else return \Redirect::back()->withInput()->withErrors($v->messages());


         }




         /********************************************
    ## ProgramCoordintorAssign
    *********************************************/
    public function ProgramCoordintorAssign(){

        /*---------------------Get Request------------------------*/
         if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year']) ){

           $all_faculty = \DB::table('program_coordinator_assigned')->where(function($query){

                           if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('coordinator_program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('program_coordinator_semester', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                                $query->where(function ($q){
                                    $q->where('program_coordinator_year', $_GET['academic_year']);
                                  });
                            }

                        })        
                        ->leftJoin('univ_program','program_coordinator_assigned.coordinator_program','=','univ_program.program_id')
                        ->leftJoin('univ_semester','program_coordinator_assigned.program_coordinator_semester','=','univ_semester.semester_code')
                        ->leftJoin('faculty_basic','program_coordinator_assigned.coordinator_faculty_id','=','faculty_basic.faculty_id')
                        ->select('program_coordinator_assigned.*','univ_program.*','univ_semester.*','faculty_basic.*')
                        ->orderBy('program_coordinator_assigned.updated_at','desc')->paginate(10);

            if(isset($_GET['program'])&&($_GET['program'] !=0))
                $program = $_GET['program'];
            else $program = null;

            if(isset($_GET['semester'])&&($_GET['semester'] !=0))
                $semester = $_GET['semester'];
            else $semester = null;

            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0))
                $academic_year = $_GET['academic_year'];
            else $academic_year = null;

            $all_faculty->setPath(url('/register/class-teacher-assign'));

            $pagination = $all_faculty->appends(['program' => $program, 'semester'=> $semester,'academic_year'=> $academic_year])->render();

            $data['pagination'] = $pagination;
            $data['all_faculty'] = $all_faculty;
            $data['program'] =$program;
            $data['semester'] =$semester;
            $data['academic_year'] = $academic_year;

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{

            $all_faculty = \DB::table('program_coordinator_assigned')
                        ->leftJoin('univ_program','program_coordinator_assigned.coordinator_program','=','univ_program.program_id')
                        ->leftJoin('univ_semester','program_coordinator_assigned.program_coordinator_semester','=','univ_semester.semester_code')
                        ->leftJoin('faculty_basic','program_coordinator_assigned.coordinator_faculty_id','=','faculty_basic.faculty_id')
                        ->select('program_coordinator_assigned.*','univ_program.*','univ_semester.*','faculty_basic.*')
                        ->orderBy('program_coordinator_assigned.updated_at','desc')->paginate(10);

            $all_faculty->setPath(url('/register/class-teacher-assign'));
            $pagination = $all_faculty->render();
            $data['pagination'] = $pagination;
            $data['all_faculty'] = $all_faculty;

        }



        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-program-coordinetor-assign',$data);

       
    }



    /********************************************
    ## ProgramCoordintorSubmit
    *********************************************/
    public function ProgramCoordintorSubmit(){

         $v = \App\Register::ProgramCoordintorFormValidation(\Request::all());

         if($v->passes()){

            $program_coordinator_tran_code =\Uuid::generate(4);
            $now=date('Y-m-d H:i:s');

            $program_coordinator_assigned_form = array(
                'program_coordinator_tran_code' => $program_coordinator_tran_code,
                'coordinator_program'=> \Request::input('coordinator_program'),
                'program_coordinator_semester'=> \Request::input('program_coordinator_semester'),
                'program_coordinator_year' =>\Request::input('program_coordinator_year'),
                'program_coordinator_level' =>\Request::input('program_coordinator_level'),
                'program_coordinator_term' =>\Request::input('program_coordinator_term'),
                'coordinator_faculty_id'=>\Request::input('coordinator_faculty_id'),
                'coordinator_assigned_by'=>\Auth::user()->user_id,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );

            try{
                $save_new_program_coordinator=\DB::table('program_coordinator_assigned')->insert($program_coordinator_assigned_form);
                \App\System::EventLogWrite('insert,program_coordinator_assigned',json_encode($program_coordinator_assigned_form));

                return \Redirect::to('/register/class-teacher-assign')->with('message','Class Teacher has been added successfully');

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }


        }else return \Redirect::to('/register/class-teacher-assign')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## ProgramListAjax
    *********************************************/
    public function ProgramListAjax($department){

        $program_list = \DB::table('univ_program')->where('program_department_no',$department)->get();

        $data['program_list']= $program_list;
        return \View::make('pages.register.ajax-program-list',$data);
    }


    /********************************************
    ## FacultyListAjax
    *********************************************/
    public function FacultyListAjax($department){

        $faculty_list = \DB::table('faculty_basic')->where('department',$department)->get();

        $data['faculty_list']= $faculty_list;
        return \View::make('pages.register.ajax-faculty-list',$data);
    }



    
    /********************************************
    # ProgramCoordinetorEdit
    *********************************************/
    public function ProgramCoordinatorEdit($program_coordinator_tran_code){

            $coordinetor_edit = \DB::table('program_coordinator_assigned')
                        ->where('program_coordinator_tran_code',$program_coordinator_tran_code)
                        ->leftJoin('univ_program','program_coordinator_assigned.coordinator_program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','program_coordinator_assigned.program_coordinator_semester','like','univ_semester.semester_code')
                        ->leftJoin('faculty_basic','program_coordinator_assigned.coordinator_faculty_id','like','faculty_basic.faculty_id')
                        ->select('program_coordinator_assigned.*','univ_program.*','univ_semester.*','faculty_basic.*')
                        ->first();
            $data['coordinetor_edit']=$coordinetor_edit;
            $data['page_title'] = $this->page_title;
             return \View::make('pages.register.register-edit-program-coordinetor-assign',$data);
    }



    /********************************************
    ## ProgramCoordinatorUpdate
    *********************************************/
    public function ProgramCoordinatorUpdate($program_coordinator_tran_code){

        $rules = array(
            'coordinator_program'  => 'Required',
            'coordinator_faculty_id' => 'Required',
            'program_coordinator_year' => 'Required',
            'program_coordinator_semester' => 'Required',
            'program_coordinator_level' =>'Required',
            'program_coordinator_term' =>'Required',
            
            );

       $v =  \Validator::make(\Request::all(),$rules);

       if($v->passes()){
        $now=date('Y-m-d H:i:s');

        $program_coordinator_update_form = array(
            'coordinator_program'=> \Request::input('coordinator_program'),
            'program_coordinator_semester'=> \Request::input('program_coordinator_semester'),
            'program_coordinator_year' =>\Request::input('program_coordinator_year'),
            'program_coordinator_level' =>\Request::input('program_coordinator_level'),
            'program_coordinator_term' =>\Request::input('program_coordinator_term'),
            'coordinator_faculty_id'=>\Request::input('coordinator_faculty_id'),
            'updated_at' => $now,
            'updated_by' =>\Auth::user()->user_id,
            );

        try{
            \DB::table('program_coordinator_assigned')->where('program_coordinator_tran_code',$program_coordinator_tran_code)->update($program_coordinator_update_form);
            \App\System::EventLogWrite('insert',json_encode($program_coordinator_update_form));

            return \Redirect::to('/register/class-teacher-assign')->with('message','Class Teacher has been updated successfully');

        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
        }


    }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
}



    /********************************************
    # ProgramCoordinatorDelete
    *********************************************/
    public function ProgramCoordinatorDelete($program_coordinator_tran_code){
        $data=\DB::table('program_coordinator_assigned')->where('program_coordinator_tran_code',$program_coordinator_tran_code)->delete();
        return \Redirect::to('/register/class-teacher-assign')->with('message',"Class Teacher Deleted Successfully!");
    }




    /********************************************
    ## FacultyAssignedCourse
    *********************************************/
    public function FacultyAssignedCourse(){
        $data['page_title'] = $this->page_title;

        $data['program']=\App\Register::ProgramList();
        $data['semester']=\DB::table('univ_semester')->get();


        if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year']) && isset($_GET['level']) && isset($_GET['term'])){

            $all_courses = \DB::table('course_basic')->where(function($query){

             if(isset($_GET['program'])){
                $query->where(function ($q){
                    $q->where('course_program', $_GET['program']);
                });
            }

            if(isset($_GET['level'])){
                $query->where(function ($q){
                    $q->where('level', $_GET['level']);
                });
            }
            if(isset($_GET['term'])){
                $query->where(function ($q){
                    $q->where('term', $_GET['term']);
                });
            }

        })   
            ->leftJoin('univ_program','univ_program.program_id','=','course_basic.course_program')      
            ->leftJoin('course_category','course_category.course_category_slug','=','course_basic.course_category')      
            ->select('course_basic.*','univ_program.*','course_category.*')
            ->get();
            $data['all_courses']=$all_courses;
        }

        $faculty=\DB::table('faculty_basic')->get();
        $data['faculty']=$faculty;
        return \View::make('pages.register.register-faculty-assigned-course',$data);
    }



    /********************************************
    ## FacultyAssignedCourseSubmit
    *********************************************/
    public function FacultyAssignedCourseSubmit($action){

        if($action=='delete'){

            $assigned_course_tran_code=\Request::input('assigned_course_tran_code');
            $course_code=\Request::input('course_code');

            try{
                $undone=\DB::table('faculty_assingned_course')->where('assigned_course_tran_code', $assigned_course_tran_code)->delete();

                \App\System::EventLogWrite('delete,faculty_assingned_course',json_encode($course_code));
                return \Redirect::back()->with('message',"Assigned Faculty Undone Successfully !");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }

        }elseif($action=='insert'){

            $rules=array(
                'faculties' => 'required',
                );

            $validator=\Validator::make(\Request::all(), $rules);

            if($validator->passes()){

                $now=date('Y-m-d H:i:s');
                $uuid=\Uuid::generate(4);
                $course_code=\Request::input('course_code');
                $assigned_course_semester=\Request::input('assigned_course_semester');
                $assigned_course_year=\Request::input('assigned_course_year');
                if(!empty($_POST['faculties'])){
                    $faculties = implode(',', $_POST['faculties']);
                }else $faculties='';

                $course_detail=\DB::table('course_basic')->where('course_code',$course_code)->first();

                $faculty_course_assign = array(
                    'assigned_course_tran_code' => $uuid->string,
                    'assigned_course_program' => $course_detail->course_program,
                    'assigned_course_semester' => $assigned_course_semester,
                    'assigned_course_year' => $assigned_course_year,
                    'assigned_course_level' => $course_detail->level,
                    'assigned_course_term' => $course_detail->term,
                    'assigned_course_id' => $course_detail->course_code,
                    'assigned_course_title' => $course_detail->course_title,
                    'assigned_course_faculties' => $faculties,
                    'assigned_course_section' => 'A',
                    'assigned_by' => \Auth::user()->user_id,

                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );


                try{
                    $faculty_course_assign_save=\DB::table('faculty_assingned_course')->insert($faculty_course_assign);
                    \App\System::EventLogWrite('insert,faculty_assingned_course',json_encode($faculty_course_assign));

                    return \Redirect::back()->with('message',"Course Assigned For Faculty Successfully !");

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }

            }
            else return \Redirect::back()->withErrors($validator);

        }


    }



    /********************************************
    ## RegisterTrimesterStudentAssign
    *********************************************/

    public function RegisterTrimesterStudentAssign(){

        $data['page_title'] = $this->page_title;

        if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year'])){

            $all_student = \DB::table('student_basic')
            ->where('student_basic.student_status','>',0)
            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->get();

            $data['all_student']=$all_student;


       }

        return \View::make('pages.register.register-trimester-student-assign',$data);
    }



    /********************************************
    ## RegisterTrimesterStudentAssignSubmit
    *********************************************/

    public function RegisterTrimesterStudentAssignSubmit(){


        $now=date('Y-m-d H:i:s');

        $rule = [
        'semester' => 'Required',
        'academic_year' => 'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $semester=\Request::input('semester');
            $year=\Request::input('academic_year');

            $univ_semester=\DB::table('univ_semester')->where('semester_code', $semester)->first();

            $student_serial_no=\Request::input('student_serial_no');

            if(!empty($student_serial_no)){

                foreach ($student_serial_no as $key => $student) {

                    $student_basic=\DB::table('student_basic')->where('student_serial_no', $student)->first();

                    $study_level_tran_code=\Uuid::generate(4);
                    $student_study_level_data=array(
                        'study_level_tran_code' => $study_level_tran_code->string,
                        'student_tran_code' =>  $student_basic->student_tran_code,
                        'study_level_semester' => $semester,
                        'study_level_year' => $year,
                        'pre_advising_status' => 0,
                        'study_level_status' => 1,

                        'created_at' =>$now,
                        'updated_at' =>$now ,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );

                    $student_study_level_store=\DB::table('student_study_level')->insert($student_study_level_data);
                }

                return \Redirect::to('/register/trimester-student-assign')->with('message',"Student Assigned Successfully for Trimester {$univ_semester->semester_title} {$year} !");

            }else  return \Redirect::back()->with('message',"No Student Selected !");
            

        }else  return \Redirect::back()->with('message',"Select Study Level Trimester and Year !");


    }



    /********************************************
    ## TimeSlot
    *********************************************/

    public function TimeSlot(){

        $data['page_title'] = $this->page_title;

        $time_slot=\DB::table('univ_time_slot')->orderBy('univ_time_slot_for','asc')->get();
        $data['univ_time_slot']=$time_slot;

        return \View::make('pages.register.register-time-slot',$data);
    }


    /********************************************
    ## TimeSlotSubmit
    *********************************************/

    public function TimeSlotSubmit(){

        $rules=array(
            'slot_name' => 'required',
            'slot_for' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            
            $univ_time_slot_tran_code=\Uuid::generate(4);

            $start_time=\Request::input('start_time');
            $end_time=\Request::input('end_time');

            $formated_start_time=date('h:i a', strtotime($start_time));
            $formated_end_time=date('h:i a', strtotime($end_time));

            $now=date('Y-m-d H:i:s');
            $time_slot_data=array(
                'univ_time_slot_tran_code' => $univ_time_slot_tran_code->string,
                'univ_time_slot' => strtoupper(\Request::input('slot_name')),
                'univ_time_slot_slug' => $formated_start_time.'-'.$formated_end_time,
                'univ_time_slot_for' => \Request::input('slot_for'),
                'time_slot_start_time' => $formated_start_time,
                'time_slot_end_time' => $formated_end_time,

                'created_at' =>$now,
                'updated_at' =>$now ,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );

            try{

                $time_slot_save=\DB::table('univ_time_slot')->insert($time_slot_data);
                \App\System::EventLogWrite('insert,univ_time_slot',json_encode($time_slot_data));

                return \Redirect::to('/register/univ-time-slot')->with('message',"Time Slot Saved Successfully !");

            }catch(\Exception $e){

                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

            }

        }else  return \Redirect::to('/register/univ-time-slot')->withErrors($v->messages());

    }



    /********************************************
    ## TimeSlotDelete
    *********************************************/

    public function TimeSlotDelete($time_slot_tran_code){

        if(!empty($time_slot_tran_code)){
            
            try{

               $time_slot_delete=\DB::table('univ_time_slot')->where('univ_time_slot_tran_code', $time_slot_tran_code)->delete();
                \App\System::EventLogWrite('delete,univ_time_slot',json_encode($time_slot_tran_code));

                return \Redirect::to('/register/univ-time-slot')->with('message',"Time Slot Deleted Successfully !");

            }catch(\Exception $e){

                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

            }
        }else return \Redirect::to('/register/univ-time-slot')->with('message',"Time Slot Not Deleted !");
        
    }




    /********************************************
    ## ClassSchedule
    *********************************************/

    public function ClassSchedule(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'add_class_schedule';
        $data['tab'] = $tab;


        $data['page_title'] = $this->page_title;

        $data['program_list']=\App\Register::ProgramList();
        $building_list=\DB::table('univ_building')->get();
        $data['building_list']=$building_list;
        
        return \View::make('pages.register.register-class-schedule',$data);
    }


    /********************************************
    ## AjaxCourseList
    *********************************************/

    public function AjaxCourseList($program_code){

        $data['page_title'] = $this->page_title;

        $course_list=\DB::table('course_basic')->where('course_program', $program_code)->get();
        $data["course_list"]=$course_list;
        return \View::make('pages.register.schedule.ajax-course-list',$data);
    }


    /********************************************
    ## AjaxRoomList
    *********************************************/

    public function AjaxRoomList($building_code){

        $data['page_title'] = $this->page_title;

        $room_list=\DB::table('univ_room')->where('building_code',$building_code)->get();
        $data['room_list']=$room_list;
        
        return \View::make('pages.register.schedule.ajax-room-list',$data);
    }


    /********************************************
    ## AjaxClassDay
    *********************************************/

    public function AjaxClassDay($room_code){

        $data['page_title'] = $this->page_title;

        $data['room_code']=$room_code;
        
        return \View::make('pages.register.schedule.ajax-class-day',$data);
    }


    /********************************************
    ## AjaxTimeSlot
    *********************************************/

    public function AjaxTimeSlot($room_code, $class_day_week){

        $data['page_title'] = $this->page_title;
        
        $time_slot=\DB::table('univ_time_slot')->where('univ_time_slot_for',1)->orderBy('univ_time_slot','asc')->get();
        $data['time_slot']=$time_slot;

        $data['room_code']=$room_code;
        $data['class_day_week']=$class_day_week;

        return \View::make('pages.register.schedule.ajax-time-slot',$data);

        
    }


    /********************************************
    ## AjaxFacultyList
    *********************************************/

    public function AjaxFacultyList($class_day_week, $time_slot){

        $data['page_title'] = $this->page_title;

        $available_faculties=\DB::table('faculty_basic')
        ->get();
        $data['available_faculties']=$available_faculties;
        $data['class_day_week']=$class_day_week;
        $data['time_slot']=$time_slot;

        return \View::make('pages.register.schedule.ajax-faculty-list',$data);

    }


    /********************************************
    ## ClassScheduleSubmit
    *********************************************/

    public function ClassScheduleSubmit(){
        $data['page_title'] = $this->page_title;

        $rules=array(
            'class_schedule_program' => 'required',
            'class_schedule_course' => 'required',
            'class_schedule_room' => 'required',
            'class_schedule_faculty' => 'required',
            'class_schedule_day_of_week' => 'required',
            'class_schedule_time_slot' => 'required',
            );
        $v=\Validator::make(\Request::all(),$rules);


        if($v->passes()){

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

            if(!empty($univ_academic_calender)){

                $now=date('Y-m-d H:i:s');
                $class_schedule_program=\Request::input('class_schedule_program');
                $class_schedule_course=\Request::input('class_schedule_course');
                $room_code=\Request::input('class_schedule_room');
                $class_schedule_faculty=\Request::input('class_schedule_faculty');
                $class_day_week=\Request::input('class_schedule_day_of_week');
                $time_slot=\Request::input('class_schedule_time_slot');

                $class_schedule_tran_code=\Uuid::generate(4);
                $class_schedule=array(
                    'class_schedule_tran_code' => $class_schedule_tran_code->string,
                    'class_schedule_program' =>  $class_schedule_program,
                    'class_schedule_year' => $univ_academic_calender->academic_calender_year,
                    'class_schedule_semester' => $univ_academic_calender->academic_calender_semester,
                    'class_schedule_course' => $class_schedule_course,
                    'class_schedule_room' => $room_code,
                    'class_schedule_faculty' => $class_schedule_faculty,
                    'class_schedule_day_of_week' => $class_day_week,
                    'class_schedule_time_slot' => $time_slot,
                    'class_schedule_status' => 1,

                    'created_by' => \Auth::user()->user_id,
                    'updated_by' => \Auth::user()->user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );


            try{
                $class_schedule_save=\DB::table('univ_class_schedule')->insert($class_schedule);
                \App\System::EventLogWrite('insert,univ_class_schedule',json_encode($class_schedule));

                return \Redirect::to('/register/class-schedule')->with('message',"Class schedule has been added successfully !");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/class-schedule')->with('message',"Class schedule not added !");
            }


        }else  return \Redirect::back()->with('message',"Something went wrong !");

    }else return \Redirect::to('/register/class-schedule')->withErrors($v->messages());

}

    
    /********************************************
    ## AjaxClassScheduleView
    *********************************************/

    public function AjaxClassScheduleView($room_code){

        $data['page_title'] = $this->page_title;

        $data['room_code']=$room_code;

        return \View::make('pages.register.schedule.ajax-class-schedule-by-room-view',$data);

    }


    /********************************************
    ## AjaxScheduleByProgramView
    *********************************************/

    public function AjaxScheduleByProgramView($program_id){

        $data['page_title'] = $this->page_title;

        $data['program_id']=$program_id;

        return \View::make('pages.register.schedule.ajax-schedule-by-program-view',$data);

    }


    /********************************************
    ## ScheduleDelete
    *********************************************/

     public function ScheduleDelete($schedule_tran_code){

        if(!empty($schedule_tran_code)){

           try{
            $schedule_delete=\DB::table('univ_class_schedule')->where('class_schedule_tran_code', $schedule_tran_code)->delete();

            \App\System::EventLogWrite('delete,univ_class_schedule', $schedule_tran_code);
            return \Redirect::back()->with('message',"Schedule Deleted Successfully !");

        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('message',"Problem Finding Schedule !");
        }

    }else return \Redirect::back()->with('message',"Problem Finding Schedule !");

}

    
    
    /********************************************
    ## SchedulePdfDownload 
    *********************************************/

    public function SchedulePdfDownload(){


        if(isset($_GET['program']) && $_GET['program'] !=0){
            $univ_program=\DB::table('univ_program')->where('program_id', $_GET['program'])->first();
            if(!empty($univ_program)){
                $program=$univ_program->program_code;
            }else{
                return \Redirect::to('/register/class-schedule')->with('message',"Program Not Found !");
            }
            
        }else{

            $program='Full';
        }

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_semester.semester_code','=','univ_academic_calender.academic_calender_semester')
        ->first();

        if(!empty($univ_academic_calender)){

            $pdf_name = 'Class_Schedule_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.$program.'_'.date('i_s');

            $data['page_title']=$this->page_title;

            if(isset($_GET['program']) && $_GET['program'] !=0){

                $program_id=$_GET['program'];
            }else{

             $program_id='';
         }
         $data['program_id']=$program_id;
         $data['semester_title']=$univ_academic_calender->semester_title;
         $data['academic_year']=$univ_academic_calender->academic_calender_year;

         $pdf = \PDF::loadView('pages.register.schedule.pdf.class-schedule-download-pdf',$data);
         return  $pdf->stream($pdf_name.'.pdf'); 

     }else return \Redirect::to('/register/class-schedule?tab=full_schedule')->with('message',"Academic Calender Not Set Yet !");


 }



    /********************************************
    ## ExamSchedule
    *********************************************/
    public function ExamSchedule(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'add_exam_schedule';
        $data['tab'] = $tab;

        if (isset($_GET['exam_type']) && isset($_GET['exam_date'])) {
            $data['exam_type'] = $_GET['exam_type'];
            $data['exam_date'] = $_GET['exam_date'];
        }
        $data['page_title'] = $this->page_title;

        $data['program_list']=\DB::table('univ_program')->get();
        $data['semester_list']=\DB::table('univ_semester')->get();

        $univ_invigilators_exam = \DB::table('univ_invigilators_exam')
        ->leftJoin('univ_semester','univ_invigilators_exam.invigilators_exam_semester','=','univ_semester.semester_code')
        ->orderBy('univ_invigilators_exam.created_at','asc')->paginate(10);

        $univ_invigilators_exam->setPath(url('/register/schedule/exam-schedule'));
        $pagination = $univ_invigilators_exam->render();
        $data['pagination'] = $pagination;
        $data['univ_invigilators_exam'] = $univ_invigilators_exam;
        
        return \View::make('pages.register.register-exam-schedule',$data);
    }



     /********************************************
    ## ExamScheduleModal
    *********************************************/
     public function ExamScheduleModal($room_code, $exam_type, $exam_date, $time_slot){

        if(!empty($room_code) && !empty($exam_type) && !empty($exam_date) && !empty($time_slot)){

            $data['room_code']=$room_code;
            $data['exam_type']=$exam_type;
            $data['exam_date']=$exam_date;
            $data['time_slot']=$time_slot;
            return \View::make('pages.register.exam-schedule.ajax-exam-schedule-modal',$data);

        }

    }


     /********************************************
    ## AjaxExamCourseList
    *********************************************/

    public function AjaxExamCourseList($program_code){

        $data['page_title'] = $this->page_title;

        $course_list=\DB::table('course_basic')->where('course_program', $program_code)->get();
        $data["course_list"]=$course_list;
        return \View::make('pages.register.exam-schedule.ajax-exam-course-list',$data);
    }



    /********************************************
    ## ExamScheduleSubmit
    *********************************************/

    public function ExamScheduleSubmit(){

        $rules=array(
            'exam_schedule_program' => 'required',
            'exam_schedule_course' => 'required',
            'exam_schedule_room' => 'required',
            'exam_schedule_date' => 'required',
            'exam_schedule_time_slot' => 'required',
            );
        $v=\Validator::make(\Request::all(),$rules);


        if($v->passes()){

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

            if(!empty($univ_academic_calender)){

                $now=date('Y-m-d H:i:s');
                $exam_schedule_program=\Request::input('exam_schedule_program');
                $exam_schedule_course=\Request::input('exam_schedule_course');
                $exam_schedule_room=\Request::input('exam_schedule_room');
                $exam_schedule_date=\Request::input('exam_schedule_date');
                $exam_schedule_time_slot=\Request::input('exam_schedule_time_slot');
                $exam_schedule_type=\Request::input('exam_schedule_type');

                foreach ($exam_schedule_course as $key => $course_code) {

                    $exam_schedule_tran_code=\Uuid::generate(4);
                    $exam_schedule=array(
                        'exam_schedule_tran_code' => $exam_schedule_tran_code->string,
                        'exam_schedule_date' => $exam_schedule_date,
                        'exam_schedule_type' => $exam_schedule_type,
                        'exam_schedule_program' =>  $exam_schedule_program,
                        'exam_schedule_year' => $univ_academic_calender->academic_calender_year,
                        'exam_schedule_semester' => $univ_academic_calender->academic_calender_semester,
                        'exam_schedule_course' => $course_code,
                        'exam_schedule_room' => $exam_schedule_room,
                        'exam_schedule_time_slot' => $exam_schedule_time_slot,
                        'exam_schedule_status' => $exam_schedule_type,

                        'created_by' => \Auth::user()->user_id,
                        'updated_by' => \Auth::user()->user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );


                    try{
                        $exam_schedule_save=\DB::table('univ_exam_schedule')->insert($exam_schedule);
                        \App\System::EventLogWrite('insert,univ_exam_schedule',json_encode($exam_schedule));

                    }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/schedule/exam-schedule')->with('message',"Exam schedule not added !");
                    }

                }
                return \Redirect::back()->with('message',"Exam schedule has been added successfully !");


            }else  return \Redirect::back()->with('message',"Academic Calender Not Found !");

        }else return \Redirect::to('/register/schedule/exam-schedule')->withErrors($v->messages());

    }

    /********************************************
    ## ExamScheduleView
    *********************************************/

    public function ExamScheduleView($exam_type, $program, $trimester, $year){

        $data['page_title'] = $this->page_title;

        if(($exam_type!=0) && (!empty($program)) && ($trimester!=0) && ($year!=0)){

            $data['exam_type']=$exam_type;
            $data['trimester']=$trimester;
            $data['year']=$year;
            $data['program']=$program;
            
            if($program=="all"){

                $exam_schedule_data = \DB::table('univ_exam_schedule')
                ->where('exam_schedule_semester', $trimester)
                ->where('exam_schedule_year', $year)
                ->where('exam_schedule_type', $exam_type)
                ->select('exam_schedule_date', \DB::raw('count(*) as total'))
                ->groupBy('exam_schedule_date')
                ->get();
            }else{
                $exam_schedule_data = \DB::table('univ_exam_schedule')
                ->where('exam_schedule_semester', $trimester)
                ->where('exam_schedule_year', $year)
                ->where('exam_schedule_program', $program)
                ->where('exam_schedule_type', $exam_type)
                ->select('exam_schedule_date', \DB::raw('count(*) as total'))
                ->groupBy('exam_schedule_date')
                ->get();
            }

            $data['exam_schedule_data']=$exam_schedule_data;

            return \View::make('pages.register.exam-schedule.ajax-exam-schedule-view',$data);
        }
        
        
    }



    /********************************************
    ## ExamScheduleDownload
    *********************************************/

    public function ExamScheduleDownload(){


        if(isset($_GET['exam_type']) && ($_GET['exam_type'] !=0) && isset($_GET['program']) && (!empty($_GET['program'])) && isset($_GET['trimester']) && ($_GET['trimester'] !=0) && isset($_GET['year']) && ($_GET['year'] !=0)){

            $data['page_title']=$this->page_title;

            $exam_type=$_GET['exam_type'];
            $program=$_GET['program'];
            $trimester=$_GET['trimester'];
            $year=$_GET['year'];

            $data['exam_type']=$exam_type;
            $data['program']=$program;
            $data['trimester']=$trimester;
            $data['year']=$year;

            if($program=='all'){

                $exam_schedule_data = \DB::table('univ_exam_schedule')
                ->where('exam_schedule_semester', $trimester)
                ->where('exam_schedule_year', $year)
                ->where('exam_schedule_type', $exam_type)
                ->select('exam_schedule_date', \DB::raw('count(*) as total'))
                ->groupBy('exam_schedule_date')
                ->get();

                $program_code='All_Program';
                $data['program_title']='All Program';
            }else{
                $exam_schedule_data = \DB::table('univ_exam_schedule')
                ->where('exam_schedule_semester', $trimester)
                ->where('exam_schedule_year', $year)
                ->where('exam_schedule_program', $program)
                ->where('exam_schedule_type', $exam_type)
                ->select('exam_schedule_date', \DB::raw('count(*) as total'))
                ->groupBy('exam_schedule_date')
                ->get();

                 $univ_program=\DB::table('univ_program')->where('program_id', $program)->first();
                 $program_code=$univ_program->program_code;
                 $data['program_title']=$univ_program->program_title;
            }
            
            $data['exam_schedule_data']=$exam_schedule_data;


           
            $univ_semester=\DB::table('univ_semester')->where('semester_code', $trimester)->first();
            $data['univ_semester']=$univ_semester;


            if($exam_type=='2'){
                $exam_type_name='Trimester_Midterm_exam_schedule';
            }elseif($exam_type=='3'){
                $exam_type_name='Trimester_Final_exam_schedule';
            }


            $pdf_name = $program_code.'_'.$exam_type_name.'_'.$univ_semester->semester_title.'_'.$year.'_'.date('i_s');

            $pdf = \PDF::loadView('pages.register.exam-schedule.pdf.exam-schedule-download-pdf',$data);
            return  $pdf->stream($pdf_name.'.pdf'); 


        }else return \Redirect::to('/register/schedule/exam-schedule?tab=download_exam_schedule')->with('message',"Select Exam Schedule To Download !");

        
    }

    


    /********************************************
    ## RegisterNoticeBoardPage
    *********************************************/
    public function RegisterNoticeBoardPage(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'faculty_notice';
        $data['tab'] = $tab;
        $user=\Auth::user()->user_id;


        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['notice_to_type'])){

           $all_notice_list = \DB::table('univ_notice_board')->where(function($query){

                           if(isset($_GET['notice_to_type'])){
                                $query->where(function ($q){
                                    $q->where('notice_to_type', $_GET['notice_to_type']);
                                  });
                            }

                        })         
                        ->leftJoin('univ_program','univ_notice_board.notice_program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','univ_notice_board.notice_semester','like','univ_semester.semester_code')
                        ->select('univ_notice_board.*','univ_program.*','univ_semester.*')
                        ->orderBy('univ_notice_board.created_at','desc')->paginate(10);

            if(isset($_GET['notice_to_type']))
                $notice_to_type = $_GET['notice_to_type'];
            else $notice_to_type = null;

            $all_notice_list->setPath(url('/register/notice-board?notice_to_type='. $notice_to_type));
            $notice_pagination = $all_notice_list->render();
            $data['notice_pagination'] = $notice_pagination;
            $data['all_notice_list'] = $all_notice_list;
        }
        else{
     
            $all_notice_list=\DB::table('univ_notice_board')
                            ->leftJoin('univ_program','univ_notice_board.notice_program','like','univ_program.program_id')
                            ->leftJoin('univ_semester','univ_notice_board.notice_semester','like','univ_semester.semester_code')
                            ->select('univ_notice_board.*','univ_program.*','univ_semester.*')
                            ->where('univ_notice_board.notice_from','=',$user)
                            ->orderBy('univ_notice_board.created_at','desc')->paginate(10);
            $all_notice_list->setPath(url('/register/notice-board'));
            $notice_pagination = $all_notice_list->render();
            $data['notice_pagination'] = $notice_pagination;
            $data['all_notice_list'] = $all_notice_list;
         }

        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-notice-board',$data);
    }



    /********************************************
    ## RegisterNoticeBoardSubmit 
    *********************************************/

    public function RegisterFacultyNoticeBoardSubmit(){

                $rule = [
                    'notice_subject' => 'Required',
                    'notice_message' => 'Required',
                    'notice_to' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $uuid = \Uuid::generate(4);
            $from_type=\Auth::user()->user_type;
            $academic_calender=\DB::table('univ_academic_calender')->first();
            $now = date('Y-m-d H:i:s');
            $faculty_notice_data = [
                                'notice_tran_code' => $uuid,
                                'notice_from_type' =>$from_type,
                                'notice_from' =>\Auth::user()->user_id,
                                'notice_to_type' =>\Request::input('notice_to_type'),
                                'notice_to' =>\Request::input('notice_to'),
                                'notice_program' =>\Request::input('notice_program'),
                                'notice_semester'=>$academic_calender->academic_calender_semester,
                                'notice_year' => $academic_calender->academic_calender_year,
                                'notice_subject' =>\Request::input('notice_subject'),
                                'notice_message'=>\Request::input('notice_message'),
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                            ];
                            //var_dump('faculty_notice_data');


                \DB::table('univ_notice_board')->insert($faculty_notice_data);

                return \Redirect::to('/register/notice-board?tab=faculty_notice')->with('message','Notice has been added.');

        }else return \Redirect::to('/register/notice-board?tab=faculty_notice')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    ## RegisterNoticeBoardSubmit 
    *********************************************/

    public function RegisterStudentNoticeBoardSubmit(){


        $student_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

        if(!empty($student_calender)){

            $now = date('Y-m-d H:i:s');

            $rule = [
            'notice_subject' => 'Required',
            'notice_message' => 'Required',
            'notice_to' => 'Required',
            ];

            $v = \Validator::make(\Request::all(),$rule);

            if($v->passes()){

                $uuid = \Uuid::generate(4);
                $from_type=\Auth::user()->user_type;

                $student_notice_data = [
                'notice_tran_code' => $uuid,
                'notice_from_type' =>$from_type,
                'notice_from' =>\Auth::user()->user_id,
                'notice_to_type' =>\Request::input('notice_to_type'),
                'notice_to' =>\Request::input('notice_to'),
                'notice_program' =>\Request::input('notice_program'),
                'notice_semester'=>$student_calender->academic_calender_semester,
                'notice_year' => $student_calender->academic_calender_year,
                'notice_subject' =>\Request::input('notice_subject'),
                'notice_message'=>\Request::input('notice_message'),
                'created_at' =>$now,
                'updated_at' =>$now ,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,

                ];



                \DB::table('univ_notice_board')->insert($student_notice_data);

                return \Redirect::to('/register/notice-board')->with('message','Notice has been added.');

            }else return \Redirect::to('/register/notice-board?tab=student_notice')->withInput(\Request::all())->withErrors($v->messages());

        }else return \Redirect::to('/register/notice-board?tab=student_notice')->with('message','Academic Calender Not Set Yet !');

    }




    /*******************************************
    # EditFacultyNoticePage
    ********************************************/
    public function EditRegisterNoticePage($notice_board, $notice_tran_code){

             $edit_register_notice=\DB::table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->first();
            if(!empty($edit_register_notice)){
                $data['page_title'] = $this->page_title;
                $data['edit_register_notice']=$edit_register_notice;
                $data['form_name']=$notice_board;
                return \View::make('pages.register.register-notice-board-edit',$data);
            }else return \Redirect::to('/register/notice-board')->with('errormessage',"Invalid Notice");
        

        
    }
    



    /********************************************
    # UpdateFacultyNotice
    *********************************************/
    public function UpdateRegisterNotice($notice_tran_code){
        


            $rule = [
                'notice_subject' => 'Required',
                'notice_message' => 'Required',
            ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){    

        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
            $update_register_notice_data = [
                                'notice_program' =>\Request::input('notice_program'),
                                'notice_to' =>\Request::input('notice_to'),
                                'notice_subject' =>\Request::input('notice_subject'),
                                'notice_message'=>\Request::input('notice_message'),                               
                                'updated_at' =>$now,
                                'updated_by' =>\Auth::user()->user_id,

                            ];
                 $update_data = \DB::table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->update($update_register_notice_data);
                 return \Redirect::to('/register/notice-board');
                }else return \Redirect::to('/register/notice-board')->withInput(\Request::all())->withErrors($v->messages());


         }



    /********************************************
    ## RegisterNoticeDelete
    *********************************************/

    public function RegisterNoticeDelete($notice_tran_code){

        $notice_data=\DB::table('univ_notice_board')->where('notice_tran_code', $notice_tran_code)->delete();
        return \Redirect::to('/register/notice-board')->with('message'," Deleted Successfully!");
    }



    /********************************************
    ## StudentGradeEquivalent
    *********************************************/
    public function StudentGradeEquivalent(){

        $grade_data=\DB::table('grade_equivalent')
                        ->select('grade_equivalent.*')
                        ->orderBy('created_at','desc')
                        ->paginate(10);
        $grade_data->setPath(url('/register/student-grade-equivalent'));
        $grade_pagination = $grade_data->render();
        $data['grade_pagination'] = $grade_pagination;
        $data['page_title'] = $this->page_title;
        $data['grade_data'] =$grade_data;
        return \View::make('pages.register.register-student-grade-equivalent',$data);
    }


    /********************************************
    ## StudentGradeEquivalentSubmit 
    *********************************************/

    public function StudentGradeEquivalentSubmit(){

            $rules = [
                    'lowest_margin' => 'required|numeric',
                    'highest_margin' => 'required|numeric',
                    'grade_point' => 'required',
                    'letter_grade'  =>  'required',
                    'eqivalence'  =>'required',
                ];

            $v = \Validator::make(\Request::all(),$rules);

            if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $grade_equivalent_data = [
                                'grade_equivalent_tran_code' => $uuid,
                                'lowest_margin' =>\Request::input('lowest_margin'),
                                'highest_margin' =>\Request::input('highest_margin'),
                                'grade_point' =>\Request::input('grade_point'),
                                'letter_grade' =>\Request::input('letter_grade'),
                                'eqivalence' =>\Request::input('eqivalence'),
                                'remarks'=>\Request::input('remarks'),
                                'created_at' =>$now,
                                'updated_at' =>$now ,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,

                            ];
                try{
                    \DB::table('grade_equivalent')->insert($grade_equivalent_data);
                    \App\System::EventLogWrite('insert',json_encode($grade_equivalent_data));
                 }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                     return \Redirect::to('/register/student-grade-equivalent')->with('message','Something wrong !!');
                 }

                return \Redirect::to('/register/student-grade-equivalent')->with('message','Academic Calender has been added.');
                }else return \Redirect::to('/register/student-grade-equivalent')->withInput(\Request::all())->withErrors($v->messages());
    }  



    /********************************************
    ## StudentEditGradeEquivalent
    *********************************************/
    public function StudentEditGradeEquivalent($grade_equivalent_tran_code){

        $first_grade_data=\DB::table('grade_equivalent')->where('grade_equivalent_tran_code',$grade_equivalent_tran_code)->first();

        $data['page_title'] = $this->page_title;
        $data['first_grade_data'] =$first_grade_data;
        return \View::make('pages.register.register-edit-student-grade-equivalent',$data);
    }



    /********************************************
    ## StudentUpdateGradeEquivalent 
    *********************************************/

    public function StudentUpdateGradeEquivalent($grade_equivalent_tran_code){


            $rules = [
                    'lowest_margin' => 'required|numeric',
                    'highest_margin' => 'required|numeric',
                    'grade_point' => 'required',
                    'letter_grade'  =>  'required',
                    'eqivalence'  =>'required',
                ];

            $v = \Validator::make(\Request::all(),$rules);

            if($v->passes()){

            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $grade_equivalent_update = [
                                'lowest_margin' =>\Request::input('lowest_margin'),
                                'highest_margin' =>\Request::input('highest_margin'),
                                'grade_point' =>\Request::input('grade_point'),
                                'letter_grade' =>\Request::input('letter_grade'),
                                'eqivalence' =>\Request::input('eqivalence'),
                                'remarks'=>\Request::input('remarks'),
                                'updated_at' =>$now ,
                                'updated_by' =>\Auth::user()->user_id,
                            ];
                try{
                    \DB::table('grade_equivalent')->where('grade_equivalent_tran_code',$grade_equivalent_tran_code)->update($grade_equivalent_update);
                    \App\System::EventLogWrite('insert',json_encode($grade_equivalent_update));
                  }catch(\Exception  $e){
                     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                     \App\System::ErrorLogWrite($message);

                      return \Redirect::to('/register/student-grade-equivalent')->with('message','Something wrong !!');
                  }

                return \Redirect::to('/register/student-grade-equivalent')->with('message','Academic Calender has been added.');
                 }else return \Redirect::to('/register/student-grade-equivalent')->withInput(\Request::all())->withErrors($v->messages());
    } 


    /********************************************
    ## StudentGradeEquivalentDelete
    *********************************************/

    public function StudentGradeEquivalentDelete($grade_equivalent_tran_code){

        $grade_data_delete=\DB::table('grade_equivalent')->where('grade_equivalent_tran_code', $grade_equivalent_tran_code)->delete();
        return \Redirect::to('/register/student-grade-equivalent')->with('message'," Deleted Successfully!");
    }


    /********************************************
    ## RegisterStudentAttendanceListPage 
    *********************************************/

    public function RegisterStudentAttendanceListPage(){

        $data['page_title'] = $this->page_title;

    if(isset($_GET['program']) && isset($_GET['semster']) && isset($_GET['academic_year']) || isset($_GET['course'])){
            $course=$_GET['course'];
            $course_found=\DB::table('course_basic')->where('course_code',$course)->first();
            $course_type=$course_found->course_type;
            
        if($course_type=='Theory'){

            $all_student = \DB::table('student_class_registers')
            ->where('student_class_registers.class_result_status',0)
            ->where(function($query){

            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                $query->where(function ($q){
                    $q->where('class_program', $_GET['program']);
                });
            }
            if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                $query->where(function ($q){
                    $q->where('class_semster', $_GET['semester']);
                });
            }

            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                $query->where(function ($q){
                    $q->where('class_year', $_GET['academic_year']);
                });
            }

            if(isset($_GET['course'])){
                $query->where(function ($q){
                    $q->where('class_course_code', $_GET['course']);
                });
            }

            })
            ->leftJoin('student_basic','student_class_registers.student_tran_code','=','student_basic.student_tran_code')
            ->leftJoin('course_basic','student_class_registers.class_course_code','=','course_basic.course_code')
            ->leftJoin('univ_program','student_class_registers.class_program','like','univ_program.program_id')
            ->get();

            /**********Lab course***********/
        }
        else{
            $all_student = \DB::table('student_lab_register')
            ->where('student_lab_register.lab_result_status',0)
            ->where(function($query){

            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                $query->where(function ($q){
                    $q->where('lab_program', $_GET['program']);
                });
            }
            if(isset($_GET['semester'])&&($_GET['semester'] !=0)){
                $query->where(function ($q){
                    $q->where('lab_semster', $_GET['semester']);
                });
            }

            if(isset($_GET['academic_year'])&&($_GET['academic_year'] !=0)){
                $query->where(function ($q){
                    $q->where('lab_year', $_GET['academic_year']);
                });
            }

            if(isset($_GET['course'])){
                $query->where(function ($q){
                    $q->where('lab_course_code', $_GET['course']);
                });
            }

            })
            ->leftJoin('student_basic','student_lab_register.student_serial_no','=','student_basic.student_serial_no')
            ->leftJoin('course_basic','student_lab_register.lab_course_code','=','course_basic.course_code')
            ->leftJoin('univ_program','student_lab_register.lab_program','like','univ_program.program_id')
            ->get();

        }
        $data['all_student']=$all_student;
       }

         return \View::make('pages.register.register-student-attendance-list',$data);
    }


    /********************************************
    ## RegisterStudentAttendanceSubmit
    *********************************************/

    public function RegisterStudentAttendanceSubmit(){

     $now=date('Y-m-d H:i:s');

     $rule = [
     'attendance_date' => 'Required',
     ];

     $v = \Validator::make(\Request::all(),$rule);

     if($v->passes()){

        $student_serial_no=\Request::input('student_no');
        $course_code=\Request::input('course_code');

        if(!empty($student_serial_no)){
            $course_found=\DB::table('course_basic')->where('course_code',$course_code)->first();
            $course_type=$course_found->course_type;

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

            if(!empty($univ_academic_calender)){
                if($course_type=='Theory'){
                    foreach ($student_serial_no as $key => $stu){
                        $student_basic=\DB::table('student_basic')
                        ->where('student_basic.student_serial_no', $stu)
                        ->leftJoin('student_class_registers','student_basic.student_tran_code','like','student_class_registers.student_tran_code')
                        ->where('student_class_registers.class_course_code',$course_code)
                        ->where('class_result_status', 0)
                        ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                        ->where('class_year', $univ_academic_calender->academic_calender_year)
                        ->first(); 
                        $attendance_tran_code=\Uuid::generate(4);
                        $student_class_attendance_data=array(
                            'attendance_tran_code' => $attendance_tran_code->string,
                            'attendance_student_id' => $student_basic->student_serial_no,
                            'attendance_program' =>$student_basic->class_program,
                            'attendance_semester' =>$student_basic->class_semster,
                            'attendance_year' =>$student_basic->class_year,
                            'attendance_course_id' =>$student_basic->class_course_code,
                            'attendance_by' => $student_basic->class_faculty,
                            'attendance_date' =>\Request::input('attendance_date'),
                            'attendance_status' => 1,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->user_id,
                            'updated_by' =>\Auth::user()->user_id,
                            );

                        try{
                            $student_class_attendance_data_store=\DB::table('student_class_attendance')->insert($student_class_attendance_data);
                            \App\System::EventLogWrite('insert',json_encode($student_class_attendance_data_store));
                        }catch(\Exception  $e){
                         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                         \App\System::ErrorLogWrite($message);

                         return \Redirect::to('/register/student/attendance/list')->with('message','Something wrong !!');
                     }
                 }
             }else{ 
                foreach ($student_serial_no as $key => $student){
                    $student_basic=\DB::table('student_lab_register')
                    ->where('student_serial_no',$student)
                    ->where('student_lab_register.lab_course_code',$course_code)
                    ->where('lab_result_status', 0)
                    ->where('lab_semster', $univ_academic_calender->academic_calender_semester)
                    ->where('lab_year', $univ_academic_calender->academic_calender_year)
                    ->first(); 

                    $attendance_tran_code=\Uuid::generate(4);
                    $student_lab_attendance_data=array(
                        'attendance_tran_code' => $attendance_tran_code->string,
                        'attendance_student_id' => $student_basic->student_serial_no,
                        'attendance_program' =>$student_basic->lab_program,
                        'attendance_semester' =>$student_basic->lab_semster,
                        'attendance_year' =>$student_basic->lab_year,
                        'attendance_course_id' =>$student_basic->lab_course_code,
                        'attendance_by' => $student_basic->lab_faculty,
                        'attendance_date' =>\Request::input('attendance_date'),
                        'attendance_status' => 1,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );

                    try{
                        $student_lab_attendance_data_store=\DB::table('student_class_attendance')->insert($student_lab_attendance_data);
                        \App\System::EventLogWrite('insert',json_encode($student_lab_attendance_data_store));
                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/register/student/attendance/list')->with('message','Something wrong !!');
                    }
                }
            }return \Redirect::to('/register/student/attendance/list')->with('message',"Student attendance stored Successfully!");

        }return \Redirect::to('/register/student/attendance/list')->with('message',"Academic Calender Not Set Yet !");
    }else  return \Redirect::back()->with('message','Please Select Student ID !!');


}return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());


}


    
    /********************************************
    ## RegisterStudentCourseWithdraw
    *********************************************/

    public function RegisterStudentCourseWithdraw(){

    if(isset($_GET['student_no'])){
        $student_serial_no=$_GET['student_no'];
            $resent_data = \DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)->first();
            $present_year=$resent_data->academic_calender_year;
            $present_semester=$resent_data->academic_calender_semester;

            $all_student = \DB::table('student_academic_tabulation')
            ->where('student_academic_tabulation.tabulation_year',$present_year)
            ->where('student_academic_tabulation.tabulation_semester',$present_semester)
            ->where('student_academic_tabulation.tabulation_status',0)
            ->where('student_serial_no',$student_serial_no)
            ->leftJoin('univ_program','student_academic_tabulation.tabulation_program','=','univ_program.program_id')
            // ->select('student_academic_tabulation.*','univ_program.*')
            ->get();


         $data['all_student']=$all_student;
        
    }
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-student-course-withdraw',$data);
         
    }







    /********************************************
    ## RegisterStudentCourseWithdrawSubmit
    *********************************************/

    public function RegisterStudentCourseWithdrawSubmit(){


        $now=date('Y-m-d H:i:s');
        $student_no=\Request::input('student_no');
        $student_course_no=\Request::input('course_no');
        $percentise=\Request::input('percentise');
        $parcent = (int)$percentise;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

        $student_accounts_info=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_no)->where('payment_semster',$univ_academic_calender->academic_calender_semester)->where('payment_year',$univ_academic_calender->academic_calender_year)->where('payment_transaction_fee_type',"tution_fee")->first();
        $new_recievable=$student_accounts_info->payment_receivable;

        if(!empty($univ_academic_calender)){
            if(!empty($student_course_no)){
                $new_other=0;
                foreach ($student_course_no as $key => $course){
                $all_course_info=\DB::table('course_basic')->where('course_code',$course)->first();
                $all_accounts_info=\DB::table('student_accounts_info')->where('accounts_student_serial_no',$student_no)->first();

                if(!empty($all_accounts_info)){
                 $accounts_fee_deatails=unserialize($all_accounts_info->accounts_fee_deatails);
                }


                #-----------------for accounts------------------#
                foreach ($accounts_fee_deatails as $key => $fees) {
                    if($fees->accounts_fee_name_slug == "tution_fee"){
                       $total_tution_fee=($all_course_info->credit_hours)*($fees->accounts_fee_amount);  
                        }
                    }

                    $return_fee=(int)(($total_tution_fee*$parcent)/100);
                    // var_dump($return_fee);
                    $others_fee=$total_tution_fee-$return_fee;
                    // var_dump($others_fee);
                    $new_other=$new_other+$others_fee;
                    

                    $new_recievable=($new_recievable)-($total_tution_fee)+$others_fee;


                    $stu_no=\DB::table('student_academic_tabulation')
                        ->where('tabulation_course_id',$course)
                        ->where('student_serial_no', $student_no)
                        ->first();
                    $class_register_tran_code=$stu_no->student_tran_code;

                    $course_withdraw_data=\DB::table('student_academic_tabulation')
                                        ->where('tabulation_course_id', $course)
                                        ->where('student_serial_no', $student_no)
                                        ->delete();
                    $course_class_register_data=\DB::table('student_class_registers')
                                            ->where('class_course_code', $course)
                                            ->where('student_tran_code', $class_register_tran_code)
                                            ->delete();
                    $course_lab_register_data=\DB::table('student_lab_register')
                                            ->where('lab_course_code', $course)
                                            ->where('student_serial_no', $student_no)
                                            ->delete();


           
                 }

                    $update_fees_transaction=array(
                    'payment_receivable' => $new_recievable,
                    'payment_others'=>$new_other,
                    'updated_by' =>\Auth::user()->user_id,
                    'updated_at' =>$now,
                    );
                    var_dump($update_fees_transaction);

                    try{
                        $student_accounts_info=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_no)->where('payment_transaction_fee_type','tution_fee')->update($update_fees_transaction);
                        
                        \App\System::EventLogWrite('update',json_encode($update_fees_transaction));
                      }catch(\Exception  $e){
                         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                         \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/student/withdraw/course')->with('message','Something wrong !!');
                      }

             var_dump($new_recievable);
             var_dump($new_other);

             return \Redirect::to('/register/student/withdraw/course')->with('message',"Student course withdraw successfully!");
            }

            else return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"Please Select Student course!"); 

        }

    }




   
    
     /********************************************
    ## RegisterStudentCreditTransfer
    *********************************************/
    public function RegisterCreditTransferStudent(){


        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
        $tab = $_REQUEST['tab'];
        }else $tab = 'student_info';

        if(isset($_GET['program'])){
            $all_course = \DB::table('course_basic')
            ->where(function($query){
            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                $query->where(function ($q){
                    $q->where('course_program', $_GET['program']);
                });
            }

            })
            ->leftJoin('univ_program','course_basic.course_program','like','univ_program.program_id')
            ->get();
            $data['all_course']=$all_course;
        $tab = 'accepted_course';
        }
        $data['tab'] = $tab;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-credit-transfer-student',$data);
    }




    
    /********************************************
    ## RegisterCreditTransferStudentInfoSUbmit
    *********************************************/
    public function RegisterStudentCreditTransferSubmit(){
    $rule = [
    'first_name'=>'Required',
    'last_name'=>'Required',
    'email'=>'Required',
    'gender'=>'Required',
    'mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11|unique:student_basic,mobile',
    'religion'=>'Required',
    'place_of_birth'=>'Required',
    'academic_year'=>'Required',
    'semester'=>'Required',
    'program'=>'Required',
    'present_address_detail'=>'Required',
    'permanent_address_detail'=>'Required',
    'permanent_postal_code'=>'Required',
    'present_postal_code'=>'Required',
    'present_city'=>'Required',
    'permanent_city'=>'Required',
    'permanent_country'=>'Required',
    'present_country'=>'Required',
    'date_of_birth'=>'Required',
    'nationality'=>'Required',
    'father_name'=>'Required',
    'mother_name'=>'Required',
    'image_url'=>'Required',
    'mother_contact_mobile'=>'Required',
    'father_contact_mobile'=>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11',
    'father_occupation'=>'Required',
    'mother_occupation'=>'Required',
    'mother_contact_mobile'=>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11',
    'father_contact_mobile'=>'Required',
    'ssc_exam_type'=>'Required',
    'ssc_board_name'=>'Required',
    'ssc_roll_number'=>'Required',
    'ssc_olevel_year'=>'Required',
    'total_ssc_olevel_gpa'=>'Required',
    'hsc_exam_type'=>'Required',
    'hsc_board_name'=>'Required',
    'hsc_roll_number'=>'Required',
    'hsc_olevel_year'=>'Required',
    'total_hsc_olevel_gpa'=>'Required',
    'emergency_contact'=>'Required',
     ];

     $v = \Validator::make(\Request::all(),$rule);



     if($v->passes()){
        $student_tran_code = \Uuid::generate(4);
        $student_personal_tran_code = \Uuid::generate(4);
        $now=date('Y-m-d H:i:s');
        $year=\Request::input('academic_year');
        $semester=\Request::input('semester');
        $program=\Request::input('program');
        $image= \Request::input('image_url');
        $student_serial_no = \DB::table('student_basic')
                                ->where('student_basic.program',$program)
                                ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
                                ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
                                ->orderBy('student_basic.created_at','desc')
                                ->first();
        $program_title=$student_serial_no->program_code;
        $semester_title=$student_serial_no->semester_title;
        $last_student_serial_no =str_pad(($student_serial_no->student_serial_no), 4,0,STR_PAD_LEFT);                       
        $student_serial = $last_student_serial_no+1;
        $new_student_serial_no =$student_serial;
        $image_url = \App\Register::TransferStudentImageUrl($program_title,$year,$semester_title,$new_student_serial_no,\Request::input('image_url'));

             $credit_transfer_basic_form=array(
                'student_tran_code' => $student_tran_code->string,
                'first_name'=> strtoupper(\Request::input('first_name')),
                'middle_name' => strtoupper(\Request::input('middle_name')),
                'last_name'=> strtoupper(\Request::input('last_name')),
                'student_serial_no'=>$new_student_serial_no,
                'program'=>\Request::input('program'),
                'semester'=>\Request::input('semester'),
                'academic_year' => \Request::input('academic_year'),
                'student_image_url'=> $image_url,
                'email'=>\Request::input('email'),
                'gender' => \Request::input('gender'),
                'mobile'=>\Request::input('mobile'),
                'religion'=> \Request::input('religion'),
                'student_status'=>3,
                'admission_date'=>\Request::input('admission_date'),
                'created_by' => \Auth::user()->user_id,
                'updated_by'=> \Auth::user()->user_id,
                'created_at' => $now,
                'updated_at'=> $now,
                );
               

                try{
                     \DB::table('student_basic')->insert($credit_transfer_basic_form);
                    \App\System::EventLogWrite('insert,student_basic',json_encode($credit_transfer_basic_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }


                $student_contacts_tran_code_present = \Uuid::generate(4);
                $contact_present = array(
                        'student_contacts_tran_code' =>$student_contacts_tran_code_present->string,
                        'student_tran_code' =>$student_tran_code,
                        'contact_type' => 'present',
                        'contact_detail' =>\Request::input('present_address_detail'),
                        'postal_code' =>\Request::input('present_postal_code'),
                        'city' =>\Request::input('present_city'),
                        'country' =>\Request::input('present_country'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                    );
                    

                try{
                    \DB::table('student_contacts')->insert($contact_present);
                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_present));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }


                $student_contacts_tran_code_permanent = \Uuid::generate(4);
                $contact_permanent = array(
                        'student_contacts_tran_code' =>$student_contacts_tran_code_permanent->string,
                        'student_tran_code' =>$student_tran_code,
                        'contact_type' => 'permanent',
                        'contact_detail' =>\Request::input('permanent_address_detail'),
                        'postal_code' =>\Request::input('permanent_postal_code'),
                        'city' =>\Request::input('permanent_city'),
                        'country' =>\Request::input('permanent_country'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    

                try{
                    \DB::table('student_contacts')->insert($contact_permanent);
                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_permanent));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }


                    $credit_transfer_personal_form=array(
                        'student_personal_tran_code' =>$student_personal_tran_code,
                        'student_tran_code'=>$student_tran_code,
                        'date_of_birth'=>\Request::input('date_of_birth'),
                        'blood_group'=>\Request::input('blood_group'),
                        'place_of_birth' => \Request::input('place_of_birth'),
                        'marital_status'=> \Request::input('marital_status'),
                        'nationality'=>\Request::input('nationality'),
                        'phone'=>\Request::input('phone'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );

                try{
                    \DB::table('student_personal')->insert($credit_transfer_personal_form);
                    \App\System::EventLogWrite('insert,student_personal',json_encode($credit_transfer_personal_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }

                    $student_gurdians_tran_code_father = \Uuid::generate(4);
                    $credit_transfer_gurdian_father_form=array(
                        'student_gurdians_tran_code' =>  $student_gurdians_tran_code_father->string,
                        'student_tran_code'=>$student_tran_code,
                        'relation'=>'father',
                        'gurdian_name'=>strtoupper(\Request::input('father_name')),
                        'occupation' => \Request::input('father_occupation'),
                        'mobile'=> \Request::input('father_contact_mobile'),
                        'email'=>\Request::input('father_contact_email'),
                        'emergency_contact'=>\Request::input('emergency_contact'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );

                try{
                    \DB::table('student_gurdians')->insert($credit_transfer_gurdian_father_form);
                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($credit_transfer_gurdian_father_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }


                    $student_gurdians_tran_code_mother = \Uuid::generate(4);
                    $credit_transfer_gurdian_mother_form=array(
                        'student_gurdians_tran_code' =>  $student_gurdians_tran_code_mother->string,
                        'student_tran_code'=>$student_tran_code,
                        'relation'=>'mother',
                        'gurdian_name'=>strtoupper(\Request::input('mother_name')),
                        'occupation' => \Request::input('mother_occupation'),
                        'mobile'=> \Request::input('mother_contact_mobile'),
                        'email'=>\Request::input('mother_contact_email'),
                        'emergency_contact'=>\Request::input('emergency_contact'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    
                try{
                    \DB::table('student_gurdians')->insert($credit_transfer_gurdian_mother_form);
                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($credit_transfer_gurdian_mother_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }



                    $student_academic_tran_code_ssc = \Uuid::generate(4);
                    $credit_transfer_academic_ssc_form=array(
                        'student_qualification_tran_code' =>  $student_academic_tran_code_ssc->string,
                        'student_tran_code'=>$student_tran_code,
                        'exam_type'=>\Request::input('ssc_exam_type'),
                        'exam_group'=>\Request::input('ssc_group_name'),
                        'exam_board' => \Request::input('ssc_board_name'),
                        'result_type'=> 'passed',
                        
                        'exam_roll_number'=>\Request::input('ssc_roll_number'),
                        'passing_year'=>\Request::input('ssc_olevel_year'),
                        'result_gpa'=>\Request::input('total_ssc_olevel_gpa'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    

                try{
                    \DB::table('student_academic_qualification')->insert($credit_transfer_academic_ssc_form);
                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($credit_transfer_academic_ssc_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }



                    $student_academic_tran_code_hsc = \Uuid::generate(4);
                    $credit_transfer_academic_hsc_form=array(
                        'student_qualification_tran_code' =>  $student_academic_tran_code_hsc->string,
                        'student_tran_code'=>$student_tran_code,
                        'exam_type'=>\Request::input('hsc_exam_type'),
                        'exam_group'=>\Request::input('hsc_group_name'),
                        'exam_board' => \Request::input('hsc_board_name'),
                        'result_type'=> 'passed',
                        'exam_roll_number'=>\Request::input('hsc_roll_number'),
                        'passing_year'=>\Request::input('hsc_olevel_year'),
                        'result_gpa'=>\Request::input('total_hsc_olevel_gpa'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    

                try{
                    \DB::table('student_academic_qualification')->insert($credit_transfer_academic_hsc_form);
                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($credit_transfer_academic_hsc_form));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }



                #----student accounts info insert----#
                
                $accounts_info_tran_code=\Uuid::generate(4);
                $program_details=\DB::table('univ_program')->where('program_id',$program)->first();
                $accounts_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                 ->where('accounts_fee_payment_type','Receivable')
                ->select('accounts_fee_name_slug','accounts_fee_slug','accounts_fee_amount')
                ->get();

                $accounts_fee_deatail=serialize($accounts_fee_deatails);

                (float)$total_fees=0;
                (float)$total_tution_fee=0;

                (float)$total_credits=$program_details->program_total_credit_hours;
                $accepted_credit=\Request::input('no_of_accepted_credit');
                $total_credit=$total_credits-$accepted_credit;

                if(!empty($accounts_fee_deatails)){
                    foreach ($accounts_fee_deatails as $key => $accounts) {

                        if($accounts->accounts_fee_name_slug=='tution_fee'){
                            $total_tution_fee=(float)$accounts->accounts_fee_amount*$total_credit;
                        }
                        if($accounts->accounts_fee_name_slug!='tution_fee'){
                            $total_fees=$total_fees+(float)$accounts->accounts_fee_amount;
                        }
                    }
                }


                $accounts_total_fees=$total_tution_fee+$total_fees;

                $waiver_type = \Request::input('waiver');

                $student_accounts_info_data=array(
                    'accounts_info_tran_code' => $accounts_info_tran_code->string,
                    'accounts_student_tran_code' => $student_tran_code,
                    'accounts_student_serial_no' => $new_student_serial_no,
                    'accounts_program' => $program,
                    'program_duration_in_year' => $program_details->program_duration,
                    'no_of_semester_in_year' => 3,
                    'accounts_total_credit' => $total_credit,
                    'accounts_fee_deatails' => $accounts_fee_deatail,
                    'waiver_type' => $waiver_type,
                    'accounts_total_fees' => $accounts_total_fees,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );

                try{
                    $student_accounts_info=\DB::table('student_accounts_info')->insert($student_accounts_info_data);
                    \App\System::EventLogWrite('insert,student_accounts_info',json_encode($student_accounts_info_data));

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }


                return \Redirect::to('/register/student/credit/transfer')->with('message',"Student registration Successfull.Student ID: {$new_student_serial_no}");
            }return \Redirect::to('/register/student/credit/transfer')->withInput(\Request::all())->withErrors($v->messages());

}



    /********************************************
    ## RegisterTransferStudentAcceptedCourse
    *********************************************/

    public function RegisterTransferStudentAcceptedCourse(){


     $now=date('Y-m-d H:i:s');

     $rule = [
     'student_no'=>'Required',
     ];

     $v = \Validator::make(\Request::all(),$rule);

     if($v->passes()){
   
                $student_serial_no=\Request::input('student_no');
                $course_code=\Request::input('course_no');

        if(!empty($course_code)){


            foreach ($course_code as $key => $credit){
                $tabulation_point=\Request::input('tabulation_grade_point_'.$credit);
                $tabulation_earn_grade=\Request::input('tabulation_grade_'.$credit);

                 if(($tabulation_point==null) || ($tabulation_earn_grade==null)){
                    return \Redirect::back()->with('errormessage','Grade and Grade point required  !!');


                 }

            }
           
            foreach ($course_code as $key => $student){
            $student_basic=\DB::table('course_basic')
                            ->leftJoin('student_basic','course_basic.course_program','like','student_basic.program')
                            ->where('course_basic.course_code',$student)
                            ->first(); 
                $tabulation_grade_point=\Request::input('tabulation_grade_point_'.$student);
                $tabulation_grade=\Request::input('tabulation_grade_'.$student);
            
                    $tabulation_tran_code=\Uuid::generate(4);
                    $student_accepted_course_data=array(
                        'tabulation_tran_code' => $tabulation_tran_code->string,
                        'student_tran_code'=>$student_basic->student_tran_code,
                        'student_serial_no' => $student_serial_no,
                        'tabulation_program' =>$student_basic->course_program,
                        'tabulation_semester' =>$student_basic->semester,
                        'tabulation_year' =>$student_basic->academic_year,
                        'tabulation_level' =>$student_basic->level,
                        'tabulation_term' => $student_basic->term,
                        'tabulation_course_id' =>$student_basic->course_code,
                        'tabulation_course_title' =>$student_basic->course_title,
                        'tabulation_course_type' =>$student_basic->course_type,
                        'tabulatation_credit_hours' =>$student_basic->credit_hours,
                        'tabulation_credit_earned' =>$student_basic->credit_hours,
                        'tabulation_grade_point' =>$tabulation_grade_point,
                        'tabulation_grade' =>$tabulation_grade,
                        'tabulation_status' => 1,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    
                    $student_basic_update_data=array(
                        'student_status' =>1,
                        'updated_at' =>$now,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                  
                try{
                    \DB::table('student_academic_tabulation')->insert($student_accepted_course_data);
                    \DB::table('student_basic')->where('student_serial_no',$student_serial_no)->update($student_basic_update_data);
                    \App\System::EventLogWrite('insert,student_academic_tabulation',json_encode($student_accepted_course_data));
                    \App\System::EventLogWrite('update,student_basic',json_encode($student_basic_update_data));
                  }catch(\Exception  $e){
                     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                     \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/student/credit/transfer')->with('message','Something wrong !!');
                  }

            }return \Redirect::back()->with('message',"Course accepted  Successfully !!!");

        }else return \Redirect::back()->with('message',"Please Select Course!");
    }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

}




    /********************************************
    ## RegisterTransferStudentAddmissionPayment
    *********************************************/

    public function RegisterTransferStudentAddmissionPayment(){

        $now=date('Y-m-d H:i:s');
        $rule = [
        'transaction_slip_no'=>'Required',
        'transaction_fees_amount'=>'Required',
        'transaction_student_serial_no'=>'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

         
            $student_serial_no=\Request::input('transaction_student_serial_no');
            $student_basic=\DB::table('student_basic')
            ->where('student_serial_no',$student_serial_no)
            ->where('student_status',3)
            ->first(); 

            $transaction_tran_code=\Uuid::generate(4);
            $transfer_student_payment_data=array(
                'transaction_tran_code'=>$transaction_tran_code->string,
                'transaction_student_tran_code'=>$student_basic->student_tran_code,
                'transaction_student_serial_no'=>\Request::input('transaction_student_serial_no'),
                'transaction_program'=>$student_basic->program,
                'transaction_semster'=>$student_basic->semester,
                'transaction_year'=>$student_basic->academic_year,
                'transaction_fees_type'=>'admission_fee',
                'transaction_payment_types'=>10000,
                'transaction_slip_no'=>\Request::input('transaction_slip_no'),
                'transaction_receive_types'=>\Request::input('transaction_receive_types'),
                'transaction_fees_amount'=>\Request::input('transaction_fees_amount'),
                'transaction_history_remarks'=>'Admission Fee',
                'created_at' =>$now,
                'updated_at' =>$now,
                'created_by' =>\Auth::user()->user_id,
                'updated_by' =>\Auth::user()->user_id,
                );

$student_basic_update_status=array(
    'student_status'=>2,
    );
\DB::table('student_basic')->where('student_serial_no',$student_basic->student_serial_no)->update($student_basic_update_status);
try{
    \DB::table('accounts_transaction_history')->insert($transfer_student_payment_data);
    
    \App\System::EventLogWrite('insert',json_encode($transfer_student_payment_data));
                    // \App\System::EventLogWrite('update',json_encode($student_basic_update_status));
}catch(\Exception  $e){
   $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
   \App\System::ErrorLogWrite($message);

   return \Redirect::to('/register/student/credit/transfer')->with('message','Something wrong !!');
}

return \Redirect::to('/register/student/credit/transfer')->with('message',"Student addmission  payment  Successfully!");
}return \Redirect::to('/register/student/credit/transfer')->withInput(\Request::all())->withErrors($v->messages());

}



    /********************************************
    ## TransferStudentImageUpload
    *********************************************/
    public function TransferStudentImageUpload(){

        $maxwidth = 455;
        $maxheight = 560;

        $file = \Request::file('image');  

        $input = array('image' => $file);

        $rules = array(
            'image' => 'image|max:100'
        );

        $validator = \Validator::make($input, $rules);
        $sizevalidator = \App\Register::CurrectImageSize($maxwidth,$maxheight,$file); 

        if($validator->fails())
        {
            return \Response::json(['success' => 'invalid_format']);
        }
        else if(! $sizevalidator) {

            return \Response::json(['success' => 'filesize']);
        }else {

            $destinationPath = 'STUDENT/TRANSFER/';
            $filename = time()."-".$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            return \Response::json(['success' => true, 'file' => ($destinationPath.$filename)]);
        }
    }



    #-----------Exam Invigilator Module------------#

    /********************************************
    ## Exam Invigilators Submit
    *********************************************/
    public function ExamInvigilatorsSubmit(){
        
    $now=date('Y-m-d H:i:s');
    $rule = [
     'invigilators_exam_date'=>'Required',
     'invigilators_exam_type'=>'Required',
     'invigilators_exam_room'=>'Required',
     'invigilators_ID'=>'Required',
     'invigilators_exam_year'=>'Required',
     'invigilators_exam_semester'=>'Required',
     'invigilators_exam_time_slot'=>'Required',
    ];

    $v = \Validator::make(\Request::all(),$rule);

    if($v->passes()){
 
            if(!empty($_POST['invigilators_ID'])){
                $faculties = implode(',', $_POST['invigilators_ID']);
            }else $faculties='';

                    $invigilators_exam_tran_code=\Uuid::generate(4);
                    $invigilators_exam_data=array(
                        'invigilators_exam_tran_code'=>$invigilators_exam_tran_code->string,
                        'invigilators_exam_date'=>\Request::input('invigilators_exam_date'),
                        'invigilators_exam_type'=>\Request::input('invigilators_exam_type'),
                        'invigilators_ID'=>$faculties,
                        'invigilators_exam_year'=>\Request::input('invigilators_exam_year'),
                        'invigilators_exam_semester'=>\Request::input('invigilators_exam_semester'),
                        'invigilators_exam_room'=>\Request::input('invigilators_exam_room'),
                        'invigilators_exam_time_slot'=>\Request::input('invigilators_exam_time_slot'),
                        'invigilators_exam_status'=>1,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    var_dump($invigilators_exam_data);

                try{
                    \DB::table('univ_invigilators_exam')->insert($invigilators_exam_data);
                    
                    \App\System::EventLogWrite('insert',json_encode($invigilators_exam_data));
                  }catch(\Exception  $e){
                     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                     \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Something wrong !!');
                  }
                
                return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message',"Invigilators  Added Successfully!");
            }return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    ## TimeSlotListAjax
    *********************************************/
    public function TimeSlotListAjax($invigilators_exam_type){
        $time_slot_list = \DB::table('univ_time_slot')->where('univ_time_slot_for',$invigilators_exam_type)->get();
        $data['time_slot_list']= $time_slot_list;
        return \View::make('pages.register.ajax-time-slot-list',$data);
    }



    /********************************************
    ## InvigilatorsDelete 
    *********************************************/

    public function InvigilatorsDelete($type_slug){

            try{
                $course_categoty_delete = \DB::table('univ_invigilators_exam')->where('invigilators_exam_tran_code',$type_slug)->delete();
                \App\System::EventLogWrite('delete','deleted ID: '.$type_slug);

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }

               
         return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Invigilator Deleted Successfully !!!');
    }



    /********************************************
    ## ExamInvigilatorEditRequest 
    *********************************************/

    public function ExamInvigilatorEditRequest($type_slug){
        $data['page_title'] = $this->page_title;
        $invigilator_edit_info = \DB::table('univ_invigilators_exam')
        ->where('univ_invigilators_exam.invigilators_exam_tran_code',$type_slug)
        ->leftJoin('univ_semester','univ_invigilators_exam.invigilators_exam_semester','=','univ_semester.semester_code')
        ->first();
        $data['invigilator_edit_info']= $invigilator_edit_info;
        return \View::make('pages.register.register-exam-invigilators-edit',$data);

        
    }





    /********************************************
    ## ExamInvigilatorsUpdate 
    *********************************************/

    public function ExamInvigilatorsUpdate($type_slug){

        $now = date('Y-m-d H:i:s');
        $rule = [
         'invigilators_exam_date'=>'Required',
         'invigilators_exam_type'=>'Required',
         'invigilators_exam_room'=>'Required',
         'invigilators_ID'=>'Required',
         'invigilators_exam_year'=>'Required',
         'invigilators_exam_semester'=>'Required',
         'invigilators_exam_time_slot'=>'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            if(!empty($_POST['invigilators_ID'])){
                $faculties = implode(',', $_POST['invigilators_ID']);
            }else $faculties='';

                    $invigilators_update_data=array(
                        'invigilators_exam_date'=>\Request::input('invigilators_exam_date'),
                        'invigilators_exam_type'=>\Request::input('invigilators_exam_type'),
                        'invigilators_ID'=>$faculties,
                        'invigilators_exam_year'=>\Request::input('invigilators_exam_year'),
                        'invigilators_exam_semester'=>\Request::input('invigilators_exam_semester'),
                        'invigilators_exam_room'=>\Request::input('invigilators_exam_room'),
                        'invigilators_exam_time_slot'=>\Request::input('invigilators_exam_time_slot'),
                        'updated_at' =>$now,
                        'updated_by' =>\Auth::user()->user_id,
                        );

            try{

                \DB::table('univ_invigilators_exam')->where('invigilators_exam_tran_code',$type_slug)->update($invigilators_update_data);
                \App\System::EventLogWrite('update',json_encode($invigilators_update_data));
            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }
        return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Invigilator Update Successfully !!!');

        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    ## ExamInvigilatorsDownload 
    *********************************************/

    public function ExamInvigilatorsDownload(){
        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->first();

        if(!empty($univ_academic_calender)){

           $invigilators_mid=\DB::table('univ_invigilators_exam')
           ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
           ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
           ->where('invigilators_exam_type', 2)
           ->get();
           $data['invigilators_mid']=$invigilators_mid;

           $invigilators_final=\DB::table('univ_invigilators_exam')
           ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
           ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
           ->where('invigilators_exam_type', 3)
           ->get();
           $data['invigilators_final']=$invigilators_final;

           $data['univ_academic_calender']=$univ_academic_calender;

           $pdf_name='Exam_Invigilators_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.date('i_s');

           $pdf = \PDF::loadView('pages.register.exam-schedule.pdf.exam-invigilators-download-pdf',$data);
           return  $pdf->stream($pdf_name.'.pdf');

       }


   }


    /*----------------------------------------------------------------------------------*/
}
