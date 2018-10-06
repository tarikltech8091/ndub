<!DOCTYPE HTML>
<html>
<head>
	<title> ADMIT CARD | NDUB </title>
<!-- 	<meta name="viewport" content="wnameth=device-wnameth, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
		
	<!-- Custom Application form Css -->
	<style type="text/css">

		p {
			margin: 0px;
			padding: 0px;
		}

	</style>

</head> 

<body>
	<div style="width:100%; height: 900px; background-color: #F1F1F1;">

		<table style="width:100%; padding:5px; background-color: #F1F1F1;">
			<tr>
				<td style="width:15%"><img src="<?php echo asset('images/plain-logo.png')?>" style="height:80px"></td>
				<td style="font-weight:bold; font-size:30px; color:#26A0C9;"><center>Notre Dame University Bangladesh (NDUB)</center></td>
			</tr>
		</table>
		<table style="width:100%; padding:5px; background-color: #F1F1F1;">
			<tr>
				<td style="font-weight:bold; font-size:25px; color:#26A0C9; text-align: center;"><center>Addmission Exam</center></td>
			</tr>
			<tr>
				<td style="font-weight:bold; font-size:20px; color:#26A0C9; text-align: center;"><center>ADMIT CARD</center></td>
			</tr>
		</table>

		<table style="width:96%; border: 1px solid black;  margin-left:auto; margin-right:auto; ">
			<tr>
				<td style="padding-left:10px;">Serial No: <?php echo $applicant_info->applicant_serial_no;?></td>
				<td style="width:20%; ">Gender: Male <input name="gender" value="male" type="checkbox" <?php echo $applicant_info->gender =="male" ? "checked" :"";?> /></td>
				<td>Female <input type="checkbox" name="gender" value="female" <?php echo $applicant_info->gender =="female" ? "checked" :"";?> /></td>
			</tr>
			
		</table><br>

		<table style="width:96%; border: 1px solid black; border-bottom:hidden;  margin-left:auto; margin-right:auto; ">

			<tr>
				<td style="font-weight:bold; font-size:20px; padding-left:10px;">Program :</td>
				<td colspan="2" style="font-weight:bold; font-size:20px;"><?php echo $applicant_info->program_title;?></td>
			</tr>
			
		</table>

		<table style="width:96%; border:1px solid black; margin-left:auto; margin-right:auto; padding-bottom:10px;">
			<tr>
				<td style="width:70%;">
					<table style="width:100%; padding:5px;">
						<tr>
							<td style="width:30%; height:25px; ">Applicant's Name</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_info->first_name.' '.$applicant_info->middle_name.' '.$applicant_info->last_name;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Trimester</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_info->semester_title;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Academic Year</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_info->academic_year;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Contact</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_info->mobile;?></td>
						</tr>
					</table>
				</td>

				<td style="width:30%;">
					<center>
						<img src="<?php echo asset($applicant_info->app_image_url); ?>" alt="<?php echo asset($applicant_info->app_image_url); ?>" style="margin-top:15px; height:140px; width:130px; border:3px solid #afbfbd; " />
					</center>
				</td>
			</tr>
		</table>
		<br><br><br>

		<table style="width:96%; border-bottom:1px dashed black; margin-left:auto; margin-right:auto;">
			<tr>
				<td  style="font-weight:bold; font-size:15px; "></td>
				<td  style=" font-size:15px; padding-bottom:5px; width:30%; text-align:center">
					<table>
						<tr>
							<td>
								<img src="<?php echo asset('images/authorized-signature.jpg'); ?>" height="60px" width="200px">
							</td>
						</tr>
						<tr><td style=" text-align: center;"><hr><b>Authorized Signature</b></td></tr>
					</table>
				</td>
			</tr>
		</table><br><br>


		<table style="width:96%; border-collapse: collapse;  margin-left:auto; margin-right:auto;">
			<tr>
				<th style="border: 1px solid black; width:50% " align="center">Bring</th>
				<th style="border: 1px solid black;" align="center">Do Not Bring</th>
			</tr>
			<tr>
				<td  style="vertical-align:top; border: 1px solid black;">
					<ul>
						<li>Admit Card</li>
						<li>Black Ballpoint Pen</li>
					</ul>
				</td>

				<td style="vertical-align:top; border: 1px solid black;">
					<ul>
						<li>Bags or Mobile Phones</li>
						<li>Calculator</li>
					</ul>
				</td>
			</tr>

			<tr>
				<td  style="vertical-align:top;  padding-bottom:20px;" colspan="2">
					<center>
						<h3>Addmission Information</h3>

						<p>Notre Dame University Bangladesh</p>
						<p>2 Arambag, Motijheel, GPO Box-7,</p>
						<p>Dhaka-1000, Bangladesh</p>
						<p>Phone: +8802-7195972, +8802-7195992</p>
						<p>Email: info@ndub.edu.bd; Web: www.ndub.edu.bd</p><br>
					</center>
					
				</td> 
			</tr>	
		</table>


	</div>
</body>
</html>
