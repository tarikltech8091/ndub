<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\System;
use Carbon;

/*******************************
#
## Applicant Model
#
*******************************/

class Applicant extends Model
{

    /********************************************
	## NewsValidation
	*********************************************/

	public static function BasicFormValidation($Request){
			$rules = array(
		        'first_name' 	=> 'Required|max:15',
		        'last_name' 	=> 'Required|max:15',
		        'program' => 'Required|not_in:0',
		        //'management' => 'required_if:program,04,05,06',
		        'semester' => 'Required|not_in:0',
		        'academic_year' => 'Required',
		        'ssc_roll_valid' => 'Required|in:1',
		        'bank_name'	=> 'max:25|required_with:applicant_fees_amount,bank_slip_number',
		        'bank_slip_number' => 'numeric|required_with:bank_name,applicant_fees_amount|unique:applicant_basic,payment_slip_no',
		        'applicant_fees_amount' => 'integer|required_with:bank_name,bank_slip_number',
		        'image_url' =>'Required',
			);

		return \Validator::make($Request, $rules);	
	}


	/********************************************
	## PersonalFormValidation
	*********************************************/

	public static function PersonalFormValidation($Request){
			$rules = array(
		        'gender' 	=> 'Required',
		        'date_of_birth'	=> 'Required|date',
		        'blood_group' => '',
		        'marital' => 'Required',
		        'birth_city' => 'Required',
		        'birth_country'	=> 'Required',
		        'nationality' => 'Required',
		        'applicant_email' => 'Required|email',
		        'applicant_phone' =>'numeric',
		        'religion' =>'required',
		        'applicant_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11|unique:applicant_personal,mobile',

			);

		return \Validator::make($Request, $rules);	
	}

	/********************************************
	## ContactlFormValidation
	*********************************************/

	public static function ContactlFormValidation($Request){
			$rules = array(
		        'present_address_detail' 	=> 'Required',
		        'present_postal_code'	=> 'Required',
		        'present_city' => 'Required',
		        'present_country' => 'Required',
		        'permanent_address_detail' => 'Required',
		        'permanent_postal_code'	=> 'Required',
		        'permanent_city' => 'Required',
		        'permanent_country' => 'Required',
		        'father_name' =>'Required',
		        'father_occupation' =>'Required',
		        'father_contact_email' =>'email',
		        'father_contact_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11',
		        'mother_name' =>'Required',
		        'mother_occupation' =>'Required',
		        'mother_contact_email' =>'email',
		        'mother_contact_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11',

		       	'local_guardian_name' =>'Required',
		        'local_guardian_occupation' =>'Required',
		        'local_guardian_contact_email' =>'email',
		        'local_guardian_contact_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11',

		        'emergency_contact' => 'Required',

			);

		return \Validator::make($Request, $rules);	
	}

	/********************************************
	## ApplicationAcademicSubmit
	*********************************************/

	public static function ApplicationAcademicSubmit($Request){


		if(\Session::has('application_basic_form')){
			$application_basic_form = \Session::get('application_basic_form');
			$program_id = $application_basic_form['program'];

			$program_code = \App\Applicant::GetProgramCode($program_id);
		}

		if(!empty($program_code) && ($program_code->program_degree_code=='02') && ($program_code->program_id=='97')){
			$rules = array(
				'ssc_olevel_exam_type' 	=> 'Required',
				'ssc_olevel_group'	=> 'Required',
				'ssc_olevel_rollnumber' => 'Required|numeric',
				'ssc_olevel_board' => 'Required|not_in:0',
				'ssc_institute_name' => 'Required',
				'ssc_olevel_year' => 'Required|date_format:Y',

				'ssc_olevel_subject_1' => 'Required',
				'ssc_olevel_subject_2' => 'Required',
				'ssc_olevel_subject_3' => 'Required',
				'ssc_olevel_subject_4' => 'Required',
				'ssc_olevel_subject_5' => 'Required',

				'ssc_olevel_subject_gpa_1'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_2'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_3'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_4'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_5'	=> 'Required|numeric|max:5|min:1',
				'total_ssc_olevel_gpa' => 'Required|numeric|max:5|min:1',

				'ssc_olevel_subject_grade_1'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_2'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_3'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_4'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_5'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'hsc_alevel_exam_type' 	=> 'Required',
				'hsc_alevel_group'	=> 'Required',
				'hsc_alevel_rollnumber' => 'Required|numeric',
				'hsc_alevel_board' => 'Required|not_in:0',
				'hsc_institute_name' => 'Required',
				'hsc_alevel_year' => 'Required|date_format:Y',

				'hsc_alevel_subject_1' => 'Required',
				'hsc_alevel_subject_2' => 'Required',
				'hsc_alevel_subject_3' => 'Required',
				'hsc_alevel_subject_4' => 'Required',
				'hsc_alevel_subject_5' => 'Required',

				'hsc_alevel_subject_gpa_1' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_2' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_3' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_4' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_5' => 'Required|numeric|max:5|min:1',
				'total_hsc_alevel_gpa' => 'Required|numeric|max:5|min:1',

				'hsc_alevel_subject_grade_1' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_2' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_3' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_4' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_5' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'exam_type_for_mba' => 'required',
				'area_major' => '',
				'roll_number' => 'required|numeric',
				'university_college' => 'required',
				'passing_year' => 'required|date_format:Y',
				'cgpa' => 'required',

				'masters_subject' => '',
				'masters_university_college' => '',
				'masters_passing_year' => 'date_format:Y',
				'masters_grade_division' => '',

				'organization_1' => 'required',
				'position_held_1' => 'required',
				'period_from_1' => 'required | date',
				'period_to_1' => '',
				'total_year_1' => 'numeric',
				'total_months_1' => 'max:11',
				);

		}else if(!empty($program_code) && ($program_code->program_degree_code=='02') && ($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){
			$rules = array(
				'ssc_olevel_exam_type' 	=> 'Required',
				'ssc_olevel_group'	=> 'Required',
				'ssc_olevel_rollnumber' => 'Required|numeric',
				'ssc_olevel_board' => 'Required|not_in:0',
				'ssc_institute_name' => 'Required',
				'ssc_olevel_year' => 'Required|date_format:Y',

				'ssc_olevel_subject_1' => 'Required',
				'ssc_olevel_subject_2' => 'Required',
				'ssc_olevel_subject_3' => 'Required',
				'ssc_olevel_subject_4' => 'Required',
				'ssc_olevel_subject_5' => 'Required',

				'ssc_olevel_subject_gpa_1'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_2'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_3'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_4'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_5'	=> 'Required|numeric|max:5|min:1',
				'total_ssc_olevel_gpa' => 'Required|numeric|max:5|min:1',

				'ssc_olevel_subject_grade_1'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_2'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_3'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_4'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_5'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'hsc_alevel_exam_type' 	=> 'Required',
				'hsc_alevel_group'	=> 'Required',
				'hsc_alevel_rollnumber' => 'Required|numeric',
				'hsc_alevel_board' => 'Required|not_in:0',
				'hsc_institute_name' => 'Required',
				'hsc_alevel_year' => 'Required|date_format:Y',

				'hsc_alevel_subject_1' => 'Required',
				'hsc_alevel_subject_2' => 'Required',
				'hsc_alevel_subject_3' => 'Required',
				'hsc_alevel_subject_4' => 'Required',
				'hsc_alevel_subject_5' => 'Required',

				'hsc_alevel_subject_gpa_1' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_2' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_3' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_4' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_5' => 'Required|numeric|max:5|min:1',
				'total_hsc_alevel_gpa' => 'Required|numeric|max:5|min:1',

				'hsc_alevel_subject_grade_1' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_2' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_3' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_4' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_5' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'exam_type_for_mba' => 'required',
				'area_major' => '',
				'roll_number' => 'required|numeric',
				'university_college' => 'required',
				'passing_year' => 'required|date_format:Y',
				'cgpa' => 'required',

				'masters_subject' => '',
				'masters_university_college' => '',
				'masters_passing_year' => 'date_format:Y',
				'masters_grade_division' => '',
				);

		}else if(!empty($program_code) && ($program_code->program_degree_code=='02') && !($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){
			$rules = array(
				'ssc_olevel_exam_type' 	=> 'Required',
				'ssc_olevel_group'	=> 'Required',
				'ssc_olevel_rollnumber' => 'Required|numeric',
				'ssc_olevel_board' => 'Required|not_in:0',
				'ssc_institute_name' => 'Required',
				'ssc_olevel_year' => 'Required|date_format:Y',

				'ssc_olevel_subject_1' => 'Required',
				'ssc_olevel_subject_2' => 'Required',
				'ssc_olevel_subject_3' => 'Required',
				'ssc_olevel_subject_4' => 'Required',
				'ssc_olevel_subject_5' => 'Required',

				'ssc_olevel_subject_gpa_1'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_2'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_3'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_4'	=> 'Required|numeric|max:5|min:1',
				'ssc_olevel_subject_gpa_5'	=> 'Required|numeric|max:5|min:1',
				'total_ssc_olevel_gpa' => 'Required|numeric|max:5|min:1',

				'ssc_olevel_subject_grade_1'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_2'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_3'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_4'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'ssc_olevel_subject_grade_5'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'hsc_alevel_exam_type' 	=> 'Required',
				'hsc_alevel_group'	=> 'Required',
				'hsc_alevel_rollnumber' => 'Required|numeric',
				'hsc_alevel_board' => 'Required|not_in:0',
				'hsc_institute_name' => 'Required',
				'hsc_alevel_year' => 'Required|date_format:Y',

				'hsc_alevel_subject_1' => 'Required',
				'hsc_alevel_subject_2' => 'Required',
				'hsc_alevel_subject_3' => 'Required',
				'hsc_alevel_subject_4' => 'Required',
				'hsc_alevel_subject_5' => 'Required',

				'hsc_alevel_subject_gpa_1' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_2' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_3' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_4' => 'Required|numeric|max:5|min:1',
				'hsc_alevel_subject_gpa_5' => 'Required|numeric|max:5|min:1',
				'total_hsc_alevel_gpa' => 'Required|numeric|max:5|min:1',

				'hsc_alevel_subject_grade_1' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_2' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_3' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_4' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
				'hsc_alevel_subject_grade_5' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

				'hons_subject' => 'required',
				'hons_university_college' => 'required',
				'hons_passing_year' => 'required|date_format:Y',
				'hons_grade_division' => 'required',

				'masters_subject' => '',
				'masters_university_college' => '',
				'masters_passing_year' => 'date_format:Y',
				'masters_grade_division' => '',

				);
		}else
		$rules = array(
			'ssc_olevel_exam_type' 	=> 'Required',
			'ssc_olevel_group'	=> 'Required',
			'ssc_olevel_rollnumber' => 'Required|numeric',
			'ssc_olevel_board' => 'Required|not_in:0',
			'ssc_institute_name' => 'Required',
			'ssc_olevel_year' => 'Required|date_format:Y',

			'ssc_olevel_subject_1' => 'Required',
			'ssc_olevel_subject_2' => 'Required',
			'ssc_olevel_subject_3' => 'Required',
			'ssc_olevel_subject_4' => 'Required',
			'ssc_olevel_subject_5' => 'Required',

			'ssc_olevel_subject_gpa_1'	=> 'Required|numeric|max:5|min:1',
			'ssc_olevel_subject_gpa_2'	=> 'Required|numeric|max:5|min:1',
			'ssc_olevel_subject_gpa_3'	=> 'Required|numeric|max:5|min:1',
			'ssc_olevel_subject_gpa_4'	=> 'Required|numeric|max:5|min:1',
			'ssc_olevel_subject_gpa_5'	=> 'Required|numeric|max:5|min:1',
			'total_ssc_olevel_gpa' => 'Required|numeric|max:5|min:1',

			'ssc_olevel_subject_grade_1'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'ssc_olevel_subject_grade_2'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'ssc_olevel_subject_grade_3'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'ssc_olevel_subject_grade_4'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'ssc_olevel_subject_grade_5'	=> 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

			'hsc_alevel_exam_type' 	=> 'Required',
			'hsc_alevel_group'	=> 'Required',
			'hsc_alevel_rollnumber' => 'Required|numeric',
			'hsc_alevel_board' => 'Required|not_in:0',
			'hsc_institute_name' => 'Required',
			'hsc_alevel_year' => 'Required|date_format:Y',

			'hsc_alevel_subject_1' => 'Required',
			'hsc_alevel_subject_2' => 'Required',
			'hsc_alevel_subject_3' => 'Required',
			'hsc_alevel_subject_4' => 'Required',
			'hsc_alevel_subject_5' => 'Required',

			'hsc_alevel_subject_gpa_1' => 'Required|numeric|max:5|min:1',
			'hsc_alevel_subject_gpa_2' => 'Required|numeric|max:5|min:1',
			'hsc_alevel_subject_gpa_3' => 'Required|numeric|max:5|min:1',
			'hsc_alevel_subject_gpa_4' => 'Required|numeric|max:5|min:1',
			'hsc_alevel_subject_gpa_5' => 'Required|numeric|max:5|min:1',
			'total_hsc_alevel_gpa' => 'Required|numeric|max:5|min:1',

			'hsc_alevel_subject_grade_1' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'hsc_alevel_subject_grade_2' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'hsc_alevel_subject_grade_3' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'hsc_alevel_subject_grade_4' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',
			'hsc_alevel_subject_grade_5' => 'Required|in:A+,a+,A,a,A-,a-,B,b,C,c,D,d,E,e,A*,a*,G,g',

			);

		return \Validator::make($Request, $rules);	

	}


	/********************************************
    ## CurrectImageSize
    *********************************************/
    public static function CurrectImageSize($maxwidth,$maxheight,$current){

        list($width, $height) = getimagesize($current);

       return ( ($width <= $maxwidth) && ($height <= $maxheight) );
    }


    /********************************************
    ## ApplicationInsert
    *********************************************/

    public static function ApplicationInsert(){

    	if(\Session::has('applicant_form')){

    		$now = date('Y-m-d H:i:s');
    		$db_name = \App\System::DatabaseName();

    		$applicant_form= \Session::get('applicant_form');

	    		/*---------applicant basic table insert----------------*/

	    		if(\Session::has('application_basic_form') && ($applicant_form['step']==5)){

	    			$application_basic_form = \Session::get('application_basic_form');
	    			$uuid = \Uuid::generate(4);

	    			#Applicant Serial generate
	    			$applicant_year = substr($application_basic_form['academic_year'],-2);
	    			$applicant_program = str_pad($application_basic_form['program'], 2,0,STR_PAD_LEFT);

	    			#Applicant last Count by department
	    			$applicant_random_no = \App\Applicant::ApplicantRandomNo();

	    			$applicant_serial_no = 'A'.$applicant_year.$application_basic_form['semester'].$applicant_program.$applicant_random_no;


	    			#pro image move
	    			$app_image_url = \App\Applicant::ApplicationImageMove($application_basic_form['image_url'],$applicant_serial_no);

	    			if(!empty($application_basic_form['bank_slip_number']))
	    				$payment_status = 2;  //waiting for approval
	    			else $payment_status = 0; //not paid

	    			

	    			$basic_form_data = array(
	    					'applicant_tran_code' =>$uuid->string,
	    					'applicant_serial_no' =>$applicant_serial_no,
	    					'first_name'=> $application_basic_form['first_name'],
	    					'middle_name'=> $application_basic_form['middle_name'],
	    					'last_name'=> $application_basic_form['last_name'],
	    					'program' =>$application_basic_form['program'],
	    					'semester' =>$application_basic_form['semester'],
	    					'academic_year' =>$application_basic_form['academic_year'],
	    					'app_image_url' =>$app_image_url,
	    					'applicant_fees_amount' =>$application_basic_form['applicant_fees_amount'],
	    					'payment_by' =>'',
	    					'payment_slip_no' =>$application_basic_form['bank_slip_number'],
	    					'payment_bank_name' =>$application_basic_form['bank_name'],
	    					'applicant_eligiblity' =>'',
	    					'payment_status' =>$payment_status,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$applicant_serial_no,
	    					'updated_by' =>$applicant_serial_no,
	    				);


	    			try{


			            $success = \DB::transaction(function () use ($basic_form_data, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

								$basic_table_insert = \DB::connection($db_name[$i])->table('applicant_basic')->insert($basic_form_data);

			                    if(!$basic_table_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_basic',json_encode($basic_form_data));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    			// if(!$basic_table_insert)
	    			// 	return false;

	    			
	    		}else return false;
	    		
	    		/*----------------end applicant basic----------------------------------*/
	    	
				$basic_last_row = \App\Applicant::GetLastRow('applicant_basic');

	    	
	    		/*----------------applicant personal----------------------------------*/
	    		if(\Session::has('application_personl_form') && (!empty($basic_last_row))){
	    			$application_personl_form = \Session::get('application_personl_form');
	    			$uuid = \Uuid::generate(4);
	    			$personal_form_data = array(
	    				'applicant_personal_tran_code' =>$uuid->string,
	    				'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    				'gender' =>$application_personl_form['gender'],
	    				'date_of_birth' =>$application_personl_form['date_of_birth'],
	    				'blood_group'=>$application_personl_form['blood_group'],
	    				'place_of_birth' =>$application_personl_form['birth_city'].','.$application_personl_form['birth_country'],
	    				'marital_status' =>$application_personl_form['marital'],
	    				'nationality' =>$application_personl_form['nationality'],
	    				'email' =>$application_personl_form['applicant_email'], 
	    				'phone' =>$application_personl_form['applicant_phone'],
	    				'mobile' =>$application_personl_form['applicant_mobile'],
	    				'religion' =>$application_personl_form['religion'],
	    				'created_at' =>$now,
	    				'updated_at' =>$now,
	    				'created_by' =>$basic_last_row->applicant_serial_no,
	    				'updated_by' =>$basic_last_row->applicant_serial_no,
	    			);


	    			try{


			            $success = \DB::transaction(function () use ($personal_form_data, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

								$personal_table_insert = \DB::connection($db_name[$i])->table('applicant_personal')->insert($personal_form_data);

			                    if(!$personal_table_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_personal',json_encode($personal_form_data));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    			

	    		}else return false;
	    		/*----------------end applicant personal----------------------------------*/

	    		/*----------------applicant contact----------------------------------*/
	    		if(\Session::has('application_contact_form') && (!empty($basic_last_row))){

	    			$application_contact_form = \Session::get('application_contact_form');

	    			$uuid = \Uuid::generate(4);
	    			$contact_peresent = array(
	    					'applicant_contacts_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'contact_type' => 'present',
	    					'contact_detail' => $application_contact_form['present_address_detail'],
	    					'postal_code' =>$application_contact_form['present_postal_code'],
	    					'city' =>$application_contact_form['present_city'],
	    					'country' =>$application_contact_form['present_country'],
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    				    'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{


			            $success = \DB::transaction(function () use ($contact_peresent, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

								$contact_peresent_insert = \DB::connection($db_name[$i])->table('applicant_contacts')->insert($contact_peresent);
								
			                    if(!$contact_peresent_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_contacts',json_encode($contact_peresent));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}



	    			$uuid = \Uuid::generate(4);
	    			$contact_permanent = array(
	    					'applicant_contacts_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'contact_type' => 'permanent',
	    					'contact_detail' => $application_contact_form['permanent_address_detail'],
	    					'postal_code' =>$application_contact_form['permanent_postal_code'],
	    					'city' =>$application_contact_form['permanent_city'],
	    					'country' =>$application_contact_form['permanent_country'],
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{


			            $success = \DB::transaction(function () use ($contact_permanent, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

								$contact_permanent_insert = \DB::connection($db_name[$i])->table('applicant_contacts')->insert($contact_permanent);

			                    if(!$contact_permanent_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_contacts',json_encode($contact_permanent));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}

	    				

	    			if($application_contact_form['emergency_contact']=='Father'){

	    				$father_emergency='yes';
	    				$mother_emergency='no';
	    				$local_guardian_emergency='no';
	    			}
	    			if($application_contact_form['emergency_contact']=='Mother'){

	    				$father_emergency='no';
	    				$mother_emergency='yes';
	    				$local_guardian_emergency='no';

	    			}
	    			if($application_contact_form['emergency_contact']=='Local_Guardian'){

	    				$father_emergency='no';
	    				$mother_emergency='no';
	    				$local_guardian_emergency='yes';
	    				
	    			}

	    			$uuid = \Uuid::generate(4);
	    			$gurdian_father = array(
	    					'applicant_gurdians_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'relation' => 'Father',
	    					'gurdian_name' => $application_contact_form['father_name'],
	    					'occupation' =>$application_contact_form['father_occupation'],
	    					'mobile' =>$application_contact_form['father_contact_mobile'],
	    					'email' =>$application_contact_form['father_contact_email'],
	    					'emergency_contact' =>$father_emergency,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    				 	'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{


			            $success = \DB::transaction(function () use ($gurdian_father, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$gurdian_father_insert = \DB::connection($db_name[$i])->table('applicant_gurdians')->insert($gurdian_father);
								
			                    if(!$gurdian_father_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_gurdians',json_encode($gurdian_father));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    				


	    			$uuid = \Uuid::generate(4);
	    			$gurdian_mother = array(
	    					'applicant_gurdians_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'relation' => 'Mother',
	    					'gurdian_name' => $application_contact_form['mother_name'],
	    					'occupation' =>$application_contact_form['mother_occupation'],
	    					'mobile' =>$application_contact_form['mother_contact_mobile'],
	    					'email' =>$application_contact_form['mother_contact_email'],
	    					'emergency_contact' =>$mother_emergency,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{



			            $success = \DB::transaction(function () use ($gurdian_mother, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$gurdian_mother_insert = \DB::connection($db_name[$i])->table('applicant_gurdians')->insert($gurdian_mother);
								
			                    if(!$gurdian_mother_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_gurdians',json_encode($gurdian_mother));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}




	    			$uuid = \Uuid::generate(4);
	    			$local_guardian= array(
	    					'applicant_gurdians_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'relation' => 'Local_Guardian',
	    					'gurdian_name' => $application_contact_form['local_guardian_name'],
	    					'occupation' =>$application_contact_form['local_guardian_occupation'],
	    					'mobile' =>$application_contact_form['local_guardian_contact_mobile'],
	    					'email' =>$application_contact_form['local_guardian_contact_email'],
	    					'emergency_contact' =>$local_guardian_emergency,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{

			            $success = \DB::transaction(function () use ($local_guardian, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$local_guardian_insert = \DB::connection($db_name[$i])->table('applicant_gurdians')->insert($local_guardian);
								
			                    if(!$local_guardian_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_gurdians',json_encode($local_guardian));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}




	    		}else return false;

	    		/*----------------applicant contact----------------------------------*/
	    		/*----------------applicant academic----------------------------------*/
	    		if(\Session::has('application_academic_form') && (!empty($basic_last_row))){

	    			$application_academic_form = \Session::get('application_academic_form');
	    			$ssc_count=$application_academic_form['multi_ssc_subject_count'];
	    			$ssc_count_ajax=$application_academic_form['multi_ssc_subject_count_ajax'];
	    			$hsc_count=$application_academic_form['multi_hsc_subject_count'];
	    			$hsc_count_ajax=$application_academic_form['multi_hsc_subject_count_ajax'];
	    			if(!empty($ssc_count_ajax)){
	    				for($i=1;$i<=$ssc_count_ajax;$i++){

	    					$subject= array(
	    						'subject_name' =>$application_academic_form['ssc_olevel_subject_'.$i],
	    						'point' =>$application_academic_form['ssc_olevel_subject_gpa_'.$i],
	    						'grade' =>$application_academic_form['ssc_olevel_subject_grade_'.$i],
	    						);

	    					$ssc_subject_detail[] = $subject;
	    				}
	    				$ssc_subject_detail = serialize($ssc_subject_detail);
	    			}
	    			else{
	    				for($i=1;$i<=$ssc_count;$i++){
	    					$subject= array(
	    						'subject_name' =>$application_academic_form['ssc_olevel_subject_'.$i],
	    						'point' =>$application_academic_form['ssc_olevel_subject_gpa_'.$i],
	    						'grade' =>$application_academic_form['ssc_olevel_subject_grade_'.$i],
	    						);
	    					$ssc_subject_detail[] = $subject;
	    				}
	    				$ssc_subject_detail = serialize($ssc_subject_detail);
	    			}

	    		
	    			$uuid = \Uuid::generate(4);
	    			$academic_ssc_data = array(
	    					'applicant_academic_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'exam_type' => $application_academic_form['ssc_olevel_exam_type'],
	    					'exam_group' => $application_academic_form['ssc_olevel_group'],
	    					'exam_board' => $application_academic_form['ssc_olevel_board'],
	    					'institute_name' => $application_academic_form['ssc_institute_name'],
	    					'result_type' =>'passed',
	    					'exam_roll_number' =>$application_academic_form['ssc_olevel_rollnumber'],
	    					'passing_year' =>$application_academic_form['ssc_olevel_year'],
	    					'result_gpa' =>$application_academic_form['total_ssc_olevel_gpa'],
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    				 	'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{

	    				$success = \DB::transaction(function () use ($academic_ssc_data, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$academic_ssc_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($academic_ssc_data);
								
			                    if(!$academic_ssc_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($academic_ssc_data));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    			

	    				

	    			/*----------------result detail insert--------------------*/
	    			$applicant_academic_tran_code = $uuid->string;

	    			$uuid = \Uuid::generate(4);
	    			$academic_ssc_detail = array(
	    					'applicant_academic_result_tran_code' =>$uuid->string,
	    					'applicant_academic_tran_code' =>$applicant_academic_tran_code,
	    					'exam_name' =>$application_academic_form['ssc_olevel_exam_type'],
	    					'academic_detail' =>$ssc_subject_detail,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,

	    				);

	    			try{

	    				$success = \DB::transaction(function () use ($academic_ssc_detail, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$academic_ssc_detail_insert = \DB::connection($db_name[$i])->table('applicant_academic_result_detail')->insert($academic_ssc_detail);
								
			                    if(!$academic_ssc_detail_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_academic_result_detail',json_encode($academic_ssc_detail));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}

	    				

	    			/*----------------result detail insert--------------------*/

	    			if(!empty($hsc_count_ajax)){
	    				for($i=1;$i<=$hsc_count_ajax;$i++){

	    					$subject= array(
	    						'subject_name' =>$application_academic_form['hsc_alevel_subject_'.$i],
	    						'point' =>$application_academic_form['hsc_alevel_subject_gpa_'.$i],
	    						'grade' =>$application_academic_form['hsc_alevel_subject_grade_'.$i],
	    						);
	    					$hsc_subject_detail[]=$subject;
	    				}

	    				$hsc_subject_detail = serialize($hsc_subject_detail);
	    			}
	    			else{
	    				for($i=1;$i<=$hsc_count;$i++){

	    					$subject= array(
	    						'subject_name' =>$application_academic_form['hsc_alevel_subject_'.$i],
	    						'point' =>$application_academic_form['hsc_alevel_subject_gpa_'.$i],
	    						'grade' =>$application_academic_form['hsc_alevel_subject_grade_'.$i],
	    						);
	    					$hsc_subject_detail[]=$subject;
	    				}

	    				$hsc_subject_detail = serialize($hsc_subject_detail);
	    			}
	    			

	    			$uuid = \Uuid::generate(4);
	    			$academic_hsc_data = array(
	    					'applicant_academic_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'exam_type' => $application_academic_form['hsc_alevel_exam_type'],
	    					'exam_group' => $application_academic_form['hsc_alevel_group'],
	    					'exam_board' => $application_academic_form['hsc_alevel_board'],
	    					'institute_name' => $application_academic_form['hsc_institute_name'],
	    					'result_type' =>'passed',
	    					'exam_roll_number' =>$application_academic_form['hsc_alevel_rollnumber'],
	    					'passing_year' =>$application_academic_form['hsc_alevel_year'],
	    					'result_gpa' =>$application_academic_form['total_hsc_alevel_gpa'],
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);

	    			try{

	    				$success = \DB::transaction(function () use ($academic_hsc_data, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$academic_hsc_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($academic_hsc_data);
								
			                    if(!$academic_hsc_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($academic_hsc_data));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    			

	    				

	    			/*----------------result detail insert--------------------*/
	    			$applicant_academic_tran_code = $uuid->string;

	    			$uuid = \Uuid::generate(4);
	    			$academic_hsc_detail = array(
	    					'applicant_academic_result_tran_code' =>$uuid->string,
	    					'applicant_academic_tran_code' =>$applicant_academic_tran_code,
	    					'exam_name' =>$application_academic_form['hsc_alevel_exam_type'],
	    					'academic_detail' =>$hsc_subject_detail,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    				);


	    			try{

	    				$success = \DB::transaction(function () use ($academic_hsc_detail, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$academic_hsc_detail_insert = \DB::connection($db_name[$i])->table('applicant_academic_result_detail')->insert($academic_hsc_detail);

								
			                    if(!$academic_hsc_detail_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_academic_result_detail',json_encode($academic_hsc_detail));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });


	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;
	    			}
	    			
	    				

	    			/*----------------result detail insert--------------------*/


	    			/*--------------------academic for MBA student-------------------------*/
	    			if(\Session::has('application_basic_form')){
	    				$application_basic_form = \Session::get('application_basic_form');
	    				$program_id = $application_basic_form['program'];

	    				$program_code = \App\Applicant::GetProgramCode($program_id);
	    			}

	    			if(($program_code->program_degree_code=='02') && !($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){
	    				$hons_uuid = \Uuid::generate(4);
	    				$hons_data = array(
	    					'applicant_academic_tran_code' =>$hons_uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'exam_type' => $application_academic_form['hons_qualification'],
	    					'exam_group' => $application_academic_form['hons_subject'],
	    					'exam_board' => $application_academic_form['hons_university_college'],
							'institute_name' => $application_academic_form['hons_university_college'],
	    					'result_type' =>'passed',
	    					'passing_year' => $application_academic_form['hons_passing_year'],
	    					'result_gpa' => $application_academic_form['hons_grade_division'],
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    					);


	    				try{

		    				$success = \DB::transaction(function () use ($hons_data, $db_name) {

				                for($i=0; $i<count($db_name); $i++){
				                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    							$hons_data_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($hons_data);

									
				                    if(!$hons_data_insert){
				                        $error=1;
				                    }
				                }

				                if(!isset($error)){
				                    \App\System::TransactionCommit();
				                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($hons_data));
				                }else{
				                    \App\System::TransactionRollback();
				                    throw new Exception("Error Processing Request", 1);
				                }
				            });

	    				}catch(\Exception $e){
	    					$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    					\App\System::ErrorLogWrite($message);
	    					return false;

	    				}
	    				

	    				// if(!$hons_data_insert)
	    				// 	return false;


		    			$uuid = \Uuid::generate(4);
		    			$masters_data = array(
		    					'applicant_academic_tran_code' =>$uuid->string,
		    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
		    					'exam_type' => $application_academic_form['masters_qualification'],
		    					'exam_group' => $application_academic_form['masters_subject'],
		    					'exam_board' => $application_academic_form['masters_university_college'],
								'institute_name' => $application_academic_form['masters_university_college'],
		    					'result_type' =>'passed',
		    					'passing_year' => $application_academic_form['masters_passing_year'],
		    					'result_gpa' => $application_academic_form['masters_grade_division'],
		    					'created_at' =>$now,
		    					'updated_at' =>$now,
		    					'created_by' =>$basic_last_row->applicant_serial_no,
		    					'updated_by' =>$basic_last_row->applicant_serial_no,
		    				);


	    			try{

	    				$success = \DB::transaction(function () use ($masters_data, $db_name) {

			                for($i=0; $i<count($db_name); $i++){
			                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    						$masters_data_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($masters_data);

			                    if(!$masters_data_insert){
			                        $error=1;
			                    }
			                }

			                if(!isset($error)){
			                    \App\System::TransactionCommit();
			                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($masters_data));
			                }else{
			                    \App\System::TransactionRollback();
			                    throw new Exception("Error Processing Request", 1);
			                }
			            });

	    			}catch(\Exception $e){
	    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    				\App\System::ErrorLogWrite($message);
	    				return false;

	    			}

	    			
	    			// if(!$masters_data_insert)
	    			// 	return false;
	    		}


	    		if(($program_code->program_degree_code=='02') && ($program_code->program_id=='97' || $program_code->program_id=='98' || $program_code->program_id=='99')){

		    			$uuid = \Uuid::generate(4);
		    			$academic_data_for_mba = array(
		    					'applicant_academic_tran_code' =>$uuid->string,
		    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
		    					'exam_type' => $application_academic_form['exam_type_for_mba'],
		    					'exam_group' => $application_academic_form['area_major'],
		    					'exam_board' => $application_academic_form['university_college'],
								'institute_name' => $application_academic_form['university_college'],
		    					'result_type' =>'passed',
		    					'exam_roll_number' => $application_academic_form['roll_number'],
		    					'passing_year' => $application_academic_form['passing_year'],
		    					'result_gpa' => $application_academic_form['cgpa'],
		    					'created_at' =>$now,
		    					'updated_at' =>$now,
		    					'created_by' =>$basic_last_row->applicant_serial_no,
		    					'updated_by' =>$basic_last_row->applicant_serial_no,
		    			);

		    			try{


		    				$success = \DB::transaction(function () use ($academic_data_for_mba, $db_name) {

				                for($i=0; $i<count($db_name); $i++){
				                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

		    						$academic_data_for_mba_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($academic_data_for_mba);

				                    if(!$academic_data_for_mba_insert){
				                        $error=1;
				                    }
				                }

				                if(!isset($error)){
				                    \App\System::TransactionCommit();
				                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($academic_data_for_mba));
				                }else{
				                    \App\System::TransactionRollback();
				                    throw new Exception("Error Processing Request", 1);
				                }
				            });


		    			}catch(\Exception $e){
		    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
		    				\App\System::ErrorLogWrite($message);
	    					return false;

		    			}
		    			

	    			// if(!$academic_data_for_mba_insert)
	    			// 	return false;


			    			$masters_uuid = \Uuid::generate(4);
			    			$masters_data = array(
			    					'applicant_academic_tran_code' =>$masters_uuid->string,
			    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
			    					'exam_type' => $application_academic_form['masters_qualification'],
			    					'exam_group' => $application_academic_form['masters_subject'],
			    					'exam_board' => $application_academic_form['masters_university_college'],
									'institute_name' => $application_academic_form['masters_university_college'],
			    					'result_type' =>'passed',
			    					'passing_year' => $application_academic_form['masters_passing_year'],
			    					'result_gpa' => $application_academic_form['masters_grade_division'],
			    					'created_at' =>$now,
			    					'updated_at' =>$now,
			    					'created_by' =>$basic_last_row->applicant_serial_no,
			    					'updated_by' =>$basic_last_row->applicant_serial_no,
			    				);


			    			try{

			    				$success = \DB::transaction(function () use ($masters_data, $db_name) {

					                for($i=0; $i<count($db_name); $i++){
					                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

			    						$masters_data_insert = \DB::connection($db_name[$i])->table('applicant_academic')->insert($masters_data);

					                    if(!$masters_data_insert){
					                        $error=1;
					                    }
					                }

					                if(!isset($error)){
					                    \App\System::TransactionCommit();
					                    \App\System::EventLogWrite('insert,applicant_academic',json_encode($masters_data));
					                }else{
					                    \App\System::TransactionRollback();
					                    throw new Exception("Error Processing Request", 1);
					                }
					            });


			    			}catch(\Exception $e){
			    				$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
			    				\App\System::ErrorLogWrite($message);
	    						return false;

			    			}
	    			

	    			// if(!$masters_data_insert)
	    			// 	return false;

	    		}


		    		if(($program_code->program_degree_code=='02') && ($program_code->program_id=='97')){
		    			$applicant_pro_experience = \Session::get('applicant_pro_experience');
		    			$count=$applicant_pro_experience['count'];

		    			for($i=1; $i<=$count;$i++){
		    				$uuid = \Uuid::generate(4);
		    				$applicant_pro_experience_data = array(
		    					'applicant_experience_tran_code' =>$uuid->string,
		    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
		    					'organization' => $applicant_pro_experience['organization_'.$i],
		    					'position_held' => $applicant_pro_experience['position_held_'.$i],
		    					'period_from' => $applicant_pro_experience['period_from_'.$i],
		    					'period_to' => $applicant_pro_experience['period_to_'.$i],
		    					'total_year' => $applicant_pro_experience['total_year_'.$i],
		    					'total_months' => $applicant_pro_experience['total_months_'.$i],
		    					'created_at' =>$now,
		    					'updated_at' =>$now,
		    					'created_by' =>$basic_last_row->applicant_serial_no,
		    					'updated_by' =>$basic_last_row->applicant_serial_no,
		    					);

		    				try{


			    				$success = \DB::transaction(function () use ($applicant_pro_experience_data, $db_name) {

					                for($i=0; $i<count($db_name); $i++){
					                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

		    							$applicant_pro_experience_data_insert = \DB::connection($db_name[$i])->table('applicant_pro_experience')->insert($applicant_pro_experience_data);

					                    if(!$applicant_pro_experience_data_insert){
					                        $error=1;
					                    }
					                }

					                if(!isset($error)){
					                    \App\System::TransactionCommit();
					                    \App\System::EventLogWrite('insert,applicant_pro_experience',json_encode($applicant_pro_experience_data));
					                }else{
					                    \App\System::TransactionRollback();
					                    throw new Exception("Error Processing Request", 1);
					                }
					            });



		    				}catch(\Exception $e){
		    					$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
		    					\App\System::ErrorLogWrite($message);
		    					return false;

		    				}

		    			}


		    			// if(!$applicant_pro_experience_data_insert)
		    			// 	return false;
		    			
		    		}


	    			/*------------major details---------------*/
	    			if(\Session::has('application_graduate_major') && (!empty($basic_last_row))){
	    				$application_graduate_major = \Session::get('application_graduate_major');
	    				for($i=1;$i<=7;$i++){
	    					$major= array(
	    						'major_program' =>$application_graduate_major['major_subject_'.$i],
	    						'priority' =>$application_graduate_major['major_subject_priority_'.$i],
	    						);
	    					$major_detail[]=$major;
	    				}

	    				$major_detail = serialize($major_detail);
	    				$uuid = \Uuid::generate(4);
	    				$graduate_major_data = array(
	    					'applicant_graduate_major_tran_code' =>$uuid->string,
	    					'applicant_tran_code' =>$basic_last_row->applicant_tran_code,
	    					'major_applied_program' =>$application_graduate_major['program'],
	    					'major_subject_priority' =>$major_detail,
	    					'created_at' =>$now,
	    					'updated_at' =>$now,
	    					'created_by' =>$basic_last_row->applicant_serial_no,
	    					'updated_by' =>$basic_last_row->applicant_serial_no,
	    					);


	    				try{

		    				$success = \DB::transaction(function () use ($graduate_major_data, $db_name) {

				                for($i=0; $i<count($db_name); $i++){
				                	$save_transaction=\DB::connection($db_name[$i])->beginTransaction();

	    							$graduate_major_table_insert = \DB::connection($db_name[$i])->table('applicant_graduate_major_detail')->insert($graduate_major_data);

				                    if(!$graduate_major_table_insert){
				                        $error=1;
				                    }
				                }

				                if(!isset($error)){
				                    \App\System::TransactionCommit();
				                    \App\System::EventLogWrite('insert,applicant_graduate_major_detail',json_encode($graduate_major_data));
				                }else{
				                    \App\System::TransactionRollback();
				                    throw new Exception("Error Processing Request", 1);
				                }
				            });


	    				}catch(\Exception $e){
	    					$message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
	    					\App\System::ErrorLogWrite($message);
	    					return false;
	    					
	    				}
	    				

	    				// if(!$graduate_major_table_insert)
	    				// 	return false;
	    			}else return $basic_last_row;

	    			/*------------end major details---------------*/
	    			
	    			/*--------------------end academic for MBA student-------------------------*/

	    		}else return false;

	    	/*----------------applicant academic----------------------------------*/

			

	    	return $basic_last_row;



    	}else return false;

    	
    }


    /********************************************
    ## GetLastRow
    *********************************************/
    public static function GetLastRow($table_name){

    	$lastInsertedRow = \DB::table($table_name)->latest()->first();
    	return $lastInsertedRow;
    }

    /********************************************
    ## ApplicationImageMove
    *********************************************/
    public static function ApplicationImageMove($image_url,$applicant_serial_no){

	   /*directory create*/
		if (!file_exists('application/profile/'))
		   mkdir('application/profile/', 0777, true);

		$file_type = explode('.', $image_url);

		$moved_filename_location = 'application/profile/'.$applicant_serial_no.'.'.$file_type[1];
		//copy file with rename and delete
		copy($image_url, $moved_filename_location);
		//unlink($image_url);
	  
	  return asset($moved_filename_location);
    }

    /********************************************
    ## ApplicationProfileInfo
    *********************************************/

    public static function ApplicationProfileInfo($applicant_tran_code){

		  $applicant_info = \DB::table('applicant_basic')->where('applicant_basic.applicant_tran_code','like',$applicant_tran_code)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.program_title','univ_semester.semester_title')
            ->first();

		return $applicant_info;
    }

    /********************************************
    ## ApplicantSessionRemove
    *********************************************/

    public static function ApplicantSessionRemove(){

	/*--------all session forget--------------------*/
    		\Session::forget('applicant_form');
    		\Session::forget('application_basic_form');
    		\Session::forget('application_personl_form');
    		\Session::forget('application_contact_form');
    		\Session::forget('application_academic_form');
    	/*--------all session forget--------------------*/

    	return true;
    }

    /********************************************
    ## ApplicantRandomNo
    *********************************************/

    public static function ApplicantRandomNo(){

    	$applicant_random_no_generate = rand(1111111,9999999);
    	if(!empty($applicant_random_no_generate)){

    		#7 digit random number
    		$applicant_random_no = (int)$applicant_random_no_generate; 

    	}else $applicant_random_no=0;

    	return $applicant_random_no;
    }


    /********************************************
    ## ApplicantEntry
    *********************************************/

    public static function ApplicantEntry($exam_type,$exam_roll_number,$program){

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
    ## ProgramList
    *********************************************/

    public static function ProgramList(){

    	$program_list = \DB::table('univ_program')->orderBy('program_title','asc')->get();		
    	return $program_list;
    }

    /********************************************
    ## MSProgramList
    *********************************************/

    public static function MSProgramList(){

    	$program_list = \DB::table('univ_program')->select('program_id')->where('program_degree_code','02')->get();		
    	return $program_list;
    }

    /********************************************
    ## ApplicantBankSlipCheck
    *********************************************/

    public static function ApplicantBankSlipCheck($payment_slip_no){

    	$slip_count = \DB::table('applicant_basic')->where('payment_slip_no', $payment_slip_no)->count();		
    	return $slip_count;
    }


    /********************************************
    ## ApplicantBasicInfo
    *********************************************/

    public static function ApplicantBasicInfo($applicant_serial_no){

    	$applicant_info_basic = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_personal', 'applicant_basic.applicant_tran_code','like','applicant_personal.applicant_tran_code')
            ->leftJoin('univ_program','applicant_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_semester','applicant_basic.semester','like','univ_semester.semester_code')
            ->select('applicant_basic.*','applicant_personal.*','univ_program.*','univ_semester.*')
            ->first();

       return $applicant_info_basic;
    }


    /********************************************
    ## GetProgramCode
    *********************************************/

    public static function GetProgramCode($program_id){

    	$program_list = \DB::table('univ_program')->where('program_id',$program_id)->first();		
    	return $program_list;
    }

    

}
