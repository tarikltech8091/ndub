
@if($type=='ssc')

<div class="ssc_add_subject_wrapper_{{$add_subject_count}}">
	<div class="form-group col-md-7">
		<input type="text" class="form-control uppercase_name"  name="ssc_olevel_subject_{{$add_subject_count}}" placeholder="Subject {{$add_subject_count}}" value="">
	</div>

	<div class="form-group col-md-2">

		<input type="text"  class="form-control uppercase_name select_point" data-subgp='{{$add_subject_count}}' name="ssc_olevel_subject_gpa_{{$add_subject_count}}" placeholder="Ex:- 5.00" value="">

	</div>

	<div class="form-group col-md-2">

		<input type="text" class="form-control uppercase_name" data-subgrd='{{$add_subject_count}}' name="ssc_olevel_subject_grade_{{$add_subject_count}}" placeholder="Ex:- A+" id="ssc_olevel_subject_grade_{{$add_subject_count}}" value="">

	</div>
	<div class="form-group col-md-1">
		<a class="btn btn-danger delete_ssc_sub" data-subgrd="{{$add_subject_count}}"><i class="fa fa-times" aria-hidden="true"></i></a>
	</div>
</div>

<input type="hidden" name="multi_ssc_subject_count_ajax" value="{{$add_subject_count}}" >

@elseif($type=='hsc')
<div class="hsc_add_subject_wrapper_{{$add_subject_count}}">

	<div class="form-group col-md-7">
		<input type="text" class="form-control uppercase_name"  name="hsc_alevel_subject_{{$add_subject_count}}" placeholder="Subject {{$add_subject_count}}" value="">
	</div>
	<div class="form-group col-md-2">

		<input type="text"  class="form-control uppercase_name select_hsc_point" data-hscsubgp='{{$add_subject_count}}' name="hsc_alevel_subject_gpa_{{$add_subject_count}}" placeholder="Ex:- 5.00" value="">

	</div>
	<div class="form-group col-md-2">

		<input type="text" class="form-control uppercase_name" data-hscsubgrd='{{$add_subject_count}}' name="hsc_alevel_subject_grade_{{$add_subject_count}}" placeholder="Ex:- A+" id="hsc_alevel_subject_grade_{{$add_subject_count}}" value="">
	</div>
	<div class="form-group col-md-1">
		<a class="btn btn-danger delete_hsc_sub" data-hscsubgrd="{{$add_subject_count}}"><i class="fa fa-times" aria-hidden="true"></i></a>
	</div>

</div>

<input type="hidden" name="multi_hsc_subject_count_ajax" value="{{$add_subject_count}}" >
@endif