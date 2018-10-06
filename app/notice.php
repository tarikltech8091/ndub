<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notice extends Model
{
    
    /********************************************
    ## AllNoticeInfo
    *********************************************/
    public static function AllNoticeInfo(){
        $user_id=\Auth::user()->user_id;
	    $student_notice_board=\DB::table('univ_notice_board')
                    ->whereIn('notice_to_type',array('register_to_student','faculty_to_student'))
                    ->orwhereIn('notice_to',array('all',$user_id))
                    ->orderBy('created_at','desc')
                    ->limit(3)
	     			->get();
	     return $student_notice_board;

    }

    /********************************************
    ## FacultyNoticeInfo
    *********************************************/
    public static function FacultyNoticeInfo(){
   
	     $notice_board=\DB::table('univ_notice_board')
	     			->where('notice_to_type','=','register_to_faculty')
                    ->whereIn('notice_to',array('all',\Auth::user()->user_id))
                    ->orderBy('created_at','desc')
                    ->limit(5)
	     			->get();
	     return $notice_board;  
    }



   /* ----------------------------------------*/

}
