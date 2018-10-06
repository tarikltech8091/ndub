

<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="exampleModalLabel">Message Box</h4>
	</div>

	<form action="{{url('/accounts/applicant/message-send',$applicant_info->applicant_serial_no)}}" method="post" enctype="multipart/form-data">
		<div class="modal-body">
			<div class="form-group">
				<i>Message To: <span style="color:#3498db">{{$applicant_info->applicant_serial_no}}</span></i>
			</div>
			<div class="form-group">
				<i>Select Recipient: </i>
				<i style="margin-left:20px"><input type="checkbox" name="email" value="checked" checked="" /> {{$applicant_info->email}} </i>
				<i style="margin-left:10px"><input type="checkbox" checked="" /> {{$applicant_info->mobile}} </i>
			</div><br>
			<div class="form-group">
				<i>Subject: </i>

				@if(!empty($message_issue) && ($message_issue=='application_payment'))
				<input type="text" class="form-control" name="message_subject" value="Applicant Payment Issue" />
				@elseif(!empty($message_issue) && ($message_issue=='admission_payment'))
				<input type="text" class="form-control" name="message_subject" value="Admission Payment Issue" />
				@else
				<input type="text" class="form-control" name="message_subject" value="" />
				@endif
				
			</div>

			<div class="form-group">
				<i for="message-text" class="control-label">Message: </i>
				
				@if(!empty($message_issue) && ($message_issue=='application_payment'))
				<textarea class="form-control" name="applicants_message" rows="5">Something wrong with your application payment.Please contact NDUB admission office.</textarea>

				@elseif(!empty($message_issue) && ($message_issue=='admission_payment'))
				<textarea class="form-control" name="applicants_message" rows="5">Something wrong with your admission payment.Please contact NDUB admission office.</textarea>

				@else
				<textarea class="form-control" name="applicants_message" rows="5"></textarea>
				@endif
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Send message</button>
		</div>
		<input type="hidden" name="applicant_email_address" value="{{$applicant_info->email}}" />
		<input type="hidden" name="_token" value="{{csrf_token()}}" />
	</form>
</div>