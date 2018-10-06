<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
 

    ########################
    #Student Course Plan
    ########################

    public static function StudentCoursePlan($program, $level, $term){

        $Student_course_plan=\DB::table('course_basic')->where('course_program',$program)->where('level',$level)->where('term',$term)
        ->leftjoin('course_category','course_category.course_category_slug','=','course_basic.course_category')
        ->get();
        return $Student_course_plan;
    }


    ########################
    #PerCreditFee
    ########################

    public static function PerCreditFee($accounts_tution_fee, $fee_type){

        foreach ($accounts_tution_fee as $key => $value) {

               if (($value->accounts_fee_name_slug) == $fee_type) {
                    return $value->accounts_fee_amount;

               }
        }
        return 0;
    }



    #--------------------end-----------------------#
}
