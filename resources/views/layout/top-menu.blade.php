
@if($page_title != 'Forgot Password')
@if($page_title != 'Forgot Password Varify')
<!--toggle button start-->
<a class="toggle-btn  menu-collapsed"><i class="fa fa-bars"></i></a>
<!--toggle button end-->

<!--notification menu start -->
<div class="menu-right">
	<div class="user-panel-top">  	
		<div class="profile_details_left ">
			
		</div>
		<div class="page_name profile_details_left col-md-8">
			{{isset($page_title) ? $page_title:''}}
		</div>
		<div class="profile_details">		
			<ul>
				<li class="dropdown profile_details_drop cursor">
					<a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle">
						<div class="profile_img">	
							<span style="background:url(images/1.jpg) no-repeat center"> </span> 
							<div class="user-name">
								@if(\Auth::check())
								<?php
								$user_name = explode(' ', \Auth::user()->name);
								?>
								<p>{{isset($user_name[2])?$user_name[2]:$user_name[0]}}
								<span>
								@if((\Auth::user()->user_type) == 'register')
								registrar
								@else
								{{\Auth::user()->user_type}}
								@endif
								</span>
								</p>
								@else
								<p>Michael<span>Administrator</span></p>
								@endif
								
							</div>
							<i class="lnr lnr-chevron-down"></i>
							<i class="lnr lnr-chevron-up"></i>
							<div class="clearfix"></div>	
						</div>	
					</a>
					<ul class="dropdown-menu drp-mnu">
						<li> <a href="{{url('/'.\Auth::user()->user_type.'/'.\Auth::user()->name_slug.'/home')}}"><i class="fa fa-user"></i>Dashboard</a> </li> 
						<li> <a href="{{url('/'.\Auth::user()->user_type.'/change-password/'.\Auth::user()->user_id)}}"><i class="fa fa-key"></i>Change Password</a> </li> 
						<li> <a onclick="location.href='{{url('/logout',\Auth::user()->name_slug)}}';"><i class="fa fa-sign-out"></i> Logout</a> </li>
					</ul>
				</li>
				<div class="clearfix"> </div>
			</ul>
		</div>		          	
		<div class="clearfix"></div>
	</div>
</div>
<!--notification menu end -->

@endif			
@endif