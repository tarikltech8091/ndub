<!DOCTYPE HTML>
<html>
<head>
	<title> Applicatnt Info | NDUB </title>

	
	<style type="text/css">

		.container{
			height: 510px;
			width: 100%;
			background-color: #F1F1F1;
		}

		.header{
			height: auto;
			width: 100%;
			margin-left: auto;
			margin-right: auto;
			padding-top: 20px;
			font-weight: bold;
		}

		.main{
			height: auto;
			width: 98%;
			margin-left: auto;
			margin-right: auto;
			margin-top: 20px;

		}

		p {
			margin: 0px;
			padding: 0px;
		}


	</style>

</head> 

<body>
	<div class="container">

		<table style="width:100%; padding:20px;">
			<tr>
				<td style="font-weight:bold; font-size:30px; color:#26A0C9;"><center>Notre Dame University Bangladesh (NDUB)</center></td>
			</tr>
			<tr>
				<td style="font-weight:bold; font-size:20px; padding-top:10px; color:#26A0C9;"><center>Applicant Information</center></td>
			</tr>
		</table><br>


		<table style="width:96%; border: 1px solid black;  margin-left:auto; margin-right:auto; ">
			<tr>
				<td style="padding-left:10px;">Serial No: <?php echo $applicant_profile->applicant_serial_no; ?> </td>
				<td style="width:20%; ">Gender: Male <input type="checkbox" <?php echo $applicant_profile->gender=='male' ? 'checked' :''; ?> /></td>
				<td>Female <input type="checkbox" <?php echo $applicant_profile->gender=='female' ? 'checked' :''; ?>/></td>
			</tr>

		
		</table><br>

		<table style="width:96%; border: 1px solid black; border-bottom:hidden;  margin-left:auto; margin-right:auto; ">

			<tr>
				<td style="font-weight:bold; font-size:20px; padding-left:10px;">Program :</td>
				<td colspan="2" style="font-weight:bold; font-size:20px;"><?php echo $applicant_profile->program_title; ?></td>
			</tr>
			
		</table>

		<table style="width:96%; border:1px solid black; margin-left:auto; margin-right:auto; padding-bottom:10px;">
			<tr>
				<td style="width:70%;">
					<table style="width:100%; padding:10px;">
						<tr>
							<td style="width:30%; height:25px; "><h>Applicant's Name</h></td>
							<td style="width:1%;"><h>:</h></td>
							<td style="width:68%; padding-left:10px;"><h><?php echo $applicant_profile->first_name.' '.$applicant_profile->middle_name.' '.$applicant_profile->last_name; ?></h></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; "><h>Trimester</h></td>
							<td style="width:1%;"><h>:</h></td>
							<td style="width:68%; padding-left:10px;"><h><?php echo $applicant_profile->semester_title; ?></h></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Academic Year</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_profile->academic_year; ?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Mobile</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $applicant_profile->mobile; ?></td>
						</tr>
						<tr>
							<td style="width:30%; height:25px; ">Applicant Status</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;">
							<?php if($applicant_profile->payment_status==1)
									echo "Paid";
								  else if($applicant_profile->payment_status==2)
								  	echo "Waiting For Approval";
								  else echo "To be Paid";
							 ?>
							</td>
						</tr>
					</table>
				</td>

				<td style="width:30%;">
					<center>
						<img src="<?php echo asset($applicant_profile->app_image_url); ?>" style="margin-top:15px; height:190px; width:175px; border:3px solid #dadada; " />
					</center>
				</td>
			</tr>
			
		</table>
		
	</div>
</body>
</html>
