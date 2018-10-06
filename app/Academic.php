<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{


    #################### Degree Validation ####################
    public static function DegreeValidation($Request){
        $rules=array(
                    'degree_title'=>'required|max:50',
                    'degree_code'=>'required|max:15',
                  );
       return \Validator::make($Request, $rules);
    }



    #################### Department Validation ####################
    public static function DepartmentValidation($Request){
        $rules=array(
                    'department_title'=>'required|max:50',
                    'department_no'=>'required',
                    'department_dean_chairperson'=>'required|max:100',
                  );
       return \Validator::make($Request, $rules);
    }



    #################### Program Validation ####################
    public static function ProgramValidation($Request){
        $rules=array(
                    'program_title'=>'required|max:50',
                    'program_id'=>'required|numeric',
                    'program_code'=>'required|max:15',
                    'program_head'=>'required|max:100',
                    'program_duration'=>'required|max:8',
                    'program_duration_type'=>'required',
                    'program_total_credit_hours'=>'required',
                    'program_degree_code'=>'required',
                    'department_no'=>'required',
                  );
       return \Validator::make($Request, $rules);
    }



    #################### Semester Validation ####################
    public static function SemesterValidation($Request){
        $rules=array(
                    'semester_title'=>'required|max:20',
                    'semester_code'=>'required|max:15',
                    'semester_sequence'=>'required',
                  );
       return \Validator::make($Request, $rules);
    }



    #################### Campus Validation ####################
    public static function CampusValidation($Request){
        $rules=array(
                    'campus_title'=>'required|max:100',
                    'campus_location'=>'required',
                  );
       return \Validator::make($Request, $rules);
    }




    #################### Building Validation ####################
    public static function BuildingValidation($Request){
        $rules=array(
                    'building_title'=>'required|max:100',
                    'building_no'=>'required',
                    'campus_code'=>'required',
                  );
       return \Validator::make($Request, $rules);
    }




    #################### Room Validation ####################
    public static function RoomValidation($Request){
        $rules=array(
                    'room_title'=>'required|max:100',
                    'room_no'=>'required',
                    'floor_no'=>'required',
                    'room_type'=>'required',
                    'room_capacity'=>'required|max:2',
                    'room_facilities'=>'required|max:200',
                    'building_code'=>'required',
                  );
       return \Validator::make($Request, $rules);
    }


    

    ######################## Degree List ########################
    public static function DegreeList(){
        $degree_list=\DB::table('univ_degree')->orderBy('degree_title','asc')->get();
        return $degree_list;
    }


    ######################## Department List ########################
    public static function DepartmentList(){
        $department_list=\DB::table('univ_department')->orderBy('department_title','asc')->get();
        return $department_list;
    }


    ######################## Program List ########################
    public static function ProgramList(){
        $program_list=\DB::table('univ_program')->orderBy('program_title','asc')->get();
        return $program_list;
    }


    ######################## Semester List ########################
    public static function SemesterList(){
        $semester_list=\DB::table('univ_semester')->orderBy('semester_sequence','asc')->get();
        return $semester_list;
    }


    ######################## Campus List ########################
    public static function CampusList(){
        $campus_list=\DB::table('univ_campus')->orderBy('campus_title','asc')->get();
        return $campus_list;
    }


    ######################## Building List ########################
    public static function BuildingList(){
        $building_list=\DB::table('univ_building')->orderBy('building_title','asc')->get();
        return $building_list;
    }


    ######################## Room List ########################
    public static function RoomList(){
        $room_list=\DB::table('univ_room')->orderBy('room_title','asc')->get();
        return $room_list;
    }

    /********************************************
    ## GetFirstLetter
    *********************************************/
    public static function GetFirstLetter($string){

        $words = explode(" ",$string);
        $acronym = "";

        foreach ($words as $w) {
          $acronym .= $w[0];
        }

        return $acronym;
    }

    #--------------------------------------------------------------#
}
