<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\System;
use App\Email;
use App\Accounts;
use Carbon;
use Exception;


/*******************************
#
## Accounts Controller
#
*******************************/

class AccountsController extends Controller
{
    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
       
    }

    /********************************************
    ## AccountsHomePage 
    *********************************************/

    public function AccountsHomePage(){

        $data['page_title'] = $this->page_title;

        return \View::make('pages.accounts.accounts-home',$data);
    }

    /********************************************
    ## ApplicantsPaymentApprovedPage 
    *********************************************/

    public function ApplicantsPaymentApprovedPage(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

           $all_applicant = \DB::table('applicant_basic')->where(function($query){

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
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                        ->orderBy('applicant_basic.updated_at','desc')->paginate(10);

            if(isset($_GET['program']))
                $program = $_GET['program'];
            else $program = null;

            if(isset($_GET['semester']))
                $semester = $_GET['semester'];
            else $semester = null;

            if(isset($_GET['academic_year']))
                $academic_year = $_GET['academic_year'];
            else $academic_year = null;

            $all_applicant->setPath(url('/accounts/applicant/payment'));

            $pagination = $all_applicant->appends(['program' => $program, 'semester'=> $semester,'academic_year'=> $academic_year])->render();

            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
            $data['program'] =$program;
            $data['semester'] =$semester;
            $data['academic_year'] = $academic_year;

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{

            $all_applicant = \DB::table('applicant_basic')
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->select('applicant_basic.*','univ_program.*','univ_semester.*')
                        ->orderBy('applicant_basic.updated_at','desc')->paginate(10);

            $all_applicant->setPath(url('/accounts/applicant/payment'));
            $pagination = $all_applicant->render();
            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
        }

        



        $data['page_title'] = $this->page_title;
        return \View::make('pages.accounts.accounts-applicant-payment',$data);
    }


    /********************************************
    ## AccountsApplicantMessage 
    *********************************************/

    public function AccountsApplicantMessage($message_issue, $applicant_serial_no){

        $data['page_title'] = $this->page_title;


        if(!empty($applicant_serial_no) && !empty($message_issue)){

            $applicant_info=\DB::table('applicant_basic')
            ->where('applicant_serial_no', $applicant_serial_no)
            ->leftjoin('applicant_personal','applicant_personal.applicant_tran_code','=','applicant_basic.applicant_tran_code')
            ->select('applicant_basic.*', 'applicant_personal.*')
            ->first();
            $data['applicant_info']=$applicant_info;
            $data['message_issue']=$message_issue;

            return \View::make('pages.accounts.ajax-applicant-message',$data);

        }else return \Redirect::to('/accounts/applicant/payment')->with('message',"Problem Finding Applicant !");

        
    }



    /********************************************
    ## AccountsApplicantMessageSend 
    *********************************************/

    public function AccountsApplicantMessageSend($applicant_serial_no){

        $rules=array(
            'applicants_message' => 'Required',
            'message_subject' => 'Required',
            );
        $v=\Validator::make(\Request::all(),$rules);

        if($v->passes()){

            $message_subject=\Request::input('message_subject');
            $applicants_message=\Request::input('applicants_message');
            $email=\Request::input('email');
            $applicant_email_address=\Request::input('applicant_email_address');

            if(!empty($applicant_serial_no) && !empty($email)){

                try{

                    \App\Email::AccountsApplicantMessageEmail($applicant_serial_no, $message_subject, $applicants_message);

                    \App\System::EventLogWrite('email',json_encode($applicant_serial_no));

                    return \Redirect::back()->with('accounts_applicant_message',"{$applicant_email_address}");

                }catch(\Exception $e){
                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                 \App\System::ErrorLogWrite($message);
                }

            }else return \Redirect::back()->with('message',"Select Email or Mobile to Send Message !");

        }else return \Redirect::back()->withErrors($v->messages());

    }


    /********************************************
    ## ApplicantsPaymentApprovedByList 
    *********************************************/

    public function ApplicantsPaymentApprovedByList($applicant_serial_list){



        if(!empty($applicant_serial_list)){
            $applicant_serial_list = explode(',', $applicant_serial_list);
            
          
            if(($key = array_search('0', $applicant_serial_list)) !== false) {
                unset($applicant_serial_list[$key]);
            }

           

            $now = date('Y-m-d H:i:s');
            if(!empty($applicant_serial_list)){
                foreach ($applicant_serial_list as $key => $applicnat) {
                    $applicant_basic_info = \DB::table('applicant_basic')->where('applicant_serial_no',$applicnat)->first();
                    if(!empty($applicant_basic_info)){

                        if($applicant_basic_info->payment_status==2){

                            $payment_data = array(
                                                'payment_by' =>'bank',
                                                'payment_status'=> 1,
                                                'applicant_eligiblity' =>1,
                                                'updated_at' =>$now,
                                                'updated_by' =>\Auth::user()->user_id,
                                            );


                            $applicant_fees_tran_code=\Uuid::generate(4);
                            $applicant_fees_transaction=array(
                                'applicant_fees_tran_code' => $applicant_fees_tran_code->string,
                                'applicant_tran_code' => $applicant_basic_info->applicant_tran_code,
                                'applicant_serial_no' => $applicant_basic_info->applicant_serial_no,
                                'applicant_fees_program' => $applicant_basic_info->program,
                                'applicant_fees_semster' => $applicant_basic_info->semester,
                                'applicant_fees_year' => $applicant_basic_info->academic_year,
                                'applicant_fees_type' => 'application_form',
                                'applicant_fees_payment_types' => 'bank',
                                'applicant_fees_slip_no' => $applicant_basic_info->payment_slip_no,
                                'applicant_fees_amount' => $applicant_basic_info->applicant_fees_amount,

                                'created_by' =>\Auth::user()->user_id,
                                'updated_by' =>\Auth::user()->user_id,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                );
                            


                            try{


                                $success = \DB::transaction(function () use ($payment_data, $applicant_fees_transaction, $applicnat) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                        $update_payment = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicnat)->update($payment_data);

                                        $applicant_fees_transaction_save = \DB::connection($this->dbList[$i])->table('applicant_fees_transaction')->insert($applicant_fees_transaction);

                                        if(!$update_payment || !$applicant_fees_transaction_save){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::TransactionCommit();

                                        \App\System::EventLogWrite('update',json_encode($payment_data));
                                        \App\System::EventLogWrite('insert',json_encode($applicant_fees_transaction));
                                        
                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                                });



                            }catch(\Exception $e){
                               $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                               \App\System::ErrorLogWrite($message);
                           }
                            
                        }
                    }
                }
            }
        }

        \Session::flash('message','Data has been Saved Successfully.');

        return 1;
    }



    /********************************************
    ## ApplicantsPaymentUndone
    *********************************************/

    public function ApplicantsPaymentUndone($payment_by, $applicant_serial_no){

        $now=date('Y-m-d H:i:s');

        if($payment_by=='bank'){

            $payment_data_undone = array(
                'payment_by' =>'',
                'payment_status'=> 2,
                'applicant_eligiblity' =>0,
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->user_id,
                );

        }elseif($payment_by=='cash'){

            $payment_data_undone = array(
                'applicant_fees_amount' => '',
                'payment_by' =>'',
                'payment_slip_no' => '',
                'payment_status'=> 0,
                'applicant_eligiblity' =>0,
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->user_id,
                );

        }
        


        try{


            $success = \DB::transaction(function () use ($payment_data_undone, $applicant_serial_no) {

                for($i=0; $i<count($this->dbList); $i++){
                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $undone_payment = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($payment_data_undone);

                    $applicant_fees_transaction_undone = \DB::connection($this->dbList[$i])->table('applicant_fees_transaction')->where('applicant_serial_no',$applicant_serial_no)->delete();

                    if(!$undone_payment || !$applicant_fees_transaction_undone){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::EventLogWrite('update,applicant_basic',json_encode($payment_data_undone));
                    \App\System::EventLogWrite('delete,applicant_fees_transaction',json_encode($applicant_serial_no));
                    \App\System::TransactionCommit();
                    
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

            \Session::flash('message','Applicant Payment Undone Successfully.');

        }catch(\Exception $e){
         $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
         \App\System::ErrorLogWrite($message);
        }

 }



    /********************************************
    ## ApplicantCashPayemnt 
    *********************************************/

    public function ApplicantCashPayemnt(){

        if(isset($_GET['applicant_serial_no'])){

            $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',trim($_GET['applicant_serial_no']))
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

            $data['applicant_info'] =$applicant_info;
        }

        $data['page_title'] = $this->page_title;
        return \View::make('pages.accounts.applicant-cash',$data);
    }

    /********************************************
    ## ApplicantCashPayemntReceived 
    *********************************************/

    public function ApplicantCashPayemntReceived($applicant_serial_no){
        $now=date('Y-m-d H:i:s');


        $applicant_basic_info = \DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->first();

        if(!empty($applicant_basic_info)){

            $accounts_fees_info = \DB::table('all_accounts_fees')->where('accounts_fee_program',$applicant_basic_info->program)->where('accounts_fee_name_slug','application_form_fee')->first();

            if(!empty($accounts_fees_info)){

                $update_data = array(
                    'payment_by' => 'cash',
                    'applicant_eligiblity' =>1,
                    'applicant_fees_amount' =>$accounts_fees_info->accounts_fee_amount,
                    'payment_status' =>1,
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->user_id,
                );


                $applicant_fees_tran_code=\Uuid::generate(4);

                $applicant_fees_transaction_data=array(
                    'applicant_fees_tran_code' => $applicant_fees_tran_code->string,
                    'applicant_tran_code' => $applicant_basic_info->applicant_tran_code,
                    'applicant_serial_no' => $applicant_basic_info->applicant_serial_no,
                    'applicant_fees_program' => $applicant_basic_info->program,
                    'applicant_fees_semster' => $applicant_basic_info->semester,
                    'applicant_fees_year' => $applicant_basic_info->academic_year,
                    'applicant_fees_type' => 'application_form',
                    'applicant_fees_payment_types' => 'cash',
                    'applicant_fees_slip_no' => NULL,
                    'applicant_fees_amount' => $accounts_fees_info->accounts_fee_amount,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    );
                
            }else{
                \Session::flash('message','Please add this program application form fee fees.');
            }

        }else{
            \Session::flash('message','Something wrong in this applicant info.');
        }


        

        try{

            $success = \DB::transaction(function () use ($update_data, $applicant_fees_transaction_data, $applicant_basic_info, $applicant_serial_no) {

                for($i=0; $i<count($this->dbList); $i++){
                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                    $applicant_fees_transaction_save = \DB::connection($this->dbList[$i])->table('applicant_fees_transaction')->insert($applicant_fees_transaction_data);

                    $update_payment = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($update_data);


                    if((!$applicant_fees_transaction_save) || (!$update_payment)){
                        $error=1;
                    }

                }

                if(!isset($error)){
                    \App\System::TransactionCommit();

                    \App\System::EventLogWrite('update,applicant_basic',json_encode($update_data));
                    \App\System::EventLogWrite('insert,applicant_fees_transaction',json_encode($applicant_fees_transaction_data));
                }else{
                    \App\System::TransactionRollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });

            
            \App\Email::ApplicantAdmitCardEmail($applicant_basic_info->applicant_serial_no);
            \App\System::EventLogWrite('email',json_encode($applicant_basic_info->applicant_serial_no));

            return 1;



        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
	    return 0;
        }

        
    }

    /********************************************
    ## AdmissionPaymentList 
    *********************************************/

        public function AdmissionPaymentList(){
           /*------------------------------------Get Request--------------------------------------------*/
           if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

             $all_applicant = \DB::table('applicant_basic')->whereIn('applicant_basic.applicant_eligiblity',[2,3,4,5])->where(function($query){

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

            $all_applicant->setPath(url('/accounts/admission/payement/list'));

            $pagination = $all_applicant->appends(['program' => $program, 'semester'=> $semester,'academic_year'=> $academic_year])->render();

            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
            $data['program'] =$program;
            $data['semester'] =$semester;
            $data['academic_year'] = $academic_year;

        }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{

            $all_applicant = \DB::table('applicant_basic')->whereIn('applicant_basic.applicant_eligiblity',[2,3,4,5])
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','univ_program.*','univ_semester.*')
            ->orderBy('applicant_basic.created_at','desc')->paginate(10);

            $all_applicant->setPath(url('/accounts/admission/payement/list'));
            $pagination = $all_applicant->render();
            $data['pagination'] = $pagination;
            $data['all_applicant'] = $all_applicant;
        }


        $data['page_title'] = $this->page_title;
        return \View::make('pages.accounts.accounts-admission-payment',$data);
    }



    /********************************************
    ## AccountsAdmissionListExcelDownload 
    *********************************************/

    public function AccountsAdmissionListExcelDownload(){

        $excel_name = 'accounts_admission_payment_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year'])){

                    $applicant_list = \DB::table('applicant_basic')->whereIn('applicant_basic.applicant_eligiblity',[2,3,4,5])
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
                    ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title')
                    ->orderBy('applicant_basic.created_at','desc')
                    ->get();

                    $data['applicant_list']= $applicant_list;
                }else{

                   $applicant_list = \DB::table('applicant_basic')->whereIn('applicant_basic.applicant_eligiblity',[2,3,4,5])
                   ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                   ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                   ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                   ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title')
                   ->orderBy('applicant_basic.created_at','desc')->get();

                   $data['applicant_list']= $applicant_list;
                }

                $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.accounts.excel-admission-list',$data);
                });
            })->export('xlsx');

        }





    /********************************************
    ## AdmissionPaymentApproved 
    *********************************************/

    public function AdmissionPaymentApproved($applicant_serial_no, $payment_type, $slip_no){

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])&&(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')){

            if($slip_no==0){

                \Session::flash('message','Please Confirm Payment Slip !');
            }
            else{

                $now=date('Y-m-d H:i:s');

                $update_data = array(
                    'applicant_eligiblity' =>5,
                    'payment_status'=>5,
                    'updated_at' =>date('Y-m-d H:i:s'),
                    'updated_by' =>\Auth::user()->user_id,
                    ); 

                #---------------------student basic entry---------------------#
                $applicant_info = \App\Applicant::ApplicantBasicInfo($applicant_serial_no);


                $student_last_number = \App\Register::StudentCountByProgram($applicant_info->program,$applicant_info->semester,$applicant_info->academic_year);

                $student_serial_no =  substr($applicant_info->academic_year,-2).$applicant_info->semester.str_pad($applicant_info->program,2,0,STR_PAD_LEFT).str_pad(($student_last_number+1), 4,0,STR_PAD_LEFT);

                $student_tran_code = \Uuid::generate(4);
                $student_basic_data=array(
                    'student_tran_code' =>$student_tran_code->string,
                    'applicant_tran_code' =>$applicant_info->applicant_tran_code,
                    'applicant_serial_no' =>$applicant_info->applicant_serial_no,
                    'student_serial_no' =>$student_serial_no,
                    'first_name'=> $applicant_info->first_name,
                    'middle_name'=> $applicant_info->middle_name,
                    'last_name'=> $applicant_info->last_name,
                    'program' =>$applicant_info->program,
                    'semester' =>$applicant_info->semester,
                    'academic_year' =>$applicant_info->academic_year,
                    'mobile' =>$applicant_info->mobile,
                    'email' =>$applicant_info->email,
                    'gender' =>$applicant_info->gender,
                    'religion' =>$applicant_info->religion,
                    'section' =>'A',
                    'admission_date' =>$now,
                    'batch_no' =>'',
                    'student_image_url' =>'',
                    'student_status' =>0,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );

                #---------------------student basic entry end---------------------#



                #---------------------student payment transactions---------------------#
                $fees=\DB::table('all_accounts_fees')
                    ->where('accounts_fee_program',$applicant_info->program)
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



                 #----------------accounts transaction history------------------#
                $transaction_tran_code=\Uuid::generate(4);
                $accounts_transaction_history_data=array(
                    'transaction_tran_code' => $transaction_tran_code->string,
                    'transaction_student_tran_code' => $student_tran_code->string,
                    'transaction_student_serial_no' => $student_serial_no,
                    'transaction_program' => $applicant_info->program,
                    'transaction_semster' => $applicant_info->semester,
                    'transaction_year' => $applicant_info->academic_year,
                    'transaction_fees_type' => 'admission_fee',
                    'transaction_payment_types' => $accounts_fee_amount,
                    'transaction_slip_no' => $slip_no,
                    'transaction_receive_types' => $payment_type,
                    'transaction_fees_amount' => $accounts_fee_amount,
                    'transaction_history_remarks' => 'Admission Fee',
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );

                #----------------end accounts transaction history--------------#



                $payment_transaction_tran_code=\Uuid::generate(4);
                $student_payment_transactions_data=array(
                    'payment_transaction_tran_code' => $payment_transaction_tran_code->string,
                    'payment_student_tran_code' => $student_tran_code->string,
                    'payment_student_serial_no' => $student_serial_no,
                    'accounts_payment_tran_code' => $transaction_tran_code->string,
                    'payment_program' => $applicant_info->program,
                    'payment_semster' => $applicant_info->semester,
                    'payment_year' => $applicant_info->academic_year,
                    'payment_transaction_fee_type' => 'admission_fee',
                    'payment_receive_type' => $payment_type,
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
                    #---------------end student payment transactions---------------#


                try{

                    $success = \DB::transaction(function () use ($student_basic_data, $student_payment_transactions_data, $accounts_transaction_history_data, $update_data, $applicant_serial_no) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $student_basic_data_save = \DB::connection($this->dbList[$i])->table('student_basic')->insert($student_basic_data);

                            $student_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_data);

                            $accounts_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_transaction_history_data);

                            $payement_approved = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($update_data);

                            if(!$student_basic_data_save || !$student_payment_transactions_save || !$accounts_transaction_history_save || !$payement_approved){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,student_basic',json_encode($student_basic_data));
                            \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_transactions_data));
                            \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($accounts_transaction_history_data));
                            \App\System::EventLogWrite('update,applicant_basic',json_encode($update_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });


                    return 1;
                    \Session::flash('message','Data has been Saved Successfully.');

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return 0;

                } 
            }

        }   

    }
        



    /********************************************
    ## ApplicantsAdmissionPaymentUndone
    *********************************************/

    public function ApplicantsAdmissionPaymentUndone($applicant_serial_no){

        $now=date('Y-m-d H:i:s');

        if(!empty($applicant_serial_no)){

            $applicant_basic_update_data = array(
                'applicant_eligiblity' =>3,
                'payment_status'=>1,
                'updated_at' =>date('Y-m-d H:i:s'),
                'updated_by' =>\Auth::user()->user_id,
                ); 

            $student_basic=\DB::table('student_basic')->where('applicant_serial_no',$applicant_serial_no)->first();

            try{


                $success = \DB::transaction(function () use ($applicant_basic_update_data, $applicant_serial_no, $student_basic) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $applicant_basic_update = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)->update($applicant_basic_update_data);

                        $student_payment_transactions_undone = \DB::connection($this->dbList[$i])->table('student_payment_transactions')->where('payment_student_serial_no',$student_basic->student_serial_no)->where('payment_transaction_fee_type','admission_fee')->delete();

                        $accounts_transaction_history_undone = \DB::connection($this->dbList[$i])->table('accounts_transaction_history')->where('transaction_student_serial_no',$student_basic->student_serial_no)->where('transaction_fees_type','admission_fee')->delete();

                        $student_basic_undone = \DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$student_basic->student_serial_no)->delete();



                        if(!$applicant_basic_update || !$student_payment_transactions_undone || !$accounts_transaction_history_undone || !$student_basic_undone){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();

                        \App\System::EventLogWrite('update,applicant_basic',json_encode($applicant_basic_update_data));
                        \App\System::EventLogWrite('delete,student_payment_transactions',json_encode($applicant_serial_no));
                        \App\System::EventLogWrite('delete,student_basic',json_encode($applicant_serial_no));
                        \App\System::EventLogWrite('delete,accounts_transaction_history',json_encode($applicant_serial_no));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                \Session::flash('message','Student Admission Payment Undone Successfully.');

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return 0;

            }

     }


 }



    /********************************************
    ## StudentPaymentTransaction 
    *********************************************/
    public function StudentPaymentTransaction(){
        $data['page_title'] = $this->page_title;


        if(isset($_GET['student_serial_no'])&&(!empty($_GET['student_serial_no']))){
            $student_serial_no=$_GET['student_serial_no'];


            $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
            ->leftJoin('univ_program','program_id','=','student_basic.program')
            ->select('student_basic.*','univ_program.*')
            ->first();

            $univ_semester_info=\DB::table('univ_semester')
                        ->orderBy('univ_semester.created_at','desc')
                        ->get();

            $univ_academic_year_info=\DB::table('univ_academic_calender')
                        ->select('univ_academic_calender.*')
                        ->groupBy('academic_calender_year')
                        ->orderBy('univ_academic_calender.created_at','desc')
                        ->get();

            $data['univ_academic_year_info']=$univ_academic_year_info;
            $data['univ_semester_info']=$univ_semester_info;


            $total_payment_receivable=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('payment_student_serial_no',$student_serial_no)
                ->sum('payment_receivable');
            $data['total_payment_receivable']=$total_payment_receivable;

            $total_payment_paid=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('payment_student_serial_no',$student_serial_no)
                ->sum('payment_paid');
            $data['total_payment_paid']=$total_payment_paid;


            $total_other_paid=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('payment_student_serial_no',$student_serial_no)
                ->sum('payment_others');
            $data['total_other_paid']=$total_other_paid;

            $data['total_payment_due']= $total_payment_receivable-$total_payment_paid;
          
            if(!empty($student_info)){

                $data['student_info']=$student_info;

                if(isset($_GET['semester']) && isset($_GET['year'])){

                        $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                        ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                        ->where('payment_student_serial_no',$student_serial_no)
                        ->where(function($query){

                           if(isset($_GET['semester']) && $_GET['semester'] !=0){
                                $query->where(function ($q){
                                    $q->where('payment_semster', $_GET['semester']);
                                  });
                            }
                            if(isset($_GET['year']) && $_GET['year']!=0){
                                $query->where(function ($q){
                                    $q->where('payment_year', $_GET['year']);
                                  });
                            }
                        })
                        ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
                        ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                        ->orderBy('student_payment_transactions.created_at','asc')
                        ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','univ_semester.*','accounts_transaction_history.*')
                        ->get();
                }else{
                    $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                        ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                        ->where('payment_student_serial_no',$student_serial_no)
                        ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
                        ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                        ->orderBy('student_payment_transactions.created_at','asc')
                        ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','univ_semester.*','accounts_transaction_history.*')
                        ->get();
                }

                $data['student_payment_transaction_detail']=$student_payment_transaction_detail;

                $fee_lists = \DB::table('fee_category')->whereNotIn('fee_category_name_slug',array('application_form_fee','admission_fee'))->get();
                if(!empty($fee_lists)){
                $data['fee_list']=$fee_lists;
                }



                $univ_academic_calender=\DB::table('univ_academic_calender')
                                    ->where('academic_calender_status',1)
                                    ->orderBy('created_at','desc')
                                    ->first();

                $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->where('student_status','>',0)->first();

                    if(!empty($univ_academic_calender) && !empty($student_basic)){

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


            }else{
                return \Redirect::to('/accounts/student-payment-transaction')->with('message',"Student Not Found !");
            }

        }
        return \View::make('pages.accounts.accounts-student-payment-transaction',$data);
    }



    /********************************************
    ## AccountsStudentPaymentSubmit 
    *********************************************/
    public function AccountsStudentPaymentSubmit(){

        $rules=array(
            'accounts_transaction_date' => 'Required|date_format:"Y-m-d"',
            'student_serial_no' => 'Required',
            'receive_type' => 'Required',
            'amount' => 'Required | numeric',
            // 'slip_no' => 'Required',
            'academic_year' => 'Required',
            'semester' => 'Required',
            'fee_type' => 'Required',
            );
        $v=\Validator::make(\Request::all(), $rules);


        if($v->passes()){

           $now=date('Y-m-d H:i:s');
           $accounts_transaction_date=\Request::input('accounts_transaction_date');
           $student_serial_no=\Request::input('student_serial_no');
           $receive_type=\Request::input('receive_type');
           $amount=\Request::input('amount');
           $slip_no=\Request::input('slip_no');
           $waiver_type=\Request::input('waiver_type');
           $academic_year=\Request::input('academic_year');
           $semester=\Request::input('semester');
           $fee_type=\Request::input('fee_type');
           $payment_details=\Request::input('payment_details');

            if(!empty($slip_no) || !empty($waiver_type)){

               $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->first();


                $fess_type_info = \DB::table('all_accounts_fees')
                    ->where('accounts_fee_name_slug',$fee_type)
                    ->where('accounts_fee_program',$student_basic->program)
                    ->first();

                if($fee_type !='other_fees'){
                    if($fess_type_info->accounts_fee_payment_type =='Receivable'){
                        $payment_paid=$amount;
                        $remarks=$fee_type;
                        $payment_others=0;
                        $payment_amounts=$amount;
                    }else{
                        $payment_paid=0;
                        $remarks=$fee_type;
                        $payment_others=$amount;
                        $payment_amounts=$amount;
                    }
                }else{
                    $payment_paid=0;
                    $remarks='Other Fee';
                    $payment_others=$amount;
                    $payment_amounts=$amount;
                }





                #---------------------accounts_transaction_history----------------#
                $transaction_tran_code=\Uuid::generate(4);
                $accounts_transaction_history_data=array(
                    'transaction_tran_code' => $transaction_tran_code->string,
                    'transaction_student_tran_code' => $student_basic->student_tran_code,
                    'accounts_transaction_date' => $accounts_transaction_date,
                    'transaction_student_serial_no' => $student_serial_no,
                    'transaction_program' => $student_basic->program,
                    'transaction_semster' => $semester,
                    'transaction_year' => $academic_year,
                    'transaction_fees_type' => $fee_type,
                    'transaction_payment_types' => $receive_type,
                    'transaction_slip_no' => $slip_no,
                    'waiver_type' => $waiver_type,
                    'transaction_receive_types' => $receive_type,
                    'transaction_fees_amount' => $amount,
                    'transaction_history_remarks' => $remarks,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );
                   #---------------------end accounts_transaction_history----------------#



                #---------------------student_payment_transactions----------------#
                $payment_transaction_tran_code=\Uuid::generate(4);
                $student_payment_transactions_data=array(
                    'payment_transaction_tran_code' => $payment_transaction_tran_code->string,
                    'payment_student_tran_code' => $student_basic->student_tran_code,
                    'payment_student_serial_no' => $student_serial_no,
                    'accounts_payment_tran_code' => $transaction_tran_code->string,
                    'payment_program' => $student_basic->program,
                    'payment_semster' => $semester,
                    'payment_year' => $academic_year,
                    'payment_transaction_fee_type' => $fee_type,
                    'payment_receive_type' => $receive_type,
                    'payment_receivable' => 0,
                    'payment_paid' => $payment_paid,
                    'payment_remarks' => $remarks,
                    'payment_others' => $payment_others,
                    'payment_amounts' => $payment_amounts,
                    'payment_details' => $payment_details,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->user_id,
                    'updated_by' =>\Auth::user()->user_id,
                    );
                   #---------------------end student_payment_transactions----------------#

                try{

                        $success = \DB::transaction(function () use ($accounts_transaction_history_data, $student_payment_transactions_data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                               $accounts_transaction_history_save=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->insert($accounts_transaction_history_data);
                               $student_payment_transactions_save=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->insert($student_payment_transactions_data);

                                if(!$accounts_transaction_history_save || !$student_payment_transactions_save){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();

                                \App\System::EventLogWrite('insert,accounts_transaction_history',json_encode($accounts_transaction_history_data));
                                \App\System::EventLogWrite('insert,student_payment_transactions',json_encode($student_payment_transactions_data));

                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                   return \Redirect::back()->with('message',"Payment Transaction Successfull !");

                }catch(\Exception $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return  \Redirect::back()->with('message',"Something went wrong !");
                }
            }

        }else return \Redirect::back()->withErrors($v->messages())->withInput();


    }


    /********************************************
    ## AccountsFeeNameList 
    *********************************************/
    public function AccountsFeeNameList($fee_name){
        $data['page_title'] = $this->page_title;

        if(!empty($fee_name)){


            $waiver_info=\DB::table('waivers')->get();

            $data['fee_name']=$fee_name;
            $data['waiver_info']= $waiver_info;

        }

        return \View::make('pages.accounts.ajax-payment-fee-type',$data);
    }

  

    
    /********************************************
    ## AccountsStudentPaymentEdit 
    *********************************************/

    public function AccountsStudentPaymentEdit($payment_tran_code){

        $data['page_title'] = $this->page_title;
      
        $student_payment_transactions=\DB::table('student_payment_transactions')->where('payment_transaction_tran_code', $payment_tran_code)
        ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
        ->first();

        $data['student_payment']=$student_payment_transactions;

        return \View::make('pages.accounts.accounts-student-payment-edit',$data);
       
    }



    /********************************************
    ## AccountsStudentPaymentUpdate 
    *********************************************/

    public function AccountsStudentPaymentUpdate($payment_tran_code){

        $rules=array(
            'accounts_transaction_date' => 'Required|date_format:"Y-m-d"',
            'academic_year' => 'Required',
            'academic_semester' => 'Required',
            'fees_type' => 'Required',
            'receive_type' => 'Required',
            'slip_no' => 'Required',
            'payment_receivable' => 'Required',
            'payment_paid' => 'Required',
            'payment_other' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $accounts_transaction_date=\Request::input('accounts_transaction_date');
            $academic_year=\Request::input('academic_year');
            $academic_semester=\Request::input('academic_semester');
            $fees_type=\Request::input('fees_type');
            $receive_type=\Request::input('receive_type');
            $slip_no=\Request::input('slip_no');
            $payment_receivable=\Request::input('payment_receivable');
            $payment_paid=\Request::input('payment_paid');
            $payment_other=\Request::input('payment_other');
            $payment_details=\Request::input('payment_details');
            $payment_remarks=\Request::input('payment_remarks');

            if($fees_type !='Waiver'){

                if(($payment_paid != 0) && ($payment_other != 0)){
                    return \Redirect::back()->with('errormessage','You can entry only one type  amount payment paid or payment others.');
                }

                if($payment_paid == 0){
                    $payment_amounts=$payment_other;
                }elseif($payment_other == 0){
                    $payment_amounts=$payment_paid;
                }else{
                   $payment_amounts=0; 
                }

                #---------------------student_payment_transactions_update----------------#
                $student_payment_transactions_Update=array(
                    'payment_semster' => $academic_semester,
                    'payment_year' => $academic_year,
                    'payment_transaction_fee_type' => $fees_type,
                    'payment_receive_type' => $receive_type,
                    'payment_receivable' => $payment_receivable,
                    'payment_paid' => $payment_paid,
                    'payment_remarks' => $payment_remarks.' Updated',
                    'payment_others' => $payment_other,
                    'payment_amounts' => $payment_amounts,
                    'payment_details' => $payment_details,
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->user_id,
                    );
                   #---------------------end student_payment_transactions_update----------------#


                 #---------------------accounts_transaction_history_update----------------#
                $accounts_transaction_history_update=[
                    'accounts_transaction_date' => $accounts_transaction_date,
                    'transaction_semster' => $academic_semester,
                    'transaction_year' => $academic_year,
                    'transaction_fees_type' => $fees_type,
                    'transaction_payment_types' => $receive_type,
                    'transaction_slip_no' => $slip_no,
                    'transaction_receive_types' => $receive_type,
                    'transaction_fees_amount' => $payment_amounts,
                    'transaction_history_remarks' => $payment_remarks.' Updated',
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->user_id,
                    ];
                   #---------------------end accounts_transaction_history_update----------------#


                $student_payment=\DB::table('student_payment_transactions')
                                ->where('student_payment_transactions.payment_transaction_tran_code',$payment_tran_code)
                                ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
                                ->where('student_payment_transactions.created_by','=',\Auth::user()->user_id)
                                ->first();
                if(!empty($student_payment)){

                    try{

                        $success = \DB::transaction(function () use ($student_payment_transactions_Update, $accounts_transaction_history_update, $student_payment, $payment_tran_code) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                                $student_payment_transactions_update=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->where('payment_transaction_tran_code', $payment_tran_code)->update($student_payment_transactions_Update);


                                $accounts_transaction_history_update_info=\DB::connection($this->dbList[$i])
                                                ->table('accounts_transaction_history')
                                                ->where('transaction_tran_code',$student_payment->transaction_tran_code)
                                                ->update($accounts_transaction_history_update);


                                if(!$student_payment_transactions_update || !$accounts_transaction_history_update){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,payment_transaction_tran_code',json_encode($student_payment_transactions_Update));
                                \App\System::EventLogWrite('delete,accounts_transaction_history',json_encode($accounts_transaction_history_update));

                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                        return \Redirect::back()->with('message','Student Transaction Updated Successfully !');

                    }catch(\Exception  $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errormessage','Something wrong in catch !!!');
                    }

                }else return \Redirect::back()->with('errormessage','You have no permission to update this.');
            }else return \Redirect::back()->with('errormessage','If this entry is wrong, please delete this and entry again.');


        }else return \Redirect::back()->withErrors($v->messages());

           
    }


    
    /********************************************
    ## AccountsStudentPaymentDelete 
    *********************************************/

    public function AccountsStudentPaymentDelete($payment_tran_code){

        $student_payment=\DB::table('student_payment_transactions')
            ->where('payment_transaction_tran_code', $payment_tran_code)
            ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
            ->where('student_payment_transactions.created_by','=',\Auth::user()->user_id)
            ->first();
        if(!empty($student_payment)){

            try{

                $success = \DB::transaction(function () use ($payment_tran_code, $student_payment) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $student_payment_transactions_delete=\DB::connection($this->dbList[$i])->table('student_payment_transactions')->where('payment_transaction_tran_code', $payment_tran_code)->delete();
                        $accounts_transaction_history_delete=\DB::connection($this->dbList[$i])->table('accounts_transaction_history')->where('transaction_tran_code', $student_payment->transaction_tran_code)->delete();

                        if(!$student_payment_transactions_delete || !$accounts_transaction_history_delete){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,payment_transaction_tran_code',json_encode($payment_tran_code));
                        \App\System::EventLogWrite('delete,accounts_transaction_history',json_encode($student_payment->transaction_tran_code));

                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::back()->with('message','Student Transaction Deleted Successfully !');

            }catch(\Exception  $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage','Not Deleted !');
            }
        }else return \Redirect::back()->with('errormessage','You have no permission to delete this.');

    }




    /********************************************
    ## StudentMidAndFinalAdmit
    *********************************************/
    public function StudentMidAndFinalAdmit($student_serial_no, $exam_type){

        $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
            ->leftJoin('univ_program','program_id','=','student_basic.program')
            ->leftJoin('univ_semester','semester_code','=','student_basic.semester')
            ->first();

        $univ_academic_calender=\DB::table('univ_academic_calender')
                            ->where('academic_calender_status',1)
                            ->orderBy('created_at','desc')
                            ->first();

        $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->where('student_status','>',0)->first();

            if(!empty($univ_academic_calender) && !empty($student_basic)){

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


        $data['student_info'] = $student_info;
        $data['exam_type'] = $exam_type;
        $data['page_title'] = $this->page_title;

        return \View::make('pages.accounts.accounts-student-mid-final-admit-card',$data);

        // $pdf_name='Exam_clearence'.$student_serial_no.'_'.$exam_type.'_'.date('i_s');
        // $pdf = \PDF::loadView('pages.accounts.accounts-student-mid-final-admit-card',$data);
        // return  $pdf->stream($pdf_name.'.pdf');
    }


    /********************************************
    ## StudentPaymentSummery 
    *********************************************/

    public function StudentPaymentSummery(){

        $data['page_title'] = $this->page_title;
        $all_student_payment_summery_info=array();
        $batch_list = \DB::table('student_basic')->select('batch_no', \DB::raw('count(*) as total'))->groupBy('batch_no')->get();
        $data['batch_list']= $batch_list;

        if(isset($_GET['date_from']) && isset($_GET['date_to']) && isset($_GET['program']) && isset($_GET['batch_no'])){

            $search_from = $_GET['date_from'].' 00:00:00';
            $search_to = $_GET['date_to'].' 23:59:59';

            $student_list = \DB::table('student_basic')
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

            if(!empty($student_list)){
                foreach ($student_list as $key => $value) {

                    $student_serial_no=$value->student_serial_no;

                    $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
                        ->leftJoin('univ_program','program_id','=','student_basic.program')
                        ->select('student_basic.*','univ_program.*')
                        ->first();
                    $student_name=$student_info->first_name.' '.$student_info->middle_name.''.$student_info->last_name;
                    $student_program=$student_info->program_code;
                    $student_mobile=$student_info->mobile;
                    $student_email=$student_info->email;
                    $data['student_info']=$student_info;

                    $total_payment_receivable=\DB::table('student_payment_transactions')
                        ->where('payment_student_serial_no',$student_serial_no)
                        ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                        ->sum('payment_receivable');

                    $total_payment_paid=\DB::table('student_payment_transactions')
                        ->where('payment_student_serial_no',$student_serial_no)
                        ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                        ->sum('payment_paid');

                    $total_others_paid=\DB::table('student_payment_transactions')
                        ->where('payment_student_serial_no',$student_serial_no)
                        ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                        ->sum('payment_others');

                    $total_payment_due= $total_payment_receivable-$total_payment_paid;

                    $student_payment_summery=[$student_serial_no,$student_name, $student_program, $student_mobile, $student_email,$total_payment_receivable, $total_payment_paid, $total_others_paid, $total_payment_due];
                    $student_payment_summery_info=serialize($student_payment_summery);
                    $all_student_payment_summery_info[]=$student_payment_summery_info;

                }
            }
            $data['all_student_payment_summery_info'] = $all_student_payment_summery_info;
        }


        return \View::make('pages.accounts.accounts-all-student-payment-summery',$data);
    }

    /********************************************
    ## StudentPaymentSummeryExcel 
    *********************************************/


    public function StudentPaymentSummeryExcel($program, $batch_no, $from, $to){

        $data['page_title'] = $this->page_title;
        $all_student_payment_summery_info=array();
        if(!empty($program) && !empty($batch_no) && !empty($from) && !empty($to)){

            $search_from = $from.' 00:00:00';
            $search_to = $to.' 23:59:59';

            $student_list_info = \DB::table('student_basic')
                        ->where('student_basic.program', $program)
                        ->where('student_basic.batch_no', $batch_no)
                        ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                        ->select('student_basic.*','univ_program.*')
                        ->get();

            if(!empty($student_list_info)){

                $excel_name = 'student_list_by_batch_'.date('Y_m_d_i_s');
                \Excel::create($excel_name, function($excel) use($program, $batch_no, $search_from, $search_to){
                    $excel->sheet('First sheet', function($sheet)  use($program, $batch_no, $search_from, $search_to){

                        $student_list = \DB::table('student_basic')
                            ->where('student_basic.program', $program)
                            ->where('student_basic.batch_no', $batch_no)
                            ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
                            ->select('student_basic.*','univ_program.*')
                            ->get();

                        if(!empty($student_list)){
                            foreach ($student_list as $key => $value) {

                                $student_serial_no=$value->student_serial_no;

                                $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
                                    ->leftJoin('univ_program','program_id','=','student_basic.program')
                                    ->select('student_basic.*','univ_program.*')
                                    ->first();

                                $student_name=$student_info->first_name.' '.$student_info->middle_name.''.$student_info->last_name;
                                $student_program=$student_info->program_code;
                                $student_mobile=$student_info->mobile;
                                $student_email=$student_info->email;
                                $data['student_info']=$student_info;

                                $total_payment_receivable=\DB::table('student_payment_transactions')
                                    ->where('payment_student_serial_no',$student_serial_no)
                                    ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                                    ->sum('payment_receivable');

                                $total_payment_paid=\DB::table('student_payment_transactions')
                                    ->where('payment_student_serial_no',$student_serial_no)
                                    ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                                    ->sum('payment_paid');

                                $total_others_paid=\DB::table('student_payment_transactions')
                                    ->where('payment_student_serial_no',$student_serial_no)
                                    ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                                    ->sum('payment_others');

                                $total_payment_due= $total_payment_receivable-$total_payment_paid;

                                $student_payment_summery=[$student_serial_no,$student_name, $student_program, $student_mobile, $student_email,$total_payment_receivable, $total_payment_paid, $total_others_paid, $total_payment_due];
                                $student_payment_summery_info=serialize($student_payment_summery);
                                $all_student_payment_summery_info[]=$student_payment_summery_info;

                            }
                        }
                        $data['all_student_payment_summery_info'] = $all_student_payment_summery_info;

                        $sheet->loadView('excelsheet.pages.accounts.excel-student-payment-summery-list',$data);
                    });
                })->export('xlsx');
            }else{
                return \Redirect::back()->with('errormessage','Invalid Student batch !!!');
            } 

        }else{
            return \Redirect::back()->with('errormessage','Invalid value !!!');
        }
    }


    /********************************************
    ## AccountsStudentBatchByProgram
    *********************************************/
    public function AccountsStudentBatchByProgram($program){
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
    ## AccountsFeeCategory 
    *********************************************/

    public function AccountsFeeCategory(){

        $data['page_title'] = $this->page_title;
        $account_fee_list=\DB::table('fee_category')
                        ->select('fee_category.*')
                        ->orderBy('created_at','desc')
                        ->paginate(10);
        $account_fee_list->setPath(url('/accounts/fee-category'));
        $fee_pagination = $account_fee_list->render();
        $data['fee_pagination'] = $fee_pagination;
        $data['account_fee_list'] = $account_fee_list;
        return \View::make('pages.accounts.accounts-fee-category-name',$data);
    }



    /********************************************
    ## AccountsFeeCategorySubmit
    *********************************************/

    public function AccountsFeeCategorySubmit(){

        $data['page_title'] = $this->page_title;

        $rule = ['fee_category_name' => 'Required'];


        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;
            $fee_category_name_slug = explode(' ', strtolower(\Request::input('fee_category_name')));
            $fee_category_name_slug = implode('_', $fee_category_name_slug);

            $get_fee_category_info=\DB::table('fee_category')
                            ->where('fee_category_name_slug',$fee_category_name_slug)
                            ->first();
            if(empty($get_fee_category_info)){

                $fee_type_name_data = [
                                    'fee_category_tran_code' => $uuid->string,
                                    'fee_category_name' => \Request::input('fee_category_name'),
                                    'fee_category_name_slug' =>$fee_category_name_slug,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>$user,
                                    'updated_by' =>$user,
                                ];

                try{

                    $success = \DB::transaction(function () use ($fee_type_name_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $fee_category_save=\DB::connection($this->dbList[$i])->table('fee_category')->insert($fee_type_name_data);

                            if(!$fee_category_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,fee_category',json_encode($fee_type_name_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/accounts/fee-category')->with('message','Fee category has been added.');

                }catch(\Exception  $e){
                    return \Redirect::to('/accounts/fee-category')->with('errormessage','Fee category Already exists.');
                }

            }else return \Redirect::to('/accounts/fee-category')->with('errormessage','Fee category Already exists.');
        }else return \Redirect::to('/accounts/fee-category')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    # AccountsFeeCategoryDelete
    *********************************************/
    public function AccountsFeeCategoryDelete($fee_category_name_slug){

        try{

            $fee_category_info=\DB::table('all_accounts_fees')
                            ->where('accounts_fee_name_slug',$fee_category_name_slug)
                            ->first();
            if(empty($fee_category_info)){

                $select_fee_category_info=['trimester_fee','admission_fee','tution_fee','application_form_fee'];

                if(!in_array($fee_category_name_slug, $select_fee_category_info)){

                    $success = \DB::transaction(function () use ($fee_category_name_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $data=\DB::connection($this->dbList[$i])->table('fee_category')->where('fee_category_name_slug',$fee_category_name_slug)->delete();

                            if(!$data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('delete,fee_category',json_encode($fee_category_name_slug));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/accounts/fee-category')->with('message'," Deleted Successfully!");
                }else return \Redirect::to('/accounts/fee-category')->with('errormessage',"You can not delete this because it is mendatory fees !");
            }else return \Redirect::to('/accounts/fee-category')->with('errormessage',"You can not delete this because it has fees !!!!");

        }catch(\Exception  $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::to('/accounts/fee-category')->with('errormessage','Something wrong in catch!');
        }
    }



    /********************************************
    # EditAccountFeeCategoryName
    *********************************************/
    public function EditAccountFeeCategoryName($fee_category_name_slug){

        $edit_fee_category=\DB::table('fee_category')->where('fee_category_name_slug',$fee_category_name_slug)->first();

        if(!empty($edit_fee_category)){
            $data['page_title'] = $this->page_title;
        
            $data['edit_fee_category']=$edit_fee_category;
            return \View::make('pages.accounts.account-fee-category-edit',$data);
        }else return \Redirect::to('/accounts/fee-category')->with('errormessage',"Invalid Fee Category!");
        
    }


    /********************************************
    # UpdateAccountFeeCategoryName
    *********************************************/
    public function UpdateAccountFeeCategoryName($fee_category_name_slug){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = ['fee_category_name' => 'Required'];
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $new_fee_category_name_slug = explode(' ', strtolower(\Request::input('fee_category_name')));
            $new_fee_category_name_slug = implode('_', $new_fee_category_name_slug);

            $get_fee_category_info=\DB::table('fee_category')
                            ->where('fee_category_name_slug',$fee_category_name_slug)
                            ->first();

            $fee_category_info=\DB::table('all_accounts_fees')
                            ->where('accounts_fee_name_slug',$fee_category_name_slug)
                            ->first();

            if(!empty($get_fee_category_info)){
            if(empty($fee_category_info)){

                $fee_data = [
                    'fee_category_name_slug' => $new_fee_category_name_slug,
                    'fee_category_name'=>\Request::input('fee_category_name'),
                    'updated_at' =>$now,
                    'updated_by' =>$user
                ];

                try{

                    $select_fee_category_info=['trimester_fee','admission_fee','tution_fee','application_form_fee'];

                    if(!in_array($fee_category_name_slug, $select_fee_category_info)){


                        $success = \DB::transaction(function () use ($fee_data, $fee_category_name_slug) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_data = \DB::connection($this->dbList[$i])->table('fee_category')->where('fee_category_name_slug',$fee_category_name_slug)->update($fee_data);

                                if(!$update_data){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,fee_category',json_encode($fee_data));
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                        return \Redirect::to('/accounts/fee-category')->with('message',"Fee category name  Updated Successfully!");
                    }else return \Redirect::to('/accounts/fee-category')->with('errormessage',"You can not delete this because it is mendatory fees !");

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/accounts/fee-category')->with('errormessage','Fee category Already exists.');
                }

            }else return \Redirect::to('/accounts/fee-category')->with('errormessage','Fee category Already exists.');
            }else return \Redirect::to('/accounts/fee-category')->with('errormessage','Fee category Already exists.');
        }else return \Redirect::to('/accounts/fee-category')->withErrors($v->messages());
    }

    /********************************************
    ## AccountsFeePayment 
    *********************************************/

    public function AccountsFeePayment(){


        if(!empty($_GET['program'])){

           $account_payment_list=\DB::table('all_accounts_fees')
                        ->leftJoin('univ_program','all_accounts_fees.accounts_fee_program','=','univ_program.program_id')
                        ->where(function($query){

                           if(isset($_GET['program'])&& ($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('accounts_fee_program', $_GET['program']);
                                  });
                            }
                        })
                        ->orderBy('all_accounts_fees.created_at','desc')
                        ->paginate(10);
            $account_payment_list->setPath(url('/accounts/fee-payment'));

            $payment_pagination = $account_payment_list->appends(['program' => $_GET['program']])->render();

            $data['payment_pagination'] = $payment_pagination;
            $data['account_payment_list'] = $account_payment_list;

        }else{
            $account_payment_list=\DB::table('all_accounts_fees')
                        ->leftJoin('univ_program','all_accounts_fees.accounts_fee_program','=','univ_program.program_id')
                        ->select('all_accounts_fees.*','univ_program.*')
                        ->orderBy('all_accounts_fees.created_at','desc')
                        ->paginate(10);
            $account_payment_list->setPath(url('/accounts/fee-payment'));
            $payment_pagination = $account_payment_list->render();
            $data['payment_pagination'] = $payment_pagination;
            $data['account_payment_list'] = $account_payment_list;
        }

        $data['page_title'] = $this->page_title;
        $program_list =\DB::table('univ_program')->get();
        $data['program_list'] = $program_list;

        return \View::make('pages.accounts.accounts-fee-payment',$data);
    }


    /********************************************
    ## AccountsFeePaymentSubmit
    *********************************************/

    public function AccountsFeePaymentSubmit(){

        $data['page_title'] = $this->page_title;

        $rules = [
                 'accounts_fee_program' => 'Required',
                 'accounts_fee_amount' => 'Required|numeric',
                 'accounts_fee_payment_type'=>'Required'
                 ];

        $v = \Validator::make(\Request::all(),$rules);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;
            $accounts_fee_program=\Request::input('accounts_fee_program');

            $accounts_fee_name_slug= \Request::input('accounts_fee_name_slug');
            $accounts_fee_name = \DB::table('fee_category')
                                ->where('fee_category_name_slug',$accounts_fee_name_slug)
                                ->first();

            $fee_category_info=['trimester_fee','admission_fee','tution_fee','application_form_fee'];

            if(in_array($accounts_fee_name_slug, $fee_category_info) && $accounts_fee_program=='all'){

                return \Redirect::to('/accounts/fee-payment')->with('errormessage','So you can not use all program for this type fees.');
            }else
            
                $all_accounts_fees_info=\DB::table('all_accounts_fees')->where('accounts_fee_name_slug',$accounts_fee_name_slug)->where('accounts_fee_program',$accounts_fee_program)->first();

                if(empty($all_accounts_fees_info)){
                    $accounts_fee_slug = $accounts_fee_name_slug .'_'.\Request::input('accounts_fee_program');

                    $fee_type_name_data = [
                                        'accounts_fee_tran_code' => $uuid->string,
                                        'accounts_fee_name' => $accounts_fee_name->fee_category_name,
                                        'accounts_fee_name_slug'=>$accounts_fee_name_slug,
                                        'accounts_fee_program' => \Request::input('accounts_fee_program'),
                                        'accounts_fee_slug' => strtolower($accounts_fee_slug),
                                        'accounts_fee_amount' => \Request::input('accounts_fee_amount'),
                                        'accounts_fee_payment_type' => \Request::input('accounts_fee_payment_type'),
                                        'created_at' =>$now,
                                        'updated_at' =>$now,
                                        'created_by' =>$user,
                                        'updated_by' =>$user,
         
                                    ];


                    try{

                        $success = \DB::transaction(function () use ($fee_type_name_data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_data = \DB::connection($this->dbList[$i])->table('all_accounts_fees')->insert($fee_type_name_data);

                                if(!$update_data){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,all_accounts_fees',json_encode($fee_type_name_data));
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                        return \Redirect::to('/accounts/fee-payment')->with('message','Fee Payment has been added.');

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/accounts/fee-payment')->with('errormessage','Fee Payment Already exists.');
                    }
                }else return \Redirect::to('/accounts/fee-payment')->with('errormessage','Fee Payment Already exists.');
            

        }else return \Redirect::to('/accounts/fee-payment')->withInput(\Request::all())->withErrors($v->messages());

    }





    /********************************************
    # EditAccountFeePayment
    *********************************************/
    public function EditAccountFeePayment($accounts_fee_tran_code){
        $edit_fee_payment=\DB::table('all_accounts_fees')->where('accounts_fee_tran_code',$accounts_fee_tran_code)->first();
         if(!empty($edit_fee_payment)){
            $data['page_title'] = $this->page_title;
            $data['edit_fee_payment'] = $edit_fee_payment;
          
            return \View::make('pages.accounts.accounts-fee-payment-edit',$data);
          }else return \Redirect::to('/accounts/fee-payment')->with('errormessage',"Invalid Fee types!");
        
    }


    /********************************************
    # UpdateAccountFeePayment
    *********************************************/
    public function UpdateAccountFeePayment($accounts_fee_tran_code){
        $now=date('Y-m-d H:i:s');
         $user =\Auth::user()->user_id;

        $rules = [
                 'accounts_fee_program' => 'Required',
                 'accounts_fee_amount' => 'Required|numeric'
                 ];

        $v = \Validator::make(\Request::all(),$rules);

        if($v->passes()){



            $new_accounts_fee_name_slug= \Request::input('accounts_fee_name_slug');
            $new_accounts_fee_name = \DB::table('fee_category')->where('fee_category_name_slug',$new_accounts_fee_name_slug)->first();
            $new_accounts_fee_slug = $new_accounts_fee_name_slug .'_'.\Request::input('accounts_fee_program');
            $accounts_fee_program=\Request::input('accounts_fee_program');
            
            $fee_category_info=['trimester_fee','admission_fee','tution_fee','application_form_fee'];

            if(in_array($new_accounts_fee_name_slug, $fee_category_info) && $accounts_fee_program=='all'){

                return \Redirect::to('/accounts/fee-payment')->with('errormessage','So you can not use all program for this type fees.');
            }
                $all_accounts_fees_info=\DB::table('all_accounts_fees')->where('accounts_fee_tran_code',$accounts_fee_tran_code)->where('accounts_fee_program',$accounts_fee_program)->first();

                if(!empty($all_accounts_fees_info)){


                        $data = [
                            'accounts_fee_name' => $new_accounts_fee_name->fee_category_name,
                            'accounts_fee_name_slug'=>$new_accounts_fee_name_slug,
                            'accounts_fee_program'=>\Request::input('accounts_fee_program'),
                            'accounts_fee_slug' => strtolower($new_accounts_fee_slug),
                            'accounts_fee_amount'=>\Request::input('accounts_fee_amount'),
                            'accounts_fee_payment_type'=>\Request::input('accounts_fee_payment_type'),
                            'updated_at' =>$now,
                            'updated_by' =>$user
                        ];

                    try{

                        $success = \DB::transaction(function () use ($data, $accounts_fee_tran_code) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_data = \DB::connection($this->dbList[$i])->table('all_accounts_fees')->where('accounts_fee_tran_code',$accounts_fee_tran_code)->update($data);

                                if(!$update_data){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,all_accounts_fees',json_encode($data));
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                         return \Redirect::to('/accounts/fee-payment')->with('errormessage','Fee Payment Already exists.');
                    }

                    return \Redirect::to('/accounts/fee-payment')->with('message',"Fee Payment Updated Successfully!");


                }else return \Redirect::to('/accounts/fee-payment')->with('errormessage','Fee Payment Already exists.');

        }else return \Redirect::to('/accounts/fee-payment')->withErrors($v->messages());
    }

    /********************************************
    # AccountsFeePaymentDelete
    *********************************************/
    public function AccountsFeePaymentDelete($accounts_fee_tran_code){

        try{
            $all_accounts_fees_info = \DB::table('all_accounts_fees')->where('accounts_fee_tran_code',$accounts_fee_tran_code)->first();

            if(!empty($all_accounts_fees_info)){

                $student_payment_transactions_info=\DB::table('student_payment_transactions')->where('payment_transaction_fee_type',$all_accounts_fees_info->accounts_fee_name_slug)->where('payment_program',$all_accounts_fees_info->accounts_fee_program)->first();

                if(empty($student_payment_transactions_info)){


                    $success = \DB::transaction(function () use ($accounts_fee_tran_code) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $delete_data = \DB::connection($this->dbList[$i])->table('all_accounts_fees')->where('accounts_fee_tran_code',$accounts_fee_tran_code)->delete();

                            if(!$delete_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::EventLogWrite('delete,all_accounts_fees',json_encode($accounts_fee_tran_code));
                            \App\System::TransactionCommit();

                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/accounts/fee-payment')->with('message',"Accounts Fee Payment Deleted Successfully!");
                }else return \Redirect::to('/accounts/fee-payment')->with('errormessage',"Accounts Fee Payment Already Have Transaction!");
            }else return \Redirect::to('/accounts/fee-payment')->with('errormessage',"Invalid id !!!");

        }catch(\Exception  $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('errormessage','Not Deleted !');
        }
    }





    /********************************************
    ## AccountsWaiverPage
    *********************************************/

    public function AccountsWaiverPage(){

        $data['page_title'] = $this->page_title;
        $account_waiver_list=\DB::table('waivers')
                        ->select('waivers.*')
                        ->orderBy('created_at','desc')->paginate(10);
        $account_waiver_list->setPath(url('/accounts/waiver'));
        $waiver_pagination = $account_waiver_list->render();
        $data['waiver_pagination'] = $waiver_pagination;
        $data['account_waiver_list'] = $account_waiver_list;
        return \View::make('pages.accounts.account-waiver',$data);
    }



    /********************************************
    ## AccountsWaiverSubmit
    *********************************************/

    public function AccountsWaiverSubmit(){

        $data['page_title'] = $this->page_title;

        $rule = ['waiver_name' => 'Required',
        'waiver_rate' => 'Required|numeric'
        ];


        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $user =\Auth::user()->user_id;
            $waiver_name_slug = explode(' ', strtolower(\Request::input('waiver_name')));
            $waiver_name_slug = implode('_', $waiver_name_slug);
            $waiver_data_info = [
                            'waiver_tran_code' => $uuid->string,
                            'waiver_name' => \Request::input('waiver_name'),
                            'waiver_name_slug' =>$waiver_name_slug,
                            'waiver_rate' => \Request::input('waiver_rate'),
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>$user,
                            'updated_by' =>$user,
                            ];

             try{

                $success = \DB::transaction(function () use ($waiver_data_info) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $update_data = \DB::connection($this->dbList[$i])->table('waivers')->insert($waiver_data_info);

                        if(!$update_data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,waivers',json_encode($waiver_data_info));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/accounts/waiver')->with('message','Waiver list has been added.');

             }catch(\Exception  $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                 return \Redirect::to('/accounts/waiver')->with('errormessage','Waiver Already exists.');
             }

            return \Redirect::to('/accounts/waiver')->with('message','Waiver list has been added.');

        }else return \Redirect::to('/accounts/waiver')->withInput(\Request::all())->withErrors($v->messages());

    }


    /********************************************
    # AccountsWaiverDelete
    *********************************************/
    public function AccountsWaiverDelete($waiver_name_slug){

        try{

            $waivers_info=\DB::table('student_accounts_info')->where('waiver_type',$waiver_name_slug)->first();
            if(empty($waivers_info)){

                $success = \DB::transaction(function () use ($waiver_name_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $delete_data = \DB::connection($this->dbList[$i])->table('waivers')->where('waiver_name_slug',$waiver_name_slug)->delete();

                        if(!$delete_data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,waivers',json_encode($waiver_name_slug));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/accounts/waiver')->with('message'," Deleted Successfully!");
            }else return \Redirect::to('/accounts/waiver')->with('errormessage'," This type waiver are used.");

        }catch(\Exception  $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
             return \Redirect::to('/accounts/waiver')->with('errormessage','Waiver Already exists.');
        }

    }



    /*******************************************
    # EditAccountWaiverPage
    ********************************************/
    public function EditAccountWaiverPage($waiver_name_slug){

        $edit_waiver_category=\DB::table('waivers')->where('waiver_name_slug',$waiver_name_slug)->first();

        if(!empty($edit_waiver_category)){
            $data['page_title'] = $this->page_title;
        
            $data['edit_waiver_category']=$edit_waiver_category;
            return \View::make('pages.accounts.account-waiver-edit',$data);
        }else return \Redirect::to('/accounts/fee-category')->with('errormessage',"Invalid Fee Category!");
        
    }


    /********************************************
    # UpdateAccountWaiverPage
    *********************************************/
    public function UpdateAccountWaiverPage($waiver_name_slug){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->user_id;
        $rule = ['waiver_name' => 'Required',
         'waiver_rate' => 'Required|numeric'
        ];
        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $waiver_new_name_slug = explode(' ', strtolower(\Request::input('waiver_name')));
            $waiver_new_name_slug = implode('_', $waiver_new_name_slug);
            $waiver_update_data = [
                                'waiver_name' => \Request::input('waiver_name'),
                                'waiver_name_slug' =>$waiver_new_name_slug,
                                'waiver_rate' => \Request::input('waiver_rate'),
                                'updated_at' =>$now,
                                'updated_by' =>$user,
                            ];
            $waivers_info=\DB::table('student_accounts_info')->where('waiver_type',$waiver_name_slug)->first();
            if(empty($waivers_info)){

                try{

                    $success = \DB::transaction(function () use ($waiver_update_data, $waiver_name_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $update_data = \DB::connection($this->dbList[$i])->table('waivers')->where('waiver_name_slug',$waiver_name_slug)->update($waiver_update_data);

                            if(!$update_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,waivers',json_encode($waiver_update_data));
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/accounts/waiver')->with('message',"Waiver name  Updated Successfully!");

                }catch(\Exception  $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/accounts/waiver')->with('errormessage','Waiver category Already exists.');
                }

            }else return \Redirect::to('/accounts/waiver')->with('errormessage','Waiver category Already exists.');


        }else return \Redirect::to('/accounts/waiver')->withErrors($v->messages());
    }

    #---------------------------end-------------------------------#




    /*******************************************
    # AccountApplicantTotalAmountPage
    ********************************************/
    public function AccountApplicantTotalAmountPage(){

        if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year']) || isset($_GET['payment'])){

                $applicant_list = \DB::table('applicant_basic')->where(function($query){

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

                    if(isset($_GET['payment']) && ($_GET['payment'] ==0) && ($_GET['payment'] !='all')){
                        $query->where(function ($q){
                            $q->where('payment_status', $_GET['payment']);
                        });
                    }elseif(isset($_GET['payment']) && ($_GET['payment'] !='all') && ($_GET['payment'] ==1)){
                         $query->where(function ($q){
                            $q->where('payment_status', '>=', $_GET['payment']);
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
                        ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                        ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                        ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                        ->leftJoin('applicant_fees_transaction','applicant_fees_transaction.applicant_tran_code','like','applicant_basic.applicant_tran_code')
                        ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title','applicant_fees_transaction.applicant_fees_amount')
                        ->orderBy('applicant_basic.created_at','desc')->get();

                    $data['applicant_list']= $applicant_list;
                }


        $data['page_title'] = $this->page_title;
        return \View::make('pages.accounts.accounts-applicant-total-amount',$data);

    }



    /********************************************
    ## ApplicantTotalListExcelDownload 
    *********************************************/

    public function ApplicantTotalListExcelDownload(){

        $excel_name = 'total_applicant_list_'.date('Y_m_d_i_s');

        \Excel::create($excel_name, function($excel) {
            $excel->sheet('First sheet', function($sheet) {

                /*------------------------------------Get Request--------------------------------------------*/
                if(isset($_GET['program']) || isset($_GET['semester']) || isset($_GET['academic_year']) || isset($_GET['payment'])){

                    $applicant_list = \DB::table('applicant_basic')->where(function($query){

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

                        if(isset($_GET['payment']) && ($_GET['payment'] ==0) && ($_GET['payment'] !='all')){
                            $query->where(function ($q){
                                $q->where('payment_status', $_GET['payment']);
                            });
                        }elseif(isset($_GET['payment']) && ($_GET['payment'] !='all') && ($_GET['payment'] ==1)){
                            $query->where(function ($q){
                                $q->where('payment_status', '>=', $_GET['payment']);
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
                }
                else{
                   $applicant_list = \DB::table('applicant_basic')
                   ->leftJoin('applicant_personal','applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
                   ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
                   ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
                   ->leftJoin('applicant_fees_transaction','applicant_fees_transaction.applicant_tran_code','like','applicant_basic.applicant_tran_code')
                   ->select('applicant_basic.*','applicant_personal.*','univ_program.program_code','univ_semester.semester_title','applicant_fees_transaction.applicant_fees_amount')
                   ->orderBy('applicant_basic.created_at','desc')->get();

                   $data['applicant_list']= $applicant_list;
                }

                $data['page_title'] = 'List';

                $sheet->loadView('excelsheet.pages.accounts.excel-total-applicant-list',$data);
            });
        })->export('xlsx');

    }





    /********************************************
    ## AccountSummeryPage 
    *********************************************/

    public function AccountSummeryPage(){

        /*------------------------------------Get Request--------------------------------------------*/
        if(isset($_GET['date_from']) && isset($_GET['date_to']) || isset($_GET['program'])){
            $search_from = $_GET['date_from'].' 00:00:00';
            $search_to = $_GET['date_to'].' 23:59:59';
            $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('student_payment_transactions.payment_amounts','!=','0')
                ->where(function($query){

                           if(isset($_GET['program']) && ($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_program', $_GET['program']);
                                  });
                            }
                        })
                ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                ->orderBy('student_payment_transactions.updated_at','desc')
                ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                ->get();

            $total_amount=\DB::table('student_payment_transactions')
                ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                ->where('student_payment_transactions.payment_amounts','!=','0')
                ->whereBetween('student_payment_transactions.updated_at',array($search_from, $search_to))
                ->where(function($query){

                           if(isset($_GET['program']) && ($_GET['program'] !=0)){
                                $query->where(function ($q){
                                    $q->where('payment_program', $_GET['program']);
                                  });
                            }
                        }) 
                ->sum('student_payment_transactions.payment_amounts');

        }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $now=date('Y-m-d');
            $search_from = $now.' 00:00:00';
            $search_to = $now.' 23:59:59';
                $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                    ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                    ->where('student_payment_transactions.payment_amounts','!=','0')
                    ->whereBetween('student_payment_transactions.updated_at',array($search_from,$search_to))

                    ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                    ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                    ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                    ->orderBy('student_payment_transactions.updated_at','desc')
                    ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                    ->get();

                $total_amount=\DB::table('student_payment_transactions')
                    ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                    ->where('student_payment_transactions.payment_amounts','!=','0')
                    ->whereBetween('student_payment_transactions.updated_at',array($search_from,$search_to))
                    ->sum('student_payment_transactions.payment_amounts');

        }

        $data['total_amount'] = $total_amount;
        $data['student_payment_transaction_detail'] = $student_payment_transaction_detail;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.accounts.accounts-account-summery',$data);
    }


    /********************************************
    ## AccountSummeryExcelDownload 
    *********************************************/

    public function AccountSummeryExcelDownload($program, $search_from, $search_to){

        try{
            $excel_name = 'accounts_summery_list_'.date('Y_m_d_i_s');

                \Excel::create($excel_name, function($excel) use($program, $search_from, $search_to) {
                    $excel->sheet('First sheet', function($sheet)  use($program, $search_from, $search_to){
                    $now=date('Y-m-d');
                    $search_from = $search_from.' 00:00:00';
                    $search_to = $search_to.' 23:59:59';

                    if($program == '0'){

                        $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_amounts','!=','0')
                            ->whereBetween('student_payment_transactions.updated_at', [$search_from,$search_to])
                            ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                            ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                            ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                            ->orderBy('student_payment_transactions.updated_at','desc')
                            ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                            ->get();


                        $total_amount=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_amounts','!=','0')
                            ->whereBetween('student_payment_transactions.updated_at',[$search_from,$search_to])
                            ->sum('student_payment_transactions.payment_amounts');
                    }else{
                        $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_amounts','!=','0')
                            ->whereBetween('student_payment_transactions.updated_at', [$search_from,$search_to])
                            ->where('student_payment_transactions.payment_program', $program)
                            ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                            ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                            ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                            ->orderBy('student_payment_transactions.updated_at','desc')
                            ->select('student_payment_transactions.*','univ_semester.semester_title','univ_program.program_code','fee_category.fee_category_name')
                            ->get();


                        $total_amount=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_amounts','!=','0')
                            ->whereBetween('student_payment_transactions.updated_at',[$search_from,$search_to])
                            ->where('student_payment_transactions.payment_program', $program)
                            ->sum('student_payment_transactions.payment_amounts');

                    }
                         $data['total_amount'] = $total_amount;
                         $data['student_payment_transaction_detail'] = $student_payment_transaction_detail;
                         $data['page_title'] = 'List';

                    $sheet->loadView('excelsheet.pages.accounts.excel-accounts-summery',$data);
                    });
                })->export('xlsx');

        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return  \Redirect::back()->withErrors($v->messages());
        }



    }


    /********************************************
    ## StudentPaymentExcel 
    *********************************************/

    public function StudentPaymentExcel($student_serial_no, $semester, $academic_year){

        $data['page_title'] = $this->page_title;

        try{
            $excel_name = 'accounts_student-payment_list_'.date('Y_m_d_i_s');

                \Excel::create($excel_name, function($excel) use($student_serial_no, $semester, $academic_year) {
                    $excel->sheet('First sheet', function($sheet)  use($student_serial_no, $semester, $academic_year){
                    $now=date('Y-m-d');

                    if($semester != 0 && $academic_year != 0){
                        $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_student_serial_no',$student_serial_no)
                            ->where('student_payment_transactions.payment_semster',$semester)
                            ->where('student_payment_transactions.payment_year',$academic_year)
                            ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
                            ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                            ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                            ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                            ->orderBy('student_payment_transactions.updated_at','desc')
                            ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','univ_semester.*','accounts_transaction_history.*','univ_program.*','fee_category.*')
                            ->get();


                        $total_amount=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_student_serial_no',$student_serial_no)
                            ->where('student_payment_transactions.payment_semster',$semester)
                            ->where('student_payment_transactions.payment_year',$academic_year)
                            ->sum('student_payment_transactions.payment_amounts');

                        $total_payment_receivable=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->where('student_payment_transactions.payment_semster',$semester)
                            ->where('student_payment_transactions.payment_year',$academic_year)
                            ->sum('payment_receivable');

                        $total_payment_paid=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_semster',$semester)
                            ->where('student_payment_transactions.payment_year',$academic_year)
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->sum('payment_paid');

                        $total_others_paid=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->where('student_payment_transactions.payment_semster',$semester)
                            ->where('student_payment_transactions.payment_year',$academic_year)
                            ->sum('payment_others');

                        $total_payment_due= $total_payment_receivable-$total_payment_paid;

                    }else{
                        $student_payment_transaction_detail=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_student_serial_no',$student_serial_no)
                            ->leftjoin('accounts_transaction_history','accounts_transaction_history.transaction_tran_code','=','student_payment_transactions.accounts_payment_tran_code')
                            ->leftjoin('univ_semester','univ_semester.semester_code','=','student_payment_transactions.payment_semster')
                            ->leftjoin('univ_program','univ_program.program_id','=','student_payment_transactions.payment_program')
                            ->leftjoin('fee_category','fee_category.fee_category_name_slug','=','student_payment_transactions.payment_transaction_fee_type')
                            ->orderBy('student_payment_transactions.updated_at','desc')
                            ->select('student_payment_transactions.*','student_payment_transactions.updated_at AS transaction_date','univ_semester.*','accounts_transaction_history.*','univ_program.*','fee_category.*')
                            ->get();

                        $total_amount=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('student_payment_transactions.payment_student_serial_no',$student_serial_no)
                            ->sum('student_payment_transactions.payment_amounts');


                        $total_payment_receivable=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->sum('payment_receivable');

                        $total_payment_paid=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->sum('payment_paid');

                        $total_others_paid=\DB::table('student_payment_transactions')
                            ->where('student_payment_transactions.payment_transaction_fee_type','!=','application_form_fee')
                            ->where('payment_student_serial_no',$student_serial_no)
                            ->sum('payment_others');

                        $total_payment_due= $total_payment_receivable-$total_payment_paid;

                    }
                        $student_info=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)
                            ->leftJoin('univ_program','program_id','=','student_basic.program')
                            ->select('student_basic.*','univ_program.*')
                            ->first();

                         $data['student_info'] = $student_info;
                         $data['total_payment_receivable'] = $total_payment_receivable;
                         $data['total_payment_paid'] = $total_payment_paid;
                         $data['total_others_paid'] = $total_others_paid;
                         $data['total_payment_due'] = $total_payment_due;
                         $data['total_amount'] = $total_amount;
                         $data['student_payment_transaction_detail'] = $student_payment_transaction_detail;
                         $data['page_title'] = 'List';

                    $sheet->loadView('excelsheet.pages.accounts.excel-accounts-student-payment',$data);
                    });
                })->export('xlsx');

        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return  \Redirect::back()->with('errormessage','Info Already exists.');
        }

    }










    /*****************end of Acoounts Controller*********************************/
}
