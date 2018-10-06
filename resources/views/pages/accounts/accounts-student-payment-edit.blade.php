
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="exampleModalLabel">Edit Student Payment</h4>
	</div>

	
	<div class="modal-body">
		@if(!empty($student_payment))
		<form action="{{url('/accounts/student-payment-update', $student_payment->payment_transaction_tran_code)}}" method="post" enctype="multipart/form-data">
			<div class="row">

				<div class="col-md-5 form-group">
					<label class="control-label"> Date (YY-MM-DD) </label>
					<div class="input-group date date_of col-md-12" data-date="" data-count="1" data-date-format="yyyy-mm-dd" data-link-field="date_of_birth" data-link-format="yyyy-mm-dd">
						<input class="form-control" name="accounts_transaction_date" size="16" type="text" value="{{$student_payment->accounts_transaction_date}}">
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>

				</div>

				<div class="col-md-4 form-group">
					<label for="recipient-name" class="control-label">Academic Year</label>
					<input type="text" class="form-control" name="academic_year" value="{{$student_payment->payment_year}}">
				</div>

				<div class="col-md-3 form-group">
					<label for="recipient-name" class="control-label">Trimester</label>
					<?php
					$semester_list=\DB::table('univ_semester')->get();
					?>
					<select class="form-control" name="academic_semester">
						@if(!empty($semester_list))
						@foreach($semester_list as $key => $list)
						<option {{isset($student_payment->payment_semster) && $student_payment->payment_semster==$list->semester_code ? 'selected' : ''}} value="{{$list->semester_code}}">{{$list->semester_title}}</option>
						@endforeach
						@endif
					</select>
				</div>

			</div>


			<div class="row">


				<div class="col-md-5 form-group">
					<label class="control-label">Receive Type</label>

					<select class="form-control" name="receive_type">
						<option {{isset($student_payment->payment_receive_type) && ($student_payment->payment_receive_type == 'bank') ? 'selected' : ''}} value="bank">Bank</option>
						<option {{isset($student_payment->payment_receive_type) && ($student_payment->payment_receive_type == 'cash') ? 'selected' : ''}} value="cash">Cash</option>
						<option {{isset($student_payment->payment_receive_type) && ($student_payment->payment_receive_type == 'NDUB') ? 'selected' : ''}} value="NDUB">NDUB</option>
					</select>
				</div>

				<div class="col-md-4 form-group">
					<label for="recipient-name" class="control-label">Fees Type</label>
					<?php
					$fee_category=\DB::table('fee_category')->whereNotIn('fee_category_name_slug',array('application_form_fee','admission_fee'))->get();
					?>
					<select class="form-control" name="fees_type">
						@if(!empty($fee_category))
						@foreach($fee_category as $key => $list)

						<option {{isset($student_payment->payment_transaction_fee_type) && ($list->fee_category_name_slug == $student_payment->payment_transaction_fee_type) ? 'selected' : ''}} value="{{$list->fee_category_name_slug}}">{{$list->fee_category_name}}</option>

						@endforeach
						@endif

						<option {{isset($student_payment->payment_transaction_fee_type) && ($student_payment->payment_transaction_fee_type == 'Waiver') ? 'selected' : ''}} value="Waiver">Waiver</option>
						<option {{isset($student_payment->payment_transaction_fee_type) && ($student_payment->payment_transaction_fee_type == 'other_fees') ? 'selected' : ''}} value="other_fees">Other Fees</option>
					</select>
					
				</div>
				<?php $waiver_info=\DB::table('waivers')->where('waiver_name_slug', $student_payment->waiver_type)->first(); ?>
				<div class="col-md-3 form-group">
					<label for="recipient-name" class="control-label">Waiver Type</label>
					<input type="text" class="form-control" name="waiver_type" value="{{isset($waiver_info)? $waiver_info->waiver_name :''}}" readonly="">
				</div>


			</div>

			<div class="row">
				<input type="hidden" class="form-control" name="payment_remarks" value="{{$student_payment->payment_remarks}}">

<!-- 				<div class="col-md-4 form-group">
					<label for="recipient-name" class="control-label">Payment Receivable</label>
					<input type="text" class="form-control" name="payment_receivable" value="{{$student_payment->payment_receivable}}">
				</div> -->

					<input type="hidden" class="form-control" name="payment_receivable" value="{{$student_payment->payment_receivable}}">
					
				<div class="col-md-5 form-group">
					<label for="recipient-name" class="control-label">Slip No</label>
					<input type="text" class="form-control" name="slip_no" value="{{$student_payment->transaction_slip_no}}">
				</div>

				<div class="col-md-4 form-group">
					<label for="recipient-name" class="control-label">Payment Paid</label>
					<input type="text" class="form-control" name="payment_paid" value="{{$student_payment->payment_paid}}">
				</div>
				<div class="col-md-3 form-group">
					<label for="recipient-name" class="control-label">Payment Other</label>
					<input type="text" class="form-control" name="payment_other" value="{{$student_payment->payment_others}}">
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 form-group">
					<label for="recipient-name" class="control-label">Payment Details</label>
					<textarea type="text" class="form-control" name="payment_details" rows="1">{{$student_payment->payment_details}}</textarea>
				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" name="_token" value="{{csrf_token()}}" />
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Update</button>
			</div>

		</form>

		@endif
	</div>
	

</div>