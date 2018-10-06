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
 		<div class="panel-heading">Edit Fee Category</div>
 			<div class="panel-body"><!--info body-->

 				<form action="{{url('/accounts/fee-category/edit/'.$edit_fee_category->fee_category_name_slug)}}" method="post" enctype="multipart/form-data">
 					<input type="hidden" name="_token" value="{{csrf_token()}}">
 					<div class="form-group">
 						<label>Account Fee Category Name</label>
 						<input type="text" name="fee_category_name" class="form-control"
 						value="{{$edit_fee_category->fee_category_name}}" required/>  
 					</div>

 					<div class="form-group pull-right">
 						<a href="{{url('/accounts/fee-category')}}" class="btn btn-danger" data-toggle="tooltip" title="Cancel Edit">Cancel</a>
 						<input type="submit" class="btn btn-success" value="Update" data-toggle="tooltip" title="Update Fee Category">
 					</div>
 				</form>

 			</div><!--/info body-->
 		</div>
 	</div>

 </div>

 @stop