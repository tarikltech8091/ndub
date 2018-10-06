<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\System;
use Carbon;
use Exception;
use DB;

/*******************************
#
## Register Controller
#
*******************************/

class RegisterController extends Controller
{
    protected $error;
    
    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
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
                        ->orderBy('applicant_basic.created_at','asc')
                        ->paginate(10);

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
    ## ApplicantListExcelDownload 
    *********************************************/

    public function ApplicantListExcelDownload(){

        $excel_name = 'applicant_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                 if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

                   $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.payment_status',1)->where(function($query){

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

                    $all_applicant = \DB::table('applicant_basic')->where('applicant_basic.payment_status',1)
                                ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                                ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                                ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                                ->orderBy('applicant_basic.updated_at','desc')->get(10);

                   
                    $data['all_applicant'] = $all_applicant;

                 }

                 $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.excel-applicant-list',$data);
            });
        })->export('xlsx');

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

                                $success = \DB::transaction(function () use ($payment_data, $applicant) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                                        $update_payment=\DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant)->update($payment_data);
                                        if(!$update_payment){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::TransactionCommit();
                                        \App\System::EventLogWrite('update,applicant_basic',json_encode($payment_data));

                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                                });

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



    /*******************************************
    # NotPaidApplicantPage
    ********************************************/
    public function NotPaidApplicantPage(){

        if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

                $applicant_list = \DB::table('applicant_basic')
                    ->where('payment_status','0')
                    ->where(function($query){

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

                    ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                    ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                    ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                    ->leftJoin('applicant_fees_transaction','applicant_fees_transaction.applicant_tran_code','like','applicant_basic.applicant_tran_code')
                    ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title','applicant_fees_transaction.applicant_fees_amount')
                    ->orderBy('applicant_basic.created_at','desc')
                    ->get();
                    $data['applicant_list']= $applicant_list;

                }else{

                     $applicant_list = \DB::table('applicant_basic')
                        ->where('payment_status','0')
                        ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->leftJoin('applicant_fees_transaction','applicant_fees_transaction.applicant_tran_code','like','applicant_basic.applicant_tran_code')
                        ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title','applicant_fees_transaction.applicant_fees_amount')
                        ->orderBy('applicant_basic.created_at','desc')->get();

                    $data['applicant_list']= $applicant_list;
                }


        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-not-paid-applicant-list',$data);

    }




    /********************************************
    ## NotPaidApplicantDelete
    *********************************************/

    public function NotPaidApplicantDelete($applicant_serial_no){

        $applicant_basic_info = \DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->first();
        if(!empty($applicant_basic_info)){

                    $applicant_contacts_info = \DB::table('applicant_contacts')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    $applicant_academic_info = \DB::table('applicant_academic')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    if(!empty($applicant_academic_info)){

                        $applicant_academic_result_detail_info = \DB::table('applicant_academic_result_detail')->where('applicant_academic_tran_code',$applicant_academic_info->applicant_academic_tran_code)->first();
                    }else $applicant_academic_result_detail_info='';

                    $applicant_fees_transaction_info = \DB::table('applicant_fees_transaction')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    $applicant_gurdians_info = \DB::table('applicant_gurdians')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    $applicant_personal_info = \DB::table('applicant_personal')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    $applicant_graduate_major_detail_info = \DB::table('applicant_graduate_major_detail')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();

                    $applicant_pro_experience_info= \DB::table('applicant_pro_experience')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->first();


            try{

                $success = \DB::transaction(function () use ($applicant_serial_no, $applicant_basic_info, $applicant_contacts_info, $applicant_academic_info, $applicant_academic_result_detail_info, $applicant_fees_transaction_info, $applicant_gurdians_info, $applicant_personal_info, $applicant_graduate_major_detail_info, $applicant_pro_experience_info) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();



                        $applicant_basic_delete = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->delete();

                        if(!$applicant_basic_delete){
                            $error=1;
                        }

                        if($applicant_contacts_info){

                            $applicant_contacts_delete = \DB::connection($this->dbList[$i])->table('applicant_contacts')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_contacts_delete){
                                $error=1;
                            }
                                
                        }
                        if($applicant_academic_info){

                            $applicant_academic_delete = \DB::connection($this->dbList[$i])->table('applicant_academic')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_academic_delete){
                                $error=1;
                            }
                        }
                        if($applicant_academic_result_detail_info){

                            $applicant_academic_result_detail_delete = \DB::connection($this->dbList[$i])->table('applicant_academic_result_detail')->where('applicant_academic_tran_code',$applicant_academic_info->applicant_academic_tran_code)->delete();

                            if(!$applicant_academic_result_detail_delete){
                                $error=1;
                            }
                        }
                        if($applicant_fees_transaction_info){

                            $applicant_fees_transaction_delete = \DB::connection($this->dbList[$i])->table('applicant_fees_transaction')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_fees_transaction_delete){
                                $error=1;
                            }
                        }
                        if($applicant_gurdians_info){

                            $applicant_gurdians_delete = \DB::connection($this->dbList[$i])->table('applicant_gurdians')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_gurdians_delete){
                                $error=1;
                            }
                        }
                        if($applicant_personal_info){

                            $applicant_personal_delete = \DB::connection($this->dbList[$i])->table('applicant_personal')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_personal_delete){
                                $error=1;
                            }
                        }
                        if($applicant_graduate_major_detail_info){

                            $applicant_graduate_major_detail_delete = \DB::connection($this->dbList[$i])->table('applicant_graduate_major_detail')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_graduate_major_detail_delete){
                                $error=1;
                            }
                        }
                        if($applicant_pro_experience_info){

                            $applicant_pro_experience_delete = \DB::connection($this->dbList[$i])->table('applicant_pro_experience')->where('applicant_tran_code',$applicant_basic_info->applicant_tran_code)->delete();

                            if(!$applicant_pro_experience_delete){
                                $error=1;
                            }
                        }

                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,applicant_contacts','deleted ID: '.$applicant_serial_no);

                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::back()->with('message','Applicant Deleted Successfully !!!');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch !!!');
            }
        } return \Redirect::back()->with('errormessage','Invalid id !!!!');


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
                        ->orderBy('applicant_basic.updated_at','desc')
                        ->paginate(10);

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
        
        $now=date('Y-m-d');

        if(isset($_GET['applicant_serial_no'])){

            $v = \App\Register::StudentAdmissionValidation(\Request::all());

            $applicant_serial_no = $_GET['applicant_serial_no'];


            if($v->passes()){

                $student_input['first_name'] =  \Request::input('first_name');
                $student_input['middle_name'] = \Request::input('middle_name');
                $student_input['last_name'] = \Request::input('last_name');
                $student_input['birth_city'] = \Request::input('birth_city');
                $student_input['program'] = \Request::input('program');
                $student_input['semester'] = \Request::input('semester');
                $student_input['academic_year'] = \Request::input('academic_year');
                $student_input['batch'] = \Request::input('batch');
                $student_input['birth_country'] = \Request::input('birth_country');
                $student_input['applicant_email'] = \Request::input('applicant_email');
                $student_input['applicant_phone'] = \Request::input('applicant_phone');
                $student_input['applicant_mobile'] = \Request::input('applicant_mobile');
                $student_input['nationality'] = \Request::input('nationality');
                $student_input['present_address_detail'] = \Request::input('present_address_detail');
                $student_input['present_postal_code'] = \Request::input('present_postal_code');
                $student_input['present_city'] = \Request::input('present_city');
                $student_input['present_country'] = \Request::input('present_country');
                $student_input['permanent_address_detail'] = \Request::input('permanent_address_detail');
                $student_input['permanent_postal_code'] = \Request::input('permanent_postal_code');
                $student_input['permanent_city'] = \Request::input('permanent_city');
                $student_input['permanent_country'] = \Request::input('permanent_country');
                $student_input['father_name'] = \Request::input('father_name');
                $student_input['father_occupation'] = \Request::input('father_occupation');
                $student_input['father_contact_email'] = \Request::input('father_contact_email');
                $student_input['father_contact_mobile'] = \Request::input('father_contact_mobile');
                $student_input['mother_name'] = \Request::input('mother_name');
                $student_input['mother_occupation'] = \Request::input('mother_occupation');
                $student_input['mother_contact_email'] = \Request::input('mother_contact_email');
                $student_input['mother_contact_mobile'] = \Request::input('mother_contact_mobile');
                $student_input['local_guardian_name'] = \Request::input('local_guardian_name');
                $student_input['local_guardian_occupation'] = \Request::input('local_guardian_occupation');
                $student_input['local_guardian_contact_mobile'] = \Request::input('local_guardian_contact_mobile');
                $student_input['local_guardian_contact_email'] = \Request::input('local_guardian_contact_email');
                $student_input['emergency_contact'] = \Request::input('emergency_contact');
                

                $applicant_info = \App\Applicant::ApplicantBasicInfo($applicant_serial_no);

                $accounts_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$applicant_info->program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','!=','application_form_fee')
                ->whereIn('accounts_fee_name_slug',array('tution_fee','trimester_fee'))
                ->select('accounts_fee_name_slug','accounts_fee_slug','accounts_fee_amount')
                ->get();

                $accounts_admission_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$applicant_info->program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','admission_fee')
                ->first();

                $accounts_tution_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$applicant_info->program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','tution_fee')
                ->first();

                $accounts_trimester_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$applicant_info->program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','trimester_fee')
                ->first();



                if(!empty($accounts_fee_deatails) && !empty($accounts_admission_fee_deatails) && !empty($accounts_tution_fee_deatails) && !empty($accounts_trimester_fee_deatails)){

                    try{

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
                                'program' =>$student_input['program'],
                                'semester' =>$student_input['semester'],
                                'academic_year' =>$student_input['academic_year'],
                                'batch_no' =>$student_input['batch'],
                                'student_image_url' =>$student_image_url,
                                'mobile' =>$student_input['applicant_mobile'],
                                'email' =>$student_input['applicant_email'],
                                'gender' =>$applicant_info->gender,
                                'religion' => $applicant_info->religion,
                                'student_status' =>1,
                                'student_details' =>'Admission Student',
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


                        /*------------------------Student Personal-----------------------------------*/

                        $student_personal_tran_code = \Uuid::generate(4);
                        $personal_form_data = array(
                            'student_personal_tran_code' =>$student_personal_tran_code->string,
                            'student_tran_code' =>$student_tran_code,
                            'date_of_birth'=>$applicant_info->date_of_birth,
                            'blood_group'=>$applicant_info->blood_group,
                            'place_of_birth' =>$student_input['birth_city'].','.$student_input['birth_country'],
                            'marital_status' =>$applicant_info->marital_status,
                            'nationality' =>$student_input['nationality'],
                            'phone' =>$student_input['applicant_phone'],
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->user_id,
                            'updated_by' =>\Auth::user()->user_id,
                            );


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



                        /*------------------------------student gurdianinfo ---------------------------------*/
                        $applicant_guardians = \App\Register::ApplicantGurdianInfo($applicant_serial_no);

                        if($student_input['emergency_contact']=='Father'){

                            $father_emergency='yes';
                            $mother_emergency='no';
                            $local_guardian_emergency='no';

                        }
                        elseif($student_input['emergency_contact']=='Mother'){

                            $father_emergency='no';
                            $mother_emergency='yes';
                            $local_guardian_emergency='no';
                        }
                        elseif($student_input['emergency_contact']=='Local_Guardian'){
                            $father_emergency='no';
                            $mother_emergency='no';
                            $local_guardian_emergency='yes';
                        }

                        $student_gurdians_tran_code_father = \Uuid::generate(4);
                        $gurdian_father = array(
                                'student_gurdians_tran_code' =>$student_gurdians_tran_code_father->string,
                                'student_tran_code' =>$student_tran_code,
                                'relation' => 'Father',
                                'gurdian_name' => $student_input['father_name'],
                                'occupation' =>$student_input['father_occupation'],
                                'mobile' =>$student_input['father_contact_mobile'],
                                'email' =>$student_input['father_contact_email'],
                                'emergency_contact' =>$father_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                            );


                   
                        $student_gurdians_tran_code_mother = \Uuid::generate(4);
                        $gurdian_mother = array(
                                'student_gurdians_tran_code' =>$student_gurdians_tran_code_mother->string,
                                'student_tran_code' =>$student_tran_code,
                                'relation' => 'Mother',
                                'gurdian_name' => $student_input['mother_name'],
                                'occupation' =>$student_input['mother_occupation'],
                                'mobile' =>$student_input['mother_contact_mobile'],
                                'email' =>$student_input['mother_contact_email'],
                                'emergency_contact' =>$mother_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                            );


                        $student_gurdians_tran_code_local_guardian = \Uuid::generate(4);
                        $local_gurdian = array(
                                'student_gurdians_tran_code' =>$student_gurdians_tran_code_local_guardian->string,
                                'student_tran_code' =>$student_tran_code,
                                'relation' => 'Local_Guardian',
                                'gurdian_name' =>$student_input['local_guardian_name'],
                                'occupation' =>$student_input['local_guardian_occupation'],
                                'mobile' =>$student_input['local_guardian_contact_mobile'],
                                'email' =>$student_input['local_guardian_contact_email'],
                                'emergency_contact' =>$local_guardian_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                            );

                        /*-------------student qualification ----------------*/
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
                                        'institute_name' => $applicant->institute_name,
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

                                    $success = \DB::transaction(function () use ($academic_data) {

                                        for($i=0; $i<count($this->dbList); $i++){
                                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                                            $academic_insert = \DB::connection($this->dbList[$i])->table('student_academic_qualification')->insert($academic_data);

                                            if(!$academic_insert){
                                                $error=1;
                                            }
                                        }

                                        if(!isset($error)){
                                            \App\System::TransactionCommit();

                                            \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($academic_data));
                                            
                                        }else{
                                            \App\System::TransactionRollback();
                                            throw new Exception("Error Processing Request", 1);
                                        }
                                    });

                                }catch(\Exception $e){
                                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                    \App\System::ErrorLogWrite($message);
                                    return \Redirect::back()->with('errormessage','Something wrong in catch!!');

                                }
                                                        
                            }
                        }


                        #----student accounts info insert----#
                        
                        $accounts_info_tran_code=\Uuid::generate(4);
                        $program_details=\DB::table('univ_program')->where('program_id',$applicant_info->program)->first();
                        

                        $accounts_fee_deatail=serialize($accounts_fee_deatails);

                        (float)$total_fees=0;
                        (float)$total_tution_fee=0;
                        (float)$total_trimester_fee=0;
                        (float)$total_credit=$program_details->program_total_credit_hours;
                        $program_duration_in_year=$program_details->program_duration;
                        $program_total_term=(float)$program_duration_in_year*3;     

                        if(!empty($accounts_fee_deatails)){
                            foreach ($accounts_fee_deatails as $key => $accounts) {

                                if($accounts->accounts_fee_name_slug=='tution_fee'){
                                    $total_tution_fee=(float)$accounts->accounts_fee_amount*$total_credit;
                                }
                                if($accounts->accounts_fee_name_slug == 'trimester_fee'){
                                    $total_trimester_fee=(float)$accounts->accounts_fee_amount*$program_total_term;
                                }

                            }
                        }
                        if(!empty($accounts_admission_fee_deatails)){

                            if((($accounts_admission_fee_deatails->accounts_fee_name_slug) =='admission_fee')){
                                $total_fees=$total_fees+(float)$accounts_admission_fee_deatails->accounts_fee_amount;
                            }

                        }



                        $accounts_total_fees=$total_tution_fee+$total_trimester_fee+$total_fees;

                        if(!empty(\Request::input('waiver_type'))){
                            $waiver_type = \Request::input('waiver_type');
                        }else{
                           $waiver_type = ''; 
                        }

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



                                $success = \DB::transaction(function () use ($basic_data, $register_data, $personal_form_data, $contact_peresent, $contact_permanent, $gurdian_father, $gurdian_mother, $local_gurdian, $student_accounts_info_data, $applicant_serial_no) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                        $basic_table_update=\DB::connection($this->dbList[$i])->table('student_basic')->where('applicant_serial_no',$applicant_serial_no)->update($basic_data);

                                        $update_applicant = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($register_data);

                                        $personal_form_data_insert = \DB::connection($this->dbList[$i])->table('student_personal')->insert($personal_form_data);

                                        $contact_peresent_insert = \DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_peresent);

                                        $contact_permanent_insert = \DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_permanent);

                                        $gurdian_father_insert = \DB::connection($this->dbList[$i])->table('student_gurdians')->insert($gurdian_father);

                                        $gurdian_mother_insert = \DB::connection($this->dbList[$i])->table('student_gurdians')->insert($gurdian_mother);

                                        $local_gurdian_insert = \DB::connection($this->dbList[$i])->table('student_gurdians')->insert($local_gurdian);

                                        $student_accounts_info=\DB::connection($this->dbList[$i])->table('student_accounts_info')->insert($student_accounts_info_data);

                                        if((!$basic_table_update) || (!$update_applicant) || (!$personal_form_data_insert) || (!$contact_peresent_insert) || (!$contact_permanent_insert) || (!$gurdian_father_insert) || (!$gurdian_mother_insert) || (!$student_accounts_info) || (!$local_gurdian_insert)){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){


                                        \App\System::EventLogWrite('update,student_basic',json_encode($basic_data));
                                        \App\System::EventLogWrite('update,applicant_basic',json_encode($register_data));
                                        \App\System::EventLogWrite('insert,student_personal',json_encode($personal_form_data));

                                        \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_peresent));

                                        \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_permanent));

                                        \App\System::EventLogWrite('insert,student_gurdians',json_encode($gurdian_father));

                                        \App\System::EventLogWrite('insert,student_gurdians',json_encode($gurdian_mother));

                                        \App\System::EventLogWrite('insert,student_gurdians',json_encode($local_gurdian));

                                        \App\System::EventLogWrite('insert,student_accounts_info',json_encode($student_accounts_info_data));

                                        \App\System::TransactionCommit();

                                        // \Session::flash('message','Data has been Saved Successfully');

                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                               });


                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
                    }


                    return \Redirect::to('/register/admission/confirm')->with('message','Student ('.$student_serial_no.') has been Successfully Registered.');

                }else return \Redirect::back()->with('message',"Program Accounts Plan Not Set Yet !");

            }else return \Redirect::to('/register/admission/confirm?applicant_serial_no='.$_GET['applicant_serial_no'])->withInput(\Request::all())->withErrors($v->messages());

        }else return \Redirect::to('/register/admission/confirm')->with('errormessage','Something wrong !!');
        
    }


    /********************************************
    ## ApplicantRejectReasonSubmit
    *********************************************/

    public function ApplicantRejectReasonSubmit(){

        $data['page_title'] = $this->page_title;

        $rules=array(
            'reject_reason' => 'Required',
            );

        $v=\Validator::make(\Request::all(),$rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $reject_reason = \Request::input('reject_reason');
            $applicant_serial_no = \Request::input('applicant_serial_no');

            $applicant_basic_form = array(
                'reject_reason'=> \Request::input('reject_reason'),
                'updated_at' => $now,
                'updated_by' =>\Auth::user()->user_id,
                );

                try{

                    $success = \DB::transaction(function () use ($applicant_basic_form, $applicant_serial_no) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $save_reject_reason=\DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no', $applicant_serial_no)->update($applicant_basic_form);


                            if(!$save_reject_reason){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('update,applicant_basic',json_encode($applicant_basic_form));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/admission/confirm')->with('message','Something wrong !!');
                }


            return \Redirect::to('/register/admission/confirm')->with('message','Applicant reject reason added successfully.');
        }else return \Redirect::to('/register/admission/confirm')->withInput(\Request::all())->withErrors($v->messages());
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

         $faculty_serial = str_pad($facult_last_number, 3,0,STR_PAD_LEFT);
         $faculty_join_year = date('y',strtotime(\Request::input('faculty_join_date')));
         $faculty_id = $faculty_join_year.$department.$faculty_serial;

         $faculty_tran_code =\Uuid::generate(4);
         $faculty_image_url = \App\Register::FacultyImageUrl($department,$faculty_id,\Request::input('image_url'));

    
            $faculty_basic_form = array(
                'faculty_tran_code' => $faculty_tran_code->string,
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


                    $success = \DB::transaction(function () use ($faculty_basic_form, $faculty_contacts_form) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $save_new_faculty=\DB::connection($this->dbList[$i])->table('faculty_basic')->insert($faculty_basic_form);

                            $save_new_faculty_contracts=\DB::connection($this->dbList[$i])->table('faculty_contacts')->insert($faculty_contacts_form);

                            if(!$save_new_faculty || !$save_new_faculty_contracts){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('insert,faculty_basic',json_encode($faculty_basic_form));

                            \App\System::EventLogWrite('insert,faculty_contacts',json_encode($faculty_contacts_form));

                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

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

        $maxwidth = 1500;
        $maxheight = 1500;

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

         // $emlpoyee_serial = str_pad(($employee_last_number+1), 3,0,STR_PAD_LEFT);
         $emlpoyee_serial = str_pad(($employee_last_number), 3,0,STR_PAD_LEFT);
         $employee_join_year = date('y',strtotime(\Request::input('employee_join_date')));
         $employee_id = $employee_join_year.$emlpoyee_serial;


         $employee_tran_code =\Uuid::generate(4);
         $employee_image_url = \App\Register::EmployeeImageUrl($employee_id,\Request::input('image_url'));

    
          $employee_basic_form = array(
                'employee_tran_code' => $employee_tran_code->string,
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



                    $success = \DB::transaction(function () use ($employee_basic_form, $employee_contacts_form) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $save_new_employee_basic=\DB::connection($this->dbList[$i])->table('employee_basic')->insert($employee_basic_form);

                            $save_new_employee_contracts=\DB::connection($this->dbList[$i])->table('employee_contacts')->insert($employee_contacts_form);

                            if(!$save_new_employee_basic || !$save_new_employee_contracts){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('insert',json_encode($employee_basic_form));

                            \App\System::EventLogWrite('insert',json_encode($employee_contacts_form));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });


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

        if(isset($_GET['program']) && isset($_GET['batch_no'])){

            $student_list = \DB::table('student_basic')
            ->where(function($query){

                 if(isset($_GET['program'])&&($_GET['program'] !=0)){
                    $query->where(function ($q){
                        $q->where('program', $_GET['program']);
                    });
                }

                if(isset($_GET['batch_no'])){
                    $query->where(function ($q){
                        $q->where('batch_no', $_GET['batch_no']);
                    });
                }

            })
            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->select('student_basic.*','univ_program.*')
            ->get();

            $data['student_list']= $student_list;

            $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))
            ->groupBy('batch_no')->get();
            
            $data['batch_list']= $batch_list;
        }

        $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))
            ->groupBy('batch_no')->get();

        $data['batch_list']= $batch_list;

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

        $change_user_status=array(
            'status' => '1',
            'updated_at'=> $now,
            );

        try{


            $success = \DB::transaction(function () use ($student_block_data, $student_serial_list, $change_user_status) {

                for($i=0; $i<count($this->dbList); $i++){
                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $block_student_confirm=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_serial_list)->update($student_block_data);

                    if(!$block_student_confirm){
                        $error=1;
                    }

                    $user_account_info=\DB::table('users')
                                    ->where('user_id',$student_serial_list)
                                    ->first();
                    if(!empty($user_account_info)){
                        $user_status_update=\DB::connection($this->dbList[$i])->table('users')->where('user_id',$student_serial_list)->update($change_user_status);
                        if(!$user_status_update){
                            $error=1;
                        }
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();

                    \App\System::EventLogWrite('update,student_basic',json_encode($student_block_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

            return \Redirect::back()->with('message','Success !!');

        }catch(\Exception  $e){
           $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
           \App\System::ErrorLogWrite($message);

           return \Redirect::back()->with('message','Something wrong !!');
       }
    }



    /********************************************
    ## Student Block With Reason 
    *********************************************/
    public function StudentBlockWithReason(){


        $rules=array(
            'block_reason' => 'Required',
            'student_serial_no' => 'Required',
            );

        $v=\Validator::make(\Request::all(),$rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $student_serial_no=\Request::input('student_serial_no');
            $block_reason=\Request::input('block_reason');
            $action=\Request::input('action');
            $student_block_data=array(
                'student_status' =>$action,
                'block_reason' =>$block_reason,
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->user_id,
                );   

            $change_user_status=array(
                'status' => $action,
                'updated_at'=> $now,
                );

            try{

                $success = \DB::transaction(function () use ($change_user_status, $student_block_data, $student_serial_no) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $block_student_confirm=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_serial_no)->update($student_block_data);

                        if(!$block_student_confirm){
                            $error=1;
                        }

                        $user_account_info=\DB::table('users')
                                        ->where('user_id',$student_serial_no)
                                        ->first();
                        if(!empty($user_account_info)){
                            $user_status_update=\DB::connection($this->dbList[$i])->table('users')->where('user_id',$student_serial_no)->update($change_user_status);
                            if(!$user_status_update){
                                $error=1;
                            }
                        }

                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('update,student_basic',json_encode($student_block_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::back()->with('message','Successfully Blocked !!');

            }catch(\Exception  $e){
               $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
               \App\System::ErrorLogWrite($message);

               return \Redirect::back()->with('message','Something wrong !!');
           }
        }else return \Redirect::back()->withErrors($v->messages());
    }





     /********************************************
    ## BlockStudentList
    *********************************************/
    public function BlockStudentList(){
        $data['page_title'] = $this->page_title;

        if(isset($_GET['program']) || isset($_GET['batch_no'])){

            $student_list = \DB::table('student_basic')
            ->where('student_basic.student_status','-5')
            ->where(function($query){

                if(isset($_GET['program'])&&($_GET['program'] !=0)){
                    $query->where(function ($q){
                        $q->where('program', $_GET['program']);
                    });
                }

                if(isset($_GET['batch_no'])&&($_GET['batch_no'] !=0)){
                    $query->where(function ($q){
                        $q->where('batch_no', $_GET['batch_no']);
                    });
                }

            })
            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->select('student_basic.*','univ_program.*')
            ->get();

            $data['student_list']= $student_list;

            $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))
            ->groupBy('batch_no')->get();
            
            $data['batch_list']= $batch_list;
        }

        else{

            $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))
            ->groupBy('batch_no')->get();

            $data['batch_list']= $batch_list;


            $student_list = \DB::table('student_basic')
            ->where('student_basic.student_status','-5')
            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->select('student_basic.*','univ_program.*')
            ->get();

            $data['student_list']= $student_list;
        }

        return \View::make('pages.register.register-block-student-list',$data);
    }




    /********************************************
    ## RegisterAcademicCalender
    *********************************************/
    public function RegisterAcademicCalender(){
        $calender_list =\DB::table('univ_academic_calender')
                        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','like','univ_semester.semester_code')
                        ->select('univ_academic_calender.*','univ_semester.*')
                        ->orderBy('univ_academic_calender.created_at','desc')
                        ->paginate(10);

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
                                'academic_calender_tran_code' => $uuid->string,
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

                $academic_calender_info=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('created_at','desc')->get();


                try{

                    $success = \DB::transaction(function () use ($academic_calender_data, $last_semester_status_update) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                                $academic_calender_update=\DB::connection($this->dbList[$i])->table('univ_academic_calender')->where('academic_calender_status',1)->update($last_semester_status_update);

                                $academic_calender_insert=\DB::connection($this->dbList[$i])->table('univ_academic_calender')->insert($academic_calender_data);


                            if(!$academic_calender_insert){
                                $error=1;
                            }
                        }

                        if(!isset($error)){

                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,univ_academic_calender',json_encode($academic_calender_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

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

        try{
            $calender_info=\DB::table('univ_academic_calender')->where('academic_calender_tran_code', $academic_calender_tran_code)->first();
            if(!empty($calender_info)){

                $student_study_level_info=\DB::table('student_study_level')
                    ->where('study_level_year', $calender_info->academic_calender_year)
                    ->where('study_level_semester', $calender_info->academic_calender_semester)
                    ->first();

                if(empty($student_study_level_info)){

                    $success = \DB::transaction(function () use ($academic_calender_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $calender_data=\DB::connection($this->dbList[$i])->table('univ_academic_calender')->where('academic_calender_tran_code', $academic_calender_tran_code)->delete();

                            if(!$calender_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('delete,univ_academic_calender',json_encode($academic_calender_tran_code));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/register/academic-calender-registration')->with('message'," Deleted Successfully!");
                }else return \Redirect::to('/register/academic-calender-registration')->with('errormessage'," This calender are using in academic semester!");
            }else return \Redirect::to('/register/academic-calender-registration')->with('errormessage'," Invalid Id!");

        }catch(\Exception  $e){
           $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
           \App\System::ErrorLogWrite($message);

           return \Redirect::to('/register/academic-calender-registration')->with('message','Something wrong !!');
       }
    }



    /*******************************************
    # EditAcademicCalender
    ********************************************/
    public function EditAcademicCalender($academic_calender_tran_code){

            $edit_academic_calender=\DB::table('univ_academic_calender')
                                    ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','like','univ_semester.semester_code')
                                    ->where('academic_calender_tran_code',$academic_calender_tran_code)
                                    ->orderBy('univ_academic_calender.created_at','desc')
                                    ->first();

            if(!empty(($edit_academic_calender->academic_calender_status) == '1')){
                if(!empty($edit_academic_calender)){
                    $data['page_title'] = $this->page_title;
                    $data['edit_academic_calender']=$edit_academic_calender;
                
                    $calender_info=\DB::table('univ_academic_calender')->where('academic_calender_tran_code', $academic_calender_tran_code)->first();

                    $student_study_level_info=\DB::table('student_study_level')
                            ->where('study_level_year', $calender_info->academic_calender_year)
                            ->where('study_level_semester', $calender_info->academic_calender_semester)
                            ->first();

                    $data['student_study_level_info']=$student_study_level_info;


                    return \View::make('pages.register.register-academic-calender-edit',$data);

                }else return \Redirect::to('/register/academic-calender-registration')->with('errormessage',"Invalid Notice");
            }else return \Redirect::to('/register/academic-calender-registration')->with('errormessage',"You can not edit this calender.");
        
    }




    /********************************************
    # UpdateAcademicCalender
    *********************************************/
    public function UpdateAcademicCalender($academic_calender_tran_code){
        


        $v = \App\Register::AcademicCalenderFormValidation(\Request::all());
        if($v->passes()){

            $calender_info=\DB::table('univ_academic_calender')->where('academic_calender_tran_code', $academic_calender_tran_code)->first();
            
            $student_study_level_info=\DB::table('student_study_level')
                    ->where('study_level_year', $calender_info->academic_calender_year)
                    ->where('study_level_semester', $calender_info->academic_calender_semester)
                    ->first();

            $now=date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;

            if(empty($student_study_level_info)){ 

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

            }else {

                $update_calender_data = [
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
            }

                    try{

                        $success = \DB::transaction(function () use ($update_calender_data, $academic_calender_tran_code) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_data = \DB::connection($this->dbList[$i])->table('univ_academic_calender')->where('academic_calender_tran_code',$academic_calender_tran_code)->update($update_calender_data);

                                if(!$update_data){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();

                                \App\System::EventLogWrite('update,univ_academic_calender',json_encode($update_calender_data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/academic-calender-registration')->with('message','Something wrong !!');
                    }

                if(empty($student_study_level_info)){ 
                    return \Redirect::to('/register/academic-calender-registration')->with('message','Academic Calender has been updated successfully.');
                }else{
                    return \Redirect::to('/register/academic-calender-registration')->with('message','Academic Calender has been updated successfully. Except academic year and trimester.');
                }


        }else return \Redirect::back()->withInput()->withErrors($v->messages());



    }





    /********************************************
    ## ProgramCoordintorAssign
    *********************************************/
    public function ProgramCoordintorAssign(){

        $calender_info=\DB::table('univ_academic_calender')
            ->where('academic_calender_status', '1')->orderBy('created_at','desc')->first();
        if(!empty($calender_info)){
                $data['semester_list']=\DB::table('univ_semester')->get();
                $data['year_info']=$calender_info->academic_calender_year;


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
                /*-----------------/Get Request--------------------*/
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
        }return Redirect::back()->with('errormessage','Please create academic calender.');
           
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
                'program_coordinator_tran_code' => $program_coordinator_tran_code->string,
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

                $success = \DB::transaction(function () use ($program_coordinator_assigned_form) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $save_new_program_coordinator=\DB::connection($this->dbList[$i])->table('program_coordinator_assigned')->insert($program_coordinator_assigned_form);

                        if(!$save_new_program_coordinator){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('insert,program_coordinator_assigned',json_encode($program_coordinator_assigned_form));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

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

        $faculty_list = \DB::table('faculty_basic')->where('faculty_status','2')->where('department',$department)->get();

        $data['faculty_list']= $faculty_list;
        return \View::make('pages.register.ajax-faculty-list',$data);
    }



    
    /********************************************
    # ProgramCoordinetorEdit
    *********************************************/
    public function ProgramCoordinatorEdit($program_coordinator_tran_code){

        $calender_info=\DB::table('univ_academic_calender')
            ->where('academic_calender_status', '1')->orderBy('created_at','desc')->first();
        if(!empty($calender_info)){
                $data['semester_list']=\DB::table('univ_semester')->get();
                $data['year_info']=$calender_info->academic_calender_year;


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
        }return Redirect::back()->with('errormessage','Please create academic calender.');
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

            $calender_info=\DB::table('univ_academic_calender')
                ->where('academic_calender_status', '1')->first();

            if(!empty($calender_info)){

                $program_coordinator_info=\DB::table('program_coordinator_assigned')
                    ->where('program_coordinator_tran_code',$program_coordinator_tran_code)
                    ->where('program_coordinator_semester',$calender_info->academic_calender_semester)
                    ->where('program_coordinator_year',$calender_info->academic_calender_year)
                    ->first();

                if(!empty($program_coordinator_info)){



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

                        $success = \DB::transaction(function () use ($program_coordinator_update_form, $program_coordinator_tran_code) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $program_coordinator_update_info=\DB::connection($this->dbList[$i])->table('program_coordinator_assigned')->where('program_coordinator_tran_code',$program_coordinator_tran_code)->update($program_coordinator_update_form);

                                if(!$program_coordinator_update_info){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();

                                \App\System::EventLogWrite('insert,program_coordinator_assigned',json_encode($program_coordinator_update_form));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                        return \Redirect::to('/register/class-teacher-assign')->with('message','Class Teacher has been updated successfully');


                    }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/register/class-teacher-assign')->with('message','Something wrong in catch !!!');

                    }

                }else return \Redirect::to('/register/class-teacher-assign')->with('errormessage',"You can not update this because it is previous semester data.");
            }else return \Redirect::to('/register/class-teacher-assign')->with('errormessage',"Please create academic calendar.");


        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    # ProgramCoordinatorDelete
    *********************************************/
    public function ProgramCoordinatorDelete($program_coordinator_tran_code){

        
        $calender_info=\DB::table('univ_academic_calender')
            ->where('academic_calender_status', '1')->first();

        if(!empty($calender_info)){

            $program_coordinator_info=\DB::table('program_coordinator_assigned')
                ->where('program_coordinator_tran_code',$program_coordinator_tran_code)
                ->where('program_coordinator_semester',$calender_info->academic_calender_semester)
                ->where('program_coordinator_year',$calender_info->academic_calender_year)
                ->first();

            if(!empty($program_coordinator_info)){
        
                try{

                    $success = \DB::transaction(function () use ($program_coordinator_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $data=\DB::connection($this->dbList[$i])->table('program_coordinator_assigned')->where('program_coordinator_tran_code',$program_coordinator_tran_code)->delete();

                            if(!$data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('delete,program_coordinator_assigned',json_encode($program_coordinator_tran_code));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/register/class-teacher-assign')->with('message',"Class Teacher Deleted Successfully!");


                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/register/class-teacher-assign')->with('message',"Something Wrong in catch !!");
                }


            }else return \Redirect::back()->with('errormessage',"You can not delete this because it is previous semester data.");
        }else return \Redirect::back()->with('errormessage',"Please create academic calendar.");
    }




    /********************************************
    ## FacultyAssignedCourse
    *********************************************/
    public function FacultyAssignedCourse(){
        $data['page_title'] = $this->page_title;

        $data['program']=\App\Register::ProgramList();
        $calender_info=\DB::table('univ_academic_calender')
            ->where('academic_calender_status', '1')->orderBy('created_at','desc')->first();
        if(!empty($calender_info)){
            $data['semester']=\DB::table('univ_semester')->where('semester_code',$calender_info->academic_calender_semester)->get();
            $data['year_info']=$calender_info->academic_calender_year;


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

            $faculty=\DB::table('faculty_basic')->where('faculty_status','2')->get();
            $data['faculty']=$faculty;
            return \View::make('pages.register.register-faculty-assigned-course',$data);
        }return \Redirect::back()->with('errormessage','please create academic calender.');
    }


    /********************************************
    ## FacultyAssignedCourseSubmit
    *********************************************/
    public function FacultyAssignedCourseSubmit($action){

        if($action=='delete'){

            $assigned_course_tran_code=\Request::input('assigned_course_tran_code');
            $course_code=\Request::input('course_code');
            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

             $assign_course_info=\DB::table('faculty_assingned_course')
                ->where('assigned_course_tran_code', $assigned_course_tran_code)
                ->where('assigned_course_semester', $univ_academic_calender->academic_calender_semester)
                ->where('assigned_course_year', $univ_academic_calender->academic_calender_year)
                ->first();
            if(!empty($assign_course_info)){

                try{

                        $success = \DB::transaction(function () use ($assigned_course_tran_code, $course_code) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $undone=\DB::connection($this->dbList[$i])->table('faculty_assingned_course')->where('assigned_course_tran_code', $assigned_course_tran_code)->delete();

                                if(!$undone){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();

                                \App\System::EventLogWrite('delete,faculty_assingned_course',json_encode($course_code));                            
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });



                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                }

                return \Redirect::back()->with('message',"Assigned Faculty Undone Successfully !");

            }else return \Redirect::back()->with('errormessage',"You can not undone this assign course !");


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

                    $success = \DB::transaction(function () use ($faculty_course_assign) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $faculty_course_assign_save=\DB::connection($this->dbList[$i])->table('faculty_assingned_course')->insert($faculty_course_assign);

                            if(!$faculty_course_assign_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();

                            \App\System::EventLogWrite('insert,faculty_assingned_course',json_encode($faculty_course_assign));                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::back()->with('message',"Course Assigned For Faculty Successfully !");


                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::back()->with('errommessage',"Something Wrong in catch !!");

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
        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->select('academic_calender_year', \DB::raw('count(*) as total'))
            ->groupBy('academic_calender_year')
            ->get();
        $data['univ_academic_calender']=$univ_academic_calender;

        $calender_info=\DB::table('univ_academic_calender')->where('academic_calender_status', '1')->orderBy('created_at','desc')->first();

        if(!empty($calender_info)){

            $data['semester_list']=\DB::table('univ_semester')->where('semester_code',$calender_info->academic_calender_semester)->get();
            $data['year_info']=$calender_info->academic_calender_year;

            
            $batch_list=\DB::table('student_basic')
                ->select('batch_no', \DB::raw('count(*) as total'))
                ->groupBy('batch_no')
                ->get();
            $data['batch_list']=$batch_list;

            if(isset($_GET['program']) && isset($_GET['batch_no'])){

                $all_student = \DB::table('student_basic')
                ->where('student_basic.student_status','>',0)
                ->where(function($query){
                    if(isset($_GET['program']) && ($_GET['program'] != 0)){
                        $query->where(function ($q){
                            $q->where('program', $_GET['program']);
                        });
                    }

                    if(isset($_GET['batch_no'])){
                        $query->where(function ($q){
                            $q->where('batch_no', $_GET['batch_no']);
                        });
                    }
                    // if(isset($_GET['semester']) && ($_GET['semester'] != 0)){
                    //     $query->where(function ($q){
                    //         $q->where('semester', $_GET['semester']);
                    //     });
                    // }
                    // if(isset($_GET['academic_year']) && ($_GET['academic_year'] != 0)){
                    //     $query->where(function ($q){
                    //         $q->where('academic_year', $_GET['academic_year']);
                    //     });
                    // }

                }) 
                ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                ->get();

                $data['all_student']=$all_student;


           }
            return \View::make('pages.register.register-trimester-student-assign',$data);
        }return \Redirect::back()->with('errormessage','Please create academic calender.');
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
                    try{

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

                                $success = \DB::transaction(function () use ($student_study_level_data) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                        $student_study_level_store=\DB::connection($this->dbList[$i])->table('student_study_level')->insert($student_study_level_data);

                                        if(!$student_study_level_store){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::TransactionCommit();

                                        \App\System::EventLogWrite('insert,student_study_level',json_encode($student_study_level_data));                                
                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                                });



                        }

                        return \Redirect::back()->with('message',"Student Assigned Successfully for Trimester {$univ_semester->semester_title} {$year} !");
                    }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errommessage',"Something Wrong in catch !!");

                    }

                }else  return \Redirect::back()->with('message',"No Student Selected !");
                

        }else  return \Redirect::back()->with('message',"Select Study Level Trimester and Year !");


    }


    /********************************************
    ## TimeSlot
    *********************************************/

    public function TimeSlot(){

        $data['page_title'] = $this->page_title;

        $time_slot=\DB::table('univ_time_slot')
                ->orderBy('univ_time_slot.created_at','desc')
                ->paginate(5);

        $time_slot->setPath(url('/register/univ-time-slot'));
        $time_pagination = $time_slot->render();
        $data['time_pagination'] = $time_pagination;

        $data['univ_time_slot']=$time_slot;

        return \View::make('pages.register.register-time-slot',$data);
    }


    /********************************************
    ## TimeSlotSubmit
    *********************************************/

    public function TimeSlotSubmit(){

        $date = \DateTime::createFromFormat('U.u', microtime(TRUE));
        $st_d= $date->format('Y-m-d H:i:s.u'); 

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

                $success = \DB::transaction(function () use ($time_slot_data) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $time_slot_save=\DB::connection($this->dbList[$i])->table('univ_time_slot')->insert($time_slot_data);

                        if(!$time_slot_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('insert,univ_time_slot',json_encode($time_slot_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

          
                $end_d= $date->format('Y-m-d H:i:s.u'); 

                $message = date('Y-m-d H:i:s u').'->>Start: '.$st_d.'| End:'.$end_d;

                \App\System::CustomLogWritter("systemlog","time_log",$message);

                return \Redirect::to('/register/univ-time-slot')->with('message',"Time Slot Saved Successfully !");

            }catch(\Exception $e){

                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/univ-time-slot')->with('errormessage',"Something wrong in catch !!");

            }

        }else  return \Redirect::to('/register/univ-time-slot')->withErrors($v->messages());

    }



    /********************************************
    ## TimeSlotDelete
    *********************************************/

    public function TimeSlotDelete($time_slot_tran_code){

        if(!empty($time_slot_tran_code)){
            
            try{

                $success = \DB::transaction(function () use ($time_slot_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $time_slot_delete=\DB::connection($this->dbList[$i])->table('univ_time_slot')->where('univ_time_slot_tran_code', $time_slot_tran_code)->delete();


                        if(!$time_slot_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('delete,univ_time_slot',json_encode($time_slot_tran_code));
                                                    
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/register/univ-time-slot')->with('message',"Time Slot Deleted Successfully !");


            }catch(\Exception $e){

                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                return \Redirect::to('/register/univ-time-slot')->with('errormessage',"Something wrong in catch !!");

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

        $available_faculties=\DB::table('faculty_basic')->where('faculty_status','2')->get();
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

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('created_at','desc')->first();

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


                $success = \DB::transaction(function () use ($class_schedule) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $class_schedule_save=\DB::connection($this->dbList[$i])->table('univ_class_schedule')->insert($class_schedule);


                        if(!$class_schedule_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,univ_class_schedule',json_encode($class_schedule));
                           
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/register/class-schedule')->with('message',"Class schedule has been added successfully !");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/class-schedule')->with('message',"Something went wrong in catch !");
            }


        }else  return \Redirect::back()->with('message',"Class schedule not added !!");

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


                $success = \DB::transaction(function () use ($schedule_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $schedule_delete=\DB::connection($this->dbList[$i])->table('univ_class_schedule')->where('class_schedule_tran_code', $schedule_tran_code)->delete();


                        if(!$schedule_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_class_schedule', $schedule_tran_code);
                                                    
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });
                return \Redirect::back()->with('message',"Schedule Deleted Successfully !");


        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('errormessage',"Something Wrong in catch");
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

        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)
            ->leftJoin('univ_semester','univ_semester.semester_code','=','univ_academic_calender.academic_calender_semester')
            ->orderBy('univ_academic_calender.created_at','desc')
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

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

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

                        $success = \DB::transaction(function () use ($exam_schedule) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $exam_schedule_save=\DB::connection($this->dbList[$i])->table('univ_exam_schedule')->insert($exam_schedule);

                                if(!$exam_schedule_save){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_exam_schedule',json_encode($exam_schedule));
                                                            
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


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
    ## ExamScheduleDelete
    *********************************************/

     public function ExamScheduleDelete($exam_schedule_tran_code){

        if(!empty($exam_schedule_tran_code)){

            try{


                $success = \DB::transaction(function () use ($exam_schedule_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $schedule_delete=\DB::connection($this->dbList[$i])->table('univ_exam_schedule')->where('exam_schedule_tran_code', $exam_schedule_tran_code)->delete();


                        if(!$schedule_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete, univ_exam_schedule', $exam_schedule_tran_code);
                                                    
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });
                return \Redirect::back()->with('message',"Exam Schedule Deleted Successfully !");


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something Wrong in catch");
            }

        }else return \Redirect::back()->with('message',"Problem Finding Schedule !");

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

            try{
                $uuid = \Uuid::generate(4);
                $from_type=\Auth::user()->user_type;
                $academic_calender=\DB::table('univ_academic_calender')->orderBy('univ_academic_calender.created_at','desc')->first();
                $now = date('Y-m-d H:i:s');
                $faculty_notice_data = [
                                'notice_tran_code' => $uuid->string,
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


                        $success = \DB::transaction(function () use ($faculty_notice_data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $faculty_notice_save=\DB::connection($this->dbList[$i])->table('univ_notice_board')->insert($faculty_notice_data);


                                if(!$faculty_notice_save){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_notice_board',json_encode($univ_notice_board));
                                                            
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });
                        return \Redirect::to('/register/notice-board?tab=faculty_notice')->with('message','Notice has been added.');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/notice-board?tab=faculty_notice')->with('message',"Something wrong in catch !!!");
            }

        }else return \Redirect::to('/register/notice-board?tab=faculty_notice')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    ## RegisterNoticeBoardSubmit 
    *********************************************/

    public function RegisterStudentNoticeBoardSubmit(){


        $student_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

        if(!empty($student_calender)){

            $now = date('Y-m-d H:i:s');

            $rule = [
            'notice_subject' => 'Required',
            'notice_message' => 'Required',
            'notice_to' => 'Required',
            ];

            $v = \Validator::make(\Request::all(),$rule);

            if($v->passes()){

                try{
                    $uuid = \Uuid::generate(4);
                    $from_type=\Auth::user()->user_type;

                    $student_notice_data = [
                                            'notice_tran_code' => $uuid->string,
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



                            $success = \DB::transaction(function () use ($student_notice_data) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $student_notice_save=\DB::connection($this->dbList[$i])->table('univ_notice_board')->insert($student_notice_data);

                                    if(!$student_notice_save){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('insert,univ_notice_board',json_encode($student_notice_data));
                                                                
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });
                            return \Redirect::to('/register/notice-board')->with('message','Notice has been added.');

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/notice-board?tab=student_notice')->with('message',"Something wrong in catch !!!");
                }


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

            try{
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



                    $success = \DB::transaction(function () use ($update_register_notice_data, $notice_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $update_data = \DB::connection($this->dbList[$i])->table('univ_notice_board')->where('notice_tran_code',$notice_tran_code)->update($update_register_notice_data);

                            if(!$update_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,univ_notice_board',json_encode($update_register_notice_data));
                                                        
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/register/notice-board')->with('message','Notice has been updated.');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/notice-board')->with('message',"Something wrong in catch !!!");
            }

        }else return \Redirect::to('/register/notice-board')->withInput(\Request::all())->withErrors($v->messages());

    }



    /********************************************
    ## RegisterNoticeDelete
    *********************************************/

    public function RegisterNoticeDelete($notice_tran_code){

            try{
                    $success = \DB::transaction(function () use ($notice_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $notice_data=\DB::connection($this->dbList[$i])->table('univ_notice_board')->where('notice_tran_code', $notice_tran_code)->delete();

                            if(!$notice_data){
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

                    return \Redirect::to('/register/notice-board')->with('message'," Deleted Successfully!");


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/register/notice-board')->with('message',"Something wrong in catch !!!");
            }
        
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
                        'grade_equivalent_tran_code' => $uuid->string,
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

                    $success = \DB::transaction(function () use ($grade_equivalent_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $update_data = \DB::connection($this->dbList[$i])->table('grade_equivalent')->insert($grade_equivalent_data);

                            if(!$update_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,grade_equivalent',json_encode($grade_equivalent_data));
                                                        
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/register/student-grade-equivalent')->with('message','Academic Calender has been added.');

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/student-grade-equivalent')->with('message','Something wrong in catch!!');
                }

                
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

                    $success = \DB::transaction(function () use ($grade_equivalent_update, $grade_equivalent_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $update_data = \DB::connection($this->dbList[$i])->table('grade_equivalent')->where('grade_equivalent_tran_code',$grade_equivalent_tran_code)->update($grade_equivalent_update);

                            if(!$update_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,grade_equivalent',json_encode($grade_equivalent_update));
                                                        
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });
                    return \Redirect::to('/register/student-grade-equivalent')->with('message','Academic Calender has been added.');


                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/register/student-grade-equivalent')->with('message','Something wrong in catch !!');
                }

            }else return \Redirect::to('/register/student-grade-equivalent')->withInput(\Request::all())->withErrors($v->messages());
    } 


    /********************************************
    ## StudentGradeEquivalentDelete
    *********************************************/

    public function StudentGradeEquivalentDelete($grade_equivalent_tran_code){

                try{

                    $success = \DB::transaction(function () use ($grade_equivalent_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $grade_data_delete=\DB::connection($this->dbList[$i])->table('grade_equivalent')->where('grade_equivalent_tran_code', $grade_equivalent_tran_code)->delete();

                            if(!$grade_data_delete){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('delete,grade_equivalent',json_encode($grade_equivalent_tran_code));
                                                        
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });
                    return \Redirect::to('/register/student-grade-equivalent')->with('message'," Deleted Successfully!");


                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/register/student-grade-equivalent')->with('message','Something wrong in catch !!');
                }

    }


    /********************************************
    ## RegisterStudentAttendanceListPage 
    *********************************************/

    public function RegisterStudentAttendanceListPage(){

        $data['page_title'] = $this->page_title;
        $all_students_attendance_info = array();


        if(isset($_GET['program']) && isset($_GET['semster']) && isset($_GET['academic_year']) && isset($_GET['course']) || isset($_GET['attendance_date_value'])){
                $course=$_GET['course'];
                $attendance_date =$_GET['attendance_date_value'];

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
                }else{

                    $all_student = \DB::table('student_lab_register')
                        //->where('student_lab_register.lab_result_status',0)
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
                    $all_students_attendance_info[]='';
                }
                $data['all_students_attendance_info']=$all_students_attendance_info;
                $data['all_student']=$all_student;

            }

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
            $attendance_date=\Request::input('attendance_date');

            if(!empty($student_serial_no)){
                $course_found=\DB::table('course_basic')->where('course_code',$course_code)->first();
                $course_type=$course_found->course_type;

                $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status','1')->orderBy('univ_academic_calender.created_at','desc')->first();


                $student_class_attendance_data_info=\DB::table('student_class_attendance')
                                                    ->where('attendance_student_id',$student_serial_no)
                                                    ->where('attendance_course_id',$course_code)
                                                    ->where('attendance_date',$attendance_date)
                                                    ->where('attendance_semester',$univ_academic_calender->academic_calender_semester)
                                                    ->where('attendance_year',$univ_academic_calender->academic_calender_year)
                                                    ->first();

                if(empty($student_class_attendance_data_info)){

                    if(!empty($univ_academic_calender)){

                        if($course_type=='Theory'){
                            foreach ($student_serial_no as $key => $stu){

                                $student_basic=\DB::table('student_basic')
                                    ->where('student_basic.student_serial_no', $stu)
                                    ->leftJoin('student_class_registers','student_basic.student_tran_code','like','student_class_registers.student_tran_code')
                                    ->where('student_class_registers.class_course_code',$course_code)
                                    //->where('student_class_registers.class_result_status', 0)
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
                                // return \Redirect::back()->with('errormessage',"Invalid Academic Calender.");
                            }return \Redirect::back()->with('message',"Student attendance stored Successfully!");
                            
                        }else{
                            foreach ($student_serial_no as $key => $student){
                                $student_basic=\DB::table('student_lab_register')
                                ->where('student_serial_no',$student)
                                ->where('student_lab_register.lab_course_code',$course_code)
                                ->where('student_lab_register.lab_result_status', 0)
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
                                        return \Redirect::to('/register/student/attendance/list')->with('message','Something wrong !!');
                                    }
                 
                                }
                                // return \Redirect::back()->with('errormessage',"Invalid Academic Calender.");
                            }return \Redirect::back()->with('message',"Student attendance stored Successfully!");
                
                        }
            
                    }return \Redirect::to('/register/student/attendance/list')->with('errormessage',"Academic Calender Not Set Yet !");
                }return \Redirect::to('/register/student/attendance/list')->with('errormessage', "Student attendance is already save !!!!");
            }else  return \Redirect::back()->with('message','Please Select Student ID !!');


        }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());


    }



    /********************************************
    ## RegisterStudentAttendancePercent
    *********************************************/
    public function RegisterStudentAttendancePercent(){

        $data['page_title'] = $this->page_title;
        $all_student_attendance_info = array();

        $univ_academic_calender=\DB::table('univ_academic_calender')
        ->select('academic_calender_year', \DB::raw('count(*) as total'))
        ->groupBy('academic_calender_year')
        ->get();
        $data['univ_academic_calender']=$univ_academic_calender;

        if(isset($_GET['program']) && isset($_GET['semester']) && isset($_GET['academic_year']) && isset($_GET['course'])){

                $program=$_GET['program'];
                $semester=$_GET['semester'];
                $academic_year=$_GET['academic_year'];
                $course=$_GET['course'];

                $course_found=\DB::table('course_basic')->where('course_code',$course)->first();
                if(!empty($course_found)){
                    $course_type=$course_found->course_type;
                }

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
                        $attendance_percentage=((int)$student_total_attendance_info*100)/$total_class_info;


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
        }

         return \View::make('pages.register.register-student-attendance-percent',$data);
    }

    
    /********************************************
    ## RegisterStudentCourseWithdraw
    *********************************************/

    public function RegisterStudentCourseWithdraw(){

        if(isset($_GET['student_no'])){
            $student_serial_no=$_GET['student_no'];
                $resent_data = \DB::table('univ_academic_calender')
                ->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();
                $present_year=$resent_data->academic_calender_year;
                $present_semester=$resent_data->academic_calender_semester;

                $all_student = \DB::table('student_academic_tabulation')
                ->where('student_academic_tabulation.tabulation_year',$present_year)
                ->where('student_academic_tabulation.tabulation_semester',$present_semester)
                ->where('student_academic_tabulation.tabulation_status',0)
                ->where('student_serial_no',$student_serial_no)
                ->leftJoin('univ_program','student_academic_tabulation.tabulation_program','=','univ_program.program_id')
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


        $rule = [
            'percentise' => 'Required|numeric',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $student_no=\Request::input('student_no');
            $student_course_no=\Request::input('course_no');
            $percentise=\Request::input('percentise');
            $parcent = (int)$percentise;

            $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->orderBy('univ_academic_calender.created_at','desc')->first();

            $student_accounts_info=\DB::table('student_payment_transactions')->where('payment_student_serial_no',$student_no)->where('payment_semster',$univ_academic_calender->academic_calender_semester)->where('payment_year',$univ_academic_calender->academic_calender_year)->where('payment_transaction_fee_type',"tution_fee")->where('payment_remarks','like',"Not Paid")->first();
            $new_recievable=$student_accounts_info->payment_receivable;


            $student_accounts_tution_fee_info=\DB::table('student_payment_transactions')
                ->where('payment_student_serial_no',$student_no)
                ->where('payment_semster',$univ_academic_calender->academic_calender_semester)
                ->where('payment_year',$univ_academic_calender->academic_calender_year)
                ->where('payment_transaction_fee_type',"tution_fee")
                ->where('payment_remarks','like',"Not Paid")
                ->count();

            if($percentise <= 100){
                if($student_accounts_tution_fee_info == 1){


                    if(!empty($univ_academic_calender)){

                        if(!empty($student_course_no)){

                                $new_other=0;
                                foreach ($student_course_no as $key => $course){
                                    $all_course_info=\DB::table('course_basic')->where('course_code',$course)->first();
                                    $withdraw_course_info[]=$course;
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
                                    $others_fee=$total_tution_fee-$return_fee;
                                    $new_other=$new_other+$others_fee;
                                    
                                    
                                    if(($student_accounts_info->payment_details) != null){
                                        $previous_others_info=explode(' ', ($student_accounts_info->payment_details));
                                        $previous_others_fees=(float)($previous_others_info[0]);
                                        $new_other=$new_other+$previous_others_fees;
                                    }

                                    $new_recievable=($new_recievable)-($total_tution_fee)+$others_fee;


                                    $stu_no=\DB::table('student_academic_tabulation')
                                        ->where('tabulation_course_id',$course)
                                        ->where('student_serial_no', $student_no)
                                        ->where('tabulation_semester',$univ_academic_calender->academic_calender_semester)
                                        ->where('tabulation_year',$univ_academic_calender->academic_calender_year)
                                        ->first();
                                    $class_register_tran_code=$stu_no->student_tran_code;
                                    $academic_course_type=$stu_no->tabulation_course_type;


                                    try{

                                        $success = \DB::transaction(function () use ($course, $student_no, $class_register_tran_code, $academic_course_type, $univ_academic_calender) {

                                            for($i=0; $i<count($this->dbList); $i++){
                                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $course_withdraw_data=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')
                                                ->where('tabulation_course_id', $course)
                                                ->where('student_serial_no', $student_no)
                                                ->where('tabulation_semester',$univ_academic_calender->academic_calender_semester)
                                                ->where('tabulation_year',$univ_academic_calender->academic_calender_year)
                                                ->delete();

                                                if($academic_course_type == 'Theory'){

                                                    $course_class_register_data=\DB::connection($this->dbList[$i])->table('student_class_registers')
                                                    ->where('class_course_code', $course)
                                                    ->where('student_tran_code', $class_register_tran_code)
                                                    ->where('class_semster',$univ_academic_calender->academic_calender_semester)
                                                    ->where('class_year',$univ_academic_calender->academic_calender_year)
                                                    ->delete();
                                                    if(!$course_class_register_data){
                                                        $error=1;
                                                    }
                                                }elseif($academic_course_type == 'Lab work'){
                                                    $course_lab_register_data=\DB::connection($this->dbList[$i])->table('student_lab_register')
                                                    ->where('lab_course_code', $course)
                                                    ->where('student_serial_no', $student_no)
                                                    ->where('lab_semster',$univ_academic_calender->academic_calender_semester)
                                                    ->where('lab_year',$univ_academic_calender->academic_calender_year)
                                                    ->delete();
                                                    if(!$course_lab_register_data){
                                                        $error=1;
                                                    }
                                                }

                                                if(!$course_withdraw_data){
                                                    $error=1;
                                                }
                                            }

                                            if(!isset($error)){
                                                \App\System::TransactionCommit();
                                                \App\System::EventLogWrite('delete,student_academic_tabulation',json_encode($course));
                                                if($academic_course_type == 'Theory'){

                                                    \App\System::EventLogWrite('delete,student_class_registers',json_encode($course));
                                                }elseif($academic_course_type == 'Lab work'){

                                                    \App\System::EventLogWrite('delete,student_lab_register',json_encode($course));
                                                }

                                            }else{
                                                \App\System::TransactionRollback();
                                                throw new Exception("Error Processing Request", 1);
                                            }
                                        });
                                    }catch(\Exception  $e){
                                         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                         \App\System::ErrorLogWrite($message);

                                        return \Redirect::to('/register/student/withdraw/course')->with('message','Something wrong in catch !!');
                                    }
                           
                                }
                                $all_withdraw_course_info='';
                                if(!empty($withdraw_course_info)){
                                    foreach ($withdraw_course_info as $key => $withdraw_course) {
                                        $all_withdraw_course_info=$all_withdraw_course_info.', '.$withdraw_course;
                                    }
                                }

                                    $update_fees_transaction=array(
                                        'payment_receivable' => $new_recievable,
                                        // 'payment_others'=>$new_other,
                                        'payment_details'=> $new_other.' tk is '.$all_withdraw_course_info.' course withdraw fine.',
                                        'updated_by' =>\Auth::user()->user_id,
                                        'updated_at' =>$now,
                                        );


                                try{

                                    $success = \DB::transaction(function () use ($update_fees_transaction, $student_no, $univ_academic_calender) {

                                        for($i=0; $i<count($this->dbList); $i++){
                                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                            $student_accounts_info=\DB::connection($this->dbList[$i])->table('student_payment_transactions')
                                            ->where('payment_student_serial_no',$student_no)
                                            ->where('payment_transaction_fee_type','tution_fee')
                                            ->where('payment_semster',$univ_academic_calender->academic_calender_semester)
                                            ->where('payment_year',$univ_academic_calender->academic_calender_year)
                                            ->update($update_fees_transaction);
                                            if(!$student_accounts_info){
                                                $error=1;
                                            }
                                        }

                                        if(!isset($error)){
                                            \App\System::TransactionCommit();
                                            \App\System::EventLogWrite('update,student_payment_transactions',json_encode($update_fees_transaction));

                                        }else{
                                            \App\System::TransactionRollback();
                                            throw new Exception("Error Processing Request", 1);
                                        }
                                    });

                                }catch(\Exception  $e){
                                     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                     \App\System::ErrorLogWrite($message);

                                    return \Redirect::to('/register/student/withdraw/course')->with('message','Something wrong !!');
                                }

                             return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"Student course withdraw successfully!");
                        }

                        else return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"Please Select Student course!"); 

                    }else return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"Invalid academic calendar!");
                }else return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"You can withdraw this because it has existing value.");
            }else return \Redirect::to('/register/student/withdraw/course?student_no='.\Request::input('student_no'))->with('message',"Percentise is to below 100.");
        }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

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
            'batch' => 'Required',
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
            $emergency_contact=\Request::input('emergency_contact');
            if($emergency_contact == 'Father'){
                $father_emergency='yes';
                $mother_emergency='no';
                $local_gurdian_emergency='no';
            }elseif($emergency_contact == 'Mother'){
                $father_emergency='no';
                $mother_emergency='yes';
                $local_gurdian_emergency='no';
            }elseif($emergency_contact == 'Local_Guardian'){
                $father_emergency='no';
                $mother_emergency='no';
                $local_gurdian_emergency='yes';
            }


            $accounts_tution_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','tution_fee')
                ->first();

            $accounts_trimester_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','trimester_fee')
                ->first();


            $accounts_admission_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','admission_fee')
                ->first();


            if(!empty($accounts_admission_fee_deatails) && !empty($accounts_tution_fee_deatails) && !empty($accounts_trimester_fee_deatails)){

                $student_serial_no = \DB::table('student_basic')
                                        ->where('student_basic.program',$program)
                                        ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
                                        ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
                                        ->orderBy('student_basic.created_at','desc')
                                        ->first();

                $program_info = \DB::table('univ_program')
                                    ->where('univ_program.program_id',$program)
                                    ->first();

                $semester_info = \DB::table('univ_semester')
                                    ->where('univ_semester.semester_code',$semester)
                                    ->first();

                $program_title=$program_info->program_code;
                $semester_title=$semester_info->semester_title;



                $student_last_number = \App\Register::StudentCountByProgram($program,$semester,$year);

                $new_student_serial_no =  substr($year,-2).$semester.str_pad($program,2,0,STR_PAD_LEFT).str_pad(($student_last_number+1), 4,0,STR_PAD_LEFT);


                $image_url = \App\Register::TransferStudentImageUrl($program_title,$year,$semester_title,$new_student_serial_no,\Request::input('image_url'));

                        $credit_transfer_basic_form=array(
                            'student_tran_code' => $student_tran_code->string,
                            'first_name'=> strtoupper(\Request::input('first_name')),
                            'middle_name' => strtoupper(\Request::input('middle_name')),
                            'last_name'=> strtoupper(\Request::input('last_name')),
                            'student_serial_no'=>$new_student_serial_no,
                            'program'=>\Request::input('program'),
                            'batch_no'=>\Request::input('batch'),
                            'semester'=>\Request::input('semester'),
                            'academic_year' => \Request::input('academic_year'),
                            'student_image_url'=> $image_url,
                            'email'=>\Request::input('email'),
                            'gender' => \Request::input('gender'),
                            'mobile'=>\Request::input('mobile'),
                            'religion'=> \Request::input('religion'),
                            'student_status'=>3,
                            'admission_date'=>\Request::input('admission_date'),
                            'student_details' =>'Credit Transfer Student',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by'=> \Auth::user()->user_id,
                            'created_at' => $now,
                            'updated_at'=> $now,
                            );


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

                        $student_gurdians_tran_code_father = \Uuid::generate(4);
                        $credit_transfer_gurdian_father_form=array(
                                'student_gurdians_tran_code' =>  $student_gurdians_tran_code_father->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Father',
                                'gurdian_name'=>strtoupper(\Request::input('father_name')),
                                'occupation' => \Request::input('father_occupation'),
                                'mobile'=> \Request::input('father_contact_mobile'),
                                'email'=>\Request::input('father_contact_email'),
                                'emergency_contact'=>$local_gurdian_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );

                        $student_gurdians_tran_code_mother = \Uuid::generate(4);
                        $credit_transfer_gurdian_mother_form=array(
                                'student_gurdians_tran_code' =>  $student_gurdians_tran_code_mother->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Mother',
                                'gurdian_name'=>strtoupper(\Request::input('mother_name')),
                                'occupation' => \Request::input('mother_occupation'),
                                'mobile'=> \Request::input('mother_contact_mobile'),
                                'email'=>\Request::input('mother_contact_email'),
                                'emergency_contact'=>$local_gurdian_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );

                        $student_local_gurdians_tran_code = \Uuid::generate(4);
                        $transfer_student_local_gurdian_form=array(
                                'student_gurdians_tran_code' =>  $student_local_gurdians_tran_code->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Local_Guardian',
                                'gurdian_name'=>strtoupper(\Request::input('mother_name')),
                                'occupation' => \Request::input('mother_occupation'),
                                'mobile'=> \Request::input('mother_contact_mobile'),
                                'email'=>\Request::input('mother_contact_email'),
                                'emergency_contact'=>$local_gurdian_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );



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
                                if(($accounts->accounts_fee_name_slug!='tution_fee')  && ($accounts->accounts_fee_name_slug!='application_form_fee')){
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


                            $success = \DB::transaction(function () use ($credit_transfer_basic_form, $contact_present, $contact_permanent, $credit_transfer_personal_form, $credit_transfer_gurdian_father_form, $credit_transfer_gurdian_mother_form, $transfer_student_local_gurdian_form, $credit_transfer_academic_ssc_form, $credit_transfer_academic_hsc_form, $student_accounts_info_data) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $transfer_student_basic_insert=\DB::connection($this->dbList[$i])->table('student_basic')->insert($credit_transfer_basic_form);

                                    $transfer_student_present_contact_insert=\DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_present);

                                    $transfer_student_permanent_contact_insert=\DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_permanent);

                                    $transfer_student_personal_insert=\DB::connection($this->dbList[$i])->table('student_personal')->insert($credit_transfer_personal_form);

                                    $transfer_student_gurdian_father_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($credit_transfer_gurdian_father_form);

                                    $transfer_student_gurdian_mother_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($credit_transfer_gurdian_mother_form);

                                    $transfer_student_local_gurdian_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($transfer_student_local_gurdian_form);

                                    $transfer_student_ssc_insert=\DB::connection($this->dbList[$i])->table('student_academic_qualification')->insert($credit_transfer_academic_ssc_form);

                                    $transfer_student_hsc_insert=\DB::connection($this->dbList[$i])->table('student_academic_qualification')->insert($credit_transfer_academic_hsc_form);

                                    $transfer_student_accounts_insert=\DB::connection($this->dbList[$i])->table('student_accounts_info')->insert($student_accounts_info_data);


                                    if(!$transfer_student_basic_insert || !$transfer_student_present_contact_insert || !$transfer_student_permanent_contact_insert || !$transfer_student_personal_insert || !$transfer_student_gurdian_father_insert || !$transfer_student_gurdian_mother_insert ||!$transfer_student_local_gurdian_insert || !$transfer_student_ssc_insert || !$transfer_student_hsc_insert || !$transfer_student_accounts_insert){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                
                                    \App\System::EventLogWrite('insert,student_basic',json_encode($credit_transfer_basic_form));
                                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_present));

                                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_permanent));

                                    \App\System::EventLogWrite('insert,student_personal',json_encode($credit_transfer_personal_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($credit_transfer_gurdian_father_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($credit_transfer_gurdian_mother_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($transfer_student_local_gurdian_form));

                                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($credit_transfer_academic_ssc_form));

                                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($credit_transfer_academic_hsc_form));

                                    \App\System::EventLogWrite('insert,student_accounts_info',json_encode($student_accounts_info_data));


                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);

                                }
                            });


                        }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::to('/register/student/credit/transfer')->with('errormessage',"Something wrong in catch !!!");

                        }


                    return \Redirect::to('/register/student/credit/transfer')->with('message',"Student registration Successfull.Student ID: {$new_student_serial_no}");
            }return \Redirect::back()->with('message',"Please create accounts paln.");

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

                        $success = \DB::transaction(function () use ($student_accepted_course_data, $student_basic_update_data, $student_serial_no, $key) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $transfer_student_academic_insert=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->insert($student_accepted_course_data);
                                $transfer_student_basic_update=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_serial_no)->update($student_basic_update_data);

                                if(isset($key)&& $key==0){
                                    if(!$transfer_student_academic_insert || !$transfer_student_basic_update){
                                        $error=1;
                                    }
                                }else{
                                    if(!$transfer_student_academic_insert){
                                        $error=1;
                                    }
                                }
                            }


                            if(!isset($error)){
                                \App\System::TransactionCommit();
                            
                                \App\System::EventLogWrite('insert,student_academic_tabulation',json_encode($student_accepted_course_data));
                                \App\System::EventLogWrite('update,student_basic',json_encode($student_basic_update_data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/student/credit/transfer')->with('message','Something wrong in catch !!');
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

            if(!empty($student_basic)){
                $student_admission_fee_info=\DB::table('all_accounts_fees')
                ->where('accounts_fee_name_slug','admission_fee')
                ->where('accounts_fee_program',$student_basic->program)
                ->first();

                if(!empty($student_admission_fee_info)){

                    $transaction_tran_code=\Uuid::generate(4);
                    $transfer_student_payment_data=array(
                        'transaction_tran_code'=>$transaction_tran_code->string,
                        'transaction_student_tran_code'=>$student_basic->student_tran_code,
                        'transaction_student_serial_no'=>\Request::input('transaction_student_serial_no'),
                        'transaction_program'=>$student_basic->program,
                        'transaction_semster'=>$student_basic->semester,
                        'transaction_year'=>$student_basic->academic_year,
                        'transaction_fees_type'=>'admission_fee',
                        'transaction_payment_types'=>$student_admission_fee_info->accounts_fee_amount,
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

                    try{


                        $success = \DB::transaction(function () use ($transfer_student_payment_data, $student_basic_update_status, $student_basic, $key) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $transfer_student_accouns_insert=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($transfer_student_payment_data);
                                $transfer_student_basic_update=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_basic->student_serial_no)->update($student_basic_update_status);

                                if(isset($key)&& $key==0){
                                    if(!$transfer_student_basic_update || !$transfer_student_accouns_insert){
                                        $error=1;
                                    }
                                }else{
                                    if(!$transfer_student_accouns_insert){
                                        $error=1;
                                    }
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                            
                                \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($transfer_student_payment_data));
                                \App\System::EventLogWrite('update.student_basic',json_encode($student_basic_update_status));      
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                        return \Redirect::to('/register/student/credit/transfer')->with('message',"Student addmission  payment  Successfully!");


                    }catch(\Exception  $e){
                       $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                       \App\System::ErrorLogWrite($message);

                       return \Redirect::to('/register/student/credit/transfer')->with('message','Something wrong in catch !!');
                    }

                }return \Redirect::to('/register/student/credit/transfer')->with('message','Admission fee not found.');
            }return \Redirect::to('/register/student/credit/transfer')->with('message','Invalid Student.');
        }return \Redirect::to('/register/student/credit/transfer')->withInput(\Request::all())->withErrors($v->messages());

    }



    /********************************************
    ## TransferStudentImageUpload
    *********************************************/
    public function TransferStudentImageUpload(){

        $maxwidth = 1500;
        $maxheight = 1500;

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




     /********************************************
    ## RegisterExistingStudent
    *********************************************/
    public function RegisterExistingStudent(){


        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'student_info';


        if(isset($_GET['program']) && isset($_GET['batch']) && !empty($_GET['batch'])  && isset($_GET['student_no']) && !empty($_GET['student_no'])){
            $all_theory_course = \DB::table('course_basic')
                        ->where('course_basic.course_type','Theory')
                        ->where(function($query){
                            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('course_program', $_GET['program']);
                                });
                            }

                        })
                        ->leftJoin('univ_program','course_basic.course_program','like','univ_program.program_id')
                        ->orderBy('course_basic.level','asc')
                        ->orderBy('course_basic.term','asc')
                        ->get();
            $data['all_theory_course']=$all_theory_course;

            $all_lab_course = \DB::table('course_basic')
                        ->where('course_basic.course_type','!=','Theory')
                        ->where(function($query){
                            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('course_program', $_GET['program']);
                                });
                            }

                        })
                        ->leftJoin('univ_program','course_basic.course_program','like','univ_program.program_id')
                        ->orderBy('course_basic.level','asc')
                        ->orderBy('course_basic.term','asc')
                        ->get();
            $data['all_lab_course']=$all_lab_course;
            $data['student_no']=$_GET['student_no'];
            $tab = 'accepted_course';
        }
            $univ_academic_calender=\DB::table('univ_academic_calender')
                                ->select('academic_calender_year', \DB::raw('count(*) as total'))
                                ->groupBy('academic_calender_year')
                                ->get();
            $data['univ_academic_calender']=$univ_academic_calender;

        $all_semester = \DB::table('univ_semester')->get();
        $data['all_semester'] = $all_semester;
        $data['tab'] = $tab;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-existing-student',$data);
    }



     /********************************************
    ## RegisterStudentBatchByProgram
    *********************************************/
    public function RegisterStudentBatchByProgram($program){
        $student_batch=\DB::table('student_basic')
                    ->where('program',$program)
            ->where('student_status','!=','0')
                    ->select('batch_no', \DB::raw('count(*) as total'))->groupBy('batch_no')
                    ->get();
        $data['student_batch'] = $student_batch;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.ajax-batch-list-by-program',$data);
    }


     /********************************************
    ## RegisterExistingStudentByBatch
    *********************************************/
    public function RegisterExistingStudentByBatch($program, $batch){
        $student_list=\DB::table('student_basic')
                    ->where('student_details','Existing Student')
                    ->where('program',$program)
                    ->where('batch_no',$batch)
                    ->get();
        $data['student_list'] = $student_list;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.ajax-student-list-by-program-batch',$data);
    }



    /********************************************
    ## RegisterExistingStudentInfoSubmit
    *********************************************/
    public function RegisterExistingStudentInfoSubmit(){

        $rule = [
            'first_name'=>'Required',
            'last_name'=>'Required',
            'email'=>'Required',
            'gender'=>'Required',
            'mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11|unique:student_basic,mobile',
            'religion'=>'Required',
            'place_of_birth'=>'Required',
            'batch' => 'Required',
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
            $paid_trimester_fees= \Request::input('paid_trimester_fees');
            $paid_tution_fees= \Request::input('paid_tution_fees');
            $paid_others_fees= \Request::input('paid_others_fees');

            $accounts_tution_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','tution_fee')
                ->first();

            $accounts_trimester_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','trimester_fee')
                ->first();


            $accounts_admission_fee_deatails=\DB::table('all_accounts_fees')
                ->where('accounts_fee_program',$program)
                ->where('accounts_fee_payment_type','Receivable')
                ->where('accounts_fee_name_slug','admission_fee')
                ->first();


            if(!empty($accounts_admission_fee_deatails) && !empty($accounts_tution_fee_deatails) && !empty($accounts_trimester_fee_deatails)){

                $student_serial_no = \DB::table('student_basic')
                                        ->where('student_basic.program',$program)
                                        ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
                                        ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
                                        ->orderBy('student_basic.created_at','desc')
                                        ->first();
                                        
                $program_info = \DB::table('univ_program')
                                    ->where('univ_program.program_id',$program)
                                    ->first();

                $semester_info = \DB::table('univ_semester')
                                    ->where('univ_semester.semester_code',$semester)
                                    ->first();

                $program_title=$program_info->program_code;
                $semester_title=$semester_info->semester_title;

                $new_student_serial_no=\Request::input('univ_roll_no');
                $emergency_contact=\Request::input('emergency_contact');

                if($emergency_contact == 'Father'){
                    $father_emergency='yes';
                    $mother_emergency='no';
                    $local_gurdian_emergency='no';
                }elseif($emergency_contact == 'Mother'){
                    $father_emergency='no';
                    $mother_emergency='yes';
                    $local_gurdian_emergency='no';
                }elseif($emergency_contact == 'Local_Guardian'){
                    $father_emergency='no';
                    $mother_emergency='no';
                    $local_gurdian_emergency='yes';
                }


                $image_url = \App\Register::TransferStudentImageUrl($program_title,$year,$semester_title,$new_student_serial_no,\Request::input('image_url'));

                        $existing_student_basic_form=array(
                            'student_tran_code' => $student_tran_code->string,
                            'first_name'=> strtoupper(\Request::input('first_name')),
                            'middle_name' => strtoupper(\Request::input('middle_name')),
                            'last_name'=> strtoupper(\Request::input('last_name')),
                            'student_serial_no'=>\Request::input('univ_roll_no'),
                            'batch_no'=>\Request::input('batch'),
                'section'=>'A',
                            'program'=>\Request::input('program'),
                            'semester'=>\Request::input('semester'),
                            'academic_year' => \Request::input('academic_year'),
                            'student_image_url'=> $image_url,
                            'email'=>\Request::input('email'),
                            'gender' => \Request::input('gender'),
                            'mobile'=>\Request::input('mobile'),
                            'religion'=> \Request::input('religion'),
                            'student_status'=>'1',
                            'admission_date'=>\Request::input('admission_date'),
                            'student_details'=>'Existing Student',
                            'created_by' => \Auth::user()->user_id,
                            'updated_by'=> \Auth::user()->user_id,
                            'created_at' => $now,
                            'updated_at'=> $now,
                            );


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

                        $existing_student_personal_form=array(
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

                        $student_gurdians_tran_code_father = \Uuid::generate(4);
                        $existing_student_gurdian_father_form=array(
                                'student_gurdians_tran_code' =>  $student_gurdians_tran_code_father->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Father',
                                'gurdian_name'=>strtoupper(\Request::input('father_name')),
                                'occupation' => \Request::input('father_occupation'),
                                'mobile'=> \Request::input('father_contact_mobile'),
                                'email'=>\Request::input('father_contact_email'),
                                'emergency_contact'=>$father_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );

                        $student_gurdians_tran_code_mother = \Uuid::generate(4);
                        $existing_student_gurdian_mother_form=array(
                                'student_gurdians_tran_code' =>  $student_gurdians_tran_code_mother->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Mother',
                                'gurdian_name'=>strtoupper(\Request::input('mother_name')),
                                'occupation' => \Request::input('mother_occupation'),
                                'mobile'=> \Request::input('mother_contact_mobile'),
                                'email'=>\Request::input('mother_contact_email'),
                                'emergency_contact'=>$mother_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );

                        $student_local_gurdians_tran_code = \Uuid::generate(4);
                        $existing_student_local_gurdian_form=array(
                                'student_gurdians_tran_code' =>  $student_local_gurdians_tran_code->string,
                                'student_tran_code'=>$student_tran_code,
                                'relation'=>'Local_Guardian',
                                'gurdian_name'=>strtoupper(\Request::input('mother_name')),
                                'occupation' => \Request::input('mother_occupation'),
                                'mobile'=> \Request::input('mother_contact_mobile'),
                                'email'=>\Request::input('mother_contact_email'),
                                'emergency_contact'=>$local_gurdian_emergency,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                );


                        $student_academic_tran_code_ssc = \Uuid::generate(4);
                        $existing_student_academic_ssc_form=array(
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


                        $student_academic_tran_code_hsc = \Uuid::generate(4);
                        $existing_student_academic_hsc_form=array(
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


                        #----student accounts info insert----#
                        
                        $accounts_info_tran_code=\Uuid::generate(4);
                        $program_details=\DB::table('univ_program')->where('program_id',$program)->first();
                        $accounts_fee_deatails=\DB::table('all_accounts_fees')
                                            ->where('accounts_fee_program',$program)
                                            ->where('accounts_fee_payment_type','Receivable')
                                            ->where('accounts_fee_name_slug','!=','application_form_fee')
                                            ->whereIn('accounts_fee_name_slug',array('tution_fee','trimester_fee'))
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
                                if(($accounts->accounts_fee_name_slug!='tution_fee')  && ($accounts->accounts_fee_name_slug!='application_form_fee')){
                                    $total_fees=$total_fees+(float)$accounts->accounts_fee_amount;
                                }
                            }
                        }


                        $accounts_total_fees=$total_tution_fee+$total_fees;

                        if(!empty(\Request::input('waiver'))){
                            $waiver_type = \Request::input('waiver');
                        }else{
                            $waiver_type ='';
                        }


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



                    #---------------------student payment transactions---------------------#
                    $fees=\DB::table('all_accounts_fees')
                        ->where('accounts_fee_program',$program)
                        ->where('accounts_fee_name_slug','admission_fee')
                        ->where('accounts_fee_payment_type','Receivable')
                        ->first();

                    if(!empty($fees)){
                        $accounts_fee_amount=$fees->accounts_fee_amount;
                    }else{
                        $accounts_fee_amount=0;
                    }

                    $payment_others=(float)0;
                    $payment_amounts=((float)$accounts_fee_amount)+($payment_others);



                     #----------------start admission transaction history------------------#
                    $transaction_admission_tran_code=\Uuid::generate(4);
                    $accounts_admission_transaction_history_data=array(
                        'transaction_tran_code' => $transaction_admission_tran_code->string,
                        'transaction_student_tran_code' => $student_tran_code->string,
                        'transaction_student_serial_no' => $new_student_serial_no,
                        'transaction_program' => $program,
                        'transaction_semster' => $semester,
                        'transaction_year' => $year,
                        'transaction_fees_type' => 'admission_fee',
                        'transaction_payment_types' => $accounts_fee_amount,
                        'transaction_slip_no' => 'Existing Student Admission Fee',
                        'transaction_receive_types' => 'cash',
                        'transaction_fees_amount' => $accounts_fee_amount,
                        'transaction_history_remarks' => 'Admission Fee',
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );



                    $payment_admission_transaction_tran_code=\Uuid::generate(4);
                    $student_admission_payment_transactions_data=array(
                        'payment_transaction_tran_code' => $payment_admission_transaction_tran_code->string,
                        'payment_student_tran_code' => $student_tran_code->string,
                        'payment_student_serial_no' => $new_student_serial_no,
                        'accounts_payment_tran_code' => $payment_admission_transaction_tran_code->string,
                        'payment_program' => $program,
                        'payment_semster' => $semester,
                        'payment_year' => $year,
                        'payment_transaction_fee_type' => 'admission_fee',
                        'payment_receive_type' => 'cash',
                        'payment_receivable' => $accounts_fee_amount,
                        'payment_paid' => $accounts_fee_amount,
                        'payment_remarks' => 'Admission Paid',
                        'payment_others' => $payment_others,
                        'payment_amounts' => $payment_amounts,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                    #---------------end student  admission transactions---------------#



                    #-----------------start_transaction_history_tution_data-------------#
                    $transaction_tution_tran_code=\Uuid::generate(4);
                    $accounts_transaction_history_tution_data=array(
                        'transaction_tran_code' => $transaction_tution_tran_code->string,
                        'transaction_student_tran_code' => $student_tran_code->string,
                        'transaction_student_serial_no' => $new_student_serial_no,
                        'transaction_program' => $program,
                        'transaction_semster' => $semester,
                        'transaction_year' => $year,
                        'transaction_fees_type' => 'tution_fee',
                        'transaction_payment_types' => 'cash',
                        'transaction_slip_no' => 'Existing Students Tution Fee',
                        'transaction_receive_types' => 'cash',
                        'transaction_fees_amount' => $paid_tution_fees,
                        'transaction_history_remarks' => 'Existing student tution fees',
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );



                    $payment_transaction_tution_tran_code=\Uuid::generate(4);
                    $student_payment_transactions_tution_data_info=array(
                        'payment_transaction_tran_code' => $payment_transaction_tution_tran_code->string,
                        'payment_student_tran_code' => $student_tran_code->string,
                        'payment_student_serial_no' => $new_student_serial_no,
                        'accounts_payment_tran_code' => $transaction_tution_tran_code->string,
                        'payment_program' => $program,
                        'payment_semster' => $semester,
                        'payment_year' => $year,
                        'payment_transaction_fee_type' => 'tution_fee',
                        'payment_receive_type' =>  'cash',
                        'payment_receivable' => 0,
                        'payment_paid' => $paid_tution_fees,
                        'payment_remarks' => 'Paid',
                        'payment_others' => 0,
                        'payment_amounts' => $paid_tution_fees,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                       #--------------end_transaction_history_tution_data----------------#


                    #-----------------start_transaction_history_trimester_data-------------#
                    $transaction_trimester_tran_code=\Uuid::generate(4);
                    $accounts_transaction_history_trimester_data=array(
                        'transaction_tran_code' => $transaction_trimester_tran_code->string,
                        'transaction_student_tran_code' => $student_tran_code->string,
                        'transaction_student_serial_no' => $new_student_serial_no,
                        'transaction_program' => $program,
                        'transaction_semster' => $semester,
                        'transaction_year' => $year,
                        'transaction_fees_type' => 'trimester_fee',
                        'transaction_payment_types' => 'cash',
                        'transaction_slip_no' => 'Existing Students Trimester Amount',
                        'transaction_receive_types' => 'cash',
                        'transaction_fees_amount' => $paid_trimester_fees,
                        'transaction_history_remarks' => 'Existing student trimester fees',
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );


                    $payment_transaction_trimester_tran_code=\Uuid::generate(4);
                    $student_payment_transactions_trimester_data_info=array(
                        'payment_transaction_tran_code' => $payment_transaction_trimester_tran_code->string,
                        'payment_student_tran_code' => $student_tran_code->string,
                        'payment_student_serial_no' => $new_student_serial_no,
                        'accounts_payment_tran_code' => $transaction_trimester_tran_code->string,
                        'payment_program' => $program,
                        'payment_semster' => $semester,
                        'payment_year' => $year,
                        'payment_transaction_fee_type' => 'trimester_fee',
                        'payment_receive_type' =>  'cash',
                        'payment_receivable' => 0,
                        'payment_paid' => $paid_trimester_fees,
                        'payment_remarks' => 'Paid',
                        'payment_others' => 0,
                        'payment_amounts' => $paid_trimester_fees,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                       #--------------end_transaction_history_trimester_data----------------#



                    #-----------------start_transaction_history_others_data-------------#
                    $transaction_others_tran_code=\Uuid::generate(4);
                    $accounts_transaction_history_others_data=array(
                        'transaction_tran_code' => $transaction_others_tran_code->string,
                        'transaction_student_tran_code' => $student_tran_code->string,
                        'transaction_student_serial_no' => $new_student_serial_no,
                        'transaction_program' => $program,
                        'transaction_semster' => $semester,
                        'transaction_year' => $year,
                        'transaction_fees_type' => 'others_fees',
                        'transaction_payment_types' => 'cash',
                        'transaction_slip_no' => 'Existing Students Others Amount',
                        'transaction_receive_types' => 'cash',
                        'transaction_fees_amount' => $paid_others_fees,
                        'transaction_history_remarks' => 'Existing student others fees',
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );



                    $payment_transaction_others_tran_code=\Uuid::generate(4);
                    $student_payment_transactions_others_data_info=array(
                        'payment_transaction_tran_code' => $payment_transaction_others_tran_code->string,
                        'payment_student_tran_code' => $student_tran_code->string,
                        'payment_student_serial_no' => $new_student_serial_no,
                        'accounts_payment_tran_code' => $transaction_others_tran_code->string,
                        'payment_program' => $program,
                        'payment_semster' => $semester,
                        'payment_year' => $year,
                        'payment_transaction_fee_type' => 'others_fees',
                        'payment_receive_type' =>  'cash',
                        'payment_receivable' => 0,
                        'payment_paid' => $paid_others_fees,
                        'payment_remarks' => 'Paid',
                        'payment_others' => 0,
                        'payment_amounts' => $paid_others_fees,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );
                       #--------------end_transaction_history_trimester_data----------------#

                            
                        try{

                            $success = \DB::transaction(function () use ($existing_student_basic_form, $contact_present, $contact_permanent, $existing_student_personal_form, $existing_student_gurdian_father_form, $existing_student_gurdian_mother_form, $existing_student_local_gurdian_form, $existing_student_academic_ssc_form, $existing_student_academic_hsc_form, $student_accounts_info_data, $accounts_admission_transaction_history_data, $student_admission_payment_transactions_data, $accounts_transaction_history_tution_data, $student_payment_transactions_tution_data_info, $accounts_transaction_history_trimester_data, $student_payment_transactions_trimester_data_info, $accounts_transaction_history_others_data, $student_payment_transactions_others_data_info, $paid_others_fees) {

                                for($i=0; $i<count($this->dbList); $i++){

                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $transfer_student_basic_insert=\DB::connection($this->dbList[$i])->table('student_basic')->insert($existing_student_basic_form);

                                    $transfer_student_present_contact_insert=\DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_present);

                                    $transfer_student_permanent_contact_insert=\DB::connection($this->dbList[$i])->table('student_contacts')->insert($contact_permanent);

                                    $transfer_student_personal_insert=\DB::connection($this->dbList[$i])->table('student_personal')->insert($existing_student_personal_form);

                                    $transfer_student_gurdian_father_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($existing_student_gurdian_father_form);

                                    $transfer_student_gurdian_mother_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($existing_student_gurdian_mother_form);

                                    $existing_student_local_gurdian_insert=\DB::connection($this->dbList[$i])->table('student_gurdians')->insert($existing_student_local_gurdian_form);

                                    $transfer_student_ssc_insert=\DB::connection($this->dbList[$i])->table('student_academic_qualification')->insert($existing_student_academic_ssc_form);

                                    $transfer_student_hsc_insert=\DB::connection($this->dbList[$i])->table('student_academic_qualification')->insert($existing_student_academic_hsc_form);



                                    $transfer_student_accounts_insert=\DB::connection($this->dbList[$i])->table('student_accounts_info')->insert($student_accounts_info_data);



                                    $student_admission_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_admission_payment_transactions_data);

                                    $accounts_admission_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_admission_transaction_history_data);



                                    $student_payment_tution_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_tution_data_info);

                                    $accounts_tution_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_transaction_history_tution_data);


                                    $student_payment_trimester_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_trimester_data_info);

                                    $accounts_trimester_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_transaction_history_trimester_data);


                                    if(!empty($paid_others_fees) && ($paid_others_fees>0)){
                                        $student_payment_others_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_others_data_info);

                                        $accounts_others_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_transaction_history_others_data);

                                        if(!$student_payment_others_transactions_save || !$accounts_others_transaction_history_save){
                                            $error=1;
                                        }
                                    }


                                    if(!$transfer_student_basic_insert || !$transfer_student_present_contact_insert || !$transfer_student_permanent_contact_insert || !$transfer_student_personal_insert || !$transfer_student_gurdian_father_insert || !$transfer_student_gurdian_mother_insert || !$existing_student_local_gurdian_insert || !$transfer_student_ssc_insert || !$transfer_student_hsc_insert || !$transfer_student_accounts_insert || !$student_admission_payment_transactions_save || !$accounts_admission_transaction_history_save || !$student_payment_tution_transactions_save  || !$accounts_tution_transaction_history_save  || !$student_payment_trimester_transactions_save   || !$accounts_trimester_transaction_history_save){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                
                                    \App\System::EventLogWrite('insert,student_basic',json_encode($transfer_student_basic_insert));
                                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_present));

                                    \App\System::EventLogWrite('insert,student_contacts',json_encode($contact_permanent));

                                    \App\System::EventLogWrite('insert,student_personal',json_encode($existing_student_personal_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($existing_student_gurdian_father_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($existing_student_gurdian_mother_form));

                                    \App\System::EventLogWrite('insert,student_gurdians',json_encode($existing_student_local_gurdian_form));

                                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($existing_student_academic_ssc_form));

                                    \App\System::EventLogWrite('insert,student_academic_qualification',json_encode($existing_student_academic_hsc_form));

                                    \App\System::EventLogWrite('insert,student_accounts_info',json_encode($student_accounts_info_data));

                                    \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_admission_payment_transactions_data));
                                    \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($accounts_admission_transaction_history_data));


                                    \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($accounts_transaction_history_tution_data));
                                    \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($accounts_transaction_history_tution_data));


                                    \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($accounts_transaction_history_trimester_data));
                                    \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($student_payment_transactions_trimester_data_info));

                                    if(!empty($paid_others_fees) && ($paid_others_fees>0)){
                                        \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($accounts_transaction_history_others_data));
                                        \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($student_payment_transactions_others_data_info));
                                    }

                                    \App\System::TransactionCommit();

                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);

                                }
                            });


                        }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::to('/register/existing/student')->with('errormessage',"Something wrong in catch !!!");
                        }

                    return \Redirect::to('/register/existing/student')->with('message',"Student registration Successfull.Student ID: {$new_student_serial_no}");

            }return \Redirect::back()->with('message',"Please create accounts plan.");

        }return \Redirect::to('/register/existing/student')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    ## RegisterExistingStudentAcceptedCourse
    *********************************************/


    // public function RegisterExistingStudentAcceptedTheoryCourse($student_serial_no, $course_code, $course_type, $course_year, $course_semester, $ct_1, $ct_2, $ct_3, $ct_4, $mid_term, $class_attendance, $class_participation, $class_presentaion, $class_final_exam){

    public function RegisterExistingStudentAcceptedTheoryCourse(){

        $rule = [
            'student_serial_no'=>'Required',
            'course_code'=>'Required',
            'course_type'=>'Required',
            'course_year'=>'Required',
            'course_semester'=>'Required',
            'ct_1'=>'Required|numeric',
            'ct_2'=>'Required|numeric',
            'ct_3'=>'Required|numeric',
            'ct_4'=>'Required|numeric',
            'mid_term'=>'Required|numeric',
            'class_attendance'=>'Required|numeric',
            'class_participation'=>'Required|numeric',
            'class_presentaion'=>'Required|numeric',
            'class_final_exam'=>'Required|numeric',
        ];

        $v = \Validator::make(\Request::all(),$rule);


        if($v->passes()){

            $student_serial_no= \Request::input('student_serial_no');
            $course_code= \Request::input('course_code');
            $course_type= \Request::input('course_type');
            $course_year= \Request::input('course_year');
            $course_semester= \Request::input('course_semester');
            $ct_1= \Request::input('ct_1');
            $ct_2= \Request::input('ct_2');
            $ct_3= \Request::input('ct_3');
            $ct_4= \Request::input('ct_4');
            $mid_term= \Request::input('mid_term');
            $class_attendance= \Request::input('class_attendance');
            $class_participation= \Request::input('class_participation');
            $class_presentaion= \Request::input('class_presentaion');
            $class_final_exam= \Request::input('class_final_exam');

            $now=date('Y-m-d H:i:s');

            if(!empty($student_serial_no) && !empty($course_code) && !empty($course_type) && !empty($course_year) && !empty($course_semester) && !empty($ct_1) && !empty($ct_2) && !empty($ct_3) && !empty($ct_4) && !empty($mid_term) && !empty($class_attendance) && !empty($class_participation) && !empty($class_presentaion) && !empty($class_final_exam)){

                if (is_numeric($ct_1) && is_numeric($ct_2) && is_numeric($ct_3) && is_numeric($ct_4) && is_numeric($mid_term) && is_numeric($class_attendance) && is_numeric($class_participation) && is_numeric($class_presentaion) && is_numeric($class_final_exam)){

                    $student_basic=\DB::table('student_basic')
                            ->where('student_serial_no', $student_serial_no)
                            ->first();

                    if(!empty($student_basic)){

                        $course_basic=\DB::table('course_basic')
                                ->where('course_program', $student_basic->program)
                                ->where('course_code', $course_code)
                                ->first();

                        $student_accounts_info=\DB::table('student_accounts_info')
                                ->where('accounts_student_tran_code',$student_basic->student_tran_code)
                                ->first();


                        $univ_program_info=\DB::table('univ_program')
                                ->where('program_id',$student_basic->program)
                                ->first();

                        if(!empty($course_basic) && !empty($student_accounts_info)  && !empty($univ_program_info) && ($course_type=='Theory')){

                            if(!empty($ct_1)){
                              $class_quiz_1=(float)$ct_1;
                            }else{
                              $class_quiz_1=null;
                            }
                            if(!empty($ct_2)){
                              $class_quiz_2=(float)$ct_2;
                            }else{
                              $class_quiz_2=null;
                            }
                            if(!empty($ct_3)){
                              $class_quiz_3=(float)$ct_3;
                            }else{
                              $class_quiz_3=null;
                            }
                            if(!empty($ct_4)){
                              $class_quiz_4=(float)$ct_4;
                            }else{
                              $class_quiz_4=null;
                            }

                            if(!empty($mid_term)){
                              $mid_term=(float)$mid_term;
                            }else{
                              $mid_term=null;
                            }

                            if(!empty($class_attendance)){
                              $class_attendance=(float)$class_attendance;
                            }else{
                              $class_attendance=null;
                            }

                            if(!empty($class_participation)){
                              $class_participation=(float)$class_participation;
                            }else{
                              $class_participation=null;
                            }
                            if(!empty($class_presentaion)){
                              $class_presentaion=(float)$class_presentaion;
                            }else{
                              $class_mid_term_exam=null;
                            }
                            if(!empty($class_final_exam)){
                              $class_final_exam=(float)$class_final_exam;
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
                            if($mid_term !=null){
                              $grand_total=$grand_total+$mid_term;
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
                                  $result_grade_point=$grade->grade_point;
                                }

                                if(((($class_final_exam)+($mid_term))<24) || ($other_total<16)){
                                    $result_grade='F';
                                    $class_result_remarks='Fail';
                                    $result_grade_point=$grade->grade_point;

                                }

                                if($class_final_exam==null){
                                    $result_grade='I';
                                    $result_grade_point='0.00';
                                    $class_result_remarks='Incomplete';
                                }


                                if(($class_quiz_1 <=10) && ($class_quiz_2 <=10) && ($class_quiz_3 <=10) && ($class_quiz_4 <=10) && ($class_attendance <=10) && ($class_participation <=5) && ($class_presentaion <=15) && ($mid_term <=20) && ($class_final_exam <=40)){

                                    $class_register_tran_code = \Uuid::generate(4);

                                    $class_register_insert=array(
                                            'class_register_tran_code' => $class_register_tran_code->string,
                                            'student_tran_code' => $student_basic->student_tran_code,
                                            'class_faculty' => \Auth::user()->user_id,
                                            'program_coordinator' => \Auth::user()->user_id,
                                            'class_register_section' => 'A',
                                            'class_department' => $univ_program_info->program_department_no,
                                            'class_program' => $student_basic->program,
                                            'class_course_code' => $course_code,
                                            'class_semster' => $course_semester,
                                            'class_year' => $course_year,
                                            'class_quiz_1' => $class_quiz_1,
                                            'class_quiz_2' => $class_quiz_2,
                                            'class_quiz_3' => $class_quiz_3,
                                            'class_quiz_4' => $class_quiz_4,
                                            'class_attendance' => $class_attendance,
                                            'class_participation' => $class_participation,
                                            'class_presentaion' => $class_presentaion,
                                            'class_quiz_avg_total' => $class_quiz_avg_total,
                                            'class_mid_term_exam' => $mid_term,
                                            'class_mid_term_avg_total' =>$mid_term,
                                            'class_final_exam' => $class_final_exam,
                                            'class_final_avg_total' => $class_final_exam,
                                            'class_grand_total' => $class_grand_total,
                                            'class_final_grade' => $result_grade,
                                            'class_result_remarks' => $class_result_remarks,
                                            'class_result_status' => '1',
                                            'created_by' =>  \Auth::user()->user_id,
                                            'updated_by' =>  \Auth::user()->user_id,
                                            'created_at' => $now,
                                            'updated_at' => $now,
                                            );

                                    $tabulation_tran_code=\Uuid::generate(4);
                                    $student_accepted_course_data=array(
                                            'tabulation_tran_code' => $tabulation_tran_code->string,
                                            'student_tran_code'=>$student_basic->student_tran_code,
                                            'student_serial_no' => $student_basic->student_serial_no,
                                            'tabulation_program' =>$student_basic->program,
                                            'tabulation_semester' =>$course_semester,
                                            'tabulation_year' =>$course_year,
                                            'tabulation_level' =>$course_basic->level,
                                            'tabulation_term' => $course_basic->term,
                                            'tabulation_course_id' =>$course_basic->course_code,
                                            'tabulation_course_title' =>$course_basic->course_title,
                                            'tabulation_course_type' =>$course_basic->course_type,
                                            'tabulatation_credit_hours' =>$course_basic->credit_hours,
                                            'tabulation_credit_earned' =>$course_basic->credit_hours,
                                            'tabulation_grade_point' =>$result_grade_point,
                                            'tabulation_grade' =>$result_grade,
                                            'tabulation_status' => '1',
                                            'created_at' =>$now,
                                            'updated_at' =>$now,
                                            'created_by' =>\Auth::user()->user_id,
                                            'updated_by' =>\Auth::user()->user_id,
                                            );


                                    $student_study_level_info=\DB::table('student_study_level')->where('student_tran_code', $student_basic->student_tran_code)->where('study_level_semester', $course_semester)->where('study_level_year', $course_year)->first();

                                    $study_level_tran_code=\Uuid::generate(4);
                                    $student_study_level_data=array(
                                        'study_level_tran_code' => $study_level_tran_code->string,
                                        'student_tran_code' =>  $student_basic->student_tran_code,
                                        'study_level_semester' => $course_semester,
                                        'study_level_year' => $course_year,
                                        'pre_advising_status' => 6,
                                        'study_level_status' => 1,
                                        'created_at' =>$now,
                                        'updated_at' =>$now ,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,
                                        );


                                    $student_accounts_fees=$student_accounts_info->accounts_fee_deatails;
                                    $accounts_fee_deatails=unserialize($student_accounts_fees);


                                    foreach ($accounts_fee_deatails as $key => $accounts_fees) {

                                        if($accounts_fees->accounts_fee_name_slug=='tution_fee'){
                                            $tution_fee_payment_receivable=($accounts_fees->accounts_fee_amount)*($course_basic->credit_hours);
                                        }

                                        if($accounts_fees->accounts_fee_name_slug=='trimester_fee'){
                                            $trimester_payment_receivable=($accounts_fees->accounts_fee_amount);
                                        }else{
                                           $trimester_payment_receivable=0; 
                                        }
                                    }


                                    $payment_tution_transaction_tran_code=\Uuid::generate(4);
                                    $student_payment_tution_transactions_data=array(
                                        'payment_transaction_tran_code' => $payment_tution_transaction_tran_code->string,
                                        'payment_student_tran_code' => $student_basic->student_tran_code,
                                        'payment_student_serial_no' => $student_basic->student_serial_no,
                                        'payment_program' => $student_basic->program,
                                        'payment_semster' => $course_semester,
                                        'payment_year' => $course_year,
                                        'payment_transaction_fee_type' => 'tution_fee',
                                        'payment_receive_type' => '',
                                        'payment_receivable' => $tution_fee_payment_receivable,
                                        'payment_paid' => 0,
                                        'payment_remarks' => 'Not Paid',
                                        'payment_others' => 0,
                                        'payment_amounts' => 0,
                                        'payment_details' => $course_code,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,

                                        );


                                    $trimester_student_receivable_transaction_info=\DB::table('student_payment_transactions')->where('payment_student_tran_code', $student_basic->student_tran_code)->where('payment_semster', $course_semester)->where('payment_year', $course_year)->first();

                                    $payment_trimester_transaction_tran_code=\Uuid::generate(4);
                                    $student_payment_trimester_transactions_data=array(
                                        'payment_transaction_tran_code' => $payment_trimester_transaction_tran_code->string,
                                        'payment_student_tran_code' => $student_basic->student_tran_code,
                                        'payment_student_serial_no' => $student_basic->student_serial_no,
                                        'payment_program' => $student_basic->program,
                                        'payment_semster' => $course_semester,
                                        'payment_year' => $course_year,
                                        'payment_transaction_fee_type' => 'trimester_fee',
                                        'payment_receive_type' => '',
                                        'payment_receivable' => $trimester_payment_receivable,
                                        'payment_paid' => 0,
                                        'payment_remarks' => 'Not Paid',
                                        'payment_others' => 0,
                                        'payment_amounts' => 0,
                                        'payment_details' => $course_code,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,

                                        );


                                    try{
                                        $success = \DB::transaction(function () use ($class_register_insert, $student_accepted_course_data, $student_payment_tution_transactions_data, $student_payment_trimester_transactions_data, $trimester_student_receivable_transaction_info, $student_study_level_info, $student_study_level_data) {

                                            for($i=0; $i<count($this->dbList); $i++){
                                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $class_register_save=\DB::connection($this->dbList[$i])->table('student_class_registers')->insert($class_register_insert);

                                                $student_academic_tabulation_insert=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->insert( $student_accepted_course_data);
                                                
                                                $student_tution_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_tution_transactions_data);

                                                if(empty($trimester_student_receivable_transaction_info)){
                                                    $student_trimester_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_trimester_transactions_data);
                                                    if(!$student_trimester_payment_transactions_save){
                                                        $error=1;
                                                    }
                                                }
                                                if(empty($student_study_level_info)){
                                                    $student_study_level_store=\DB::connection($this->dbList[$i])->table('student_study_level')->insert($student_study_level_data);
                                                    if(!$student_study_level_store){
                                                        $error=1;
                                                    }
                                                }
                                                if(!$class_register_save || !$student_academic_tabulation_insert || !$student_tution_payment_transactions_save){
                                                    $error=1;
                                                }
                                            }

                                            if(!isset($error)){

                                                \App\System::EventLogWrite('insert,student_class_registers',json_encode($class_register_insert));
                                                \App\System::EventLogWrite('insert,student_academic_tabulation',json_encode($student_accepted_course_data));

                                                \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_tution_transactions_data));

                                                if(empty($trimester_student_receivable_transaction_info)){
                                                    \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_trimester_transactions_data));
                                                }

                                                if(empty($student_study_level_info)){
                                                    \App\System::EventLogWrite('insert,student_study_level',json_encode($student_study_level_data));
                                                }

                                                \App\System::TransactionCommit();
                                                
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

                                    return \Redirect::back()->with('message',"Course accepted  Successfully !!!");


                                }return \Redirect::back()->with('errormessage','Invalid marks');

                            }return \Redirect::back()->with('errormessage','Marks is to be below 100');


                        }return \Redirect::back()->with('errormessage',"Invalid Course or accounts info.");

                    }return \Redirect::back()->with('errormessage',"Invalid student!!!");

                }return \Redirect::back()->with('errormessage',"Invalid or missing value");
            }return \Redirect::back()->with('errormessage',"Invalid or missing value");
        }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

    }



    /********************************************
    ## RegisterExistingStudentAcceptedLabCourse
    *********************************************/

    // public function RegisterExistingStudentAcceptedLabCourse($student_serial_no, $course_code, $course_type, $course_year, $course_semester, $lab_attendance, $lab_performance, $lab_reprot, $lab_verbal, $lab_final){

    public function RegisterExistingStudentAcceptedLabCourse(){

        $now=date('Y-m-d H:i:s');
        $rule = [
            'student_serial_no'=>'Required',
            'course_code'=>'Required',
            'course_type'=>'Required',
            'course_year'=>'Required',
            'course_semester'=>'Required',
            //'lab_attendance'=>'Required|numeric',
            //'lab_performance'=>'Required|numeric',
            //'lab_reprot'=>'Required|numeric',
            //'lab_verbal'=>'Required|numeric',
            //'lab_final'=>'Required|numeric',
        ];

        $v = \Validator::make(\Request::all(),$rule);


        if($v->passes()){

            $student_serial_no= \Request::input('student_serial_no');
            $course_code= \Request::input('course_code');
            $course_type= \Request::input('course_type');
            $course_year= \Request::input('course_year');
            $course_semester= \Request::input('course_semester');
            $lab_attendance= \Request::input('lab_attendance');
            $lab_performance= \Request::input('lab_performance');
            $lab_reprot= \Request::input('lab_reprot');
            $lab_verbal= \Request::input('lab_verbal');
            $lab_final= \Request::input('lab_final');

            if(!empty($student_serial_no) && !empty($course_code) && !empty($course_type) && !empty($course_year) && !empty($course_semester) && !empty($lab_attendance) && !empty($lab_performance) && !empty($lab_reprot) &&!empty($lab_verbal) && !empty($lab_final)){

                if (is_numeric($lab_attendance) && is_numeric($lab_performance) && is_numeric($lab_reprot) && is_numeric($lab_verbal) && is_numeric($lab_final)){
                    $student_basic=\DB::table('student_basic')
                            ->where('student_serial_no', $student_serial_no)
                            ->first();

                    if(!empty($student_basic)){

                        $course_basic=\DB::table('course_basic')
                                ->where('course_program', $student_basic->program)
                                ->where('course_code', $course_code)
                                ->first();

                        $univ_program_info=\DB::table('univ_program')
                                ->where('program_id',$student_basic->program)
                                ->first();

                        $student_accounts_info=\DB::table('student_accounts_info')
                                ->where('accounts_student_tran_code',$student_basic->student_tran_code)
                                ->first();

                        if(!empty($course_basic) && !empty($student_accounts_info) && !empty($univ_program_info) && ($course_type !='Theory')){

                            $total_marks=0;
                            if(!empty($lab_attendance)){
                              $lab_attendance=$lab_attendance;
                              $total_marks=$total_marks+$lab_attendance;
                            }else{
                              $lab_attendance=null;
                            }
                            if(!empty($lab_performance)){
                              $lab_performance=$lab_performance;
                              $total_marks=$total_marks+$lab_performance;
                            }else{
                              $lab_performance=null;
                            }
                            if(!empty($lab_reprot)){
                              $lab_reprot=$lab_reprot;
                              $total_marks=$total_marks+$lab_reprot;
                            }else{
                              $lab_reprot=null;
                            }
                            if(!empty($lab_verbal)){
                              $lab_verbal=$lab_verbal;
                              $total_marks=$total_marks+$lab_verbal;
                            }else{
                              $lab_verbal=null;
                            }
                            if(!empty($lab_final)){
                              $lab_final=$lab_final;
                              $total_marks=$total_marks+$lab_final;
                            }else{
                              $lab_final=null;
                            }
                            

                            $lab_grand_total=ceil($total_marks);

                            if(($lab_grand_total <= 100) && ($lab_grand_total >= 0)){

                                $grade=\DB::table('grade_equivalent')->where('lowest_margin','<=', $lab_grand_total)->where('highest_margin','>=', $lab_grand_total)->first();

                                if(!empty($grade)){
                                  $result_grade=$grade->letter_grade;
                                  $result_grade_point=$grade->grade_point;
                                  $lab_result_remarks=$grade->remarks;
                                }

                                if($lab_grand_total<40){
                                  $result_grade='F';
                                  $result_grade_point=$grade->grade_point;
                                  $lab_result_remarks='Fail';

                                }

                                if($lab_final==null){
                                 $result_grade='I';
                                 $result_grade_point='0.00';
                                 $lab_result_remarks='Incomplete';
                                }

                                //if(($lab_attendance <=10) && ($lab_performance <=25) && ($lab_reprot <=25) && ($lab_verbal <=10) && ($lab_final <=40)){
                                    $lab_register_tran_code = \Uuid::generate(4);
                                    $lab_result_store=array(
                                        'lab_register_tran_code' => $lab_register_tran_code->string,
                                        'student_tran_code' => $student_basic->student_tran_code,
                                        'student_serial_no' => $student_basic->student_serial_no,
                                        'lab_section' => 'A',
                                        'lab_department' => $univ_program_info->program_department_no,
                                        'lab_program' => $student_basic->program,
                                        'lab_semster' => $course_semester,
                                        'lab_year' => $course_year,
                                        'lab_course_code' => $course_code,
                                        'lab_faculty' => \Auth::user()->user_id,
                                        'lab_coordinator' => \Auth::user()->user_id,
                                        'lab_result_status' => 1,
                                        'lab_attendance' => $lab_attendance,
                                        'lab_performance' => $lab_performance,
                                        'lab_reprot' => $lab_reprot,
                                        'lab_verbal' => $lab_verbal,
                                        'lab_final' => $lab_final,
                                        'lab_result_total' => $lab_grand_total,
                                        'lab_result_grade' => $result_grade,
                                        'lab_result_remarks' => $lab_result_remarks,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                        'created_by' => \Auth::user()->user_id,
                                        'updated_by' => \Auth::user()->user_id,
                                        );


                                    $tabulation_tran_code=\Uuid::generate(4);
                                    $student_accepted_course_data=array(
                                            'tabulation_tran_code' => $tabulation_tran_code->string,
                                            'student_tran_code'=>$student_basic->student_tran_code,
                                            'student_serial_no' => $student_basic->student_serial_no,
                                            'tabulation_program' =>$student_basic->program,
                                            'tabulation_semester' =>$course_semester,
                                            'tabulation_year' =>$course_year,
                                            'tabulation_level' =>$course_basic->level,
                                            'tabulation_term' => $course_basic->term,
                                            'tabulation_course_id' =>$course_basic->course_code,
                                            'tabulation_course_title' =>$course_basic->course_title,
                                            'tabulation_course_type' =>$course_basic->course_type,
                                            'tabulatation_credit_hours' =>$course_basic->credit_hours,
                                            'tabulation_credit_earned' =>$course_basic->credit_hours,
                                            'tabulation_grade_point' =>$result_grade_point,
                                            'tabulation_grade' =>$result_grade,
                                            'tabulation_status' => '1',
                                            'created_at' =>$now,
                                            'updated_at' =>$now,
                                            'created_by' =>\Auth::user()->user_id,
                                            'updated_by' =>\Auth::user()->user_id,
                                            );


                                    $student_study_level_info=\DB::table('student_study_level')->where('student_tran_code', $student_basic->student_tran_code)->where('study_level_semester', $course_semester)->where('study_level_year', $course_year)->first();

                                    $study_level_tran_code=\Uuid::generate(4);
                                    $student_study_level_data=array(
                                        'study_level_tran_code' => $study_level_tran_code->string,
                                        'student_tran_code' =>  $student_basic->student_tran_code,
                                        'study_level_semester' => $course_semester,
                                        'study_level_year' => $course_year,
                                        'pre_advising_status' => 6,
                                        'study_level_status' => 1,
                                        'created_at' =>$now,
                                        'updated_at' =>$now ,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,
                                        );


                                    $student_accounts_fees=$student_accounts_info->accounts_fee_deatails;
                                    $accounts_fee_deatails=unserialize($student_accounts_fees);


                                    foreach ($accounts_fee_deatails as $key => $accounts_fees) {

                                        if($accounts_fees->accounts_fee_name_slug=='tution_fee'){
                                            $tution_fee_payment_receivable=($accounts_fees->accounts_fee_amount)*($course_basic->credit_hours);
                                        }

                                        if($accounts_fees->accounts_fee_name_slug=='trimester_fee'){
                                            $trimester_payment_receivable=($accounts_fees->accounts_fee_amount);
                                        }else{
                                            $trimester_payment_receivable=0;
                                        }
                                    }


                                    $payment_tution_transaction_tran_code=\Uuid::generate(4);
                                    $student_payment_tution_transactions_data=array(
                                        'payment_transaction_tran_code' => $payment_tution_transaction_tran_code->string,
                                        'payment_student_tran_code' => $student_basic->student_tran_code,
                                        'payment_student_serial_no' => $student_basic->student_serial_no,
                                        'payment_program' => $student_basic->program,
                                        'payment_semster' => $course_semester,
                                        'payment_year' => $course_year,
                                        'payment_transaction_fee_type' => 'tution_fee',
                                        'payment_receive_type' => '',
                                        'payment_receivable' => $tution_fee_payment_receivable,
                                        'payment_paid' => 0,
                                        'payment_remarks' => 'Not Paid',
                                        'payment_others' => 0,
                                        'payment_amounts' => 0,
                                        'payment_details' => $course_code,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,

                                        );


                                    $trimester_student_receivable_transaction_info=\DB::table('student_payment_transactions')->where('payment_student_tran_code', $student_basic->student_tran_code)->where('payment_semster', $course_semester)->where('payment_year', $course_year)->first();

                                    $payment_trimester_transaction_tran_code=\Uuid::generate(4);
                                    $student_payment_trimester_transactions_data=array(
                                        'payment_transaction_tran_code' => $payment_trimester_transaction_tran_code->string,
                                        'payment_student_tran_code' => $student_basic->student_tran_code,
                                        'payment_student_serial_no' => $student_basic->student_serial_no,
                                        'payment_program' => $student_basic->program,
                                        'payment_semster' => $course_semester,
                                        'payment_year' => $course_year,
                                        'payment_transaction_fee_type' => 'trimester_fee',
                                        'payment_receive_type' => '',
                                        'payment_receivable' => $trimester_payment_receivable,
                                        'payment_paid' => 0,
                                        'payment_remarks' => 'Not Paid',
                                        'payment_others' => 0,
                                        'payment_amounts' => 0,
                                        'payment_details' => $course_code,
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>\Auth::user()->user_id,
                                        'updated_by' =>\Auth::user()->user_id,

                                        );


                                    try{
                                        $success = \DB::transaction(function () use ($lab_result_store, $student_accepted_course_data, $student_payment_tution_transactions_data, $student_payment_trimester_transactions_data, $trimester_student_receivable_transaction_info, $student_study_level_info, $student_study_level_data) {

                                            for($i=0; $i<count($this->dbList); $i++){
                                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $lab_result_save=\DB::connection($this->dbList[$i])->table('student_lab_register')->insert($lab_result_store);

                                                $student_academic_tabulation_insert=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')->insert( $student_accepted_course_data);
                                                
                                                $student_tution_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_tution_transactions_data);

                                                if(empty($trimester_student_receivable_transaction_info)){
                                                    $student_trimester_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_trimester_transactions_data);
                                                    if(!$student_trimester_payment_transactions_save){
                                                        $error=1;
                                                    }
                                                }
                                                if(empty($student_study_level_info)){
                                                    $student_study_level_store=\DB::connection($this->dbList[$i])->table('student_study_level')->insert($student_study_level_data);
                                                    if(!$student_study_level_store){
                                                        $error=1;
                                                    }
                                                }
                                                if(!$lab_result_save || !$student_academic_tabulation_insert || !$student_tution_payment_transactions_save){
                                                    $error=1;
                                                }
                                            }

                                            if(!isset($error)){

                                                \App\System::EventLogWrite('insert,student_lab_register',json_encode($lab_result_store));
                                                \App\System::EventLogWrite('insert,student_academic_tabulation',json_encode($student_accepted_course_data));

                                                \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_tution_transactions_data));

                                                if(empty($trimester_student_receivable_transaction_info)){
                                                    \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_trimester_transactions_data));
                                                }

                                                if(empty($student_study_level_info)){
                                                    \App\System::EventLogWrite('insert,student_study_level',json_encode($student_study_level_data));
                                                }

                                                \App\System::TransactionCommit();
                                                
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

                                    return \Redirect::back()->with('message',"Course accepted  Successfully !!!");


                                //}return \Redirect::back()->with('errormessage','Invalid marks');

                            }return \Redirect::back()->with('errormessage','Marks is to be below 100');


                        }return \Redirect::back()->with('errormessage',"Invalid Course or accounts info.");

                    }return \Redirect::back()->with('errormessage',"Invalid student!!!");

                }return \Redirect::back()->withs('errormessage',"Invalid or missing value");
            }return \Redirect::back()->withs('errormessage',"Invalid or missing value");
        }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

    }



    /********************************************
    ## RegisterExistingStudentDeleteCourse
    *********************************************/

    public function RegisterExistingStudentDeleteCourse($student_serial_no, $course_code, $course_type, $course_year, $course_semester){

        $now=date('Y-m-d H:i:s');
        $no_of_subject='';

        if(!empty($student_serial_no) && !empty($course_code) && !empty($course_type) && !empty($course_year) && !empty($course_semester)){

            $student_basic=\DB::table('student_basic')
                        ->where('student_serial_no', $student_serial_no)
                        ->first();

            if(!empty($student_basic)){

                $course_basic=\DB::table('course_basic')
                        ->where('course_program', $student_basic->program)
                        ->where('course_code', $course_code)
                        ->first();

                $univ_program_info=\DB::table('univ_program')
                        ->where('program_id',$student_basic->program)
                        ->first();

                $student_accounts_info=\DB::table('student_accounts_info')
                        ->where('accounts_student_tran_code',$student_basic->student_tran_code)
                        ->first();

                if(!empty($course_basic) && !empty($student_accounts_info) && !empty($univ_program_info)){

                    $theory_sub_info=\DB::table('student_class_registers')
                                    ->where('student_tran_code',$student_basic->student_tran_code)
                                    ->where('class_semster',$course_semester)
                                    ->where('class_year',$course_year)
                                    ->get();

                    $lab_sub_info=\DB::table('student_lab_register')
                                    ->where('student_tran_code',$student_basic->student_tran_code)
                                    ->where('lab_semster',$course_semester)
                                    ->where('lab_year',$course_year)
                                    ->get();

                    if(!empty($theory_sub_info) && count($theory_sub_info)>1){
                        $no_of_subject='multiple';
                    }

                    if(!empty($lab_sub_info) && count($lab_sub_info)>1){
                        $no_of_subject='multiple';
                    }

                    if((!empty($theory_sub_info) && count($theory_sub_info)>=1) && (!empty($lab_sub_info) && count($lab_sub_info)>=1)){
                        $no_of_subject='multiple';
                    }

                    $delete_data_info=array(
                        'student_serial_no' => $student_serial_no,
                        'course_code' => $course_code,
                        'course_type' => $course_type,
                        'course_semester' => $course_semester,
                        'course_year' => $course_year,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->user_id,
                        'updated_by' =>\Auth::user()->user_id,
                        );


                    try{
                        $success = \DB::transaction(function () use ($student_serial_no, $course_code, $course_type, $student_basic, $course_basic,$course_year, $course_semester, $no_of_subject, $delete_data_info) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                                if($course_type == 'Theory'){

                                    $theory_sub_delete=\DB::connection($this->dbList[$i])->table('student_class_registers')
                                        ->where('student_tran_code',$student_basic->student_tran_code)
                                        ->where('class_course_code',$course_code)
                                        ->where('class_semster',$course_semester)
                                        ->where('class_year',$course_year)
                                        ->delete();


                                    if(!$theory_sub_delete){
                                        $error=1;
                                    }
                                }else{
                                    $lab_sub_delete=\DB::connection($this->dbList[$i])->table('student_lab_register')
                                        ->where('student_tran_code',$student_basic->student_tran_code)
                                        ->where('lab_course_code',$course_code)
                                        ->where('lab_semster',$course_semester)
                                        ->where('lab_year',$course_year)
                                        ->delete();

                                    if(!$lab_sub_delete){
                                        $error=1;
                                    }
                                }


                                $student_academic_tabulation_delete=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')
                                    ->where('student_tran_code',$student_basic->student_tran_code)
                                    ->where('tabulation_course_id', $course_code)
                                    ->where('tabulation_semester', $course_semester)
                                    ->where('tabulation_year', $course_year)
                                    ->delete();
                                
                                $student_tution_payment_transactions_delete=\DB::connection($this->dbList[$i])->table('student_payment_transactions')
                                    ->where('payment_details',$course_code)
                                    ->where('payment_transaction_fee_type','tution_fee')
                                    ->where('payment_student_tran_code',$student_basic->student_tran_code)
                                    ->where('payment_semster',$course_semester)
                                    ->where('payment_year',$course_year)
                                    ->delete();

                                if($no_of_subject != 'multiple'){

                                    $student_trimester_payment_transactions_delete=\DB::connection($this->dbList[$i])->table('student_payment_transactions')
                                    ->where('payment_transaction_fee_type', 'trimester_fee')
                                    ->where('payment_student_tran_code',$student_basic->student_tran_code)
                                    // ->where('payment_details',$course_code)
                                    ->where('payment_semster',$course_semester)
                                    ->where('payment_year',$course_year)
                                    ->delete();


                                    $student_study_level_delete=\DB::connection($this->dbList[$i])->table('student_study_level')
                                        ->where('student_tran_code', $student_basic->student_tran_code)
                                        ->where('study_level_semester',$course_semester)
                                        ->where('study_level_year',$course_year)
                                        ->delete();

                                    if(!$student_trimester_payment_transactions_delete || !$student_study_level_delete){
                                        $error=1;
                                    }
                                }

                                if(!$student_academic_tabulation_delete || !$student_tution_payment_transactions_delete){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){

                                if($course_type == 'Theory'){
                                    \App\System::EventLogWrite('delete,student_class_registers',json_encode($delete_data_info));
                                }else{
                                    \App\System::EventLogWrite('delete,student_lab_register',json_encode($delete_data_info));
                                }

                                \App\System::EventLogWrite('delete,student_academic_tabulation',json_encode($delete_data_info));

                                \App\System::EventLogWrite('delete,student_payment_transactions',json_encode($delete_data_info));

                                if($no_of_subject !='multiple'){
                                    \App\System::EventLogWrite('delete,student_payment_transactions',json_encode($delete_data_info));

                                    \App\System::EventLogWrite('delete,student_study_level',json_encode($delete_data_info));
                                }

                                \App\System::TransactionCommit();
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                        return \Redirect::back()->with('message','Successfully Undo');
     
                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::back()->with('errormessage','Something wrong in catch!!');
                    }


                }return \Redirect::back()->with('errormessage',"Invalid Course info.");

            }return \Redirect::back()->with('errormessage',"Invalid student!!!");


        }return \Redirect::back()->withs('errormessage',"Invalid or missing value");

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

                try{

                    $success = \DB::transaction(function () use ($invigilators_exam_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $exam_invigilators_save = \DB::connection($this->dbList[$i])->table('univ_invigilators_exam')->insert($invigilators_exam_data);
                            if(!$exam_invigilators_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,univ_invigilators_exam',json_encode($invigilators_exam_data));            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message',"Invigilators  Added Successfully!");


                  }catch(\Exception  $e){
                     $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                     \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Something wrong !!');
                  }

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

                $success = \DB::transaction(function () use ($type_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $course_categoty_delete = \DB::connection($this->dbList[$i])->table('univ_invigilators_exam')->where('invigilators_exam_tran_code',$type_slug)->delete();
                        if(!$course_categoty_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete','deleted ID: '.$type_slug);

                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Invigilator Deleted Successfully !!!');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('errormessage','Something wrong in catch !!!');
            }

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


                $success = \DB::transaction(function () use ($invigilators_update_data, $type_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $exam_invigilators_update=\DB::connection($this->dbList[$i])->table('univ_invigilators_exam')->where('invigilators_exam_tran_code',$type_slug)->update($invigilators_update_data);

                        if(!$exam_invigilators_update){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,univ_invigilators_exam',json_encode($invigilators_update_data));

                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('message','Invigilator Update Successfully !!!');


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/register/schedule/exam-schedule?tab=exam_invigilators')->with('errormessage','Something wrong in catch !!!');

            }

        }else return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    ## ExamInvigilatorsDownload 
    *********************************************/

    public function ExamInvigilatorsDownload(){
        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)
        ->leftJoin('univ_semester','univ_academic_calender.academic_calender_semester','=','univ_semester.semester_code')
        ->orderBy('univ_academic_calender.created_at','desc')
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



    /********************************************
    ## FacultyList 
    *********************************************/

    public function FacultyList(){
        $data['page_title'] = $this->page_title;

        if(isset($_GET['program']) || isset($_GET['department'])){

            $faculty_list = \DB::table('faculty_basic')->where(function($query){

             if(isset($_GET['department'])&&($_GET['department'] !=0)){
                $query->where(function ($q){
                    $q->where('department', $_GET['department']);
                });
            }

            if(isset($_GET['program'])&&($_GET['program'] !=0)){
                $query->where(function ($q){
                    $q->where('program', $_GET['program']);
                });
            }

        })
            ->leftJoin('univ_department','univ_department.department_no','=','faculty_basic.department')
            ->leftJoin('univ_program','univ_program.program_id','=','faculty_basic.program')
            ->select('faculty_basic.*','univ_department.*','univ_program.*')
            ->get();
            $data['faculty_list']= $faculty_list;
        }

        else{

            $faculty_list = \DB::table('faculty_basic')
            ->leftJoin('univ_department','univ_department.department_no','=','faculty_basic.department')
            ->leftJoin('univ_program','univ_program.program_id','=','faculty_basic.program')
            ->select('faculty_basic.*','univ_department.*','univ_program.*')
            ->get();

            $data['faculty_list']= $faculty_list;
        }
        return \View::make('pages.register.register-faculty-list',$data);

    }




    /********************************************
    ## FacultyListExcelDownload 
    *********************************************/

     public function FacultyListExcelDownload(){

        $excel_name = 'faculty_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                if(isset($_GET['program']) || isset($_GET['department'])){

                    $faculty_list = \DB::table('faculty_basic')->where(function($query){

                       if(isset($_GET['department'])&&($_GET['department'] !=0)){
                        $query->where(function ($q){
                            $q->where('department', $_GET['department']);
                        });
                    }

                    if(isset($_GET['program'])&&($_GET['program'] !=0)){
                        $query->where(function ($q){
                            $q->where('program', $_GET['program']);
                        });
                    }

                })
                    ->leftJoin('univ_department','univ_department.department_no','=','faculty_basic.department')
                    ->leftJoin('univ_program','univ_program.program_id','=','faculty_basic.program')
                    ->select('faculty_basic.*','univ_department.*','univ_program.*')
                    ->get();
                    $data['faculty_list']= $faculty_list;
                }
                else{

                    $faculty_list = \DB::table('faculty_basic')
                    ->leftJoin('univ_department','univ_department.department_no','=','faculty_basic.department')
                    ->leftJoin('univ_program','univ_program.program_id','=','faculty_basic.program')
                    ->select('faculty_basic.*','univ_department.*','univ_program.*')
                    ->get();

                    $data['faculty_list']= $faculty_list;
                }

                $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.excel-faculty-list',$data);
            });
        })->export('xlsx');

    }



    /********************************************
    ## EmployeeList 
    *********************************************/

    public function EmployeeList(){
        $data['page_title'] = $this->page_title;

        if(isset($_GET['employee_department'])){

            $employee_list = \DB::table('employee_basic')
            ->where('pro_designation', $_GET['employee_department'])
            ->get();
            $data['employee_list']= $employee_list;
        }

        else{

            $employee_list = \DB::table('employee_basic')
            ->get();

            $data['employee_list']= $employee_list;
        }
        return \View::make('pages.register.register-employee-list',$data);

    }



    /********************************************
    ## EmployeeListExcelDownload 
    *********************************************/

    public function EmployeeListExcelDownload(){

        $excel_name = 'employee_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                if(isset($_GET['employee_department'])){

                    $employee_list = \DB::table('employee_basic')
                    ->where('pro_designation', $_GET['employee_department'])
                    ->get();
                    $data['employee_list']= $employee_list;
                }

                else{

                    $employee_list = \DB::table('employee_basic')
                    ->get();

                    $data['employee_list']= $employee_list;
                }
                $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.excel-employee-list',$data);
            });
        })->export('xlsx');

    }



    /********************************************
    ## StudentListExcelDownload 
    *********************************************/

    public function StudentListExcelDownload(){

        $excel_name = 'student_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*-----------------------------Get Request-----------------------*/
                if(isset($_GET['program']) && isset($_GET['batch_no'])){

                    $student_list = \DB::table('student_basic')->where(function($query){

                     if(isset($_GET['program'])&&($_GET['program'] !=0)){
                        $query->where(function ($q){
                            $q->where('program', $_GET['program']);
                        });
                    }
                    if(isset($_GET['batch_no'])&&($_GET['batch_no'] !=0)){
                        $query->where(function ($q){
                            $q->where('batch_no', $_GET['batch_no']);
                        });
                    }


                })
                    ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                    ->select('student_basic.*','univ_program.*')
                    ->get();
                    $data['student_list']= $student_list;
                }


                $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.excel-student-list',$data);
            });
        })->export('xlsx');

    }





     /********************************************
    ## RegisterAllSummery
    *********************************************/
    public function RegisterAllSummery(){
        $data['page_title'] = $this->page_title;


        $today = date('Y-m-d');

        if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){


            $applicant_list = \DB::table('applicant_basic')
                ->where('payment_status','!=','0')
                ->where(function($query){

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
                ->leftJoin('univ_program','univ_program.program_id','=','applicant_basic.program')
                ->select('applicant_basic.*','univ_program.*')
                ->get();
                $data['applicant_list']=count($applicant_list);



            $student_list = \DB::table('student_basic')
                ->where('student_status','>=','1')
                ->where(function($query){

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
                $data['student_list']=count($student_list);


            $faculty_list = \DB::table('faculty_basic')
                ->where('faculty_status','2')
                ->where(function($query){

                    if(isset($_GET['program'])&&($_GET['program'] !=0)){
                        $query->where(function ($q){
                            $q->where('program', $_GET['program']);
                        });
                    }


                })
                ->get();
                $data['faculty_list']=count($faculty_list);



            $employee_list = \DB::table('employee_basic')
                ->where('employee_status','2')
                ->get();
                $data['employee_list']=count($employee_list);

        }else{


            $applicant_list = \DB::table('applicant_basic')->where('payment_status','!=','0')->get();
            $student_list = \DB::table('student_basic')->where('student_status','>=','1')->get();
            $faculty_list = \DB::table('faculty_basic')->where('faculty_status','2')->get();
            $employee_list = \DB::table('employee_basic')->where('employee_status','2')->get();

                 
            $data['applicant_list']=count($applicant_list);

            $data['student_list']=count($student_list);

            $data['faculty_list']=count($faculty_list);

            $data['employee_list']=count($employee_list);


        }

        return \View::make('pages.register.all-summery',$data);
    }




    /********************************************
    ## RegisterAccountSummeryPage 
    *********************************************/

    public function RegisterAccountSummeryPage(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){


            $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('student_payment_transactions.payment_amounts','!=','0')
                ->where(function($query){

                           if(isset($_GET['program']) && ($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_semster', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year']) && ($_GET['academic_year'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_year', $_GET['academic_year']);
                                  });
                            }
                        }) 
                ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                ->orderBy('student_payment_transactions.updated_at','desc')
                ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                ->get();

            $total_amount=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('student_payment_transactions.payment_amounts','!=','0')
                ->where(function($query){

                           if(isset($_GET['program']) && ($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_program', $_GET['program']);
                                  });
                            }
                            if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_semster', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year']) && ($_GET['academic_year'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_year', $_GET['academic_year']);
                                  });
                            }
                        }) 
                ->sum('student_payment_transactions.payment_amounts');

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $year=date('Y');
            $univ_academic_calender_detail=\DB::table('univ_academic_calender')
                ->where('univ_academic_calender.academic_calender_status','1')
                ->orderBy('univ_academic_calender.created_at','desc')
                ->first();
            if(!empty($univ_academic_calender_detail)){
                $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                    ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                    ->where('student_payment_transactions.payment_amounts','!=','0')
                    ->where('student_payment_transactions.payment_year',$univ_academic_calender_detail->academic_calender_year)
                    ->where('student_payment_transactions.payment_semster',$univ_academic_calender_detail->academic_calender_semester)
                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                    ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                    ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                    ->orderBy('student_payment_transactions.updated_at','desc')
                    ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                    ->get();

                $total_amount=\DB::table('student_payment_transactions')
                    ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                    ->where('student_payment_transactions.payment_amounts','!=','0')
                    ->where('student_payment_transactions.payment_year',$univ_academic_calender_detail->academic_calender_year)
                    ->where('student_payment_transactions.payment_semster',$univ_academic_calender_detail->academic_calender_semester)
                    ->sum('student_payment_transactions.payment_amounts');

            }else{
                $student_payment_transaction_detail='';  
                $total_amount=0;

            }

        }

        $data['total_amount'] = $total_amount;
        $data['student_payment_transaction_detail'] = $student_payment_transaction_detail;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-account-summery',$data);
    }



    /********************************************
    ## RegisterStudentGradeSheet 
    *********************************************/

    public function RegisterStudentGradeSheet(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')
        ->select('academic_calender_year', \DB::raw('count(*) as total'))
        ->groupBy('academic_calender_year')
        ->get();
        
        $data['univ_academic_calender']=$univ_academic_calender;

        $semester_list=\DB::table('univ_semester')->get();
        $data['semester_list']=$semester_list;



        if(isset($_GET['student_serial_no']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

            $semester=$_GET['semester'];
            $academic_year=$_GET['academic_year'];
            $student_serial_no=$_GET['student_serial_no'];
        $all_course = array();
            $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();

            $student_tabulation_info=\DB::table('student_academic_tabulation')
                ->where('student_serial_no',$student_serial_no)
                ->where('tabulation_status','=',1)
                ->where(function($query){

                        if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                            $query->where(function ($q){
                                $q->where('tabulation_semester', $_GET['semester']);
                              });
                        }

                        if(isset($_GET['academic_year']) && ($_GET['academic_year'] != 0)){
                            $query->where(function ($q){
                                $q->where('tabulation_year', $_GET['academic_year']);
                              });
                        }

                }) 
                ->select('tabulation_course_id', DB::raw('count(*) as total'))
                ->groupBy('tabulation_course_id')
                ->get();

                foreach ($student_tabulation_info as $key => $value) {
                    $list=(string)$value->tabulation_course_id;

                    $student_tabulation_info2=\DB::table('student_academic_tabulation')
                        ->where('student_serial_no',$student_serial_no)
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

            $student_cgpa=\DB::table('student_academic_tabulation')
                ->where('student_serial_no',$student_serial_no)
                ->where('tabulation_status','=',1)
                ->whereIn('tabulation_tran_code',$all_course)
                ->where(function($query){

                        if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                            $query->where(function ($q){
                                $q->where('tabulation_semester', $_GET['semester']);
                              });
                        }

                        if(isset($_GET['academic_year']) && ($_GET['academic_year'] != 0)){
                            $query->where(function ($q){
                                $q->where('tabulation_year', $_GET['academic_year']);
                              });
                        }

                    }) 
                ->get();

            $total_taken_credit=0;
            $total_earned_credit=0;
            $total_point=0;

            foreach ($student_cgpa as $key => $tabulation) {

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
                ->where('student_serial_no',$student_serial_no)
                ->where('tabulation_status','=',1)
                ->where(function($query){

                    if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                        $query->where(function ($q){
                            $q->where('tabulation_semester', $_GET['semester']);
                          });
                    }

                    if(isset($_GET['academic_year']) && ($_GET['academic_year'] !=0)){
                        $query->where(function ($q){
                            $q->where('tabulation_year', $_GET['academic_year']);
                          });
                    }

                }) 
                ->get();
            $data['student_result']=$student_result;

            $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first(); 
            $data['student_info']=$student_info;

            $semester_info=\DB::table('univ_semester')->where('semester_code', $semester)->first(); 
            $data['semester_info']=$semester_info;
            $data['year']=$academic_year;

        }

        return \View::make('pages.register.register-student-grade-sheet',$data);


    }


    /********************************************
    ## RegisterStudentSupplimentryCourse 
    *********************************************/

    public function RegisterStudentSupplimentryCourse(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->select('academic_calender_year', \DB::raw('count(*) as total'))
            ->groupBy('academic_calender_year')
            ->get();
        
        $data['univ_academic_calender']=$univ_academic_calender;

        $univ_current_academic_calender=\DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)
            ->orderBy('univ_academic_calender.created_at','desc')
            ->first();
        
        $data['univ_current_academic_calender']=$univ_current_academic_calender;

        $semester_list=\DB::table('univ_semester')->get();
        $data['semester_list']=$semester_list;

        if(isset($_GET['student_serial_no']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

            $semester=$_GET['semester'];
            $academic_year=$_GET['academic_year'];
            $student_serial_no=$_GET['student_serial_no'];

            $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();
            $data['student_info']=$student_info;

            if(!empty($student_info)){

                $semester_info=\DB::table('univ_semester')->where('semester_code', $semester)->first(); 
                $data['semester_info']=$semester_info;
                $data['year']=$academic_year;

                $theory_courses=\DB::table('student_class_registers')
                    ->where('student_class_registers.student_tran_code',$student_info->student_tran_code)
                    ->where('class_program', $student_info->program)
                    // ->where('class_semster', $univ_academic_calender->academic_calender_semester)
                    // ->where('class_year', $univ_academic_calender->academic_calender_year)
                    ->where('class_result_status', 1)
                    ->where(function($query){

                            if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('class_semster', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year']) && ($_GET['academic_year'] != 0)){
                                $query->where(function ($q){
                                    $q->where('class_year', $_GET['academic_year']);
                                  });
                            }

                    }) 
                    ->leftjoin('student_basic','student_basic.student_tran_code','=','student_class_registers.student_tran_code')
                    ->leftjoin('course_basic','course_basic.course_code','=','student_class_registers.class_course_code')
                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_class_registers.class_semster')
                    ->get();

                $data['theory_courses']=$theory_courses;

                $lab_courses=\DB::table('student_lab_register')
                    ->where('student_lab_register.student_tran_code',$student_info->student_tran_code)
                    ->where('lab_program', $student_info->program)
                    // ->where('lab_semster', $univ_academic_calender->academic_calender_semester)
                    // ->where('lab_year', $univ_academic_calender->academic_calender_year)
                    ->where('lab_result_status', 1)
                    ->where(function($query){

                            if(isset($_GET['semester']) && ($_GET['semester'] !=0)){
                                $query->where(function ($q){
                                    $q->where('lab_semster', $_GET['semester']);
                                  });
                            }

                            if(isset($_GET['academic_year']) && ($_GET['academic_year'] != 0)){
                                $query->where(function ($q){
                                    $q->where('lab_year', $_GET['academic_year']);
                                  });
                            }

                    })
                    ->leftjoin('student_basic','student_basic.student_tran_code','=','student_lab_register.student_tran_code')
                    ->leftjoin('course_basic','course_basic.course_code','=','student_lab_register.lab_course_code')
                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_lab_register.lab_semster')
                    ->get();

                $data['lab_courses']=$lab_courses;
            }
        }

        return \View::make('pages.register.register-supplimentry-course',$data);

    }



    /********************************************
    ## SupplimentryCourseResultUpdate
    *********************************************/

    public function SupplimentryCourseResultUpdate($student_serial_no, $course_code, $program, $semester, $year){

        $now=date('Y-m-d H:i:s');

        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)
            ->orderBy('univ_academic_calender.created_at','desc')
            ->first();

        $student_basic=\DB::table('student_basic')
            ->where('student_serial_no', $student_serial_no)
            ->first();

        $course_basic=\DB::table('course_basic')
            ->where('course_program', $student_basic->program)
            ->where('course_code', $course_code)
            ->first();

        if(!empty($univ_academic_calender)){

            try{

                if($course_basic->course_type=='Theory'){

                    $rule = [
                        'class_quiz_1'=>'Required|numeric',
                        'class_quiz_2'=>'Required|numeric',
                        'class_quiz_3'=>'Required|numeric',
                        'class_quiz_4'=>'Required|numeric',
                        'class_attendance'=>'Required|numeric',
                        'class_participation'=>'Required|numeric',
                        'class_presentaion'=>'Required|numeric',
                        'class_mid_term_exam'=>'Required|numeric',
                        'class_final_exam'=>'Required|numeric',
                    ];

                    $v = \Validator::make(\Request::all(),$rule);


                    if($v->passes()){

                        if(\Request::input('class_quiz_1')){
                          $class_quiz_1=(float)\Request::input('class_quiz_1');
                        }else{
                          $class_quiz_1=null;
                        }
                        if(\Request::input('class_quiz_2')){
                          $class_quiz_2=(float)\Request::input('class_quiz_2');
                        }else{
                          $class_quiz_2=null;
                        }
                        if(!empty(\Request::input('class_quiz_3'))){
                          $class_quiz_3=(float)\Request::input('class_quiz_3');
                        }else{
                          $class_quiz_3=null;
                        }
                        if(!empty(\Request::input('class_quiz_4'))){
                          $class_quiz_4=(float)\Request::input('class_quiz_4');
                        }else{
                          $class_quiz_4=null;
                        }
                        if(!empty(\Request::input('class_attendance'))){
                          $class_attendance=(float)\Request::input('class_attendance');
                        }else{
                          $class_attendance=null;
                        }
                        if(!empty(\Request::input('class_participation'))){
                          $class_participation=(float)\Request::input('class_participation');
                        }else{
                          $class_participation=null;
                        }
                        if(!empty(\Request::input('class_presentaion'))){
                          $class_presentaion=(float)\Request::input('class_presentaion');
                        }else{
                          $class_presentaion=null;
                        }
                        if(!empty(\Request::input('class_mid_term_exam'))){
                          $class_mid_term_exam=(float)\Request::input('class_mid_term_exam');
                        }else{
                          $class_mid_term_exam=null;
                        }
                        if(!empty(\Request::input('class_final_exam'))){
                          $class_final_exam=(float)\Request::input('class_final_exam');
                        }else{
                          $class_final_exam=null;
                        }
                        

                        $quiz_total=0;
                        $count=0;
                        if($class_quiz_1  >=0){
                          $quiz_total= $quiz_total+$class_quiz_1;
                          $count=$count+1;
                        }
                        if($class_quiz_2 >=0){
                          $quiz_total= $quiz_total+$class_quiz_2;
                          $count=$count+1;
                        }
                        if($class_quiz_3  >=0){
                          $quiz_total= $quiz_total+$class_quiz_3;
                          $count=$count+1;
                        }
                        if($class_quiz_4 >=0){
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

                            $supplimentry_booking_course_update=array(
                                  'supplimentry_student_course_status' => '2',
                                  'updated_at' => $now,
                                  'updated_by' => \Auth::user()->user_id,
                                  );


                                $success = \DB::transaction(function () use ($head_result_update, $student_basic, $course_code, $univ_academic_calender, $program, $supplimentry_booking_course_update, $semester, $year) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                            $head_result_update_save=\DB::connection($this->dbList[$i])->table('student_class_registers')
                                                   ->where('student_tran_code', $student_basic->student_tran_code)
                                                   ->where('class_course_code', $course_code)
                                                   ->where('class_semster', $semester)
                                                   ->where('class_year', $year)
                                                   ->where('class_program', $program)
                                                   ->where('class_result_status', 1)
                                                   ->update($head_result_update);

                                            $supplimentry_class_course_save=\DB::connection($this->dbList[$i])->table('student_supplimentry_course')
                                                   ->where('supplimentry_student_tran_code', $student_basic->student_tran_code)
                                                   ->where('supplimentry_student_course_code', $course_code)
                                                   ->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
                                                   ->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
                                                   ->update($supplimentry_booking_course_update);

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
                                        ->where('class_semster', $semester)
                                        ->where('class_year', $year)
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
                                        // 'tabulation_status' => $tabulation_status,
                                        'updated_at' => $now,
                                    );


                                    $success = \DB::transaction(function () use ($head_result_update, $student_basic, $course_code, $univ_academic_calender, $program, $student_academic_tabulation_update_data, $semester, $year) {

                                        for($i=0; $i<count($this->dbList); $i++){
                                          $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')
                                                    ->where('student_tran_code', $student_basic->student_tran_code)
                                                    ->where('tabulation_program', $program)
                                                    ->where('tabulation_semester', $semester)
                                                    ->where('tabulation_year', $year)
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

                                    return \Redirect::back()->with('message','Successfully Updated');
                                }return \Redirect::back()->with('errormessage','Something wrong please try again');


                            }return \Redirect::back()->with('errormessage','Invalid marks');

                        }return \Redirect::back()->with('errormessage','Marks is to be below 100');
                    }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());

                }
                else{


                    $rule = [
                        //'lab_attendance'=>'Required|numeric',
                        //'lab_performance'=>'Required|numeric',
                        //'lab_reprot'=>'Required|numeric',
                        //'lab_verbal'=>'Required|numeric',
                        //'lab_final'=>'Required|numeric',
                    ];

                    $v = \Validator::make(\Request::all(),$rule);


                    if($v->passes()){

                        $total_marks=0;
                        if(!empty(\Request::input('lab_attendance'))){
                          $lab_attendance=\Request::input('lab_attendance');
                          $total_marks=$total_marks+$lab_attendance;
                        }else{
                          $lab_attendance=null;
                        }
                        if(!empty(\Request::input('lab_performance'))){
                          $lab_performance=\Request::input('lab_performance');
                          $total_marks=$total_marks+$lab_performance;
                        }else{
                          $lab_performance=null;
                        }
                        if(!empty(\Request::input('lab_reprot'))){
                          $lab_reprot=\Request::input('lab_reprot');
                          $total_marks=$total_marks+$lab_reprot;
                        }else{
                          $lab_reprot=null;
                        }
                        if(!empty(\Request::input('lab_verbal'))){
                          $lab_verbal=\Request::input('lab_verbal');
                          $total_marks=$total_marks+$lab_verbal;
                        }else{
                          $lab_verbal=null;
                        }
                        if(!empty(\Request::input('lab_final'))){
                          $lab_final=\Request::input('lab_final');
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

                                $supplimentry_booking_lab_course_update=array(
                                  'supplimentry_student_course_status' => '2',
                                  'updated_at' => $now,
                                  'updated_by' => \Auth::user()->user_id,
                                  );



                                    $success = \DB::transaction(function () use ($lab_result_store, $student_serial_no, $course_code, $univ_academic_calender, $program, $student_basic, $supplimentry_booking_lab_course_update, $semester, $year) {

                                        for($i=0; $i<count($this->dbList); $i++){
                                          $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                              $head_result_update_save=\DB::connection($this->dbList[$i])->table('student_lab_register')
                                                     ->where('student_serial_no', $student_serial_no)
                                                     ->where('lab_course_code', $course_code)
                                                     ->where('lab_semster', $semester)
                                                     ->where('lab_year', $year)
                                                     ->where('lab_program', $program)
                                                     ->where('lab_result_status', 1)
                                                     ->update($lab_result_store);


                                                $supplimentry_lab_course_save=\DB::connection($this->dbList[$i])->table('student_supplimentry_course')
                                                   ->where('supplimentry_student_tran_code', $student_basic->student_tran_code)
                                                   ->where('supplimentry_student_course_code', $course_code)
                                                   ->where('supplimentry_student_semster', $univ_academic_calender->academic_calender_semester)
                                                   ->where('supplimentry_student_year', $univ_academic_calender->academic_calender_year)
                                                   ->update($supplimentry_booking_lab_course_update);


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


                                $student_lab_register=\DB::table('student_lab_register')
                                    ->where('student_tran_code', $student_basic->student_tran_code)
                                    ->where('lab_program', $program)
                                    ->where('lab_semster', $semester)
                                    ->where('lab_year', $year)
                                    ->where('lab_course_code', $course_code)
                                    ->first();

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
                                      // 'tabulation_status' => $tabulation_status,
                                      'updated_at' => $now,

                                      );

                                    $success = \DB::transaction(function () use ($student_serial_no, $student_basic, $course_code, $univ_academic_calender, $program, $student_academic_tabulation_update_data, $semester, $year) {

                                        for($i=0; $i<count($this->dbList); $i++){
                                          $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                                $student_academic_tabulation_update=\DB::connection($this->dbList[$i])->table('student_academic_tabulation')
                                                    ->where('student_tran_code', $student_basic->student_tran_code)
                                                    ->where('tabulation_program', $program)
                                                    ->where('tabulation_semester', $semester)
                                                    ->where('tabulation_year', $year)
                                                    ->where('tabulation_course_id', $course_code)
                                                    ->update($student_academic_tabulation_update_data);

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

                            //}return \Redirect::back()->with('errormessage','Invalid mark please try again');
                        }return \Redirect::back()->with('errormessage','Marks is to be below 100');
                    }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
                }

            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


        }else return \Redirect::back()->with('message',"Academic Calender Not Set Yet !");


   }


    /********************************************
    ## RegisterStudentBookingSupplimentryCourse 
    *********************************************/

    public function RegisterStudentBookingSupplimentryCourse(){

        $data['page_title'] = $this->page_title;

        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)
            ->orderBy('univ_academic_calender.created_at','desc')
            ->first();
        
        $data['univ_academic_calender']=$univ_academic_calender;

        $semester_list=\DB::table('univ_semester')->get();
        $data['semester_list']=$semester_list;

        if(isset($_GET['student_serial_no'])){

            $student_serial_no=$_GET['student_serial_no'];

            $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();
            $data['student_info']=$student_info;

            if(!empty($student_info)){



                $theory_courses=\DB::table('student_class_registers')
                    ->where('student_class_registers.student_tran_code',$student_info->student_tran_code)
                    ->where('class_program', $student_info->program)
                    ->where('class_result_status', 1)
                    ->leftjoin('student_basic','student_basic.student_tran_code','=','student_class_registers.student_tran_code')
                    ->leftjoin('course_basic','course_basic.course_code','=','student_class_registers.class_course_code')
                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_class_registers.class_semster')
                    ->get();

                $data['theory_courses']=$theory_courses;

                $lab_courses=\DB::table('student_lab_register')
                    ->where('student_lab_register.student_tran_code',$student_info->student_tran_code)
                    ->where('lab_program', $student_info->program)
                    ->where('lab_result_status', 1)
                    ->leftjoin('student_basic','student_basic.student_tran_code','=','student_lab_register.student_tran_code')
                    ->leftjoin('course_basic','course_basic.course_code','=','student_lab_register.lab_course_code')
                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_lab_register.lab_semster')
                    ->get();

                $data['lab_courses']=$lab_courses;
            }
        }

        return \View::make('pages.register.register-student-booking-supplimentry-course',$data);

    }


    /********************************************
    ## RegisterSupplimentryCourseBookingConfirm
    *********************************************/

    public function RegisterSupplimentryCourseBookingConfirm(){

        $now=date('Y-m-d H:i:s');

        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->where('academic_calender_status',1)
            ->orderBy('univ_academic_calender.created_at','desc')
            ->first();

        $student_basic=\DB::table('student_basic')
            ->where('student_tran_code', \Request::input('supplimentry_student_tran_code'))
            ->first();
        $course_basic=\DB::table('course_basic')
            ->where('course_code', \Request::input('supplimentry_student_course_code'))
            ->first();

        if(!empty($univ_academic_calender) && !empty($student_basic) && !empty($course_basic)){

            try{


                    $rule = [
                        'supplimentry_student_payment_slip_no'=>'Required',
                    ];

                    $v = \Validator::make(\Request::all(),$rule);


                    if($v->passes()){
                        $student_supplimentry_course_tran_code=\Uuid::generate(4);
                        $supplimentry_course_booking_data=array(
                                  'student_supplimentry_course_tran_code' => $student_supplimentry_course_tran_code->string,
                                  'class_or_lab_register_tran_code' => \Request::input('class_or_lab_register_tran_code'),
                                  'supplimentry_student_tran_code' => \Request::input('supplimentry_student_tran_code'),
                                  'supplimentry_student_department' => \Request::input('supplimentry_student_department'),
                                  'supplimentry_student_program' => \Request::input('supplimentry_student_program'),
                                  'supplimentry_student_course_code' => \Request::input('supplimentry_student_course_code'),
                                  'supplimentry_student_course_type' => \Request::input('supplimentry_student_course_type'),
                                  'supplimentry_student_payment_slip_no' =>\Request::input('supplimentry_student_payment_slip_no'),
                  'supplimentry_course_semster' => \Request::input('supplimentry_course_semster'),
                                  'supplimentry_course_year' => \Request::input('supplimentry_course_year'),
                                  'supplimentry_student_semster' => $univ_academic_calender->academic_calender_semester,
                                  'supplimentry_student_year' =>$univ_academic_calender->academic_calender_year,
                                  'supplimentry_student_remarks' =>'Supplimentry Course Booking',
                                  'supplimentry_student_course_status' =>'1',
                                  'created_at' => $now,
                                  'updated_at' => $now,
                                  'created_by' => \Auth::user()->user_id,
                                  'updated_by' => \Auth::user()->user_id,
                                  );




                                $success = \DB::transaction(function () use ($supplimentry_course_booking_data, $student_basic, $univ_academic_calender, $course_basic) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                            $supplimentry_booking_course_save=\DB::connection($this->dbList[$i])->table('student_supplimentry_course')
                                                   ->insert($supplimentry_course_booking_data);

                                        if(!$supplimentry_booking_course_save){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::TransactionCommit();
                                        \App\System::EventLogWrite('insert,student_supplimentry_course',json_encode($supplimentry_course_booking_data));
                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }

                                });

                            return \Redirect::back()->with('message','Successfully booking supplimentry course.');

                    }return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());



            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


        }else return \Redirect::back()->with('message',"Academic Calender Not Set Yet !");


    }




    /********************************************
    ## RegisterStudentBookingSupplimentryCourseList 
    *********************************************/

    public function RegisterStudentBookingSupplimentryCourseList(){

        $data['page_title'] = $this->page_title;


        $univ_academic_calender=\DB::table('univ_academic_calender')
            ->select('academic_calender_year', \DB::raw('count(*) as total'))
            ->groupBy('academic_calender_year')
            ->get();
        
        $data['univ_academic_calender']=$univ_academic_calender;


        if(!empty($_GET['program']) && !empty($_GET['course']) && !empty($_GET['semester']) && !empty($_GET['academic_year'])){ 
            
            $semester_info=\DB::table('univ_semester')->where('semester_code', $_GET['semester'])->first();
            $data['semester_info']=$semester_info;

            $supplimentry_booking_course_info=\DB::table('student_supplimentry_course')
                ->where('supplimentry_student_program', $_GET['program'])
                ->where('supplimentry_student_course_code', $_GET['course'])
                ->where('supplimentry_student_semster', $_GET['semester'])
                ->where('supplimentry_student_year', $_GET['academic_year'])
                ->leftjoin('student_basic','student_basic.student_tran_code','=','student_supplimentry_course.supplimentry_student_tran_code')
                ->leftjoin('course_basic','course_basic.course_code','=','student_supplimentry_course.supplimentry_student_course_code')
                ->leftjoin('univ_semester','univ_semester.semester_code','=','student_supplimentry_course.supplimentry_student_semster')
                ->leftjoin('univ_program','univ_program.program_id','=','student_supplimentry_course.supplimentry_student_program')
                ->get();

            $data['supplimentry_booking_course_info']=$supplimentry_booking_course_info;

        }return \View::make('pages.register.register-student-booking-supplimentry-course-list',$data);

    }


    /********************************************
    ## RegisterSupplimentryCourseBookingDelete
    *********************************************/

    public function RegisterSupplimentryCourseBookingDelete($supplimentry_tran_code){

        $now=date('Y-m-d H:i:s');

        $student_supplimentry_course_info=\DB::table('student_supplimentry_course')
            ->where('student_supplimentry_course_tran_code',$supplimentry_tran_code)
            ->first();
        if(!empty($student_supplimentry_course_info)){

            try{

                $success = \DB::transaction(function () use ($supplimentry_tran_code) {

                    for($i=0; $i<count($this->dbList); $i++){
                      $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $supplimentry_booking_course_delete=\DB::connection($this->dbList[$i])->table('student_supplimentry_course')
                                   ->where('student_supplimentry_course_tran_code',$supplimentry_tran_code)
                                   ->where('supplimentry_student_course_status','!=','2')
                                   ->delete();

                        if(!$supplimentry_booking_course_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,student_supplimentry_course',json_encode($supplimentry_tran_code));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }

                });

                return \Redirect::back()->with('message','Successfully delete supplimentry course booking.');


            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Something wrong in catch');
            }


        }else return \Redirect::back()->with('message',"Invalid supplimentry booking course.");


    }



    
    /********************************************
    ## RegisterStudentBatchSemesterChange
    *********************************************/

    public function RegisterStudentBatchSemesterChange(){

        if(isset($_GET['student_no'])){
            $select_student = \DB::table('student_basic')
                            ->where('student_serial_no',$_GET['student_no'])
                            ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
                            ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
                            ->first();

            $data['select_student'] = $select_student;
            
        }
        $data['page_title'] = $this->page_title;
        return \View::make('pages.register.register-student-change-batch-semester',$data);
    }



    /********************************************
    ## RegisterStudentBatchSemesterChangeConfirm
    *********************************************/

    public function RegisterStudentBatchSemesterChangeConfirm(){


        $rule = [
            'student_no' => 'Required',
            'change_year' => 'Required',
            'change_semester' => 'Required',
            'change_batch' => 'Required',
        ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $student_no=\Request::input('student_no');
            $change_year=\Request::input('change_year');
            $change_semester=\Request::input('change_semester');
            $change_batch=\Request::input('change_batch');

            $select_student_info = \DB::table('student_basic')
                            ->where('student_serial_no',$student_no)
                            ->leftJoin('univ_semester','student_basic.semester','=','univ_semester.semester_code')
                            ->leftJoin('univ_program','student_basic.program','=','univ_program.program_id')
                            ->first();

            if(!empty($select_student_info)){

                    $update_student_data=array(
                        'batch_no' => $change_batch,
                        'semester' => $change_semester,
                        'academic_year'=> $change_year,
                        'updated_by' =>\Auth::user()->user_id,
                        'updated_at' =>$now,
                        );


                    try{

                        $success = \DB::transaction(function () use ($update_student_data, $student_no) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $student_update_info=\DB::connection($this->dbList[$i])->table('student_basic')
                                ->where('student_serial_no',$student_no)
                                ->update($update_student_data);
                                if(!$student_update_info){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,student_basic',json_encode($update_student_data));

                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                    }catch(\Exception  $e){
                         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                         \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/register/student/batch-semester/change?student_no='.$student_no)->with('message','Something wrong !!');
                    }

                return \Redirect::to('/register/student/batch-semester/change?student_no='.$student_no)->with('message',"Student updated successfully!");

            }else return \Redirect::to('/register/student/batch-semester/change?student_no=?student_no='.$student_no)->with('message',"Invalid student Id!"); 

        }return \Redirect::to('/register/student/batch-semester/change')->withInput(\Request::all())->withErrors($v->messages());


    }



     /********************************************
    ## StudentCertificateList
    *********************************************/
    public function StudentCertificateList(){
        $data['page_title'] = $this->page_title;

        if(isset($_GET['program']) && isset($_GET['batch_no'])){

            $student_list = \DB::table('student_basic')
            ->where(function($query){

                 if(isset($_GET['program'])&&($_GET['program'] !=0)){
                    $query->where(function ($q){
                        $q->where('program', $_GET['program']);
                    });
                }

                if(isset($_GET['batch_no'])){
                    $query->where(function ($q){
                        $q->where('batch_no', $_GET['batch_no']);
                    });
                }

            })
            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
            ->select('student_basic.*','univ_program.*')
            ->get();

            $data['student_list']= $student_list;

        }

        $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))
            ->groupBy('batch_no')->get();

        $data['batch_list']= $batch_list;

        return \View::make('pages.register.register-student-certificate-list',$data);
    }


    /********************************************
    ## Register Student Certificate Confirm
    *********************************************/
    public function RegisterStudentCertificateConfirm($student_serial_list, $action){
        $now=date('Y-m-d H:i:s');
        
        $student_certification_data=array(
            'certificate_status' =>$action,
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->user_id,
            ); 


        try{

            $success = \DB::transaction(function () use ($student_certification_data, $student_serial_list) {

                for($i=0; $i<count($this->dbList); $i++){
                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $certified_student_confirm=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_serial_list)->update($student_certification_data);

                    if(!$certified_student_confirm){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::TransactionCommit();

                    \App\System::EventLogWrite('update,student_basic',json_encode($student_certification_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

            if($action == '1'){
                return \Redirect::back()->with('message','Successfully Certified.');
            }else{
                return \Redirect::back()->with('message','Certified Cancel.');
            }


        }catch(\Exception  $e){
           $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
           \App\System::ErrorLogWrite($message);

           return \Redirect::back()->with('message','Something wrong !!');
       }
    }














    /*----------------------------------------------------------------------------------*/
}
