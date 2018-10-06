<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="margin: 0; padding: 0; background: #ece8df;">

	<div class="main-area" style="background: #f2efe9;	padding-bottom: 60px;">

		<div class="logo" style="text-align: center; padding-top: 50px;	padding-bottom: 36px;">
			<img src="{{asset('images/banner-form.png')}}" alt="logo" style="width: 420px; height: 100px;">
		</div>

		<div class="content" style="background: #fff; width: 420px;	padding: 60px 90px;	margin: 0 auto;">
		@yield('content')
			
		</div>

	</div>

	<div class="mail-footer">

		<div class="content" style="width: 420px; padding: 60px 90px; margin: 0 auto; background: #ece8df;">

			<p>NOTRE DAME UNIVERSITY BANGLADESH <br />

				<a href="mailto:support@ndub.edu.bd" style="color: gray; text-decoration: none;"> support@ndub.edu.bd </a>

			</p>

		</div>

		<div class="clearfix" style="clear: both;"></div>

	</div>
</body>
</html>