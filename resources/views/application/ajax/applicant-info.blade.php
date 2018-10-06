
@if(!empty($applicant_info))
<div class="alert alert-warning">
	<a href="{{url('/online-application/applicant')}}" class="close" data-dismiss="alert" aria-label="close">&times;</a>

	<div class="row">
		<div class="page-header" >
			<center class="header-search">Search Result</center>
		</div>
	</div>
	<div class="row main-border">
		<div class="col-md-2 photo-div">
			<center>
				<img src="{{asset($applicant_info->app_image_url)}}" class="image" title="{{asset($applicant_info->middle_name)}}" alt="{{asset($applicant_info->applicant_serial_no)}}" />
			</center>
		</div>


		<div class="col-md-10 serial-gender-div">
			<div class="col-md-6 serial_input">
				<span class="serial_input_label"  for="inputColor">Serial No : {{$applicant_info->applicant_serial_no}}</span>
			</div>
			<div class="col-md-6">
				<span class="serial_input_label">Gender : {{ucfirst($applicant_info->gender)}}</span>
			</div>
		</div>
		<div class="col-md-10 program-div">
			<table  class="serial">
				<tr>
					<td><b>Program : </b></td>
					<td>{{$applicant_info->program_title}}</td>
				</tr>
			</table>
		</div>

		<div class="col-md-9 applicant-info">
			<table class="info-table">
				<tr>
					<td style="width:25%">Applicant's Name</td>
					<td>:</td>		
					<td>{{$applicant_info->first_name.' '.$applicant_info->middle_name.' '.$applicant_info->last_name}} </td>
				</tr>
				<tr>
					<td>Trimester</td>
					<td>:</td>
					<td>{{$applicant_info->semester_title}}</td>
				</tr>
				<tr>
					<td>Academic Year</td>
					<td>:</td>		
					<td>{{$applicant_info->academic_year}}</td>
				</tr>
				<tr>
					<td>Contact</td>
					<td>:</td>		
					<td>{{$applicant_info->mobile}}</td>
				</tr>
				@if($applicant_info->payment_status==1)
				<tr>
					<td>Payment Status</td>
					<td>:</td>
					<td>Paid</td>
				</tr>

				<tr>
					<td>Instruction</td>
					<td>:</td>
					<td> Please Check admission result.</td>
				</tr>

				@elseif($applicant_info->payment_status==2)
				<tr>
					<td>Payment Status</td>
					<td>:</td>
					<td>Waiting For Approval</td>
				</tr>
				<tr>
					<td>Instruction</td>
					<td>:</td>
					<td> Please go to accounts office for approval.</td>
				</tr>
				@elseif($applicant_info->payment_status==5)
				<tr>
					<td>Status</td>
					<td>:</td>
					<td>Admitted Student</td>
				</tr>
				
				@elseif($applicant_info->payment_status==0)
				<tr>
					<td>Payment Slip</td>
					<td>:</td>
					<td><a href="{{url('/online-application/payment-slip/'.$applicant_info->applicant_serial_no)}}" target="_blank">Download Payment Slip <i class="fa fa-print"></i></a></td>
				</tr>
				<tr>
					<td>Payment Status</td>
					<td>:</td>		
					<td class="payment_status">
						To be Paid <a data-toggle="modal" data-target="#paymentModal" class="payment_edit"><i class="fa fa-plus-square"></i> Add Payment Slip</a>
					</td>
				</tr>
				<tr>
					<td>Instruction</td>
					<td>:</td>
					<td> Please go to accounts office for payment and approval.</td>
				</tr>
				@endif
				@if(($applicant_info->payment_status==1)&&($applicant_info->applicant_eligiblity==1))
				<tr>
					<td>Applicant Status</td>
					<td>:</td>
					<td>Eligible <a href="{{url('/online-application/admit-card')}}" target="_blank"  class="payment_edit"><i class="fa fa-cloud-download"></i> Admit Card</a></td>
				</tr>
				<tr>
					<td>Instruction</td>
					<td>:</td>
					<td> You are eligible fo admission test.</td>
				</tr>
				@endif
			</table>
		</div>
		<input type="hidden" class="site_url" value="{{url('/')}}">
		<input type="hidden" class="applicant_serial_no" value="{{$applicant_info->applicant_serial_no}}">
		
	</div>

	<!-- Modal -->
	<div id="paymentModal" class="modal fade" role="dialog">

		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Bank Payment</h4>
				</div>
				<div class="modal-body">
      	<!-- <form class="form-inline" method="post" action="{{url('/')}}">
      		
      </form> -->
      <div class="form-group">
      	<label>Bank Name</label>
      	<!-- <input type="text" class="form-control" name="bank_name" id="bank_name" value=""> -->
      	<select class="form-control" name="bank_name" id="bank_name">
      		<option value="">--Select Bank--</option>
      		<option {{old('bank_name')=='MBL'?'selected':''}} value="MBL">Mercantile Bank Ltd</option>
      	</select>
      </div>
      <div class="form-group">
      	<label>Payment Slip No</label>
      	<input type="text" class="form-control" name="payment_slip_no" id="payment_slip_no" value="">
      </div>
      <div class="form-group">
      	<label>Paid Amount</label>
      	<input type="text" class="form-control" name="payment_amount" id="payment_amount" value="">
      </div>
      
  </div>
  <div class="modal-footer">
  	<button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
  	<a  class="btn btn-success" id="payment_submit">Submit</a>
  </div>
</div>

<script type="text/javascript">

	jQuery('#payment_submit').click(function(){
		var payment_slip_no = jQuery('#payment_slip_no').val();
		var payment_amount = jQuery('#payment_amount').val();
		var bank_name = jQuery('#bank_name').val();

		if((Math.floor(payment_slip_no)==payment_slip_no) && (Math.floor(payment_amount)==payment_amount) && (payment_slip_no.length !=0) && (payment_amount.length !=0) && (bank_name.length !=0)){

			
			jQuery('#paymentModal').modal('hide');
			
			
			var site_url = jQuery('.site_url').val();
			var applicant_serial_no = jQuery('.applicant_serial_no').val();

			var request_url = site_url+'/online-application/payment/'+applicant_serial_no+'/'+payment_amount+'/'+payment_slip_no+'/'+bank_name;


			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){
					
					jQuery('.payment_status').html("Waiting For Approval");
				}
			});
		}else alert("Invalid Payment or Amount !!");
		
	});
</script>

</div>
</div>
</div>
@else
<div class="row">
	<div class="page-header" >
		<center class="header-search">Search Result</center>
	</div>
	<div class="col-md-12">
		<div class="alert alert-danger text-center">
			<strong>No Applicant's Found</strong>
		</div>
	</div>
</div>
@endif