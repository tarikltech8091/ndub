@extends('layout.master')
@section('content')
@include('layout.bradecrumb')


<div class="page_row row"><!--message-->
	<div class="col-md-12">
		<!--error message*******************************************-->
		@if($errors->count() > 0 )

		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h6>The following errors have occurred:</h6>
			<ul>
				@foreach( $errors->all() as $message )
				<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@if(Session::has('message'))
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div> 
		@endif

		@if(Session::has('errormessage'))
		<div class="alert alert-danger" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('errormessage') }}
		</div>
		@endif
		<!--*******************************End of error message*************************************-->
	</div>
</div><!--/message-->


<div class="row page_row">
	<div class="col-md-9 advising">
		<div class="panel panel-info">
			<div class="panel-heading">Course Information</div>
			<div class="panel-body"><!--info body-->

				<div class="page_row">
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>SL</th>
								<th>Program</th>
								<th>Trimester</th>
								<th>Year</th>
								<th>Level</th>
								<th>Term</th>
								<th>Advise</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($assigned_program_cordinator))
							@foreach($assigned_program_cordinator as $key => $cordinator)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$cordinator->program_title}}</td>
								<td>{{$cordinator->semester_title}}</td>
								<td>{{$cordinator->program_coordinator_year}}</td>
								<td>{{$cordinator->program_coordinator_level}}</td>
								<td>{{$cordinator->program_coordinator_term}}</td>
								<td><center>
									<button style="padding-top:0;padding-bottom:0;" type="button" class="btn btn-primary btn-xs student_pre_advising_info" data-id="{{$cordinator->program_id}}" data-level="{{$cordinator->program_coordinator_level}}" data-term="{{$cordinator->program_coordinator_term}}" data-semester="{{$cordinator->program_coordinator_semester}}" data-year="{{$cordinator->program_coordinator_year}}">View</button>
								</center>
							</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6">
								<div class="alert alert-success">
									<center><h3 style="font-style:italic">You are not assigned as cordinator !</h3></center>
								</div>
							</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>



			<!-- <a href="" class="clickme">Advise</a> -->
			<!--Serach Result-->
			<div class="col-md-10 col-md-offset-1 advising_result">
				<div id="pre_advising_list">

				</div>
			</div>
			<!--/Serach Result-->

		</div><!--/info body-->
	</div>
</div>
<!--sidebar widget-->
<div class="col-md-3">
	@include('pages.faculty.faculty-notice')
</div>
<!--/sidebar widget-->

</div>


@stop