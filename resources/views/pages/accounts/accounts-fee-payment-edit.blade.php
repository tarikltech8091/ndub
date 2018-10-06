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
 			<div class="panel-heading">Edit Accounts Fee Payment</div>
 			<div class="panel-body"><!--info body-->

 				<form action="{{url('/accounts/account-payment/edit/'.$edit_fee_payment->accounts_fee_tran_code)}}" method="post">
 					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<caption>(<strong>Instruction:</strong> Here tution fee, trimester fee, admission fee and application form fee are different from others program. So you can not use all program.)</caption><br>

 					<?php 
 					$account_fee_type_list = \DB::table('fee_category')->select('fee_category.*')->get();
 					?>
 					<div class="form-group">
 						<label for="Program">Accounts Fee Types <span class="required-sign">*</span></label>
 						<select class="form-control" name="accounts_fee_name_slug">
 							@if(!empty($account_fee_type_list))
 							@foreach($account_fee_type_list as $key => $list)
 							<option {{($edit_fee_payment->accounts_fee_name == $list->fee_category_name) ? "selected" :''}}
 								value="{{$list->fee_category_name_slug}}">{{$list->fee_category_name}}</option>
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
								<option {{($edit_fee_payment->accounts_fee_program == 'all') ? "selected" :''}} value="all">All</option>
 								@if(!empty($program_list))
 								@foreach($program_list as $key => $list)
 								<option {{($edit_fee_payment->accounts_fee_program == $list->program_id) ? "selected" :''}} 
 									value="{{$list->program_id}}">{{$list->program_title}}</option>
 									@endforeach
 									@endif
 								</select> 
 							</div>

 							<div class="form-group">
 								<label>Fee Amount</label>
 								<input type="text" name="accounts_fee_amount" class="form-control" value="{{$edit_fee_payment->accounts_fee_amount}}" />	

 							</div>

 							<div class="form-group">
 								<label>Fee Payment Type</label>

 								<select class="form-control" name="accounts_fee_payment_type">
 									<option value="{{$edit_fee_payment->accounts_fee_payment_type}}">{{$edit_fee_payment->accounts_fee_payment_type}}</option>
 								</select>
 							</div>

 							<div class="form-group pull-right">
 								<a href="{{url('/accounts/fee-payment')}}" class="btn btn-danger"  data-toggle="tooltip" title="Cancel Edit">Cancel</a>
 								<input type="submit" class="btn btn-success" value="Update"  data-toggle="tooltip" title="Update Fees">
 							</div>
 						</form>

 					</div><!--/info body-->
 				</div>
 			</div>

 		</div>


 		@stop