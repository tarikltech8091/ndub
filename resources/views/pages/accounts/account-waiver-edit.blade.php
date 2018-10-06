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
 			<div class="panel-heading">Edit Student Waiver</div>
 			<div class="panel-body"><!--info body-->

 				<form action="{{url('/accounts/waiver/edit/'.$edit_waiver_category->waiver_name_slug)}}" method="post" enctype="multipart/form-data">
 					<input type="hidden" name="_token" value="{{csrf_token()}}">
 					<div class="form-group">
 						<label>Account Waiver</label>
 						<input type="text" name="waiver_name" class="form-control"
 						value="{{$edit_waiver_category->waiver_name}}" placeholder="Ex:- Financial Waiver" required/>  
 					</div>

 					<div class="form-group">
 						<label>Waiver Rate (%)</label>
 						<input type="text" name="waiver_rate" class="form-control"
 						value="{{$edit_waiver_category->waiver_rate}}" placeholder="Ex:- 20" required/>  
 					</div>

 					<div class="form-group pull-right">
 						<a href="{{url('/accounts/waiver')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
 						<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Waiver">
 					</div>
 				</form>

 			</div><!--/info body-->
 		</div>
 	</div>

 </div>

 @stop