@extends('layout.master')
@section('content')
@include('layout.bradecrumb')
	<div class="row page_row">
		<div class="col-md-12">
			<div class="panel panel-info padding_0">
			  <div class="panel-heading">Notice Information</div>
			  <div class="panel-body"><!--info body-->
			  <label>Notice List</label>
					<table class="table table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>SL</th>
								<th>Notice Subject</th>
								<th>Notice From</th>
								<th>Notice For</th>
								<th>Program</th>
								<th>Semester</th>
								<th>Year</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($student_all_notice) && count($student_all_notice) > 0)
							@foreach($student_all_notice as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->notice_subject}}</td>
								<td>{{$list->notice_from_type}}</td>
								<td>{{$list->notice_to}}</td>
								<td>{{(isset($list) && ($list->program_title)) ? $list->program_title : 'all'}}</td>
								<td>{{$list->semester_title}}</td>
								<td>{{$list->notice_year}}</td>
								<td>{{$list->created_at}}</td>
								<td><a style="font-weight:bold; text-decoration:none; margin-left:15px;" data-toggle="modal" data-target="#studentnoticeModal"  data-id="{{$list->notice_tran_code}}" class="text_none student_notice_show" href=""><i class="fa fa-envelope" aria-hidden="true" data-toggle1="tooltip" title="View Notice"></i></a>
								</td>
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="9">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($student_all_notice_pagination) ? $student_all_notice_pagination:""}}

			  </div><!--/info body-->
			</div>
		</div>		
	</div>

	<div id="studentnoticeModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Notice</h4>
			</div>
			<div class="modal-body">
			    <div class="notice_student_form">
			    	
			    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

@stop