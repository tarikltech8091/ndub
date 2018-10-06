<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

/*******************************
#
## Register Model
#
*******************************/
class Register extends Model
{
    /********************************************
    ## ApplicantContactInfo
    *********************************************/
    public static function ApplicantContactInfo($applicant_serial_no){

        $applicant_contact = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_contacts', 'applicant_basic.applicant_tran_code','like','applicant_contacts.applicant_tran_code')
            ->select('applicant_basic.*','applicant_contacts.*')
            ->orderBy('applicant_contacts.created_at','desc')
            ->get();

        return $applicant_contact;
    }

    /********************************************
    ## ApplicantGurdianInfo
    *********************************************/
    public static function ApplicantGurdianInfo($applicant_serial_no){

        $applicant_guardians = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_gurdians', 'applicant_basic.applicant_tran_code','like','applicant_gurdians.applicant_tran_code')
            ->select('applicant_basic.*','applicant_gurdians.*')
            ->orderBy('applicant_gurdians.created_at','desc')
            ->get();

        return $applicant_guardians;
    }

    /********************************************
    ## ApplicantAcademicDetail
    *********************************************/
    public static function ApplicantAcademicDetail($applicant_serial_no){

        $applicant_academic = \DB::table('applicant_basic')->where('applicant_basic.applicant_serial_no','like',$applicant_serial_no)
            ->leftJoin('applicant_academic', 'applicant_basic.applicant_tran_code','like','applicant_academic.applicant_tran_code')
            ->leftJoin('applicant_academic_result_detail', 'applicant_academic.applicant_academic_tran_code','like','applicant_academic_result_detail.applicant_academic_tran_code')
            ->select('applicant_basic.*','applicant_academic.*','applicant_academic_result_detail.*')
            ->orderBy('applicant_academic.created_at','asc')
            ->get();

        return $applicant_academic;
    }

    /********************************************
    ## PersonalFormValidation
    *********************************************/

    public static function StudentAdmissionValidation($Request){
            $rules = array(
                'first_name'    => 'Required|max:15',
                'last_name'     => 'Required|max:15',
                'birth_city' => 'Required',
                'birth_country' => 'Required',
                'batch' => 'Required',
                'applicant_email' => 'Required|email',
                'applicant_phone' =>'numeric',
                'applicant_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'present_address_detail'    => 'Required',
                'present_postal_code'   => 'Required',
                'present_city' => 'Required',
                'present_country' => 'Required',
                'permanent_address_detail' => 'Required',
                'permanent_postal_code' => 'Required',
                'permanent_city' => 'Required',
                'permanent_country' => 'Required',
                'father_occupation' =>'Required',
                'father_contact_email' =>'email',
                'father_contact_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'mother_occupation' =>'Required',
                'mother_contact_email' =>'email',
                'mother_contact_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'emergency_contact' => 'Required',
            );

        return \Validator::make($Request, $rules);  
    }

    /********************************************
    ## StudentCountByProgram
    *********************************************/

    public static function StudentCountByProgram($program,$semester,$academic_year){

        $last_student_serial_no = \DB::table('student_basic')
        ->select('student_serial_no')->where('program',$program)
        ->where('semester', $semester)
        ->where('academic_year', $academic_year)
        // ->orderBy('student_serial_no','desc')
        ->orderBy('created_at','desc')
        ->first();

        if(!empty($last_student_serial_no)){

            #serial no 4 digit
            $last_number = substr($last_student_serial_no->student_serial_no,-4); 
            $last_number = (int)$last_number;

        }else $last_number=0;

        return $last_number;
    }



    /********************************************
    ## StudentImageUrl
    *********************************************/

    public static function StudentImageUrl($program_code,$semester_title,$academic_year,$student_serial_no,$image_url){

        $request_dir = 'STUDENT/'.strtoupper($program_code).'/'.$academic_year.'/'.strtoupper($semester_title).'/';

        if (!file_exists(public_path($request_dir)))
               mkdir(public_path($request_dir), 0777, true);

            $file_info = explode('/', $image_url);
            $file_type = explode('.', end($file_info));

            $moved_filename_location = $request_dir.$student_serial_no.'.'.$file_type[1];
            // copy($image_url, $moved_filename_location);

            $content = file_get_contents($image_url);
            $fp = fopen($moved_filename_location , "w");
            fwrite($fp, $content);
            fclose($fp);

            // $ch = curl_init ($image_url);
            // curl_setopt($ch, CURLOPT_HEADER, 0);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            // $raw=curl_exec($ch);
            // curl_close ($ch);
            // if(file_exists($saveto)){
            //     unlink($saveto);
            // }
            // $fp = fopen($moved_filename_location,'x');
            // fwrite($fp, $raw);
            // fclose($fp);


        return asset($moved_filename_location);
    }







     /********************************************
    ## FacultyBasicFormValidation
    *********************************************/

    public static function FacultyBasicFormValidation($FacultyRequest){
            $rules = array(
                'department' => 'Required',
                'program' => 'Required',
                'first_name'    => 'Required|max:15',
                'last_name'   => 'Required|max:15',
                'mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11|unique:faculty_basic,mobile',
                'email'   => 'Required|email',
                'gender' => 'Required',
                'marital_status' => 'Required',
                'nationality' => 'Required',
                'religion' => 'Required',
                // 'blood_group' => 'Required',
                'date_of_birth' => 'Required|date',
                'faculty_join_date' =>'Required',
                'postal_code' => 'Required',
                'city' => 'Required',
                'country' => 'Required',
                'pro_designation' =>'Required',
                // 'others_designation' =>'Required',
                'contact_type' => 'Required',
                'contact_detail' => 'Required',
                'image_url'=>'Required'
                
            );

        return \Validator::make($FacultyRequest, $rules);  
    }


    /*****************************************
    ## FacultyCount
    *********************************************/

    public static function FacultyCount($department){

        $last_number_info = \DB::table('faculty_basic')->where('department',$department)->orderBy('faculty_id','desc')->first();
        if(!empty($last_number_info)){
            $last_faculty_id=$last_number_info->faculty_id;
            $last_faculty_number=(int)substr($last_faculty_id,4);
            $last_number=$last_faculty_number+1;
        }else{
          $last_number =001; 
        }
        return $last_number;
    }

    /********************************************
    ## FacultyImageUrl
    *********************************************/

    public static function FacultyImageUrl($department,$faculty_serial_no,$image_url){

        $request_dir = 'FACULTY/'.$department.'/';

        if (!file_exists($request_dir))
               mkdir($request_dir, 0777, true);

            $file_type = explode('.', $image_url);

            $moved_filename_location = $request_dir.$faculty_serial_no.'.'.$file_type[1];
            copy($image_url, $moved_filename_location);

        return asset($moved_filename_location);
    }


    /********************************************
    ## ProgramList
    *********************************************/

    public static function ProgramList(){

        $program_list = \DB::table('univ_program')->orderBy('program_id','asc')->get();     
        return $program_list;
    }


    /********************************************
    ## DepartmentList
    *********************************************/

    public static function DepartmentList(){

        $department_list = \DB::table('univ_department')->orderBy('department_no','asc')->get();     
        return $department_list;
    }



    /********************************************
    ## FacultyBasicFormValidation
    *********************************************/

    public static function ProgramCoordintorFormValidation($Request){
        $rules = array(
            'department'    => 'Required',
            'coordinator_program'  => 'Required',
            'coordinator_faculty_id' => 'Required',
            'program_coordinator_year' => 'Required',
            'program_coordinator_semester' => 'Required',
            'program_coordinator_level' =>'Required',
            'program_coordinator_term' =>'Required',
            
            );

        return \Validator::make($Request, $rules);  
    }



    /********************************************
    ## WaiverList
    *********************************************/

    public static function WaiverList(){

        $waiver_list = \DB::table('waivers')->orderBy('waiver_name','asc')->get();     
        return $waiver_list;
    }



    /********************************************
    ## AcademicCalenderFormValidation
    *********************************************/

    public static function AcademicCalenderFormValidation($Request){
            $rules = array(
                'academic_calender_year'    => 'Required',
                'academic_calender_semester'   => 'Required',
                'semester_start' => 'Required',
                'semester_end' => 'Required',
                'semester_course_reg_start' => 'Required',
                'semester_course_reg_end' =>'Required',
                'midterm_exam_start' =>'Required|date',
                'midterm_exam_end'    => 'Required',
                'final_exam_start'   => 'Required',
                'final_exam_end' => 'Required',
                'semester_break_start' => 'Required',
                'semester_break_end' => 'Required',
            );

        return \Validator::make($Request, $rules);  
    }



    
    /********************************************
    ## EmployeeImageSize
    *********************************************/
    public static function EmployeeImageSize($maxwidth,$maxheight,$current){

        list($width, $height) = getimagesize($current);

       return ( ($width <= $maxwidth) && ($height <= $maxheight) );
    }


     /********************************************
    ## StuffBasicFormValidation
    *********************************************/

    public static function EmployeeBasicFormValidation($EmployeeRequest){
            $rules = array(
                'first_name'    => 'Required|max:15',
                'last_name'   => 'Required|max:15',
                'mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11|regex:/^[^0-9]*(88)?0/|min:11|unique:faculty_basic,mobile',
                'email'   => 'Required|email',
                'gender' => 'Required',
                'marital_status' => 'Required',
                'nationality' => 'Required',
                'religion' => 'Required',
                // 'blood_group' => 'Required',
                'date_of_birth' => 'Required|date',
                'employee_join_date' =>'Required',
                'postal_code' => 'Required',
                'city' => 'Required',
                'country' => 'Required',
                'pro_designation' =>'Required',
                // 'others_designation' =>'Required',
                'contact_type' => 'Required',
                'contact_detail' => 'Required',
                'image_url'=>'Required'
                
            );

        return \Validator::make($EmployeeRequest, $rules);  
    }



    /********************************************
    ## EmployeeImageUrl
    *********************************************/

    public static function EmployeeImageUrl($employee_id,$image_url){

         $request_dir = 'EMPLOYEE/';

        if (!file_exists($request_dir))
               mkdir($request_dir, 0777, true);

            $file_type = explode('.', $image_url);

            $moved_filename_location = $request_dir.$employee_id.'.'.$file_type[1];
            copy($image_url, $moved_filename_location);

        return $moved_filename_location;
    }

    /*****************************************
    ## EmployeeCount
    *********************************************/

    public static function EmployeeCount(){

        // $last_number = \DB::table('employee_basic')->count();
        // return $last_number;

        $last_number_info = \DB::table('employee_basic')->orderBy('employee_id','desc')->first();
        if(!empty($last_number_info)){
            $last_employee_id=$last_number_info->employee_id;
            $last_employee_number=(int)substr($last_employee_id,4);
            $last_number=$last_employee_number+1;
        }else{
          $last_number =001; 
        }
        return $last_number;
    }



    /********************************************
    ## TransferStudentImageUrl
    *********************************************/

    public static function TransferStudentImageUrl($program_code,$academic_year,$semester_title,$new_student_serial_no, $image_url){

        $request_dir = 'STUDENT/'.strtoupper($program_code).'/'.$academic_year.'/'.strtoupper($semester_title).'/';
        if (!file_exists($request_dir))
            mkdir($request_dir, 0777, true);
            $file_type = explode('.', $image_url);
            $moved_filename_location = $request_dir.$new_student_serial_no.'.'.$file_type[1];
            copy($image_url, $moved_filename_location);

        return asset($moved_filename_location);
    }


    /********************************************
    ## CurrectImageSize
    *********************************************/
    public static function CurrectImageSize($maxwidth,$maxheight,$current){

        list($width, $height) = getimagesize($current);

       return ( ($width <= $maxwidth) && ($height <= $maxheight) );
    }


    
    /*----------------------------------------------------------------------------*/
}
