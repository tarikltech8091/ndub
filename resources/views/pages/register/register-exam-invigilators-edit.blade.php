@extends('layout.master')
@section('content')
@include('layout.bradecrumb')


<!--error message*******************************************-->
<div class="row page_row">
  <div class="col-md-12">
    @if($errors->count() > 0 )

    <div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <h6>The following errors have occurred:</h6>
      <ul>
        @foreach( $errors->all() as $message )
        <li>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if(Session::has('message'))
    <div class="alert alert-success" role="alert">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ Session::get('message') }}
    </div> 
    @endif

    @if(Session::has('errormessage'))
    <div class="alert alert-danger" role="alert">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ Session::get('errormessage') }}
    </div>
    @endif

  </div>
</div>
<!--end of error message*************************************-->


<div class="row page_row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">Sutdent Information</div>
      <div class="panel-body"><!--info body-->
        <form action="{{url('/register/exam/invigilators/update/'.$invigilator_edit_info->invigilators_exam_tran_code)}}" method="post" enctype="multipart/form-data">

          <div class="form-group">
            <label for="Exam Date">Exam Date<span class="required-sign">*</span></label>
            <div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
              <input class="form-control" name="invigilators_exam_date" size="16" type="text" value="{{$invigilator_edit_info->invigilators_exam_date}}" readonly>
              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
          </div>

          <div class="form-group">
            <label for="Exam Type">Exam Type<span class="required-sign">*</span></label>
            <select class="form-control time_slot_list" name="invigilators_exam_type">
              <option {{(isset($invigilator_edit_info->invigilators_exam_type) && $invigilator_edit_info->invigilators_exam_type == "2") ? "selected" :''}}  value="2">Mid Term Exam</option>
              <option {{(isset($invigilator_edit_info->invigilators_exam_type) && $invigilator_edit_info->invigilators_exam_type == "3") ? "selected" :''}} value="3">Final Exam</option>
            </select>
          </div>

          <div class="form-group">
            <label for="Exam Time Slot">Exam Time Slot <span class="required-sign">*</span></label>
            <select class="form-control time_slot" name="invigilators_exam_time_slot">
              <option  value="{{$invigilator_edit_info->invigilators_exam_time_slot}}" selected="">{{$invigilator_edit_info->invigilators_exam_time_slot}}</option>
            </select> 

          </div>


          <?php 
          $faculty_list = \DB::table('faculty_basic')->select('faculty_basic.*')->get();
          ?>
          <div class="form-group">
            <label for="Invigilators">Invigilators <span class="required-sign">*</span></label>
            <select name="invigilators_ID[]" class="multipleSelectExample" style="width:100%;" data-placeholder="Select Invigilators ID" multiple>
              <option value="{{$invigilator_edit_info->invigilators_ID}}">{{$invigilator_edit_info->invigilators_ID}}</option>
              @foreach($faculty_list as $key => $faculties)
              <option {{isset($invigilator_edit_info->invigilators_ID) && ($invigilator_edit_info->invigilators_ID == $faculties->faculty_id) ? 'selected' : ''}} value="{{$faculties->faculty_id}}">{{$faculties->faculty_id}} {{$faculties->first_name}} {{$faculties->last_name}}</option>
              @endforeach
            </select>
            
          </div>



          <div class="form-group">
            <label for="Semester">Exam Trimester<span class="required-sign">*</span></label>
            <?php
            $semester_list=\DB::table('univ_semester')->get();
            ?>
            <select class="form-control" name="invigilators_exam_semester" >

              @if(!empty($semester_list))
              @foreach($semester_list as $key => $list)
              <option {{(isset($invigilator_edit_info->semester_code) && ($invigilator_edit_info->semester_code == $list->semester_code)) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="form-group">
            <label for="AcademicYear">Exam Year<span class="required-sign">*</span></label>
            <select class="form-control" name="invigilators_exam_year" >
              <option {{(isset($invigilator_edit_info->invigilators_exam_year) && ($invigilator_edit_info->invigilators_exam_year ==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
              <option {{(isset($invigilator_edit_info->invigilators_exam_year) && ($invigilator_edit_info->invigilators_exam_year ==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
            </select>
          </div>


          <?php 
          $room_list = \DB::table('univ_room')->select('univ_room.*')->get();
          ?>
          <div class="form-group">
            <label for="University Room">University Room <span class="required-sign">*</span></label>
            <select class="form-control" name="invigilators_exam_room">
              @if(!empty($room_list))
              @foreach($room_list as $key => $list)
              <option {{(isset($invigilator_edit_info->invigilators_exam_room) && ($invigilator_edit_info->invigilators_exam_room == $list->room_code)) ? "selected" :''}} value="{{$list->room_code}}">{{$list->room_code}}</option>
              @endforeach
              @endif
            </select> 
          </div>


          <div class="form-group pull-right">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <a href="{{url('/register/schedule/exam-schedule?tab=exam_invigilators')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
            <input type="submit" class="btn btn-primary" data-toggle="tooltip" title="Update Invigilator" value="Update">
          </div>
        </form>

      </div><!--/info body-->
    </div>
  </div>
</div>
@stop