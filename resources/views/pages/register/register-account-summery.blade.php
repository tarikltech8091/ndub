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
			<form method="get" action="{{url('/register/account-summery')}}">
				<div class="sorting_form">
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
					<div class="form-group col-md-3">
						<label for="Semester">Trimester</label>
						<?php
						$semester_list=\DB::table('univ_semester')->get();
						?>
						<select class="form-control semester" name="semester" >
							<option value="0">All</option>
							@if(!empty($semester_list))
							@foreach($semester_list as $key => $list)
							<option {{(isset($_GET['semester']) && ($list->semester_code==$_GET['semester'])) ? 'selected':''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="AcademicYear">Academic Year</label>
						<select class="form-control academic_year" name="academic_year" >
							<option value="0">All</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-6 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-6 year"))}}">{{date("Y",strtotime("-6 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-5 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-5 year"))}}">{{date("Y",strtotime("-5 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-4 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-4 year"))}}">{{date("Y",strtotime("-4 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-3 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-3 year"))}}">{{date("Y",strtotime("-3 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-2 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-2 year"))}}">{{date("Y",strtotime("-2 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('-1 year')))) ? 'selected':''}}  value="{{date("Y",strtotime("-1 year"))}}">{{date("Y",strtotime("-1 year"))}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y'))) ? 'selected':''}}  value="{{date('Y')}}">{{date('Y')}}</option>
							<option {{(isset($_GET['academic_year']) && ($_GET['academic_year']==date('Y',strtotime('+1 year')))) ? 'selected':''}} value="{{date("Y",strtotime("+1 year"))}}">{{date("Y",strtotime("+1 year"))}}</option>
						</select>
					</div>

					<div class="form-group col-md-1" style="margin-top:20px;">
						<button class="btn btn-danger total_registered_student_search" data-toggle="tooltip" title="Search Students">Search</button>
					</div>

				</div>
			</form>
		</div>
	</div>



	<div class="page_row">

		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading">Accounts Summary </div>
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
							@if(!empty($student_payment_transaction_detail))
							@foreach($student_payment_transaction_detail as $key => $list)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->payment_student_serial_no}}</td>
								<td>{{$list->program_code}}</td>
								<td>{{$list->semester_title}}</td>
								<td>{{$list->payment_year}}</td>
								<td>{{isset($list->fee_category_name)?$list->fee_category_name:'Others Fee'}}</td>
								<td>{{$list->payment_receive_type}}</td>
								<td>{{$list->payment_amounts}}</td>
								<td>{{$list->updated_by}}</td>
								<td>{{$list->updated_at}}</td>
							</tr>
							@endforeach
							<tr>
								<th colspan="7" align="center">Total Amount</th>
								<th colspan="3">{{isset($total_amount)? $total_amount :'0'}}</th>
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