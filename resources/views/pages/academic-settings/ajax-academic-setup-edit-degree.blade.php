

@if($setting_type=='degree')

<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Degree: <span style="color:#5bc0de;">{{$edit_degree[0]->degree_title}}</span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{URL::route('Update Degree',$edit_degree[0]->degree_slug)}}" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Degree Title</label>
				<input type="text" name="degree_title" class="form-control" value="{{$edit_degree[0]->degree_title}}" />	
			</div>
			<div class="form-group">
				<label>Degree Code</label>
				<input type="text" name="degree_code" class="form-control" value="{{$edit_degree[0]->degree_code}}" />	
			</div>


			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>
	</div>
</div>


@elseif($setting_type=='department')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Department: <span style="color:#5bc0de;">{{$edit_department[0]->department_title}}</span></h4>
	</div><br>
	<div class="col-md-12">

		<form action="{{URL::route('Update Department',$edit_department[0]->department_slug)}}" method="post" enctype="multipart/form-data">

			<div class="form-group">
				<label>Department Title</label>
				<input type="text" name="department_title" class="form-control" value="{{$edit_department[0]->department_title}}" />	
			</div>
			<div class="form-group">
				<label>Department Code</label>
				<input type="number" name="department_no" min="1" max="30" class="form-control" value="{{$edit_department[0]->department_no}}" />	
			</div>
			<div class="form-group">
				<label>Department Dean/Chairperson</label>
				<input type="text" name="department_dean_chairperson" class="form-control" value="{{$edit_department[0]->department_dean_chairperson}}" />	
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>
	</div>
</div>



@elseif($setting_type=='program')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Program: <span style="color:#5bc0de;">{{$edit_program[0]->program_title}}</span></h4>
	</div><br>
	<div class="col-md-12">

		<form action="{{URL::route('Update Program',$edit_program[0]->program_slug)}}" method="post" enctype="multipart/form-data">

			<div class="form-group">
				<label>Program Title</label>
				<input type="text" name="program_title" class="form-control" value="{{$edit_program[0]->program_title}}" />	
			</div>
			<div class="form-group">
				<label>Program ID</label>
				<input type="text" name="program_id" class="form-control" value="{{$edit_program[0]->program_id}}" />	
			</div>
			<div class="form-group">
				<label>Program Code</label>
				<input type="text" name="program_code" class="form-control" value="{{$edit_program[0]->program_code}}" />	
			</div>
			<div class="form-group">
				<label>Program Head</label>
				<input type="text" name="program_head" class="form-control" value="{{$edit_program[0]->program_head}}" />	
			</div>
			<div class="row">
			<div class="form-group col-md-4">
				<label>Program Duration</label>
				<input type="number" name="program_duration" class="form-control" value="{{$edit_program[0]->program_duration}}" />	
			</div>
			<div class="form-group col-md-4">
				<label>Duration Type</label>
				<select name="program_duration_type" class="form-control">
					<option value="year">Years</option>
					<!-- <option value="month">Months</option> -->
				</select>	
			</div>
			<div class="form-group col-md-4">
				<label>Total Credit</label>
				<input type="number" name="program_total_credit_hours" class="form-control" value="{{$edit_program[0]->program_total_credit_hours}}" />	
			</div>
			</div>

			<div class="form-group">
				<label>Degree</label>
				<?php 
					$degree_list=\App\Academic::DegreeList();
				?>
				<select name="program_degree_code" class="form-control">
				@foreach($degree_list as $key => $degree)

					@if($degree->degree_code==$edit_program[0]->program_degree_code)
					<option value="{{$degree->degree_code}}" selected>{{$degree->degree_title}}</option>
					@else
						<option value="{{$degree->degree_code}}">{{$degree->degree_title}}</option>
					@endif
					
				@endforeach
				</select>	
			</div>

			<div class="form-group">
				<label>Department</label>
				<select name="department_no" class="form-control">
				<?php 
					$department_list=\App\Academic::DepartmentList();
				?>
				@foreach($department_list as $key => $department)
					@if($department->department_no==$edit_program[0]->program_department_no)
					<option value="{{$department->department_no}}" selected>{{$department->department_title}}</option>
					@else
					<option value="{{$department->department_no}}">{{$department->department_title}}</option>
					@endif
				@endforeach
				</select>	
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>
	</div>
</div>



@elseif($setting_type=='semester')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Trimester: <span style="color:#5bc0de;">{{$edit_semester[0]->semester_title}}</span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{URL::route('Update Semester',$edit_semester[0]->semester_slug)}}" method="post" enctype="multipart/form-data">

			<div class="form-group">
				<label>Trimester Title</label>
				<input type="text" name="semester_title" class="form-control" value="{{$edit_semester[0]->semester_title}}" />	
			</div>
			<div class="form-group">
				<label>Trimester Code</label>
				<input type="text" name="semester_code" class="form-control" value="{{$edit_semester[0]->semester_code}}" />	
			</div>
			<div class="form-group">
				<label>Trimester Sequence</label>
				<input type="text" name="semester_sequence" class="form-control" value="{{$edit_semester[0]->semester_sequence}}" />
			</div>
			<div class="form-group">
				<label>Trimester Duration</label>
				<input name="semester_duration" class="form-control" value="{{$edit_semester[0]->semester_duration}}" />
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>
	</div>
</div>


@elseif($setting_type=='campus')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Campus: <span style="color:#5bc0de;">{{$edit_campus[0]->campus_title}}</span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{URL::route('Update Campus',$edit_campus[0]->campus_slug)}}" method="post" enctype="multipart/form-data">

			<div class="form-group">
				<label>Campus Title</label>
				<input type="text" name="campus_title" class="form-control" value="{{$edit_campus[0]->campus_title}}" />	
			</div>
			<div class="form-group">
				<label>Campus Location</label>
				<textarea name="campus_location" class="form-control">{{$edit_campus[0]->campus_location}}</textarea>
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>

	</div>
</div>


@elseif($setting_type=='building')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Building: <span style="color:#5bc0de;">{{$edit_building[0]->building_title}}</span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{URL::route('Update Building',$edit_building[0]->building_slug)}}" method="post" enctype="multipart/form-data">

			<div class="form-group">
				<label>Building Title</label>
				<input type="text" name="building_title" class="form-control" value="{{$edit_building[0]->building_title}}" />	
			</div>
			<div class="form-group">
				<label>Building No.</label>
				<input type="number" min="1" max="20" name="building_no" class="form-control" value="{{(int)substr($edit_building[0]->building_code,-2)}}" />	
			</div>

			<div class="form-group">
				<label>Campus</label>
				<select name="campus_code" class="form-control">
				<?php 
					$campus_list=\App\Academic::CampusList();
				?>
				@foreach($campus_list as $key => $campus)
					@if($campus->campus_code==$edit_building[0]->campus_code)
					<option value="{{$campus->campus_code}}" selected>{{$campus->campus_title}}</option>
					@else
					<option value="{{$campus->campus_code}}">{{$campus->campus_title}}</option>
					@endif
				
				@endforeach
				</select>
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>

	</div>
</div>


@elseif($setting_type=='room')
<div class="row">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Room: <span style="color:#5bc0de;">{{$edit_room[0]->room_title}}</span></h4>
	</div><br>
	<div class="col-md-12">
		<form action="{{URL::route('Update Room',$edit_room[0]->room_slug)}}" method="post" enctype="multipart/form-data">
				<?php $building_list= \App\Academic::BuildingList();?>
			<div class="form-group">
				<label>Building Name</label>
				<select name="building_code"  class="form-control" required>
					<option value="">Select Building</option>
					@if(!empty($building_list))
						@foreach($building_list as $key => $building)
						<option {{($edit_room[0]->building_code==$building->building_code) ? 'selected' :''}} value="{{$building->building_code}}">{{$building->building_title}}</option>
						@endforeach
					@endif
				</select>
			</div>

			<div class="row">
				<div class="form-group col-md-6">
					<label>Room Title</label>
					<input type="text" name="room_title" class="form-control" value="{{$edit_room[0]->room_title}}" required/>	
				</div>
				<div class="form-group col-md-6">
					<label>Room Type</label>
					<select name="room_type" class="form-control" required>
						<option  value="">Select Type</option>
						<option {{($edit_room[0]->room_type=='Class Room') ? 'selected':''}} value="Class Room">Class Room</option>
						<option {{($edit_room[0]->room_type=='Lab') ? 'selected':''}} value="Lab">Lab</option>
						<option {{($edit_room[0]->room_type=='Seminar') ? 'selected':''}} value="Seminar">Seminar</option>
					</select>
				</div>
			</div>

			<?php 
				$room_code = $edit_room[0]->room_code;
				$room_info=explode('-', $room_code);

				$room_no = substr($room_info[1],-2);
				$floor_no =  substr($room_info[1],0,2);


			?>

			<div class="row">
				<div class="form-group col-md-6">
					<label>Room No.</label>
					<input type="number" min="1" max="100" name="room_no" class="form-control" value="{{(int)$room_no}}" required/>	
				</div>
				<div class="form-group col-md-6">
					<label>Floor No.</label>
					<input type="number" min="1" max="50" name="floor_no" class="form-control" value="{{(int)$floor_no}}" required/>	
				</div>
			</div>
			
			<div class="form-group">
				<label>Room Capacity</label>
				<input type="number" name="room_capacity" class="form-control" min="20" max="1000" value="{{$edit_room[0]->room_capacity}}" required/>
			</div>
			<div class="form-group">
				<label>Room Facilities</label>
				<textarea name="room_facilities" class="form-control">{{$edit_room[0]->room_facilities}}</textarea>
			</div>
			<div class="form-group">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</form>

	</div>
</div>

@endif
