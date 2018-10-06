<?php
$count = count(\Request::segments());
$last_segment = \Request::segments(3);
$last_segment[$count-1];

?>



<div class="row page_row">
	<div class="col-md-12">
		<div class="page_heading_breadcrumb">
			<ol class="breadcrumb">
			  <li class="cursor"><a href="{{url(\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class='fa fa-home'></i>Dashboard</a></li>
			  <li class="active cursor"><a href="{{\Request::url()}}">{{isset($page_title)? $page_title:''}}</a></li>
			 
			</ol>
		</div>
	</div>
</div>

