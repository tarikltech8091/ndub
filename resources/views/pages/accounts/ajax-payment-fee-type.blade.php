
@if(!empty($fee_name) && $fee_name =='Waiver')
<div class="col-md-6 form-group">
	<label>Amount<span class="required-sign">*</span></label>
	<input type="text" name="amount" placeholder="Amount" class="form-control"/>
	<input type="hidden" name="slip_no" value="NDUB" class="form-control"/>

</div>
<div class="col-md-6 form-group ">
	<label>Waiver Type<span class="required-sign">*</span></label>
	<select name="waiver_type" class="form-control" required="">
		<option value="">Select Waiver Type</option>
		@if(!empty($waiver_info))
		@foreach ($waiver_info as $key => $value)
			<option value="{{$value->waiver_name_slug}}">{{$value->waiver_name}} ({{$value->waiver_rate}}%)</option>
		@endforeach
		@endif
	</select>
</div>
@else
<div class="col-md-6 form-group">
	<label>Amount<span class="required-sign">*</span></label>
	<input type="text" name="amount" placeholder="Amount" class="form-control"/>
</div>
<div class="col-md-6 form-group ">
	<label>Slip No.<span class="required-sign">*</span></label>
	<input type="text" name="slip_no" placeholder="Slip no.." class="form-control" required="" />
</div>
@endif