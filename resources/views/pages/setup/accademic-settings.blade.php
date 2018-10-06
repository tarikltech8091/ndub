@extends('layout.master')
@section('content')
	
@include('layout.bradecrumb')
	<div class="row page_row">
		
		<div class="col-md-7 semester profile_tab">

			<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs" role="tablist">
				  <li role="presentation" class="active"><a href="#department" role="tab" id="department-tab" data-toggle="tab" aria-controls="department"><i class="fa fa-map-o"></i>Deperment</a></li>
				 
				  <li role="presentation" ><a href="#programe" id="programe-tab" role="tab" data-toggle="tab" aria-controls="programe" aria-expanded="true"><i class="fa fa-map-signs"></i>Programs</a></li>

				  <li role="presentation"><a href="#campus" role="tab" id="campus-tab" data-toggle="tab" aria-controls="campus"><i class="fa fa-newspaper-o"></i>Campus</a></li>

				  <li role="presentation"><a href="#building" role="tab" id="building-tab" data-toggle="tab" aria-controls="building"><i class="fa fa-file-word-o"></i>Building</a></li>	

				  <!-- <li role="presentation"><a href="#4th" role="tab" id="4th-tab" data-toggle="tab" aria-controls="4th"><i class="fa fa-file-word-o"></i>Course</a></li>	 -->		 
				</ul>
				<div id="myTabContent" class="tab-content"><!--main tab content-->

					<div role="tabpanel" class="tab-pane fade in active" id="department" aria-labelledby="department-tab"><!--department tab-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Department Name</label>
							  		<input type="text" name="title" class="form-control"  />	
								</div>

								<div class="form-group">
									<label>Department Code</label>
							  		<input type="text" name="title" class="form-control"  />	
								</div>
						  		
						  		<div class="form-group">
									<input type="submit" class="btn btn-default" value="Cancel">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
						  	</div>
						  	<div class="col-md-6">
						  		<table class="table table-striped  table-hover">
						  			<thead>
						  				<tr>
						  					<th>SL</th>
						  					<th>Department Name</th>
						  					<th>Dept. Code</th>
						  					<th>Manage</th>
						  				</tr>
						  			</thead>
						  			<tbody>
										<tr>
											<td>1</td>
											<td>Department of Architecture</td>
											<td>11</td>
											<td><a href="#"><i class="fa  fa-pencil-square-o"></i></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Department of Pharmecy</td>
											<td>12</td>
											<td><a href="#"><i class="fa  fa-pencil-square-o"></i></a></td>
										</tr>
									</tbody>
						  		</table>
						  	</div>
						</div>
					</div><!--/department tab-->
					<div role="tabpanel" class="tab-pane fade " id="programe" aria-labelledby="programe-tab"><!--/programe tab-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Department</label>
									<select class="form-control">
							  			<option value="">Select Department</option>
							  			<option value="cse">Department of Architecture</option>
							  			<option value="bba">Department of Pharmecy</option>
							  		</select>
								</div>
								<div class="form-group">
									<label>Program Name</label>
									<input type="text" name="title" class="form-control"  />
								</div>
								<div class="form-group">
									<label>Program Code</label>
							  		<input type="text" name="title" class="form-control"  />	
								</div>
								<div class="form-group">
									<label>Program keyword</label>
									<input type="text" name="title" class="form-control"  />
								</div>
								<div class="form-group">
									<input type="submit" class="btn btn-default" value="Cancel">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
							</div>
						</div>
					</div><!--/programe tab-->

					<div role="tabpanel" class="tab-pane fade " id="building" aria-labelledby="building-tab"><!--/campus tab-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Campus Name</label>
							  		<select class="form-control">
							  			<option value="">Select Campus</option>
							  			<option value="cse">Arambag 1</option>
							  			<option value="bba">Arambag 2</option>
							  		</select>	
								</div>
						  		<div class="form-group">
									<label>Building Name</label>
									<input type="text" name="title" class="form-control"  />
								</div>
								<div class="form-group">
									<label>Building keyword</label>
									<input type="text" name="title" class="form-control"  />
								</div>
								<div class="form-group">
									<label>Building Code</label>
									<input type="text" name="title" class="form-control"  />
								</div>
						  		<div class="form-group">
									<input type="submit" class="btn btn-default" value="Cancel">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
						  	</div>
						</div>

					</div><!--/campus tab-->

					<div role="tabpanel" class="tab-pane fade " id="campus" aria-labelledby="campus-tab"><!--/building tab-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Campus Name</label>
							  		<input type="text" name="title" class="form-control"  />	
								</div>
						  		
						  		<div class="form-group">
									<input type="submit" class="btn btn-default" value="Cancel">
									<input type="submit" class="btn btn-success" value="Save">
								</div>
						  	</div>
						</div>

					</div><!--/building tab-->
		    </div><!--/main tab content-->
		</div>
	</div>
</div>

@stop