<div class="col-md-12">
	<table class="table table-striped  table-hover">
		<thead>
			<tr>
				<th>Course Code</th>
				<th>Course Title</th>
				<th>Course Type</th>
				<th>Credit</th>
				<th>Year</th>
				<th>Trimester</th>
				<th>All <input type="checkbox" id="course_selected_checkbox_selectall" value="0" /></th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($course_list))
				@foreach($course_list as $key => $course)
					@if(empty($course->course_category))
						<tr>
							<td>{{$course->course_code}}</td>
							<td>{{$course->course_title}}</td>
							<td>{{$course->course_type}}</td>
							<td>{{$course->credit_hours}}</td>
							<td>{{$course->level}}</td>
							<td>{{$course->term}}</td>
							<td><input type="checkbox" name="course_selected_checkbox[]" class="course_selected_checkbox" value="{{$course->course_slug}}"></td>
						</tr>
					@endif
				@endforeach

				
			@else
			<tr>
				<td colspan="7" >No Data available</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>

<script type="text/javascript">
	jQuery(function () {
	
		 jQuery('#course_selected_checkbox_selectall').click(function(event) {  //on click 
	        if(this.checked) { // check select status
	            jQuery('.course_selected_checkbox').each(function() { //loop through each checkbox
	                this.checked = true;  //select all checkboxes with class "checkbox1"               
	            });
	        }else{
	            jQuery('.course_selected_checkbox').each(function() { //loop through each checkbox
	                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
	            });         
	        }
	    });
		
	});
</script>