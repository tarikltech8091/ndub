
<!-- Include Date Picker -->
		
		<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<script type="text/javascript" src="https://formden.com/static/cdn/formden.js"></script> -->

	<div class="multi-field pro_div_{{$multi_count}}" >
	<div class="col-md-12" style="padding-left:0;padding-right:0;">
		<div class="col-md-2">
			<input type="text" name="organization_{{$multi_count}}" class="form-control" placeholder="Organization {{$multi_count}}">
		</div>
		<div class="col-md-2">
			<input type="text" name="position_held_{{$multi_count}}" class="form-control" placeholder="Position">
		</div>
		
		<!-- <div class="col-md-2">
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar">
					</i>
				</div>
				<input class="form-control date" id="date" name="period_from_{{$multi_count}}"  placeholder="YYYY/MM/DD" type="text" />
			</div>
		</div> -->

		

		<!-- <div class="col-md-1">
			<input class="todayBox" name="till_{{$multi_count}}" type="checkbox"> Use Today<br>
			Enter Date: <input class="enterDate" type="text">
		</div>
		<div class="col-md-2 till_now" hidden>
			<p>Till Now</p>
		</div> -->
		<!-- <div class="col-md-1">
			<input type="checkbox" class="form-control" value="00">
		</div> -->
	<!-- 	<div class="col-md-2 date_field_show" hidden>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar">
					</i>
				</div>
				<input class="form-control date" id="date" name="period_to_{{$multi_count}}"  placeholder="YYYY/MM/DD" disabled="" type="text" />
			</div>
		</div> -->
		<!-- <div class="col-md-2">
		<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar">
					</i>
				</div>
				<input class="form-control date" id="date" name="period_to_{{$multi_count}}"  placeholder="YYYY/MM/DD" type="text" />
			</div>
		</div> -->

		<div class="col-md-3">
			<div class="input-group form_date_group date form_date_2 col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="period_from_{{$multi_count}}" data-count="2" data-link-format="yyyy-mm-dd">
				<input class="form-control" size="16" type="text" value="" readonly>
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" name="period_from_{{$multi_count}}" id="period_from_{{$multi_count}}" value="" /><br/>
		</div>

		<div class="col-md-1">
		<div style="margin-top:7px;font-size:12px;">
				<label><input type="checkbox" data-till="{{$multi_count}}" name="period_to_{{$multi_count}}"  /> <span>Till Now</span></label>
			</div>

		</div>

		<div class="col-md-3 {{$multi_count}}">
			<div class="input-group form_date_group date form_date_2 col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="period_to_{{$multi_count}}" data-count="2" data-link-format="yyyy-mm-dd">
				<input class="form-control" size="16" type="text" value="" readonly>
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" name="period_to_{{$multi_count}}" id="period_to_{{$multi_count}}" value="" /><br/>
		</div>

<!-- 		<div class="col-md-1">
			<input type="text" name="total_year_{{$multi_count}}" placeholder="Year's" class="form-control">
		</div>
		<div class="col-md-1">
			<input type="text" name="total_months_{{$multi_count}}" placeholder="Months" class="form-control" >
		</div> -->
		<input type="hidden" name="multi_count" class="multi_count" value="1" >
		<button type="button" data-id="{{$multi_count}}" class="btn btn-danger btn-sm remove-field col-md-1" style="width:40px"><i class="fa fa-times" aria-hidden="true"></i></button>
	</div>
	</div>

<script type="text/javascript">
// 	  $(document).ready(function(){
//     var date_input=$('.date'); //our date input has the name "date"
//     var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
//     date_input.datepicker({
//       format: 'yyyy/mm/dd',
//       container: container,
//       todayHighlight: true,
//       autoclose: true,
//     });
//   });


/*jQuery(function() {
   jQuery("input:checkbox").click(function() {
    
      var date_field = '.'+$(this).data('till');
      var row = $(this).data('till');
      if(jQuery(this).is(':checked')){
    
        jQuery('#period_to_'+row).val('0000-00-00');
        jQuery(date_field).hide();
        
      }
      else 
        
        jQuery(date_field).show();
       
      
    });
 });*/
</script>

