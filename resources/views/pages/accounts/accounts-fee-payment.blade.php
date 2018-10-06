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
			<div class="panel-heading">Accounts fee Payment</div>
			<div class="panel-body"><!--info body-->

				<form action="{{url('/accounts/fee-payment')}}" method="post" enctype="multipart/form-data">
					<caption>(<strong>Instruction:</strong> Here tution fee, trimester fee, admission fee and application form fee are different from others program. So you can not use all program.)</caption><br>

					<?php 
					$accounts_fee_category_list = \DB::table('fee_category')->select('fee_category.*')->get();
					?>
					<div class="form-group">
						<label for="Program">Accounts Fee Types <span class="required-sign">*</span></label>
						<select class="form-control" name="accounts_fee_name_slug">
							@if(!empty($accounts_fee_category_list))
							@foreach($accounts_fee_category_list as $key => $list)
							<option {{(old('accounts_fee_name')== $list->fee_category_name_slug) ? "selected" :''}} value="{{$list->fee_category_name_slug}}">{{$list->fee_category_name}}</option>
							@endforeach
							@endif
						</select> 
					</div>


					<?php 
					$program_list =\App\Register::ProgramList();
					?>
					<div class="form-group">
						<label for="Program">Program <span class="required-sign">*</span></label>
						<select class="form-control" name="accounts_fee_program">
							<option value="all">All</option>
							@if(!empty($program_list))
							@foreach($program_list as $key => $list)
							<option {{(old('accounts_fee_program')== $list->program_id) ? "selected" :''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
							@endforeach
							@endif
						</select> 
					</div>

					<div class="form-group">
						<label>Fee Amount</label>
						<input type="text" name="accounts_fee_amount" class="form-control" value="{{old('accounts_fee_amount')}}" placeholder="Amount" />	
					</div>

					<div class="form-group">
						<label>Fee Payment Type</label>
						<select class="form-control" name="accounts_fee_payment_type">
							<option  {{(old('accounts_fee_payment_type')== "receivable") ? "selected" :''}} value="Receivable">Receivable</option>
							<option {{(old('accounts_fee_payment_type')== "others") ? "selected" :''}} value="Others">Others</option>
						</select>	
					</div>

					<div class="form-group">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="reset" class="btn btn-danger" value="Reset">
						<input type="submit" class="btn btn-primary" value="Submit">
					</div>
				</form>

			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-6">


		<div class="panel panel-info">
			<div class="panel-heading">Fee Payment Lists</div>
			<div class="panel-body">

				<div class="row">
					<div class="sorting_form">
						<form method="get" action="{{url('/accounts/fee-payment')}}">
							<div class="form-group col-md-10">
								<label for="Program">Program</label>
								<select class="form-control" name="program" >
									<option value="0">All</option>
									@if(!empty($program_list))
									@foreach($program_list as $key => $list)
									<option {{(isset($_GET['program']) && ($list->program_id==$_GET['program'])) ? 'selected':''}} value="{{$list->program_id}}">{{$list->program_title}}</option>
									@endforeach
									@endif
								</select>
							</div>


							<div class="form-group col-md-2 margin_top_20">
								<button class="btn btn-danger" data-toggle="tooltip" title="Search Fee List">Search</button>
							</div>
						</form>
					</div>
				</div>

				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Fee Category</th>
							<th>Program</th>
							<th>Amount</th>
							<th>Fee Payment type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($account_payment_list) && count($account_payment_list) > 0)
						@foreach($account_payment_list as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->accounts_fee_name}}</td>
							<td>{{$list->program_code}}</td>
							<td>{{$list->accounts_fee_amount}}</td>
							<td>{{$list->accounts_fee_payment_type}}</td>
							<td>
								<a href="{{URL::route('Edit Accounts Fee Payment',$list->accounts_fee_tran_code)}}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Fee Payments"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a data-confirm-url="{{URL::route('Accounts Fee Payment Delete',$list->accounts_fee_tran_code)}}" class="btn btn-default btn-xs confirm_box"  data-toggle="tooltip" title="Delete Fee Payments"><i class="fa  fa-trash-o"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="6">No Data available</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($payment_pagination) ? $payment_pagination:""}}
		</div>
	</div>
</div>

</div>

@stop