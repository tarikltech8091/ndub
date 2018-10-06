
<div class="banner-bottom-video-grid-left side_bar_right">
	<div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
		<div class="panel panel-danger">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title asd">
					<a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
						<span class="fa fa-plus"></span><i class="fa fa-minus"></i><label>Latest news</label>
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body panel_text today_schedule_list">
					<?php 
					$notice_board =\App\notice::FacultyNoticeInfo();
					?>

					@if(!empty($notice_board))
					<marquee  direction="up" scrollamount="3" onMouseOver="this.setAttribute('scrollamount', 0, 0);" OnMouseOut="this.setAttribute('scrollamount', 3, 0);">	

						<ul>

							@foreach($notice_board as $key => $list)
							<li class="alert alert-success">
								<p style="font-size:12px;">
									<i class="fa fa-calendar-o"></i>{{$list->created_at}}&nbsp;
									<span style="color:#F50A19;"><i> Form : {{$list->notice_from_type}}</i></span>
								</p>
								<a style="font-weight:bold; text-decoration:none; margin-left:15px;" data-toggle="modal" data-target="#facultynoticeModal"  data-id="{{$list->notice_tran_code}}" class="text_none notice_show" href="">{{str_limit($list->notice_subject, 20)}}</a>
							</li>
							@endforeach
							
						</ul>
					</marquee>
					<div class="more_btn">
						<a href="{{url('/faculty/all/notice')}}" class="btn btn-primary" data-toggle="tooltip" title="View All Notice">View All</a>
					</div>
					@else
					<div class="alert alert-success text-center">No Notice Available !</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div id="facultynoticeModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Notice</h4>
			</div>
			<div class="modal-body">

				<div class="notice_setting_form">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>
