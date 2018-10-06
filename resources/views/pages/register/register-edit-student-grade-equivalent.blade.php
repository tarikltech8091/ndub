@extends('layout.master')
@section('content')
@include('layout.bradecrumb')

<!--error message*******************************************-->
<div class="row page_row">
	<div class="col-md-12">
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

	</div>
</div>
<!--end of error message*************************************-->


<div class="row page_row">
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Grade Information</div>
			<div class="panel-body"><!--info body-->
				<form action="{{url('/register/student-grade-equivalent/update',$first_grade_data->grade_equivalent_tran_code)}}" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="lowest_margin">Lowest Margin<span class="required-sign">*</span></label>
							<input type="text" class="form-control" name="lowest_margin" placeholder="Highest Margin" value="{{$first_grade_data->lowest_margin}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Highest Margin">Highest Margin<span class="required-sign">*</span></label>
							<input type="text" class="form-control" name="highest_margin" placeholder="Highest Margin" value="{{$first_grade_data->highest_margin}}">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="Grade Point">Grade Point<span class="required-sign">*</span></label>
							<input type="text" class="form-control" name="grade_point" placeholder="Grade Point" value="{{$first_grade_data->grade_point}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Letter Grade">Letter Grade<span class="required-sign">*</span></label>
							<input type="text" class="form-control" name="letter_grade" placeholder="Letter Grade" value="{{$first_grade_data->letter_grade}}">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="Eqivalence">Eqivalence<span class="required-sign">*</span></label>
							<input type="text" class="form-control" name="eqivalence" placeholder="Eqivalence" value="{{$first_grade_data->eqivalence}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Remarks">Remarks</label>
							<textarea class="form-control" name="remarks" value="{{old('remarks')}}"  placeholder="Remarks">{{$first_grade_data->remarks}}</textarea>
						</div>
					</div>
					<div class="pull-right">
						<a href="{{url('/register/student-grade-equivalent')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
						<input type="submit" class="btn btn-primary " data-toggle="tooltip" title="Update Grade Equivalent" value="Update">
					</div>
				</form>
			</div><!--/info body-->
		</div>
	</div>
	
</div>

@stop