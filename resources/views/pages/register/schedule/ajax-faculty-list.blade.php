

<div class="form-group">
	<label>Select Faculty</label> 
	<select class="form-control" name="class_schedule_faculty">
		<option value="">--Select Faculty--</option>

		
		@foreach($available_faculties as $key => $list)

		<?php
		$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

		if(!empty($univ_academic_calender)){

			$univ_class_schedule=\DB::table('univ_class_schedule')
			->where('class_schedule_semester', $univ_academic_calender->academic_calender_semester)
			->where('class_schedule_year', $univ_academic_calender->academic_calender_year)
			->where('class_schedule_day_of_week',$class_day_week)
			->where('class_schedule_time_slot',$time_slot)
			->where('class_schedule_faculty',$list->faculty_id)
			->first();
		}

		?>

		@if(!empty($univ_class_schedule))
		@if($list->faculty_id == $univ_class_schedule->class_schedule_faculty)
		@else
		<option value="{{$list->faculty_id}}">{{$list->faculty_id}}</option>
		@endif

		@else
		<option value="{{$list->faculty_id}}">{{$list->faculty_id}}</option>
		@endif
		@endforeach

	</select>
</div>

<button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add Class Schedule</button>