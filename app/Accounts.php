<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*******************************
#
## Accounts Model
#
*******************************/

class Accounts extends Model
{
    /********************************************
    ## ApplicantInformationByDefault
    *********************************************/

    public static function ApplicantInformationByDefault(){

    	$all_applicant = \DB::table('applicant_basic')->where('semester', $semester)->where('academic_year', $academic_year)->orderBy('updated_at','desc')->paginate(15);		
    	return $applicant;
    }



    /********************************************
    ## FeeList
    *********************************************/
     public static function FeeList(){

    	$fee_list = \DB::table('all_accounts_fees')->orderBy('accounts_fee_name','asc')->get();		
    	return $fee_list;
    }



}
