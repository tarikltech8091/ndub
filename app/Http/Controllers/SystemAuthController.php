<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\System;
use App\Sessions;
use App\Email;
use Cookie;
use Carbon;
use Exception;



/*******************************
#
## SystemAuth Controller
#
*******************************/

class SystemAuthController extends Controller
{
    public function __construct(){
        $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];
     
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
        
    }


    /********************************************
    ## SystemHomePage
    *********************************************/

    public function SystemHomePage(){

        $data['page_title'] = $this->page_title;

        return \View::make('application.pages.home',$data);
    }

    /********************************************
    ## SystemLoginPage 
    *********************************************/

    public function SystemLoginPage(){


        if(\Auth::check()){

            if(!empty(\Auth::user()->user_type)){

                if(\Auth::user()->user_type=="accounts"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/accounts/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="register"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/register/'.\Auth::user()->name_slug.'/home');
                    
                }else if(\Auth::user()->user_type=="academic"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/academic/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="faculty"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="student"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/student/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="systemadmin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/systemadmin/'.\Auth::user()->name_slug.'/home');
                }
                
            }else{

                \Auth::logout();

                return \Redirect::to('/login')->with('errormessage',"Whoops, looks like something went wrong.");
            }
            
        }else{

            $data['page_title'] = $this->page_title;

            return \View::make('pages.login',$data);

        }

    }

    /********************************************
    ## SystemAuthenticationCheck 
    *********************************************/

    public function SystemAuthenticationCheck(){
       $rules = [
       'user_id' =>'required',
       'password'=> 'required',
    // 'g-recaptcha-response' => 'required|captcha',
       ];

       $v = \Validator::make(\Request::all(),$rules);


       if($v->passes()){

        $remember = (\Request::has('remember')) ? true : false;
        $credentials = [
        'user_id' => \Request::input('user_id'),
        'password'=> \Request::input('password'),
        'status'=> '1'
        ];

        if(\Auth::attempt($credentials)){

                if ( \Session::has('pre_login_url') ){ //redirect cureent page after login
                   
                    $url = \Session::get('pre_login_url');
                    \Session::forget('pre_login_url');
                    return \Redirect::to($url);
                }

                else if(\Auth::user()->user_type=="accounts"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/accounts/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="register"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/register/'.\Auth::user()->name_slug.'/home');
                    
                }else if(\Auth::user()->user_type=="academic"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/academic/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="faculty"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/faculty/'.\Auth::user()->name_slug.'/home');

                }else if(\Auth::user()->user_type=="student"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/student/'.\Auth::user()->name_slug.'/home');

                } else if(\Auth::user()->user_type=="systemadmin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/systemadmin/'.\Auth::user()->name_slug.'/home');
                }
                else{

                    \App\User::LogInStatusUpdate("logout");
                    \Auth::logout();

                    return \Redirect::to('/login')->with('errormessage',"Whoops, looks like something went wrong.");
                }

            }else return \Redirect::to('/login')->with('errormessage',"Incorrect combinations.Please try again.");

        }else return  \Redirect::to('/login')->withInput()->withErrors($v->messages());

    }


    /********************************************
    ## SystemLogoutPage 
    *********************************************/

    public function SystemLogoutPage($name_slug){

        if(\Auth::check()){

            $user_info = \App\User::where('user_id',\Auth::user()->user_id)->first();

            if(!empty($user_info) && ($name_slug==$user_info->name_slug)){
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return \Redirect::to('/login');

            }else return \Redirect::to('/login');

        }else return \Redirect::to('/login')->with('errormessage',"Error logout");
        
    }



    /********************************************
    ## ChangePassword 
    *********************************************/

    public function ChangePassword($user_type, $user_id){

        if(\Auth::check()){

            $user_info = \App\User::where('user_id',\Auth::user()->user_id)->first();

            if(!empty($user_info) && ($user_type==$user_info->user_type) && ($user_id==$user_info->user_id)){

                $data['page_title'] = $this->page_title;
                return \View::make('pages.change-password',$data);

            }else return \Redirect::back()->with('message',"You Are Not Logged In !");

        }else return \Redirect::back()->with('message',"You Are Not Logged In !");
        
    }


    /********************************************
    ## UpdatePassword 
    *********************************************/

    public function UpdatePassword($user_type, $user_id){


        if(\Auth::check()){

            $user_info=\DB::table('users')->where('user_id',\Auth::user()->user_id)->where('user_type', \Auth::user()->user_type)->first();

            if(!empty($user_info) && ($user_type==$user_info->user_type) && ($user_id==$user_info->user_id)){

                $rules=array(
                    'current_password' => 'required|min:4',
                    'new_password' => 'required|min:4',
                    'confirm_password' => 'required|min:4',
                    );
                $v=\Validator::make(\Request::all(), $rules);

                if($v->passes()){

                    $new_password=\Request::input('new_password');
                    $confirm_password=\Request::input('confirm_password');
                    if($new_password == $confirm_password){

                        if (\Hash::check(\Request::input('current_password'), \Auth::user()->password)) {
                            $update_password=array(
                                'password' => bcrypt(\Request::input('new_password')),
                                );
                            try{
                                $success = \DB::transaction(function () use ($update_password, $user_info) {

                                    for($i=0; $i<count($this->dbList); $i++){
                                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                                        $update_password_info=\DB::connection($this->dbList[$i])->table('users')->where('user_type',$user_info->user_type)->where('user_id',$user_info->user_id)->update($update_password);

                                        if(!$update_password_info){
                                            $error=1;
                                        }
                                    }

                                    if(!isset($error)){
                                        \App\System::EventLogWrite('update,users',json_encode($update_password));
                                        \App\System::TransactionCommit();

                                    }else{
                                        \App\System::TransactionRollback();
                                        throw new Exception("Error Processing Request", 1);
                                    }
                                });


                                return \Redirect::to('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')->with('message',"Password Changed Successfully ! !");


                            }catch(\Exception $e){
                              $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                              \App\System::ErrorLogWrite($message);

                              return \Redirect::back()->with('message','Info Not Updated !');

                            }

                        }

                    }else return \Redirect::back()->with('message',"Invalid Password Combination !");

                }else return \Redirect::back()->withErrors($v->messages());



            }else return \Redirect::back()->with('message',"You Are Not Logged In !");

        }else return \Redirect::back()->with('message',"You Are Not Logged In !");
        
    }



    /********************************************
    ## SystemImageUpload
    *********************************************/
    public function SystemImageUpload(){

        //passpost 413x531 ,450x558
        $maxwidth = 1500;
        $maxheight = 1500;

        $file = \Request::file('image');  

        $input = array('image' => $file);

        $rules = array(
            'image' => 'image|max:100'
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

            $destinationPath = 'official/';
            $filename = time()."-".$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            return \Response::json(['success' => true, 'file' => ($destinationPath.$filename)]);
        }
    }







    /********************************************
    ## SystemForgotPasswordPage
    *********************************************/

    public function SystemForgotPasswordPage(){
        $data['page_title'] = $this->page_title;
        return \View::make('pages.forgot-password',$data);
    }


    
    /********************************************
    ## SystemForgotPasswordConfirm
    *********************************************/

    public function SystemForgotPasswordConfirm(){
        $data['page_title'] = $this->page_title;
        $user_id=\Request::input('user_id');
        $email=\Request::input('email');
        $users_data= \DB::table('users')
        ->where('user_id','=',$user_id)
        ->first();
        if(!empty($users_data)){
            $users_id=$users_data->user_id;
            $users_type=$users_data->user_type;

            if($users_type=='accounts' || $users_type=='academic' || $users_type=='systemadmin'){
                $employee_email= \DB::table('employee_basic')->where('employee_id','=',$users_id)->first();
                $users_email=$employee_email->email; 
            }elseif($users_type=='register' || $users_type=='faculty'){
                $faculty_email= \DB::table('faculty_basic')->where('faculty_id','=',$users_id)->first();
                $users_email=$faculty_email->email; 
            }else{
                $student_email= \DB::table('student_basic')->where('student_serial_no','=',$users_id)->first();
                $users_email=$student_email->email; 
            }

            \Cookie::queue('petp_reset_password_email', $users_email, 60);
            if(!empty($users_email)){
                if($users_email==$email){
                    $reset_url= url('/forget/password/'.$user_id.'/verify').'?token='.$users_data->remember_token;
                    $a=\App\Email::ForgotPasswordEmail($users_data->user_id, $reset_url);
                    return \Redirect::to('/forget/password')->with('message',"Please Check Email !");

                }else{
                    
                    return \Redirect::back()->with('message',"Email does not match !!!");
                    
                }
            } 
            \Cookie::queue('petp_reset_password_email', null, 60);

        }else{
            return \Redirect::back()->with('message',"Invalid User ID!");
        }        
    }



    /********************************************
    ## SystemForgotPasswordVerification
    *********************************************/

    public function SystemForgotPasswordVerification($user_id){
        $remember_token=$_GET['token'];
        $user_serial_no= \DB::table('users')->where('user_id','=',$user_id)->first();

        $data['user_serial_no']=$user_serial_no;
        $data['remember_token']=$remember_token;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.new-password',$data);
    }
    

    /********************************************
    ## SystemNewPasswordSubmit
    *********************************************/

    public function SystemNewPasswordSubmit(){
        $data['page_title'] = $this->page_title;
        $user_id=\Request::input('user_id');
        $rem_token=\Request::input('token');

        $now=date('Y-m-d H:i:s');
        $rules = [
        'password'=> 'required',
        'confirm_password'  =>'required|same:password',
        ];
        $v = \Validator::make(\Request::all(),$rules);
        if($v->passes()){

            $new_password_data =array(
                'password'=>\Hash::make( \Request::input('password')),
                'updated_at'=>$now,
                );

            try{

                $success = \DB::transaction(function () use ($new_password_data, $user_id, $rem_token) {

                    for($i=0; $i<count($this->dbList); $i++){
                        $save_transaction=\DB::connection($this->dbList[$i])->beginTransaction();
                        if($i == 0){
                            $chnage_passwords=\DB::connection($this->dbList[$i])->table('users')->where('user_id',$user_id)
                            ->where('remember_token',$rem_token)
                            ->update($new_password_data);
                            if(!$chnage_passwords){
                                $error=1;
                            }
                        }else{
                            $chnage_password=\DB::connection($this->dbList[$i])->table('users')->where('user_id',$user_id)
                            ->update($new_password_data);
                            if(!$chnage_password){
                                $error=1;
                            }
                        }
                    }

                    if(!isset($error)){
                        \App\System::EventLogWrite('update,users',json_encode($new_password_data));
                        \App\System::TransactionCommit();
                        var_dump($chnage_password);

                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);
                    }
                });


                return \Redirect::to('/login')->with('message','Password Change Successfully.');


            }catch(\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/login')->with('errormessage','Something wrong, Please try again to send mail!!');
            }


        }else{ 
            return \Redirect::back()->with('message','Please Enter Same Password.');
        }
    }




    

    /***********************************************************************/
}
