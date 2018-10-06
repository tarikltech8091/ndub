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
	<div class="col-md-5">
		<div class="panel panel-info">
			<div class="panel-heading">Grade Information</div>
			<div class="panel-body"><!--info body-->
				<form action="{{url('/register/student-grade-equivalent/submit')}}" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="lowest_margin">Lowest Margin<span class="required-sign">*</span></label>
							<input type="text" class="form-control uppercase_name" name="lowest_margin" placeholder="Highest Margin" value="{{old('lowest_margin')}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Highest Margin">Highest Margin<span class="required-sign">*</span></label>
							<input type="text" class="form-control uppercase_name" name="highest_margin" placeholder="Highest Margin" value="{{old('highest_margin')}}">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="Grade Point">Grade Point<span class="required-sign">*</span></label>
							<input type="text" class="form-control uppercase_name" name="grade_point" placeholder="Grade Point" value="{{old('grade_point')}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Letter Grade">Letter Grade<span class="required-sign">*</span></label>
							<input type="text" class="form-control uppercase_name" name="letter_grade" placeholder="Letter Grade" value="{{old('letter_grade')}}">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="Eqivalence">Eqivalence<span class="required-sign">*</span></label>
							<input type="text" class="form-control uppercase_name" name="eqivalence" placeholder="Eqivalence" value="{{old('eqivalence')}}">
						</div>
						<div class="form-group col-md-6">
							<label for="Remarks">Remarks</label>
							<!-- <input type="text" class="form-control uppercase_name" name="remarks" placeholder="Middle Name" value="{{old('remarks')}}"> -->
							<textarea class="form-control" name="remarks" value="{{old('remarks')}}"  placeholder="Remarks"></textarea>
						</div>
					</div>
					<div class="pull-right">
						<a href="{{\Request::url()}}" class="btn btn-danger">Reset</a>
						<input type="submit" class="btn btn-primary " value="Submit">
					</div>
				</form>
			</div><!--/info body-->
		</div>
	</div>
	<div class="col-md-7">
		<div class="panel panel-info">
			<div class="panel-heading">Grade Equivalence List</div>
			<div class="panel-body">

				<table class="table table-hover table-bordered table-striped nopadding" >
					<thead>
						<tr>
							<th>SL</th>
							<th>Date</th>
							<th>Lowest Mark</th>
							<th>Highest Mark</th>
							<th>Grade Point</th>
							<th>Letter Grade</th>
							<th>Equivalnce</th>
							<th>remarks</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($grade_data) && count($grade_data) > 0)
						@foreach($grade_data as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{date('Y-m-d', strtotime($list->updated_at))}}</td>
							<td>{{$list->lowest_margin}}</td>
							<td>{{$list->highest_margin}}</td>
							<td>{{$list->grade_point}}</td>
							<td>{{$list->letter_grade}}</td>
							<td>{{$list->eqivalence}}</td>
							<td>{{$list->remarks}}</td>
							<td style="possition:relative">
								<a href="{{url('/register/student-grade-equivalent/edit',$list->grade_equivalent_tran_code)}}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Grade"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a data-confirm-url="{{url('/register/student-grade-equivalent/delete',$list->grade_equivalent_tran_code)}}" class="btn btn-default btn-xs confirm_box cursor" data-toggle="tooltip" title="Delete Grade"><i class="fa  fa-trash-o"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="9">
							<div class="alert alert-success">
								<center><h3 style="font-style:italic">No Data Found !</h3></center>
							</div>
						</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($grade_pagination) ? $grade_pagination:""}}
		</div>
	</div>
</div>

</div>

@stop