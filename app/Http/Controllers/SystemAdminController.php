<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\System;
use Carbon;
use Exception;

class SystemAdminController extends Controller
{
        
    public $dbList;

    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }


    
    /********************************************
    ## SystemAdminHomePage
    *********************************************/

    public function SystemAdminHomePage(){

        $data['page_title'] = $this->page_title;

        $today = date('Y-m-d');
        $today_count=0;
        $weekly_count=0;
        $monthly_count=0;
        $yearly_count=0;


        $today_count=\DB::table('access_log')
             ->where('created_at','like',$today."%")
             ->select('access_client_ip')
             ->groupBy('access_client_ip')
             ->get();

             
        $data['today_count']=count($today_count);

        $from = date('Y-m-d')." 00:00:00";
        $last_week = date("Y-m-d", strtotime("-1 week"))." 23:59:59";
        
        $weekly_count=\DB::table('access_log')
             ->whereBetween('access_log.created_at',array($last_week,$from))
             ->select('access_client_ip')
             ->groupBy('access_client_ip')
             ->get();
        $data['weekly_count']=count($weekly_count);



        $last_month = date("Y-m-d", strtotime("-1 month"))." 23:59:59";
        $monthly_count=\DB::table('access_log')
                     ->whereBetween('access_log.created_at',array($last_month,$from))
                     ->select('access_client_ip')
                    ->groupBy('access_client_ip')->get();
        $data['monthly_count']=count($monthly_count);


        $last_year= date("Y-m-d", strtotime("-1 year"))." 23:59:59";
        $yearly_count=\DB::table('access_log')
                     ->whereBetween('access_log.created_at',array($last_year,$from))
                     ->select('access_client_ip')
                    ->groupBy('access_client_ip')->get();
        $data['yearly_count']=count($yearly_count);



        return \View::make('pages.system-admin.system-admin-home',$data);
    }
    

    /********************************************
    ## AccessLogListPage
    *********************************************/

    public function AccessLogs(){
        
        
        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $access_log_list = \DB::table('access_log')
                                ->whereBetween('access_log.created_at',array($form_search_date,$to_search_date))
                                ->leftJoin('users','access_log.access_user_id','=','users.user_id')
                                ->select('access_log.*','users.name','users.user_id')
                                ->orderBy('access_log.created_at','desc')
                                ->paginate(10);

            $access_log_list->setPath(url('/system-admin/access-logs'));

            $pagination = $access_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
            

         } 
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $access_log_list=\DB::table('access_log')->where('access_log.created_at','like',$today."%")
                            ->leftJoin('users','access_log.access_user_id','=','users.user_id')
                            ->select('access_log.*','users.name','users.user_id')
                            ->orderBy('access_log.created_at','desc') 
                            ->paginate(10);
            $access_log_list->setPath(url('/system-admin/access-logs'));
            $pagination = $access_log_list->render();
            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.access-log',$data);

    }



    /********************************************
    ## ErrorLogListPage
    *********************************************/

    public function ErrorLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $error_log_list = \DB::table('error_log')
                                ->whereBetween('error_log.created_at',array($form_search_date,$to_search_date))
                                ->leftJoin('users','error_log.error_user_id','=','users.user_id')
                                ->select('error_log.*','users.name','users.user_id')
                                ->orderBy('error_log.created_at','desc')
                                ->paginate(10);

            $error_log_list->setPath(url('/system-admin/error-logs'));

            $error_pagination = $error_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;

            
            

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $error_log_list=\DB::table('error_log')->where('error_log.created_at','like',$today."%")
                            ->leftJoin('users','error_log.error_user_id','=','users.user_id')
                            ->select('error_log.*','users.name','users.user_id')
                            ->orderBy('error_log.created_at','desc')
                            ->paginate(10);
            $error_log_list->setPath(url('/system-admin/error-logs'));
            $error_pagination = $error_log_list->render();
            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.error-log',$data);
    }






    /********************************************
    ## EventLogListPage
    *********************************************/

    public function EventLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $event_log_list = \DB::table('event_log')->whereBetween('event_log.created_at',array($form_search_date,$to_search_date))
                              ->leftJoin('users','event_log.event_user_id','=','users.user_id')
                              ->select('event_log.*','users.name','users.user_id')
                              ->orderBy('event_log.created_at','desc')
                              ->paginate(10);

            $event_log_list->setPath(url('/system-admin/event-logs'));

            $event_pagination = $event_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;

            
            

         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $event_log_list=\DB::table('event_log')->where('event_log.created_at','like',$today."%")
                            ->leftJoin('users','event_log.event_user_id','=','users.user_id')
                            ->select('event_log.*','users.name','users.user_id')
                            ->orderBy('event_log.created_at','desc')
                            ->paginate(10);
            $event_log_list->setPath(url('/system-admin/event-logs'));
            $event_pagination = $event_log_list->render();
            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.event-log',$data);
    }





    /********************************************
    ## AuthLogListPage
    *********************************************/

    public function AuthLogs(){

        /*------------------------------------Get Request--------------------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $auth_log_list = \DB::table('auth_log')->whereBetween('auth_log.created_at',array($form_search_date,$to_search_date))
                              ->leftJoin('users','auth_log.auth_user_id','=','users.user_id')
                              ->select('auth_log.*','users.name','users.user_id')
                              ->orderBy('auth_log.created_at','desc')
                              ->paginate(10);

            $auth_log_list->setPath(url('/system-admin/auth-logs'));

            $auth_pagination = $auth_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;


         }
        /*------------------------------------/Get Request--------------------------------------------*/
        else{
            $today = date('Y-m-d');
            $auth_log_list=\DB::table('auth_log')->where('auth_log.created_at','like',$today."%")
                            ->leftJoin('users','auth_log.auth_user_id','=','users.user_id')
                            ->select('auth_log.*','users.name','users.user_id')
                            ->orderBy('auth_log.created_at','desc')
                            ->paginate(10);
            $auth_log_list->setPath(url('/system-admin/auth-logs'));
            $auth_pagination = $auth_log_list->render();
            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('pages.system-admin.auth-log',$data);
    }






     #-----------------------Register Student Account---------------#
    /********************************************
    ## Register Student Account
    *********************************************/


    public function SystemadminStudentAccount(){

        $student_list = \DB::table('student_basic')->where('student_status',1)
        ->leftJoin('univ_program','univ_program.program_id','=','student_basic.program')
        ->select('student_basic.*','univ_program.*')
        ->orderBy('student_basic.created_at','desc')
        ->paginate(10);
        $student_list->setPath(url('/systemadmin/student-account'));
        $student_list_pagination = $student_list->render();
        $data['student_list_pagination'] = $student_list_pagination;

        $data['student_list']= $student_list;

        if(isset($_GET['student_serial_no']) && ($_GET['student_serial_no']!=0)){
            
            $student_serial_no=$_GET['student_serial_no'];

            $student_basic=\DB::table('student_basic')->where('student_serial_no',$student_serial_no)->where('student_status',1)
            ->leftJoin('univ_program','student_basic.program','like','univ_program.program_id')
            ->leftJoin('univ_department','univ_program.program_department_no','like','univ_department.department_no')
            ->leftJoin('univ_semester','student_basic.semester','like','univ_semester.semester_code')
            ->leftJoin('student_personal','student_basic.student_tran_code','like','student_personal.student_tran_code')
            ->first();
            $data['student_info']=$student_basic;
        
        }

        $data['page_title']= $this->page_title;
        return \View::make('pages.system-admin.systemadmin-student-account-create',$data);
    }



    /********************************************
    ## Student Registration
    *********************************************/

    public function StudentRegistration(Request $request){
        $validator=\Validator::make($request->all(),[
            'password'=>'required|min:4',
            ]);

        if($validator->passes()){
            
            $now=date('Y-m-d H:i:s');
            $student_serial_no = \Request::input('student_serial_no');
            
            $student_info=\DB::table('student_basic')->where('student_serial_no', $student_serial_no)
            ->first();

            $name=$student_info->first_name.' '.$student_info->middle_name.' '.$student_info->last_name;
            $name_slug =strtolower(str_replace(' ','.', $name));

            $student_account_creation=array(
                'user_id' => $student_serial_no,
                'name' => $name,
                'name_slug' => $name_slug,
                'user_type' => 'student',
                'password' => bcrypt(\Request::input('password')),
                'login_status' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                );

            $student_status_update_data=array(
                'student_status' => 2,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );

            try{

                $success = \DB::transaction(function () use ($student_account_creation,$student_status_update_data, $student_info) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        $save_registration=\DB::connection($this->dbList[$i])->table('users')->insert($student_account_creation);
                        $student_status_update=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_tran_code',$student_info->student_tran_code)->update($student_status_update_data);

                        if(!$save_registration || !$student_status_update){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,users',json_encode($student_account_creation));
                        \App\System::EventLogWrite('update,student_basic',json_encode($student_status_update_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('/systemadmin/student-account')->with('errormessage',"Something wrong in catch !!!"); 

            }
            

            return \Redirect::to('/systemadmin/student-account')->with('message',"User Registration Successfull!"); 
        } else return back()->withErrors($validator); 
    }



     /********************************************
    ## Faculty Account Create
    *********************************************/

    public function FacultyAccountCreate(){

        $faculty_list = \DB::table('faculty_basic')->where('faculty_status',1)
        ->leftJoin('univ_program','univ_program.program_id','=','faculty_basic.program')
        ->select('faculty_basic.*','univ_program.*')
        ->orderBy('faculty_basic.created_at','desc')
        ->paginate(10);
        $faculty_list->setPath(url('/system-admin/faculty-account'));
        $faculty_list_pagination = $faculty_list->render();
        $data['faculty_list_pagination'] = $faculty_list_pagination;
        $data['faculty_list']= $faculty_list;

        if(isset($_GET['faculty_id']) && ($_GET['faculty_id']!=0)){
            
            $faculty_id=$_GET['faculty_id'];

            $faculty_basic=\DB::table('faculty_basic')->where('faculty_id',$faculty_id)->where('faculty_status',1)
            ->leftJoin('univ_program','faculty_basic.program','=','univ_program.program_id')
            ->leftJoin('univ_department','univ_program.program_department_no','=','univ_department.department_no')

            ->first();
            $data['faculty_info']=$faculty_basic;
        
        }

        $data['page_title']= $this->page_title;
        return \View::make('pages.system-admin.systemadmin-faculty-account-create',$data);
    }


    /********************************************
    ## Faculty Registration
    *********************************************/

    public function FacultyRegistration(Request $request){
        $validator=\Validator::make($request->all(),[
            'password'=>'required|min:4',
            'user_type'=>'required',
            'user_role'=>'required',
            ]);

        if($validator->passes()){
            
            $now=date('Y-m-d H:i:s');
            $faculty_id = \Request::input('faculty_id');
            
            $faculty_info=\DB::table('faculty_basic')->where('faculty_id', $faculty_id)
            ->first();


            $name = $faculty_info->first_name.' '.$faculty_info->middle_name.' '.$faculty_info->last_name;
            $name_slug = strtolower(str_replace(' ','.', $name));

            $faculty_user_account=array(
                'user_id' => $faculty_id,
                'name' => $name,
                'name_slug' => $name_slug,
                'user_type' => \Request::input('user_type'),
                'user_role' => \Request::input('user_role'),
                'password' => bcrypt(\Request::input('password')),
                'login_status' => 0,
                'status' => 1,
                'created_at'=> $now,
                'updated_at'=> $now,
                );

            $faculty_status_update_data=array(
                'faculty_status' => 2,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );

            try{

                $success = \DB::transaction(function () use ($faculty_user_account,$faculty_status_update_data, $faculty_info) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $save_registration=\DB::connection($this->dbList[$i])->table('users')->insert($faculty_user_account);
                        $faculty_status_update=\DB::connection($this->dbList[$i])->table('faculty_basic')->where('faculty_tran_code',$faculty_info->faculty_tran_code)->update($faculty_status_update_data);
                        if(!$save_registration || !$faculty_status_update){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,users',json_encode($faculty_user_account));
                        \App\System::EventLogWrite('update,faculty_basic',json_encode($faculty_status_update_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }
            


            return \Redirect::to('/system-admin/faculty-account')->with('message',"Faculty Registration Successfull!"); 
        } else return back()->withErrors($validator);
    }




    /********************************************
    ## SystemUsers
    *********************************************/

    public function SystemUsers(){
        $data['page_title']= $this->page_title;
        if(isset($_GET['search_id']) && !empty($_GET['search_id'])){
            $users=\DB::table('users')->where('user_id',$_GET['search_id'])->first();
        }else{

            $users=\DB::table('users')->paginate(10);
            $users->setPath(url('/system-admin/system-users'));
            $users_pagination = $users->render();
            $data['users_pagination'] = $users_pagination;
        }


        $data['users'] = $users;
        
        return \View::make('pages.system-admin.systemadmin-system-users',$data);
    }


    /********************************************
    ## SystemUsers Change Status
    *********************************************/

    public function SystemUsersChangeStatus ($id, $status){

        $now=date('Y-m-d H:i:s');
        
        $user_info=\DB::table('users')->where('user_id', $id)
        ->first();
        if(!empty($user_info) && !empty($status)){

            $user_type=$user_info->user_type;

            $change_user_status=array(
                'status' => $status,
                'updated_at'=> $now,
                );

            if($status == 1){
                $change_status=2;
            }elseif($status == -1){
                $change_status=-5;
            }

            $employee_status_update_data=array(
                'employee_status' => $change_status,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );

            $faculty_status_update_data=array(
                'faculty_status' => $change_status,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );

            $student_status_update_data=array(
                'student_status' => $change_status,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );


            try{

                $success = \DB::transaction(function () use ($change_user_status,$status, $id, $user_type, $employee_status_update_data, $faculty_status_update_data, $student_status_update_data) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $user_status_update=\DB::connection($this->dbList[$i])->table('users')->where('user_id',$id)->update($change_user_status);

                        if($user_type == 'student'){
                            $student_status_update=\DB::connection($this->dbList[$i])->table('student_basic')->where('student_serial_no',$id)->update($student_status_update_data);

                            if(!$student_status_update){
                                $error=1;
                            }

                        }elseif(($user_type == 'faculty')){
                            $faculty_status_update=\DB::connection($this->dbList[$i])->table('faculty_basic')->where('faculty_id',$id)->update($faculty_status_update_data);

                            if(!$faculty_status_update){
                                $error=1;
                            }

                        }else{
                            $employee_status_update=\DB::connection($this->dbList[$i])->table('employee_basic')->where('employee_id',$id)->update($employee_status_update_data);

                            if(!$employee_status_update){
                                $error=1;
                            }

                        }

                        if(!$user_status_update){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('update,users',json_encode($change_user_status));
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
            
            return \Redirect::back()->with('message',"Change Status Successfully!"); 
        } else return back()->withErrors($validator->messages());
    }




    /********************************************
    ## Employee Account Create
    *********************************************/

    public function EmployeeAccountCreate(){

        $employee_list = \DB::table('employee_basic')->where('employee_status',1)
        ->select('employee_basic.*')
        ->get();

        $data['employee_list']= $employee_list;

        if(isset($_GET['employee_id']) && ($_GET['employee_id']!=0)){
            
            $employee_id=$_GET['employee_id'];

            $employee_basic=\DB::table('employee_basic')->where('employee_id',$employee_id)->where('employee_status',1)->first();
            $data['employee_info']=$employee_basic;
        
        }

        $data['page_title']= $this->page_title;
        return \View::make('pages.system-admin.systemadmin-employee-account-create',$data);
    }



    /********************************************
    ## Employee Registration
    *********************************************/

    public function EmployeeRegistration(Request $request){
        $validator=\Validator::make($request->all(),[
            'password'=>'required|min:4',
            'user_type'=>'required',
            'user_role'=>'required',
            ]);

        if($validator->passes()){
            
            $now=date('Y-m-d H:i:s');
            $employee_id = \Request::input('employee_id');
            
            $employee_info=\DB::table('employee_basic')->where('employee_id', $employee_id)
            ->first();


            $name = $employee_info->first_name.' '.$employee_info->middle_name.' '.$employee_info->last_name;
            $name_slug = strtolower(str_replace(' ','.', $name));

            $employee_user_account=array(
                'user_id' => $employee_id,
                'name' => $name,
                'name_slug' => $name_slug,
                'user_type' => \Request::input('user_type'),
                'user_role' => \Request::input('user_role'),
                'password' => bcrypt(\Request::input('password')),
                'login_status' => 0,
                'status' => 1,
                'created_at'=> $now,
                'updated_at'=> $now,
                );

            $employee_status_update_data=array(
                'employee_status' => 2,
                'updated_at' => $now,
                'updated_by' => \Auth::user()->user_id,
                );

            try{

                $success = \DB::transaction(function () use ($employee_user_account,$employee_status_update_data, $employee_info) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();

                        $save_registration=\DB::connection($this->dbList[$i])->table('users')->insert($employee_user_account);
                        $faculty_status_update=\DB::connection($this->dbList[$i])->table('employee_basic')->where('employee_tran_code',$employee_info->employee_tran_code)->update($employee_status_update_data);
                        if(!$save_registration || !$faculty_status_update){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                        \App\System::EventLogWrite('insert,users',json_encode($employee_user_account));
                        \App\System::EventLogWrite('update,faculty_basic',json_encode($employee_status_update_data));
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });
                

            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
            }
            


            return \Redirect::to('/system-admin/employee-account')->with('message',"Employee Registration Successfull!"); 
         } else return back()->withErrors($validator->messages());
    }


    #-------------------------------end-----------------------------#







    public function TestingRegistration(){

        $now = \Carbon::now(); // Where is this use? 

        $faculty_user_account = [
            'created_at'=> $now,
            'updated_at'=> $now,
        ];


        $success = \DB::transaction(function () use ($faculty_user_account) {
            $aa= array();
            for($i=0; $i<count($this->dbList); $i++){
                $view=\DB::connection($this->dbList[$i])->table('users')->insert($faculty_user_account);
                if(!$view){
                    $error=1;
                }
            }

            if(!isset($error)){
                \DB::commit();
                return \Redirect::to('/')->with('message',"Faculty Registration Successfull!"); 
            }else{
                \DB::rollback();
                // return \Redirect::back()->with('errormessage', 'Unable to save.'); 
            }

        });

        if (!isset($error)) {

            return \Redirect::to('/')->with('errormessage', 'Unable to save.'); 
        }

    }










}
