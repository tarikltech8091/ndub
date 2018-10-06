@extends('layout.master')
@section('content')

@include('layout.bradecrumb')
	
	<div class="row page_row">
		
		<div class="col-md-8">
			<div class="panel panel-info">
			  <div class="panel-heading ">
			  Spring 2016
			  	<span><a href="#" onclick="printDiv('printableArea')"><i class="fa fa-print"></i></a></span>
			  </div>
			  <div class="panel-body"><!--info body-->
				 <table id="" class="table table-striped table-bordered table-hover">
			  		<thead>
			  			<tr>
			  				<th>Date</th>
			  				<th>Time</th>
			  				<th>Exam Room</th>
			  				
			  			</tr>
			  		</thead>
			  		<tbody>
			  			<tr>
			  				<td>2016-04-02</td>
			  				<td>9:00 AM</td>
			  				<td>RM-4012</td>
			  				
			  			</tr>
			  			<tr>
			  				<td>2016-04-04</td>
			  				<td>9:00 AM</td>
			  				
			  				<td>RM-4012</td>
			  				
			  			</tr>
			  			<tr>
			  				<td>2016-04-05</td>
			  				<td>9:00 AM</td>
			  			
			  				<td>RM-4012</td>
			  				
			  			</tr>
			  			<tr>
			  				<td>2016-04-08</td>
			  				<td>9:00 AM</td>
			  				
			  				<td>RM-4012</td>
			  				
			  			</tr>
			  		</tbody>
			  	</table>
			  </div><!--/info body-->
			</div>
		</div>
		<!--sidebar widget-->
		<div class="col-md-3 schedule">
			@include('pages.student.student-widget')
		</div>
		<!--/sidebar widget-->
	</div>

@stop