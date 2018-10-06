<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\System;
use Carbon;
use Exception;
use DB;


/*******************************
#
## Faculty Controller
#
*******************************/

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
       
    }


    /********************************************
    ## FacultyDashboard 
    *********************************************/

    public function FacultyDashboard(){

       $data['page_title'] = $this->page_title;

      $faculty_profile=\DB::table('faculty_basic')
      ->where('faculty_id',\Auth::user()->user_id)
      ->leftJoin('univ_program','faculty_basic.program','like','univ_program.program_id')
      ->leftJoin('univ_department','univ_program.program_department_no','like','univ_department.department_no')
      ->leftJoin('faculty_contacts','faculty_basic.faculty_tran_code','like','faculty_contacts.faculty_tran_code')
      ->select('faculty_basic.*','univ_program.*','univ_department.*','faculty_contacts.*')
      ->first();
      $data['faculty_profile']=$faculty_profile;
      return \View::make('pages.faculty.faculty-dashboard',$data);
    }


    ########################
    #Faculty Profile Edit
    ########################
    public function FacultyEditProfile(){

        $data['page_title'] = $this->page_title;

        $faculty_profile_edit=\DB::table('faculty_basic')
        ->where('faculty_id',\Auth::user()->user_id)
        ->leftJoin('faculty_contacts','faculty_basic.faculty_tran_code','=','faculty_contacts.faculty_tran_code')
        ->leftJoin('univ_program','faculty_basic.program','=','univ_program.program_id')
        ->leftJoin('univ_department','univ_program.program_department_no','like','univ_department.department_no')
        ->select('faculty_basic.*','univ_program.*','univ_department.*','faculty_contacts.*')
        ->first();
        $data['faculty_profile_edit']=$faculty_profile_edit;

        return \View::make('pages.faculty.faculty-edit-profile',$data);
    }


    ########################
    #Faculty Basic Profile Update
    ########################
    public function FacultyUpdateBasicProfile($faculty_id){

      $rule = [
        'email' => 'Required',
        'mobile' => 'Required',
      ];

      $v = \Validator::make(\Request::all(),$rule);

      if($v->passes()){ 
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $update_faculty_basic_data = [

          'email'=>\Request::input('email'),
          'mobile' => \Request::input('mobile'),
          'updated_at' =>$now,
          'updated_by' =>\Auth::user()->user_id,   

        ];

        try{

            $success = \DB::transaction(function () use ($update_faculty_basic_data, $faculty_id) {

                for($i=0; $i<count($this->dbList); $i++){
                  $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $update_basic_data = \DB::connection($this->dbList[$i])->table('faculty_basic')->where('faculty_id',$faculty_id)->update($update_faculty_basic_data);

                    if(!$update_basic_data){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();
                    \App\System::EventLogWrite('update,faculty_basic',json_encode($update_faculty_basic_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

          return \Redirect::back()->with('message','Faculty Info Has Been Updated Successfully !');

        }catch(\Exception $e){
          $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
          \App\System::ErrorLogWrite($message);

          return \Redirect::back()->with('message','Info Not Updated !');

        }

        
      }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

    }



    ########################
    #Faculty Basic Profile Update
    ########################
    public function FacultyUpdateContractProfile($faculty_tran_code){


      $rule = [
      'country' => 'Required',
      'city' => 'Required',
      'postal_code' => 'Required',
      'contact_detail' => 'Required',
      ];

      $v = \Validator::make(\Request::all(),$rule);

      if($v->passes()){ 

        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $update_faculty_contract_data = [

                        'country' =>\Request::input('country'),
                        'city' =>\Request::input('city'),
                        'postal_code' =>\Request::input('postal_code'),
                        'contact_detail' =>\Request::input('contact_detail'),  
                        'updated_at' =>$now,
                        'updated_by' =>\Auth::user()->user_id,
                        ];


        try{

            $success = \DB::transaction(function () use ($update_faculty_contract_data, $faculty_tran_code) {

                for($i=0; $i<count($this->dbList); $i++){
                  $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $update_contract_data = \DB::connection($this->dbList[$i])->table('faculty_contacts')->where('faculty_tran_code',$faculty_tran_code)->update($update_faculty_contract_data);

                    if(!$update_contract_data){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();
                    \App\System::EventLogWrite('update,faculty_contacts',json_encode($update_faculty_contract_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

        }catch(\Exception  $e){
          $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
          \App\System::ErrorLogWrite($message);

          return \Redirect::back()->with('message','Info Not Updated !');
        }

        return \Redirect::back()->with('message','Faculty Contract Info Has Been Updated Successfully !');
      }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
    }




    /********************************************
    ## FacultyClassSchedule
    *********************************************/

    public function FacultyClassSchedule(){
        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();
        $data['univ_academic_calender']=$univ_academic_calender;

        $data['page_title'] = $this->page_title;
        return \View::make('pages.faculty.faculty-class-schedule',$data);
    }


    /********************************************
    ## FacultyClassSchedulePdfDownload
    *********************************************/

    public function FacultyClassSchedulePdfDownload(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();

        if(!empty($univ_academic_calender)){
            $pdf_name=\Auth::user()->user_id.'_Class_Schedule_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.date('i_s');

            $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)
            ->leftjoin('univ_program','univ_program.program_id','=','faculty_basic.program')
            ->first();
            $data['faculty_basic']=$faculty_basic;

            $data['univ_academic_calender']=$univ_academic_calender;

            $pdf = \PDF::loadView('pages.faculty.pdf.faculty-class-schedule-pdf-download',$data);
            return  $pdf->stream($pdf_name.'.pdf'); 

        }else return \Redirect::back()->with('message',"Academic Calender Not Set Yet !");
    }


    /********************************************
    ## FacultyAssignedCourses
    *********************************************/

    public function FacultyAssignedCourses(){

        $data['page_title'] = $this->page_title;

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();

      if(!empty($univ_academic_calender)){

        $faculty_assingned_course=\DB::table('faculty_assingned_course')
        ->where('assigned_course_faculties', \Auth::user()->user_id)
        ->where('assigned_course_semester', $univ_academic_calender->academic_calender_semester)
        ->where('assigned_course_year', $univ_academic_calender->academic_calender_year)
        ->leftjoin('univ_program','univ_program.program_id','=','faculty_assingned_course.assigned_course_program')
        ->leftjoin('univ_semester','univ_semester.semester_code','=','faculty_assingned_course.assigned_course_semester')
        ->get();

        $data['faculty_assingned_course']=$faculty_assingned_course;
        $data['univ_academic_calender']=$univ_academic_calender;

      }

        return \View::make('pages.faculty.faculty-assigned-courses',$data);
    }


    /********************************************
    ## FacultyCourseAdvising
    *********************************************/

    public function FacultyCourseAdvising(){

      $data['page_title'] = $this->page_title;

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->orderBy('univ_academic_calender.created_at','desc')->first();

      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)->first();

      if(!empty($univ_academic_calender) && !empty($faculty_basic)){

        $data['assigned_program_cordinator']=\DB::table('program_coordinator_assigned')
                ->where('coordinator_faculty_id',\Auth::user()->user_id)
                ->where('program_coordinator_semester', $univ_academic_calender->academic_calender_semester)
                ->where('program_coordinator_year', $univ_academic_calender->academic_calender_year)
                ->leftJoin('univ_program','univ_program.program_id','=','program_coordinator_assigned.coordinator_program')
                ->leftJoin('univ_semester','univ_semester.semester_code','=','program_coordinator_assigned.program_coordinator_semester')
                ->select('program_coordinator_assigned.*','univ_program.*','univ_semester.*')
                ->get();

        return \View::make('pages.faculty.faculty-course-advising',$data);


      }else return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
      

    }



    /********************************************
    ## FacultyPreAdvisingConfirm
    *********************************************/

    public function FacultyPreAdvisingLists($program_id,$level,$term,$semester,$year){

        $data['page_title'] = $this->page_title;

        $pre_advised_students=\DB::table('temp_preadvising')->where('temp_preadvising_program',$program_id)
        ->where('temp_preadvising_level',$level)
        ->where('temp_preadvising_term',$term)
        ->where('temp_preadvising_year',$year)
        ->where('temp_preadvising_semester',$semester)
        ->leftJoin('student_basic','student_basic.student_tran_code','=','temp_preadvising.student_tran_code')
        ->leftJoin('univ_program','univ_program.program_id','=','temp_preadvising.temp_preadvising_program')
        ->get();

        $data['pre_advised_students'] = $pre_advised_students;

        return \View::make('pages.faculty.ajx-faculty-pre-advising-list',$data);
    }



    /********************************************
    ## FacultyPreAdvisingModal
    *********************************************/

    public function FacultyPreAdvisingModal($temp_tran_code){

        $data['page_title'] = $this->page_title;

        $taken_courses=\DB::table('temp_preadvising')->where('temp_preadvising_tran_code',$temp_tran_code)
        ->first();

         $data['students_info']=\DB::table('student_basic')->where('student_tran_code',$taken_courses->student_tran_code)
        ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
        ->first();

        $data['taken_courses']=$taken_courses;
        $data['temp_tran_code']=$temp_tran_code;

        return \View::make('pages.faculty.ajax-faculty-pre-advising-modal',$data);
    }



    /********************************************
    ## FacultyPreAdvisingSubmit
    *********************************************/

    public function FacultyPreAdvisingSubmit(){

      $now = date('Y-m-d H:i:s');
      $temp_tran_code=\Request::input('temp_tran_code');

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();
      $temp_preadvising=\DB::table('temp_preadvising')->where('temp_preadvising_tran_code',$temp_tran_code)->first();


      if(!empty($univ_academic_calender) && !empty($temp_preadvising)){

        $pre_advising_status=\Request::input('pre_advising_status');
        // $total_credit=\Request::input('total_credit');

        $coordinator=\DB::table('program_coordinator_assigned')
        ->where('coordinator_program',$temp_preadvising->temp_preadvising_program)
        ->where('program_coordinator_level', $temp_preadvising->temp_preadvising_level)
        ->where('program_coordinator_term', $temp_preadvising->temp_preadvising_term)
        ->where('program_coordinator_semester', $temp_preadvising->temp_preadvising_semester)
        ->where('program_coordinator_year', $temp_preadvising->temp_preadvising_year)
        ->first();


        $class_register_course_code=\Request::input('temp_course_code');

        if(!empty($class_register_course_code)){

            try{

                $total_credit=0;
                foreach ($class_register_course_code as $key => $course) {

                  $course_basic_table=\DB::table('course_basic')
                  ->where('course_program',$temp_preadvising->temp_preadvising_program)
                  ->where('course_code',$course)
                  ->first();


                  $student_accounts_info_table=\DB::table('student_accounts_info')
                    ->where('accounts_student_serial_no',\Auth::user()->user_id)
                    ->first();

                  if(!empty($student_accounts_info_table)){
                      $accounts_tution_fee=unserialize($student_accounts_info_table->accounts_fee_deatails);
                      $temp_per_credit_fees_amount=(int)\App\Student::PerCreditFee($accounts_tution_fee, 'tution_fee');
                      $temp_total_credit_fees_amount=$temp_per_credit_fees_amount*($course_info->credit_hours);
                  
                  }else{
                      $temp_per_credit_fees_amount=0;
                      $temp_total_credit_fees_amount=0;
                  }


                  #--------temp preadvising update-------#
                    $temp_course_update_data=array(
                      'temp_course_code' => $course_basic_table->course_code,         
                      'temp_course_title' => $course_basic_table->course_title,
                      'temp_course_type' => $course_basic_table->course_type,
                      'temp_credit_hours' => $course_basic_table->credit_hours,
                      'temp_per_credit_fees_amount' => $temp_per_credit_fees_amount,
                      'temp_total_credit_fees_amount' => $temp_total_credit_fees_amount,
                      );

                    $Update_temp_preadvising_detail[] = $temp_course_update_data;
                    $total_credit=$total_credit+$course_basic_table->credit_hours;
                }


                $temp_preadvising_detail = serialize($Update_temp_preadvising_detail);
                $update_temp_preadvising_data=array(
                  'temp_preadvising_total_credit' => $total_credit,
                  'temp_preadvising_status' => $pre_advising_status,
                  'temp_preadvising_approved_by_faculty' => \Auth::user()->user_id,
                  'temp_preadvising_detail' => $temp_preadvising_detail,
                  'updated_by' => \Auth::user()->user_id,
                  'updated_at' => $now,
                  );



                #--------student study level update-------#
                $study_level_preadvising_status_update=array(
                  'pre_advising_status' => $pre_advising_status,
                  'updated_by' => \Auth::user()->user_id,
                  'updated_at' => $now,
                  );


              
                $success = \DB::transaction(function () use ($update_temp_preadvising_data,$study_level_preadvising_status_update, $temp_preadvising) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $temp_preadvising_update_save=\DB::connection($this->dbList[$i])->table('temp_preadvising')
                                                    ->where('temp_preadvising_tran_code',$temp_preadvising->temp_preadvising_tran_code)
                                                    ->update($update_temp_preadvising_data);

                        $student_study_level_save=\DB::connection($this->dbList[$i])->table('student_study_level')
                                            ->where('student_tran_code',$temp_preadvising->student_tran_code)
                                            ->where('study_level_semester',$temp_preadvising->temp_preadvising_semester)
                                            ->where('study_level_year',$temp_preadvising->temp_preadvising_year)
                                            ->update($study_level_preadvising_status_update);

                        if((!$temp_preadvising_update_save) || (!$student_study_level_save)){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,temp_preadvising',json_encode($update_temp_preadvising_data));
                        \App\System::EventLogWrite('update,student_study_level',json_encode($study_level_preadvising_status_update));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


          return \Redirect::to('/faculty/course-advising')->with('message',"Student Advising Confirmed Successfully !");

        }else return \Redirect::to('/faculty/course-advising')->with('message',"Select Courses for Advise !");

      }else return \Redirect::to('/faculty/course-advising')->with('message',"Something went wrong !");
     

    }



    /********************************************
    ## FacultyResultProcessing
    *********************************************/

    public function FacultyResultProcessing(){

      $data['page_title'] = $this->page_title;

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)->first();

      if(!empty($univ_academic_calender) && !empty($faculty_basic)){

        $all_courses = \DB::table('faculty_assingned_course')->where('assigned_course_faculties', \Auth::user()->user_id)
        ->where('assigned_course_semester', $univ_academic_calender->academic_calender_semester)
        ->where('assigned_course_year', $univ_academic_calender->academic_calender_year)
        ->leftJoin('univ_program','univ_program.program_id','=','faculty_assingned_course.assigned_course_program')
        ->leftJoin('course_basic','course_basic.course_code','=','faculty_assingned_course.assigned_course_id')
        ->select('faculty_assingned_course.*','univ_program.*','course_basic.*')
        ->get();
        $data['all_courses']=$all_courses;

        return \View::make('pages.faculty.faculty-result-processing',$data);

      }else{
        return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
      }


    }


    /********************************************
    ## FacultyResultProcessingMarksSubmit
    *********************************************/

    public function FacultyResultProcessingMarksSubmit($program, $course_code, $semester, $year){

        $data['course_type']=\DB::table('course_basic')->where('course_code', $course_code)->where('course_program', $program)->first();


        $data['class_course_info'] =\DB::table('student_class_registers')
        ->where('class_course_code',$course_code)
        ->where('class_program',$program)
        ->where('class_semster',$semester)
        ->where('class_year',$year)
        ->leftJoin('course_basic','course_basic.course_code','=','student_class_registers.class_course_code')
        ->leftJoin('univ_program','univ_program.program_id','=','student_class_registers.class_program')
        ->leftJoin('univ_semester','univ_semester.semester_code','=','student_class_registers.class_semster')
        ->select('student_class_registers.*','course_basic.*','univ_program.*','univ_semester.*')
        ->first();

        $data['lab_course_info'] =\DB::table('student_lab_register')
        ->where('lab_course_code',$course_code)
        ->where('lab_program',$program)
        ->where('lab_semster',$semester)
        ->where('lab_year',$year)
        ->leftJoin('course_basic','course_basic.course_code','=','student_lab_register.lab_course_code')
        ->leftJoin('univ_program','univ_program.program_id','=','student_lab_register.lab_program')
        ->leftJoin('univ_semester','univ_semester.semester_code','=','student_lab_register.lab_semster')
        ->select('student_lab_register.*','course_basic.*','univ_program.*','univ_semester.*')
        ->first();



        $student_class_registers=\DB::table('student_class_registers')
        ->where('class_program',$program)
        ->where('class_course_code',$course_code)
        ->where('class_semster',$semester)
        ->where('class_year',$year)
        ->where('class_result_status',0)
        ->leftJoin('student_basic','student_basic.student_tran_code','=','student_class_registers.student_tran_code')
        ->select('student_basic.*','student_class_registers.*')
        ->get();
        $data['student_class_registers']=$student_class_registers;


        $student_lab_register=\DB::table('student_lab_register')
        ->where('lab_program',$program)
        ->where('lab_semster',$semester)
        ->where('lab_year',$year)
        ->where('lab_result_status',0)
        ->leftJoin('student_basic','student_basic.student_tran_code','=','student_lab_register.student_tran_code')
        ->select('student_basic.*','student_lab_register.*')
        ->get();
        $data['student_lab_register']=$student_lab_register;

        

        return \View::make('pages.faculty.ajax-faculty-result-processing-search',$data);
    }



     /********************************************
    ## FacultyResultProcessingClassTestStore
    *********************************************/

    public function FacultyResultProcessingClassTestStore($student_serial_no, $program, $semester, $year, $course_code, $ct_1, $ct_2, $ct_3, $ct_4){

      $now=date('Y-m-d H:i:s');

      $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();


          if($ct_1=='101'){
            $ct_1=NULL;
          }
          if($ct_2=='101'){
            $ct_2=NULL;
          }
          if($ct_3=='101'){
            $ct_3=NULL;
          }
          if($ct_4=='101'){
            $ct_4=NULL;
          }


          $count=0;
          $class_quiz_total=0;
          if($ct_1>=0){
             $count=$count+1;
             $class_quiz_total=$class_quiz_total+$ct_1;
          }
          if($ct_2>=0){
             $count=$count+1;
             $class_quiz_total=$class_quiz_total+$ct_2;
          }
          if($ct_3>=0){
             $count=$count+1;
             $class_quiz_total=$class_quiz_total+$ct_3;
          }
          if($ct_4>=0){
             $count=$count+1;
             $class_quiz_total=$class_quiz_total+$ct_4;
          }

          if(!empty($count) && !empty($class_quiz_total)){
             $class_quiz_avg_total=($class_quiz_total)/($count);
         }else{
            $class_quiz_avg_total=NULL;
         }
         

        if(($ct_1 >10) || ($ct_2 >10) || ($ct_3 >10) || ($ct_4 >10)){
          return 0;
        }

      $class_test_data=array(
        'class_quiz_1' => $ct_1,
        'class_quiz_2' => $ct_2,
        'class_quiz_3' => $ct_3,
        'class_quiz_4' => $ct_4,
        'class_quiz_avg_total' => $class_quiz_avg_total,
        'updated_at' => $now,

        );


            try{
              
                $success = \DB::transaction(function () use ($class_test_data, $student_basic, $program, $semester, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $class_test_store=\DB::connection($this->dbList[$i])->table('student_class_registers')->where('student_tran_code',$student_basic->student_tran_code)->where('class_program', $program)->where('class_semster', $semester)->where('class_year', $year)->where('class_course_code',$course_code)->where('class_result_status',0)->update( $class_test_data);


                        if((!$class_test_store)){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_class_registers',json_encode($class_test_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


      return 1;
     
    }


     /********************************************
    ## FacultyResultProcessingMidTermStore
    *********************************************/

    public function FacultyResultProcessingMidTermStore($student_serial_no, $program, $semester, $year, $course_code, $mid_term,$mid_term_outof){

      $now=date('Y-m-d H:i:s');

      $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();


            if($mid_term=='101'){
                $mid_term=NULL;
            }if($mid_term_outof=='101'){
                $mid_term_outof='20';
            }

            if(!empty($mid_term)){
                $class_mid_term_avg_total=(($mid_term)*'20')/($mid_term_outof);
            }elseif($mid_term=='0'){
                $class_mid_term_avg_total='0';
            }else{
                $class_mid_term_avg_total=NULL;
            }

            if(($class_mid_term_avg_total >20)){
              return 0;
            }

        $mid_term_data=array(
            'class_mid_term_exam' => $mid_term,
            'class_mid_term_avg_total' => $class_mid_term_avg_total,
            'updated_at' => $now,
            );


            try{
              
                $success = \DB::transaction(function () use ($mid_term_data, $student_basic, $program, $semester, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $mid_term_store=\DB::connection($this->dbList[$i])->table('student_class_registers')->where('student_tran_code',$student_basic->student_tran_code)->where('class_program',$program)->where('class_semster', $semester)->where('class_year', $year)->where('class_course_code',$course_code)->where('class_result_status',0)->update( $mid_term_data);

                        if((!$mid_term_store)){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_class_registers',json_encode($mid_term_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }

        return 1;

    }


    /********************************************
    ## FacultyResultProcessingFinalExamStore
    *********************************************/

    public function FacultyResultProcessingFinalExamStore($student_serial_no, $program, $semester, $year, $course_code, $class_attendance, $class_participation, $class_presentaion, $class_final_exam, $final_outof){

      $now=date('Y-m-d H:i:s');

      $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();

      $class_register=\DB::table('student_class_registers')->where('student_tran_code',$student_basic->student_tran_code)->where('class_program',$program)->where('class_semster',$semester)->where('class_year',$year)->where('class_course_code',$course_code)->first();


            if($class_attendance=='101'){
                $class_attendance=NULL;
            }if($class_participation=='101'){
                $class_participation=NULL;
            }if($class_presentaion=='101'){
                $class_presentaion=NULL;
            }
            if($class_final_exam=='101'){
                $class_final_exam=NULL;
            }if($final_outof=='101'){
                (int)$final_outof='40';
            }



            if(!empty($class_final_exam)){
                $class_final_avg_total=($class_final_exam*40)/($final_outof);
            }elseif($class_final_exam=='0'){
                $class_final_avg_total='0';
            }else{
                 $class_final_avg_total=NULL;
            }


            $class_grand_total=0;
            $other_total=0;

            if($class_register->class_quiz_avg_total != NULL){
              $class_grand_total=$class_grand_total+$class_register->class_quiz_avg_total;
              $other_total=$other_total+$class_register->class_quiz_avg_total;
            }else{
              $other_total=$other_total+0;
            }
            
            if($class_attendance != NULL){
              $class_grand_total=$class_grand_total+$class_attendance;
              $other_total=$other_total+$class_attendance;
            }else{
              $other_total=$other_total+0;
            }

            if($class_participation != NULL){
              $class_grand_total=$class_grand_total+$class_participation;
              $other_total=$other_total+$class_participation;
            }else{
              $other_total=$other_total+0;
            }

            if($class_presentaion != NULL){
              $class_grand_total=$class_grand_total+$class_presentaion;
              $other_total=$other_total+$class_presentaion;
            }else{
              $other_total=$other_total+0;
            }


            if($class_register->class_mid_term_avg_total != NULL){
              $class_grand_total=$class_grand_total+$class_register->class_mid_term_avg_total;
              $mid_avg=$class_register->class_mid_term_avg_total;
            }else{
              $mid_avg=0;
            }


            if($class_final_exam != NULL){
              $class_grand_total=$class_grand_total+$class_final_avg_total;
            }

            if(($class_grand_total <= 100) && ($class_grand_total >= 0)){

            $grade=\DB::table('grade_equivalent')->where('lowest_margin','<=', $class_grand_total)->where('highest_margin','>=', $class_grand_total)->first();
            
            if(!empty($grade)){
              $result_grade=$grade->letter_grade;
              $class_result_remarks=$grade->remarks;
            }
            
            if(((($class_final_avg_total)+($mid_avg))<24) || ($other_total<16)){
            $result_grade='F';
            $class_result_remarks='Fail';

            }

            if($class_final_exam==NULL){
             $result_grade='I';
             $class_result_remarks='Incomplete';
           }
        
          if(($class_attendance >10) || ($class_participation >5) || ($class_presentaion >15) || ($class_final_avg_total >40)){
            return 0;
          }

          $final_data=array(
            'class_attendance' => $class_attendance,
            'class_participation' => $class_participation,
            'class_presentaion' => $class_presentaion,
            'class_final_exam' => $class_final_exam,
            'class_final_avg_total' => $class_final_avg_total,
            'class_grand_total' =>  $class_grand_total,
            'class_final_grade' => $result_grade,
            'class_result_remarks' => $class_result_remarks,
            'updated_at' => $now,
          );

            try{

                $success = \DB::transaction(function () use ($final_data, $student_basic, $program, $semester, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $final_store=\DB::connection($this->dbList[$i])->table('student_class_registers')->where('student_tran_code',$student_basic->student_tran_code)->where('class_program',$program)->where('class_semster', $semester)->where('class_year', $year)->where('class_course_code',$course_code)->where('class_result_status',0)->update( $final_data);

                        if((!$final_store)){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_class_registers',json_encode($final_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


      }
        return 1;

    }

    
    /********************************************
    ## FacultyResultProcessingLabResultStore
    *********************************************/

    public function FacultyResultProcessingLabResultStore($student_serial_no, $program, $semester, $year, $course_code, $lab_attendance, $lab_performance, $lab_reprot, $lab_verbal, $lab_final){

      $now=date('Y-m-d H:i:s');

      $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();

        if($lab_attendance=='101'){
          $lab_attendance=NULL;
        }if($lab_performance=='101'){
          $lab_performance=NULL;
        }if($lab_reprot=='101'){
          $lab_reprot=NULL;
        }if($lab_verbal=='101'){
          $lab_verbal=NULL;
        }if($lab_final=='101'){
          $lab_final=NULL;
        }

        $lab_total=0;
        if($lab_attendance != NULL){
          $lab_total=$lab_total+$lab_attendance;
        }
        if($lab_performance != NULL){
          $lab_total=$lab_total+$lab_performance;
        }
        if($lab_reprot != NULL){
          $lab_total=$lab_total+$lab_reprot;
        }
        if($lab_verbal != NULL){
          $lab_total=$lab_total+$lab_verbal;
        }
        if($lab_final != NULL){
          $lab_total=$lab_total+$lab_final;
        }

        if(($lab_total <= 100) && ($lab_total >= 0)){

          try{

             $grade=\DB::table('grade_equivalent')->where('lowest_margin','<=', $lab_total)->where('highest_margin','>=', $lab_total)->first();

              $result_grade=$grade->letter_grade;
              $lab_result_remarks=$grade->remarks;

              if($lab_final==null){
                 $result_grade='I';
                 $lab_result_remarks='Incomplete';
              }

              // if(($lab_attendance>10) && ($lab_performance >25) && ($lab_reprot >25) && ($lab_verbal >10) && ($lab_final >40)){
              //   return 0;
              // }

              $lab_result_store=array(
                'lab_attendance' => $lab_attendance,
                'lab_performance' => $lab_performance,
                'lab_reprot' => $lab_reprot,
                'lab_verbal' => $lab_verbal,
                'lab_final' => $lab_final,
                'lab_result_total' => $lab_total,
                'lab_result_grade' => $result_grade,
                'lab_result_remarks' => $lab_result_remarks,
                'updated_at' => $now,
                );

                $success = \DB::transaction(function () use ($lab_result_store, $student_basic, $semester, $program, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $student_lab_register_save=\DB::connection($this->dbList[$i])->table('student_lab_register')->where('student_tran_code',$student_basic->student_tran_code)->where('lab_program',$program)->where('lab_semster',$semester)->where('lab_year',$year)->where('lab_course_code',$course_code)->where('lab_result_status',0)->update($lab_result_store);

                        if((!$student_lab_register_save)){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_lab_register',json_encode($lab_result_store));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


          }catch(\Exception  $e){
              $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
              \App\System::ErrorLogWrite($message);
              return \Redirect::back()->with('errormessage','Something wrong in catch');
          }
        }
        return 1;

    }



    /********************************************
    ## FacultyResultPublish
    *********************************************/

    public function FacultyResultPublish(){

      $now=date('Y-m-d H:i:s');
      $course_type=\Request::input('course_type');
      $student_tran_code=\Request::input('student_tran_code');

      $program=\Request::input('program');
      $semester=\Request::input('semester');
      $year=\Request::input('year');
      $course_code=\Request::input('course_code');

      if(!empty($student_tran_code)){

        try{

          if($course_type=='Theory'){
            foreach ($student_tran_code as $key => $student) {

              $student_class_registers_update=array(
                'class_result_status' => 1,
                );

              
              $student_class_registers=\DB::table('student_class_registers')->where('student_tran_code', $student)->where('class_program', $program)->where('class_semster', $semester)->where('class_year', $year)->where('class_course_code', $course_code)->where('class_result_status', 0)->first();

              if(!empty($student_class_registers->class_final_grade)){

                if($student_class_registers->class_final_grade=='I'){

                  $result_grade='I';
                  $result_grade_point='0.00';
                  $tabulation_credit_earned='0.0';
                  $tabulation_status='-3';

                }elseif($student_class_registers->class_final_grade=='W'){
                  $result_grade='W';
                  $result_grade_point='0.00';
                  $tabulation_credit_earned='0.0';
                  $tabulation_status='-4';

                }else{

                  $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_class_registers->class_final_grade)->first();
                  $result_grade=$grade->letter_grade;
                  $result_grade_point=$grade->grade_point;
                  $class_result_remarks=$grade->remarks;

                  if($result_grade=='F'){
                   $tabulation_credit_earned='0.0';
                   $tabulation_status='-2';
                  }else{
                  $credit_earned=\DB::table('course_basic')->where('course_program',$student_class_registers->class_program)->where('course_code',$student_class_registers->class_course_code)->first();
                  $tabulation_credit_earned= $credit_earned->credit_hours;
                  $tabulation_status='-1';
                }

              }

              $student_academic_tabulation_update_data=array(
                'tabulation_credit_earned' =>  $tabulation_credit_earned,
                'tabulation_grade_point' => $result_grade_point,
                'tabulation_grade' => $result_grade,
                'tabulation_status' => $tabulation_status,
                'updated_at' => $now,

                );



              $temp_preadvising_update_data=array(
                'temp_preadvising_status' => '6',
                'updated_at' => $now,
                );

              $student_study_level_update_data=array(
                'pre_advising_status' => '6',
                'updated_at' => $now,
                );

                $success = \DB::transaction(function () use ($student_class_registers_update,$student_academic_tabulation_update_data,$temp_preadvising_update_data,$student_study_level_update_data, $student, $program, $semester, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                          $student_class_registers_update_info=\DB::connection($this->dbList[$i])->table('student_class_registers')->where('student_tran_code', $student)->where('class_program', $program)->where('class_semster', $semester)->where('class_year', $year)->where('class_course_code', $course_code)->where('class_result_status', 0)->update($student_class_registers_update);


                          $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->where('student_tran_code', $student)->where('tabulation_program', $program)->where('tabulation_semester', $semester)->where('tabulation_year', $year)->where('tabulation_course_id', $course_code)->update( $student_academic_tabulation_update_data);

                          $temp_preadvising_update=\DB::connection($this->dbList[$i])->table('temp_preadvising')->where('student_tran_code', $student)->where('temp_preadvising_program', $program)->where('temp_preadvising_semester', $semester)->where('temp_preadvising_year', $year)->where('temp_preadvising_status',5)->update($temp_preadvising_update_data);

                          $student_study_level_update=\DB::connection($this->dbList[$i])->table('student_study_level')->where('student_tran_code', $student)->where('study_level_semester', $semester)->where('study_level_year', $year)->where('study_level_status',1)->update($student_study_level_update_data);

                        if(isset($key)&&($key==0)){

                          if((!$student_class_registers_update_info) || (!$student_academic_tabulation_update) || (!$temp_preadvising_update) || (!$student_study_level_update)){
                              $error=1;
                          }

                        }else{

                          if((!$student_academic_tabulation_update)){
                              $error=1;
                          }

                        }

                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_class_registers',json_encode($student_class_registers_update));
                        \App\System::EventLogWrite('update,student_academic_tabulation',json_encode($student_academic_tabulation_update_data));
                        \App\System::EventLogWrite('update,temp_preadvising',json_encode($temp_preadvising_update_data));
                        \App\System::EventLogWrite('update,student_study_level',json_encode($student_study_level_update_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });


              }

            }

            return \Redirect::back()->with('message',"Result Published Successfully !");


          }else{


            foreach ($student_tran_code as $key2 => $student) {

              $student_lab_register_update=array(
                'lab_result_status' => 1,
                );

              $student_lab_register=\DB::table('student_lab_register')->where('student_tran_code', $student)->where('lab_program', $program)->where('lab_semster', $semester)->where('lab_year', $year)->where('lab_course_code', $course_code)->where('lab_result_status', 0)->first();

              if(!empty($student_lab_register->lab_result_grade)){

                if($student_lab_register->lab_result_grade=='I'){

                  $result_grade='I';
                  $result_grade_point='0.00';
                  $tabulation_credit_earned='0.0';
                  $tabulation_status='-3';

                }elseif($student_lab_register->lab_result_grade=='W'){

                  $result_grade='W';
                  $result_grade_point='0.00';
                  $tabulation_credit_earned='0.0';
                  $tabulation_status='-4';

                }else{

                  $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_lab_register->lab_result_grade)->first();
                  $result_grade=$grade->letter_grade;
                  $result_grade_point=$grade->grade_point;
                  $class_result_remarks=$grade->remarks;

                  if($result_grade=='F'){
                   $tabulation_credit_earned='0.0';
                   $tabulation_status='-2';
                  }else{
                    $credit_earned=\DB::table('course_basic')->where('course_program',$student_lab_register->lab_program)->where('course_code',$student_lab_register->lab_course_code)->first();
                    $tabulation_credit_earned= $credit_earned->credit_hours;
                    $tabulation_status='-1';
                  }

                }

                $student_academic_tabulation_update_data=array(
                  'tabulation_credit_earned' => $tabulation_credit_earned,
                  'tabulation_grade_point' => $result_grade_point,
                  'tabulation_grade' => $result_grade,
                  'tabulation_status' => $tabulation_status,
                  'updated_at' => $now,

                  );


                $success = \DB::transaction(function () use ($student_academic_tabulation_update_data, $student_lab_register_update, $student, $program, $semester, $year, $course_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                          $student_lab_register_update_info=\DB::connection($this->dbList[$i])->table('student_lab_register')->where('student_tran_code', $student)->where('lab_program', $program)->where('lab_semster', $semester)->where('lab_year', $year)->where('lab_course_code', $course_code)->where('lab_result_status', 0)->update($student_lab_register_update);

                          $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->where('student_tran_code', $student)->where('tabulation_program', $program)->where('tabulation_semester', $semester)->where('tabulation_year', $year)->where('tabulation_course_id', $course_code)->update($student_academic_tabulation_update_data);

                        if(isset($key2)&&($key2==0)){

                          if((!$student_lab_register_update_info) || (!$student_academic_tabulation_update)){
                              $error=1;
                          }

                        }else{

                          if((!$student_academic_tabulation_update)){
                              $error=1;
                          }

                        }

                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_lab_register',json_encode($student_lab_register_update));
                        \App\System::EventLogWrite('update,student_academic_tabulation',json_encode($student_academic_tabulation_update_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });

              }

            }


            return \Redirect::back()->with('message',"Result Published Successfully !");
          }

        }catch(\Exception  $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('errormessage','Something wrong in catch');
        }

      }else return \Redirect::back()->with('message',"Select Student ID to Publish Result !");

    }


    /********************************************
    ## ProgramHeadResultPublish
    *********************************************/

    public function ProgramHeadResultPublish(){

      $data['page_title'] = $this->page_title;

      if(\Auth::user()->user_role=='head'){

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
      ->orderBy('univ_academic_calender.created_at','desc')->first();
      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)
      ->first();

      if(!empty($univ_academic_calender) && !empty($faculty_basic)){

        $course_tabulation=\DB::table('student_academic_tabulation')
        ->where('tabulation_program', $faculty_basic->program)
        ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
        ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
        ->where('tabulation_status', '!=',0)
        ->select('student_academic_tabulation.*', \DB::raw('count(*) as total'))
        ->groupBy('tabulation_course_id')
        ->paginate(20);
        $course_tabulation->setPath(url('/faculty/program-head-result-publish'));
        $course_tabulation_pagination = $course_tabulation->render();
        $data['course_tabulation_pagination'] = $course_tabulation_pagination;

        $data['course_tabulation']=$course_tabulation;
      }

      return \View::make('pages.faculty.faculty-head-result-publish',$data);

    }else return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home')->with('message',"Your Are Not Assigned As Program Head !");

    }



    /********************************************
    ## ProgramHeadResultPublishModal
    *********************************************/

    public function ProgramHeadResultPublishModal($course_code){

      $data['page_title'] = $this->page_title;
      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();
      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)
      ->first();

      if(!empty($univ_academic_calender) && !empty($faculty_basic)){

        $course_basic=\DB::table('course_basic')
        ->where('course_program', $faculty_basic->program)
        ->where('course_code', $course_code)
        ->first();
        if(!empty($course_basic)){
          $data['course_type']=$course_basic->course_type;
        }

        if(!empty($course_basic) && ($course_basic->course_type=='Theory')){

          $courses=\DB::table('student_class_registers')
          ->where('class_program', $faculty_basic->program)
          ->where('class_semster', $univ_academic_calender->academic_calender_semester)
          ->where('class_year', $univ_academic_calender->academic_calender_year)
          ->where('class_course_code', $course_code)
          ->where('class_result_status', 1)
          ->leftjoin('student_basic','student_basic.student_tran_code','=','student_class_registers.student_tran_code')
          ->get();

          $data['courses']=$courses;
          $data['course_type']=$course_basic->course_type;

        }elseif(!empty($course_basic) && (($course_basic->course_type=='Lab work') || ($course_basic->course_type=='Field work'))){

          $courses=\DB::table('student_lab_register')
          ->where('lab_program', $faculty_basic->program)
          ->where('lab_semster', $univ_academic_calender->academic_calender_semester)
          ->where('lab_year', $univ_academic_calender->academic_calender_year)
          ->where('lab_course_code', $course_code)
          ->where('lab_result_status', 1)
          ->get();

          $data['courses']=$courses;
          $data['course_type']=$course_basic->course_type;
        }

        return \View::make('pages.faculty.ajx-faculty-head-result-publish-modal',$data);


      }else return \Redirect::to('/faculty/program-head-result-publish')->with('message',"Academic Calender Not Set Yet !");

    }



    /********************************************
    ## ProgramHeadResultUpdate
    *********************************************/

    public function ProgramHeadResultUpdate($student_serial_no, $course_code, $program){

      $now=date('Y-m-d H:i:s');

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
      ->orderBy('univ_academic_calender.created_at','desc')->first();
      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)
      ->first();
      $student_basic=\DB::table('student_basic')->where('student_serial_no', $student_serial_no)
      ->first();
      $course_basic=\DB::table('course_basic')
      ->where('course_program', $faculty_basic->program)
      ->where('course_code', $course_code)
      ->first();

      if(!empty($univ_academic_calender)){

        try{

          if($course_basic->course_type=='Theory'){

            if(isset($_GET['class_quiz_1'])){
              $class_quiz_1=$_GET['class_quiz_1'];
            }else{
              $class_quiz_1=null;
            }
            if(isset($_GET['class_quiz_2'])){
              $class_quiz_2=$_GET['class_quiz_2'];
            }else{
              $class_quiz_2=null;
            }
            if(isset($_GET['class_quiz_3'])){
              $class_quiz_3=$_GET['class_quiz_3'];
            }else{
              $class_quiz_3=null;
            }
            if(isset($_GET['class_quiz_4'])){
              $class_quiz_4=$_GET['class_quiz_4'];
            }else{
              $class_quiz_4=null;
            }
            if(isset($_GET['class_attendance'])){
              $class_attendance=$_GET['class_attendance'];
            }else{
              $class_attendance=null;
            }
            if(isset($_GET['class_participation'])){
              $class_participation=$_GET['class_participation'];
            }else{
              $class_participation=null;
            }
            if(isset($_GET['class_presentaion'])){
              $class_presentaion=$_GET['class_presentaion'];
            }else{
              $class_presentaion=null;
            }
            if(isset($_GET['class_mid_term_exam'])){
              $class_mid_term_exam=$_GET['class_mid_term_exam'];
            }else{
              $class_mid_term_exam=null;
            }
            if(isset($_GET['class_final_exam'])){
              $class_final_exam=$_GET['class_final_exam'];
            }else{
              $class_final_exam=null;
            }
            


            $quiz_total=0;
            $count=0;
            if($class_quiz_1 !=null){
              $quiz_total= $quiz_total+$class_quiz_1;
              $count=$count+1;
            }
            if($class_quiz_2 !=null){
              $quiz_total= $quiz_total+$class_quiz_2;
              $count=$count+1;
            }
            if($class_quiz_3 !=null){
              $quiz_total= $quiz_total+$class_quiz_3;
              $count=$count+1;
            }
            if($class_quiz_4 !=null){
              $quiz_total= $quiz_total+$class_quiz_4;
              $count=$count+1;
            }

            if(!empty($quiz_total)){
              (float)$class_quiz_avg_total=(($quiz_total)/($count));
            }else{
              $class_quiz_avg_total=0;
            }
            

            $grand_total=0;
            $other_total=0;
            if($class_quiz_avg_total !=null){
              $grand_total=$grand_total+$class_quiz_avg_total;
              $other_total=$other_total+$class_quiz_avg_total;
            }
            if($class_attendance !=null){
              $grand_total=$grand_total+$class_attendance;
              $other_total=$other_total+$class_attendance;
            }
            if($class_participation !=null){
              $grand_total=$grand_total+$class_participation;
              $other_total=$other_total+$class_participation;
            }
            if($class_presentaion !=null){
              $grand_total=$grand_total+$class_presentaion;
              $other_total=$other_total+$class_presentaion;
            }
            if($class_mid_term_exam !=null){
              $grand_total=$grand_total+$class_mid_term_exam;
            }
            if($class_final_exam !=null){
              $grand_total=$grand_total+$class_final_exam;
            }

            $class_grand_total=ceil($grand_total);

            if(($class_grand_total <= 100) && ($class_grand_total >= 0)){

              $grade=\DB::table('grade_equivalent')->where('lowest_margin','<=', $class_grand_total)->where('highest_margin','>=', $class_grand_total)->first();

              if(!empty($grade)){
                $result_grade=$grade->letter_grade;
                $class_result_remarks=$grade->remarks;
              }

              if(((($class_final_exam)+($class_mid_term_exam))<24) || ($other_total<16)){
                $result_grade='F';
                $class_result_remarks='Fail';

              }

              if($class_final_exam==null){
               $result_grade='I';
               $class_result_remarks='Incomplete';
              }

              if(($class_quiz_1 <=10) && ($class_quiz_2 <=10) && ($class_quiz_3 <=10) && ($class_quiz_4 <=10) && ($class_attendance <=10) && ($class_participation <=5) && ($class_presentaion <=15) && ($class_mid_term_exam <=20) && ($class_final_exam <=40)){

                $head_result_update=array(
                  'class_quiz_1' => $class_quiz_1,
                  'class_quiz_2' => $class_quiz_2,
                  'class_quiz_3' => $class_quiz_3,
                  'class_quiz_4' => $class_quiz_4,
                  'class_attendance' => $class_attendance,
                  'class_participation' => $class_participation,
                  'class_presentaion' => $class_presentaion,
                  'class_quiz_avg_total' => $class_quiz_avg_total,
                  'class_mid_term_exam' => $class_mid_term_exam,
                  'class_mid_term_avg_total' =>$class_mid_term_exam,
                  'class_final_exam' => $class_final_exam,
                  'class_final_avg_total' => $class_final_exam,
                  'class_grand_total' => $class_grand_total,
                  'class_final_grade' => $result_grade,
                  'class_result_remarks' => $class_result_remarks,
                  'updated_at' => $now,
                  'updated_by' => \Auth::user()->user_id,
                  );



                $success = \DB::transaction(function () use ($head_result_update, $student_basic, $course_code, $univ_academic_calender, $program) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                          $head_result_update_save=\DB::connection($this->dbList[$i])->table('student_class_registers')
                                   ->where('student_tran_code', $student_basic->student_tran_code)
                                   ->where('class_course_code', $course_code)
                                   ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                                   ->where('class_year', $univ_academic_calender->academic_calender_year)
                                   ->where('class_program', $program)
                                   ->where('class_result_status', 1)
                                   ->update($head_result_update);

                        if(!$head_result_update_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_class_registers',json_encode($head_result_update));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });

                  
                $student_class_registers=\DB::table('student_class_registers')
                        ->where('student_tran_code', $student_basic->student_tran_code)
                        ->where('class_program', $program)
                        ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                        ->where('class_year', $univ_academic_calender->academic_calender_year)
                        ->where('class_course_code', $course_code)
                        ->first();

                if(!empty($student_class_registers->class_final_grade)){

                    if($student_class_registers->class_final_grade=='I'){

                      $result_grade='I';
                      $result_grade_point='0.00';
                      $tabulation_credit_earned='0.0';
                      $tabulation_status='-3';

                    }elseif($student_class_registers->class_final_grade=='W'){
                      $result_grade='W';
                      $result_grade_point='0.00';
                      $tabulation_credit_earned='0.0';
                      $tabulation_status='-4';

                    }else{

                      $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_class_registers->class_final_grade)->first();
                      $result_grade=$grade->letter_grade;
                      $result_grade_point=$grade->grade_point;
                      $class_result_remarks=$grade->remarks;

                        if($result_grade=='F'){
                           $tabulation_credit_earned='0.0';
                           $tabulation_status='-2';
                        }else{
                          $credit_earned=\DB::table('course_basic')->where('course_program',$student_class_registers->class_program)->where('course_code',$student_class_registers->class_course_code)->first();
                          $tabulation_credit_earned= $credit_earned->credit_hours;
                          $tabulation_status='-1';
                        }

                    }


                    $student_academic_tabulation_update_data=array(
                        'tabulation_credit_earned' =>  $tabulation_credit_earned,
                        'tabulation_grade_point' => $result_grade_point,
                        'tabulation_grade' => $result_grade,
                        'updated_at' => $now,
                    );


                    $success = \DB::transaction(function () use ($head_result_update, $student_basic, $course_code, $univ_academic_calender, $program, $student_academic_tabulation_update_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                          $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')
                                    ->where('student_tran_code', $student_basic->student_tran_code)
                                    ->where('tabulation_program', $program)
                                    ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                                    ->where('class_year', $univ_academic_calender->academic_calender_year)
                                    ->where('tabulation_course_id', $course_code)
                                    ->update( $student_academic_tabulation_update_data);

                            if(!$student_academic_tabulation_update){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,student_academic_tabulation',json_encode($student_academic_tabulation_update_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }

                    });

                    return \Redirect::back()->with('message','Updated');
                }return \Redirect::back()->with('errormessage','Something wrong please try again');

              }return 1;
            }
            return 1;

          }
          else{

            $total_marks=0;
            if(isset($_GET['lab_attendance'])){
              $lab_attendance=$_GET['lab_attendance'];
              $total_marks=$total_marks+$lab_attendance;
            }else{
              $lab_attendance=null;
            }
            if(isset($_GET['lab_performance'])){
              $lab_performance=$_GET['lab_performance'];
              $total_marks=$total_marks+$lab_performance;
            }else{
              $lab_performance=null;
            }
            if(isset($_GET['lab_reprot'])){
              $lab_reprot=$_GET['lab_reprot'];
              $total_marks=$total_marks+$lab_reprot;
            }else{
              $lab_reprot=null;
            }
            if(isset($_GET['lab_verbal'])){
              $lab_verbal=$_GET['lab_verbal'];
              $total_marks=$total_marks+$lab_verbal;
            }else{
              $lab_verbal=null;
            }
            if(isset($_GET['lab_final'])){
              $lab_final=$_GET['lab_final'];
              $total_marks=$total_marks+$lab_final;
            }else{
              $lab_final=null;
            }
            

            $lab_grand_total=ceil($total_marks);

            if(($lab_grand_total <= 100) && ($lab_grand_total >= 0)){

              $grade=\DB::table('grade_equivalent')->where('lowest_margin','<=', $lab_grand_total)->where('highest_margin','>=', $lab_grand_total)->first();

              if(!empty($grade)){
                $result_grade=$grade->letter_grade;
                $lab_result_remarks=$grade->remarks;
              }

              if($lab_grand_total<40){
                $result_grade='F';
                $lab_result_remarks='Fail';

              }

              if($lab_final==null){
               $result_grade='I';
               $lab_result_remarks='Incomplete';
              }

              //if(($lab_attendance <=10) && ($lab_performance <=25) && ($lab_reprot <=25) && ($lab_verbal <=10) && ($lab_final <=40)){

                $lab_result_store=array(
                  'lab_attendance' => $lab_attendance,
                  'lab_performance' => $lab_performance,
                  'lab_reprot' => $lab_reprot,
                  'lab_verbal' => $lab_verbal,
                  'lab_final' => $lab_final,
                  'lab_result_total' => $lab_grand_total,
                  'lab_result_grade' => $result_grade,
                  'lab_result_remarks' => $lab_result_remarks,
                  'updated_at' => $now,
                  'updated_by' => \Auth::user()->user_id,
                  );


                $success = \DB::transaction(function () use ($lab_result_store, $student_serial_no, $course_code, $univ_academic_calender, $program) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                          $head_result_update_save=\DB::connection($this->dbList[$i])->table('student_lab_register')
                                 ->where('student_serial_no', $student_serial_no)
                                 ->where('lab_course_code', $course_code)
                                 ->where('lab_semster', $univ_academic_calender->academic_calender_semester)
                                 ->where('lab_year', $univ_academic_calender->academic_calender_year)
                                 ->where('lab_program', $program)
                                 ->where('lab_result_status', 1)
                                 ->update($lab_result_store);

                        if(!$head_result_update_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,student_lab_register',json_encode($lab_result_store));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                    #################### 12-13-2017 #####################

                $student_lab_register=\DB::table('student_lab_register')->where('student_tran_code', $student_basic->student_tran_code)->where('lab_program', $program)->where('lab_semster', $univ_academic_calender->academic_calender_semester)->where('lab_year', $univ_academic_calender->academic_calender_year)->where('lab_course_code', $course_code)->first();

                  if(!empty($student_lab_register->lab_result_grade)){

                      if($student_lab_register->lab_result_grade=='I'){

                        $result_grade='I';
                        $result_grade_point='0.00';
                        $tabulation_credit_earned='0.0';
                        $tabulation_status='-3';

                      }elseif($student_lab_register->lab_result_grade=='W'){

                        $result_grade='W';
                        $result_grade_point='0.00';
                        $tabulation_credit_earned='0.0';
                        $tabulation_status='-4';

                      }else{

                        $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_lab_register->lab_result_grade)->first();
                        $result_grade=$grade->letter_grade;
                        $result_grade_point=$grade->grade_point;
                        $class_result_remarks=$grade->remarks;

                          if($result_grade=='F'){
                              $tabulation_credit_earned='0.0';
                              $tabulation_status='-2';
                          }else{
                              $credit_earned=\DB::table('course_basic')->where('course_program',$student_lab_register->lab_program)->where('course_code',$student_lab_register->lab_course_code)->first();
                              $tabulation_credit_earned= $credit_earned->credit_hours;
                              $tabulation_status='-1';
                          }

                      }

                      $student_academic_tabulation_update_data=array(
                        'tabulation_credit_earned' => $tabulation_credit_earned,
                        'tabulation_grade_point' => $result_grade_point,
                        'tabulation_grade' => $result_grade,
                        'updated_at' => $now,

                        );

                      $success = \DB::transaction(function () use ($student_serial_no, $student_basic, $course_code, $univ_academic_calender, $program, $student_academic_tabulation_update_data) {

                          for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                                  $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->where('student_tran_code', $student_basic->student_tran_code)->where('tabulation_program', $program)
                                      ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
                                      ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
                                      ->where('tabulation_course_id', $course_code)->update($student_academic_tabulation_update_data);

                              if(!$student_academic_tabulation_update){
                                  $error=1;
                              }
                          }

                          if(!isset($error)){
                              \App\System::TransactionCommit();
                              \App\System::EventLogWrite('update,student_lab_register',json_encode($student_academic_tabulation_update_data));
                          }else{
                              \App\System::TransactionRollback();
                              throw new Exception("Error Processing Request", 1);
                          }
                      });

                      return \Redirect::back()->with('message','Successfully Updated.');

                  }return \Redirect::back()->with('errormessage','Something wrong please try again');

                    ################### 12-13-2017 #####################

              //}return 1;

            }

           return 1;
          }

        }catch(\Exception  $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('errormessage','Something wrong in catch');
        }


      }else return \Redirect::to('/faculty/program-head-result-publish')->with('message',"Academic Calender Not Set Yet !");


   }



   /********************************************
    ## FacultyHeadResultPublish
    *********************************************/

    public function FacultyHeadResultPublish(){
      
      $now=date('Y-m-d H:i:s');

      $course_type=\Request::input('course_type');
      $student_serial_no=\Request::input('student_serial_no');

      $program=\Request::input('program');
      $semester=\Request::input('semester');
      $year=\Request::input('year');
      $course_code=\Request::input('course_code');

        $univ_academic_calender=\DB::table('univ_academic_calender')
        ->where('academic_calender_status',1)
        ->orderBy('univ_academic_calender.created_at','desc')->first();

        if(!empty($student_serial_no) && !empty($univ_academic_calender)){

          try{

            if($course_type=='Theory'){
              foreach ($student_serial_no as $key => $student) {
                $student_basic=\DB::table('student_basic')->where('student_serial_no', $student)->first();

                $student_class_registers=\DB::table('student_class_registers')->where('student_tran_code', $student_basic->student_tran_code)->where('class_program', $program)->where('class_semster', $univ_academic_calender->academic_calender_semester)->where('class_year', $univ_academic_calender->academic_calender_year)->where('class_course_code', $course_code)->where('class_result_status', 1)->first();

                if(!empty($student_class_registers->class_final_grade)){

                  if($student_class_registers->class_final_grade=='I'){

                    $result_grade='I';
                    $result_grade_point='0.00';
                    $tabulation_credit_earned='0.0';
                    $tabulation_status='3';

                  }elseif($student_class_registers->class_final_grade=='W'){
                    $result_grade='W';
                    $result_grade_point='0.00';
                    $tabulation_credit_earned='0.0';
                    $tabulation_status='4';

                  }else{

                    $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_class_registers->class_final_grade)->first();
                    $result_grade=$grade->letter_grade;
                    $result_grade_point=$grade->grade_point;
                    $class_result_remarks=$grade->remarks;

                    if($result_grade=='F'){
                       $tabulation_credit_earned='0.0';
                       $tabulation_status='2';
                     }else{
                      $credit_earned=\DB::table('course_basic')->where('course_program',$student_class_registers->class_program)->where('course_code',$student_class_registers->class_course_code)->first();
                      $tabulation_credit_earned= $credit_earned->credit_hours;
                      $tabulation_status='1';
                    }

                  }

                  $student_academic_tabulation_update_data=array(
                    'tabulation_credit_earned' =>  $tabulation_credit_earned,
                    'tabulation_grade_point' => $result_grade_point,
                    'tabulation_grade' => $result_grade,
                    'tabulation_status' => $tabulation_status,
                    'updated_at' => $now,
                    'updated_by' => \Auth::user()->user_id,
                    );


                  $success = \DB::transaction(function () use ($student_academic_tabulation_update_data, $student, $program, $univ_academic_calender, $course_code) {

                      for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                          $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->where('student_serial_no', $student)->where('tabulation_program', $program)->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)->where('tabulation_year', $univ_academic_calender->academic_calender_year)->where('tabulation_course_id', $course_code)->update( $student_academic_tabulation_update_data);

                          if(!$student_academic_tabulation_update){
                              $error=1;
                          }
                      }

                      if(!isset($error)){
                          \App\System::TransactionCommit();
                          \App\System::EventLogWrite('update,student_academic_tabulation',json_encode($student_academic_tabulation_update_data));
                      }else{
                          \App\System::TransactionRollback();
                          throw new Exception("Error Processing Request", 1);
                      }
                  });


                }
              }

              return \Redirect::back()->with('message',"Result Published Successfully !");
            }

            else{

              foreach ($student_serial_no as $key => $student) {

                $student_lab_register=\DB::table('student_lab_register')->where('student_serial_no', $student)->where('lab_program', $program)->where('lab_semster', $univ_academic_calender->academic_calender_semester)->where('lab_year', $univ_academic_calender->academic_calender_year)->where('lab_course_code', $course_code)->where('lab_result_status', 1)->first();

                if(!empty($student_lab_register->lab_result_grade)){

                  if($student_lab_register->lab_result_grade=='I'){

                    $result_grade='I';
                    $result_grade_point='0.00';
                    $tabulation_credit_earned='0.0';
                    $tabulation_status='3';

                  }elseif($student_lab_register->lab_result_grade=='W'){

                    $result_grade='W';
                    $result_grade_point='0.00';
                    $tabulation_credit_earned='0.0';
                    $tabulation_status='4';

                  }else{

                    $grade=\DB::table('grade_equivalent')->where('letter_grade',$student_lab_register->lab_result_grade)->first();
                    $result_grade=$grade->letter_grade;
                    $result_grade_point=$grade->grade_point;
                    $class_result_remarks=$grade->remarks;

                    if($result_grade=='F'){
                       $tabulation_credit_earned='0.0';
                       $tabulation_status='2';
                    }else{
                      $credit_earned=\DB::table('course_basic')->where('course_program',$student_lab_register->lab_program)->where('course_code',$student_lab_register->lab_course_code)->first();
                      $tabulation_credit_earned= $credit_earned->credit_hours;
                      $tabulation_status='1';
                    }

                  }

                  $student_academic_tabulation_update_data=array(
                    'tabulation_credit_earned' => $tabulation_credit_earned,
                    'tabulation_grade_point' => $result_grade_point,
                    'tabulation_grade' => $result_grade,
                    'tabulation_status' => $tabulation_status,
                    'updated_at' => $now,
                    'updated_by' => \Auth::user()->user_id,
                    );


                    $success = \DB::transaction(function () use ($student_academic_tabulation_update_data, $student, $program, $univ_academic_calender, $course_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                          $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->where('student_serial_no', $student)->where('tabulation_program', $program)->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)->where('tabulation_year', $univ_academic_calender->academic_calender_year)->where('tabulation_course_id', $course_code)->update($student_academic_tabulation_update_data);

                            if(!$student_academic_tabulation_update){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,student_academic_tabulation',json_encode($student_academic_tabulation_update_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                }
            
              }

              return \Redirect::back()->with('message',"Result Published Successfully !");
            }

          }catch(\Exception  $e){
              $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
              \App\System::ErrorLogWrite($message);
              return \Redirect::back()->with('errormessage','Something wrong in catch');
          }

        }else return \Redirect::back()->with('message',"Select Student ID to Publish Result !");


    }




    /********************************************
    ## FacultyInvigilatorSchedule
    *********************************************/

    public function FacultyInvigilatorSchedule(){

      $data['page_title'] = $this->page_title;

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
      ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
      ->orderBy('univ_academic_calender.created_at','desc')->first();

      if(!empty($univ_academic_calender)){

        $invigilator_list_mid=\DB::table('univ_invigilators_exam')
        ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
        ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
        ->where('invigilators_exam_type', 2)
        ->get();
        $data['invigilator_list_mid']=$invigilator_list_mid;

        $invigilator_list_final=\DB::table('univ_invigilators_exam')
        ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
        ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
        ->where('invigilators_exam_type', 3)
        ->get();
        $data['invigilator_list_final']=$invigilator_list_final;

        $data['univ_academic_calender']=$univ_academic_calender;

      }

      return \View::make('pages.faculty.faculty-invigilator-schedule',$data);
    }




    /********************************************
    ## FacultyInvigilatorScheduleDownload
    *********************************************/

    public function FacultyInvigilatorScheduleDownload(){

      $data['page_title'] = $this->page_title;

      $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
      ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
      ->orderBy('univ_academic_calender.created_at','desc')
      ->first();

      if(!empty($univ_academic_calender)){

        $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)
        ->leftjoin('univ_program','univ_program.program_id','=','faculty_basic.program')
        ->first();
        $data['faculty_basic']=$faculty_basic;
        $data['univ_academic_calender']=$univ_academic_calender;


         $invigilator_list_mid=\DB::table('univ_invigilators_exam')
        ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
        ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
        ->where('invigilators_exam_type', 2)
        ->get();
        $data['invigilator_list_mid']=$invigilator_list_mid;

        $invigilator_list_final=\DB::table('univ_invigilators_exam')
        ->where('invigilators_exam_year', $univ_academic_calender->academic_calender_year)
        ->where('invigilators_exam_semester', $univ_academic_calender->academic_calender_semester)
        ->where('invigilators_exam_type', 3)
        ->get();
        $data['invigilator_list_final']=$invigilator_list_final;

        $data['univ_academic_calender']=$univ_academic_calender;

        $pdf_name=\Auth::user()->user_id.'_Invigilator_Schedule_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.date('i_s');

        $pdf = \PDF::loadView('pages.faculty.pdf.faculty-invigilator-schedule-pdf-download',$data);
        return  $pdf->stream($pdf_name.'.pdf');

      }

    }



    // /********************************************
    // ## FacultyProfile
    // *********************************************/

    // public function FacultyProfile(){

    //     $data['page_title'] = $this->page_title;
    //     return \View::make('pages.faculty.faculty-edit-profile',$data);
    // }


    
    /********************************************
    ## FacultyNoticeBoard
    *********************************************/

    public function FacultyNoticeBoard(){

      $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',\Auth::user()->user_id)->first();

      if(!empty($faculty_basic)){

        $user=\Auth::user()->user_id;
        $faculty_notice_list=\DB::table('univ_notice_board')
        ->leftjoin('univ_program','univ_notice_board.notice_program','=','univ_program.program_id')
        ->leftjoin('univ_semester','univ_notice_board.notice_semester','=','univ_semester.semester_code')
        ->where('univ_notice_board.notice_from','=',$user)
        ->orderBy('univ_notice_board.created_at','desc')->paginate(5);

        $faculty_notice_list->setPath(url('/faculty/notice-board'));
        $faculty_notice_pagination = $faculty_notice_list->render();
        $data['faculty_notice_pagination'] = $faculty_notice_pagination;
        $data['faculty_notice_list'] = $faculty_notice_list;

        $data['page_title'] = $this->page_title;
        return \View::make('pages.faculty.faculty-notice-board',$data);
        
      }else{
        return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
      }

    }



    /********************************************
    ## FacultyNoticeSubmit
    *********************************************/

    public function FacultyNoticeSubmit(){

              $rule = [
                    'notice_to' => 'Required',
                    'notice_subject' => 'Required',
                    'notice_message' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $from_type=\Auth::user()->user_type;
            $uuid = \Uuid::generate(4);
            $academic_calender=\DB::table('univ_academic_calender')->orderBy('univ_academic_calender.created_at','desc')->first();
            $now = date('Y-m-d H:i:s');
            $faculty_notice_data = [
                                'notice_tran_code' => $uuid,
                                'notice_from_type' =>$from_type,
                                'notice_from' =>\Auth::user()->user_id,
                                'notice_program' =>\Request::input('notice_program'),
                                'notice_to_type' =>\Request::input('notice_to_type'),
                                'notice_to' =>\Request::input('notice_to'),
                                'notice_semester'=>$academic_calender->academic_calender_semester,
                                'notice_year' => $academic_calender->academic_calender_year,
                                'notice_subject' =>\Request::input('notice_subject'),
                                'notice_message'=>\Request::input('notice_message'),
                                'created_at' =>$now,
                                'updated_at' =>$now ,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,

                            ];


            try{

                $success = \DB::transaction(function () use ($faculty_notice_data) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $insert_data = \DB::connection($this->dbList[$i])->table('univ_notice_board')->insert($faculty_notice_data);


                        if(!$insert_data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,univ_notice_board',json_encode($faculty_notice_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                return \Redirect::to('/faculty/notice-board')->with('message','Faculty Notice has been added.');
            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/faculty/notice-board')->with('errormessage','Faculty Notice Already exists.');
            }

        }else return \Redirect::to('/faculty/notice-board')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## FacultyNoticeDelete
    *********************************************/

    public function FacultyNoticeDelete($notice_tran_code){

      try{

            $success = \DB::transaction(function () use ($notice_tran_code) {

                for($i=0; $i<count($this->dbList); $i++){
                  $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $delete_data = \DB::connection($this->dbList[$i])->table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->delete();

                    if(!$delete_data){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();
                    \App\System::EventLogWrite('delete,univ_notice_board',json_encode($notice_tran_code));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

        return \Redirect::to('/faculty/notice-board')->with('message'," Deleted Successfully!");
      }catch(\Exception  $e){
          $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
          \App\System::ErrorLogWrite($message);
          return \Redirect::to('/faculty/notice-board')->with('errormessage','Faculty Notice Already exists.');
      }
    }



    /*******************************************
    # EditFacultyNoticePage
    ********************************************/
    public function EditFacultyNoticePage($notice_tran_code){

        $edit_faculty_notice=\DB::table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->first();

        if(!empty($edit_faculty_notice)){
            $data['page_title'] = $this->page_title;
        
            $data['edit_faculty_notice']=$edit_faculty_notice;
            return \View::make('pages.faculty.faculty-notice-board-edit',$data);
        }else return \Redirect::to('/faculty/notice-board')->with('errormessage',"Invalid Fee Category!");
        
    }



    /********************************************
    # UpdateFacultyNotice
    *********************************************/
    public function UpdateFacultyNotice($notice_tran_code){

      $rule = [
      'notice_to' => 'Required',
      'notice_subject' => 'Required',
      'notice_message' => 'Required',
      ];

      $v = \Validator::make(\Request::all(),$rule);

      if($v->passes()){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $update_faculty_notice_data = [
        'notice_program' =>\Request::input('notice_program'),
        'notice_to' =>\Request::input('notice_to'),
        'notice_subject' =>\Request::input('notice_subject'),
        'notice_message'=>\Request::input('notice_message'),                               
        'updated_at' =>$now,
        'updated_by' =>\Auth::user()->user_id,

        ];
        try{

            $success = \DB::transaction(function () use ($update_faculty_notice_data, $notice_tran_code) {

                for($i=0; $i<count($this->dbList); $i++){
                  $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $update_data = \DB::connection($this->dbList[$i])->table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->update($update_faculty_notice_data);


                    if(!$update_data){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();
                    \App\System::EventLogWrite('update,univ_notice_board',json_encode($update_faculty_notice_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });
            
          return \Redirect::to('/faculty/notice-board');             
       }catch(\Exception  $e){
          $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
          \App\System::ErrorLogWrite($message);
          return \Redirect::to('/faculty/notice-board')->with('errormessage','Faculty Notice Already exists.');
        }
    }else return \Redirect::to('/faculty/notice-board')->withInput(\Request::all())->withErrors($v->messages());
  }


    /********************************************
    # FacultyNotice
    *********************************************/
    public function FacultyNotice($notice_tran_code){     

      $data['page_title'] = $this->page_title;

      $notice_view=\DB::table('univ_notice_board')->where('notice_tran_code', $notice_tran_code)->first();

      $data['notice_view']=$notice_view;
      return \View::make('pages.faculty.ajax-faculty-notice',$data);

    }


     /********************************************
    # FacultyAllNotice
    *********************************************/
    public function FacultyAllNotice(){     

      $data['page_title'] = $this->page_title;     
      $all_notice=\DB::table('univ_notice_board')
            ->where('univ_notice_board.notice_to_type','=','register_to_faculty')
            ->where('univ_notice_board.notice_to','=','all')
            ->orWhere('univ_notice_board.notice_to','=',\Auth::user()->user_id)
            ->leftJoin('univ_program','univ_notice_board.notice_program','=','univ_program.program_id')
            ->leftJoin('univ_semester','univ_notice_board.notice_semester','=','univ_semester.semester_code')
            ->orderBy('univ_notice_board.created_at','desc')
            ->paginate(10);
      $all_notice->setPath(url('/faculty/all/notice'));
      $faculty_all_notice_pagination = $all_notice->render();
      $data['faculty_all_notice_pagination'] = $faculty_all_notice_pagination;

      $data['all_notice']=$all_notice;
      return \View::make('pages.faculty.faculty-view-all-notice',$data);

    }


    /********************************************
    ## FacultyStudentAttendancePercent
    *********************************************/
    public function FacultyStudentAttendancePercent(){

        $data['page_title'] = $this->page_title;
        $all_student_attendance_info = array();
        
        $univ_academic_calender=\DB::table('univ_academic_calender')
                              ->select('academic_calender_year', \DB::raw('count(*) as total'))
                              ->groupBy('academic_calender_year')
                              ->get();
        $data['univ_academic_calender']=$univ_academic_calender;

        $program_list =\DB::table('faculty_basic')
              ->where('faculty_basic.faculty_id','=', \Auth::user()->user_id)
              ->leftJoin('univ_program','faculty_basic.program','=','univ_program.program_id')
              ->first();

        $data['program_list']=$program_list;


        $course_list =\DB::table('faculty_assingned_course')
              ->where('assigned_course_faculties','like', \Auth::user()->user_id)
              ->select('assigned_course_id','assigned_course_title', \DB::raw('count(*) as total'))
              ->groupBy('assigned_course_id','assigned_course_title')
              ->get();

        $data['course_list']=$course_list;

        $semester_list =\DB::table('univ_semester')->get();

        $data['semester_list']=$semester_list;



        if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year']) && isset($_GET['course'])){


            $faculty_course_info=\DB::table('faculty_assingned_course')
                            ->where('assigned_course_program',$_GET['program'])
                            ->where('assigned_course_semester',$_GET['semester'])
                            ->where('assigned_course_year',$_GET['academic_year'])
                            ->where('assigned_course_id',$_GET['course'])
                            ->where('assigned_course_faculties','like',\Auth::user()->user_id)
                            ->first();
            if(!empty($faculty_course_info)){


                $program=$_GET['program'];
                $semester=$_GET['semester'];
                $academic_year=$_GET['academic_year'];
                $course=$_GET['course'];

                $course_found=\DB::table('course_basic')->where('course_code',$course)->first();
                $course_type=$course_found->course_type;

                $no_of_class_info=\DB::table('student_class_attendance')
                                ->where('attendance_program',$_GET['program'])
                                ->where('attendance_semester',$_GET['semester'])
                                ->where('attendance_year',$_GET['academic_year'])
                                ->where('attendance_course_id',$_GET['course'])
                                ->select('attendance_date', \DB::raw('count(*) as total'))
                                ->groupBy('attendance_date')
                                ->get();

                $total_class_info=count($no_of_class_info);


                $attendance_info=\DB::table('student_class_attendance')
                        ->where('attendance_program',$_GET['program'])
                        ->where('attendance_semester',$_GET['semester'])
                        ->where('attendance_year',$_GET['academic_year'])
                        ->where('attendance_course_id',$_GET['course'])
                        ->select('attendance_student_id', \DB::raw('count(*) as total'))
                        ->groupBy('attendance_student_id')
                        ->get();



                if(!empty($attendance_info)){
                    foreach ($attendance_info as $key => $value) {

                        $student_attendance_info=\DB::table('student_class_attendance')
                            ->where('attendance_program', $_GET['program'])
                            ->where('attendance_semester', $_GET['semester'])
                            ->where('attendance_year', $_GET['academic_year'])
                            ->where('attendance_course_id', $_GET['course'])
                            ->where('attendance_student_id', $value->attendance_student_id)
                            ->leftJoin('student_basic','student_class_attendance.attendance_student_id','=','student_basic.student_serial_no')
                            ->leftJoin('course_basic','student_class_attendance.attendance_course_id','=','course_basic.course_code')
                            ->leftJoin('univ_program','student_class_attendance.attendance_program','like','univ_program.program_id')
                            ->leftJoin('univ_semester','student_class_attendance.attendance_semester','like','univ_semester.semester_code')
                            ->get();



                        $student_total_attendance_info=count($student_attendance_info);
                        $absence_class=$total_class_info-$student_total_attendance_info;
                        $attendance_percentage=number_format((((int)$student_total_attendance_info*100)/$total_class_info),2);


                        $course_info=\DB::table('course_basic')->where('course_code',$course)->first();



                        $student_all_info=\DB::table('student_basic')
                                ->leftJoin('univ_program','student_basic.program','like','univ_program.program_id')
                                ->leftJoin('univ_semester','student_basic.semester','like','univ_semester.semester_code')
                                ->where('student_serial_no', $value->attendance_student_id)
                                ->first();
                        if(!empty($student_all_info) && !empty($course_info)){

                            $student_serial_no=$student_all_info->student_serial_no;
                            $student_name=($student_all_info->first_name).' '.(($student_all_info->middle_name)? $student_all_info->middle_name :'').' '.($student_all_info->last_name);
                            $student_program=$student_all_info->program_title;
                            $student_semester=$student_all_info->semester_title;
                            $student_academic_year=$student_all_info->academic_year;
                            $course_type=$course_info->course_type;
                            $course_title=$course_info->course_title;
                            $course_code=$course_info->course_code;

                            $all_student_attendance=array($student_serial_no, $student_name, $student_program, $course_code, $course_title, $total_class_info, $student_total_attendance_info, $absence_class, $attendance_percentage);
                            $student_attendance=serialize($all_student_attendance);

                            $all_student_attendance_info[]=$student_attendance;

                        }else{
                           $all_student_attendance_info =''; 
                        }

                    }

                }

                $data['all_student_attendance_info']=$all_student_attendance_info;
                return \View::make('pages.faculty.faculty-student-attendance-in-percent',$data);

            }return \Redirect::back()->with('errormessage','You are not assign in this course. !!');

        }return \View::make('pages.faculty.faculty-student-attendance-in-percent',$data);
    }






    /********************************************
    ## FacultyStudentAttendanceListPage 
    *********************************************/

    public function FacultyStudentAttendanceListPage(){

        $data['page_title'] = $this->page_title;
        $all_students_attendance_info = array();

        $program_list =\DB::table('faculty_basic')
              ->where('faculty_basic.faculty_id','=', \Auth::user()->user_id)
              ->leftJoin('univ_program','faculty_basic.program','=','univ_program.program_id')
              ->first();

        $data['program_list']=$program_list;


        $course_list =\DB::table('faculty_assingned_course')
              ->where('assigned_course_faculties','like', \Auth::user()->user_id)
              ->select('assigned_course_id','assigned_course_title', \DB::raw('count(*) as total'))
              ->groupBy('assigned_course_id','assigned_course_title')
              ->get();


        $data['course_list']=$course_list;

                
        $univ_academic_calender=\DB::table('univ_academic_calender')
                              ->select('academic_calender_year', \DB::raw('count(*) as total'))
                              ->groupBy('academic_calender_year')
                              ->get();
        $data['univ_academic_calender']=$univ_academic_calender;


        if(isset($_GET['program']) && isset($_GET['semster']) && isset($_GET['academic_year']) && isset($_GET['course']) || isset($_GET['attendance_date_value'])){
                $course=$_GET['course'];
                $attendance_date =$_GET['attendance_date_value'];


            $faculty_course_info=\DB::table('faculty_assingned_course')
                            ->where('assigned_course_program',$_GET['program'])
                            ->where('assigned_course_semester',$_GET['semester'])
                            ->where('assigned_course_year',$_GET['academic_year'])
                            ->where('assigned_course_id',$_GET['course'])
                            ->where('assigned_course_faculties','like',\Auth::user()->user_id)
                            ->first();

            if(!empty($faculty_course_info)){



                $course_found=\DB::table('course_basic')->where('course_code',$course)->first();

                if(!empty($course_found)){
                    $course_type=$course_found->course_type;
                        
                    if($course_type=='Theory'){


                      $all_student = \DB::table('student_class_registers')
                        // ->where('student_class_registers.class_result_status',0)
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
                        // ->join(
                        //     DB::raw('(SELECT *, COUNT(*) FROM student_class_attendance GROUP BY attendance_course_id) AS q1'),'student_class_registers.class_course_code', '=', 'q1.attendance_course_id'
                        // )
                        ->leftJoin('univ_program','student_class_registers.class_program','like','univ_program.program_id')

                        ->get();


                        /**********Lab course***********/
                    }
                    else{
                        $all_student = \DB::table('student_lab_register')
                        // ->where('student_lab_register.lab_result_status',0)
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

                    $students_attendance_info=\DB::table('student_class_attendance')
                            ->where('attendance_program',$_GET['program'])
                            ->where('attendance_semester',$_GET['semester'])
                            ->where('attendance_year',$_GET['academic_year'])
                            ->where('attendance_course_id',$_GET['course'])
                            ->where('attendance_date','like',$attendance_date)
                            ->get();

                    if(!empty($students_attendance_info) && count($students_attendance_info)>0){
                        foreach ($students_attendance_info as $key => $value) {
                            $all_students_attendance_info[]=$value->attendance_student_id;
                        }
                    }else{
                        $all_students_attendance_info = array();
                    }

                    $data['all_students_attendance_info']=$all_students_attendance_info;
                    $data['all_student']=$all_student;


                }

            }else return \Redirect::back()->with('errormessage','You are not assign in this course. !!');

        }

        return \View::make('pages.faculty.faculty-student-attendance-list',$data);
    }


    /********************************************
    ## FacultyStudentAttendanceSubmit
    *********************************************/

    public function FacultyStudentAttendanceSubmit(){

        $now=date('Y-m-d H:i:s');

        $rule = [
            'attendance_date' => 'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $student_serial_no=\Request::input('student_no');
            $course_code=\Request::input('course_code');
            $attendance_date=\Request::input('attendance_date');


            if(!empty($student_serial_no)){

                $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status','1')->orderBy('univ_academic_calender.created_at','desc')->first();

                $student_class_attendance_data_info=\DB::table('student_class_attendance')
                                                    ->where('attendance_student_id',$student_serial_no)
                                                    ->where('attendance_course_id',$course_code)
                                                    ->where('attendance_date',$attendance_date)
                                                    ->where('attendance_semester',$univ_academic_calender->academic_calender_semester)
                                                    ->where('attendance_year',$univ_academic_calender->academic_calender_year)
                                                    ->first();

                if(empty($student_class_attendance_data_info)){

                    $course_found=\DB::table('course_basic')->where('course_code',$course_code)->first();
                    $faculty_found=\DB::table('faculty_basic')->where('faculty_id','like',\Auth::user()->user_id)->first();


                    if(!empty($univ_academic_calender) && !empty($course_found) && !empty($faculty_found)){

                        $course_type=$course_found->course_type;
                        $faculty_program=$faculty_found->program;

                        $faculty_course_info=\DB::table('faculty_assingned_course')
                                        ->where('assigned_course_program',$faculty_program)
                                        ->where('assigned_course_semester',$univ_academic_calender->academic_calender_semester)
                                        ->where('assigned_course_year',$univ_academic_calender->academic_calender_year)
                                        ->where('assigned_course_id',$course_code)
                                        ->where('assigned_course_faculties','like',\Auth::user()->user_id)
                                        ->first();
                        if(!empty($faculty_course_info)){

                            if($course_type=='Theory'){
                                foreach ($student_serial_no as $key => $stu){

                                    $student_basic=\DB::table('student_basic')
                                        ->where('student_basic.student_serial_no', $stu)
                                        ->leftJoin('student_class_registers','student_basic.student_tran_code','like','student_class_registers.student_tran_code')
                                        ->where('student_class_registers.class_course_code',$course_code)
                                        // ->where('student_class_registers.class_result_status', 0)
                                        ->where('student_class_registers.class_semster', $univ_academic_calender->academic_calender_semester)
                                        ->where('student_class_registers.class_year', $univ_academic_calender->academic_calender_year)
                                        ->first();
                                  if(!empty($student_basic)){

                                    $attendance_tran_code=\Uuid::generate(4);
                                    $student_class_attendance_data=array(
                                        'attendance_tran_code' => $attendance_tran_code->string,
                                        'attendance_student_id' => $student_basic->student_serial_no,
                                        'attendance_program' =>$student_basic->class_program,
                                        'attendance_semester' =>$student_basic->class_semster,
                                        'attendance_year' =>$student_basic->class_year,
                                        'attendance_course_id' =>$student_basic->class_course_code,
                                        'attendance_by' => $student_basic->class_faculty,
                                        'attendance_date' =>$attendance_date,
                                        'attendance_status' => 1,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,
                                        );

                                    try{

                                            $success = \DB::transaction(function () use ($student_class_attendance_data) {

                                                for($i=0; $i<count($this->dbList); $i++){
                                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                    $student_class_attendance_data_store=\DB::connection($this->dbList[$i])->table('student_class_attendance')->insert($student_class_attendance_data);
                                                    if(!$student_class_attendance_data_store){
                                                        $error=1;
                                                    }
                                                }

                                                if(!isset($error)){
                                                    \App\System::TransactionCommit();
                                                    \App\System::EventLogWrite('insert,student_class_attendance',json_encode($student_class_attendance_data));                              
                                                }else{
                                                    \App\System::TransactionRollback();
                                                    throw new Exception("Error Processing Request", 1);
                                                }
                                            });

                                    }catch(\Exception  $e){
                                         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                         \App\System::ErrorLogWrite($message);

                                         return \Redirect::to('/register/student/attendance/list')->with('message','Something wrong !!');
                                    }

                                  }
                                  //return \Redirect::back()->with('errormessage',"Invalid Academic Calender.");
                                }
                            }else{
                                foreach ($student_serial_no as $key => $student){
                                    $student_basic=\DB::table('student_lab_register')
                                    ->where('student_serial_no',$student)
                                    ->where('student_lab_register.lab_course_code',$course_code)
                                    // ->where('student_lab_register.lab_result_status', 0)
                                    ->where('student_lab_register.lab_semster', $univ_academic_calender->academic_calender_semester)
                                    ->where('student_lab_register.lab_year', $univ_academic_calender->academic_calender_year)
                                    ->first(); 
                                  if(!empty($student_basic)){
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

                                        $success = \DB::transaction(function () use ($student_lab_attendance_data) {

                                            for($i=0; $i<count($this->dbList); $i++){
                                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $student_lab_attendance_data_store=\DB::connection($this->dbList[$i])->table('student_class_attendance')->insert($student_lab_attendance_data);
                                                if(!$student_lab_attendance_data_store){
                                                    $error=1;
                                                }
                                            }

                                            if(!isset($error)){
                                                \App\System::TransactionCommit();
                                                \App\System::EventLogWrite('insert,student_class_attendance',json_encode($student_lab_attendance_data_store));

                                            }else{
                                                \App\System::TransactionRollback();
                                                throw new Exception("Error Processing Request", 1);
                                            }
                                        });

                                    }catch(\Exception  $e){
                                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                        \App\System::ErrorLogWrite($message);
                                        return \Redirect::to('/faculty/student/attendance/list')->with('message','Something wrong !!');
                                    }

                                  }
                                  // return \Redirect::back()->with('errormessage',"Invalid Academic Calender.");
                                }
                            }
                            return \Redirect::back()->with('message',"Student attendance stored Successfully!");

                        }else return \Redirect::to('/faculty/student/attendance/list')->with('errormessage',"You are not assign for this course !!!");

                    }return \Redirect::to('/faculty/student/attendance/list')->with('errormessage',"Academic Calender Not Set Yet !");
                }return \Redirect::to('/faculty/student/attendance/list')->with('errormessage', "Student attendance is already save !!!!");
            }else  return \Redirect::back()->with('message','Please Select Student ID !!');


        }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());


    }



    





    #----------------end-----------------#
}
