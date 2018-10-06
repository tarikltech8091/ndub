<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Applicant;
use Session;
use App\System;
use App\Email;
use Carbon;
use Exception;




/*******************************
#
## Application Controller
#
*******************************/
class ApplicationController extends Controller
{
    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## ApplicationInformationPage 
    *********************************************/
    public function ApplicationInformationPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('application.pages.application',$data);
    }
    
    /********************************************
    ## ApplicationFormPage 
    *********************************************/

    public function ApplicationFormPage(){

            // $application_basic_form = \Session::get('application_basic_form');
            // var_dump($application_basic_form);
            
    	if(\Session::has('applicant_form')){

    	 	$applicant_form_info = \Session::get('applicant_form');

            if($applicant_form_info['step'] ==5){

                \App\Applicant::ApplicantSessionRemove();

                return \Redirect::to('/online-application/form');

            }else $data['from_part'] = $applicant_form_info['step'];
            
            

    	}else{

    		$uuid = \Uuid::generate(4);
    		$applicant_form = array(
    				'applicant_form_uuid' =>$uuid->string,
    				'step'=>1
    			);
    		\Session::put('applicant_form',$applicant_form);
    		$data['from_part'] = $applicant_form['step'];
    	}
		
        $data['page_title'] = $this->page_title;
        return \View::make('application.pages.application-form',$data);
    }

    

    /********************************************
    ## ApplicationBasicSubmit
    *********************************************/

    public function ApplicationBasicSubmit(){

    	

        if(\Session::has('applicant_form')){

            $v = \App\Applicant::BasicFormValidation(\Request::all());

            if($v->passes()){

                $applicant_form = \Session::get('applicant_form');
                $applicant_form['step'] = 2;

                /*------------Input Processing--------------------------*/
                $application_basic_form['first_name'] = strtoupper(\Request::input('first_name'));
                $application_basic_form['middle_name'] = strtoupper(\Request::input('middle_name'));
                $application_basic_form['last_name'] = strtoupper(\Request::input('last_name'));
                $application_basic_form['program'] = \Request::input('program');
                $application_basic_form['semester'] = \Request::input('semester');
                $application_basic_form['academic_year'] = \Request::input('academic_year');
                $application_basic_form['bank_name'] = \Request::input('bank_name');
                $application_basic_form['bank_slip_number'] = \Request::input('bank_slip_number');
                $application_basic_form['applicant_fees_amount'] = \Request::input('applicant_fees_amount');
                $application_basic_form['image_url'] = \Request::input('image_url');
                $application_basic_form['applicant_form_uuid'] = $applicant_form['applicant_form_uuid'];
                $application_basic_form['ssc_roll'] = \Request::input('ssc_roll');


                /*--------------------academic for MBA student---------------*/
                $program_id = \Request::input('program');

                if($program_id=='96' || $program_id=='97' || $program_id=='98'){
                    $application_graduate_major['program'] = $program_id;

                    $application_graduate_major['major_subject_1'] = 'Management';
                    $application_graduate_major['major_subject_priority_1'] = \Request::input('management');
                    $application_graduate_major['major_subject_2'] = 'Marketing';
                    $application_graduate_major['major_subject_priority_2'] = \Request::input('marketing');
                    $application_graduate_major['major_subject_3'] = 'Finance';
                    $application_graduate_major['major_subject_priority_3'] = \Request::input('finance');
                    $application_graduate_major['major_subject_4'] = 'Marketing';
                    $application_graduate_major['major_subject_priority_4'] = \Request::input('marketing');
                    $application_graduate_major['major_subject_5'] = 'HR Management';
                    $application_graduate_major['major_subject_priority_5'] = \Request::input('hr_management');
                    $application_graduate_major['major_subject_6'] = 'Bank Management';
                    $application_graduate_major['major_subject_priority_6'] = \Request::input('bank_management');
                    $application_graduate_major['major_subject_7'] = 'International Bussiness';
                    $application_graduate_major['major_subject_priority_7'] = \Request::input('int_bussiness');

                    \Session::put('application_graduate_major',$application_graduate_major);
                }
                /*--------------------end academic for MBA student---------------*/


                if(!empty(\Request::input('bank_slip_number'))){
                    $slip_exits = \App\Applicant::ApplicantBankSlipCheck(\Request::input('bank_slip_number'));

                    if($slip_exits>0)
                        return \Redirect::to('/online-application/form')->with('errormessage','Payment slip is invalid.!!');

                }


                \Session::put('applicant_form',$applicant_form);
                \Session::put('application_basic_form',$application_basic_form);

                /*--------------------------------------*/
                $applicant_form = \Session::get('application_basic_form');

                return \Redirect::to('/online-application/form');


            }else return \Redirect::to('/online-application/form')->withInput()->withErrors($v->messages());

        }else return \Redirect::to('/online-application/form')->withInput()->with('errormessage','Please Refresh Your Page and Try Again !!');

    }


    /********************************************
    ## ApplicantExperience 
    *********************************************/
    public function ApplicantExperience($multi_count){

        $data['page_title'] = $this->page_title;

        $data['multi_count'] = $multi_count;

        return \View::make('application.ajax.applicant-experience',$data);

    }



    /********************************************
    ## ApplicantSubjectAdd 
    *********************************************/
    public function ApplicantSubjectAdd($add_subject_count,$type){

        $data['type'] = $type;

        $data['add_subject_count'] = $add_subject_count;

        return \View::make('application.ajax.applicant-add-subject',$data);

    }



    /********************************************
    ## ApplicationAcademicSubmit
    *********************************************/

    public function ApplicationAcademicSubmit(){

        if(\Session::has('applicant_form')){

         $v = \App\Applicant::ApplicationAcademicSubmit(\Request::all());
         if($v->passes()){

            $applicant_form = \Session::get('applicant_form');

            /*-----------applicant check for duplicate---------------------------*/

            $application_basic_form = \Session::get('application_basic_form');
            $applicant_entry_hsc = \App\Applicant::ApplicantEntry(\Request::input('hsc_alevel_exam_type'),\Request::input('hsc_alevel_rollnumber'),$application_basic_form['program']);
            $applicant_entry_ssc = \App\Applicant::ApplicantEntry(\Request::input('ssc_olevel_exam_type'),\Request::input('ssc_olevel_rollnumber'),$application_basic_form['program']);

            if(($application_basic_form['ssc_roll']!=\Request::input('ssc_olevel_rollnumber'))||($applicant_entry_hsc==1)||($applicant_entry_ssc==1)){

                return  \Redirect::to('/online-application/form')->withInput()->with('errormessage','Invalid SSC or HSC roll number !');
            }


            /*------------Input Processing--------------------------*/
            $application_academic_form['ssc_olevel_exam_type'] = \Request::input('ssc_olevel_exam_type');
            $application_academic_form['ssc_olevel_group'] = \Request::input('ssc_olevel_group');
            $application_academic_form['ssc_olevel_rollnumber'] = \Request::input('ssc_olevel_rollnumber');
            $application_academic_form['ssc_olevel_board'] = \Request::input('ssc_olevel_board');
            $application_academic_form['ssc_institute_name'] = \Request::input('ssc_institute_name');
            $application_academic_form['ssc_olevel_year'] = \Request::input('ssc_olevel_year');

            $application_academic_form['ssc_olevel_subject_1'] = \Request::input('ssc_olevel_subject_1');
            $application_academic_form['ssc_olevel_subject_2'] = \Request::input('ssc_olevel_subject_2');
            $application_academic_form['ssc_olevel_subject_3'] = \Request::input('ssc_olevel_subject_3');
            $application_academic_form['ssc_olevel_subject_4'] = \Request::input('ssc_olevel_subject_4');
            $application_academic_form['ssc_olevel_subject_5'] = \Request::input('ssc_olevel_subject_5');


            $application_academic_form['ssc_olevel_subject_gpa_1'] = \Request::input('ssc_olevel_subject_gpa_1');
            $application_academic_form['ssc_olevel_subject_gpa_2'] = \Request::input('ssc_olevel_subject_gpa_2');
            $application_academic_form['ssc_olevel_subject_gpa_3'] = \Request::input('ssc_olevel_subject_gpa_3');
            $application_academic_form['ssc_olevel_subject_gpa_4'] = \Request::input('ssc_olevel_subject_gpa_4');
            $application_academic_form['ssc_olevel_subject_gpa_5'] = \Request::input('ssc_olevel_subject_gpa_5');
            $application_academic_form['total_ssc_olevel_gpa'] = number_format((float) \Request::input('total_ssc_olevel_gpa'),2,'.','');

            $application_academic_form['ssc_olevel_subject_grade_1'] = \Request::input('ssc_olevel_subject_grade_1');
            $application_academic_form['ssc_olevel_subject_grade_2'] = \Request::input('ssc_olevel_subject_grade_2');
            $application_academic_form['ssc_olevel_subject_grade_3'] = \Request::input('ssc_olevel_subject_grade_3');
            $application_academic_form['ssc_olevel_subject_grade_4'] = \Request::input('ssc_olevel_subject_grade_4');
            $application_academic_form['ssc_olevel_subject_grade_5'] = \Request::input('ssc_olevel_subject_grade_5');

            $application_academic_form['multi_ssc_subject_count'] = \Request::input('multi_ssc_subject_count');
            $application_academic_form['multi_ssc_subject_count_ajax'] = \Request::input('multi_ssc_subject_count_ajax');

            if(!empty(\Request::input('multi_ssc_subject_count_ajax'))){
                $count=\Request::input('multi_ssc_subject_count_ajax');
                for($i=6;$i<=$count;$i++){
                    $application_academic_form['ssc_olevel_subject_'.$i] = \Request::input('ssc_olevel_subject_'.$i);
                    $application_academic_form['ssc_olevel_subject_gpa_'.$i] = \Request::input('ssc_olevel_subject_gpa_'.$i);
                    $application_academic_form['ssc_olevel_subject_grade_'.$i] = \Request::input('ssc_olevel_subject_grade_'.$i);
                }
            }

            $application_academic_form['hsc_alevel_exam_type'] = \Request::input('hsc_alevel_exam_type');
            $application_academic_form['hsc_alevel_group'] = \Request::input('hsc_alevel_group');
            $application_academic_form['hsc_alevel_rollnumber'] = \Request::input('hsc_alevel_rollnumber');
            $application_academic_form['hsc_alevel_board'] = \Request::input('hsc_alevel_board');
            $application_academic_form['hsc_institute_name'] = \Request::input('hsc_institute_name');
            $application_academic_form['hsc_alevel_year'] = \Request::input('hsc_alevel_year');

            $application_academic_form['hsc_alevel_subject_1'] = \Request::input('hsc_alevel_subject_1');
            $application_academic_form['hsc_alevel_subject_2'] = \Request::input('hsc_alevel_subject_2');
            $application_academic_form['hsc_alevel_subject_3'] = \Request::input('hsc_alevel_subject_3');
            $application_academic_form['hsc_alevel_subject_4'] = \Request::input('hsc_alevel_subject_4');
            $application_academic_form['hsc_alevel_subject_5'] = \Request::input('hsc_alevel_subject_5');

            $application_academic_form['hsc_alevel_subject_gpa_1'] = \Request::input('hsc_alevel_subject_gpa_1');
            $application_academic_form['hsc_alevel_subject_gpa_2'] = \Request::input('hsc_alevel_subject_gpa_2');
            $application_academic_form['hsc_alevel_subject_gpa_3'] = \Request::input('hsc_alevel_subject_gpa_3');
            $application_academic_form['hsc_alevel_subject_gpa_4'] = \Request::input('hsc_alevel_subject_gpa_4');
            $application_academic_form['hsc_alevel_subject_gpa_5'] = \Request::input('hsc_alevel_subject_gpa_5');

            $application_academic_form['total_hsc_alevel_gpa'] = number_format((float) \Request::input('total_hsc_alevel_gpa'),2,'.','');

            $application_academic_form['hsc_alevel_subject_grade_1'] = \Request::input('hsc_alevel_subject_grade_1');
            $application_academic_form['hsc_alevel_subject_grade_2'] = \Request::input('hsc_alevel_subject_grade_2');
            $application_academic_form['hsc_alevel_subject_grade_3'] = \Request::input('hsc_alevel_subject_grade_3');
            $application_academic_form['hsc_alevel_subject_grade_4'] = \Request::input('hsc_alevel_subject_grade_4');
            $application_academic_form['hsc_alevel_subject_grade_5'] = \Request::input('hsc_alevel_subject_grade_5');

            $application_academic_form['multi_hsc_subject_count'] = \Request::input('multi_hsc_subject_count');
            $application_academic_form['multi_hsc_subject_count_ajax'] = \Request::input('multi_hsc_subject_count_ajax');

            if(!empty(\Request::input('multi_hsc_subject_count_ajax'))){
                $count_hsc=\Request::input('multi_hsc_subject_count_ajax');
                for($i=6;$i<=$count_hsc;$i++){
                    $application_academic_form['hsc_alevel_subject_'.$i] = \Request::input('hsc_alevel_subject_'.$i);
                    $application_academic_form['hsc_alevel_subject_gpa_'.$i] = \Request::input('hsc_alevel_subject_gpa_'.$i);
                    $application_academic_form['hsc_alevel_subject_grade_'.$i] = \Request::input('hsc_alevel_subject_grade_'.$i);
                }
            }



            if(\Session::has('application_basic_form')){
                $application_basic_form = \Session::get('application_basic_form');
                $program_id = $application_basic_form['program'];

                $program_code = \App\Applicant::GetProgramCode($program_id);
            }

            if(($program_code->program_degree_code=='02') && !($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){
                $application_academic_form['hons_qualification'] = \Request::input('hons_qualification');
                $application_academic_form['hons_subject'] = \Request::input('hons_subject');
                $application_academic_form['hons_university_college'] = \Request::input('hons_university_college');
                $application_academic_form['hons_passing_year'] = \Request::input('hons_passing_year');
                $application_academic_form['hons_grade_division'] = \Request::input('hons_grade_division');

                $application_academic_form['masters_qualification'] = \Request::input('masters_qualification');
                $application_academic_form['masters_subject'] = \Request::input('masters_subject');
                $application_academic_form['masters_university_college'] = \Request::input('masters_university_college');
                $application_academic_form['masters_passing_year'] = \Request::input('masters_passing_year');
                $application_academic_form['masters_grade_division'] = \Request::input('masters_grade_division');
            }

            if(($program_code->program_degree_code=='02') && ($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){

                $application_academic_form['exam_type_for_mba'] = \Request::input('exam_type_for_mba');
                $application_academic_form['area_major'] = \Request::input('area_major');
                $application_academic_form['university_college'] = \Request::input('university_college');
                $application_academic_form['roll_number'] = \Request::input('roll_number');
                $application_academic_form['passing_year'] = \Request::input('passing_year');
                $application_academic_form['cgpa'] = \Request::input('cgpa');

                $application_academic_form['masters_qualification'] = \Request::input('masters_qualification');
                $application_academic_form['masters_subject'] = \Request::input('masters_subject');
                $application_academic_form['masters_university_college'] = \Request::input('masters_university_college');
                $application_academic_form['masters_passing_year'] = \Request::input('masters_passing_year');
                $application_academic_form['masters_grade_division'] = \Request::input('masters_grade_division');
            }

            if(($program_code->program_degree_code=='02') && ($program_code->program_id=='97')){
                if(\Request::input('multi_count')!=0){
                    $count=\Request::input('multi_count');
                    for ($i=1; $i<=$count;$i++) {
                        $applicant_pro_experience['organization_'.$i] = \Request::input('organization_'.$i);
                        $applicant_pro_experience['position_held_'.$i] = \Request::input('position_held_'.$i);
                        $applicant_pro_experience['period_from_'.$i] = \Request::input('period_from_'.$i);
                        $applicant_pro_experience['period_to_'.$i] = \Request::input('period_to_'.$i);


                        $experience_from=\Request::input('period_from_'.$i);
                        $experience_to=\Request::input('period_to_'.$i);

                        if(!empty($experience_from) && !empty($experience_to)){
                            if($experience_to=='0000-00-00'){
                                $now=date('Y-m-d');
                                $period_from = new \DateTime($experience_from);
                                $period_to = new \DateTime($now);
                                $interval = $period_from->diff($period_to);
                                $year=$interval->format('%Y');
                                $month=$interval->format('%m');

                                $applicant_pro_experience['total_year_'.$i] = $year;
                                $applicant_pro_experience['total_months_'.$i] = $month;
                            }
                            else{
                                $period_from = new \DateTime($experience_from);
                                $period_to = new \DateTime($experience_to);
                                $interval = $period_from->diff($period_to);
                                $year=$interval->format('%Y');
                                $month=$interval->format('%m');

                                $applicant_pro_experience['total_year_'.$i] = $year;
                                $applicant_pro_experience['total_months_'.$i] = $month;
                            }

                        }
                        else
                        {
                            $applicant_pro_experience['total_year_'.$i] = '';
                            $applicant_pro_experience['total_months_'.$i] = '';
                        }

                    }

                    $applicant_pro_experience['count']=$count;

                    \Session::put('applicant_pro_experience',$applicant_pro_experience);

                }

            }


            $application_academic_form['applicant_form_uuid'] = $applicant_form['applicant_form_uuid'];
            \Session::put('application_academic_form',$application_academic_form);
            /*------------Input Processing--------------------------*/

            $applicant_form['step'] = 3;
            \Session::put('applicant_form',$applicant_form);

            return \Redirect::to('/online-application/form');


        }else return \Redirect::to('/online-application/form')->withInput()->withErrors($v->messages());

    }else return \Redirect::to('/online-application/form')->withInput()->with('errormessage','Check Applicant Information and Try Again !!');

}

    /********************************************
    ## ApplicationPersonalSubmit
    *********************************************/

    public function ApplicationPersonalSubmit(){


        if(\Session::has('applicant_form')){

            $v = \App\Applicant::PersonalFormValidation(\Request::all());

            if($v->passes()){

             $applicant_form = \Session::get('applicant_form');
             $applicant_form['step'] = 4;
             \Session::put('applicant_form',$applicant_form);

             /*------------Input Processing--------------------------*/
             $application_personl_form['gender'] = \Request::input('gender');
             $application_personl_form['date_of_birth'] = \Request::input('date_of_birth');
             $application_personl_form['blood_group'] = \Request::input('blood_group');
             $application_personl_form['marital'] = \Request::input('marital');
             $application_personl_form['birth_city'] = \Request::input('birth_city');
             $application_personl_form['birth_country'] = \Request::input('birth_country');
             $application_personl_form['nationality'] = \Request::input('nationality');
             $application_personl_form['applicant_email'] = \Request::input('applicant_email');
             $application_personl_form['applicant_phone'] = \Request::input('applicant_phone');
             $application_personl_form['applicant_mobile'] = \Request::input('applicant_mobile');
             $application_personl_form['religion'] = \Request::input('religion');
             $application_personl_form['applicant_form_uuid'] = $applicant_form['applicant_form_uuid'];
             \Session::put('application_personl_form',$application_personl_form);

             /*------------Input Processing--------------------------*/
             return \Redirect::to('/online-application/form');

            }else return \Redirect::to('/online-application/form')->withInput()->withErrors($v->messages());

        }else return \Redirect::to('/online-application/form')->withInput()->with('errormessage','Check Applicant Information and Try Again !!');

    }

    /********************************************
    ## ApplicationContactSubmit
    *********************************************/

    public function ApplicationContactSubmit(){

            if(\Session::has('applicant_form')){

                $v = \App\Applicant::ContactlFormValidation(\Request::all());

                if($v->passes()){

                    $applicant_form = \Session::get('applicant_form');
                    $applicant_form['step'] = 5;
                    \Session::put('applicant_form',$applicant_form);

                    /*------------Input Processing--------------------------*/
                    $application_contact_form['present_address_detail'] = \Request::input('present_address_detail');
                    $application_contact_form['present_postal_code'] = \Request::input('present_postal_code');
                    $application_contact_form['present_city'] = \Request::input('present_city');
                    $application_contact_form['present_country'] = \Request::input('present_country');
                    $application_contact_form['permanent_address_detail'] = \Request::input('permanent_address_detail');
                    $application_contact_form['permanent_postal_code'] = \Request::input('permanent_postal_code');
                    $application_contact_form['permanent_city'] = \Request::input('permanent_city');
                    $application_contact_form['permanent_country'] = \Request::input('permanent_country');
                    $application_contact_form['father_name'] = strtoupper(\Request::input('father_name'));
                    $application_contact_form['father_occupation'] = \Request::input('father_occupation');
                    $application_contact_form['father_contact_email'] = \Request::input('father_contact_email');
                    $application_contact_form['father_contact_mobile'] = \Request::input('father_contact_mobile');
                    $application_contact_form['mother_name'] = strtoupper(\Request::input('mother_name'));
                    $application_contact_form['mother_occupation'] = \Request::input('mother_occupation');
                    $application_contact_form['mother_contact_email'] = \Request::input('mother_contact_email');
                    $application_contact_form['mother_contact_mobile'] = \Request::input('mother_contact_mobile');

                    $application_contact_form['local_guardian_name'] = strtoupper(\Request::input('local_guardian_name'));
                    $application_contact_form['local_guardian_occupation'] = \Request::input('local_guardian_occupation');
                    $application_contact_form['local_guardian_contact_mobile'] = \Request::input('local_guardian_contact_mobile');
                    $application_contact_form['local_guardian_contact_email'] = \Request::input('local_guardian_contact_email');
                    
                    $application_contact_form['emergency_contact'] = \Request::input('emergency_contact');
                    $application_contact_form['applicant_form_uuid'] = $applicant_form['applicant_form_uuid'];
                    \Session::put('application_contact_form',$application_contact_form);

                    /*------------Input Processing--------------------------*/

                    return \Redirect::to('/online-application/form/complete')->with('message','Your Application has been successfully submitted.Please Collect your Serial No.!!');


                }
                else return \Redirect::to('/online-application/form')->withInput()->withErrors($v->messages());


            }
            return \Redirect::to('/online-application/form')->withInput()->with('errormessage','Check Applicant Information and Try Again !!');

    }

    

    /********************************************
    ## ApplicantImageUpload
    *********************************************/

    public function ApplicantImageUpload(){

        //passpost 413x531 ,450x558
        $maxwidth = 1500;
        $maxheight = 1500;

        $file = \Request::file('image');  

        $input = array('image' => $file);

        $rules = array(
            'image' => 'image|max:50'
        );

        $validator = \Validator::make($input, $rules);
        $sizevalidator = \App\Applicant::CurrectImageSize($maxwidth,$maxheight,$file); 

        if($validator->fails())
        {
            return \Response::json(['success' => 'invalid_format']);
        }
        else if(! $sizevalidator) {

            return \Response::json(['success' => 'filesize']);
        }else {

            $destinationPath = 'application/applied/';
            $filename = time()."-".$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            
            return \Response::json(['success' => true, 'file' => ($destinationPath.$filename)]);
        }

    }

    /********************************************
    ## ApplicationCompletePage
    *********************************************/

    public function ApplicationCompletePage(){


        $now = date('Y-m-d');

        if(\Session::has('applicant_form')){

            $applicant_form = \Session::get('applicant_form');

            if($applicant_form['step']==5){


                    $application_insert = \App\Applicant::ApplicationInsert();
                    if(!empty($application_insert)){
                        $st_d =$application_insert->created_at;
                        $end_d= $now; 

                        $message = date('Y-m-d H:i:s u').'->>Start: '.$st_d.'| End:'.$end_d;
                        \App\System::CustomLogWritter("systemlog","time_log",$message);

                        $applicant_info = \App\Applicant::ApplicationProfileInfo($application_insert->applicant_tran_code);
                        
                        ##-----------------applicant confirm mail send-------------##
                        if(!empty($applicant_info)){
                            \App\Email::ApplicantConfirmEmail($applicant_info->applicant_serial_no);
                            \App\System::EventLogWrite('email',json_encode($applicant_info->applicant_serial_no));
                        }

                        if($applicant_info){

                            $data['applicant_info'] = $applicant_info;
                            $data['page_title'] = $this->page_title;

                            \App\Applicant::ApplicantSessionRemove();

                            \Session::put('applicant_serial_no',$applicant_info->applicant_serial_no);

                            return \View::make('application.pages.application-complete',$data);

                        }else {

                            \App\Applicant::ApplicantSessionRemove();
                            return \Redirect::to('/online-application/form')->with('errormessage','Applicant Not Found !!');
                        }

                    }else{

                        \App\Applicant::ApplicantSessionRemove();
                        return \Redirect::to('/online-application/form')->with('errormessage','Session Removed.Please Try again !!');

                    }


            }else return \Redirect::to('/online-application/form')->withInput()->with('errormessage',"Check Applicant Information and Try Again !!");

        }else return \Redirect::to('/online-application/form');
    }


    /********************************************
    ## ApplicantInfoDownload
    *********************************************/

    public function ApplicantInfoDownload(){

        if(\Session::has('applicant_serial_no')){

            $applicant_serial_no = \Session::get('applicant_serial_no');

            \Session::forget('applicant_serial_no');

            $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

            $data['applicant_profile'] = $applicant_info;

            $pdf = \PDF::loadView('application.pdf.applicant-info',$data);
            return  $pdf->stream($applicant_serial_no.'.pdf'); 

        }else return \Redirect::to('/online-application/form');

    }

    

    /********************************************
    ## ApplicantInfoSerachPage 
    *********************************************/
    public function ApplicantInfoSerachPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('application.pages.applicant-serach-info',$data);
    }

    /********************************************
    ## ApplicantInfoSerachSubmit
    *********************************************/
    public function ApplicantInfoSerachSubmit($applicant_serial_no){
        
        $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

        $data['applicant_info'] = $applicant_info;
        if($applicant_info){
            if(($applicant_info->payment_status==1)&&($applicant_info->applicant_eligiblity==1)){
                 \Session::put('admit_card_serial_no',$applicant_serial_no);
            }
        }
       

        return \View::make('application.ajax.applicant-info',$data);
    }

    /********************************************
    ## ApplicantPaymentUpdate 
    *********************************************/
    public function ApplicantPaymentUpdate($applicant_serial_no,$payment_amount,$payment_slip_no,$bank_name){
        $now = date("Y-m-d H:i:s");
        $update_data = array(
                'applicant_fees_amount' =>$payment_amount,
                'payment_slip_no'=>$payment_slip_no,
                'payment_bank_name' =>$bank_name,
                'payment_status'=>2,
                'updated_at'=>$now,
                'updated_by' =>$applicant_serial_no,
            );


        try{

            $success = \DB::transaction(function () use ($update_data) {

                for($i=0; $i<count($this->dbList); $i++){

                    $update_payment = \DB::connection($this->dbList[$i])->table('applicant_basic')->where('applicant_serial_no', $applicant_serial_no)->update($update_data);
                    if(!$update_payment){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \DB::commit();
                    \App\System::EventLogWrite('update',json_encode($update_data));
                }else{
                    \DB::rollback();
                    throw new Exception("Error Processing Request", 1);
                }
            });
            
            return 1;

        }catch(\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
        }
        
    }

    /********************************************
    ## ApplicantAdmitSerachPage 
    *********************************************/
    public function ApplicantAdmitSerachPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('application.pages.applicant-result-search',$data);
    }

    /********************************************
    ## ApplicationAdmitCardShow
    *********************************************/

    public function ApplicationAdmitCardShow(){

        if(\Session::has('admit_card_serial_no')){

            $applicant_serial_no = \Session::get('admit_card_serial_no');

           $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

            $data['applicant_info'] = $applicant_info; 
            // return \View::make('application.pdf.application-admitcard',$data);

            \Session::forget('admit_card_serial_no');
            $pdf = \PDF::loadView('application.pdf.application-admitcard',$data);
            return $pdf->stream();

        }else return \Redirect::to('/online-application');
       
    }


    /********************************************
    ## ApplicantAdmissionResultSubmit
    *********************************************/
    public function ApplicantAdmissionResultSubmit($applicant_serial_no){
        
        $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)->where('applicant_basic.applicant_eligiblity','>',1)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

        $data['applicant_info'] = $applicant_info;

        return \View::make('application.ajax.applicant-result',$data);
    }

    /********************************************
    ## ApplicantEntry
    *********************************************/

    public function ApplicantEntryValidation($exam_type,$exam_roll_number,$program){

        $applicant_entry = \DB::table('applicant_academic')
                                ->where('applicant_academic.exam_type',$exam_type)->where('applicant_academic.exam_roll_number',$exam_roll_number)
                                ->leftJoin('applicant_basic', 'applicant_academic.applicant_tran_code','=','applicant_basic.applicant_tran_code')
                                ->select('applicant_basic.program')
                                ->first();
        if(!empty($applicant_entry)&&($program==$applicant_entry->program))
            return 1;
        else return 0;
  
    }

    /********************************************
    ## ApplicantPaymentSlipDownload
    *********************************************/

    public function ApplicantPaymentSlipDownload($applicant_serial_no){

        $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

        if(!empty($applicant_info)){

            if(($applicant_info->payment_status !=1)&&(empty($applicant_info->payment_slip_no))){
                  $data['applicant_info'] = $applicant_info;

                $pdf = \PDF::loadView('application.pdf.payment-slip',$data);
                return $pdf->stream();  

            }else return \Redirect::to('/online-application/applicant')->with('errormessage','Something wrong !!');

            

        }else return  \Redirect::to('/online-application/applicant')->with('errormessage','Something wrong !!');
    }


    /********************************************
    ## ApplicantAdmissionPaymentSlipDownload
    *********************************************/

    public function ApplicantAdmissionPaymentSlipDownload($applicant_serial_no){

        $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)->where('applicant_basic.applicant_eligiblity','>',1)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

        if(!empty($applicant_info)){
                $data['applicant_info'] =$applicant_info;
                $pdf = \PDF::loadView('application.pdf.admission-payment-slip',$data);
                return $pdf->stream();   

        }else return  \Redirect::to('/online-application/applicant/admission-result')->with('errormessage','Something wrong !!');
    }



    /********************************************
    ## RemoveSession
    *********************************************/

    public function RemoveSession(){
        
        \App\Applicant::ApplicantSessionRemove();
        return \Redirect::to('/online-application/form');

    }



    /********************************************
    ## Mail Templates
    *********************************************/

    // public function ApplicantConfirmationMail(){
    //     return \View::make('email.pages.application-confirmation-mail');
    // }

    // public function ApplicantAdmitCardMail(){
    //     return \View::make('email.pages.applicant-admit-card-mail');
    // }

    public function ForgetPasswordMail(){
        return \View::make('email.pages.forget-password-mail');
    }

    /*-----------------------end of Applicant Controller-------------------*/
}
