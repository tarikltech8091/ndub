@extends('application.layout.master')
@section('content')
<div class="row col-md-12">
	<div class="page-header" >
		<center class="header-name">Addmission Examintation</center>
	</div>
</div>
<div class="col-md-12 border-3">
	<div class="col-md-6 padding-5">
		<div class="form-group">
			<label class="col-md-3 control-label mtop-7"><b>Serial No :</b></label>
			<div class="col-md-6">
				<input type="text" class="form-control"placeholder="0101010101" disabled="">
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group mtop-7" >
			<label >
				<b>Gender: </b>
			</label>

			<label >
				<input type="checkbox" checked="" > <span>Male</span>
			</label>

			<label >
				<input type="checkbox" > <span> Female</span>
			</label>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-md-12">
		<table class="table border-3 mtop-30_wd100">

			<tr>
				<td class="wd15_f25" >
					Program :
				</td>
				<td  colspan="2" style="font-size:25px;">
					Computer Science and Engineering
				</td>
			</tr>



			<tr>
				<td style="width:15%" style="border-top:3px solid black">
					<ul class="ul-class">
						<li>Applicant's Name</li>
						<li>Father's Name</li>
						<li>Semester</li>
						<li>Academic Year</li>
						<li>Contact</li>
					</ul>

				</td>
				<td style="width:1%">
					<ul class="ul-class">
						<li>:</li>
						<li>:</li>
						<li>:</li>
						<li>:</li>
						<li>:</li>
					</ul>

				</td>
				<td >
					<ul class="ul-class">
						<li>K.B.M Mirajul Islam Bappy</li>
						<li>Uuk Tumuk</li>
						<li>Spring</li>
						<li>2016</li>
						<li>01722000000</li>
					</ul>

				</td>
				<td style="width:30%"><center>
					<img src="images/profile1.png" style="padding:3px; height:140px; width:130px; border:3px solid #2BB7B5; " />
				</center>
			</td>

		</tr>


	</table>

</div>
</div>
@stop