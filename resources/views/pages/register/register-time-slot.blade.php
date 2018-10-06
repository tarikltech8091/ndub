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

	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading"><i class="fa fa-plus-square" aria-hidden="true"></i>
				Add Time Slot</div>
				<div class="panel-body"><!--info body-->

					<form action="{{url('/register/univ-time-slot')}}" method="post" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="col-md-6 form-group">
							<label>Slot Name</label>
							<input type="text" name="slot_name" class="form-control" style="text-transform:uppercase" placeholder="Ex:- A,B,C ...">
						</div>
						<div class="col-md-6 form-group">
							<label>Slot For</label>
							<select name="slot_for" class="form-control">
								<option value="1">Class Schedule Slot</option>
								<option value="2">Midterm Exam Schedule Slot</option>
								<option value="3">Final Exam Schedule Slot</option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group date_obd_class">
								<label>Daily Start Time</label>
								<div class="input-group date start_time col-md-9" data-date="<?php echo date('Y-m-d');?>" data-date-format="hh:ii" data-link-field="start_time_input" data-link-format="hh:ii">
									<input class="form-control" size="16" type="text" value="<?php echo date('h:i A');?>" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
								</div>
								<input type="hidden" id="start_time_input" name="start_time" value="<?php echo date('H:i');?>" required/><br/>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group date_obd_class">
								<label>Daily End Time</label>
								<div class="input-group date end_time col-md-9" data-date="<?php echo date('Y-m-d');?>" data-date-format="hh:ii" data-link-field="end_time_input" data-link-format="hh:ii">
									<input class="form-control" size="16" type="text" value="<?php echo date('h:i A');?>" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
								</div>
								<input type="hidden" id="end_time_input" name="end_time"  value="<?php echo date('H:i');?>" required/><br/>
							</div>
						</div>
						
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Time Slot</button>
						</div>

					</form>


				</div><!--/info body-->
			</div>
		</div>


		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading">Time Slot List</div>
				<div class="panel-body">

					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>SL</th>
								<th>Slot For</th>
								<th>Slot Name</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Duration</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							@if(!empty($univ_time_slot) && count($univ_time_slot) > 0)
							@foreach($univ_time_slot as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>
									@if($list->univ_time_slot_for=='1')
									Class Time Slot
									@elseif($list->univ_time_slot_for=='2')
									Mid Term Time Slot
									@elseif($list->univ_time_slot_for=='3')
									Final Term Time Slot
									@endif
								</td>
								<td>{{$list->univ_time_slot}}</td>
								<td>{{$list->time_slot_start_time}}</td>
								<td>{{$list->time_slot_end_time}}</td>
								<td>{{$list->univ_time_slot_slug}}</td>
								<td><a data-confirm-url="{{url('/register/time-slot-delete/'.$list->univ_time_slot_tran_code)}}" class="btn btn-default btn-xs confirm_box" data-toggle="tooltip" title="Delete Time Slot"><i class="fa fa-trash-o"></i></a></td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="7">
									<div class="alert alert-success">
										<center><h3 style="font-style:italic">No Data Available !</h3></center>
									</div>
								</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($time_pagination)?$time_pagination:''}}

				</div>
			</div>
		</div>

	</div>



	@stop
