<!DOCTYPE html>
<html>
<head>
	<title>Payment Slip</title>



	<style type="text/css">
		.main{
			background-color:     #d5e8ad  ;
			padding-left: 10px;
			padding-right: 10px;

		}
		.header{
			height: 140px;
			width: 100%;
			margin-top: 20px;
			/*border:1px solid black;*/
		}

		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			font-style: normal;
		}
		.slip-text{
			background-color:  #979e89;
			width: 230px;
			border-radius: 11px;
			margin-left: auto;
			margin-right: auto;
			padding: 2px;
		}

		.bank-name-text{
			background-color:  #979e89;
			width: 500px;
			border-radius: 11px;
			margin-left: auto;
			margin-right: auto;
			padding: 2px;
		}


		/*th {
			text-align: center;
		}*/
		td{
			padding-left: 5px;
		}
	</style>
</head>
<body>

	<div class="main">
		<!-- header section -->
		<center>
			<table style="width:100%;margin-top:20px;border:hidden;">
				<tr>
					<td style="width:20%"><img src="<?php echo asset('images/plain-logo.png')?>" style="height:80px"></td>
					<td style="width:60%;border-left:hidden;"><center><h2 style="font-weight:bold; font-size:20px; color:#26A0C9; margin:0;padding:0;">Notre Dame University Bangladesh (NDUB)</h2></center>
						<center><p style="margin:0;padding:0;">GPO Box-7, 2 Arambagh, Motijheel,</p></center>
						<center><p style="margin:0;padding:0;">Dhaka-1000, Phone: 7192672</p></center>
						<div class="slip-text">
							<center>COLLECTION SLIP</center>
						</div></td>
						<td style="width:20%;border-left:hidden;"></td>
					</tr>
				</table>
				<p style="margin:0;padding:0;">This deposit slip will be allowed only for cash deposit or pay order / demand draft</p>
			</center>
			<br><br>


			<!-- student info section -->
			<table style="width:100%">
				<tr>
					<td colspan="2">Applicant Name: <?php echo $applicant_info->first_name.' '.$applicant_info->middle_name.' '.$applicant_info->last_name; ?></td>
					<td style="border-left:hidden;">Applicant ID: <?php echo $applicant_info->applicant_serial_no; ?></td>
				</tr>
				<tr>
					<td colspan="2">Department &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $applicant_info->program_title; ?></td>
					<td style="border-left:hidden;">Contact &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $applicant_info->mobile; ?></td>
				</tr>
				<tr>
					<td >Trimester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $applicant_info->semester_title; ?></td>
					<td style="border-left:hidden;">Year: <?php echo $applicant_info->academic_year; ?></td>
					<td style="border-left:hidden;">Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
				</tr>

			</table><br>
			<div class="bank-name-text">
				<center>Mercantile Bank Ltd. (Collection A/C) No. 01191100013161</center>
			</div>


			<!-- Fees section -->
			<table style="width:100%; margin-top:5px;">
				<tr>
					<th  style="width:40%">Name of Fees</th>
					<th style="">Taka</th>
					<th style="">Particulars of PO/DD</th>
				</tr>
				<tr>
					<td  style="width:40%">Trimester Fee</td>
					<td ></td>
					<td style="">PO/DD:</td>
				</tr>
				<tr>
					<td  style="width:40%">Course/Tution Fee</td>
					<td style=""></td>
					<td >Bank/Br:</td>
				</tr>
				<tr>
					<td  style="width:40%">Lab/Library/Student Activity Fee</td>
					<td style=""></td>
					<td >Date:</td>
				</tr>
				<tr>
					<td  style="width:40%">Exam Fee</td>
					<td style=""></td>
					<td ></td>
				</tr>
				<tr>
					<td  style="width:40%">Transcript Fee</td>
					<td style=""></td>
					<td ></td>
				</tr>
				<tr>
					<td  style="width:40%">Fine</td>
					<td style=""></td>
					<td ></td>
				</tr>
				<tr>
					<td  style="width:40%">Form Fee</td>
					<td style=""></td>
					<td style=""></td>
				</tr>
				<tr>
					<td  style="width:40%">Others</td>
					<td style=""></td>
					<td ></td>
				</tr>
				<tr>
					<td  style="width:40%">Total:</td>
					<td style=""></td>
					<td ></td>
				</tr>
				<tr>
					<td colspan="3">In Words:</td>

				</tr>

			</table>
			<br><br>

			<table style="width:100%;border:hidden;">
				<tr>
					<td><span style="border-top:1px solid black;">Deposited by:</span></td>
					<td style="text-align:right;border-left:hidden;"><span style="border-top:1px solid black;">Authorized Signature</span></td>
				</tr>
			</table>
			<br><br>
		</div>

	</body>
	</html>