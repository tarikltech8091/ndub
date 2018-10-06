<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    

    /********************************************
    ## ApplicantConfirmEmail 
    *********************************************/
    public static function ApplicantConfirmEmail($applicant_serial_no){

        $applicant_info=\DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)
        ->leftJoin('applicant_personal','applicant_personal.applicant_tran_code','=','applicant_basic.applicant_tran_code')
        ->select('applicant_basic.*','applicant_personal.*')
        ->first();

        $applicant_email = $applicant_info->email;
        $applicant_name = $applicant_info->first_name.isset($applicant_info->middle_name) ? ' '.$applicant_info->middle_name:''.' '.$applicant_info->last_name;

        $data['applicant_info'] = $applicant_info;

        \Mail::send('email.pages.application-confirmation-mail', $data, function($message) use ($applicant_email,$applicant_name) {

            $message->to($applicant_email, $applicant_name)->subject('Applicant Confirmation');

        });

        return true;
    }



    /********************************************
    ## AccountsApplicantMessageEmail 
    *********************************************/
    public static function AccountsApplicantMessageEmail($applicant_serial_no, $message_subject, $applicants_message){

        $applicant_info=\DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)
        ->leftJoin('applicant_personal','applicant_personal.applicant_tran_code','=','applicant_basic.applicant_tran_code')
        ->select('applicant_basic.*','applicant_personal.*')
        ->first();

        $applicant_email = $applicant_info->email;
        $applicant_name = $applicant_info->first_name.isset($applicant_info->middle_name) ? ' '.$applicant_info->middle_name:''.' '.$applicant_info->last_name;

        $data['applicant_info'] = $applicant_info;
        $data['applicants_message'] = $applicants_message;

        \Mail::send('email.pages.accounts-applicant-message-mail', $data, function($message) use ($applicant_email,$applicant_name, $message_subject) {

            $message->to($applicant_email, $applicant_name)->subject($message_subject);

        });

        return true;
    }



    /********************************************
    ## ApplicantAdmitCardEmail 
    *********************************************/
    public static function ApplicantAdmitCardEmail($applicant_serial_no){

        $applicant_info=\DB::table('applicant_basic')->where('applicant_serial_no',$applicant_serial_no)
        ->leftJoin('univ_program','univ_program.program_id','=','applicant_basic.program')
        ->leftJoin('applicant_personal','applicant_personal.applicant_tran_code','=','applicant_basic.applicant_tran_code')
        ->leftJoin('univ_semester','univ_semester.semester_code','=','applicant_basic.semester')
        ->select('applicant_basic.*','univ_program.*','applicant_personal.*','univ_semester.*')
        ->first();

        $data['applicant_info'] = $applicant_info;

        $applicant_email = $applicant_info->email;
        $applicant_name = $applicant_info->first_name.isset($applicant_info->middle_name) ? ' '.$applicant_info->middle_name:''.' '.$applicant_info->last_name;


        $pdf_name =$applicant_serial_no.'-'.time().'.pdf';
        \PDF::loadView('application.pdf.application-admitcard',$data)->save('applicant-admit-card/'.$pdf_name);
        
        $file_path = public_path().'/applicant-admit-card/'.$pdf_name;

        \Mail::send('email.pages.applicant-admit-card-mail', $data, function($message) use ($applicant_email,$applicant_name,$file_path) {
            
          $message->to($applicant_email, $applicant_name)->subject('Applicant Admit Card');

          $message->attach($file_path);

         });

        return true;
    }



    
     /********************************************
        ## ForgotPasswordEmail 
        *********************************************/
    public static function ForgotPasswordEmail($user_id,$reset_url){

        $user_info=\DB::table('users')->where('user_id',$user_id)->first();

        if(isset($user_info)){
            $users_id=$user_info->user_id;
            $users_type=$user_info->user_type;

            if($users_type=='accounts' || $users_type=='academic' || $users_type=='systemadmin' || $users_type=='register'){
                $employee_email= \DB::table('employee_basic')->where('employee_id','=',$users_id)->first();
                $users_email=$employee_email->email;  
            }elseif($users_type=='faculty'){
                $faculty_email= \DB::table('faculty_basic')->where('faculty_id','=',$users_id)->first();
                $users_email=$faculty_email->email; 
            }else{
                $student_email= \DB::table('student_basic')->where('student_serial_no','=',$users_id)->first();
                $users_email=$student_email->email; 
            }


            $data['user_info'] = $user_info;
            $data['reset_url'] = $reset_url;

          // $user_email = $user_info->email;
            $user_email = $users_email;
            $user_name = $user_info->name;


            \Mail::send('email.pages.forget-password-mail', $data, function($message) use ($user_email,$user_name) {
                
                $message->to($user_email,$user_name)->subject('Password Recovery');

            });


            return true;

        }else{
            return \Redirect::back()->with('message',"Invalid User ID!");
        }    


    }


#----------end--------------#
}
