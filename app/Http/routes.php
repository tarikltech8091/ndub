<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

	#Cache Clear
	Route::get('/cache/clear', function () {
		Artisan::call('cache:clear');
        echo "OK";
	});

	#Config Clear
	Route::get('/config/cache', function () {
		Artisan::call('config:cache');
		echo "OK";
	});
	
	/*
	######################
	## Application Module
	#######################
	*/

	#Online Application Form Page
	Route::get('/online-application',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationInformationPage'));

	Route::get('/online-application/entry/validation/{exam_type}-{exam_roll_number}-{program}',array('as'=>'Online Application','uses'=>'ApplicationController@ApplicantEntryValidation'));

	Route::get('/online-application/form',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationFormPage'));

	
	#Online Application Form Submit
	Route::post('/online-application/form/basic-info',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationBasicSubmit'));
	Route::post('/online-application/form/personal-info',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationPersonalSubmit'));
	Route::post('/online-application/form/contact-info',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationContactSubmit'));
	Route::post('/online-application/form/academic-info',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationAcademicSubmit'));
	Route::post('/online-application/form/image',array('as'=>'Online Application','uses'=>'ApplicationController@ApplicantImageUpload'));

	#Online Application Form Complete
	Route::get('/online-application/form/complete',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationCompletePage'));

	#Online Application Payment Slip
	Route::get('/online-application/payment-slip/{applicant_serial_no}',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicantPaymentSlipDownload'));

	#Online Applicant information pdf
	Route::get('/online-application/applicant-info',array('as'=>'Online Application','uses'=>'ApplicationController@ApplicantInfoDownload'));


	#Online Applicant Information Page
	Route::get('/online-application/applicant',array('as'=>'Applicant Information', 'uses'=>'ApplicationController@ApplicantInfoSerachPage'));
	Route::get('/online-application/applicant/info/{applicant_serial_no}',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicantInfoSerachSubmit'));

	#Online Applicant payment Update unisg Ajax
	Route::get('/online-application/payment/{applicant_serial_no}/{payment_amount}/{payment_slip_no}/{bank_name}',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicantPaymentUpdate'));

	#Online Application Admission Result Page
	Route::get('/online-application/applicant/admission-result',array('as'=>'Applicant Admission Result', 'uses'=>'ApplicationController@ApplicantAdmitSerachPage'));

	#Online Application Admission Result serach using Ajax
	Route::get('/online-application/applicant/admission-result/search/{applicant_serial_no}',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicantAdmissionResultSubmit'));

	#Online Application Admission Payement Slip PDF
	Route::get('/online-application/admission-payment/slip-{applicant_serial_no}',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicantAdmissionPaymentSlipDownload'));

	Route::get('/online-application/admit-card',array('as'=>'Online Application', 'uses'=>'ApplicationController@ApplicationAdmitCardShow'));

	#Experience add
	Route::get('/online-application/experience/{multi_count}',array('as'=>'Applicant Experience', 'uses'=>'ApplicationController@ApplicantExperience'));
	
	#ApplicantSubjectAdd
	Route::get('/online-application/form/{add_subject_count}/{type}',array('as'=>'Applicant Subject Add', 'uses'=>'ApplicationController@ApplicantSubjectAdd'));

	#RemoveSession
	Route::get('/online-application/form/remove-session',array('as'=>'Remove Session', 'uses'=>'ApplicationController@RemoveSession'));


	#ForgetPasswordMail
	Route::get('/forget-password-mail',array('as'=>'Forget Password Mail', 'uses'=>'ApplicationController@ForgetPasswordMail'));


	/*
	######################
	## 404 Page
	#######################
	*/

	Route::get('/error/404',function(){

		return \View::make('errors.404');
	});


	/*
	######################
	## System Login Module
	#######################
	*/

	#Login
	Route::get('/',array('as'=>'Home' , 'uses' =>'SystemAuthController@SystemHomePage'));
	Route::get('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@SystemLoginPage'));
	Route::post('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@SystemAuthenticationCheck'));

	#Logout
	Route::get('/logout/{name_slug}',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@SystemLogoutPage'));

	#ChangePassword
	Route::get('{user_type}/change-password/{user_id}',array('as'=>'Change Password' , 'uses' =>'SystemAuthController@ChangePassword'));

	#UpdatePassword
	Route::post('/update-password/{user_type}/{user_id}',array('as'=>'Update Password' , 'uses' =>'SystemAuthController@UpdatePassword'));


	#------Forget Password Module------#
	Route::get('/forget/password',array('as'=>'Forgot Password' , 'uses' =>'SystemAuthController@SystemForgotPasswordPage'));

	Route::post('/forget/password',array('as'=>'Forgot Password Confirm' , 'uses' =>'SystemAuthController@SystemForgotPasswordConfirm'));

	Route::get('/forget/password/{user_id}/verify',array('as'=>'Forgot Password Varify' , 'uses' =>'SystemAuthController@SystemForgotPasswordVerification'));

	Route::post('/new/password',array('as'=>'New Password Submit' , 'uses' =>'SystemAuthController@SystemNewPasswordSubmit'));



	Route::get('/auth',function(){
		if(\Auth::check()){
			echo "loggedin";
		}else echo "Not loggedin";
	});


	/*
	#####################
	## Accounts Module
	######################
	*/

	Route::group(['middleware' => ['accounts_auth']], function () {

		#Accounts Home Page
		Route::get('/accounts/{name_slug}/home',array('as'=>"Accounts Dashboard",'uses'=>'AccountsController@AccountsHomePage'));

		#Accounts Applicant payment Verify
		Route::get('/accounts/applicant/payment',array('as'=>"Applicant Payment",'uses'=>'AccountsController@ApplicantsPaymentApprovedPage'));

		#Accounts Applicant message
		Route::get('/accounts/applicant/message/{message_issue}/{applicant_serial_no}',array('as'=>"Accounts Applicant Message",'uses'=>'AccountsController@AccountsApplicantMessage'));

		#Accounts Applicant message Send
		Route::post('/accounts/applicant/message-send/{applicant_serial_no}',array('as'=>"Accounts Applicant Message Send",'uses'=>'AccountsController@AccountsApplicantMessageSend'));
		
		#Accounts Applicant Payment Verified
		Route::get('/accounts/applicant/payemnt-verify/{applicant_serial_list}',array('as'=>"Accounts",'uses'=>'AccountsController@ApplicantsPaymentApprovedByList'));

		#Accounts Applicants Payment Undone
		Route::get('/accounts/applicant/payemnt-undone/{payment_by}/{applicant_serial_no}',array('as'=>"Applicants Payment Undone",'uses'=>'AccountsController@ApplicantsPaymentUndone'));

		#Accounts Applicant Payment Cash
		Route::get('/accounts/applicant/cash-payment',array('as'=>"Applicant Cash Payment",'uses'=>'AccountsController@ApplicantCashPayemnt'));

		#Accounts Applicant Payment Cash
		Route::get('/accounts/applicant/cash-payment/received/{applicant_serial_no}',array('as'=>"Accounts",'uses'=>'AccountsController@ApplicantCashPayemntReceived'));

		#Accounts Admission Payment List
		Route::get('/accounts/admission/payement/list',array('as'=>'Admission Payment','uses'=>'AccountsController@AdmissionPaymentList'));

		#Accounts Admission Payment List apporved by ajax
		Route::get('/accounts/admission/payement/approved/{applicant_serial_no}/{payment_type}/{slip_no}',array('as'=>'Admission Payment','uses'=>'AccountsController@AdmissionPaymentApproved'));

		#Accounts Applicants Admission Payment Undone
		Route::get('/accounts/admission/payemnt-undone/{applicant_serial_no}',array('as'=>"Applicants Admission Payment Undone",'uses'=>'AccountsController@ApplicantsAdmissionPaymentUndone'));

		#Accounts Student Payment Transaction Page
		Route::get('/accounts/student-payment-transaction',array('as'=>'Student Payment Transaction','uses'=>'AccountsController@StudentPaymentTransaction'));

		#AccountsStudent Payment Submit
		Route::post('/accounts/student-payment-submit',array('as'=>'Accounts Student Payment Submit','uses'=>'AccountsController@AccountsStudentPaymentSubmit'));

		#Accounts Student Payment  Excel
		Route::get('/accounts/student/payment/excel/sno-{student_serial_no}/semester-{semester}/year-{academic_year}',array('as'=>'Accounts Student Payment Excel','uses'=>'AccountsController@StudentPaymentExcel'));

		#Accounts Fee Name List
		Route::get('/register/student/fee-{fee_name}',array('as'=>'Accounts Fee Name List','uses'=>'AccountsController@AccountsFeeNameList'));

		#Accounts Student Payment Delete
		Route::get('/accounts/student-payment-delete/{payment_tran_code}',array('as'=>'Accounts Student Payment Delete','uses'=>'AccountsController@AccountsStudentPaymentDelete'));

		#Accounts Student Payment Edit
		Route::get('/accounts/student-payment-edit/{payment_tran_code}',array('as'=>'Accounts Student Payment Edit','uses'=>'AccountsController@AccountsStudentPaymentEdit'));

		#Accounts Student Payment Update
		Route::post('/accounts/student-payment-update/{payment_tran_code}',array('as'=>'Accounts Student Payment Update','uses'=>'AccountsController@AccountsStudentPaymentUpdate'));

		#Accounts Student Admit
		Route::get('/accounts/student/id-{student_serial_no}/admit/{exam_type}',array('as'=>'Accounts Student Admit','uses'=>'AccountsController@StudentMidAndFinalAdmit'));

		#Accounts Student Payment Summary
		Route::get('/accounts/student/payment/summery',array('as'=>'Accounts Student Payment Summary','uses'=>'AccountsController@StudentPaymentSummery'));

		#Accounts Student Payment Summary Excel
		Route::get('/accounts/student/payment/summery/excel/program-{program}/batch-{batch}/from-{search_from}/to-{search_to}',array('as'=>'Accounts Student Payment Summary Excel','uses'=>'AccountsController@StudentPaymentSummeryExcel'));

		#Accounts Student Batch By Program
		Route::get('/accounts/student/batch/program-{program}',array('as'=>"Accounts Student Batch By Program",'uses'=>'AccountsController@AccountsStudentBatchByProgram'));





		#Accounts Fee Category
		Route::get('/accounts/fee-category',array('as'=>'Accounts Fee Types','uses'=>'AccountsController@AccountsFeeCategory'));

		#Accounts Fee Category Submit
		Route::post('/accounts/fee-category',array('as'=>'Accounts Fee Types','uses'=>'AccountsController@AccountsFeeCategorySubmit'));

		#Edit Accounts Fee Category
		Route::get('/accounts/fee-category/edit/{fee_category_name_slug}',array('as'=>"Edit Accounts Fee Types",'uses'=>'AccountsController@EditAccountFeeCategoryName'));

		#Update Accounts Fee Category
		Route::post('/accounts/fee-category/edit/{fee_category_name_slug}',array('as'=>"Update Accounts Fee Types",'uses'=>'AccountsController@UpdateAccountFeeCategoryName'));

		#Accounts Fee Category Delete
		Route::get('/accounts/fee-category/delete/{fee_category_name_slug}',array('as'=>"Accounts Fee Types Delete",'uses'=>'AccountsController@AccountsFeeCategoryDelete'));



		#Accounts Fee Payment
		Route::get('/accounts/fee-payment',array('as'=>'Accounts Fee Payment','uses'=>'AccountsController@AccountsFeePayment'));

		#Accounts Fee Payment Submit
		Route::post('/accounts/fee-payment',array('as'=>'Accounts Fee Types','uses'=>'AccountsController@AccountsFeePaymentSubmit'));

		#Edit Academic Setup Page
		Route::get('/accounts/account-payment/edit/{accounts_fees_slug}',array('as'=>"Edit Accounts Fee Payment",'uses'=>'AccountsController@EditAccountFeePayment'));

		#Update Accounts Fee Payment
		Route::post('/accounts/account-payment/edit/{accounts_fees_slug}',array('as'=>"Update Accounts Fee Payment",'uses'=>'AccountsController@UpdateAccountFeePayment'));

		#Accounts Fee Payment Delete
		Route::get('/accounts/fee-payment/delete/{accounts_fees_tran_code}',array('as'=>"Accounts Fee Payment Delete",'uses'=>'AccountsController@AccountsFeePaymentDelete'));



		#Accounts Waiver
		Route::get('/accounts/waiver',array('as'=>'Accounts Waiver','uses'=>'AccountsController@AccountsWaiverPage'));

		#Accounts Waiver Submit
		Route::post('/accounts/waiver',array('as'=>'Accounts Waiver','uses'=>'AccountsController@AccountsWaiverSubmit'));

		#Edit Waiver Page
		Route::get('/accounts/waiver/edit/{waiver_name_slug}',array('as'=>"Edit Account Waiver Page",'uses'=>'AccountsController@EditAccountWaiverPage'));

		#Update Accounts waiver page
		Route::post('/accounts/waiver/edit/{waiver_name_slug}',array('as'=>"Update Account Waiver",'uses'=>'AccountsController@UpdateAccountWaiverPage'));

		#Accounts Waiver Delete
		Route::get('/accounts/waiver/delete/{waiver_name_slug}',array('as'=>"Accounts Waiver Delete",'uses'=>'AccountsController@AccountsWaiverDelete'));

		#Accounts Applicant Total Amount
		Route::get('/accounts/applicant/total-amount',array('as'=>'Accounts Applicant Total Amount','uses'=>'AccountsController@AccountApplicantTotalAmountPage'));

		#Accounts Admission List Download
		Route::get('/accounts/admission/list/download',array('as'=>"Admission List Download",'uses'=>'AccountsController@AccountsAdmissionListExcelDownload'));


		#Accounts Summary
		Route::get('/accounts/account-summery',array('as'=>'Accounts Summary','uses'=>'AccountsController@AccountSummeryPage'));

		#Accounts Summary Excel Download
		Route::get('/accounts/summery/download/program-{program}/from-{search_from}/to-{search_to}',array('as'=>"Accounts Summary Excel Download",'uses'=>'AccountsController@AccountSummeryExcelDownload'));

		#-------------------end---------------------#


	});


	/*
	#####################
	## Registrar Module
	######################
	*/

	Route::group(['middleware' => ['register_auth']], function () {


		#Registrar Office Home Page
		Route::get('/register/{name_slug}/home',array('as'=>"Registrar Dashboard",'uses'=>'RegisterController@RegisterOfficeDashboardPage'));

		#-----Registrar Applicant And Admission Module-----#
		#Registrar Office Applicant List Page
		Route::get('/register/applicant/list',array('as'=>"Applicant List",'uses'=>'RegisterController@RegisterApplicantPage'));

		#Registrar Office List Applicant Payemnt List Page
		Route::get('/register/applicant/list/download',array('as'=>"Applicant List Download",'uses'=>'RegisterController@ApplicantListExcelDownload'));


		#Registrar Office List Amission Payemnt List Page
		Route::get('/register/admission/list',array('as'=>"Admission List",'uses'=>'RegisterController@RegisterAdmissionPage'));

		#Registrar Office List Amission Payemnt List Page
		Route::get('/register/admission/list/download',array('as'=>"Admission List Download",'uses'=>'RegisterController@AdmissionListExcelDownload'));

		#Registrar Office Applicant details
		Route::get('/register/applicant/list/detail-{applicant_serial_no}',array('as'=>"Registrar",'uses'=>'RegisterController@ApplicantDetailsAjaxPage'));

		#Registrar Office Cabdidate approved by Ajax
		Route::get('/register/candidate/approved/{applicant_serial_list}/{status}',array('as'=>"Registrar",'uses'=>'RegisterController@RegisterCandidateApprovedByList'));

		#Registrar Office Cabdidate approved by Ajax
		Route::get('/register/candidate/approved/{applicant_serial_list}/{status}',array('as'=>"Registrar",'uses'=>'RegisterController@RegisterCandidateApprovedByList'));



		#-----Registrar Amission Confirm Module-----#
		#Registrar Office  Amission Confirm Page
		Route::get('/register/admission/confirm',array('as'=>"Admission Confirm",'uses'=>'RegisterController@RegisterAdmissionConfirmPage'));

		#Registrar Office  Amission Confirm Submit
		Route::post('/register/admission/confirm',array('as'=>"Admission Confirm",'uses'=>'RegisterController@StudentAdmissionSubmit'));

		#Registrar Office  Studnet Create
		Route::post('/register/admission/confirm/{applicant_serial_no}',array('as'=>"Admission Confirm",'uses'=>'RegisterController@StudentAdmissionSubmit'));

		#Registrar Office  Applicant  Reject
		Route::post('/register/office/applicant/reject',array('as'=>"Registrar Office  Applicant  Reject",'uses'=>'RegisterController@ApplicantRejectReasonSubmit'));



		#-----Faculty Registration Module-----#
		#Faculty Registration
		Route::get('/register/faculty-account-registration',array('as'=>"Faculty Registration",'uses'=>'RegisterController@FacultyRegistration'));

		#Faculty Registration Image
		Route::post('/register/register-faculty-account/image-upload',array('as'=>"Faculty Registration Image",'uses'=>'SystemAuthController@SystemImageUpload'));

		#Faculty Registration Submit
		Route::post('/register/faculty-account-registration',array('as'=>"Faculty Registration Submit",'uses'=>'RegisterController@FacultyRegistrationSubmit'));




		#-----Employee Registration Module-----#
		#Registrar Employee Registration
		Route::get('/register/employee-registration',array('as'=>"Employee Registration",'uses'=>'RegisterController@EmployeeRegistration'));

		#Registrar Employee Registration Submit
		Route::post('/register/employee-registration/submit',array('as'=>"Employee Registration Submit",'uses'=>'RegisterController@EmployeeRegistrationSubmit'));

		#Employee Image Registration 
		Route::post('/register/employee-registration/image-upload',array('as'=>"Employee Registration Image",'uses'=>'RegisterController@EmployeeImageUpload'));



		#-----Student List Module-----#
		#Student List
		Route::get('/register/student-list',array('as'=>"Registrar Student List",'uses'=>'RegisterController@StudentList'));

		#Registrar Office Student Blocked by Ajax
		Route::get('/register/block/student/{student_serial_list}/{action}',array('as'=>"Registrar Student Block Confirm",'uses'=>'RegisterController@RegisterBlockStudentConfirm'));

		#Student Block With Reason
		Route::post('/student/block/with/reason',array('as'=>"Student Block With Reason",'uses'=>'RegisterController@StudentBlockWithReason'));

		#Registrar Student List Download
		Route::get('/register/student/list/download',array('as'=>"Student List Download",'uses'=>'RegisterController@StudentListExcelDownload'));

		#Block Student List
		Route::get('/register/block/student-list',array('as'=>"Registrar Block Student List",'uses'=>'RegisterController@BlockStudentList'));


		#Student Certificate List
		Route::get('/register/student/certificate/list',array('as'=>"Registrar Student Certificate List",'uses'=>'RegisterController@StudentCertificateList'));

		#Registrar Student Certificate Confirm
		Route::get('/register/certificate/student/{student_serial_list}/{action}',array('as'=>"Registrar Student Certificate Confirm",'uses'=>'RegisterController@RegisterStudentCertificateConfirm'));




		#-----Academic Calender Module-----#
		#Registrar Academic Calender Registration 
		Route::get('/register/academic-calender-registration',array('as'=>"Academic Calender Registration",'uses'=>'RegisterController@RegisterAcademicCalender'));

		#Registrar Academic Calender Registration  Submit
		Route::post('/register/academic-calender-registration',array('as'=>"Academic Calender Registration Submit",'uses'=>'RegisterController@RegisterAcademicCalenderSubmit'));

		#Registrar Academic Calender Registration 
		Route::get('/register/academic-calender/delete/{academic_calender_tran_code}',array('as'=>"Academic Calender Delete",'uses'=>'RegisterController@RegisterAcademicCalenderDelete'));

		#Edit Academic Calender 
		Route::get('/register/academic-calender/edit/{academic_calender_tran_code}',array('as'=>"Academic Calender Edit",'uses'=>'RegisterController@EditAcademicCalender'));

		#Update Academic Calender 
		Route::post('/register/academic-calender/update/{academic_calender_tran_code}',array('as'=>"Academic Calender Update",'uses'=>'RegisterController@UpdateAcademicCalender'));



		#-----Registrar Class Teacher Assign Module-----#
		#Registrar Class Teacher Assign
		Route::get('/register/class-teacher-assign',array('as'=>"Class Teacher Assign",'uses'=>'RegisterController@ProgramCoordintorAssign'));

		#Registrar Class Teacher Assign Submit
		Route::post('/register/class-teacher-assign',array('as'=>"Class Teacher Assign Submit",'uses'=>'RegisterController@ProgramCoordintorSubmit'));

		#ProgramList By Deaprtment
		Route::get('/register/program-list-ajax/{department}',array('as'=>"Class Teacher Assign",'uses'=>'RegisterController@ProgramListAjax'));
		#ProgramList By Deaprtment
		Route::get('/register/faculty-list-ajax/{department}',array('as'=>"Class Teacher Assign",'uses'=>'RegisterController@FacultyListAjax'));

		#Registrar Class Teacher Edit
		Route::get('/register/class-teacher-edit/{program_coordinator_tran_code}',array('as'=>"Class Teacher Edit",'uses'=>'RegisterController@ProgramCoordinatorEdit'));

		#Registrar Class Teacher Update
		Route::post('/register/class-teacher-update/{program_coordinator_tran_code}',array('as'=>"Class Teacher Update",'uses'=>'RegisterController@ProgramCoordinatorUpdate'));

		#Registrar Class Teacher Delete
		Route::get('/register/class-teacher-del/{program_coordinator_tran_code}',array('as'=>"Class Teacher Delete",'uses'=>'RegisterController@ProgramCoordinatorDelete'));



		#---Faculty Assigned Course---#
		#Faculty Assigned Course
		Route::get('/register/faculty-assigned-course',array('as'=>"Faculty Assigned Course",'uses'=>'RegisterController@FacultyAssignedCourse'));

		#Faculty Assigned Course Submit
		Route::post('/register/faculty-assigned-course/{action}',array('as'=>"Faculty Assigned Course Submit",'uses'=>'RegisterController@FacultyAssignedCourseSubmit'));



		#---Time Slot Module--#
		#Time Slot
		Route::get('/register/univ-time-slot',array('as'=>"Time Slot",'uses'=>'RegisterController@TimeSlot'));

		#Time Slot Submit
		Route::post('/register/univ-time-slot',array('as'=>"Time Slot Submit",'uses'=>'RegisterController@TimeSlotSubmit'));

		#Time Slot Delete
		Route::get('/register/time-slot-delete/{time_slot_tran_code}',array('as'=>"Time Slot Delete",'uses'=>'RegisterController@TimeSlotDelete'));



		#---Class Schedule Module--#
		#Class Schedule
		Route::get('/register/class-schedule',array('as'=>"Class Schedule",'uses'=>'RegisterController@ClassSchedule'));

		#Ajax Room List
		Route::get('/register/ajax-room-list/{building_code}',array('as'=>"Ajax Room List",'uses'=>'RegisterController@AjaxRoomList'));

		#Ajax Course List
		Route::get('/register/ajax-course-list/{program_code}',array('as'=>"Ajax Course List",'uses'=>'RegisterController@AjaxCourseList'));

		#Ajax Class Day
		Route::get('/register/ajax-class-day/{room_code}',array('as'=>"Ajax Class Day",'uses'=>'RegisterController@AjaxClassDay'));

		#Ajax Time Slot
		Route::get('/register/ajax-time-slot/{room_code}/{class_day_week}',array('as'=>"Ajax Time Slot",'uses'=>'RegisterController@AjaxTimeSlot'));

		#Ajax Faculty List
		Route::get('/register/ajax-faculty-list/{class_day_week}/{time_slot}',array('as'=>"Ajax Faculty List",'uses'=>'RegisterController@AjaxFacultyList'));

		#Class Schedule Submit
		Route::post('/register/class-schedule-submit',array('as'=>"Class Schedule Submit",'uses'=>'RegisterController@ClassScheduleSubmit'));

		#Ajax Class Schedule View
		Route::get('/register/ajax-class-schedule-view/{room_code}',array('as'=>"Ajax Class Schedule View",'uses'=>'RegisterController@AjaxClassScheduleView'));

		#Ajax Schedule By Program View
		Route::get('/register/ajax-schedule-by-program-view/{program_id}',array('as'=>"Ajax Schedule By Program View",'uses'=>'RegisterController@AjaxScheduleByProgramView'));

		#Schedule Delete
		Route::get('/register/schedule-delete/{schedule_tran_code}',array('as'=>"Schedule Delete",'uses'=>'RegisterController@ScheduleDelete'));

		#Registrar Schedule Download
		Route::get('/register/class/schedule/download',array('as'=>"Schedule Download",'uses'=>'RegisterController@SchedulePdfDownload'));

		#Ajax Exam Course List
		Route::get('/register/ajax-exam-course-list/{program_code}',array('as'=>"Ajax Exam Course List",'uses'=>'RegisterController@AjaxExamCourseList'));


		#------------Registrar Exam Schedule--------#
		#Registrar Exam Schedule
		Route::get('/register/schedule/exam-schedule',array('as'=>"Exam Schedule",'uses'=>'RegisterController@ExamSchedule'));

		// #Registrar Exam Schedule Ajax
		// Route::get('/register/schedule/exam-schedule-ajax/{exam_type}/{exam_date}',array('as'=>"Exam Schedule",'uses'=>'RegisterController@AjaxExamSchedule'));

		#Registrar Exam Schedule Modal
		Route::get('/register/schedule/exam-schedule-modal/{room_code}/{exam_type}/{exam_date}/{time_slot}',array('as'=>"Exam Schedule Modal",'uses'=>'RegisterController@ExamScheduleModal'));

		#Registrar Exam Schedule Submit
		Route::post('/register/schedule/exam-schedule-submit',array('as'=>"Exam Schedule Submit",'uses'=>'RegisterController@ExamScheduleSubmit'));

		#Registrar Exam Schedule View
		Route::get('/register/exam-schedule/exam-schedule-view/{exam_type}/{program}/{trimester}/{year}',array('as'=>"Exam Schedule View",'uses'=>'RegisterController@ExamScheduleView'));

		#Registrar Exam Schedule Download Page
		Route::get('/register/exam-schedule/exam-schedule-download-pdf',array('as'=>"Exam Schedule Download",'uses'=>'RegisterController@ExamScheduleDownload'));

		#Registrar Exam Schedule Delete
		Route::get('/register/exam/schedule-delete/{exam_schedule_tran_code}',array('as'=>"Exam Schedule Delete",'uses'=>'RegisterController@ExamScheduleDelete'));

		#---Registrar Notice Board Module---#
		#Registrar Notice Board Page
		Route::get('/register/notice-board',array('as'=>"Registrar Notice Board",'uses'=>'RegisterController@RegisterNoticeBoardPage'));

		#Registrar Notice Board Submit
		Route::post('/register/notice-board',array('as'=>"Registrar Faculty Notice Board Submit",'uses'=>'RegisterController@RegisterFacultyNoticeBoardSubmit'));

		#Registrar Notice Board Submit
		Route::post('/register/notice-board',array('as'=>"Registrar Student Notice Board Submit",'uses'=>'RegisterController@RegisterStudentNoticeBoardSubmit'));

		#Registrar Notice Board Delete
		Route::get('/register/notice-board/delete/{notice_tran_code}',array('as'=>"Registrar Notice Board Delete",'uses'=>'RegisterController@RegisterNoticeDelete'));

		#Faculty Notice Edit
		Route::get('/register/edit/{notice_board}/{notice_tran_code}',array('as'=>'Registrar Notice Edit','uses'=>'RegisterController@EditRegisterNoticePage'));

		#Update Faculty Notice
		Route::post('/register/notice-board/edit/{notice_tran_code}',array('as'=>"Update Registrar Notice",'uses'=>'RegisterController@UpdateRegisterNotice'));



		#---Registrar Student Trimester Assign Module---#
		#Registrar Trimester Student Assign
		Route::get('/register/trimester-student-assign',array('as'=>"Trimester Student Assign",'uses'=>'RegisterController@RegisterTrimesterStudentAssign'));

		#Registrar Trimester Student Assign Submit
		Route::post('/register/trimester-student-assign-submit',array('as'=>"Registrar Trimester Student Assign Submit",'uses'=>'RegisterController@RegisterTrimesterStudentAssignSubmit'));



		#---Registrar Student Grade Equivalent Module---#
		#Registrar Student Grade Equivalent
		Route::get('/register/student-grade-equivalent',array('as'=>"Student Grade Equivalent",'uses'=>'RegisterController@StudentGradeEquivalent'));

		#Registrar Student  Grade Equivalent Submit
		Route::post('/register/student-grade-equivalent/submit',array('as'=>"Student Grade Equivalent Submit",'uses'=>'RegisterController@StudentGradeEquivalentSubmit'));

		#Registrar Student Edit Grade Equivalent
		Route::get('/register/student-grade-equivalent/edit/{grade_equivalent_tran_code}',array('as'=>"Student Grade Equivalent Edit",'uses'=>'RegisterController@StudentEditGradeEquivalent'));

		#Registrar Student Update Grade Equivalent
		Route::post('/register/student-grade-equivalent/update/{grade_equivalent_tran_code}',array('as'=>"Student Grade Equivalent Update",'uses'=>'RegisterController@StudentUpdateGradeEquivalent'));

		#Registrar Student Grade Equivalent Delete
		Route::get('/register/student-grade-equivalent/delete/{grade_equivalent_tran_code}',array('as'=>"Student Grade Equivalent Delete",'uses'=>'RegisterController@StudentGradeEquivalentDelete'));


		#---Registrar Student Class Attendance Module---#
		#Registrar Student Attendance List Page
		Route::get('/register/student/attendance/list',array('as'=>"Registrar Attendance List",'uses'=>'RegisterController@RegisterStudentAttendanceListPage'));

		#Ajax Course List
		Route::get('/register/ajax-course/list/{program}',array('as'=>"Ajax Course List",'uses'=>'RegisterController@AjaxCourseList'));

		#Registrar Student Attendance Submit Page
		Route::post('/register/student/attendance/list/submit',array('as'=>"Registrar Attendance Submit",'uses'=>'RegisterController@RegisterStudentAttendanceSubmit'));


		#Registrar Student Attend Percent
		Route::get('/register/attendance/percent',array('as'=>"Registrar Student Attendance List",'uses'=>'RegisterController@RegisterStudentAttendancePercent'));


		#---Registrar Student Course Withdraw Module---#
		#Registrar Student Withdraw Course
		Route::get('/register/student/withdraw/course',array('as'=>"Registrar Student Withdraw Course",'uses'=>'RegisterController@RegisterStudentCourseWithdraw'));

		#Registrar Student Withdraw Course Submit
		Route::post('/register/student/withdraw/course',array('as'=>"Registrar Student Course no Submit",'uses'=>'RegisterController@RegisterStudentCourseWithdrawSubmit'));


		#---Registrar Student Credit Transfer Module---#
		
  		#Registrar Student credit Transfer
		Route::get('/register/student/credit/transfer',array('as'=>"Registrar Student Credit Transfer",'uses'=>'RegisterController@RegisterCreditTransferStudent'));

  		#Registrar credit Transfer Student Info Submit
		Route::post('/register/student/credit/transfer/submit',array('as'=>"Registrar Student Credit Transfer Submit",'uses'=>'RegisterController@RegisterStudentCreditTransferSubmit'));

  		#Student credit Transfer Image Upload
		Route::post('/register/student/credit/transfer/image-upload',array('as'=>"Transfer Student  Image",'uses'=>'RegisterController@TransferStudentImageUpload'));

  		#Registrar Transfer Student Accepted Course Submit
		Route::post('/register/student/accepted/course/submit',array('as'=>"Registrar Student Accepted Course Submit",'uses'=>'RegisterController@RegisterTransferStudentAcceptedCourse'));

 		#Registrar Transfer Student Addmission Payment Submit
		Route::post('/register/student/addmission/payment/submit',array('as'=>"Registrar Transfer Student Addmission Payment",'uses'=>'RegisterController@RegisterTransferStudentAddmissionPayment'));


  		######################## For Existing Student ############################
		
  		#Registrar Existing Student
		Route::get('/register/existing/student',array('as'=>"Registrar Existing Student",'uses'=>'RegisterController@RegisterExistingStudent'));


  		#Register Existing Student Batch By Program
		Route::get('/register/existing/student/batch/program-{program}',array('as'=>"Register Existing Student Batch By Program",'uses'=>'RegisterController@RegisterStudentBatchByProgram'));


  		#Register Existing Student By Batch
		Route::get('/register/existing/batch-student/program-{program}/batch-{batch}',array('as'=>"Register Existing Student By Batch",'uses'=>'RegisterController@RegisterExistingStudentByBatch'));

  		#Registrar Existing Student Submit
		Route::post('/register/existing/student/submit',array('as'=>"Registrar Existing Student Submit",'uses'=>'RegisterController@RegisterExistingStudentInfoSubmit'));


  		#Registrar Existing Student Accepted Theory Course
		Route::get('/register/existing/student/marks/submit',array('as'=>"Registrar Existing Student Accepted Theory Course",'uses'=>'RegisterController@RegisterExistingStudentAcceptedTheoryCourse'));

		Route::get('/register/existing/student/marks/submit/{student_serial_no}/{course_code}/{course_type}/{course_year}/{course_semester}/{ct_1}/{ct_2}/{ct_3}/{ct_4}/{mid_term}/{class_attendance}/{class_participation}/{class_presentaion}/{class_final_exam}',array('as'=>"Registrar Existing Student Accepted Theory Course",'uses'=>'RegisterController@RegisterExistingStudentAcceptedTheoryCourse'));


  		#Registrar Existing Student Accepted Lab Course
		Route::get('/register/existing/student/lab/marks/submit',array('as'=>"Registrar Existing Student Accepted Theory Course",'uses'=>'RegisterController@RegisterExistingStudentAcceptedLabCourse'));

		Route::get('/register/existing/student/lab/marks/submit/{student_serial_no}/{course_code}/{course_type}/{course_year}/{course_semester}/{lab_attendance}/{lab_performance}/{lab_reprot}/{lab_verbal}/{lab_final}',array('as'=>"Registrar Existing Student Accepted Theory Course",'uses'=>'RegisterController@RegisterExistingStudentAcceptedLabCourse'));

  		#Registrar Existing Student Delete Course
		Route::get('/register/existing/student/delete/course/{student_serial_no}/{course_code}/{course_type}/{course_year}/{course_semester}',array('as'=>"Registrar Existing Student Delete Course",'uses'=>'RegisterController@RegisterExistingStudentDeleteCourse'));


  		######################## For Existing Student ############################





		#--------------Exam Invigilator Module------------#
		#Registrar Exam Invigilators Page
		Route::get('/register/exam/invigilators',array('as'=>"Registrar Exam Invigilators",'uses'=>'RegisterController@ExamInvigilatorsPage'));

		#TimeSlotList By Exam Type
		Route::get('/register/time-slot-list-ajax/{invigilators_exam_type}',array('as'=>"Exam Time Slot List",'uses'=>'RegisterController@TimeSlotListAjax'));

		#Registrar Exam Invigilators Submit
		Route::post('/register/exam/invigilators/submit',array('as'=>"Registrar Exam Invigilators Submit",'uses'=>'RegisterController@ExamInvigilatorsSubmit'));


		#Registrar Exam Invigilators Delete
		Route::get('/register/exam/invigilators/delete/{invigilators_exam_tran_code}',array('as'=>"Registrar Exam Invigilators Del",'uses'=>'RegisterController@InvigilatorsDelete'));

		#Registrar Exam Invigilators Edit
		Route::get('/register/exam/invigilators/edit/{type_slug}',array('as'=>"Registrar Exam Invigilators Edit",'uses'=>'RegisterController@ExamInvigilatorEditRequest'));

		#Registrar Exam Invigilators Update
		Route::post('/register/exam/invigilators/update/{invigilators_exam_tran_code}',array('as'=>"Registrar Exam Invigilators Update",'uses'=>'RegisterController@ExamInvigilatorsUpdate'));

		#Registrar Exam Invigilators Download
		Route::get('/register/exam/invigilators-download',array('as'=>"Registrar Exam Invigilators Download",'uses'=>'RegisterController@ExamInvigilatorsDownload'));



		#Registrar Faculty List
		Route::get('/register/faculty-list',array('as'=>"Faculty List",'uses'=>'RegisterController@FacultyList'));

		#Registrar Faculty List Download
		Route::get('/register/faculty/list/download',array('as'=>"Faculty List Download",'uses'=>'RegisterController@FacultyListExcelDownload'));



		#Registrar Employee List
		Route::get('/register/employee-list',array('as'=>"Employee List",'uses'=>'RegisterController@EmployeeList'));

		#Registrar Employee List Download
		Route::get('/register/employee/list/download',array('as'=>"Employee List Download",'uses'=>'RegisterController@EmployeeListExcelDownload'));



		#Registrar All Summary
		Route::get('/register/all-summery',array('as'=>"Registrar All Summary",'uses'=>'RegisterController@RegisterAllSummery'));

		#Registrar Account Summary
		Route::get('/register/account-summery',array('as'=>"Registrar Account Summary",'uses'=>'RegisterController@RegisterAccountSummeryPage'));

		#Registrar Student Grade Sheet
		Route::get('/register/student/grade-sheet',array('as'=>'Registrar Student Grade Sheet','uses'=>'RegisterController@RegisterStudentGradeSheet'));

		#Registrar Student Booking Supplementary Course
		Route::get('/register/student/booking/supplimentry/course',array('as'=>'Registrar Booking Supplementary Course','uses'=>'RegisterController@RegisterStudentBookingSupplimentryCourse'));

		#Registrar Student Booking Supplementary Course Confirm
		Route::post('/register/student/booking/supplimentry/course/confirm',array('as'=>'Registrar Booking Supplementary Course Confirm','uses'=>'RegisterController@RegisterSupplimentryCourseBookingConfirm'));

		#Registrar Student Booking Supplementary Course List
		Route::get('/register/student/booking/supplimentry/course/list',array('as'=>'Supplementary Course List','uses'=>'RegisterController@RegisterStudentBookingSupplimentryCourseList'));

		#Registrar Supplementary Course Delete
		Route::get('/register/supplimentry/delete/{supplimentry_tran_code}',array('as'=>'Registrar Supplementary Course Delete','uses'=>'RegisterController@RegisterSupplimentryCourseBookingDelete'));


		#Registrar Student Supplementary Course
		Route::get('/register/student/supplimentry/course',array('as'=>'Registrar Supplementary Course','uses'=>'RegisterController@RegisterStudentSupplimentryCourse'));

		#Registrar Supplementary Result Update
		Route::post('/register/supplimentry-result-update/{student_serial_no}/{course_code}/{program}/{semester}/{year}',array('as'=>'Registrar Supplementary Result Update','uses'=>'RegisterController@SupplimentryCourseResultUpdate'));


		#Registrar Not Paid Applicant
		Route::get('/register/not-paid/applicant',array('as'=>'Registrar Not Paid Applicant','uses'=>'RegisterController@NotPaidApplicantPage'));

		#Registrar Not Paid Applicant Delete
		Route::get('/register/not-paid/applicant/no-{applicant_serial_no}',array('as'=>'Registrar Not Paid Applicant Delete','uses'=>'RegisterController@NotPaidApplicantDelete'));


		#Registrar Student Batch Semester Change
		Route::get('/register/student/batch-semester/change',array('as'=>'Registrar Student Change Info','uses'=>'RegisterController@RegisterStudentBatchSemesterChange'));

		#Registrar Student Batch Semester Change Confirm
		Route::post('/register/student/batch-semester/change/confirm',array('as'=>'Registrar Student Change Info Confirm','uses'=>'RegisterController@RegisterStudentBatchSemesterChangeConfirm'));



	});

	
	#####################
	## Academic Settings Module
	######################
	

	Route::group(['middleware' => ['administration_auth']], function () {

		#Academic-Home
		Route::get('/academic/{name_slug}/home',array('as'=>"Academic",'uses'=>'AcademicSetupController@AcademicDahsboardPage'));

		#Academic-Course-Settings
		Route::get('/academic/course-settings',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseSettingsPage'));

		#Academic-Course-Category Add
		Route::post('/academic/course/category/add',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseCategoryAdd'));

		#Academic-Course-Basic Add
		Route::post('/academic/course/entry',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseBasicEntry'));


		#Academic-Course-List Ajax
		Route::get('/academic/course-list/ajax/{program}',array('as'=>"Academic",'uses'=>'AcademicSetupController@AcademicCourseListAjax'));

		Route::post('/academic/course-category/update',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseCategoryUpdate'));

		//Route::post('/academic/course-catalogue/entry',array('as'=>"Academic",'uses'=>'AcademicSetupController@AcademicCatalogueEntry'));

		#Academic-Settings Edit Update Ajax
		Route::get('/academic/course-settings/{course_setting_type}/{type_slug}',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseEditFormAjaxRequest'));

		#Academic-Setting Course Category Update
		Route::post('/academic/course-category/edit/{category_slug}',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseCategoryEdit'));

		#Academic-Setting Course Edit
		Route::post('/academic/course/edit/{course_slug}',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseInfoUpdate'));

		#Academic-Setting Course Catalogue Update
		Route::post('/academic/course-catalogue/edit/{catalogue_slug}',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseCatalogueUpdate'));

		#Academic-Settings Delete Request
		Route::get('/academic/course-settings/delete/{course_setting_type}/{type_slug}',array('as'=>"Academic",'uses'=>'AcademicSetupController@CourseSettingsDelete','middleware'=>'auth.basic'));



		#Academic Settings Home Page
		Route::get('/academic-settings/home',array('as'=>"Academic Settings",'uses'=>'AcademicSetupController@AcademicSettingsPage'));
		
		#Academic Settings Form Submit
		Route::post('/academic-settings/home/{action}',array('as'=>"Academic Settings Form Submit",'uses'=>'AcademicSetupController@AcademicSettingsFormSubmit'));

		#Degree Delete
		Route::get('/academic-settings/home/degree/{degree_slug}',array('as'=>"Degree Delete",'uses'=>'AcademicSetupController@DegreeDelete'));

		#Department Delete
		Route::get('/academic-settings/home/department/{department_slug}',array('as'=>"Department Delete",'uses'=>'AcademicSetupController@DepartmentDelete'));

		#Program Delete
		Route::get('/academic-settings/home/program/{program_slug}',array('as'=>"Program Delete",'uses'=>'AcademicSetupController@ProgramDelete'));

		#Semester Delete
		Route::get('/academic-settings/home/semester/{semester_slug}',array('as'=>"Semester Delete",'uses'=>'AcademicSetupController@SemesterDelete'));

		#Campus Delete
		Route::get('/academic-settings/home/campus/{campus_slug}',array('as'=>"Campus Delete",'uses'=>'AcademicSetupController@CampusDelete'));

		#Building Delete
		Route::get('/academic-settings/home/building/{building_slug}',array('as'=>"Building Delete",'uses'=>'AcademicSetupController@BuildingDelete'));

		#Room Delete
		Route::get('/academic-settings/home/room/{room_slug}',array('as'=>"Room Delete",'uses'=>'AcademicSetupController@RoomDelete'));

		#Edit Academic Setup Page
		Route::get('/academic-settings/home-{setting_type}-{slug}',array('as'=>"Edit Degree Page",'uses'=>'AcademicSetupController@EditAcademicSettings'));

		#Update Degree
		Route::post('/academic-settings/update/degree/{degree_slug}',array('as'=>"Update Degree",'uses'=>'AcademicSetupController@UpdateDegree'));

		#Update Department
		Route::post('/academic-settings/update/department/{department_slug}',array('as'=>"Update Department",'uses'=>'AcademicSetupController@UpdateDepartment'));

		#Update Program
		Route::post('/academic-settings/update/program/{program_slug}',array('as'=>"Update Program",'uses'=>'AcademicSetupController@UpdateProgram'));

		#Update Semester
		Route::post('/academic-settings/update/semester/{semester_slug}',array('as'=>"Update Semester",'uses'=>'AcademicSetupController@UpdateSemester'));

		#Update Campus
		Route::post('/academic-settings/update/campus/{campus_slug}',array('as'=>"Update Campus",'uses'=>'AcademicSetupController@UpdateCampus'));

		#Update Building
		Route::post('/academic-settings/update/building/{building_slug}',array('as'=>"Update Building",'uses'=>'AcademicSetupController@UpdateBuilding'));

		#Update Room
		Route::post('/academic-settings/update/room/{room_slug}',array('as'=>"Update Room",'uses'=>'AcademicSetupController@UpdateRoom'));

		#Course Catalouge List Ajax
		Route::get('/academic/catalouge-list/ajax/{program}',array('as'=>"Catalouge List Ajax",'uses'=>'AcademicSetupController@CourseCatalougeListAjax'));

		Route::post('/academic/catalouge-list/store-degree-plan',array('as'=>"Store Degree Plan",'uses'=>'AcademicSetupController@StoreDegreePlan'));


		#View Degree Plan
		Route::get('/academic-settings/view/degree-plan-detail/{degree_plan_tran}',array('as'=>"View Degree Plan",'uses'=>'AcademicSetupController@ViewDegreePlan'));

		#Delete Degree Plan
		Route::get('/academic-settings/delete-degree-plan/{degree_plan_tran}',array('as'=>"Delete Degree Plan",'uses'=>'AcademicSetupController@DeleteDegreePlan'));

		#Delete Course Catalouge
		Route::get('/academic-settings/delete-course-catalouge/{course_catalouge_tran}',array('as'=>"Delete Course Catalouge",'uses'=>'AcademicSetupController@DeleteCourseCatalouge'));
		
	});



	/*
	###################
	## Faculty Panel
	###################
	*/

	
	Route::group(['middleware' => 'faculty_auth'], function(){

		#Dashboard Faculty
		Route::get('/faculty/{name_slug}/home',array('as'=>'Faculty Dashboard','uses'=>'FacultyController@FacultyDashboard'));

		#Faculty Class Schedule
		Route::get('/faculty/class-schedule',array('as'=>'Class Schedule','uses'=>'FacultyController@FacultyClassSchedule'));

		#Faculty Course Advising
		Route::get('/faculty/course-advising',array('as'=>'Course Advising','uses'=>'FacultyController@FacultyCourseAdvising'));

		#Faculty Course Re Advising
		Route::get('/faculty/course-readvising',array('as'=>'Course Re Advising','uses'=>'FacultyController@FacultyCourseReAdvising'));

	

		#Faculty Result Processing
		Route::get('/faculty/result-processing',array('as'=>'Result Processing','uses'=>'FacultyController@FacultyResultProcessing'));

		#Faculty Exam schedule
		Route::get('/faculty/exam-schedule',array('as'=>'Exam Schedule','uses'=>'FacultyController@FacultyInvigilatorSchedule'));

		#Faculty Exam schedule
		Route::get('/faculty/invigilator-schedule-download',array('as'=>'Invigilator Schedule Download','uses'=>'FacultyController@FacultyInvigilatorScheduleDownload'));

		#Faculty Edit Profile
		Route::get('/faculty/edit-profile/{faculty_id}',array('as'=>'Faculty Edit Profile','uses'=>'FacultyController@FacultyEditProfile'));

		#Faculty Update Basic Profile
		Route::post('/faculty/edit-profile/{faculty_id}',array('as'=>'Faculty Update Basic Profile','uses'=>'FacultyController@FacultyUpdateBasicProfile'));

		#Faculty Update Contract Profile
		Route::post('/faculty/update-contract-profile/{faculty_tran_code}',array('as'=>'Faculty Update Contract Profile','uses'=>'FacultyController@FacultyUpdateContractProfile'));


		#Faculty Pre Advising List
		Route::get('/faculty/pre-advising-lists/{program_id}/{level}/{term}/{semester}/{year}',array('as'=>'FacultyPreAdvisingLists','uses'=>'FacultyController@FacultyPreAdvisingLists'));

		#Faculty Pre Advising Modal
		Route::get('/faculty/pre-advising-modal/{temp_tran_code}',array('as'=>'FacultyPreAdvisingModal','uses'=>'FacultyController@FacultyPreAdvisingModal'));

		#Faculty Pre Advising Submit
		Route::post('/faculty/pre-advising-submit',array('as'=>'Faculty Pre Advising Submit','uses'=>'FacultyController@FacultyPreAdvisingSubmit'));

		#FacultyResultProcessingMarksSubmit
		Route::get('/faculty/result-processing-marks-submit/{program}/{course_code}/{semester}/{year}',array('as'=>'Faculty Result Processing Marks Submit','uses'=>'FacultyController@FacultyResultProcessingMarksSubmit'));

		#FacultyResultProcessingClassTestStore
		Route::get('/faculty/result-class-test-store/{student_serial_no}/{program}/{semester}/{year}/{course_code}/{ct_1}/{ct_2}/{ct_3}/{ct_4}',array('as'=>'Faculty Result Processing Class Test Store','uses'=>'FacultyController@FacultyResultProcessingClassTestStore'));

		#FacultyResultProcessingMidTermStore
		Route::get('/faculty/result-mid-term-store/{student_serial_no}/{program}/{semester}/{year}/{course_code}/{mid_term}/{mid_term_outof}',array('as'=>'Faculty Result Processing Mid Term Store','uses'=>'FacultyController@FacultyResultProcessingMidTermStore'));

		#FacultyResultProcessingFinalExamStore
		Route::get('/faculty/result-final-store/{student_serial_no}/{program}/{semester}/{year}/{course_code}/{class_attendance}/{class_participation}/{class_presentaion}/{class_final_exam}/{final_outof}',array('as'=>'Faculty Result Processing Mid Term Store','uses'=>'FacultyController@FacultyResultProcessingFinalExamStore'));

		#FacultyResultProcessingLabResultStore
		Route::get('/faculty/lab-result-store/{student_serial_no}/{program}/{semester}/{year}/{course_code}/{lab_attendance}/{lab_performance}/{lab_reprot}/{lab_verbal}/{lab_final}',array('as'=>'Faculty Result Processing Lab Result Store','uses'=>'FacultyController@FacultyResultProcessingLabResultStore'));

		#FacultyResultPublish
		Route::post('/faculty/result-publish',array('as'=>'Faculty Result Publish','uses'=>'FacultyController@FacultyResultPublish'));

		#Faculty Notice board
		Route::get('/faculty/notice-board',array('as'=>'Notice Board','uses'=>'FacultyController@FacultyNoticeBoard'));

		#Faculty Notice Submit
		Route::post('/faculty/notice-board',array('as'=>'Notice Board Submit','uses'=>'FacultyController@FacultyNoticeSubmit'));

		#Faculty Notice Delete
		Route::get('/faculty/notice-board/delete/{notice_tran_code}',array('as'=>'Faculty Notice Delete','uses'=>'FacultyController@FacultyNoticeDelete'));

		#Faculty Notice Edit
		Route::get('/faculty/notice-board/edit/{notice_tran_code}',array('as'=>'Faculty Notice Edit','uses'=>'FacultyController@EditFacultyNoticePage'));

		#Update Faculty Notice
		Route::post('/faculty/notice-board/edit/{notice_tran_code}',array('as'=>"Update Faculty Notice",'uses'=>'FacultyController@UpdateFacultyNotice'));

		#Faculty Notice View
		Route::get('/faculty/home/{notice_tran_code}',array('as'=>'Faculty Notice View','uses'=>'FacultyController@FacultyNotice'));

		#Faculty All Notice View
		Route::get('/faculty/all/notice',array('as'=>'Faculty All Notice View','uses'=>'FacultyController@FacultyAllNotice'));

		#Faculty Class Schedule Pdf Download
		Route::get('/faculty/pdf/class-schedule-download',array('as'=>'Faculty Class Schedule Pdf Download','uses'=>'FacultyController@FacultyClassSchedulePdfDownload'));

		#Faculty Assigned Courses
		Route::get('/faculty/assigned-courses',array('as'=>'Faculty Assigned Courses','uses'=>'FacultyController@FacultyAssignedCourses'));


		#Program Head Result Publish
		Route::get('/faculty/program-head-result-publish',array('as'=>'Program Head Result Publish','uses'=>'FacultyController@ProgramHeadResultPublish'));

		#Ajax Program Head Result Publish Modal
		Route::get('/faculty/ajax-program-head-result-publish/{course_code}',array('as'=>'Program Head Result Publish Modal','uses'=>'FacultyController@ProgramHeadResultPublishModal'));

		#Program Head Result Update
		Route::get('/faculty/program-head-result-update/{student_serial_no}/{course_code}/{program}',array('as'=>'Program Head Result Update','uses'=>'FacultyController@ProgramHeadResultUpdate'));

		#FacultyHeadResultPublish
		Route::post('/faculty/head-result-publish',array('as'=>'Faculty Head Result Publish','uses'=>'FacultyController@FacultyHeadResultPublish'));


		#---Faculty Student Class Attendance Module---#
		#Faculty Student Attendance List Page
		Route::get('/faculty/student/attendance/list',array('as'=>"Student Attendance List",'uses'=>'FacultyController@FacultyStudentAttendanceListPage'));

		#Faculty Student Attendance Submit Page
		Route::post('/faculty/student/attendance/list/submit',array('as'=>"Faculty Attendance Submit",'uses'=>'FacultyController@FacultyStudentAttendanceSubmit'));


		#Faculty Student Attendance Percent
		Route::get('/faculty/student/attendance/percent',array('as'=>"Student Attendance Percent",'uses'=>'FacultyController@FacultyStudentAttendancePercent'));



	});
	


	
	/*
	#####################
	## Addmitted Student Panel
	######################
	*/

	Route::group(['middleware' => ['student_auth']], function () {

		#Dashboard Student
		Route::get('/student/{name_slug}/home',array('as'=>'Student Dashboard','uses'=>'StudentController@StudentDashboard'));

		#Student Edit Profile
		Route::get('/student/edit-profile',array('as'=>'Profile Edit','uses'=>'StudentController@StudentEditProfile'));

		
		#Student Update Basic Profile
		Route::post('/student/basic-info-update',array('as'=>'Student Update Basic Profile','uses'=>'StudentController@StudentUpdateBasicProfile'));

		#Student Update Contact Profile
		Route::post('/student/contact-info-update/{student_tran_code}',array('as'=>'Student Update Contact Profile','uses'=>'StudentController@StudentUpdateContractProfile'));

		#Student Gurdian Profile
		Route::post('/student/gurdian-profile/submit',array('as'=>'Student gurdian profile submit','uses'=>'StudentController@StudentProfileGurdianSubmit'));

		#Student Gurdian Profile Update
		Route::post('/student/gurdian-profile/update/{student_tran_code}',array('as'=>'Student gurdian profile update','uses'=>'StudentController@StudentProfileGurdianUpdate'));



		#Student Class Schedule
		Route::get('/student/class-schedule',array('as'=>'Student Class Schedule','uses'=>'StudentController@StudentClassSchedule'));

		#Student Pre Advising
		Route::get('/student/pre-advising',array('as'=>'Student Pre Advising','uses'=>'StudentController@StudentPreAdvising'));

		#Student Pre Advising Submit
		Route::post('/student/pre-advising-submit',array('as'=>'Student Pre Advising Submit','uses'=>'StudentController@StudentPreAdvisingSubmit'));

		#Student Re Advising
		Route::get('/student/re-advising/{temp_preadvising_tran_code}/{level}/{term}',array('as'=>'Student Re-Advising','uses'=>'StudentController@StudentReAdvising'));

		#Student Re Advising Submit
		Route::post('/student/re-advising/submit/{temp_preadvising_tran_code}',array('as'=>'Student Re Advising Submit','uses'=>'StudentController@StudentReAdvisingSubmit'));

		#StudentPreAdvisingPayment
		Route::post('/student/pre-advising-payment',array('as'=>'Student Pre Advising Payment','uses'=>'StudentController@StudentPreAdvisingPayment'));

		#Student Grade Sheet
		Route::get('/student/grade-sheet',array('as'=>'Student Grade Sheet','uses'=>'StudentController@StudentGradeSheet'));

		#Student Payment History
		Route::get('/student/payment-history',array('as'=>'Student Payment Ledger','uses'=>'StudentController@StudentPaymentHistory'));

		#Student Exam Routine
		Route::get('/student/exam-routine',array('as'=>'Student Exam Routine','uses'=>'StudentController@StudentExamRoutine'));

		#Student Exam Routine Download
		Route::get('/student/exam-routine-download',array('as'=>'Student Exam Routine Download','uses'=>'StudentController@StudentExamRoutineDownload'));

		#Student Payment History Ajax
		Route::get('/student/payment-history/{semester}',array('as'=>'Student Payment Ledger Ajax','uses'=>'StudentController@StudentPaymentHistoryAjax'));

		#Student Academic Course Plan
		Route::get('/student/academic-course-plan',array('as'=>'Student Academic Course Plan','uses'=>'StudentController@StudentAcademicCoursePlan'));

		#Ajax Student Course Plan Details
		Route::get('/student/course-plan-details/{program}/{course_category}',array('as'=>'Ajax Student Course Plan Details','uses'=>'StudentController@AjaxStudentCoursePlanDetails'));

		#Student Notice View
	    Route::get('/student/home/{notice_tran_code}',array('as'=>'Student Notice View','uses'=>'StudentController@StudentNoticeView'));

	    #Student All Notice View
		Route::get('/student/all/notice',array('as'=>'Student All Notice View','uses'=>'StudentController@StudentAllNotice'));

	    #Student Class Schedule Download
			Route::get('/student/class/schedule/download',array('as'=>"Student Class Schedule Download",'uses'=>'StudentController@StudentClassScheduleDownload'));
	
	});


	/*
	#####################
	## System Admin Panel
	######################
	*/
	Route::group(['middleware' => ['systemadmin_auth']], function () {


		#Systemadmin Student Account Search
		Route::get('/systemadmin/student-account',array('as'=>"Student Account Creation",'uses'=>'SystemAdminController@SystemadminStudentAccount'));

		#Systemadmin Student Account Search
		Route::post('/systemadmin/student-account-submit',array('as'=>"Student Registration",'uses'=>'SystemAdminController@StudentRegistration'));

		#System admin home
		Route::get('/systemadmin/{name_slug}/home',array('as'=>"SystemAdmin Dashboard",'uses'=>'SystemAdminController@SystemAdminHomePage'));

		#Access Log List
		Route::get('/system-admin/access-logs',array('as'=>"Access Logs",'uses'=>'SystemAdminController@AccessLogs'));

		# Error Log List
		Route::get('/system-admin/error-logs',array('as'=>"Error Logs",'uses'=>'SystemAdminController@ErrorLogs'));

		#Event Log List
		Route::get('/system-admin/event-logs',array('as'=>"Event Logs",'uses'=>'SystemAdminController@EventLogs'));

		#Auth Log List
		Route::get('/system-admin/auth-logs',array('as'=>"Auth Logs",'uses'=>'SystemAdminController@AuthLogs'));

	 	#Faculty Account
		Route::get('/system-admin/faculty-account',array('as'=>"Faculty Account Create",'uses'=>'SystemAdminController@FacultyAccountCreate'));

		#Faculty Account submit
		Route::post('/system-admin/faculty-account-submit',array('as'=>"Faculty Registration",'uses'=>'SystemAdminController@FacultyRegistration'));
		// Route::post('/system-admin/faculty-account-submit',array('as'=>"Faculty Registration",'uses'=>'SystemAdminController@TestingRegistration'));

		#SystemUsers
		Route::get('/system-admin/system-users',array('as'=>"System Users",'uses'=>'SystemAdminController@SystemUsers'));

		#Employee Account
		Route::get('/system-admin/employee-account',array('as'=>"Employee Account Create",'uses'=>'SystemAdminController@EmployeeAccountCreate'));

		#Employee Account submit
		Route::post('/system-admin/employee-account-submit',array('as'=>"Employee Registration",'uses'=>'SystemAdminController@EmployeeRegistration'));

		#Employee Account submit
		Route::get('system/user/change/id-{user_id}/status-{status}',array('as'=>"User Change Status",'uses'=>'SystemAdminController@SystemUsersChangeStatus'));

	

	});

	

	/*
	##########################
	## Academic Setup Panel
	###########################
	*/
	Route::get('/applicant/info/{applicant_serial_no}',function($applicant_serial_no){

		$data['applicant_serial_no'] = $applicant_serial_no;

		return \View::make('application.ajax.test',$data);
	});


	Route::get('/notification/alert',function(){

		echo asset('FACULTY/01/1701004.jpg');

	//	return \View::make('ajax-request.notification');
	});









	Route::get('/dbset',array('as'=>"Test user Registration",'uses'=>'SystemAdminController@TestingRegistration'));



	Route::get('/dbsets',function(){



	});



	Route::get('/dbtest',function(){

		// $img_src = 'http://202.4.111.35/faculty-dashboard/public/images/banner-form.png';
		// $data = @file_get_contents($img_src , false, $context);
		// if(is_writable(asset('/STUDENT/temp.jpg')))
		// 	echo "write";
		// else "Failed";

		// $dest = asset('/STUDENT/temp.jpg');
		// $status = @copy($img_src, $dest) or die("Could not copy file contents");


	});







