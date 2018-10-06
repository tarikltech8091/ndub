/*
#######################
## Custom Js Script
#######################
*/

/*----------------------------------Account Module Script--------------------------------------------------------------*/

/*##########################################
#s All System Image Upload
############################################
*/
jQuery(function() {

	jQuery('#image').change(function(){
		var site_url = $('.site_url').val();
		var request_url = site_url+'/'+'register/register-faculty-account/image-upload';
		jQuery("#validation-errors").hide().empty();

		jQuery.ajax({
			url:request_url,
			data:new FormData($("#upload")[0]),
			dataType:'json',
			async:false,
			type:'POST',
			processData: false,
			contentType: false,
			success:function(response){

				if(response.success == "invalid_format"){


					jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>File Type is not allowed !!</strong></div>');
					jQuery("#validation-errors").show();
					jQuery(".image_loader").removeClass('loading_icon'); 

				}else if(response.success == "filesize"){


					jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>Filesize is not valid!!</strong></div>');
					jQuery("#validation-errors").show();
					jQuery(".image_loader").removeClass('loading_icon'); 
				}

				else {

					var site_url = jQuery('.site_url').val();
					jQuery("#demo").html("<img src='"+site_url+"/"+response.file+"' />");
					jQuery("#output").css('display','block');
					jQuery("#image_url").val(response.file);
					jQuery(".image_loader").removeClass('loading_icon'); 

				}
			},
		});
});
});




/*##########################################
# Applicant Payment Approved Selectbox
############################################
*/

jQuery(function () {
	
	 jQuery('#apporoved_payment_selectall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            jQuery('.apporoved_payment_group').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            jQuery('.apporoved_payment_group').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });

	});

/*##########################################
# Applicant Payment Approved Saved and next
############################################
*/
jQuery(function(){

	jQuery('.apporoved_payment_submit').click(function(){

		var next_page_url = jQuery(this).data('nexturl');

		var applicant_serial_list = jQuery('input:checkbox:checked').map(function() {
			return this.value;
		}).get();

		if((applicant_serial_list.length != 0) &&(applicant_serial_list!=0)){

			var site_url = jQuery('.site_url').val();

			var request_url = site_url+'/accounts/applicant/payemnt-verify/'+applicant_serial_list;

			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){

					window.location.href = next_page_url;
				}
			});


		}else window.location.href = next_page_url;

	});


});

/*##########################################
# Single Applicant Payment Approved 
############################################
*/

jQuery(function(){

	jQuery('.apporoved_payment_single').click(function(){

		var applicant_serial_list = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();

		var request_url = site_url+'/accounts/applicant/payemnt-verify/'+applicant_serial_list;


		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});

/*##########################################
# Applicant Cash Payment 
############################################
*/

jQuery(function(){

	jQuery('#cash_payment').click(function(){

		var applicant_serial_no = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/accounts/applicant/cash-payment/received/'+applicant_serial_no;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				location.reload();
			}
		});


	});
});

/*###########################
# Applicant Serach By Account
#############################
*/ 

jQuery(function(){

	jQuery('.applicant_payment_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/accounts/applicant/payment?'+request_url;

		}else window.location.href = site_url+'/accounts/applicant/payment';

		
	});
});

/*##########################################
# Admission payment Approved By Accounts
############################################
*/
jQuery(function(){

	jQuery('.admission_payment_single').click(function(){

		var applicant_serial_no = jQuery(this).data('id');

		var payment_type = jQuery(".payment_type_"+applicant_serial_no).val();
		
		var slip_no = jQuery(".slip_no_"+applicant_serial_no).val();


		if(slip_no.length==0){
			var slip_no=0;
		}

		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();

		var request_url = site_url+'/accounts/admission/payement/approved/'+applicant_serial_no+'/'+payment_type+'/'+slip_no;


		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});



	});
});



/*
###########################
# accounts message to applicant
############################
*/

jQuery(function(){
	jQuery('.accounts_message_to_applicant').click(function(){

		var applicant_serial_no = jQuery(this).data('applicant');
		var message_issue = jQuery(this).data('message-issue');

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/accounts/applicant/message/'+message_issue+'/'+applicant_serial_no;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.write_message_to').html(data);
			}
		});

	});
});


/*##########################################
# Single Applicant Payment Undone 
############################################
*/

jQuery(function(){

	jQuery('.apporoved_payment_undone').click(function(){

		var applicant_serial_no = jQuery(this).data('id');
		var payment_by = jQuery(this).data('payment-by');
		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();

		var request_url = site_url+'/accounts/applicant/payemnt-undone/'+payment_by+'/'+applicant_serial_no;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});




/*##########################################
# Single Applicant Admission Payment Undone 
############################################
*/

jQuery(function(){

	jQuery('.admission_payment_undone').click(function(){

		var applicant_serial_no = jQuery(this).data('id');

		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();

		var request_url = site_url+'/accounts/admission/payemnt-undone/'+applicant_serial_no;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});



/*
###########################
# student payment edit modal
############################
*/

jQuery(function(){
	jQuery('.student_payment_edit_modal').click(function(){

		var payment_tran_code = jQuery(this).data('payment-tran-code');

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/accounts/student-payment-edit/'+payment_tran_code;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.student_payment_edit').html(data);
			}
		});

	});
});


/*###############################
# Student Payment Transaction Search
#################################
*/

jQuery(function(){

	jQuery('.student_payment_transaction').click(function(){

		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var student_serial_no = jQuery(".student_serial_no").val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;


		if((semester.length !=0) && semester !=0){
			parameter=1;
			request_url += 'semester='+semester;
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if((student_serial_no.length !=0) && student_serial_no !=0){

			if(parameter==1)
				request_url += '&student_serial_no='+student_serial_no;
			else
				request_url += 'student_serial_no='+student_serial_no;
			
		}


		if(request_url.length !=0)
			window.location.href = site_url+'/accounts/student-payment-transaction?'+request_url;
		else	
			window.location.href = site_url+'/accounts/student-payment-transaction';
		

	});
});




 //Error Log formdate Picker
 jQuery(function () {

 	jQuery('.form_date_search').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});

 });

 jQuery(function () {
 	jQuery('.to_date_search').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});
 });


  //Event Log formdate Picker

  jQuery(function () {
  	jQuery('.from_date_search_event').datetimepicker({

  		weekStart: 1,
  		todayBtn:  1,
  		autoclose: 1,
  		todayHighlight: 1,
  		startView: 2,
  		minView: 2,
  		forceParse: 0
  	});
  });

  jQuery(function () {
  	jQuery('.to_date_search_event').datetimepicker({

  		weekStart: 1,
  		todayBtn:  1,
  		autoclose: 1,
  		todayHighlight: 1,
  		startView: 2,
  		minView: 2,
  		forceParse: 0
  	});
  });

 //Access Log formdate Picker

 jQuery(function () {
 	jQuery('.from_date_search_access').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});
 });

 jQuery(function () {
 	jQuery('.to_date_search_access').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});
 });


 //Auth Log formdate Picker
 jQuery(function () {
 	jQuery('.from_date_search_auth').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});
 });


 jQuery(function () {
 	jQuery('.to_date_search_auth').datetimepicker({

 		weekStart: 1,
 		todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 2,
 		minView: 2,
 		forceParse: 0
 	});
 });




/*###########################
# Applicant Total Amount Search
#############################
*/ 
jQuery(function(){

	jQuery('.applicant_total_amount_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year").val();
		var payment = jQuery(".payment option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			parameter=1;
			
		}

		if((payment.length !=0) && payment !='all'){

			if(parameter==1)
				request_url += '&payment='+payment;
			else
				request_url += 'payment='+payment;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/accounts/applicant/total-amount?'+request_url;

		}else window.location.href = site_url+'/accounts/applicant/total-amount';

		
	});
});


/*##########################
# Total Applicant List Print
############################
*/

jQuery(function(){

	jQuery('.accounts_total_applicant_list_print').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year").val();
		var payment = jQuery(".payment option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			parameter=1;
			
		}

		if((payment.length !=0) && payment !='all'){

			if(parameter==1)
				request_url += '&payment='+payment;
			else
				request_url += 'payment='+payment;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/accounts/total-applicant/list/download?'+request_url;

		}else window.location.href = site_url+'/accounts/total-applicant/list/download';
		

	});
});



/*##########################
# Accounts Admission List Search
############################
*/

jQuery(function(){

	jQuery('.accounts_admission_payment_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/accounts/admission/payement/list?'+request_url;

		}else window.location.href = site_url+'/accounts/admission/payement/list';
		

	});
});



/*##########################
# Accounts Admission List Print
############################
*/

jQuery(function(){

	jQuery('.accounts_admission_list_print').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/accounts/admission/list/download?'+request_url;

		}else window.location.href = site_url+'/accounts/admission/list/download';
		

	});
});





/*-----------------------End Account Module Script----------------------------------------*/



/*-------------------------Register Module Script--------------------------------------*/
/*##########################################
# Waiting Applicant all Selectbox
############################################
*/

jQuery(function () {
	
	 jQuery('#waiting_selectall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            jQuery('.waiting_selected_group').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            jQuery('.waiting_selected_group').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });

	});

 /*##########################################
# Merit Applicant all Selectbox
############################################
*/

jQuery(function () {
	
	 jQuery('#merit_selectall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            jQuery('.merit_selected_group').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            jQuery('.merit_selected_group').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });

	});

/* ===============================
   Applicant Details view Ajax
   * ============================= */

   jQuery(function(){

   	jQuery('.view_details').click(function(){

   		var applicant_serial_no = jQuery(this).data('id');
   		var site_url = jQuery('.site_url').val();

   		if(applicant_serial_no.length !=0){

   			var request_url = site_url+'/register/applicant/list/detail-'+applicant_serial_no;

   			jQuery.ajax({
   				url: request_url,
   				type: "get",
   				success:function(data){

   					jQuery('.details_view').html(data);
   				}
   			});

   		}else alert("Please Add serial no");

   	});


   });

/*##########################################
# Waiting List Select Saved
############################################
*/
jQuery(function(){

	jQuery('.waiting_select_submit').click(function(){

		var current_page_url = jQuery('.current_page_url').val();

		var applicant_serial_list = jQuery("input[name='waiting_selected_checkbox[]']:checked").map(function() {
			return this.value;
		}).get();


		if((applicant_serial_list.length != 0) &&(applicant_serial_list!=0)){

			var site_url = jQuery('.site_url').val();
			var status=2;

			var request_url = site_url+'/register/candidate/approved/'+applicant_serial_list+'/'+status;

			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){

					window.location.href = current_page_url;
				}
			});

		}else window.location.href = current_page_url;

	});

});

 /*##########################################
# Waiting List Select Saved
############################################
*/
jQuery(function(){

	jQuery('.merit_select_submit').click(function(){

		var current_page_url = jQuery('.current_page_url').val();

		var applicant_serial_list = jQuery("input[name='merit_selected_checkbox[]']:checked").map(function() {
			return this.value;
		}).get();



		if((applicant_serial_list.length != 0) &&(applicant_serial_list!=0)){

			var site_url = jQuery('.site_url').val();
			var status=3;

			var request_url = site_url+'/register/candidate/approved/'+applicant_serial_list+'/'+status;

			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){

					window.location.href = current_page_url;
				}
			});

		}else window.location.href = current_page_url;

	});

});

/*##########################################
# Single Waiting Approved 
############################################
*/

jQuery(function(){

	jQuery('.waiting_selected_single').click(function(){

		var applicant_serial_list = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();
		var status =2;

		var request_url = site_url+'/register/candidate/approved/'+applicant_serial_list+'/'+status;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});

/*##########################################
# Single Merit Approved 
############################################
*/

jQuery(function(){

	jQuery('.merit_selected_single').click(function(){

		var applicant_serial_list = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();
		var status =3;

		var request_url = site_url+'/register/candidate/approved/'+applicant_serial_list+'/'+status;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});

/*###########################
# Register Serach Applicant
#############################
*/ 

jQuery(function(){

	jQuery('.applicant_register_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/applicant/list?'+request_url;

		}else window.location.href = site_url+'/register/applicant/list';

		
	});
});

/*###########################
# Register Serach Admission
#############################
*/ 

jQuery(function(){

	jQuery('.admission_register_list_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var religion = jQuery(".religion option:selected").val();
		var gender = jQuery(".gender option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			parameter=1;
			
		}


		if(request_url.length !=0){

			window.location.href = site_url+'/register/admission/list?'+request_url;

		}else window.location.href = site_url+'/register/admission/list';

		
	});
});

/*##########################
# Admission List Print
############################
*/

jQuery(function(){

	jQuery('.register_admission_list_print').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}


		
		if(request_url.length !=0)
			window.location.href = site_url+'/register/admission/list/download?'+request_url;
		else	
			window.location.href = site_url+'/register/admission/list/download';
		

	});
});



jQuery(function () {

	jQuery('.date_of').datetimepicker({

		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
});


/*##########################
# Applicant List Print
############################
*/

jQuery(function(){

	jQuery('.register_applicant_print').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}


		
		if(request_url.length !=0)
			window.location.href = site_url+'/register/applicant/list/download?'+request_url;
		else	
			window.location.href = site_url+'/register/applicant/list/download';
		

	});
});



/*##########################
# Faculty List Print
############################
*/

jQuery(function(){

	jQuery('.register_faculty_list_print').click(function(){

		var department = jQuery(".department option:selected").val();
		var program = jQuery(".program option:selected").val();

		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((department.length !=0) && department !=0){

			parameter=1;
			request_url += 'department='+department;
		}

		if((program.length !=0) && program !=0){
			if(parameter==1)
				request_url += '&program='+program;
			else{
				request_url += 'program='+program;
				parameter=1;
			}
			
		}

		
		if(request_url.length !=0)
			window.location.href = site_url+'/register/faculty/list/download?'+request_url;
		else	
			window.location.href = site_url+'/register/faculty/list/download';
		

	});
});


/*##########################
# Employee List Print
############################
*/

jQuery(function(){

	jQuery('.register_employee_list_print').click(function(){

		var employee_department = jQuery(".employee_department option:selected").val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((employee_department.length !=0) && employee_department !=0){

			parameter=1;
			request_url += 'employee_department='+employee_department;
		}

		if(request_url.length !=0)
			window.location.href = site_url+'/register/employee/list/download?'+request_url;
		else	
			window.location.href = site_url+'/register/employee/list/download';
		

	});
});


/*##########################
# Student List Print
############################
*/

jQuery(function(){

	jQuery('.register_student_list_print').click(function(){

		var program = jQuery(".program option:selected").val();
		// var batch_no = jQuery(".batch_no option:selected").val();
		var batch_no = jQuery(".batch_no").val();
		var site_url = jQuery('.site_url').val();
		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((batch_no.length !=0) && batch_no !=0){
			if(parameter==1)
				request_url += '&batch_no='+batch_no;
			else{
				request_url += 'batch_no='+batch_no;
				parameter=1;
			}
			
		}

		if(request_url.length !=0)
			window.location.href = site_url+'/register/student/list/download?'+request_url;
		else	
			window.location.href = site_url+'/register/student/list/download';
		

	});
});


/*###########################
# Registered Faculty Search
#############################
*/ 
jQuery(function(){

	jQuery('.total_faculty_search').click(function(){

		var department = jQuery(".department option:selected").val();
		var program = jQuery(".program option:selected").val();
		
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();
		
		var request_url ='';
		var parameter = 0;

		if((department.length !=0) && department !=0){

			parameter=1;
			request_url += 'department='+department;
		}

		if((program.length !=0) && program !=0){
			if(parameter==1)
				request_url += '&program='+program;
			else{
				request_url += 'program='+program;
				parameter=1;
			}
			
		}


		if(request_url.length !=0){

			window.location.href = site_url+'/register/faculty-list?'+request_url;

		}else window.location.href = site_url+'/register/faculty-list';

		
	});
});




/*###########################
# Register Employee Search
#############################
*/ 
jQuery(function(){

	jQuery('.employee_search').click(function(){

		var employee_department = jQuery(".employee_department option:selected").val();

		
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();
		
		var request_url ='';
		var parameter = 0;

		if((employee_department.length !=0) && employee_department !=0){

			parameter=1;
			request_url += 'employee_department='+employee_department;
		}


		if(request_url.length !=0){

			window.location.href = site_url+'/register/employee-list?'+request_url;

		}else window.location.href = site_url+'/register/employee-list';

		
	});
});



/*##########################################
# Time Slot List By ajax
############################################
*/

jQuery(function(){
	jQuery('.time_slot_list').change(function(){

		var invigilators_exam_type = jQuery(this).val();
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/register/time-slot-list-ajax/'+invigilators_exam_type;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.time_slot').html(data);
			}
		});

	});
});


// Student Block Module
/*###########################
# All Student Search
#############################
*/ 
jQuery(function(){

	jQuery('.total_student_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();
		
		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/block/student?'+request_url;

		}else window.location.href = site_url+'/register/block/student';

		
	});
});



/*##########################################
# Student Blocked
############################################
*/

jQuery(function(){

	jQuery('.single_student_block').click(function(){

		var student_serial_list = jQuery(this).data('id');
		var action = jQuery(this).data('action');
		var site_url = jQuery('.site_url').val();
		var current_page_url = jQuery('.current_page_url').val();

		var request_url = site_url+'/register/block/student/'+student_serial_list+'/'+action;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				window.location.href = current_page_url;
			}
		});
	});
});



/*##########################################
# Student Blocked With reason
############################################
*/

	// jquery(document).on("click", ".AddBlockDialog", function () {
 //    	var SelectBlockId = jquery(this).data('id');
 //    	var SelectStatus = jquery(this).data('action');
 //    	alert(SelectBlockId);
 //    	alert(SelectStatus);
 //    	jquery(".modal-body #BlockId").val( SelectBlockId );
 //    	jquery(".modal-body #StatusValue").val( SelectStatus );
	// });






/*###########################
# Register Student Attendance
#############################
*/ 

jQuery(function(){

	jQuery('.register_attendance_list_search').click(function(){

		var program = jQuery(".sprogram option:selected").val();
		var semester = jQuery(".ssemester option:selected" ).val();
		var academic_year = jQuery(".sacademic_year option:selected").val();
		var course = jQuery(".scourse option:selected").val();

		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			parameter=1;
			
		}
		if((course.length !=0) && course !=0){

			if(parameter==1)
				request_url += '&course='+course;
			else
				request_url += 'course='+course;
			parameter=1;
			
		}


		if(request_url.length !=0){

			window.location.href = site_url+'/register/student/attendance/list?'+request_url;

		}else window.location.href = site_url+'/register/student/attendance/list';

		
	});
});



/*###########################
# Register Student Course Withdraw
#############################
*/ 

jQuery(function(){

	jQuery('.register_course_list_search').click(function(){

		var wserial = jQuery('.wserial').val();
		alert(wserial);
		var student_no = jQuery(this).val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();


		
	});
});



/*###########################
# Register Student credit Transfer
#############################
*/ 

jQuery(function(){

	jQuery('.transfer_student_course_search').click(function(){

		var program = jQuery(".courseprogram option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			if(parameter==1)
				request_url += '&program='+program;
			else{
				request_url += 'program='+program;
				parameter=1;
			}
		}
		if(request_url.length !=0){

			window.location.href = site_url+'/register/student/credit/transfer?'+request_url;

		}else window.location.href = site_url+'/register/student/credit/transfer';

		
	});
});




/*##########################################
# All Transfer Student Image Upload
############################################
*/
jQuery(function() {

	jQuery('#image_transfer_student').change(function(){
		var site_url = $('.site_url').val();
		var request_url = site_url+'/'+'/register/student/credit/transfer/image-upload';
		jQuery("#validation-errors").hide().empty();

		jQuery.ajax({
			url:request_url,
			data:new FormData($("#upload")[0]),
			dataType:'json',
			async:false,
			type:'POST',
			processData: false,
			contentType: false,
			success:function(response){

				if(response.success == "invalid_format"){


					jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>File Type is not allowed !!</strong></div>');
					jQuery("#validation-errors").show();
					jQuery(".image_loader").removeClass('loading_icon'); 

				}else if(response.success == "filesize"){

					jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>Filesize is not valid!!</strong></div>');
					jQuery("#validation-errors").show();
					jQuery(".image_loader").removeClass('loading_icon'); 
				}

				else {

					var site_url = jQuery('.site_url').val();
					jQuery("#demo").html("<img src='"+site_url+"/"+response.file+"' />");
					jQuery("#output").css('display','block');
					jQuery("#image_url").val(response.file);
					jQuery(".image_loader").removeClass('loading_icon'); 

				}
			},
		});
});
});




/*##########################################
# Time Slot Start End Time Picker
############################################
*/
jQuery(function () {

	jQuery('.start_time').datetimepicker({

		format: 'HH:ii P',
		autoclose: true,
		showMeridian: true,
		startView: 1,
		maxView: 1
	});

	jQuery('.end_time').datetimepicker({

		format: 'HH:ii P',
		autoclose: true,
		showMeridian: true,
		startView: 1,
		maxView: 1
	});

});





/*###########################
# Register Exam Schedule Modal
#############################
*/ 

jQuery(function(){

	jQuery('.exam_schedule_modal').click(function(){

		var room_code = jQuery(this).data('room');
		var exam_type = jQuery(this).data('exam-type');
		var exam_date = jQuery(this).data('exam-date');
		var time_slot = jQuery(this).data('time-slot');

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/schedule/exam-schedule-modal/'+room_code+'/'+exam_type+'/'+exam_date+'/'+time_slot;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_exam_schedule_modal').html(data);
			}
		});
		
	});
});


/*###########################
# Register Exam Schedule View
#############################
*/ 

jQuery(function(){

	jQuery('.exam_schedule_view').change(function(){

		var exam_type = jQuery('.exam_type').val();
		var program = jQuery('.program').val();
		var trimester = jQuery('.trimester').val();
		var year = jQuery('.year').val();

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/exam-schedule/exam-schedule-view/'+exam_type+'/'+program+'/'+trimester+'/'+year;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_exam_schedule_view').html(data);
			}
		});
		
	});
});


/*
###########################
# Ajax Room List
############################
*/

jQuery(function(){
	jQuery('.building_code').change(function(){

		var building_code = jQuery(this).val();

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/ajax-room-list/'+building_code;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_room_list').html(data);
			}
		});

	});
});



/*
###########################
# Ajax Course List
############################
*/

jQuery(function(){
	jQuery('.program_code').change(function(){

		var program_code = jQuery(this).val();

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/ajax-course-list/'+program_code;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_course_list').html(data);
			}
		});

	});
});



/*
###########################
# Ajax Schedule View
############################
*/

jQuery(function(){
	jQuery('.class_schedule_view').change(function(){

		var room_code = jQuery(this).val();

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/ajax-class-schedule-view/'+room_code;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_class_schedule_view').html(data);
			}
		});

	});
});



/*
###########################
# Ajax Schedule By Program View
############################
*/

jQuery(function(){
	jQuery('.schedule_by_program').change(function(){

		var program_id = jQuery(this).val();

		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/register/ajax-schedule-by-program-view/'+program_id;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_schedule_by_program_view').html(data);
			}
		});

	});
});


/*##########################
# Exam Schedule Pdf Download
############################
*/

jQuery(function(){

	jQuery('.exam_schedule_pdf_download').click(function(){

		var exam_type = jQuery(".exam_type option:selected").val();
		var program = jQuery(".program option:selected").val();
		var trimester = jQuery(".trimester option:selected").val();
		var year = jQuery(".year").val();
		var site_url = jQuery('.site_url').val();
		
		window.location.href = site_url+'/register/exam-schedule/exam-schedule-download-pdf?exam_type='+exam_type+'&program='+program+'&trimester='+trimester+'&year='+year;

	});
});


/*##########################
# Schedule Pdf Download
############################
*/

jQuery(function(){

	jQuery('.schedule_pdf_download').click(function(){

		var program = jQuery(".schedule_by_program option:selected").val();
		var site_url = jQuery('.site_url').val();
		
		window.location.href = site_url+'/register/class/schedule/download?program='+program;

	});
});


/*###########################
# Register Notice Serach
#############################*/ 
jQuery(function(){

	jQuery('.register_notice_search').click(function(){

		var notice_to_type = jQuery(".notice_to_type option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;


		if((notice_to_type.length !=0) && notice_to_type !=0){

			if(parameter==1)
				request_url += '&notice_to_type='+notice_to_type;
			else
				request_url += 'notice_to_type='+notice_to_type;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/notice-board?'+request_url;

		}else window.location.href = site_url+'/register/notice-board';

		
	});
});



/*###########################
# Registered Student Search
#############################
*/ 
jQuery(function(){

	jQuery('.total_registered_student_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();
		
		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/student-list?'+request_url;

		}else window.location.href = site_url+'/register/student-list';

		
	});
});


/*###########################
# trimester student assign search
#############################
*/ 

jQuery(function(){

	jQuery('.trimester_student_assign_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();

		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/trimester-student-assign?'+request_url;

		}else window.location.href = site_url+'/register/trimester-student-assign';

		
	});
});


/*##########################################
# Programlist By department
############################################
*/

jQuery(function(){
	jQuery('.department_list').change(function(){

		var department = jQuery(this).val();
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/register/program-list-ajax/'+department;

		if(department.length != 0){
			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					jQuery('.coordinator_program').html(data);
				}
			});

			request_url = site_url+'/register/faculty-list-ajax/'+department;
			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					jQuery('.coordinator_faculty_id').html(data);
				}
			});
		}
	});
});

/*###############################
# Program Coordinator Search
#################################
*/

jQuery(function(){

	jQuery('.program_coordinator_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((program.length !=0) && program !=0){

			parameter=1;
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}


		
		if(request_url.length !=0)
			window.location.href = site_url+'/register/class-teacher-assign?'+request_url;
		else	
			window.location.href = site_url+'/register/class-teacher-assign';
		

	});
});


/*###########################
# Faculty Course Assign Search
#############################
*/ 

jQuery(function(){

	jQuery('.faculty_course_assign_search').click(function(){

		var program = jQuery(".program option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var level = jQuery(".level option:selected").val();
		var term = jQuery(".term option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		// alert(current_page_url);
		var site_url = jQuery('.site_url').val();

		var request_url ='';

		if((program.length !=0) && program !=0){
			request_url += 'program='+program;
		}

		if((semester.length !=0) && semester !=0){
			request_url += '&semester='+semester;		
		}

		if((academic_year.length !=0) && academic_year !=0){
			request_url += '&academic_year='+academic_year;
		}

		if((level.length !=0) && level !=0){
			request_url += '&level='+level;
		}

		if((term.length !=0) && term !=0){
			request_url += '&term='+term;
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/register/faculty-assigned-course?'+request_url;

		}else window.location.href = site_url+'/register/faculty-assigned-course';

		
	});
});





/*--------------------End Register Module Script-------------------------------*/






/*-----------------------------Faculty Module Script------------------------------*/


	/*##########################################
	# program head result publish
	############################################
	*/

	jQuery(function(){
		jQuery('.program_head_result_publish').click(function(){

			var course_code = jQuery(this).data('course-code');
			var site_url = jQuery('.site_url').val();

			var request_url = site_url+'/faculty/ajax-program-head-result-publish/'+course_code;

			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					jQuery('.ajax_program_head_result_publish').html(data);
				}
			});

		});
	});


	/*###########################
# Faculty Advising Search
#############################
*/ 

jQuery(function(){

	jQuery('.faculty_advising_search').click(function(){

		var program = jQuery(".program option:selected").val();
		// var section = jQuery(".section option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';

		if((program.length !=0) && program !=0){
			request_url += 'program='+program;
		}

		// if((section.length !=0) && section !=0){
		// 	request_url += 'section='+section;
		// }

		if((semester.length !=0) && semester !=0){
			request_url += '&semester='+semester;		
		}

		if((academic_year.length !=0) && academic_year !=0){
			request_url += '&academic_year='+academic_year;
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/faculty/course-advising?'+request_url;

		}else window.location.href = site_url+'/faculty/course-advising';

		
	});
});


/*##########################################
# Faculty Pre Advising Confirm
############################################
*/
// jQuery(function () {
// 	$('#click').click(function()
// 	{   
// 		$("#panel").toggle();     
// 	});

// });

jQuery(function() {

	jQuery('.student_pre_advising_info').click(function(){

		var program_id = jQuery(this).data('id');
		var level = jQuery(this).data('level');
		var term = jQuery(this).data('term');
		var semester = jQuery(this).data('semester');
		var year = jQuery(this).data('year');
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/faculty/pre-advising-lists/'+program_id+'/'+level+'/'+term+'/'+semester+'/'+year;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){
				jQuery('#pre_advising_list').html(data);
			}
		});

	});
});


/*##########################################
# Faculty Pre Advising List
############################################
*/

jQuery(function() {

	jQuery('.advising .advising_result #pre_advising_list').on('click','.student_pre_advising_course_lists',function(){

		var temp_tran_code = jQuery(this).data('temp-tran-code');
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/faculty/pre-advising-modal/'+temp_tran_code;
		
		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){
				jQuery('#pre_advising_course_list').html(data);
			}
		});

	});

});


/*###########################
# Faculty Result Processing Search
#############################
*/ 

jQuery(function(){

	jQuery('.faculty_result_processing_search').click(function(){

		var program = jQuery(".program option:selected").val();
		// var section = jQuery(".section option:selected").val();
		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		// var current_page_url = jQuery('.current_page_url').val();
		// alert(current_page_url);
		var site_url = jQuery('.site_url').val();

		var request_url ='';

		if((program.length !=0) && program !=0){
			request_url += 'program='+program;
		}

		// if((section.length !=0) && section !=0){
		// 	request_url += 'section='+section;
		// }

		if((semester.length !=0) && semester !=0){
			request_url += '&semester='+semester;		
		}

		if((academic_year.length !=0) && academic_year !=0){
			request_url += '&academic_year='+academic_year;
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/faculty/result-processing?'+request_url;

		}else window.location.href = site_url+'/faculty/result-processing';

		
	});
});


/*##########################################
# Faculty Result Processing Search Result
############################################
*/

jQuery(function() {

	jQuery('.faculty_result_submit').click(function(){

		var course_code = jQuery(this).data('course');
		var program = jQuery(this).data('program');
		var semester = jQuery(this).data('semester');
		var year = jQuery(this).data('year');
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/faculty/result-processing-marks-submit/'+program+'/'+course_code+'/'+semester+'/'+year;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){

				jQuery('.faculty_result_entry_form').html(data);
			}
		});

	});
});


/*
###########################
# Faculty Notice Request Modal
############################
*/

jQuery(function(){
	jQuery('.notice_show').click(function(){

		var notice_tran_code = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/faculty/home/'+notice_tran_code;
		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){

				jQuery('.notice_setting_form').html(data);

			}
		});

	});
});


/*-----------------------------\Faculty Module Script------------------------------*/





/*-----------------------------Academic Settings Module Script------------------------------*/

/* ===============================
   Course List According In Program
   * ================================== */

   jQuery(function(){
   	jQuery('.catalouge_program').change(function(){

   		var program = jQuery(this).val();
   		var site_url = jQuery('.site_url').val();

   		request_url = site_url+'/academic/course-list/ajax/'+program;

   		if(program.length != 0){
   			jQuery.ajax({
   				url: request_url,
   				type: 'get',
   				success:function(data){
   					jQuery('#course_catalouge_list').html(data);
   				}
   			});
   		}
   	});

   });

/*
###########################
# Ediit Form Request Modal
############################
*/

jQuery(function(){
	jQuery('.course_settings_edit').click(function(){

		var  course_setting_type = jQuery(this).data('type');
		var type_slug = jQuery(this).data('slug');
		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/academic/course-settings/'+course_setting_type+'/'+type_slug;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){

				if(data != '404')
					jQuery('.course_setting_form').html(data);

				else location.reload();
			}
		});

	});
});


/*
########################
# Edit Academic Settings 
######################
*/
jQuery(function(){

	jQuery('.edit_academic_settings').click(function(){

		var slug = jQuery(this).data('id');
		var setting_type = jQuery(this).data('type');
		var site_url = jQuery('.site_url').val();

		if(slug.length !=0){

			var request_url = site_url+'/academic-settings/home-'+setting_type+'-'+slug;

			jQuery.ajax({
				url: request_url,
				type: "get",
				success:function(data){

					jQuery('.edit_view').html(data);
				}
			});

		}else alert("Please Add To Edit");

	});
});



	/* ===============================
   Degree Plan Catalogue
   * ================================== */

   jQuery(function(){
   	jQuery('.catalouge_list_program').change(function(){

   		var program = jQuery(this).val();
   		var site_url = jQuery('.site_url').val();

   		request_url = site_url+'/academic/catalouge-list/ajax/'+program;

   		if(program.length != 0){
   			jQuery.ajax({
   				url: request_url,
   				type: 'get',
   				success:function(data){
   					jQuery('#course_catalouge_list_program').html(data);
   				}
   			});
   		}
   	});

   });


   /* ===============================
   Degree Plan Detail modal
   * ================================== */

   jQuery(function(){
   	jQuery('.degree_plan_view').click(function(){

   		var degree_plan_tran = jQuery(this).data('degree-plan-tran');
   		var site_url = jQuery('.site_url').val();

   		request_url = site_url+'/academic-settings/view/degree-plan-detail/'+degree_plan_tran;
   		
   		jQuery.ajax({
   			url: request_url,
   			type: 'get',
   			success:function(data){
   				jQuery('.ajax_degree_plan_detail_modal').html(data);
   			}
   		});

   	});

   });


   /*----------------------------------End Academic Settings Module Script------------------------------------------------*/

/*
########################
# Notifiaction Alert
######################
*/

/*jQuery(function(){

	setInterval(function() {

		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/notification/alert';

        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('#notification').html(data);
          }
        });
    }, 50000);
});*/




jQuery(function(){


	jQuery('#btnRight').click(function(e) {
		var selectedOpts = jQuery('#lstBox1 option:selected');
		if (selectedOpts.length == 0) {
			alert("Nothing to move.");
			e.preventDefault();
		}

		jQuery('#lstBox2').append(jQuery(selectedOpts).clone());
		jQuery(selectedOpts).remove();
		e.preventDefault();
	});

	jQuery('#btnLeft').click(function(e) {
		var selectedOpts = $('#lstBox2 option:selected');
		if (selectedOpts.length == 0) {
			alert("Nothing to move.");
			e.preventDefault();
		}

		jQuery('#lstBox1').append(jQuery(selectedOpts).clone());
		jQuery(selectedOpts).remove();
		e.preventDefault();
	});


});



jQuery(function () {

	jQuery('.form_date').datetimepicker({

		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});

	jQuery('.to_date').datetimepicker({

		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});

});


/*
########################
# Print Current div
######################
*/

function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;

	document.body.innerHTML = printContents;

	window.print();

	document.body.innerHTML = originalContents;
}

/*function PrintContent(divName) {
      var DocumentContainer = document.getElementById(divName);
      var WindowObject = window.open('', 'PrintWindow', 'width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
       
     WindowObject.document.writeln(DocumentContainer.innerHTML);
   
   
      WindowObject.document.close();
      WindowObject.focus();
      WindowObject.print();
      WindowObject.close();
  }*/




/*##########################################
# Pre Advising Selectbox
############################################
*/

jQuery(function () {
	$(".check").change(function(){
		var credit = 0;
		$(".check:checked").each(function(){        
			credit += parseFloat($(this).attr('credit'));  
		});
		jQuery('.pre_advising_total_credit').val(credit);
	});
});





/*-------------------------Student Module---------------------------*/

/*###########################
# pre-advising resubmit
#############################
*/ 


jQuery(function() {

	jQuery('.preadvising_resubmit').click(function(){

		var temp_preadvising_tran_code= jQuery(this).data('temptrancode');
		var level= jQuery(this).data('level');
		var term= jQuery(this).data('term');

		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/student/re-advising/'+temp_preadvising_tran_code+'/'+level+'/'+term;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){
				jQuery('.resubmit_form').html(data);
			}
		});

	});
});


/*##########################
# Student Exam Schedule Pdf Download
############################
*/

jQuery(function(){

	jQuery('.student_exam_schedule_pdf_download').click(function(){

		var site_url = jQuery('.site_url').val();
		
		window.location.href = site_url+'/student/exam-routine-download';

	});
});


/*
###########################
# Student Notice Request Modal
############################
*/

jQuery(function(){
	jQuery('.student_notice_show').click(function(){

		var notice_tran_code = jQuery(this).data('id');
		var site_url = jQuery('.site_url').val();
		var request_url  = site_url+'/student/home/'+notice_tran_code;
		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){

				jQuery('.notice_student_form').html(data);

			}
		});

	});
});


/*###########################
# student payment history search
#############################
*/ 

jQuery(function(){
	jQuery('#student_payment_history_search').change(function(){

		var semester = jQuery(this).val();
		var site_url = jQuery('.site_url').val();
		request_url = site_url+'/student/payment-history/'+semester;
			// alert(request_url);

			if(semester.length != 0){
				jQuery.ajax({
					url: request_url,
					type: 'get',
					success:function(data){
						jQuery('#payment_history').html(data);
					}
				});
			}
		});
});



/*##########################################
# Faculty Result Processing Search Result
############################################
*/

jQuery(function() {

	jQuery('.student_academic_course_plan_detail').click(function(){

		var program = jQuery(this).data('program');
		var category = jQuery(this).data('category');

		if(category=='internship/thesis'){
			var category='internship.thesis';
		}
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/student/course-plan-details/'+program+'/'+category;

		jQuery.ajax({
			url: request_url,
			type: "get",
			success:function(data){
				jQuery('.student_academic_course_plan_detail_show').html(data);
			}
		});

	});
});


/*###########################
# student_result_search
#############################
*/ 

jQuery(function(){

	jQuery('.student_result_search').click(function(){

		var semester = jQuery(".semester option:selected" ).val();
		var academic_year = jQuery(".academic_year option:selected").val();
		// var religion = jQuery(".religion option:selected").val();
		// var gender = jQuery(".gender option:selected").val();
		var current_page_url = jQuery('.current_page_url').val();
		var site_url = jQuery('.site_url').val();

		var request_url ='';
		var parameter = 0;

		if((semester.length !=0) && semester !=0){
			if(parameter==1)
				request_url += '&semester='+semester;
			else{
				request_url += 'semester='+semester;
				parameter=1;
			}
			
		}

		if((academic_year.length !=0) && academic_year !=0){

			if(parameter==1)
				request_url += '&academic_year='+academic_year;
			else
				request_url += 'academic_year='+academic_year;
			
		}

		if(request_url.length !=0){

			window.location.href = site_url+'/student/grade-sheet?'+request_url;

		}else window.location.href = site_url+'/student/grade-sheet';

		
	});
});


/*-------------------------End Student Module---------------------------*/




/*###########################
# Faculty Course Assign Select 2
#############################
*/ 
jQuery(function(){
	jQuery(".multipleSelectExample").select2();
});



/*###########################
# loading Button
#############################
*/ 
$('.loadingButton').on('click', function () {
	var $btn = $(this).button('loading');
});



/*###########################
# checkAll
#############################
*/ 

jQuery(".checkAll").change(function () {
	jQuery("input:checkbox").prop('checked', jQuery(this).prop("checked"));
});



/*###########################
# tooltip
#############################
*/ 
jQuery(document).ready(function(){
	jQuery('[data-toggle="tooltip"]').tooltip();   
});
jQuery(document).ready(function(){
	jQuery('[data-toggle1="tooltip"]').tooltip();   
});




/*###########################
# Confirm Box
#############################
*/ 
jQuery(function(){

	jQuery('.confirm_box').click(function(){

		var confirm_url=jQuery(this).data('confirm-url');
		if (confirm("Do You Want To Delete ?") == true) {
			window.location.href=confirm_url;
		}
	});

});



/*
###########################
# Ajax Program List
############################
*/

jQuery(function(){
	jQuery('.department_code').change(function(){

		var department_no = jQuery(this).val();
		var site_url = jQuery('.site_url').val();

		var request_url  = site_url+'/register/program-list-ajax/'+department_no;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.ajax_program_list').html(data);
			}
		});

	});
});



/* ===============================
   Fee Type view Ajax
   * ============================= */

   jQuery(function(){

   	jQuery('.fee_type').change(function(){

		var fee_name = jQuery(this).val();

   		var site_url = jQuery('.site_url').val();
   		if(fee_name.length !=0){

   			var request_url = site_url+'/register/student/fee-'+fee_name;

   			jQuery.ajax({
   				url: request_url,
   				type: "get",
   				success:function(data){

   					jQuery('.fee_type_details').html(data);
   				}
   			});

   		}else alert("Please Select Fee Type");

   	});


   });



	/*##########################################
	# Time Slot List By ajax
	############################################
	*/

	jQuery(function(){
		jQuery('.attendance_program').change(function(){

			var program = jQuery(this).val();
			var site_url = jQuery('.site_url').val();
			var request_url = site_url+'/register/ajax-course/list/'+program;

			jQuery.ajax({
				url: request_url,
				type: 'get',
				success:function(data){
					jQuery('.attendance_course').html(data);
				}
			});

		});
	});







/*##########################################
# Batch By ajax
############################################
*/

jQuery(function(){
	jQuery('.select_program').change(function(){

		var program = jQuery(this).val();
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/register/existing/student/batch/program-'+program;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.get_batch').html(data);
			}
		});

	});
});


/*##########################################
# Student By Batch in ajax
############################################
*/

jQuery(function(){
	jQuery('.select_batch').change(function(){

		var batch = jQuery(this).val();
		var program = jQuery('.select_program').find(":selected").val();
		var site_url = jQuery('.site_url').val();

		var request_url = site_url+'/register/existing/batch-student/program-'+program+'/batch-'+batch;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.get_student').html(data);
			}
		});

	});
});





	/*##############################
	# ExamMarksStore
	############################# */ 

	jQuery(function(){
		jQuery(".ExamMarksStore").click(function(){
			var site_url = jQuery(".site_url").val();
			var course_code = jQuery(this).data('course');
			var student_serial_no = jQuery('#student_serial_no_'+course_code).val();
			var course_type = jQuery('#course_type_'+course_code).val();

			if(course_type=='Theory'){
				var course_year = jQuery("#course_year_"+course_code).val();
				var course_semester = jQuery("#course_semester_"+course_code).val();
				var ct_1 = jQuery("#ct_1_"+course_code).val();
				var ct_2 = jQuery("#ct_2_"+course_code).val();
				var ct_3= jQuery("#ct_3_"+course_code).val();
				var ct_4 = jQuery("#ct_4_"+course_code).val();
				var mid_term = jQuery("#mid_term_"+course_code).val();
				var class_attendance = jQuery("#class_attendance_"+course_code).val();
				var class_participation = jQuery("#class_participation_"+course_code).val();
				var class_presentaion = jQuery("#class_presentaion_"+course_code).val();
				var class_final_exam = jQuery("#class_final_exam_"+course_code).val();

				var request_url=site_url+'/register/existing/student/marks/submit/'+student_serial_no+'/'+course_code+'/'+course_type+'/'+course_year+'/'+course_semester+'/'+ct_1+'/'+ct_2+'/'+ct_3+'/'+ct_4+'/'+mid_term+'/'+class_attendance+'/'+class_participation+'/'+class_presentaion+'/'+class_final_exam;
				
				jQuery.ajax({
					url: request_url,
					type: "get",
					success:function(data){

						location.reload();
					}
				});

			}
			else{
				var course_year = jQuery("#course_year_"+course_code).val();
				var course_semester = jQuery("#course_semester_"+course_code).val();
				var lab_attendance = jQuery("#lab_attendance_"+course_code).val();
				var lab_performance = jQuery("#lab_performance_"+course_code).val();
				var lab_reprot = jQuery("#lab_reprot_"+course_code).val();
				var lab_verbal = jQuery("#lab_verbal_"+course_code).val();
				var lab_final = jQuery("#lab_final_"+course_code).val();

				var request_url=site_url+'/register/existing/student/lab/marks/submit/'+student_serial_no+'/'+course_code+'/'+course_type+'/'+course_year+'/'+course_semester+'/'+lab_attendance+'/'+lab_performance+'/'+lab_reprot+'/'+lab_verbal+'/'+lab_final;
					jQuery.ajax({
						url: request_url,
						type: "get",
						success:function(data){

							location.reload();
						}
					});

			}

		});
	});



	/*##########################################
	# 
	############################################
	*/

	jQuery(function(){
		jQuery('.AddBlockDialog').click(function(){

			var student_serial_no = jQuery(this).data('student');
			var action = jQuery(this).data('action');
			var site_url = jQuery('.site_url').val();
    	 	document.getElementById("BlockId").value = student_serial_no;
    	 	document.getElementById("BlockAction").value = action;

		});
	});
	
	
/*##########################################
# Accounts Batch By ajax
############################################
*/

jQuery(function(){
	jQuery('.select_accounts_program').change(function(){

		var program = jQuery(this).val();
		var site_url = jQuery('.site_url').val();
		var request_url = site_url+'/accounts/student/batch/program-'+program;

		jQuery.ajax({
			url: request_url,
			type: 'get',
			success:function(data){
				jQuery('.get_accounts_batch').html(data);
			}
		});

	});
});

