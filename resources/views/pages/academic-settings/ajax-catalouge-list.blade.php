<div class="col-md-12">

	<table class="table table-striped  table-hover">
		<thead>
			<tr>
				<th>SL</th>
				<th>Catalouge Title</th>
				<th>No. of Course</th>
				<th>No. of Credit</th>
				<th>All <input type="checkbox" id="catalogue_selected_checkbox_selectall"  value="" checked/></th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($course_catalogue_list))

			<?php 
			$total_course= 0;
			$total_credit = 0;
			?>
			@foreach($course_catalogue_list as $key => $catalogue)

			<?php 
			$total_course=  $total_course+$catalogue->no_of_courses;
			$total_credit = $total_credit+$catalogue->total_credit_hours;
			?>

			<tr>
				<td>{{$key+1}}</td>
				<td>{{$catalogue->course_category_name}}</td>
				<td>{{$catalogue->no_of_courses}}</td>
				<td>{{$catalogue->total_credit_hours}}</td>
				<td><input type="checkbox" course="{{$catalogue->no_of_courses}}" credit="{{$catalogue->total_credit_hours}}" name="catalogue_selected_checkbox[]" class="catalogue_selected_checkbox check"  value="{{$catalogue->course_catalogue_tran_code}}" checked></td>
			</tr>
			@endforeach

			<tr>
				<td colspan="2" style="text-align:right">Total Course:</td>
				<td>
					<input type="text" style="border:hidden;width:auto" readonly="" value="{{$total_course}}" name="total_course" class="total_course"><span style="float:right">Total Credit:</span>
				</td>
				<td colspan="2" style="text-align:left">
					<input type="text" style="border:hidden;width:auto" readonly="" value="{{$total_credit}}" name="total_credit" class="total_credit">
				</td>
			</tr>


			@else
			<tr>
				<td colspan="5" >No Data available</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>

<script type="text/javascript">
	jQuery(function () {

		 jQuery('#catalogue_selected_checkbox_selectall').click(function(event) {  //on click 
	        if(this.checked) { // check select status
	        	course=0;
	        	credit=0;
	            jQuery('.catalogue_selected_checkbox').each(function() { //loop through each checkbox
	                this.checked = true;  //select all checkboxes with class "checkbox1"  
	                course += parseInt($(this).attr('course'));     
	                credit += parseInt($(this).attr('credit'));     
	                jQuery('.total_course').val(course);
	                jQuery('.total_credit').val(credit);
	                
	            });
	        }else{
	            jQuery('.catalogue_selected_checkbox').each(function() { //loop through each checkbox
	                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
	                jQuery('.total_course').val(0);
	                jQuery('.total_credit').val(0);
	            });         
	        }	        		         
	    });

		 $(".check").change(function(){
		 	var course = 0;
		 	var credit = 0;
		 	$(".check:checked").each(function(){
		 		course += parseInt($(this).attr('course'));              
		 		credit += parseInt($(this).attr('credit'));              
		 	});
		 	jQuery('.total_course').val(course);
		 	jQuery('.total_credit').val(credit);
		 });


		});

</script>

