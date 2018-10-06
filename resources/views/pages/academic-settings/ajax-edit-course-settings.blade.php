
@if($form_name=='course_categoty')
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Edit Course Category</h4>
  </div>
  <form action="{{url('/academic/course-category/edit/'.$course_categoty_info->course_category_slug)}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body ">
      <div class="form-group">
        <label>Course Category Name</label>
          <input type="text" name="course_category_name" class="form-control" value="{{$course_categoty_info->course_category_name}}" required/>  
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
      <input type="submit" class="btn btn-success" value="Update">
    </div>
  </form>
@endif


@if($form_name=='course_add')
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Edit Course</h4>
  </div>
  <form action="{{url('/academic/course/edit/'.$course_info->course_slug)}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body ">

      <?php

        $program_list =\App\Applicant::ProgramList();

      ?>
      <div class="form-group">
        <label>Program</label>
        <select class="form-control" name="course_program" required>
          <option value="">Select Program</option>
          @if(!empty($program_list))
            @foreach($program_list as $key => $list)
              <option {{($course_info->course_program == $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_code}}</option>
            @endforeach
          @endif
        </select>
      </div>
      <div class="form-group">
        <label>Course Title</label>
        <input type="text" name="course_title" class="form-control" value="{{$course_info->course_title}}"  required/>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label>Course Code</label>
            <input type="text" name="course_code" class="form-control" value="{{$course_info->course_code}}"  required/> 
        </div>
        <div class="form-group col-md-6">
          <label>Course Type</label>
            <select name="course_type" class="form-control"   required>
              <option value="">Select Type</option>
              <option {{($course_info->course_type =='Theory') ? 'selected':''}} value="Theory">Theory</option>
              <option {{($course_info->course_type =='Lab work') ? 'selected':''}} value="Lab work">Lab work</option>
              <option {{($course_info->course_type =='Field work') ? 'selected':''}} value="Field work">Field work</option>
            </select> 
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label>Credit Hours</label>
            <input type="text" name="credit_hours" class="form-control"  value="{{$course_info->credit_hours}}" required/> 
        </div>
        <div class="form-group col-md-3">
          <label>Year</label>
            <input type="number" min="1" max="4" name="level" step="1" class="form-control" value="{{$course_info->level}}" required/>   
        </div>
        <div class="form-group col-md-3">
          <label>Trimester</label>
            <input type="number" min="1" max="3" name="term" step="1" class="form-control"  value="{{$course_info->term}}" required/>  
        </div>
      </div>
      

<!--       <div class="row">
        <div class="form-group col-md-6">
          <label>Per Credit Fee</label>
            <input type="text" name="per_credit_fees_amount" class="form-control" value="{{$course_info->per_credit_fees_amount}}"  required/> 
        </div>
        <div class="form-group col-md-6">
          <label>Total Credit Fee</label>
            <input type="text" name="total_credit_fees_amount" class="form-control" value="{{$course_info->total_credit_fees_amount}}"  required/> 
        </div>
      </div> -->
      
      <div class="form-group">
        <label>Course Description</label>
        <textarea class="form-control" name="course_description" rows="4" required>{{$course_info->course_description}}</textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
      <input type="submit" class="btn btn-success" value="Update">
    </div>
  </form>
@endif
     
@if($form_name=='course_plan')
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Edit Course Plan</h4>
  </div>
  <form action="{{url('/academic/course-catalogue/edit/'.$course_catalogue_info->course_catalogue_slug)}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body ">
      
      <div class="row">
        <div class="form-group col-md-6">
          <label>Program</label>
          <select class="form-control" name="catalouge_program" required>
            <option value="">Select Program</option>
            @if(!empty($program_list))
              @foreach($program_list as $key => $list)
                <option {{($list->program_id==$course_catalogue_info->course_catalogue_program) ? 'selected' :''}}  value="{{$list->program_id}}">{{$list->program_code}}</option>
              @endforeach
            @endif
          </select>
        </div>

        <div class="forn-group col-md-6">
        <label>Course Category</label>
          <select class="form-control" name="catalouge_category" required>
            <option value="">Select Category</option>
            @if(!empty($all_course_category))
              @foreach($all_course_category as $key => $category)
              <option {{($category->course_category_slug==$course_catalogue_info->course_category_slug) ? 'selected' :''}}  value="{{$category->course_category_slug}}">{{$category->course_category_name}}</option>
              @endforeach
            @endif
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label>No. of Courses</label>
            <input type="text" name="no_of_courses" class="form-control" value="{{$course_catalogue_info->no_of_courses}}"  required/> 
        </div>
        <div class="form-group col-md-6">
          <label>Total Credit Hours</label>
            <input type="text" name="total_credit_hours" class="form-control" value="{{$course_catalogue_info->total_credit_hours}}"  required/> 
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
      <input type="submit" class="btn btn-success" value="Update">
    </div>
  </form>
@endif