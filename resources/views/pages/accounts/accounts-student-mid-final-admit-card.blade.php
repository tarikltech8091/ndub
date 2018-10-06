<!DOCTYPE HTML>
<html>
<head>
	<title> ADMIT CARD | NDUB </title>

	<style type="text/css" media="print">

		p {
			margin: 0px;
			padding: 0px;
		}
		@page {
		  size: auto;
		  margin: 0;
		}

	</style>

</head> 

<body onload="window.print();" onfocus="window.close()">
	<div style="width:100%;height: 870px; background-color: #F1F1F1;">

		<table style="width:100%; padding:20px; background-color: #F1F1F1;">
			<tr>
				<td style="width:20%"><center><img src="<?php echo asset('images/plain-logo.png')?>" style="height:80px"></center></td>
			</tr>
			<tr>
				<td style="font-weight:bold; color:#26A0C9;">
					<center>
					<h1>Notre Dame University Bangladesh (NDUB)</h1>
					<p>2 Arambag, Motijheel, GPO Box-7, Dhaka-1000, Bangladesh</p>
					</center>
				</td>
			</tr>

			<tr>
				<td style="font-weight:bold; font-size:20px; padding-top:10px; color:#26A0C9;" align="center"><center>Exam Clearence</center></td>
			</tr>
			<tr>
				<td style="font-weight:bold; font-size:20px; padding-top:10px; color:#26A0C9;"><center>
					<?php
						if($exam_type == 'mid_term_exam'){
							echo 'Mid Term Exam';
						}elseif($exam_type == 'final_exam'){
							echo 'Final Exam';
						}
					?>
				</center></td>
			</tr>
		</table><br>


		<table style="width:96%; border:1px solid black; margin-left:auto; margin-right:auto; padding-bottom:10px;">
			<tr>
				<td style="width:70%;">
					<table style="width:100%; padding:10px;">
						<tr>
							<td style="width:33%; height:25px;"><strong>Student Information</strong></td>
							<td style="width:1%;"></td>
							<td style="width:68%; padding-left:10px;"></td>
						</tr>
						<tr>
							<td style="width:30%; height:25px; ">Student Id</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->student_serial_no;?></td>
						</tr>
						<tr>
							<td style="width:30%; height:25px; ">Student Name</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->first_name.' '.$student_info->middle_name.' '.$student_info->last_name;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Student Program</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->program_title;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Trimester</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->semester_title;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Academic Year</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->academic_year;?></td>
						</tr>

						<tr>
							<td style="width:30%; height:25px; ">Contact</td>
							<td style="width:1%;">:</td>
							<td style="width:68%; padding-left:10px;"><?php echo $student_info->mobile;?></td>
						</tr>
					</table>
				</td>

			</tr>
		</table>
		<br>

		<table style="width:96%; border:1px solid black; margin-left:auto; margin-right:auto; padding-bottom:10px;">
			<tr>
				<td style="width:70%;">
					<table style="width:100%; padding:10px;">
							<tr>
								<td style="width:33%; height:25px;"><strong>Course Code</strong></td>
								<td style="width:43%;"><strong>Course Title</strong></td>
								<td style="width:23%; padding-left:10px;"> <strong>Credit</strong></td>
							</tr>

							@if(!empty($registered_class_course))

								@foreach($registered_class_course as $key => $list)
									<?php 
										$registered_class_course=\DB::table('student_class_registers')
				                        ->where('student_tran_code',$list->student_tran_code)
				                        ->where('class_course_code', $list->course_code)
				                        ->get();
									?>
								<tr>
									<td style="width:33%; height:25px;">{{$list->course_code}}</td>
									<td style="width:43%;">{{$list->course_title}}</td>
									<td style="width:23%; padding-left:10px;">{{number_format($list->credit_hours,1,'.','')}}</td>
								</tr>
								@endforeach

							@endif

							@if(!empty($registered_lab_course))

								@foreach($registered_lab_course as $key => $list)
								<tr>
									<td style="width:33%; height:25px; ">{{$list->course_code}}</td>
									<td style="width:43%;">{{$list->course_title}}</td>
									<td style="width:23%; padding-left:10px;">{{number_format($list->credit_hours,1,'.','')}}</td>
								</tr>
								@endforeach
								
							@endif


					</table>
				</td>

			</tr>
		</table>
		<br><br><br>

		<table style="width:96%; border-bottom:1px dashed black; margin-left:auto; margin-right:auto;">
			<tr>
				<td  style="font-weight:bold; font-size:15px; ">Date : <?php echo date('Y-M-d'); ?></td>
				<td  style=" font-size:15px; padding-bottom:30px; width:30%; text-align:center">
					<table>
						<tr>
							<td>
								<img src="{{asset('images/authorized-signature.jpg')}}" height="60px" width="400px">
							</td>
						</tr>
						<tr><td><hr><b>Authorized Signature</b></td></tr>
					</table>
				</td>
			</tr>
		</table>

	</div>
</body>
</html>
