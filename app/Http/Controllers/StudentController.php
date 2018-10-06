<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\System;
use App\Student;
use Carbon;
use Exception;
use DB;



/*******************************
#
## Student Controller
#
*******************************/

class StudentController extends Controller{
    
    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
       
    }


    ########################
    #Student Dashboard
    ########################
    public function StudentDashboard(){

        $data['page_title'] = $this->page_title;

        $data['student_profile']=\DB::table('student_basic')
        ->where('student_serial_no',\Auth::user()->user_id)
        ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
        ->leftJoin('univ_department','univ_program.program_department_no','=','univ_department.department_no')
        ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
        ->leftJoin('student_personal','student_basic.student_tran_code','=','student_personal.student_tran_code')
        ->select('student_basic.*','univ_program.*','univ_department.*','univ_semester.*','student_personal.*')
        ->first();


        $data['student_contact']=\DB::table('student_contacts')->get();
        return \View::make('pages.student.student-dashboard',$data);
    }


    ########################
    #Student Profile Edit
    ########################
    public function StudentEditProfile(){

        $data['page_title'] = $this->page_title;

        $student_profile_edit=\DB::table('student_basic')
        ->where('student_serial_no',\Auth::user()->user_id)
        ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
        ->leftJoin('univ_department','univ_program.program_department_no','like','univ_department.department_no')
        ->leftJoin('univ_semester','student_basic.semester','like','univ_semester.semester_code')
        ->leftJoin('student_personal','student_basic.student_tran_code','like','student_personal.student_tran_code')
        ->select('student_basic.*','univ_program.*','univ_department.*','univ_semester.*','student_personal.*')
        ->get();
        $data['student_profile_edit']=$student_profile_edit;
        $data['student_contact']=\DB::table('student_contacts')->get();
        // $data['student_gurdian']=\DB::table('student_gurdians')->get();

        $data['student_gurdian']=\DB::table('student_basic')
        ->where('student_serial_no',\Auth::user()->user_id)
        ->leftjoin('student_gurdians','student_gurdians.student_tran_code','=','student_basic.student_tran_code')
        ->where('relation','!=','Local_Guardian')
        ->select('student_basic.*','student_gurdians.*')
        ->get();

        $data['student_info']=\DB::table('student_basic')
        ->where('student_serial_no',\Auth::user()->user_id)
        ->leftjoin('student_gurdians','student_gurdians.student_tran_code','=','student_basic.student_tran_code')
        ->where('relation','Local_Guardian')
        ->select('student_basic.*','student_gurdians.*')
        ->first();

        // $data['student_info']=\DB::table('student_gurdians')
        // ->where('student_gurdians.relation','Local_Guardian')
        // ->where('student_serial_no',\Auth::user()->user_id)
        // ->leftjoin('student_basic','student_basic.student_tran_code','=','student_gurdians.student_tran_code')
        // ->select('student_basic.*','student_gurdians.*')
        // ->first();

        $student_info=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->first();

        $data['student_academic']=\DB::table('student_academic_qualification')->where('student_tran_code',$student_info->student_tran_code)->get();
        return \View::make('pages.student.student-edit-profile',$data);
    }




    ########################
    #Student Basic Profile Update
    ########################
    public function StudentUpdateBasicProfile(){

        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $update_student_basic_data = [
        'mobile' =>\Request::input('mobile'),
        'email' =>\Request::input('email'),
        'updated_at' =>$now,
        'updated_by' =>$user,
        ];

        try{

                $success = \DB::transaction(function () use ($update_student_basic_data, $user) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $update_basic_data=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$user)->update($update_student_basic_data);

                        if(!$update_basic_data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('update,student_basic',json_encode($update_student_basic_data));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

        }catch(\Exception  $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::back()->with('message','Something wrong !!');
        }

        return \Redirect::back()->with('message','Student Basic Info Has Been Updated Successfully.');
    }




    ########################
    #Student Contact Profile Update
    ########################
    public function StudentUpdateContractProfile($student_tran_code){


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
            $update_student_contract_data = [

            'country' =>\Request::input('country'),
            'city' =>\Request::input('city'),
            'postal_code' =>\Request::input('postal_code'),
            'contact_detail' =>\Request::input('contact_detail'),
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->user_id,
            ];

            try{


                $success = \DB::transaction(function () use ($update_student_contract_data, $student_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $update_contract_data = \DB::connection($this->dbList[$i])->table('student_contacts')->where('student_tran_code',$student_tran_code)->where('contact_type','=','present')->update($update_student_contract_data);

                        if(!$update_contract_data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('update',json_encode($update_student_contract_data));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::back()->with('message','Something wrong !!');
            }

            return \Redirect::back()->with('message','Student Contract Has Been Updated Successfully.');
        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
    }




    ########################
    #Student Profile Gurdian Submit
    ########################
    public function StudentProfileGurdianSubmit(){


        $rule = [
        'gurdian_name' => 'Required',
        'occupation' => 'Required',
        'mobile' => 'Required',
        'email' => 'Required',
        'emergency_contact' => 'Required',
        'relation' =>'Required'
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){ 
            $user =\Auth::user()->user_id;
            $now=date('Y-m-d H:i:s');
            $uuid = \Uuid::generate(4);
            $student_tran=\DB::table('student_basic')->select('student_basic.*')->where('student_serial_no',\Auth::user()->user_id)
            ->first();
            $guardian=\DB::table('student_gurdians')->where('student_tran_code',$student_tran->student_tran_code)->where('relation','local_gurdian')->first();

            $student_gurdian_data = [
            'student_gurdians_tran_code' =>$uuid,
            'student_tran_code' => $student_tran->student_tran_code,
            'gurdian_name' =>\Request::input('gurdian_name'),
            'occupation' =>\Request::input('occupation'),
            'mobile' =>\Request::input('mobile'),
            'email' =>\Request::input('email'),
            'relation' =>\Request::input('relation'),
            'emergency_contact' =>\Request::input('emergency_contact'),
            'created_at' =>$now,
            'created_by' =>\Auth::user()->user_id,
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->user_id,
            ];

            $student_gurdian_data_update = [

            'gurdian_name' =>\Request::input('gurdian_name'),
            'occupation' =>\Request::input('occupation'),
            'mobile' =>\Request::input('mobile'),
            'email' =>\Request::input('email'),
            'relation' =>\Request::input('relation'),
            'emergency_contact' =>\Request::input('emergency_contact'),
            'created_at' =>$now,
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->user_id,
            ];

            try{

                if(empty($guardian)){

                    $success = \DB::transaction(function () use ($student_gurdian_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                            $update_guardian_data = \DB::connection($this->dbList[$i])->table('student_gurdians')->insert($student_gurdian_data);

                            if(!$update_guardian_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,student_gurdians',json_encode($student_gurdian_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                 }else{

                    $success = \DB::transaction(function () use ($student_gurdian_data_update, $student_tran) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                            $update_guardian_data_update = \DB::connection($this->dbList[$i])->table('student_gurdians')->where('student_tran_code', $student_tran->student_tran_code)->where('relation', 'Local_Guardian')->update($student_gurdian_data_update);

                            if(!$update_guardian_data_update){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,student_gurdians',json_encode($student_gurdian_data_update));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                 }

           }catch(\Exception  $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::back()->with('message','Something wrong !!');
        }



        return \Redirect::back()->with('message','Student gurdian has been added successfully.');
            }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());


    }



    /********************************************
    ## StudentClassSchedule 
    *********************************************/

    public function StudentProfileGurdianUpdate($student_tran_code){
        $user =\Auth::user()->user_id;
        $now=date('Y-m-d H:i:s');
        $student_update_tran=\DB::table('student_basic')->select('student_basic.*')
        ->where('student_serial_no',\Auth::user()->user_id)
        ->leftjoin('student_gurdians','student_gurdians.student_tran_code','=','student_basic.student_tran_code')
        ->first();
        $guardian=\DB::table('student_gurdians')->where('student_tran_code',$student_update_tran->student_tran_code)->where('relation','local_gurdian')->first();
        $student_gurdian_update = [
        'gurdian_name' =>\Request::input('gurdian_name'),
        'occupation' =>\Request::input('occupation'),
        'mobile' =>\Request::input('mobile'),
        'email' =>\Request::input('email'),
        'relation' =>\Request::input('relation'),
        'emergency_contact' =>\Request::input('emergency_contact'),
        'updated_at' =>$now,
        'updated_by' =>\Auth::user()->user_id,
        ];

        try{


                $success = \DB::transaction(function () use ($student_gurdian_update, $student_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $student_gurdian_update_info=\DB::connection($this->dbList[$i])->table('student_gurdians')->where('student_tran_code',$student_tran_code)->where('relation','=','Local_Guardian')->update($student_gurdian_update);

                        if(!$student_gurdian_update_info){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update',json_encode($student_gurdian_update));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

        }catch(\Exception  $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::back()->with('message','Something wrong !!');
        }
        return \Redirect::back()->with('message','Student gurdian has been updated successfully.');

    }



    /********************************************
    ## StudentClassSchedule 
    *********************************************/

    public function StudentClassSchedule(){

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();
        $data['univ_academic_calender']=$univ_academic_calender;

        $data['page_title'] = $this->page_title;

        return \View::make('pages.student.student-class-schedule',$data);
    }


    /********************************************
    ## StudentClassScheduleDownload 
    *********************************************/

    public function StudentClassScheduleDownload(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();

        if(!empty($univ_academic_calender)){
            $pdf_name=\Auth::user()->user_id.'_Class_Schedule_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.date('i_s');

            $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)
            ->leftjoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->first();
            $data['student_basic']=$student_basic;

            $data['univ_academic_calender']=$univ_academic_calender;

            $pdf = \PDF::loadView('pages.student.pdf.student-class-schedule-pdf-download',$data);
            return  $pdf->stream($pdf_name.'.pdf'); 

        }else return \Redirect::back()->with('message',"Academic Calender Not Set Yet !");

    }



    /********************************************
    ## StudentPreAdvising 
    *********************************************/

    public function StudentPreAdvising(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')
                            ->where('academic_calender_status',1)
                            ->orderBy('created_at','desc')
                            ->first();

        $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();

        if(!empty($univ_academic_calender) && !empty($student_basic)){

            $data['academic_year']= $univ_academic_calender->academic_calender_year;
            $data['academic_semester']= $univ_academic_calender->academic_calender_semester;


            #------------------------preadvising status--------------------------#
            $preadvising=\DB::table('student_basic')
                ->where('student_serial_no',\Auth::user()->user_id)
                ->where('student_status','>',0)
                ->leftjoin('student_study_level','student_study_level.student_tran_code', '=', 'student_basic.student_tran_code')
                ->where('study_level_semester',$univ_academic_calender->academic_calender_semester)
                ->where('study_level_year',$univ_academic_calender->academic_calender_year)
                ->select('student_basic.*','student_study_level.*')
                ->first();


            $data['preadvising']=$preadvising;
            $data['student_basic']=$student_basic;



            #------------------------level term find--------------------------#
            $student_level_term=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)
                ->where('student_status','>',0)
                ->leftjoin('student_study_level','student_basic.student_tran_code','=','student_study_level.student_tran_code')
                ->select('student_basic.*','student_study_level.*')
                ->get();

            $count=0;
            foreach ($student_level_term as $key => $study_level) {
                   $count= $count+1;
            }

               $term=(int)$count%3;
                if($term==0){
                    $term=3;
                }
            $level=ceil((int)$count/3);
            $level=1;

            $data['term']=$term;
            $data['level']=$level;

            ###################### 10-12-2017 ########################
            $student_passed_courses=\DB::table('student_academic_tabulation')
                    ->where('student_serial_no', $student_basic->student_serial_no)
                    ->where('tabulation_program', $student_basic->program)
                    ->where('tabulation_status', 1)                    
                    ->select('student_academic_tabulation.tabulation_course_id', DB::raw('count(*) as total'))
                    ->groupBy('tabulation_course_id')
                    ->get();
            $data['student_passed_courses']=$student_passed_courses;

            ###################### 10-12-2017 ########################


            #------------------------preadvising courses--------------------------#
            $data['preadvising_courses']=\DB::table('course_basic')
            ->where('course_program', $student_basic->program)
            ->where('level', '<=', $level)
            ->where('term', '<=', $term)
            ->get();


            #------------------------preadvised courses--------------------------#
            $preadvised_courses=\DB::table('student_basic')
                ->where('student_serial_no',\Auth::user()->user_id)
                ->where('student_status','>',0)
                ->leftjoin('temp_preadvising','temp_preadvising.student_tran_code','=','student_basic.student_tran_code')
                ->where('temp_preadvising_semester',$univ_academic_calender->academic_calender_semester)
                ->where('temp_preadvising_year',$univ_academic_calender->academic_calender_year)
                ->where('temp_preadvising_level',$level)
                ->where('temp_preadvising_term',$term)
                ->select('student_basic.*','temp_preadvising.*')
                ->first();


            if(!empty($preadvised_courses)){
                 $data['preadvised_courses']=$preadvised_courses;
                 $temp_preadvising_detail[]=unserialize($preadvised_courses->temp_preadvising_detail);
                 $data['temp_preadvising_detail']=$temp_preadvising_detail;
            }

            if(!empty($preadvising) && ($preadvising->pre_advising_status==5)){

                $registered_class_course=\DB::table('student_class_registers')
                ->where('student_tran_code',$student_basic->student_tran_code)
                ->where('class_program', $student_basic->program)
                ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                ->where('class_year', $univ_academic_calender->academic_calender_year)
                ->leftjoin('course_basic','course_basic.course_code','=','student_class_registers.class_course_code')
                ->where('course_program', $student_basic->program)
                ->leftjoin('faculty_basic','faculty_basic.faculty_id','=','student_class_registers.class_faculty')
                ->get();
                $data['registered_class_course']=$registered_class_course;

                $registered_lab_course=\DB::table('student_lab_register')
                ->where('student_tran_code', $student_basic->student_tran_code)
                ->where('lab_program', $student_basic->program)
                ->where('lab_semster', $univ_academic_calender->academic_calender_semester)
                ->where('lab_year', $univ_academic_calender->academic_calender_year)
                ->leftjoin('course_basic','course_basic.course_code','=','student_lab_register.lab_course_code')
                ->where('course_program', $student_basic->program)
                ->leftjoin('faculty_basic','faculty_basic.faculty_id','=','student_lab_register.lab_faculty')
                ->get();
                $data['registered_lab_course']=$registered_lab_course;
            }

            return \View::make('pages.student.student-course-booking',$data);


        }else return \Redirect::to('/student/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
     
        
    }





    /********************************************
    ## StudentPreAdvisingSubmit 
    *********************************************/

    public function StudentPreAdvisingSubmit(){   

        $now = date('Y-m-d H:i:s');

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

        if(!empty($univ_academic_calender)){

            if(!empty(\Request::input('pre_advising_selected_checkbox'))){
                
               $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();
               $student_program= $student_basic->program;

               $level=\Request::input('level');
               $term=\Request::input('term');
               $academic_semester=$univ_academic_calender->academic_calender_semester;
               $academic_year=$univ_academic_calender->academic_calender_year;
               $credit_taken=\Request::input('credit_taken');



                foreach (\Request::input('pre_advising_selected_checkbox') as $key => $course_code) {

                    $course_info=\DB::table('course_basic')->where('course_code',$course_code)
                    ->where('course_program', $student_program)
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


                    $pre_advising_courses=array(
                        'temp_course_code' => $course_info->course_code,         
                        'temp_course_title' => $course_info->course_title,
                        'temp_course_type' => $course_info->course_type,
                        'temp_credit_hours' => $course_info->credit_hours,
                        // 'temp_per_credit_fees_amount' => $course_info->per_credit_fees_amount,
                        // 'temp_total_credit_fees_amount' => $course_info->total_credit_fees_amount,
                        'temp_per_credit_fees_amount' => $temp_per_credit_fees_amount,
                        'temp_total_credit_fees_amount' => $temp_total_credit_fees_amount,
                        );
                    $temp_preadvising_detail[]=$pre_advising_courses;
                }

                $temp_preadvising_detail=serialize($temp_preadvising_detail);


                $uuid_temp_preadvising_tran_code = \Uuid::generate(4);
                $temp_pre_advising_data=array(
                    'temp_preadvising_tran_code' => $uuid_temp_preadvising_tran_code->string,
                    'student_tran_code' => $student_basic->student_tran_code,
                    'temp_preadvising_program' => $student_program,
                    'temp_preadvising_level' => $level,
                    'temp_preadvising_term' => $term,
                    'temp_preadvising_semester' => $academic_semester,
                    'temp_preadvising_year' => $academic_year,
                    'temp_preadvising_total_credit' => $credit_taken,
                    'temp_preadvising_status' => 1,
		    'temp_preadvising_approved_by_faculty' => '',
                    'temp_preadvising_detail' => $temp_preadvising_detail,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by' => \Auth::user()->user_id,
                    'updated_by' => \Auth::user()->user_id,
                    );

                 
                $student_study_level_update = array(
                    'pre_advising_status' => 1,
                    'updated_at' => $now,
                    'updated_by' =>\Auth::user()->user_id,
                );


                //try{
                    $success = \DB::transaction(function () use ($temp_pre_advising_data,$student_study_level_update, $student_basic, $academic_semester, $academic_year) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $temp_preadvising_save=\DB::connection($this->dbList[$i])->table('temp_preadvising')->insert($temp_pre_advising_data);  

                            $student_study_level=\DB::connection($this->dbList[$i])->table('student_study_level')->where('student_tran_code',$student_basic->student_tran_code)->where('study_level_semester', $academic_semester)->where('study_level_year', $academic_year)->update($student_study_level_update);

                            if((!$temp_preadvising_save) || (!$student_study_level)){
                                $error=1;
                            }

                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,temp_preadvising',json_encode($temp_pre_advising_data));
                            \App\System::EventLogWrite('update,student_study_level',json_encode($student_study_level_update));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                /*}catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::back()->with('message','Something wrong !!');
                }*/


                return \Redirect::to('/student/pre-advising')->with('message',"Pre-Advising has been submitted Successfully !");
            
      
            }else return \Redirect::to('/student/pre-advising')->with('message',"No Course Selected !");

        }else return \Redirect::to('/student/pre-advising')->with('message',"Academic Calender Not Set Yet !");
        
    }



    /********************************************
    ## StudentReAdvising
    *********************************************/

    public function StudentReAdvising($temp_preadvising_tran_code, $level, $term){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();


        if(!empty($univ_academic_calender)){

        $data['temp_preadvising_tran_code'] = $temp_preadvising_tran_code;
        $data['temp_preadvising_info'] =\DB::table('temp_preadvising')->where('temp_preadvising_tran_code', $temp_preadvising_tran_code)
        ->leftjoin('univ_semester','univ_semester.semester_code','=','temp_preadvising.temp_preadvising_semester')
        ->first();
        $data['term']=$term;
        $data['level']=$level;

      #------------------------Rreadvising courses--------------------------#
        $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();
        $data['student_basic']=$student_basic;

        $data['preadvising_courses']=\DB::table('course_basic')
        ->where('course_program', $student_basic->program)
        ->where('level', '<=', $level)
        ->where('term', '<=', $term)
        ->get();

            ###################### 10-12-2017 ########################
            $student_passed_courses=\DB::table('student_academic_tabulation')
                    ->where('student_serial_no', $student_basic->student_serial_no)
                    ->where('tabulation_program', $student_basic->program)
                    ->where('tabulation_status', 1)                    
                    ->select('student_academic_tabulation.tabulation_course_id', DB::raw('count(*) as total'))
                    ->groupBy('tabulation_course_id')
                    ->get();
            $data['student_passed_courses']=$student_passed_courses;

            ###################### 10-12-2017 ########################

        return \View::make('pages.student.ajax-student-preadvising-resubmit',$data);

        }else return \Redirect::to('/student/pre-advising')->with('message',"Something went wrong !");

    }




    /********************************************
    ## StudentReAdvisingSubmit 
    *********************************************/

    public function StudentReAdvisingSubmit($temp_preadvising_tran_code){  

        $now = date('Y-m-d H:i:s');
        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

        if(!empty($univ_academic_calender)){

        $level=\Request::input('level');
        $term=\Request::input('term');
        $credit_taken=\Request::input('credit_taken');

        
        $student_basic=\DB::table('student_basic')->where('student_serial_no', \Auth::user()->user_id)->where('student_status','>',0)->first();


            if(!empty(\Request::input('pre_advising_selected_checkbox'))){

                foreach (\Request::input('pre_advising_selected_checkbox') as $key => $course_code) {

                    $course_info=\DB::table('course_basic')->where('course_code',$course_code)
                    ->where('course_program', $student_basic->program)
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


                    $pre_advising_courses=array(
                        'temp_course_code' => $course_info->course_code,         
                        'temp_course_title' => $course_info->course_title,
                        'temp_course_type' => $course_info->course_type,
                        'temp_credit_hours' => $course_info->credit_hours,
                        // 'temp_per_credit_fees_amount' => $course_info->per_credit_fees_amount,
                        // 'temp_total_credit_fees_amount' => $course_info->total_credit_fees_amount,
                        'temp_per_credit_fees_amount' => $temp_per_credit_fees_amount,
                        'temp_total_credit_fees_amount' => $temp_total_credit_fees_amount,
                        );
                    $temp_preadvising_detail[]=$pre_advising_courses;
                }
                $temp_preadvising_detail=serialize($temp_preadvising_detail);


                $temp_pre_advising_data=array(
                    'temp_preadvising_total_credit' => $credit_taken,
                    'temp_preadvising_status' => 3,
                    'temp_preadvising_detail' => $temp_preadvising_detail,
                    'updated_at' => $now,
                    'updated_by' => \Auth::user()->user_id,
                    );

                $student_study_level_update = array(
                    'pre_advising_status' => 3,
                    'updated_at' => $now,
                    'updated_by' =>\Auth::user()->user_id,
                    );


                try{
                    $success = \DB::transaction(function () use ($temp_pre_advising_data,$student_study_level_update, $temp_preadvising_tran_code, $univ_academic_calender, $student_basic) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $temo_preadvising_save=\DB::connection($this->dbList[$i])->table('temp_preadvising')->where('temp_preadvising_tran_code', $temp_preadvising_tran_code)->update($temp_pre_advising_data);  

                            $student_study_level=\DB::connection($this->dbList[$i])->table('student_study_level')->where('student_tran_code',$student_basic->student_tran_code)->where('study_level_semester', $univ_academic_calender->academic_calender_semester)->where('study_level_year', $univ_academic_calender->academic_calender_year)->update($student_study_level_update);

                            if(!$temo_preadvising_save || !$student_study_level){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,temp_preadvising',json_encode($temp_pre_advising_data));
                            \App\System::EventLogWrite('update,student_study_level',json_encode($student_study_level_update));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });
                    
                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::back()->with('message','Something wrong !!');
                }


                return \Redirect::to('/student/pre-advising')->with('message',"Re-Advising has been submitted Successfully !");
            }

        }else return \Redirect::to('/student/pre-advising')->with('message',"Something went wrong !");
           

    }



    /********************************************
    ## StudentPreAdvisingPayment 
    *********************************************/
    public function StudentPreAdvisingPayment(){

        $now=date('Y-m-d H:i:s');
        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

        $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->where('student_status','>',0)->first();
        

        if(!empty($univ_academic_calender) && !empty($student_basic)){

            $course_code=\Request::input('course_code');
            $credit_hours=\Request::input('credit_hours');
            $academic_semester=$univ_academic_calender->academic_calender_semester;
            $academic_year=$univ_academic_calender->academic_calender_year;
            $level=\Request::input('level');
            $term=\Request::input('term');


            $student_accounts_info=\DB::table('student_accounts_info')->where('accounts_student_tran_code',$student_basic->student_tran_code)->first();
            $student_accounts_fees=$student_accounts_info->accounts_fee_deatails;
            $accounts_fee_deatails=unserialize($student_accounts_fees);


           try{

                $total_tution_fee=0;
                foreach ($course_code as $key => $course) {

                    $course_info=\DB::table('course_basic')->where('course_program',$student_basic->program)->where('course_code',$course)->first();

                    $class_faculty=\DB::table('faculty_assingned_course')
                    ->where('assigned_course_program',$course_info->course_program)
                    ->where('assigned_course_level', $level)
                    ->where('assigned_course_term', $term)
                    ->where('assigned_course_year', $academic_year)
                    ->where('assigned_course_semester', $academic_semester)
                    ->where('assigned_course_id', $course)
                    ->first();

                    if(!empty($class_faculty)){
                        $class_course_faculty=$class_faculty->assigned_course_faculties;
                    }else{
                         $class_course_faculty='';
                    }

                    $department=\DB::table('univ_program')->where('program_id',$student_basic->program)->first();

                    $coordinator=\DB::table('program_coordinator_assigned')
                        ->where('coordinator_program',$student_basic->program)
                        ->where('program_coordinator_level', $level)
                        ->where('program_coordinator_term', $term)
                        ->where('program_coordinator_year', $academic_year)
                        ->where('program_coordinator_semester', $academic_semester)
                        ->first();
                    if(!empty($coordinator)){
                        $program_coordinator=$coordinator->coordinator_faculty_id;
                    }else{
                        $program_coordinator='';
                    }

                    #------------------inserts--------------------#
                    #--------------------class register insert------------------------#
                    if($course_info->course_type=='Theory'){

                        $class_register_tran_code = \Uuid::generate(4);

                        $student_class_registers=array(
                          'class_register_tran_code' => $class_register_tran_code,
                          'student_tran_code' => $student_basic->student_tran_code,
                          'class_register_section' => 'A',
                          'class_department' => $department->program_department_no,
                          'class_program' => $student_basic->program,
                          'class_semster' => $academic_semester,
                          'class_year' => $academic_year,
                          'class_course_code' => $course,
                          'class_faculty' => $class_course_faculty,
                          'program_coordinator' => $program_coordinator,
                          'class_result_status' => 0,
                          'created_at' => $now,
                          'updated_at' => $now,
                          'created_by' => \Auth::user()->user_id,
                          'updated_by' => \Auth::user()->user_id,

                          );


                            $success = \DB::transaction(function () use ($student_class_registers,$class_register_tran_code) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $student_class_registers_save=\DB::connection($this->dbList[$i])->table('student_class_registers')->insert($student_class_registers);



                                    if(!$student_class_registers_save){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('insert,student_class_registers',json_encode($student_class_registers));                                

                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });
                            

                    }

                    #--------------------lab register insert------------------------#
                    if($course_info->course_type!='Theory'){

                        $lab_register_tran_code = \Uuid::generate(4);

                        $student_lab_register=array(

                          'lab_register_tran_code' => $lab_register_tran_code,
                          'student_tran_code' => $student_basic->student_tran_code,
                          'student_serial_no' => $student_basic->student_serial_no,
                          'lab_section' => 'A',
                          'lab_department' => $department->program_department_no,
                          'lab_program' => $student_basic->program,
                          'lab_semster' => $academic_semester,
                          'lab_year' => $academic_year,
                          'lab_course_code' => $course,
                          'lab_faculty' => $class_course_faculty,
                          'lab_coordinator' => $program_coordinator,
                          'lab_result_status' => 0,

                          'created_at' => $now,
                          'updated_at' => $now,
                          'created_by' => \Auth::user()->user_id,
                          'updated_by' => \Auth::user()->user_id,

                          );

                            $success = \DB::transaction(function () use ($student_lab_register) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $student_lab_register_save=\DB::connection($this->dbList[$i])->table('student_lab_register')->insert($student_lab_register);


                                    if(!$student_lab_register_save){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('insert,student_lab_register',json_encode($student_lab_register));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });
                            

                    }


                    #--------------------student academic tabulation insert---------------------#
                    $tabulation_tran_code = \Uuid::generate(4);
                    $student_academic_tabulation=array(
                          'tabulation_tran_code' => $tabulation_tran_code->string,
                          'student_tran_code' => $student_basic->student_tran_code,
                          'student_serial_no' => $student_basic->student_serial_no,
                          'tabulation_program' => $student_basic->program,
                          'tabulation_semester' => $academic_semester,
                          'tabulation_year' => $academic_year,
                          'tabulation_level' => $level,
                          'tabulation_term' => $term,
                          'tabulation_course_id' => $course,
                          'tabulation_course_title' => $course_info->course_title,
                          'tabulation_course_type' => $course_info->course_type,
                          'tabulatation_credit_hours' => $course_info->credit_hours,
                          'tabulation_status' => 0,
                          'created_at' => $now,
                          'updated_at' => $now,
                          'created_by' => \Auth::user()->user_id,
                          'updated_by' => \Auth::user()->user_id,

                      );


                    $study_level_preadvising_status=array(
                        'pre_advising_status' => 5,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' => $now,
                        );

                    $temp_preadvising_status=array(
                        'temp_preadvising_status' => 5,
                        'updated_by' => \Auth::user()->user_id,
                        'updated_at' => $now,
                        );

                    

                        $success = \DB::transaction(function () use ($student_academic_tabulation, $study_level_preadvising_status,$temp_preadvising_status, $student_basic, $academic_year, $academic_semester, $key) {


                            for($j=0; $j<count($this->dbList); $j++){


                                $save_transaction=\DB::connection($this->dbList[$j])->beginTransaction();
                                $student_academic_tabulation_save=\DB::connection($this->dbList[$j])->table('student_academic_tabulation')->insert($student_academic_tabulation);



                                $study_level_preadvising_status_update=\DB::connection($this->dbList[$j])->table('student_study_level')
                                ->where('student_tran_code',$student_basic->student_tran_code)
                                ->where('study_level_semester',$academic_semester)
                                ->where('study_level_year',$academic_year)
                                ->update($study_level_preadvising_status);


                                $temp_preadvising_status_update=\DB::connection($this->dbList[$j])->table('temp_preadvising')
                                ->where('student_tran_code',$student_basic->student_tran_code)
                                ->where('temp_preadvising_semester',$academic_semester)
                                ->where('temp_preadvising_year',$academic_year)
                                ->update($temp_preadvising_status);

                                if(isset($key)&&($key==0)){

                                     if((!$student_academic_tabulation_save) || (!$study_level_preadvising_status_update) || (!$temp_preadvising_status_update)){
                                        $error=1;

                                     }
                                }else{
                                    if(!$student_academic_tabulation_save){
                                        $error=1;
                                    }
                                }

                            }
                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,student_academic_tabulation',json_encode($student_academic_tabulation));
                        
                                \App\System::EventLogWrite('update,student_study_level',json_encode($study_level_preadvising_status));
                                \App\System::EventLogWrite('update,temp_preadvising',json_encode($temp_preadvising_status));

                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });



                    #-----------------end inserts-----------------#



                    #-----------------for accounts------------------#
                    foreach ($accounts_fee_deatails as $key => $accounts_fees) {
                        if($accounts_fees->accounts_fee_name_slug=='tution_fee'){
                           $tution_fee=($course_info->credit_hours)*($accounts_fees->accounts_fee_amount);
                           $total_tution_fee=$total_tution_fee+$tution_fee;      
                        }

                    }
                    #-------------------end accounts----------------#


                }


            }catch(\Exception $e){
                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('message','Something wrong in catch !!!');

            }


            if(!empty($student_accounts_info->waiver_type)){
                $waiver=\DB::table('waivers')->where('waiver_name_slug',$student_accounts_info->waiver_type)->first();
                $waiver_rate=$waiver->waiver_rate;
                $waiver_paid=(($total_tution_fee)/(100))*($waiver_rate);


                $payment_transaction_waiver_tran_code_paid=\Uuid::generate(4);

                $student_payment_transactions_waiver_paid=array(
                        'payment_transaction_tran_code' => $payment_transaction_waiver_tran_code_paid->string,
                        'payment_student_tran_code' => $student_basic->student_tran_code,
                        'payment_student_serial_no' => $student_basic->student_serial_no,
                        'payment_program' => $student_basic->program,
                        'payment_semster' => $academic_semester,
                        'payment_year' => $academic_year,
                        'payment_transaction_fee_type' => 'Waiver',
                        'payment_receive_type' => 'NDUB',
                        'payment_receivable' => 0,
                        'payment_paid' => $waiver_paid,
                        'payment_remarks' => 'Paid',
                        'payment_others' => 0,
                        'payment_amounts' => $waiver_paid,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,


                    );

                try{
                    $success = \DB::transaction(function () use ($student_payment_transactions_waiver_paid) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $student_payment_waiver_paid_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_waiver_paid);

                            if(!$student_payment_waiver_paid_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_transactions_waiver_paid));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });
                    
                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::back()->with('message','Something wrong !!');
                }

            }
        // else{
        //     $waiver_paid=0;
        // }


            foreach ($accounts_fee_deatails as $key => $accounts_fees) {

                if($accounts_fees->accounts_fee_name_slug=='tution_fee'){
                    // $payment_receivable=($total_tution_fee)-($waiver_paid);
                    $payment_receivable=$total_tution_fee;
                }else{
                    $payment_receivable=$accounts_fees->accounts_fee_amount;
                }

                if($accounts_fees->accounts_fee_name_slug != 'admission_fee'){
                    $payment_transaction_tran_code=\Uuid::generate(4);
                    $student_payment_transactions_data=array(
                        'payment_transaction_tran_code' => $payment_transaction_tran_code->string,
                        'payment_student_tran_code' => $student_basic->student_tran_code,
                        'payment_student_serial_no' => $student_basic->student_serial_no,
                        'payment_program' => $student_basic->program,
                        'payment_semster' => $academic_semester,
                        'payment_year' => $academic_year,
                        'payment_transaction_fee_type' => $accounts_fees->accounts_fee_name_slug,
                        'payment_receive_type' => '',
                        'payment_receivable' => $payment_receivable,
                        'payment_paid' => 0,
                        'payment_remarks' => 'Not Paid',
                        'payment_others' => 0,
                        'payment_amounts' => 0,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,

                        );

                    try{
                        $success = \DB::transaction(function () use ($student_payment_transactions_data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                                
                                $student_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_data);

                                if(!$student_payment_transactions_save){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_transactions_data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });
                        
                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::back()->with('message','Something wrong !!');
                    }

                }

            }

            return \Redirect::to('/student/pre-advising')->with('message',"Course Registration Successfull !");

        }
        

    }


    /********************************************
    ## StudentGradeSheet 
    *********************************************/

    public function StudentGradeSheet(){

        $data['page_title'] = $this->page_title;

        $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->first();

        $univ_academic_calender=\DB::table('univ_academic_calender')
        ->select('academic_calender_year', \DB::raw('count(*) as total'))
        ->groupBy('academic_calender_year')
        ->get();
        $data['univ_academic_calender']=$univ_academic_calender;

        $semester_list=\DB::table('univ_semester')->get();
        $data['semester_list']=$semester_list;



        if(isset($_GET['semester']) && isset($_GET['academic_year'])){
            $semester=$_GET['semester'];
            $academic_year=$_GET['academic_year'];

            $student_cgpa=\DB::table('student_academic_tabulation')->where('student_serial_no',\Auth::user()->user_id)
            ->where('tabulation_status','>',0)
            ->get();

            $total_taken_credit=0;
            $total_earned_credit=0;
            $total_point=0;
            $all_course = array();

                foreach ($student_cgpa as $key => $value) {
                    $list=(string)$value->tabulation_course_id;

                    $student_tabulation_info2=\DB::table('student_academic_tabulation')
                        ->where('student_serial_no',\Auth::user()->user_id)
                        ->where('tabulation_course_id',$list)
                        ->where('tabulation_status','=',1)
                        ->get();
                    foreach ($student_tabulation_info2 as $key2 => $value2) {
                        $max_grade_point=0;
                        $max_grade_point_info=0;
                        if($max_grade_point<($value2->tabulation_grade_point)){
                            $max_grade_point=$value2->tabulation_grade_point;
                            $max_grade_point_info=$value2->tabulation_tran_code;
                        }

                    }

                    $all_course[]=$max_grade_point_info;
                }

            $student_cgpa_info=\DB::table('student_academic_tabulation')
                ->where('student_serial_no',\Auth::user()->user_id)
                ->where('tabulation_status','=',1)
                ->whereIn('tabulation_tran_code',$all_course)
                ->get();



            foreach ($student_cgpa_info as $key => $tabulation) {

                $total_taken_credit=$total_taken_credit+$tabulation->tabulatation_credit_hours;
                $total_earned_credit=$total_earned_credit+$tabulation->tabulation_credit_earned;
                $total_point=$total_point+($tabulation->tabulation_credit_earned)*($tabulation->tabulation_grade_point);
            }

            if(!empty($total_point) && !empty($total_earned_credit)){

                $student_cgpa=round($total_point/$total_earned_credit, 2);
                $data['student_cgpa']=$student_cgpa;
                $data['total_taken_credit']=$total_taken_credit;
                $data['total_earned_credit']=$total_earned_credit;
            }

            $student_result=\DB::table('student_academic_tabulation')
            ->where('student_serial_no',\Auth::user()->user_id)
            ->where('tabulation_status','>',0)
            ->where('tabulation_semester',$semester)
            ->where('tabulation_year',$academic_year)
            ->get();
            $data['student_result']=$student_result;

            $student_info=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)
            ->first(); 
            $data['student_info']=$student_info;

            $semester_info=\DB::table('univ_semester')->where('semester_code', $semester)
            ->first(); 
            $data['semester_info']=$semester_info;
            $data['year']=$academic_year;

        }

        return \View::make('pages.student.student-grade-sheet',$data);


    }



    /********************************************
    ## StudentPaymentHistory 
    *********************************************/

    public function StudentPaymentHistory(){

      $data['page_title'] = $this->page_title;

      $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->first();

      if(!empty($student_basic)){

          $student_serial_no=\Auth::user()->user_id;

          $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
          ->leftJoin('univ_program','program_id','=','student_basic.program')
          ->select('student_basic.*','univ_program.*')
          ->first();
          $data['student_info']=$student_info;

          $student_payment_transaction_detail=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_serial_no)
          ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
          ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
          ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
          ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
          // ->orderBy('student_payment_transactions.created_at','desc')
          ->orderBy('student_payment_transactions.payment_year','asc')
            ->orderBy('student_payment_transactions.payment_semster','asc')
            ->orderBy('student_payment_transactions.payment_amounts','asc')

          ->select('student_payment_transactions.*','accounts_transaction_history.*','student_payment_transactions.updated_at AS transaction_date','fee_category.*','univ_semester.*','student_payment_transactions.created_at as transaction_date')
          ->get();


          $data['student_payment_transaction_detail']=$student_payment_transaction_detail;

          $total_payment_receivable=\DB::table('student_payment_transactions')
          ->where('payment_student_serial_no',$student_serial_no)
          ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
          ->sum('payment_receivable');
          $data['total_payment_receivable']=$total_payment_receivable;

          $total_payment_paid=\DB::table('student_payment_transactions')
          ->where('payment_student_serial_no',$student_serial_no)
          ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
          ->sum('payment_paid');
          $data['total_payment_paid']=$total_payment_paid;

          $total_payment_others=\DB::table('student_payment_transactions')
          ->where('payment_student_serial_no',$student_serial_no)
          ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
          ->sum('payment_others');
          $data['total_payment_others']= $total_payment_others;

          $data['total_payment_due']= $total_payment_receivable-$total_payment_paid;

          $univ_academic_calender=\DB::table('univ_academic_calender')
          ->leftjoin('univ_semester','univ_semester.semester_code','=','univ_academic_calender.academic_calender_semester')->orderBy('univ_academic_calender.created_at','desc')
          ->get();
          $data['univ_academic_calender']=$univ_academic_calender;

          return \View::make('pages.student.student-payment-status',$data);

      }else{
        return \Redirect::to('/student/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
    }

}



    /********************************************
    ## StudentPaymentHistoryAjax 
    *********************************************/

    public function StudentPaymentHistoryAjax($semester){

      $data['page_title'] = $this->page_title;

      $student_serial_no=\Auth::user()->user_id;

      if(!empty($semester) && ($semester!='all')){
        $academic_year=substr($semester,-4);
        $semester_code=substr($semester,0,-5);

        $semester_info=\DB::table('univ_semester')->where('semester_code',$semester_code)->first();
        $data['semester_title']=$semester_info->semester_title;

        $data['year']=$academic_year;
        $student_payment_transaction_detail=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_serial_no)->where('payment_semster',$semester_code)->where('payment_year',$academic_year)
        ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
	->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
        ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
        // ->orderBy('student_payment_transactions.created_at','desc')
        ->orderBy('student_payment_transactions.payment_year','asc')
        ->orderBy('student_payment_transactions.payment_semster','asc')
        ->orderBy('student_payment_transactions.payment_amounts','asc')
        ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','fee_category.*','univ_semester.*')
        ->get();

        $data['student_payment_transaction_detail']=$student_payment_transaction_detail;

      }

      else{

        $data['semester_title']='ALL';

        $student_payment_transaction_detail=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_serial_no)
        ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
        ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
        ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
        // ->orderBy('student_payment_transactions.created_at','desc')
        ->orderBy('student_payment_transactions.payment_year','asc')
        ->orderBy('student_payment_transactions.payment_semster','asc')
        ->orderBy('student_payment_transactions.payment_amounts','asc')
        ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','fee_category.*','univ_semester.*')
        ->get();

        $data['student_payment_transaction_detail']=$student_payment_transaction_detail;
      }


      return \View::make('pages.student.ajax-student-payment-history',$data);
    }



    /********************************************
    ## StudentAcademicCoursePlan 
    *********************************************/

    public function StudentAcademicCoursePlan(){

        $data['page_title'] = $this->page_title;

        $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)->first();


        if(!empty($student_basic)){

            $student_academic_course_plan=\DB::table('degree_plans')->where('plan_program',$student_basic->program)
            ->leftjoin('degree_plan_detail','degree_plan_detail.degree_plan_tran_code','=','degree_plans.degree_plan_tran_code')
            ->leftjoin('course_catalogue','course_catalogue.course_catalogue_slug','=','degree_plan_detail.course_catalogue_tran_code')
            ->leftjoin('course_category','course_category.course_category_slug','=','course_catalogue.course_category_slug')
            ->orderBy('course_category_name','asc')
            ->get();
            $data['student_academic_course_plan']=$student_academic_course_plan;


            $student_program= $student_basic->program;
            $data['lt_11']=\App\Student::StudentCoursePlan($student_program,1,1);
            $data['lt_12']=\App\Student::StudentCoursePlan($student_program,1,2);
            $data['lt_13']=\App\Student::StudentCoursePlan($student_program,1,3);
            $data['lt_21']=\App\Student::StudentCoursePlan($student_program,2,1);
            $data['lt_22']=\App\Student::StudentCoursePlan($student_program,2,2);
            $data['lt_23']=\App\Student::StudentCoursePlan($student_program,2,3);
            $data['lt_31']=\App\Student::StudentCoursePlan($student_program,3,1);
            $data['lt_32']=\App\Student::StudentCoursePlan($student_program,3,2);
            $data['lt_33']=\App\Student::StudentCoursePlan($student_program,3,3);
            $data['lt_41']=\App\Student::StudentCoursePlan($student_program,4,1);
            $data['lt_42']=\App\Student::StudentCoursePlan($student_program,4,2);
            $data['lt_43']=\App\Student::StudentCoursePlan($student_program,4,3);

            return \View::make('pages.student.student-academic-course-plan',$data);

        }else{

            return \Redirect::to('/student/'.\Auth::user()->name_slug.'/home')->with('message',"Something went wrong !");
        }
        
    }


    /********************************************
    ## AjaxStudentCoursePlanDetails 
    *********************************************/

    public function AjaxStudentCoursePlanDetails($program, $course_category){

        $data['page_title'] = $this->page_title;
        if($course_category=='internship.thesis'){
          $course_category='internship/thesis';
        }
        

        $completed_course=\DB::table('course_basic')->where('course_program',$program)->where('course_category', $course_category)
        ->get();
        $data['completed_course']=$completed_course;


        $course_category=\DB::table('course_category')->where('course_category_slug',$course_category)->first();
        $course_category_name=$course_category->course_category_name;
        $data['course_category_name']= $course_category_name;

        return \View::make('pages.student.ajax-student-course-plan-details',$data);
    }



    /********************************************
    ## StudentExamRoutine 
    *********************************************/

    public function StudentExamRoutine(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();

        if(!empty($univ_academic_calender)){
            $data['univ_academic_calender']=$univ_academic_calender;

            $student_basic=\DB::table('student_basic')->where('student_serial_no', \Auth::user()->user_id)->first();

            $midterm_exam_schedule=\DB::table('student_academic_tabulation')
            ->where('student_serial_no', $student_basic->student_serial_no)
            ->where('tabulation_program', $student_basic->program)
            ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
            ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
            ->leftjoin('univ_exam_schedule','univ_exam_schedule.exam_schedule_course','=','student_academic_tabulation.tabulation_course_id')
            ->where('exam_schedule_type', 2)
            ->where('exam_schedule_program', $student_basic->program)
            ->where('exam_schedule_year', $univ_academic_calender->academic_calender_year)
            ->where('exam_schedule_semester', $univ_academic_calender->academic_calender_semester)
            ->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
            ->leftjoin('univ_time_slot','univ_time_slot.univ_time_slot','=','univ_exam_schedule.exam_schedule_time_slot')
            ->where('univ_time_slot_for',2)
            ->get();

            $final_exam_schedule=\DB::table('student_academic_tabulation')
            ->where('student_serial_no', $student_basic->student_serial_no)
            ->where('tabulation_program', $student_basic->program)
            ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
            ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
            ->leftjoin('univ_exam_schedule','univ_exam_schedule.exam_schedule_course','=','student_academic_tabulation.tabulation_course_id')
            ->where('exam_schedule_type', 3)
            ->where('exam_schedule_program', $student_basic->program)
            ->where('exam_schedule_year', $univ_academic_calender->academic_calender_year)
            ->where('exam_schedule_semester', $univ_academic_calender->academic_calender_semester)
            ->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
            ->leftjoin('univ_time_slot','univ_time_slot.univ_time_slot','=','univ_exam_schedule.exam_schedule_time_slot')
            ->where('univ_time_slot_for',3)
            ->get();

            $data['midterm_exam_schedule']=$midterm_exam_schedule;
            $data['final_exam_schedule']=$final_exam_schedule;

        }
        

        return \View::make('pages.student.student-exam-routine',$data);
    }


    ########################
    #StudentExamRoutineDownload
    ########################
    public function StudentExamRoutineDownload(){     

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
        ->first();
        if(!empty($univ_academic_calender)){
           $data['page_title'] = $this->page_title;


           $student_basic=\DB::table('student_basic')->where('student_serial_no',\Auth::user()->user_id)
            ->leftjoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->first();

            $data['student_basic']=$student_basic;
            $data['univ_academic_calender']=$univ_academic_calender;

           $midterm_exam_schedule=\DB::table('student_academic_tabulation')
           ->where('student_serial_no', $student_basic->student_serial_no)
           ->where('tabulation_program', $student_basic->program)
           ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
           ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
           ->leftjoin('univ_exam_schedule','univ_exam_schedule.exam_schedule_course','=','student_academic_tabulation.tabulation_course_id')
           ->where('exam_schedule_type', 2)
           ->where('exam_schedule_program', $student_basic->program)
           ->where('exam_schedule_year', $univ_academic_calender->academic_calender_year)
           ->where('exam_schedule_semester', $univ_academic_calender->academic_calender_semester)
           ->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
            ->leftjoin('univ_time_slot','univ_time_slot.univ_time_slot','=','univ_exam_schedule.exam_schedule_time_slot')
            ->where('univ_time_slot_for',2)
           ->get();

           $final_exam_schedule=\DB::table('student_academic_tabulation')
           ->where('student_serial_no', $student_basic->student_serial_no)
           ->where('tabulation_program', $student_basic->program)
           ->where('tabulation_semester', $univ_academic_calender->academic_calender_semester)
           ->where('tabulation_year', $univ_academic_calender->academic_calender_year)
           ->leftjoin('univ_exam_schedule','univ_exam_schedule.exam_schedule_course','=','student_academic_tabulation.tabulation_course_id')
           ->where('exam_schedule_type', 3)
           ->where('exam_schedule_program', $student_basic->program)
           ->where('exam_schedule_year', $univ_academic_calender->academic_calender_year)
           ->where('exam_schedule_semester', $univ_academic_calender->academic_calender_semester)
           ->leftjoin('univ_program','univ_program.program_id','=','univ_exam_schedule.exam_schedule_program')
            ->leftjoin('univ_time_slot','univ_time_slot.univ_time_slot','=','univ_exam_schedule.exam_schedule_time_slot')
            ->where('univ_time_slot_for',3)
           ->get();

           $data['midterm_exam_schedule']=$midterm_exam_schedule;
           $data['final_exam_schedule']=$final_exam_schedule;


           $pdf_name =  'Exam_schedule_'.\Auth::user()->user_id.'_'.$univ_academic_calender->semester_title.'_'.$univ_academic_calender->academic_calender_year.'_'.date('i_s');

           $pdf = \PDF::loadView('pages.student.pdf.student-exam-schedule-pdf-download',$data);
           return  $pdf->stream($pdf_name.'.pdf'); 

       }

   }




    ########################
    #StudentNoticeView
    ########################
    public function StudentNoticeView($notice_tran_code){     

        $data['page_title'] = $this->page_title;

        $notice_view=\DB::table('univ_notice_board')->where('notice_tran_code', $notice_tran_code)->first();

        $data['notice_view']=$notice_view;
        return \View::make('pages.student.ajax-student-notice',$data);

    }



    /********************************************
    ## StudentAllNotice
    *********************************************/
    public function StudentAllNotice(){
        
        $student_all_notice=\DB::table('univ_notice_board')
        ->whereIn('univ_notice_board.notice_to_type',array('register_to_student','faculty_to_student'))
        ->orwhereIn('univ_notice_board.notice_to',array('all',\Auth::user()->user_id))
        ->leftJoin('univ_program','univ_notice_board.notice_program','=','univ_program.program_id')
        ->leftJoin('univ_semester','univ_notice_board.notice_semester','=','univ_semester.semester_code')
        ->orderBy('univ_notice_board.created_at','desc')
        ->paginate(10);
        $student_all_notice->setPath(url('/student/all/notice'));
        $student_all_notice_pagination = $student_all_notice->render();
        $data['student_all_notice_pagination'] = $student_all_notice_pagination;
        $data['student_all_notice']=$student_all_notice;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.student.student-view-all-notice',$data);
    }



    /***********************************************************************/
}

