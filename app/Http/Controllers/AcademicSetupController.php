<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\System;
use Carbon;
use Exception;


/*******************************
#
## Academic Setup Controller
#
*******************************/

class AcademicSetupController extends Controller
{

    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
       
    }

    /********************************************
    ## AcademicDahsboardPage 
    *********************************************/

    public function AcademicDahsboardPage(){

        $data['page_title'] = $this->page_title;
        return \View::make('pages.academic-settings.academic-home',$data);
    }

    /********************************************
    ## CourseSettingsPage 
    *********************************************/

    public function CourseSettingsPage(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'course_categoty';

        /*-------------------Course Category-------------------------*/
        $all_course_category_info = \DB::table('course_category')->get();
        $data['all_course_category_info'] =$all_course_category_info;
        
        $all_course_category = \DB::table('course_category')->paginate(10);
        $all_course_category->setPath(url('/academic/course-settings'));
        $course_pagination = $all_course_category->appends(['tab' => 'course_categoty'])->render();
        $data['course_pagination'] =$course_pagination;
        $data['all_course_category'] = $all_course_category;

        /*-------------------Course Category-------------------------*/


        if(isset($_GET['program'])){

            $all_course = \DB::table('course_basic')
                        ->leftJoin('univ_program','course_basic.course_program','=','univ_program.program_id')
                        ->where(function($query){

                           if(isset($_GET['program']) && $_GET['program'] != null){
                                $query->where(function ($q){
                                    $q->where('course_program', $_GET['program']);
                                  });
                            }

                        })     
                        ->select('course_basic.*','univ_program.*')
                        ->orderBy('course_basic.created_at','desc')
                        ->paginate(10);

            if(isset($_GET['program']))
                $program = $_GET['program'];
            else $program = null;

            $all_course->setPath(url('/academic/course-settings'));
            $course_basic_pagination = $all_course->appends(['tab' => 'course_add','program' => $program])->render();
            $data['course_basic_pagination'] =$course_basic_pagination;
            $data['all_course'] = $all_course;

        }else{
            $all_course = \DB::table('course_basic')
                            ->leftJoin('univ_program','course_basic.course_program','=','univ_program.program_id')
                            ->select('course_basic.*','univ_program.*')
                            ->orderBy('course_basic.created_at','desc')
                            ->paginate(10);
            $all_course->setPath(url('/academic/course-settings'));
            $course_basic_pagination = $all_course->appends(['tab' => 'course_add'])->render();
            $data['course_basic_pagination'] =$course_basic_pagination;
            $data['all_course'] = $all_course;
        }

        /*-------------------Course plan-------------------------*/

        $all_course_plan = \DB::table('course_catalogue')
                        ->leftJoin('univ_program','course_catalogue.course_catalogue_program','=','univ_program.program_id')
                        ->leftJoin('course_category','course_catalogue.course_category_slug','=','course_category.course_category_slug')
                        ->select('course_catalogue.*','univ_program.*','course_category.*')
                        ->paginate(10);
        $all_course_plan->setPath(url('/academic/course-settings'));
        $course_plan_pagination = $all_course_plan->appends(['tab' => 'course_plan'])->render();
        $data['course_plan_pagination'] =$course_plan_pagination;
        $data['all_course_plan'] = $all_course_plan;


        $data['tab'] = $tab;
        $data['page_title'] = $this->page_title;
        $data['program_list'] =\App\Applicant::ProgramList();
        $data['degree_list'] =\App\Academic::DegreeList();

        $degree_plan_list=\DB::table('degree_plans')
        ->leftJoin('univ_program','univ_program.program_id','=','degree_plans.plan_program')
        ->leftJoin('univ_degree','univ_degree.degree_slug','=','degree_plans.plan_degree')
        ->paginate(10);
        $degree_plan_list->setPath(url('/academic/course-settings'));
        $degree_plan_pagination = $degree_plan_list->appends(['tab' => 'degree_plan'])->render();
        $data['degree_plan_list']=$degree_plan_list;
        $data['degree_plan_pagination']=$degree_plan_pagination;


        if(isset($_GET['program'])){

            $catalogue_list=\DB::table('course_catalogue')
                ->leftJoin('course_category','course_category.course_category_slug','=','course_catalogue.course_category_slug')
                ->leftJoin('univ_program','univ_program.program_id','=','course_catalogue.course_catalogue_program')
                ->where(function($query){

                   if(isset($_GET['program']) && $_GET['program'] != null){
                        $query->where(function ($q){
                            $q->where('course_catalogue_program', $_GET['program']);
                          });
                    }

                }) 
                ->orderBy('program_code','asc')
                ->orderBy('course_category_name','asc')
                ->paginate(10);

            $catalogue_list->setPath(url('/academic/course-settings'));
            $catalogue_list_pagination = $catalogue_list->appends(['tab' => 'course_catalogue','program' => $program])->render();
            $data['catalogue_list']=$catalogue_list;
            $data['catalogue_list_pagination']=$catalogue_list_pagination;

        }else{

            $catalogue_list=\DB::table('course_catalogue')
            ->leftJoin('course_category','course_category.course_category_slug','=','course_catalogue.course_category_slug')
            ->leftJoin('univ_program','univ_program.program_id','=','course_catalogue.course_catalogue_program')
            ->orderBy('program_code','asc')
            ->orderBy('course_category_name','asc')
            ->paginate(10);
            $catalogue_list->setPath(url('/academic/course-settings'));
            $catalogue_list_pagination = $catalogue_list->appends(['tab' => 'course_catalogue'])->render();
            $data['catalogue_list']=$catalogue_list;
            $data['catalogue_list_pagination']=$catalogue_list_pagination;

        }

        return \View::make('pages.academic-settings.course-setting',$data);
    }


    /********************************************
    ## CourseCategoryAdd 
    *********************************************/

    public function CourseCategoryAdd(){


        $rule = ['course_category_name' => 'Required'];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $category_slug = explode(' ', \Request::input('course_category_name'));
            $category_slug = implode('_', $category_slug);

            $course_category_info = \DB::table('course_category')->where('course_category_slug',$category_slug)->first();
            if(empty($course_category_info)){

                $category_data = [
                                    'course_category_tran_code' => $uuid->string,
                                    'course_category_name' => \Request::input('course_category_name'),
                                    'course_category_slug' =>strtolower($category_slug),
                                    'created_at' =>$now,
                                    'updated_at' =>$now ,
                                    'created_by' =>\Auth::user()->user_id,
                                    'updated_by' =>\Auth::user()->user_id,

                                ];

                try{


                    $success = \DB::transaction(function () use ($category_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                            $course_category_save = \DB::connection($this->dbList[$i])->table('course_category')->insert($category_data);

                            if(!$course_category_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert',json_encode($category_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });


                    return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('message','Course Category has been added.');

                }catch(\Exception  $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('errormessage','Course Category Already exists.');
                }
            }return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('errormessage','Course Category Already exists.');

        }else return \Redirect::to('/academic/course-settings?tab=course_categoty')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## CourseBasicEntry 
    *********************************************/

    public function CourseBasicEntry(){


        $rule = [
                    'course_program' => 'Required|not_in:0',
                    'course_code' => 'Required|alpha_num',
                    'course_type' => 'Required|not_in:0',
                    'course_title' => 'Required',
                    'credit_hours' => 'Required|numeric',
                    'level' => 'Required',
                    'term' => 'Required',
                    // 'per_credit_fees_amount' => 'Required|numeric',
                    // 'total_credit_fees_amount' => 'Required|numeric',
                    'course_description' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');

            $course_slug = strtolower(\Request::input('course_code')).'_'.\Request::input('course_program').'_'.\Request::input('level').'_'.\Request::input('term');

            $course_basic_info = \DB::table('course_basic')->where('course_slug',$course_slug)->first();

            if(empty($course_basic_info)){
                
                $course_data = array(

                                    'course_basic_tran_code' => $uuid->string,
                                    'course_slug' => $course_slug,
                                    'course_program' => \Request::input('course_program'),
                                    'course_code' => strtoupper(\Request::input('course_code')),
                                    'course_type' => \Request::input('course_type'),
                                    'course_title' => \Request::input('course_title'),
                                    'credit_hours' => \Request::input('credit_hours'),
                                    'level' => \Request::input('level'),
                                    'term' => \Request::input('term'),
                                    // 'per_credit_fees_amount' => \Request::input('per_credit_fees_amount'),
                                    // 'total_credit_fees_amount' => \Request::input('total_credit_fees_amount'),
                                    'per_credit_fees_amount' => 0,
                                    'total_credit_fees_amount' => 0,
                                    'course_description' => trim(\Request::input('course_description')),
                                    'created_at' => $now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->user_id,
                                    'updated_by' =>\Auth::user()->user_id,
                                );

                try{


                    $success = \DB::transaction(function () use ($course_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                            $course_insert_data = \DB::connection($this->dbList[$i])->table('course_basic')->insert($course_data);


                            if(!$course_insert_data){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,course_basic',json_encode($course_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/academic/course-settings?tab=course_add')->with('message','Course has been added.');

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Course Code Already exists.');
                }
            }return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Course Code Already exists.');
          

        }else return \Redirect::to('/academic/course-settings?tab=course_add')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## AcademicCourseListAjax 
    *********************************************/

    public function AcademicCourseListAjax($program){

        $course_list = \DB::table('course_basic')->where('course_program',$program)->orderBy('course_code','asc')->get();

        $data['course_list'] = $course_list;

        return \View::make('pages.academic-settings.ajax-course-list',$data);
    }

    /********************************************
    ## CourseCategoryUpdate 
    *********************************************/

    public function CourseCategoryUpdate(){

       $rule = [
                'category_program' => 'Required|not_in:0',
                'course_category' => 'Required|not_in:0',
                'no_of_courses' => 'Required|integer',
                'total_credit_hours' => 'Required|numeric'
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){


            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');
            $course_catalogue_slug = \Request::input('course_category').'_'.\Request::input('category_program');


            $course_catalogue_info = \DB::table('course_catalogue')->where('course_catalogue_slug',$course_catalogue_slug)->first();
            
            if(empty($course_catalogue_info)){
            
                $catalogue_data = [
                                    'course_catalogue_tran_code' => $uuid->string,
                                    'course_catalogue_slug' => $course_catalogue_slug,
                                    'course_catalogue_program' =>\Request::input('category_program'),
                                    'course_category_slug' => \Request::input('course_category'),
                                    'no_of_courses' => \Request::input('no_of_courses'),
                                    'total_credit_hours' => \Request::input('total_credit_hours'),
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->user_id,
                                    'updated_by' =>\Auth::user()->user_id,

                                ];

                try{

                    $success = \DB::transaction(function () use ($catalogue_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                            $course_catalogue_save = \DB::connection($this->dbList[$i])->table('course_catalogue')->insert($catalogue_data);

                            if(!$course_catalogue_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,course_catalogue',json_encode($catalogue_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('errormessage','Course Plan Already exists.');
                }


                if(is_array(\Request::input('course_selected_checkbox'))){
                    
                    $all_course_slug = \Request::input('course_selected_checkbox');
                    $now = date('Y-m-d H:i:s');
                    $update_data = ['course_category'=>\Request::input('course_category'),'updated_at' =>$now,'updated_by' =>\Auth::user()->user_id,];

                    foreach ($all_course_slug as $key => $course_slug) {

                        try{

                            $success = \DB::transaction(function () use ($update_data, $course_slug) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                                    $update_course= \DB::connection($this->dbList[$i])->table('course_basic')->where('course_slug',$course_slug)->update($update_data);


                                    if(!$update_course){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('update,course_basic',json_encode($update_data));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });


                        }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                        }
                        
                    }

                    return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('message','Courses has been added successfully.');

                }else return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('errormessage','Please Select Course.');
            }else return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('errormessage','Already exist this.');

        }else return \Redirect::to('/academic/course-settings?tab=course_catalogue')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## AcademicCatalogueEntry 
    *********************************************/

    public function AcademicCatalogueEntry(){


        $rule = [
                    'catalouge_program' => 'Required',
                    'catalouge_category' =>'Required',
                    'no_of_courses' => 'Required|integer',
                    'total_credit_hours' => 'Required|numeric'
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $uuid = \Uuid::generate(4);
            $now = date('Y-m-d H:i:s');

            $course_catalogue_slug = \Request::input('catalouge_category').'_'.\Request::input('catalouge_program');

            $course_catalogue_info = \DB::table('course_catalogue')->where('course_catalogue_slug',$course_catalogue_slug)->first();
            
            if(empty($course_catalogue_info)){

                $catalogue_data = [
                                    'course_catalogue_tran_code' => $uuid->string,
                                    'course_catalogue_slug' => $course_catalogue_slug,
                                    'course_catalogue_program' =>\Request::input('catalouge_program'),
                                    'course_category_slug' => \Request::input('catalouge_category'),
                                    'no_of_courses' => \Request::input('no_of_courses'),
                                    'total_credit_hours' => \Request::input('total_credit_hours'),
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->user_id,
                                    'updated_by' =>\Auth::user()->user_id,

                                ];

                try{


                    $success = \DB::transaction(function () use ($catalogue_data) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $course_catalogue_save=\DB::connection($this->dbList[$i])->table('course_catalogue')->insert($catalogue_data);

                            if(!$course_catalogue_save){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('insert,course_catalogue',json_encode($catalogue_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/academic/course-settings?tab=course_plan')->with('message','Course Plan has been added.');

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic/course-settings?tab=course_plan')->with('errormessage','Course Plan Already exists.');
                }
            
            }return \Redirect::to('/academic/course-settings?tab=course_plan')->with('errormessage','Course Plan Already exists.');

        }else return \Redirect::to('/academic/course-settings?tab=course_plan')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## CourseEditFormAjaxRequest 
    *********************************************/

    public function CourseEditFormAjaxRequest($course_setting_type,$type_slug){

        if($course_setting_type=='course_categoty'){

            $course_categoty_info = \DB::table('course_category')->where('course_category_slug',$type_slug)->first();
            $data['form_name'] = $course_setting_type;
            $data['course_categoty_info']= $course_categoty_info;

            if(!empty($course_categoty_info))
                return \View::make('pages.academic-settings.ajax-edit-course-settings',$data);

            else{
                    \Session::flash('errormessage','Somthing Wrong');
                return "404";
            } 

        }elseif ($course_setting_type=='course_add'){

           $course_info = \DB::table('course_basic')->where('course_slug',$type_slug)->first();
            $data['form_name'] = $course_setting_type;
            $data['course_info']= $course_info;

            $data['program_list'] =\App\Applicant::ProgramList();

            if(!empty($course_info))
                return \View::make('pages.academic-settings.ajax-edit-course-settings',$data);

            else{
                    \Session::flash('errormessage','Somthing Wrong !!');
                return "404";
            } 

        }elseif($course_setting_type=='course_plan'){

            $course_catalogue_info = \DB::table('course_catalogue')->where('course_catalogue_slug',$type_slug)->first();
            $data['form_name'] = $course_setting_type;
            $data['course_catalogue_info']= $course_catalogue_info;
            $data['program_list'] =\App\Applicant::ProgramList();
            $data['all_course_category'] = \DB::table('course_category')->get();



            if(!empty($course_catalogue_info))
                return \View::make('pages.academic-settings.ajax-edit-course-settings',$data);

            else{
                    \Session::flash('errormessage','Somthing Wrong !!');
                return "404";
            } 
            
        }else{

            return "404";
        }
    }

    /********************************************
    ## CourseCategoryEdit 
    *********************************************/

    public function CourseCategoryEdit($category_slug){


        $rule = ['course_category_name' => 'Required'];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            $now = date('Y-m-d H:i:s');
            $category_slug_new = explode(' ', \Request::input('course_category_name'));
            $category_slug_new = implode('_', $category_slug_new);

            $course_category_info = \DB::table('course_category')->where('course_category_slug',$category_slug_new)->first();
            $course_basic_info = \DB::table('course_basic')->where('course_category',$category_slug)->first();
            if(empty($course_category_info) && empty($course_basic_info)){

                $category_data = [
                                    'course_category_name' => \Request::input('course_category_name'),
                                    'course_category_slug' =>strtolower($category_slug_new),
                                    'updated_at' =>$now, 
                                    'updated_by' =>\Auth::user()->user_id,

                                ];

                try{

                    $success = \DB::transaction(function () use ($category_data, $category_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $course_category_update=\DB::connection($this->dbList[$i])->table('course_category')->where('course_category_slug',$category_slug)->update($category_data);


                            if(!$course_category_update){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,course_category',json_encode($category_data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });


                    return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('message','Course Category has been updated.');

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('errormessage','Course Category Already exists.');
                }
            }return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('errormessage','Course Category already exist.');

        }else return \Redirect::to('/academic/course-settings?tab=course_categoty')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## CourseInfoUpdate 
    *********************************************/

    public function CourseInfoUpdate($course_slug){


        $rule = [
                    'course_program' => 'Required|not_in:0',
                    'course_code' => 'Required|alpha_num',
                    'course_type' => 'Required|not_in:0',
                    'course_title' => 'Required',
                    'credit_hours' => 'Required|numeric',
                    'level' => 'Required',
                    'term' => 'Required',
                    // 'per_credit_fees_amount' => 'Required|numeric',
                    // 'total_credit_fees_amount' => 'Required|numeric',
                    'course_description' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
            
            $now = date('Y-m-d H:i:s');

            $course_slug_new = strtolower(\Request::input('course_code')).'_'.\Request::input('course_program').'_'.\Request::input('level').'_'.\Request::input('term');

            $course_info = \DB::table('course_basic')->where('course_slug',$course_slug)->first();

            if(!empty($course_info)){
                $student_academic_tabulation_info = \DB::table('student_academic_tabulation')->where('tabulation_course_id',$course_info->course_code)->first();
                if(empty($student_academic_tabulation_info)){
                    $course_data = array(
                                        'course_slug' => $course_slug_new,
                                        'course_program' => \Request::input('course_program'),
                                        'course_code' => strtoupper(\Request::input('course_code')),
                                        'course_type' => \Request::input('course_type'),
                                        'course_title' => \Request::input('course_title'),
                                        'credit_hours' => \Request::input('credit_hours'),
                                        'level' => \Request::input('level'),
                                        'term' => \Request::input('term'),
                                        // 'per_credit_fees_amount' => \Request::input('per_credit_fees_amount'),
                                        // 'total_credit_fees_amount' => \Request::input('total_credit_fees_amount'),
                                        'per_credit_fees_amount' => 0,
                                        'total_credit_fees_amount' => 0,
                                        'course_description' => trim(\Request::input('course_description')),
                                        'updated_at' =>$now,
                                        'updated_by' =>\Auth::user()->user_id,
                                    );

                    try{


                        $success = \DB::transaction(function () use ($course_data, $course_slug) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $course_update_data = \DB::connection($this->dbList[$i])->table('course_basic')->where('course_slug',$course_slug)->update($course_data);


                                if(!$course_update_data){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,course_basic',json_encode($course_data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                        return \Redirect::to('/academic/course-settings?tab=course_add')->with('message','Course has been updated.');

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Course Code Already exists.');
                    }
                }return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','You can not update this, because it has program.');
            }return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Invalid course.');
      

        }else return \Redirect::to('/academic/course-settings?tab=course_add')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## CourseCatalogueUpdate 
    *********************************************/

    public function CourseCatalogueUpdate($catalogue_slug){


        $rule = [
                    'catalouge_program' => 'Required',
                    'catalouge_category' =>'Required',
                    'no_of_courses' => 'Required|integer',
                    'total_credit_hours' => 'Required|numeric'
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){
      
            $now = date('Y-m-d H:i:s');
            $course_catalogue_slug_new = \Request::input('catalouge_category').'_'.\Request::input('catalouge_program');

            $degree_plan_detail_info = \DB::table('degree_plan_detail')->where('course_catalogue_tran_code',$course_catalogue_slug_new)->first();
                
            if(empty($degree_plan_detail_info)){
            
                $course_catalogue_info = \DB::table('course_catalogue')->where('course_catalogue_slug',$course_catalogue_slug_new)->first();
                
                if(empty($course_catalogue_info)){

                    $catalogue_data = [
                                        'course_catalogue_slug' => $course_catalogue_slug_new,
                                        'course_catalogue_program' =>\Request::input('catalouge_program'),
                                        'course_category_slug' => \Request::input('catalouge_category'),
                                        'no_of_courses' => \Request::input('no_of_courses'),
                                        'total_credit_hours' => \Request::input('total_credit_hours'),
                                        'updated_at' =>$now,
                                        'updated_by' =>\Auth::user()->user_id,

                                    ];

                    try{


                        $success = \DB::transaction(function () use ($catalogue_data, $catalogue_slug) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $course_catalogue_update=\DB::connection($this->dbList[$i])->table('course_catalogue')->where('course_catalogue_slug',$catalogue_slug)->update($catalogue_data);

                                if(!$course_catalogue_update){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,course_catalogue',json_encode($catalogue_data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                        return \Redirect::to('/academic/course-settings?tab=course_plan')->with('message','Course Plan has been updated.');

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic/course-settings?tab=course_plan')->with('errormessage','Course Plan Already exists.');
                    }

                }return \Redirect::to('/academic/course-settings?tab=course_plan')->with('errormessage','Course Plan Already exists.');
            }return \Redirect::to('/academic/course-settings?tab=course_plan')->with('errormessage','Course Plan Already exists.');

        }else return \Redirect::to('/academic/course-settings?tab=course_plan')->withInput(\Request::all())->withErrors($v->messages());
    }


    /********************************************
    ## CourseSettingsDelete 
    *********************************************/

    public function CourseSettingsDelete($course_setting_type,$type_slug){

        if($course_setting_type=='course_categoty'){
            try{

                $course_basic_info = \DB::table('course_basic')->where('course_category',$type_slug)->first();
            
                if(empty($course_basic_info)){


                    $success = \DB::transaction(function () use ($type_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $course_categoty_delete = \DB::connection($this->dbList[$i])->table('course_category')->where('course_category_slug',$type_slug)->delete();

                            if(!$course_categoty_delete){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('delete,course_category',json_encode($type_slug));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('message','Course Category has been deleted.');
                }return \Redirect::to('/academic/course-settings?tab=course_categoty')->with('errormessage','Course Category has course.');

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }
                
        }elseif ($course_setting_type=='course_add'){

            try{

                $course_info = \DB::table('course_basic')->where('course_slug',$type_slug)->first();

                    if(!empty($course_info)){
                        $student_academic_tabulation_info = \DB::table('student_academic_tabulation')->where('tabulation_course_id',$course_info->course_code)->first();
                        if(empty($student_academic_tabulation_info)){

                            if(($course_info->course_category) == NULL){

                                $success = \DB::transaction(function () use ($type_slug) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                        $course_info_delete = \DB::connection($this->dbList[$i])->table('course_basic')->where('course_slug',$type_slug)->delete();


                                        if(!$course_info_delete){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::TransactionCommit();
                                        \App\System::EventLogWrite('delete,course_basic',json_encode($type_slug));
                                        
                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                                });

                                return \Redirect::to('/academic/course-settings?tab=course_add')->with('message','Course  has been deleted.');

                            } return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Course  has calalouge');
                        }else return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Student are studing this course.');

                    }return \Redirect::to('/academic/course-settings?tab=course_add')->with('errormessage','Empty course !!!');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }                


        }elseif($course_setting_type=='course_plan'){

            try{

                $degree_plan_detail_info = \DB::table('degree_plan_detail')->where('course_catalogue_tran_code',$type_slug)->first();
                
                if(empty($degree_plan_detail_info)){

                    $success = \DB::transaction(function () use ($type_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $course_catalogue_delete = \DB::connection($this->dbList[$i])->table('course_catalogue')->where('course_catalogue_slug', $type_slug)->delete();

                            if(!$course_catalogue_delete){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('delete,course_catalogue',json_encode($type_slug));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });


                     return \Redirect::to('/academic/course-settings?tab=course_plan')->with('message','Course Plan  has been deleted.');
                }else return \Redirect::to('/academic/course-settings')->with('errormessage','Course Plan include to degree pian.');

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            } 

               
        }else return \Redirect::to('/academic/course-settings')->with('errormessage','Invalid Request.');
    }
    

    /********************************************
    ## AcademicSettingsPage 
    *********************************************/
    public function AcademicSettingsPage(){


        if(isset($_GET['tab']) && !empty($_GET['tab'])){
                $tab=$_GET['tab'];
        }else $tab='degree';

        $data['page_title'] = $this->page_title;
        $data['degree_list']=\App\Academic::DegreeList();

        /*-------------Department------------------------------*/
        $department=\DB::table('univ_department')->orderBy('department_title','asc')->paginate(5);
        $department->setPath(url('/academic-settings/home'));
        $pagination_department = $department->appends(['tab' => 'department'])->render();
        $data['pagination_department'] = $pagination_department;
        $data['department_list'] = $department;


        /*-------------Programlist------------------------------*/
        $program_list=\DB::table('univ_program')
                        ->leftJoin('univ_department', 'univ_program.program_department_no','=','univ_department.department_no')
                        ->leftJoin('univ_degree', 'univ_program.program_degree_code','=','univ_degree.degree_code')
                        ->select('univ_program.*','univ_department.*','univ_degree.*')
                        ->orderBy('program_id','asc')->paginate(5);
        $program_list->setPath(url('/academic-settings/home'));
        $pagination_program = $program_list->appends(['tab' => 'program'])->render();
        $data['pagination_program'] = $pagination_program;
        $data['program_list'] = $program_list;


        /*-------------Building List------------------------------*/
        $building_list = \DB::table('univ_building')
                        ->leftJoin('univ_campus','univ_building.campus_code','=','univ_campus.campus_code')
                        ->select('univ_building.*','univ_campus.*')
                        ->paginate(5);

        $building_list->setPath(url('/academic-settings/home'));
        $pagination_building_list = $building_list->appends(['tab' => 'building'])->render();
        $data['pagination_building_list'] = $pagination_building_list;
        $data['building_list'] = $building_list;

        /*-------------RoomList List------------------------------*/

        $room_list = \DB::table('univ_room')
                        ->leftJoin('univ_building','univ_building.building_code','=','univ_room.building_code')
                        ->select('univ_room.*','univ_building.*')
                        ->paginate(5);

        $room_list->setPath(url('/academic-settings/home'));
        $pagination_room_list = $room_list->appends(['tab' => 'room'])->render();
        $data['pagination_room_list'] = $pagination_room_list;
        $data['room_list'] = $room_list;


        $data['semester_list']=\App\Academic::SemesterList();
        $data['campus_list']=\App\Academic::CampusList();
      

        $data['tab'] =$tab;
        return \View::make('pages.academic-settings.academic-settings',$data);
    }




    ######################## Academic Settings Form Submit ########################

    public function AcademicSettingsFormSubmit(Request $request ,$action){
        $now=date('Y-m-d H:i:s');


        #------Degree Insert------#
        if($action=='degree'){

            $validation_degree=\App\Academic::DegreeValidation(\Request::all());
            if($validation_degree->passes()){
                $data['degree_tran_code']=\Uuid::generate(4);
                $data['degree_title']=\Request::input('degree_title');
                $degree_slug = explode(' ', \Request::input('degree_title'));
                $degree_slug_new = strtolower(implode('_', $degree_slug));
                $data['degree_slug'] =strtolower(implode('_', $degree_slug));
                $data['degree_code']=\Request::input('degree_code');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;

                $degree_info=\DB::table('univ_degree')->where('degree_slug',$degree_slug_new)->first();

                if(empty($degree_info)){
                        try{

                            $success = \DB::transaction(function () use ($data) {

                                for($i=0; $i<count($this->dbList); $i++){

                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $save_degree=\DB::connection($this->dbList[$i])->table('univ_degree')->insert($data);

                                    if(!$save_degree){
                                        $error=1;
                                        break; 
                                    }

                                }

                            });

                            if(!isset($error)){

                                \App\System::TransactionCommit();

                                \App\System::EventLogWrite('insert,univ_degree',json_encode($data));
                                
                            }else{

                                \App\System::TransactionRollback();

                                throw new Exception("Error Processing Request", 1);
                            }


                            return \Redirect::to('/academic-settings/home?tab=degree')->with('message',"Degree Inserted Successfully!");

                        }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                        }
                }return \Redirect::back()->with('errormessage',"Already exist this.");
                        
                
            }else return \Redirect::to('/academic-settings/home?tab=degree')->withInput()->withErrors($validation_degree->messages());
        }



        #------Department Insert------#
        else if($action=='department'){

            $validation_department=\App\Academic::DepartmentValidation(\Request::all());
            if($validation_department->passes()){
                $data['department_tran_code']=\Uuid::generate(4);
                $data['department_title']=\Request::input('department_title');
                $department_slug = explode(' ', \Request::input('department_title'));
                $department_slug_new=strtolower(implode('_', $department_slug));
                $data['department_slug'] =strtolower(implode('_', $department_slug));
                $data['department_no']= str_pad(\Request::input('department_no'), 2,0,STR_PAD_LEFT);
		$department_no= str_pad(\Request::input('department_no'), 2,0,STR_PAD_LEFT);
                $data['department_dean_chairperson']=\Request::input('department_dean_chairperson');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;

                

                $department_info=\DB::table('univ_department')->where('department_slug',$department_slug_new)->first();
                $department_no_info=\DB::table('univ_department')->where('department_no',$department_no)->first();

                if(empty($department_info) && empty($department_no_info)){
  

                    try{


                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_department=\DB::connection($this->dbList[$i])->table('univ_department')->insert($data);

                                if(!$save_department){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_department',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                    return \Redirect::to('/academic-settings/home?tab=department')->with('message',"Department Inserted Successfully!");

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic-settings/home?tab=department')->with('errormessage','Department  Already exists.');
                    }
                }return \Redirect::to('/academic-settings/home?tab=department')->with('errormessage','Department  Already exists.');

            }else return \Redirect::to('/academic-settings/home?tab=department')->withInput()->withErrors($validation_department->messages());
        }



        #------Program Insert------#
        else if($action=='program'){

            $validation_program=\App\Academic::ProgramValidation(\Request::all());
            if($validation_program->passes()){
                $data['program_tran_code']=\Uuid::generate(4);
                $data['program_title']=\Request::input('program_title');
                $program_slug = explode(' ', \Request::input('program_title'));
                $program_slug_new =strtolower(implode('_', $program_slug));
                $data['program_slug'] =strtolower(implode('_', $program_slug));
                $data['program_id']=\Request::input('program_id');
                $data['program_code']=\Request::input('program_code');
                $data['program_head']=\Request::input('program_head');
                $data['program_duration']=\Request::input('program_duration');
                $data['program_duration_type']=\Request::input('program_duration_type');
                $data['program_total_credit_hours']=\Request::input('program_total_credit_hours');
                $data['program_degree_code']=\Request::input('program_degree_code');
                $data['program_department_no']=\Request::input('department_no');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;

                $program_info=\DB::table('univ_program')->where('program_slug',$program_slug_new)->first();

                if(empty($program_info)){

                    try{


                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_program=\DB::connection($this->dbList[$i])->table('univ_program')->insert($data);

                                if(!$save_program){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_program',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                    return \Redirect::to('/academic-settings/home?tab=program')->with('message',"Program Inserted Successfully!");

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic-settings/home?tab=program')->with('errormessage','Program  Already exists.');
                    }
                }return \Redirect::to('/academic-settings/home?tab=program')->with('errormessage','Program  Already exists.');
      
            }else return \Redirect::to('/academic-settings/home?tab=program')->withInput()->withErrors($validation_program->messages());
        }



        #------Semester Insert------#
        else if($action=='semester'){

            $validation_semester=\App\Academic::SemesterValidation(\Request::all());
            if($validation_semester->passes()){
                $data['semester_tran_code']=\Uuid::generate(4);
                $data['semester_title']=\Request::input('semester_title');
                $semester_slug = explode(' ', \Request::input('semester_title'));
                $semester_slug_new =strtolower(implode('_', $semester_slug));
                $data['semester_slug'] =strtolower(implode('_', $semester_slug));
                $data['semester_code']=\Request::input('semester_code');
                $data['semester_sequence']=\Request::input('semester_sequence');
                $data['semester_duration']=\Request::input('semester_duration');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;

                $semester_info=\DB::table('univ_semester')->where('semester_slug',$semester_slug_new)->first();

                if(empty($semester_info)){
                    try{


                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_semester=\DB::connection($this->dbList[$i])->table('univ_semester')->insert($data);

                                if(!$save_semester){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_semester',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                       return \Redirect::to('/academic-settings/home?tab=semester')->with('message',"Semester Inserted Successfully!");

                   }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                    }
                    
                }return \Redirect::back()->with('errormessage',"Already exist this.");
                    
            }else return \Redirect::to('/academic-settings/home?tab=semester')->withInput()->withInput()->withErrors($validation_semester->messages());
        }



        #------Campus Insert------#
        else if($action=='campus'){

            $validation_campus=\App\Academic::CampusValidation(\Request::all());
            if($validation_campus->passes()){
                $data['campus_tran_code']=\Uuid::generate(4);
                $data['campus_title']=\Request::input('campus_title');
                $campus_slug = explode(' ', \Request::input('campus_title'));
                $campus_slug_new =strtolower(implode('_', $campus_slug));
                $data['campus_slug'] =strtolower(implode('_', $campus_slug));
                $data['campus_code']=\App\Academic::GetFirstLetter(\Request::input('campus_title'));
                $data['campus_location']=\Request::input('campus_location');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;


                $campus_info=\DB::table('univ_campus')->where('campus_slug',$campus_slug_new)->first();

                if(empty($campus_info)){

                    try{

                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_campus=\DB::connection($this->dbList[$i])->table('univ_campus')->insert($data);

                                if(!$save_campus){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_campus',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                       return \Redirect::to('/academic-settings/home?tab=campus')->with('message',"Campus Inserted Successfully!");

                   }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                    }
                        
                }return \Redirect::back()->with('errormessage',"Already exist this.");
                        
            }else return \Redirect::to('/academic-settings/home?tab=campus')->withInput()->withErrors($validation_campus->messages());
        }



        #------Building Insert------#
        else if($action=='building'){

            $validation_building=\App\Academic::BuildingValidation(\Request::all());
            if($validation_building->passes()){
                $data['building_tran_code']=\Uuid::generate(4);
                $data['building_title']=\Request::input('building_title');
                $building_slug = explode(' ', \Request::input('building_title'));
                $building_slug_new =strtolower(implode('_', $building_slug));
                $data['building_slug'] =strtolower(implode('_', $building_slug));
                $data['building_code']=  \Request::input('campus_code').str_pad(\Request::input('building_no'), 2,0,STR_PAD_LEFT);
                $data['campus_code']=\Request::input('campus_code');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;


                $building_info=\DB::table('univ_building')->where('building_slug',$building_slug_new)->first();

                if(empty($building_info)){

                    try{


                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_building=\DB::connection($this->dbList[$i])->table('univ_building')->insert($data);

                                if(!$save_building){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_building',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                        return \Redirect::to('/academic-settings/home?tab=building')->with('message',"Building Entry Completed!");

                    }catch(\Exception  $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage','Buliding  Already exists.');
                    }

                }return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage','Buliding  Already exists.');

                
            }else return \Redirect::to('/academic-settings/home?tab=building')->withInput()->withErrors($validation_building->messages());
        }



        #------Room Insert------#
        else if($action=='room'){

            $validation_room=\App\Academic::RoomValidation(\Request::all());
            if($validation_room->passes()){
                $data['room_tran_code']=\Uuid::generate(4);
                $data['room_title']=\Request::input('room_title');
                $room_no = \Request::input('room_no');
                $floor_no = \Request::input('floor_no');

                $data['room_slug'] = str_pad(\Request::input('floor_no'), 2,0,STR_PAD_LEFT).'_'.str_pad(\Request::input('room_no'), 2,0,STR_PAD_LEFT).'_'.\Request::input('building_code');

                $data['room_code']= \Request::input('building_code').'-'.str_pad(\Request::input('floor_no'), 2,0,STR_PAD_LEFT).str_pad(\Request::input('room_no'), 2,0,STR_PAD_LEFT);
                $room_code= \Request::input('building_code').'-'.str_pad(\Request::input('floor_no'), 2,0,STR_PAD_LEFT).str_pad(\Request::input('room_no'), 2,0,STR_PAD_LEFT);
                $data['room_type']=\Request::input('room_type');
                $data['room_capacity']=\Request::input('room_capacity');
                $data['room_facilities']=\Request::input('room_facilities');
                $data['building_code']=\Request::input('building_code');
                $data['created_at']=$now;
                $data['updated_at']=$now;
                $data['created_by'] = \Auth::user()->user_id;
                $data['updated_by'] = \Auth::user()->user_id;

                $room_info=\DB::table('univ_room')->where('room_code',$room_code)->first();

                if(empty($room_info)){

                    try{

                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $save_room=\DB::connection($this->dbList[$i])->table('univ_room')->insert($data);

                                if(!$save_room){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,univ_room',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                        return \Redirect::to('/academic-settings/home?tab=room')->with('message',"Room Entry Completed!");

                    }catch(\Exception  $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/academic-settings/home?tab=room')->with('errormessage','Room  Already exists.');
                    }

                }return \Redirect::to('/academic-settings/home?tab=room')->with('errormessage','Room  Already exists.');


            }else return \Redirect::to('/academic-settings/home?tab=room')->withInput()->withErrors($validation_room->messages());
        }


    }


    #-------------- Edit Module Start ------------------------#

    ######################## Edit Academic Settings ########################
    public function EditAcademicSettings($setting_type, $slug){
        $data['page_title'] = $this->page_title;
        $data['setting_type']=$setting_type;
        $data['edit_degree']=\DB::table('univ_degree')->where('degree_slug',$slug)->get();
        $data['edit_department']=\DB::table('univ_department')->where('department_slug',$slug)->get();
        $data['edit_program']=\DB::table('univ_program')->where('program_slug',$slug)->get();
        $data['edit_semester']=\DB::table('univ_semester')->where('semester_slug',$slug)->get();
        $data['edit_campus']=\DB::table('univ_campus')->where('campus_slug',$slug)->get();
        $data['edit_building']=\DB::table('univ_building')->where('building_slug',$slug)->get();
        $data['edit_room']=\DB::table('univ_room')->where('room_slug',$slug)->get();
        return \View::make('pages.academic-settings.ajax-academic-setup-edit-degree',$data);
    }


    ######################## Update Degree ########################
    public function UpdateDegree(Request $request, $degree_slug){
            $now=date('Y-m-d H:i:s');

            $v=\App\Academic::DegreeValidation(\Request::all());
            if($v->passes()){
                $data['degree_title']=\Request::input('degree_title');
                $new_degree_slug = explode(' ', \Request::input('degree_title'));
                $data['degree_slug'] =strtolower(implode('_', $new_degree_slug));
                $data['degree_code']=\Request::input('degree_code');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

                $degree_info=\DB::table('univ_degree')->where('degree_slug',$degree_slug)->first();
                $program_info=\DB::table('univ_program')->where('program_degree_code',($degree_info->degree_code)?($degree_info->degree_code):'')->first();

                if(!empty($degree_info) && empty($program_info)){

                    try{

                        $success = \DB::transaction(function () use ($data, $degree_slug) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_degree=\DB::connection($this->dbList[$i])->table('univ_degree')->where('degree_slug',$degree_slug)->update($data);

                                if(!$update_degree){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,univ_degree',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                       return \Redirect::to('/academic-settings/home?tab=degree')->with('message',"Degree Updated Successfully!");

                    }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errormessage',"Something wrong in catch !!!");
                    }

                }return \Redirect::back()->with('errormessage',"You can not update this");

            
            }else return \Redirect::to('/academic-settings/home?tab=degree')->withErrors($v->messages());
    }


    ######################## Update Department ########################
    public function UpdateDepartment(Request $request, $department_slug){
        $now=date('Y-m-d H:i:s');

        $v=\App\Academic::DepartmentValidation(\Request::all());
        if($v->passes()){
                $data['department_title']=\Request::input('department_title');
                $new_department_slug = explode(' ', \Request::input('department_title'));
                $data['department_slug'] =strtolower(implode('_', $new_department_slug));
                $data['department_no']= str_pad(\Request::input('department_no'), 2,0,STR_PAD_LEFT);
                $department_no= str_pad(\Request::input('department_no'), 2,0,STR_PAD_LEFT);
                $data['department_dean_chairperson']=\Request::input('department_dean_chairperson');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

            $department_info=\DB::table('univ_department')->where('department_slug',$department_slug)->first();
	    $department_no_info=\DB::table('univ_department')->where('department_no',$department_no)->first();
            $program_info=\DB::table('univ_program')->where('program_department_no',($department_info->department_no)?($department_info->department_no):'')->first();

            if(!empty($department_info) && empty($program_info)&& empty($department_no_info)){

                try{

                    $success = \DB::transaction(function () use ($data, $department_slug) {

                        for($i=0; $i<count($this->dbList); $i++){
                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $update_department=\DB::connection($this->dbList[$i])->table('univ_department')->where('department_slug',$department_slug)->update($data);

                            if(!$update_department){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::TransactionCommit();
                            \App\System::EventLogWrite('update,univ_department',json_encode($data));
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/academic-settings/home?tab=department')->with('message',"Department Updated Successfully!");

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic-settings/home?tab=department')->with('errormessage','Department  Already exists.');
                }

            }return \Redirect::back()->with('errormessage',"You can not update this");

                
        }else return \Redirect::to('/academic-settings/home?tab=department')->withErrors($v->messages());
    }


    ######################## Update Program ########################
    public function UpdateProgram(Request $request, $program_slug){
            $now=date('Y-m-d H:i:s');

            $v=\App\Academic::ProgramValidation(\Request::all());
            if($v->passes()){
                $data['program_title']=\Request::input('program_title');
                $new_program_slug = explode(' ', \Request::input('program_title'));
                $data['program_slug'] =strtolower(implode('_', $new_program_slug));
                $data['program_id']=\Request::input('program_id');
                $data['program_code']=\Request::input('program_code');
                $data['program_head']=\Request::input('program_head');
                $data['program_duration']=\Request::input('program_duration');
                $data['program_duration_type']=\Request::input('program_duration_type');
                $data['program_total_credit_hours']=\Request::input('program_total_credit_hours');
                $data['program_degree_code']=\Request::input('program_degree_code');
                $data['program_department_no']=\Request::input('department_no');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

                $program_info=\DB::table('univ_program')->where('program_slug',$program_slug)->first();

                $student_info=\DB::table('student_basic')->where('program',$program_info->program_id)->first();
                if(!empty($program_info) && empty($student_info)){

                        try{


                            $success = \DB::transaction(function () use ($data, $program_slug) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $update_program=\DB::connection($this->dbList[$i])->table('univ_program')->where('program_slug',$program_slug)->update($data);

                                    if(!$update_program){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('update,univ_program',json_encode($data));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });

                            return \Redirect::to('/academic-settings/home?tab=program')->with('message',"Program Updated Successfully!");

                        }catch(\Exception  $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);

                            return \Redirect::to('/academic-settings/home?tab=program')->with('errormessage','Program  Already exists.');
                        }
                }return \Redirect::back()->with('errormessage',"You can not update this");
         
                
            }else return \Redirect::to('/academic-settings/home?tab=program')->withErrors($v->messages());
    }


    ######################## Update Semester ########################
    public function UpdateSemester(Request $request, $semester_slug){
            $now=date('Y-m-d H:i:s');

            $v=\App\Academic::SemesterValidation(\Request::all());
            if($v->passes()){
                $data['semester_title']=\Request::input('semester_title');
                $data['semester_code']=\Request::input('semester_code');
                $data['semester_sequence']=\Request::input('semester_sequence');
                $data['semester_duration']=\Request::input('semester_duration');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

                $semester_info=\DB::table('univ_semester')->where('semester_slug',$semester_slug)->first();

                $student_info=\DB::table('student_basic')->where('semester',$semester_info->semester_code)->first();

                if(!empty($semester_info) && empty($student_info)){

                        try{

                            $success = \DB::transaction(function () use ($data, $semester_slug) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $update_semester=\DB::connection($this->dbList[$i])->table('univ_semester')->where('semester_slug',$semester_slug)->update($data);

                                    if(!$update_semester){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('update,univ_semester',json_encode($data));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });


                           return \Redirect::to('/academic-settings/home?tab=semester')->with('message',"Semester Updated Successfully!");

                       }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                        }
                }return \Redirect::back()->with('errormessage',"You can not update this");    
                        
            }else return \Redirect::to('/academic-settings/home?tab=semester')->withErrors($v->messages());
    }


    ######################## Update Campus ########################
    public function UpdateCampus(Request $request, $campus_slug){
            $now=date('Y-m-d H:i:s');

            $v=\App\Academic::CampusValidation(\Request::all());
            if($v->passes()){
                $data['campus_title']=\Request::input('campus_title');
                $new_campus_slug = explode(' ', \Request::input('campus_title'));
                $data['campus_slug'] =strtolower(implode('_', $new_campus_slug));
                $data['campus_code']=\App\Academic::GetFirstLetter(\Request::input('campus_title'));
                $data['campus_location']=\Request::input('campus_location');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

                $campus_info=\DB::table('univ_campus')->where('campus_slug',$campus_slug)->first();
                $building_info=\DB::table('univ_building')->where('campus_code',($campus_info->campus_code)?($campus_info->campus_code):'')->first();

                if(!empty($campus_info) && empty($building_info)){

                        try{

                            $success = \DB::transaction(function () use ($data, $campus_slug) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $update_campus=\DB::connection($this->dbList[$i])->table('univ_campus')->where('campus_slug',$campus_slug)->update($data);

                                    if(!$update_campus){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('update,univ_campus',json_encode($data));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });


                            return \Redirect::to('/academic-settings/home?tab=campus')->with('message',"Campus Updated Successfully!");

                        }catch(\Exception $e){
                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::to('/academic-settings/home?tab=campus')->with('errormessage',"Something wrong in catch");
                        }
                        
                }return \Redirect::to('/academic-settings/home?tab=campus')->with('errormessage',"You can not update this");

            }else return \Redirect::to('/academic-settings/home?tab=campus')->withErrors($v->messages());
    }


    ######################## Update Building ########################
    public function UpdateBuilding(Request $request, $building_slug){
            $now=date('Y-m-d H:i:s');

            $v=\App\Academic::BuildingValidation(\Request::all());
            if($v->passes()){
                $data['building_title']=\Request::input('building_title');
                $new_building_slug = explode(' ', trim(\Request::input('building_title')));
                $data['building_slug'] =strtolower(implode('_', $new_building_slug));
                $data['building_code']=  \Request::input('campus_code').str_pad(\Request::input('building_no'), 2,0,STR_PAD_LEFT);
                $data['campus_code']=\Request::input('campus_code');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

                $building_info=\DB::table('univ_building')->where('building_slug',$building_slug)->first();
                $room_info=\DB::table('univ_room')->where('building_code',($building_info->building_code)?($building_info->building_code):'')->first();

                if(!empty($building_info) && empty($room_info)){
                        try{


                            $success = \DB::transaction(function () use ($data, $building_slug) {

                                for($i=0; $i<count($this->dbList); $i++){
                                    $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                    $update_building=\DB::connection($this->dbList[$i])->table('univ_building')->where('building_slug',$building_slug)->update($data);

                                    if(!$update_building){
                                        $error=1;
                                    }
                                }

                                if(!isset($error)){
                                    \App\System::TransactionCommit();
                                    \App\System::EventLogWrite('update,univ_building',json_encode($data));
                                    
                                }else{
                                    \App\System::TransactionRollback();
                                    throw new Exception("Error Processing Request", 1);
                                }
                            });


                            return \Redirect::to('/academic-settings/home?tab=building')->with('message',"Building Updated Successfully!");

                        }catch(\Exception  $e){

                            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                            \App\System::ErrorLogWrite($message);
                            return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage','Buliding  Already exists.');
                        }

                }return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage','Buliding  Already exists.');

            }else return \Redirect::to('/academic-settings/home?tab=building')->withErrors($v->messages());
    }


    ######################## Update Room ########################
    public function UpdateRoom(Request $request, $room_slug){
        $now=date('Y-m-d H:i:s');

        $v=\App\Academic::RoomValidation(\Request::all());
        if($v->passes()){
               

                $data['room_title']=\Request::input('room_title');
                $room_no = \Request::input('room_no');
                $floor_no = \Request::input('floor_no');

                $data['room_slug'] = str_pad(\Request::input('floor_no'), 2,0,STR_PAD_LEFT).'_'.str_pad(\Request::input('room_no'), 2,0,STR_PAD_LEFT).'_'.\Request::input('building_code');

                $data['room_code']= \Request::input('building_code').'-'.str_pad(\Request::input('floor_no'), 2,0,STR_PAD_LEFT).str_pad(\Request::input('room_no'), 2,0,STR_PAD_LEFT);
                $data['room_type']=\Request::input('room_type');
                $data['room_capacity']=\Request::input('room_capacity');
                $data['room_facilities']=\Request::input('room_facilities');
                $data['building_code']=\Request::input('building_code');
                $data['updated_at']=$now;
                $data['updated_by'] = \Auth::user()->user_id;

            $room_info=\DB::table('univ_room')->where('room_slug',$room_slug)->first();

            $schedule_info=\DB::table('univ_class_schedule')->where('class_schedule_room',$room_info->room_code)->first();

            if(!empty($room_info) && empty($schedule_info)){

                    try{


                        $success = \DB::transaction(function () use ($data, $room_slug) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $update_room=\DB::connection($this->dbList[$i])->table('univ_room')->where('room_slug',$room_slug)->update($data);

                                if(!$update_room){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('update,univ_room',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });

                         return \Redirect::to('/academic-settings/home?tab=room')->with('message',"Room Updated Successfully!");

                    }catch(\Exception  $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/academic-settings/home?tab=room')->with('errormessage','Room  Already exists.');
                    }

            }return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage','Buliding  Already exists.');


        }else return \Redirect::to('/academic-settings/home?tab=room')->withErrors($v->messages());
    }

    #------------------ Edit Module End ----------------------------#







    #--------------------- Delete Module Start ----------------------#

    ######################## Degree Delete ########################
    public function DegreeDelete($degree_slug){


        $degree_info=\DB::table('univ_degree')->where('degree_slug',$degree_slug)->first();
        $program_info=\DB::table('univ_program')->where('program_degree_code',($degree_info->degree_code)?($degree_info->degree_code):'')->first();

        if(!empty($degree_info) && empty($program_info)){

            try{

                $success = \DB::transaction(function () use ($degree_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_degree')->where('degree_slug',$degree_slug)->delete();

                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_degree',json_encode($degree_slug));
                        
                    }else{
                        \App\System::TransactionRollback();

                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/academic-settings/home?tab=degree')->with('message',"Degree Deleted Successfully!");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/academic-settings/home?tab=degree')->with('errormessage',"Something Wrong in catch!");
            }

        }else return \Redirect::to('/academic-settings/home?tab=degree')->with('errormessage',"Degree has  program!");
        
    }


    ######################## Department Delete ########################
    public function DepartmentDelete($department_slug){


        $department_info=\DB::table('univ_department')->where('department_slug',$department_slug)->first();
        $program_info=\DB::table('univ_program')->where('program_department_no',($department_info->department_no)?($department_info->department_no):'')->first();

        if(!empty($department_info) && empty($program_info)){

            try{

                $success = \DB::transaction(function () use ($department_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_department')->where('department_slug',$department_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_department',json_encode($department_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/academic-settings/home?tab=department')->with('message',"Department Deleted Successfully!");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            } 

        }else  return \Redirect::to('/academic-settings/home?tab=department')->with('errormessage',"Department have program!");
    }


    ######################## Program Delete ########################
    public function ProgramDelete($program_slug){
        $program_info=\DB::table('univ_program')->where('program_slug',$program_slug)->first();

        $student_info=\DB::table('student_basic')->where('program',$program_info->program_id)->first();
        if(!empty($program_info) && empty($student_info)){

            try{

                $success = \DB::transaction(function () use ($program_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_program')->where('program_slug',$program_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_program',json_encode($program_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                return \Redirect::to('/academic-settings/home?tab=program')->with('message',"Program Deleted Successfully!");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }

        }return \Redirect::to('/academic-settings/home?tab=program')->with('errormessage',"Program can not delete!");


    }


    ######################## Semester Delete ########################
    public function SemesterDelete($semester_slug){
        $semester_info=\DB::table('univ_semester')->where('semester_slug',$semester_slug)->first();

        $student_info=\DB::table('student_basic')->where('semester',$semester_info->semester_code)->first();

        if(!empty($semester_info) && empty($student_info)){

            try{

                $success = \DB::transaction(function () use ($semester_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_semester')->where('semester_slug',$semester_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_semester',json_encode($semester_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/academic-settings/home?tab=semester')->with('message',"Semester Deleted Successfully!");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }
        }return \Redirect::to('/academic-settings/home?tab=semester')->with('errormessage',"Semester can not delete !");

            
    }


    ######################## Campus Delete ########################
    public function CampusDelete($campus_slug){

        $campus_info=\DB::table('univ_campus')->where('campus_slug',$campus_slug)->first();
        $building_info=\DB::table('univ_building')->where('campus_code',($campus_info->campus_code)?($campus_info->campus_code):'')->first();

        if(!empty($campus_info) && empty($building_info)){
            try{

                $success = \DB::transaction(function () use ($campus_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_campus')->where('campus_slug',$campus_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_campus',json_encode($campus_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

               return \Redirect::to('/academic-settings/home?tab=campus')->with('message',"Campus Deleted Successfully!");

           }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/academic-settings/home?tab=campus')->with('errormessage',"Something wrong in catch!");
            }

        }else return \Redirect::to('/academic-settings/home?tab=campus')->with('errormessage',"Campus have building!");

    }


    ######################## Building Delete ########################
    public function BuildingDelete($building_slug){

        $building_info=\DB::table('univ_building')->where('building_slug',$building_slug)->first();
        $room_info=\DB::table('univ_room')->where('building_code',($building_info->building_code)?($building_info->building_code):'')->first();

        if(!empty($building_info) && empty($room_info)){

            try{
                $success = \DB::transaction(function () use ($building_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_building')->where('building_slug',$building_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_building',json_encode($building_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                return \Redirect::to('/academic-settings/home?tab=building')->with('message',"Building Deleted Successfully!");

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }
      
        }else return \Redirect::to('/academic-settings/home?tab=building')->with('errormessage',"Building has room!");

    }


    ######################## Room Delete ########################
    public function RoomDelete($room_slug){

        $room_info=\DB::table('univ_room')->where('room_slug',$room_slug)->first();

        $schedule_info=\DB::table('univ_class_schedule')->where('class_schedule_room',$room_info->room_code)->first();

        if(!empty($room_info) && empty($schedule_info)){

            try{

                $success = \DB::transaction(function () use ($room_slug) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $data=\DB::connection($this->dbList[$i])->table('univ_room')->where('room_slug',$room_slug)->delete();
                        if(!$data){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,univ_room',json_encode($room_slug));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


               return \Redirect::to('/academic-settings/home?tab=room')->with('message',"Room Deleted Successfully!");

           }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::back()->with('errormessage',"Something wrong in catch!");
            }
        }return \Redirect::to('/academic-settings/home?tab=room')->with('errormessage',"Room can not delete!");


    }
        #------------ Delete Module End ---------------#






    /********************************************
    ## CourseCatalougeListAjax 
    *********************************************/

    public function CourseCatalougeListAjax($program){

        $course_catalogue_list = \DB::table('course_catalogue')->where('course_catalogue_program',$program)
        ->leftJoin('course_category','course_catalogue.course_category_slug','=','course_category.course_category_slug')
        ->select('course_catalogue.*','course_category.*')
        ->get();

        $data['course_catalogue_list'] = $course_catalogue_list;
        return \View::make('pages.academic-settings.ajax-catalouge-list',$data);
    }





    /********************************************
    ## StoreDegreePlan 
    *********************************************/

    public function StoreDegreePlan(){

        $now = date('Y-m-d H:i:s');
        $rule = [
                'plan_degree' => 'Required',
                'plan_program' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

        if($v->passes()){


            $uuid = \Uuid::generate(4);


            $degree_plan_slug = \Request::input('plan_degree').'_'.\Request::input('plan_program');

            $degree_plan = [
            'degree_plan_tran_code' => $uuid->string,
            'degree_plan_slug' => $degree_plan_slug,
            'plan_degree' => \Request::input('plan_degree'),
            'plan_program' => \Request::input('plan_program'),
            'trimester_min_credit' => '9',
            'trimester_max_credit' => '15',
            'plan_total_no_course' => \Request::input('total_course'),
            'plan_total_credit' => \Request::input('total_credit'),
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->user_id,
            'updated_by' =>\Auth::user()->user_id,

            ];


            try{

                $success = \DB::transaction(function () use ($degree_plan) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $degree_plan_save=\DB::connection($this->dbList[$i])->table('degree_plans')->insert($degree_plan);

                        if(!$degree_plan_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,degree_plans',json_encode($degree_plan));
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

             }catch(\Exception  $e){
                 $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                 \App\System::ErrorLogWrite($message);

                 return \Redirect::back()->with('message','Degree Plan Already Exist!');

             }

             #-------------degree_plan_detail insert-------------#
            if (\Request::input('catalogue_selected_checkbox')){

                foreach(\Request::input('catalogue_selected_checkbox') as $key => $degree_plan_tran_code)
                {   
                    $query=\DB::table('course_catalogue')->where('course_catalogue_tran_code',$degree_plan_tran_code)->first();

                    $uuid_degree_detail = \Uuid::generate(4);
                    $data['degree_plan_detail_tran_code'] = $uuid_degree_detail->string;
                    $data['degree_plan_tran_code'] = $uuid->string;
                    $data['course_catalogue_tran_code'] = $query->course_catalogue_slug;
                    $data['deatail_no_course']= $query->no_of_courses;
                    $data['deatail_total_credit']= $query->total_credit_hours;
                    $data['created_at'] = $now;
                    $data['updated_at'] = $now;
                    $data['created_by'] = \Auth::user()->user_id;
                    $data['updated_by'] = \Auth::user()->user_id;

                    try{


                        $success = \DB::transaction(function () use ($data) {

                            for($i=0; $i<count($this->dbList); $i++){
                                $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                                $degree_plan_details_save=\DB::connection($this->dbList[$i])->table('degree_plan_detail')->insert($data);

                                if(!$degree_plan_details_save){
                                    $error=1;
                                }
                            }

                            if(!isset($error)){
                                \App\System::TransactionCommit();
                                \App\System::EventLogWrite('insert,degree_plan_detail',json_encode($data));
                                
                            }else{
                                \App\System::TransactionRollback();
                                throw new Exception("Error Processing Request", 1);
                            }
                        });


                    }catch(\Exception $e){
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::back()->with('errormessage',"Something wrong in catch!");
                    }

                }
            }

            return \Redirect::to('/academic/course-settings?tab=degree_plan')->with('message','Degree Plan Has Been Saved Successfully!');



        }
        else return \Redirect::to('/academic/course-settings?tab=degree_plan')->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    ## DeleteDegreePlan 
    *********************************************/

    public function DeleteDegreePlan($degree_plan_tran){

        if(!empty($degree_plan_tran)){
            

            try{


                $success = \DB::transaction(function () use ($degree_plan_tran) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();


                        $delete_degree_plans=\DB::connection($this->dbList[$i])->table('degree_plans')->where('degree_plan_tran_code', $degree_plan_tran)->delete();
                        $delete_degree_plan_detail=\DB::connection($this->dbList[$i])->table('degree_plan_detail')->where('degree_plan_tran_code', $degree_plan_tran)->delete();

                        if(!$delete_degree_plans || !$delete_degree_plan_detail){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('delete,degree_plans',$degree_plan_tran);
                        \App\System::EventLogWrite('delete,degree_plan_detail',$degree_plan_tran);
                        
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

                return \Redirect::to('/academic/course-settings?tab=degree_plan')->with('message',"Degree Plan Deleted Successfully !");

            }catch(\Exception  $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/academic/course-settings?tab=degree_plan')->with('errormessage','Problem On Delete Degree Plan !');
            }

        }else return \Redirect::to('/academic/course-settings?tab=degree_plan')->with('message',"Degree Plan Could Not Found !");
        
    }



    /********************************************
    ## ViewDegreePlan 
    *********************************************/

    public function ViewDegreePlan($degree_plan_tran){

        $data['page_title'] = $this->page_title;

        $degree_plans=\DB::table('degree_plans')
        ->where('degree_plan_tran_code', $degree_plan_tran)
        ->leftJoin('univ_program','univ_program.program_id','=','degree_plans.plan_program')
        ->first();

        if(!empty($degree_plans)){
            $degree_plan_details=\DB::table('degree_plan_detail')
            ->where('degree_plan_tran_code', $degree_plans->degree_plan_tran_code)
            ->leftJoin('course_catalogue','course_catalogue.course_catalogue_slug','=','degree_plan_detail.course_catalogue_tran_code')
            ->leftJoin('course_category','course_category.course_category_slug','=','course_catalogue.course_category_slug')
            ->get();

            $data['degree_plan_details']=$degree_plan_details;
            $data['program']=$degree_plans->program_title;
        }
        

        return \View::make('pages.academic-settings.ajax-degree-plan-view-modal',$data);

    }


    /********************************************
    ## DeleteCourseCatalouge 
    *********************************************/

    public function DeleteCourseCatalouge($course_catalouge_tran){

        if(!empty($course_catalouge_tran)){

            $course_catalouge=\DB::table('course_catalogue')->where('course_catalogue_tran_code', $course_catalouge_tran)->first();

            if(!empty($course_catalouge)){

                $course_update=array(
                    'course_category' => '',
                    );


                try{

                    $success = \DB::transaction(function () use ($course_catalouge_tran, $course_catalouge, $course_update) {

                        for($i=0; $i<count($this->dbList); $i++){

                            $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                            $delete_course_catalouge=\DB::connection($this->dbList[$i])->table('course_catalogue')->where('course_catalogue_tran_code', $course_catalouge_tran)->delete();
                    
                            $update_data=\DB::connection($this->dbList[$i])->table('course_basic')
                            ->where('course_category', $course_catalouge->course_category_slug)
                            ->where('course_program', $course_catalouge->course_catalogue_program)
                            ->update($course_update);

                            
                            if(!$update_data || !$delete_course_catalouge){
                                $error=1;
                            }
                        }

                        if(!isset($error)){
                            \App\System::EventLogWrite('update,course_category', $update_data);
                            \App\System::EventLogWrite('delete,course_catalogue', $course_catalouge_tran);
                            \App\System::TransactionCommit();
                            
                        }else{
                            \App\System::TransactionRollback();
                            throw new Exception("Error Processing Request", 1);
                        }
                    });

                    return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('message',"Course Catalouge Deleted Successfully !");

                }catch(\Exception  $e){
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('errormessage','Problem Deleting Course Catalouge !');
                }

            }else return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('message',"Course Catalouge Not Found !");


        }else return \Redirect::to('/academic/course-settings?tab=course_catalogue')->with('message',"Course Catalouge Catalouge Not Found !");
        
    }



    /*----------------------end of controller--------------------*/
}
