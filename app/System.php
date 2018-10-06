<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Classes\CustomLogger;
use Carbon;

/*******************************
#
## System Model
#
*******************************/
class System extends Model{

    /********************************************
    ## DatabaseName 
    *********************************************/

    public static function DatabaseName(){

        $db_name = ['mysql','mysql_2','mysql_3','mysql_4'];

        return $db_name;

    }

    /********************************************
    ## CustomLogWritter 
    *********************************************/

    public static function CustomLogWritter($log_dir,$file_type_name,$message){

        if (!file_exists(storage_path($log_dir)))
           mkdir(storage_path($log_dir), 0777, true);

        $log = new CustomLogger(storage_path($log_dir.'/'.$file_type_name));
        $log = $log->logWrite($message);
        return true;

    }


    /********************************************
    ## AccessLogWrite 
    *********************************************/
    public static function AccessLogWrite(){

        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client_info  = \App\System::getBrowser();
        $client_location  = \App\System::geolocation($client_ip);

        if(\Auth::check())
            $user_id = \Auth::user()->user_id;
        else
            $user_id = 'guest';

        $access_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $access_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $access_country = isset($client_location['country']) ? $client_location['country'] : '' ;
        $db_name = \App\System::DatabaseName();

        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $access_data = [
                                'access_tran_code' => $uuid->string,
                                'access_client_ip' => $client_ip,
                                'access_user_id'   => $user_id,
                                'access_browser'   => $client_info['browser'],
                                'access_platform'  => $client_info['platform'],
                                'access_city'      => $access_city,
                                'access_division'  => $access_division,
                                'access_country'   => $access_country,
                                'access_message'   => $page_title.','.$page_url,
                                'created_at'       => $now,
                                'updated_at'       => $now 

                        ];



         \DB::table('access_log')->insert($access_data);


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_title.'|'.$page_url.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$access_city.'|'.$access_division.'|'.$access_country;

        \App\System::CustomLogWritter("systemlog","access_log",$message);

        return true;

    }



    /********************************************
    ## EventLogWrite 
    *********************************************/
    public static function EventLogWrite($event_type,$event_data){

        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $db_name = \App\System::DatabaseName();

        if(\Auth::check())
            $user_id = \Auth::user()->user_id;
        else
            $user_id = 'guest';


        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $event_insert = [
                                'event_tran_code' => $uuid->string,
                                'event_client_ip' => $client_ip,
                                'event_user_id'   => $user_id,
                                'event_request_url' => $page_url,
                                'event_type'  => $event_type,
                                'event_data'  => $event_data,
                                'created_at'  => $now,
                                'updated_at'  => $now 

                        ];

                $success = \DB::transaction(function () use ($event_insert, $db_name) {

                    for($i=0; $i<count($db_name); $i++){
                        $save_transaction=\DB::connection($db_name[$i])->beginTransaction();

                        $event_log_save=\DB::connection($db_name[$i])->table('event_log')->insert($event_insert);

                        if(!$event_log_save){
                            $error=1;
                        }
                    }

                    if(!isset($error)){
                        \App\System::TransactionCommit();
                    }else{
                        \App\System::TransactionRollback();
                        throw new Exception("Error Processing Request", 1);

                        return false;
                    }
                });



        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$event_type.'|'.$event_data;

        \App\System::CustomLogWritter("eventlog","event_log",$message);

        return true;

    }


    /********************************************
    ## ErrorLogWrite 
    *********************************************/
    public static function ErrorLogWrite($error_data){

        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        

        if(\Auth::check())
            $user_id = \Auth::user()->user_id;
        else
            $user_id = 'guest';

        $db_name = \App\System::DatabaseName();

        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $error_insert = [
                                'error_tran_code' => $uuid->string,
                                'error_client_ip' => $client_ip,
                                'error_user_id'   => $user_id,
                                'error_request_url' => $page_url,
                                'error_data'  => $error_data,
                                'created_at'  => $now,
                                'updated_at'  => $now 

                        ];


        $success = \DB::transaction(function () use ($error_insert, $db_name) {

            for($i=0; $i<count($db_name); $i++){
               $save_transaction=\DB::connection($db_name[$i])->beginTransaction();

                $error_log_save=\DB::connection($db_name[$i])->table('error_log')->insert($error_insert);
                if(!$error_log_save){
                    $error=1;
                }
            }

            if(!isset($error)){
                \App\System::EventLogWrite('insert,event_log',json_encode($error_insert));
                \App\System::TransactionCommit();

            }else{
                \App\System::TransactionRollback();
                return false;
            }
        });



        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$error_data;

        \App\System::CustomLogWritter("errorlog","error_log",$message);

        return true;

    }

   

    /********************************************
    ## get_client_ip 
    *********************************************/
    public static function get_client_ip() {

            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
               $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            if($ipaddress=='::1')
                $ipaddress = getHostByName(getHostName());
            
            return $ipaddress;

    }



    /********************************************
    ## getBrowser 
    *********************************************/

    public static function getBrowser(){ 
            
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)){
            
            preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent,$matches);
           
           $platform = $matches[0];
            
        }
        elseif (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
           
        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif(preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }else{
            $platform = 'Unknown';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        }else{
            $ub='Unknown';
        } 

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= isset($matches['version'][1]) ? $matches['version'][1]:'';
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'browser'   => $bname,
            'version'   => $version,
            'platform'  => $platform,
        );

    } 

    /********************************************
    ## geolocation 
    *********************************************/

    public static function geolocation($ipaddress){

       /* $url = "http://www.geoplugin.net/php.gp?ip=".$ipaddress;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $geo = unserialize($data);*/

        $geolocation = array();

        /*/if(!empty($geo)){

            $geolocation = array(

                'ip' =>$ipaddress,
                'city'=> $geo['geoplugin_city'],
                'division' =>$geo['geoplugin_region'],
                'country' =>$geo['geoplugin_countryName'],
                'latitude' =>$geo['geoplugin_latitude'],
                'longitude'=>$geo['geoplugin_longitude']

                );
            
        }*/


        return $geolocation;
        
    }


     /********************************************
    ## AuthLogWrite 
    *********************************************/
    public static function AuthLogWrite($auth_status){

        $client_ip  = \App\System::get_client_ip();
        $client_ip  = \App\System::get_client_ip();
        $client_info  = \App\System::getBrowser();
        $client_location  = \App\System::geolocation($client_ip);
        

        if(\Auth::check())
            $user_id = \Auth::user()->user_id;
        else
            $user_id = 'guest';

        if($auth_status==1)
            $auth_type = "Log In";
        else $auth_type = "Log Out";

        $auth_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $auth_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $auth_country = isset($client_location['country']) ? $client_location['country'] : '' ;
        $db_name = \App\System::DatabaseName();

        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $auth_insert = [
                             'auth_tran_code' => $uuid->string,
                                'auth_client_ip' => $client_ip,
                                'auth_user_id'   => $user_id,
                                'auth_browser'   => $client_info['browser'],
                                'auth_platform'  => $client_info['platform'],
                                'auth_city'      => $auth_city,
                                'auth_division'  => $auth_division,
                                'auth_country'   => $auth_country,
                                'auth_type'      => $auth_type,
                                'created_at'       => $now,
                                'updated_at'       => $now 

                        ];


            $success = \DB::transaction(function () use ($auth_insert, $db_name) {

                for($i=0; $i<count($db_name); $i++){
                        $save_transaction=\DB::connection($db_name[$i])->beginTransaction();

                    $auth_log_save=\DB::connection($db_name[$i])->table('auth_log')->insert($auth_insert);
                    if(!$auth_log_save){
                        $error=1;
                    }
                }

                if(!isset($error)){
                    \App\System::EventLogWrite('insert,auth_log',json_encode($auth_insert));
                    \App\System::TransactionCommit();

                }else{
                    \App\System::TransactionRollback();
                    return false;

                }
            });



        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$auth_type.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$auth_city.'|'.$auth_division.'|'.$auth_country;

        \App\System::CustomLogWritter("authlog","auth_log",$message);

        return true;

    }


    /********************************************
    ## TransactionCommit 
    *********************************************/
    public static function TransactionCommit(){

        $db_name = \App\System::DatabaseName();

        for($i=0; $i<count($db_name); $i++){

            $save_commit=\DB::connection($db_name[$i])->commit();

        }
        return 1;

    }


    /********************************************
    ## TransactionRollback 
    *********************************************/
    public static function TransactionRollback(){

        $db_name = \App\System::DatabaseName();

        for($i=0; $i<count($db_name); $i++){

            $save_rollback=\DB::connection($db_name[$i])->rollback();

        }
        return 1;
    }
















    
}
