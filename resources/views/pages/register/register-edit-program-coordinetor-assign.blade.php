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
      <div class="panel-heading">Class Teacher Information</div>
      <div class="panel-body"><!--info body-->
        <form action="{{url('/register/class-teacher-update',$coordinetor_edit->program_coordinator_tran_code)}}" method="post" enctype="multipart/form-data">

          <?php 
          $department_list =\App\Register::DepartmentList();
          ?>
          <div class="form-group col-md-6">
            <label for="Department">Department</label>
            <select class="form-control department_list" name="department" required>
              @if(!empty($department_list))
              @foreach($department_list as $key => $list)
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_department_no == $list->department_no) ? 'selected' : ''}} value="{{$list->department_no}}">{{$list->department_title}}</option>
              @endforeach
              @endif
            </select>
          </div>

          <div class="form-group col-md-6">
            <label for="Program">Program <span class="required-sign">*</span></label>
            <?php $program_list=\DB::table('univ_program')->where('program_department_no', $coordinetor_edit->program_department_no)->get(); ?>
            <select class="form-control coordinator_program" name="coordinator_program" required>
              @if(!empty($program_list))
              @foreach($program_list as $key => $list)
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->coordinator_program == $list->program_id) ? 'selected' : ''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
              @endforeach
              @endif
            </select> 
          </div>


          <div class="form-group col-md-6">
            <label for="Faculty">Faculty <span class="required-sign">*</span></label>
            <?php 
              $faculty_list=\DB::table('faculty_basic')->where('program', $coordinetor_edit->coordinator_program)->get(); 
            ?>
            <select class="form-control coordinator_faculty_id" name="coordinator_faculty_id" required>
            @if(!empty($faculty_list))
            @foreach($faculty_list as $key => $list)
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->coordinator_faculty_id == $list->faculty_id) ? 'selected' : ''}} value="{{$list->faculty_id}}"> {{$list->first_name}} {{$list->middle_name}} {{$list->last_name}}</option>
              @endforeach
              @endif
            </select> 
          </div>
          
          <div class="form-group col-md-6">
            <label for="Year">Year <span class="required-sign">*</span></label>
            <select class="form-control" name="program_coordinator_year" required>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_year == date('Y')) ? 'selected' : ''}} value="{{date('Y')}}">{{date('Y')}}</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_year == date('Y',strtotime('+1 year'))) ? 'selected' : ''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
            </select>
          </div>



          <div class="form-group col-md-4">
            <label for="Semester">Trimester <span class="required-sign">*</span></label>
            <select class="form-control" name="program_coordinator_semester" required>
              @if(!empty($semester_list))
              @foreach($semester_list as $key => $list)
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_semester == $list->semester_code) ? 'selected' : ''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
              @endforeach
              @endif
            </select> 
          </div>
          <div class="form-group col-md-4">
            <label for="Level">Level <span class="required-sign">*</span></label>
            <select class="form-control" name="program_coordinator_level" required>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_level == '1') ? 'selected' : ''}} value="1">1</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_level == '2') ? 'selected' : ''}} value="2">2</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_level == '3') ? 'selected' : ''}} value="3">3</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_level == '4') ? 'selected' : ''}} value="4">4</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="Term">Term <span class="required-sign">*</span></label>
            <select class="form-control" name="program_coordinator_term" required>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_term == '1') ? 'selected' : ''}} value="1">1</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_term == '2') ? 'selected' : ''}} value="2">2</option>
              <option {{isset($coordinetor_edit) && ($coordinetor_edit->program_coordinator_term == '3') ? 'selected' : ''}} value="3">3</option>
            </select>
          </div>


          <div class="form-group col-md-12" style="margin-top:20px;">
            <div class="pull-right">
              <a href="{{url('/register/class-teacher-assign')}}" class="btn btn-danger" data-toggle="tooltip" title="Camcel Edit">Cancel</a>
              <input type="submit" class="btn btn-primary" data-toggle="tooltip" title="Update Class Teacher" value="Update">
            </div>
          </div>


        </form>
      </div><!--/info body-->
    </div>
  </div>
</div>

@stop
