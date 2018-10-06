@extends('admin.layout.master')
@section('content')

@include('admin.layout.bradecrumb')
<div class="row page_row">
	
	<div class="col-md-9">
		<div class="panel panel-info">
			<div class="panel-heading ">Spring 2016 <span><a href="#" onclick="printDiv('printableArea')"><i class="fa fa-print"></i></a></span></div>
			<div class="panel-body"><!--info body-->
				
				<table id="printableArea" class="table table-striped table-bordered table-hover">

					<thead>
						<tr>
							<th>Course ID</th>
							<th>Course Titile</th>
							<th>Credit</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>CSE 1101</td>
							<td>Computer Fundamental</td>
							<td>3</td>
						</tr>

						<tr>
							<td>CSE 1101</td>
							<td>Computer Fundamental</td>
							<td>3</td>
						</tr>

						<tr>
							<td>CSE 1101</td>
							<td>Computer Fundamental</td>
							<td>3</td>
						</tr>
						<tr>
							<th colspan="2">Total Credit</th>
							<th>9</th>
						</tr>
					</tbody>
				</table>
			</div><!--/info body-->
		</div>
	</div>

	<!--sidebar widget-->
	<div class="col-md-3">
		@include('admin.pages.student.student-widget')
	</div>
	<!--/sidebar widget-->
</div>

@stop