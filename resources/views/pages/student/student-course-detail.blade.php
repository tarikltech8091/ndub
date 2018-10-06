@extends('admin.layout.master')
@section('content')

@include('admin.layout.bradecrumb')
<div class="row page_row">
	
	<div class="col-md-9 course">
		
		<div class="panel panel-info">
			<div class="panel-heading">All Courses</div>
			<div class="panel-body"><!--info body-->
				<div class="but_list"><!--course Tab-->
					<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
						<ul id="myTab" class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#1st" id="1st-tab" role="tab" data-toggle="tab" aria-controls="1st" aria-expanded="true">1st Year</a></li>
							<li role="presentation"><a href="#2nd" role="tab" id="2nd-tab" data-toggle="tab" aria-controls="2nd">2nd Year</a></li>

							<li role="presentation"><a href="#3rd" role="tab" id="3rd-tab" data-toggle="tab" aria-controls="3rd">3rd Year</a></li>

							<li role="presentation"><a href="#4th" role="tab" id="4th-tab" data-toggle="tab" aria-controls="4th">4th Year</a></li>
							
						</ul>
						<div id="myTabContent" class="tab-content"><!--Spring tab content-->
							<div role="tabpanel" class="tab-pane fade in active" id="1st" aria-labelledby="1st-tab">

								<div class="panel panel-danger">
									<div class="panel-heading text-right">Spring</div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Fall </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Summer </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								
							</div><!--Spring tab content-->


							<div role="tabpanel" class="tab-pane fade" id="2nd" aria-labelledby="2nd-tab">
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Spring </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Fall </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Summer</div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
							</div><!--2nd tab content-->

							<div role="tabpanel" class="tab-pane fade" id="3rd" aria-labelledby="3rd-tab">
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Spring </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Fall </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Summer </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								
							</div><!--summer tab content-->

							<div role="tabpanel" class="tab-pane fade" id="4th" aria-labelledby="4th-tab">
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Summer </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Fall </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								<div class="panel panel-danger">
									<div class="panel-heading text-right">Summer </div>
									<div class="panel-body"><!--info body-->
										
										<table id="" class="table table-striped table-bordered table-hover">

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
								
							</div><!--summer tab content-->
							
						</div>
					</div>
				</div><!--course Tab-->
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