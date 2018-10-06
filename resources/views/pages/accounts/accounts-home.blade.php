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
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success dash_pad_0">
			<div class="row page_row_dash">

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/applicant/total-amount')}}';">
						<p>	
							<a href="{{url('/accounts/applicant/total-amount')}}"><i class="fa fa-list" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/applicant/total-amount')}}">Applicant List</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/applicant/payment')}}';">
						<p>	
							<a href="{{url('/accounts/applicant/payment')}}"><i class="fa fa-list" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/applicant/payment')}}">Applicant Payment List</a>
						</p>
					</div>
				</div><!--/reprtcard-->
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/applicant/cash-payment')}}';">
						<p>	
							<a href="{{url('/accounts/applicant/cash-payment')}}"><i class="fa fa-list-alt" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/applicant/cash-payment')}}">Applicant Cash Payment</a>
						</p>
					</div>
				</div><!--/reprtcard-->
				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/admission/payement/list')}}';">
						<p>	
							<a href="{{url('/accounts/admission/payement/list')}}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/admission/payement/list')}}">Admmission Payment List</a>
						</p>
					</div>
				</div><!--/reprtcard-->


				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/account-summery')}}';">
						<p>	
							<a href="{{url('/accounts/account-summery')}}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/account-summery')}}">Accounts Summary</a>
						</p>
					</div>
				</div><!--/reprtcard-->

				<div class="col-md-2"><!--reprtcard-->
					<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/student-payment-transaction')}}';">
						<p>	
							<a href="{{url('/accounts/student-payment-transaction')}}"><i class="fa fa-money" aria-hidden="true"></i>
							</a>
						</p>
						<p class="report_name">	
							<a href="{{url('/accounts/student-payment-transaction')}}">Student Accounts Transaction</a>
						</p>
					</div>
				</div><!--/reprtcard-->


			</div>


				<div class="row page_row_dash">

					<div class="col-md-2"><!--reprtcard-->
						<div class="report_view reprt_color_1 cursor dashborad_menus" onclick="location.href='{{url('/accounts/student/payment/summery')}}';">
							<p>	
								<a href="{{url('/accounts/student/payment/summery')}}"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
							</p>
							<p class="report_name">	
								<a href="{{url('/accounts/student/payment/summery')}}">Student Payment Summary</a>
							</p>
						</div>
					</div><!--/reprtcard-->
					
					@if((\Auth::user()->user_type) == 'accounts' && (\Auth::user()->user_role) == 'head') 

						<div class="col-md-2"><!--reprtcard-->
							<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/waiver')}}';">
								<p>	
									<a href="{{url('/accounts/waiver')}}"><i class="fa fa-bar-chart" aria-hidden="true"></i>
									</a>
								</p>
								<p class="report_name">	
									<a href="{{url('/accounts/waiver')}}">Accounts Waiver Name List</a>
								</p>
							</div>
						</div><!--/reprtcard-->


						<div class="col-md-2"><!--reprtcard-->
							<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/fee-category')}}';">
								<p>	
									<a href="{{url('/accounts/fee-category')}}"><i class="fa fa-list" aria-hidden="true"></i>
									</a>
								</p>
								<p class="report_name">	
									<a href="{{url('/accounts/fee-category')}}">Account Fee Category</a>
								</p>
							</div>
						</div><!--/reprtcard-->


						<div class="col-md-2"><!--reprtcard-->
							<div class="report_view reprt_color_1 cursor dashborad_menus centered" onclick="location.href='{{url('/accounts/fee-payment')}}';">
								<p>	
									<a href="{{url('/accounts/fee-payment')}}"><i class="fa fa-money" aria-hidden="true"></i>
									</a>
								</p>
								<p class="report_name">	
									<a href="{{url('/accounts/fee-payment')}}">Account Fee Payment List</a>
								</p>
							</div>
						</div><!--/reprtcard-->
					@endif



				</div>
				


		</div>
	</div>
</div>


@stop