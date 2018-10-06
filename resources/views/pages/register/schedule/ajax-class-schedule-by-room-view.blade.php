

<div class="col-md-12" style="margin-top:30px;">

	<table class="table table-bordered">
		<thead>


			<?php 
			$i=1;  
			$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');
			?>

			<tr>
				<th>Day <i class="fa fa-long-arrow-right" style="color:green" aria-hidden="true"></i></th>
				@for($i=0;$i<=6;$i++)
				<th>{{$days[$i]}}</th>
				@endfor

			</tr>

			
		</thead>

		<tbody>

			<?php
			$time_slots=\DB::table('univ_time_slot')->where('univ_time_slot_for',1)->orderBy('univ_time_slot','asc')->get();

			$i=1;  
			$days = array('Saturday','Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday');

			?>


			@if(!empty($time_slots))
			@foreach($time_slots as $key => $list)


			<tr>
				<th>{{$list->univ_time_slot_slug}}</th>

				@for($i=0;$i<=6;$i++)

				<?php

				$day=$days[$i];
				$univ_academic_calender=\DB::table('univ_academic_calender')->where('academic_calender_status',1)->first();

				$existing_class=\DB::table('univ_class_schedule')
				->where('class_schedule_room', $room_code)
				->where('class_schedule_semester',$univ_academic_calender->academic_calender_semester)
				->where('class_schedule_year',$univ_academic_calender->academic_calender_year)
				->where('class_schedule_day_of_week',$day)
				->where('class_schedule_time_slot',$list->univ_time_slot_slug)
				->leftjoin('faculty_basic','faculty_basic.faculty_id','=','univ_class_schedule.class_schedule_faculty')
				->first();
				?>

				<td class="text-center {{isset($existing_class->class_schedule_course) ? 'schedule_td_hover':''}}">
					@if(!empty($existing_class->class_schedule_course))
					<a data-confirm-url="{{url('/register/schedule-delete', $existing_class->class_schedule_tran_code)}}" class="confirm_box cursor" data-toggle="tooltip" title="Delete Schedule" style="float:right;"><i class="fa fa-minus-square"></i></a>
					<b>SC: {{$existing_class->class_schedule_course}}</b><br>
					<i>FID: {{$existing_class->class_schedule_faculty}} ({{strtoupper(substr($existing_class->first_name,0,1))}}{{strtoupper(substr($existing_class->middle_name,0,1))}}{{strtoupper(substr($existing_class->last_name,0,1))}})
						@else
						@endif
					</td>
					@endfor
				</tr>

				@endforeach
				@endif



			</tbody>
		</table>

	</div>

	<script type="text/javascript">
		/*###########################
		# tooltip
		#############################
		*/ 
		jQuery(document).ready(function(){
			jQuery('[data-toggle="tooltip"]').tooltip();   
		});
		jQuery(document).ready(function(){
			jQuery('[data-toggle1="tooltip"]').tooltip();   
		});




		/*###########################
		# Confirm Box
		#############################
		*/ 
		jQuery(function(){

			jQuery('.confirm_box').click(function(){

				var confirm_url=jQuery(this).data('confirm-url');
				if (confirm("Do You Want To Delete ?") == true) {
					window.location.href=confirm_url;
				}
			});

		});
	</script>