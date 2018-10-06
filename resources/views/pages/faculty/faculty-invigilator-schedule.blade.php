@extends('layout.master')
@section('content')

@include('layout.bradecrumb')

<div class="row page_row">

	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading ">
				{{isset($univ_academic_calender->semester_title) ? $univ_academic_calender->semester_title : ''}} {{isset($univ_academic_calender->academic_calender_year) ? $univ_academic_calender->academic_calender_year : ''}}
				<span><a href="{{url('/faculty/invigilator-schedule-download')}}" ><i class="fa fa-print" data-toggle="tooltip" title="Download Exam Schedule"></i></a></span>
			</div>
			<div class="panel-body"><!--info body-->
				<center><h4>Trimester Midterm Exam Invigilator</h4></center>
				<table id="" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time</th>
							<th>Exam Room</th>

						</tr>
					</thead>
					<tbody>
						@if(!empty($invigilator_list_mid))
						@foreach($invigilator_list_mid as $key => $list_mid)
						<?php
						$invigilators_mid=explode(',', $list_mid->invigilators_ID);


						?>
						@foreach ($invigilators_mid as $key => $invigilator_mid) 
						@if($invigilator_mid==\Auth::user()->user_id)
						<tr>
							<td>{{$list_mid->invigilators_exam_date}}</td>
							<td>{{$list_mid->invigilators_exam_time_slot}}</td>
							<td>{{$list_mid->invigilators_exam_room}}</td>
						</tr>
						@endif
						@endforeach
						
						@endforeach
						@else
						<tr>
							<td colspan="3">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>
							</td>
						</tr>
						@endif

					</tbody>
				</table><br><br>

				<center><h4>Trimester Final Exam Invigilator</h4></center>
				<table id="" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time</th>
							<th>Exam Room</th>

						</tr>
					</thead>
					<tbody>
						@if(!empty($invigilator_list_final))
						@foreach($invigilator_list_final as $key => $list_final)
						<?php
						$invigilators_final=explode(',', $list_final->invigilators_ID);


						?>
						@foreach ($invigilators_final as $key => $invigilator_final) 
						@if($invigilator_final==\Auth::user()->user_id)
						<tr>
							<td>{{$list_final->invigilators_exam_date}}</td>
							<td>{{$list_final->invigilators_exam_time_slot}}</td>
							<td>{{$list_final->invigilators_exam_room}}</td>
						</tr>
						@endif
						@endforeach
						
						@endforeach
						@else
						<tr>
							<td colspan="3">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">No Data Available !</h3></center>
								</div>
							</td>
						</tr>
						@endif

					</tbody>
				</table>
			</div><!--/info body-->
		</div>
	</div>
	<!--sidebar widget-->
	<div class="col-md-3 schedule">
		@include('pages.faculty.faculty-notice')
	</div>
	<!--/sidebar widget-->
</div>

@stop