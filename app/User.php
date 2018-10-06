<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon;

// protected $connection = 'mysql_2';

/*******************************
#
## User Model
#
*******************************/

class User extends Authenticatable
{

    // public function __construct(){
    //     $this->dbList =['mysql','mysql_2','mysql_3','mysql_4'];       
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];
*/
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
        'password', 'remember_token',
    ];*/

    protected $table= "users";

    /********************************************
    ## LogInStatusUpdate
    *********************************************/
    public static function LogInStatusUpdate($status){

        $db_name = \App\System::DatabaseName();

        if(\Auth::checK()){

            if($status=='login'){
                $change_status=1;
            }else{
                $change_status=0;
            } 


            try{

                $success = \DB::transaction(function () use ($change_status, $db_name) {

                    for($i=0; $i<count($db_name); $i++){
                        $save_transaction=\DB::connection($db_name[$i])->beginTransaction();

                        $loginstatuschange=\DB::connection($db_name[$i])->table('users')->where('user_id',\Auth::user()->user_id)->update(array('login_status'=>$change_status));

                        if(!$loginstatuschange){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::EventLogWrite('update,users',json_encode($change_status));
                        \App\System::AuthLogWrite($change_status);
                        \App\System::TransactionCommit();

                    }else{
                        \App\System::TransactionRollback();
                        return 0;
                    }
                });

                return 1;

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return 0;
            }




        }
        
    }
}
