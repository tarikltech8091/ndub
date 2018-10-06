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
 		<!-- <h4>Edit Account Fee Type</h4> -->
 		<form action="{{url('/accounts/fee-types/edit/'.$edit_fee_type->fee_types_name_slug)}}" method="post" enctype="multipart/form-data">
 			<input type="hidden" name="_token" value="{{csrf_token()}}">
 			<div class="form-group">
 				<label>Account Fee Types Name</label>
 				<input type="text" name="fee_types_name" class="form-control"
 				value="{{$edit_fee_type->fee_types_name}}" required/>  
 			</div>

 			<div class="modal-footer">
 				<input type="submit" class="btn btn-success" value="Update">
 			</div>
 		</form>
 	</div>
 </div>

 @stop