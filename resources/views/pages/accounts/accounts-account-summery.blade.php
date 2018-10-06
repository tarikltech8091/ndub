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
		<div class="panel panel-body padding_0">
			<form method="get" action="{{url('/accounts/account-summery')}}">
				<div class="sorting_form">

				    <div class="col-md-3">
				      	<label class="control-label">From <span class="required-sign">*</span></label>
				      	<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_from" data-link-format="yyyy-mm-dd">
				      		<input class="form-control" size="16" type="text" value="{{(isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'))}}" readonly>
				      		<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				      		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				      	</div>
				      	<input type="hidden" name="date_from" value="{{(isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d'))}}" id="date_from" /><br/>
				    </div>

				    <div class="col-md-3">
				      	<label class="control-label">To <span class="required-sign">*</span></label>
				      	<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_to" data-link-format="yyyy-mm-dd">
				      		<input class="form-control" size="16" type="text" value="{{(isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d'))}}" readonly>
				      		<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				      		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				      	</div>
				      	<input type="hidden" name="date_to" value="{{(isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d'))}}" id="date_to" /><br/>
				    </div>

				    <?php 
					$program_list =\App\Applicant::ProgramList();

					?>
					<div class="form-group col-md-4">
						<label for="Program">Program</label>
						<select class="form-control program" name="program" >
							<option value="0">All</option>
							@if(!empty($program_list))
							@foreach($program_list as $key => $list)
							<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group col-md-1" style="margin-top:20px;">
						<button class="btn btn-danger total_registered_student_search" data-toggle="tooltip" title="Search Students">Search</button>
					</div>
                <?php 
                	$now=date('Y-m-d');
                	$search_from =isset($_GET['date_from'])?$_GET['date_from'] : $now;
                	$search_to = isset($_GET['date_to'])?$_GET['date_to'] : $now;
                	$program=isset($_GET['program'])? $_GET['program'] : 0;
                ?>

					<div class="col-md-1 margin_top_20">
						<a href="{{url('/accounts/summery/download/program-'.$program.'/from-'.$search_from.'/to-'.$search_to)}}" target="_tab"><span class="btn btn-warning" data-toggle="tooltip" title="Download Student List"><i class="fa fa-print"></i></span></a>
					</div>
				</div>
			</form>
		</div>
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Accounts Summery </div>
				<div class="panel-body" style="height: 400px; overflow: auto;">

					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Student ID</th>
								<th>Program</th>
								<th>Semester</th>
								<th>Year</th>
								<th>Fee Type</th>
								<th>Receive Type</th>
								<th>Amount</th>
								<th>Created By</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($student_payment_transaction_detail) && count($student_payment_transaction_detail)>0)
							@foreach($student_payment_transaction_detail as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->payment_student_serial_no}}</td>
								<td>{{$list->program_code}}</td>
								<td>{{$list->semester_title}}</td>
								<td>{{$list->payment_year}}</td>
								<td>{{($list->fee_category_name)? ($list->fee_category_name) : 'Others Fee' }}</td>
								<td>{{$list->payment_receive_type}}</td>
								<td>{{$list->payment_amounts}}</td>
								<td>{{$list->updated_by}}</td>
								<td>{{$list->updated_at}}</td>
							</tr>
							@endforeach
							<tr>
								<th colspan="7" align="center">Total Amount</th>
								<th colspan="3">{{isset($total_amount)? $total_amount :0}}</th>
							</tr>
							@else
							<tr>
								<td colspan="10" align="center">No Data Found !</td>
							</tr>
							@endif

						</tbody>
					</table>
			</div>
		</div>
		
	</div>

</div>
<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
<input type="hidden" name="site_url" class="site_url" value="{{url('/')}}">

@stop