   /* ===============================
   Date Time Picker for date of birth
   * ============================= */

   // $(function () {
   // 	$('#datetimepicker9').datetimepicker({
   // 		format: "dd/mm/yyyy"
   // 	});
   // });


   /* ===============================
   Radio Button Onclick Change for Address
   * ============================= */

   function permanent_address_city()	{
   	$('#permanent-address-village-yes').slideUp("fast");
   	$('#permanent-address-city-yes').slideDown("fast");
   }

   function permanent_address_village()	{
   	$('#permanent-address-village-yes').slideDown("fast");
   	$('#permanent-address-city-yes').slideUp("fast");
   }

   function present_address_city()	{
   	$('#present-address-village-yes').slideUp("fast");
   	$('#present-address-city-yes').slideDown("fast");
   }
   function present_address_village()	{
   	$('#present-address-village-yes').slideDown("fast");
   	$('#present-address-city-yes').slideUp("fast");
   }


/* ===============================
   Date of Birth Date
* ============================= */
//    jQuery(function () {

//        jQuery('.date_of_birth').datetimepicker({
           
//            weekStart: 1,
//            todayBtn:  1,
//            autoclose: 1,
//            todayHighlight: 1,
//            startView: 2,
//            minView: 2,
//            forceParse: 0
//       });
// });


/* ===============================
   Applicant Serach Info
* ============================= */

jQuery(function(){

  jQuery('.applicant_search_btn').click(function(){

      var applicant_serial_no = jQuery('#applicant_serial_no').val();
      var site_url = jQuery('.site_url').val();

      if(applicant_serial_no.length !=0){

       var request_url = site_url+'/online-application/applicant/info/'+applicant_serial_no;

        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('.applicant_search_result').html(data);
          }
        });

      }else alert("Please Add serial no");

  });


});

/* ===============================
   Applicant SSC validation
* ============================= */
  jQuery(function(){
      jQuery(".ssc_roll").change(function(){


          var exam_roll_number = jQuery(this).val();
          var program = jQuery('.program').val();

          if(exam_roll_number.length !=0 && program.length !=0 && jQuery.isNumeric(exam_roll_number)){

            var site_url = jQuery('.site_url').val();

            var request_url = site_url+'/online-application/entry/validation/SSC-'+exam_roll_number+'-'+program;

              jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                  if(data==0){
                      jQuery('.ssc_check span').html('<i class="suceess fa fa-check"></i>');
                     jQuery('.ssc_roll_valid').val(1);
                  }else{
                    jQuery('.ssc_check span').html('<i class="failed fa fa-times"></i> (You have already applied !)');
                     jQuery('.ssc_roll_valid').val(0);
                  }

                     
                }
              });

          }else{
            jQuery('.ssc_check span').html('');
            jQuery('.ssc_roll_valid').val(0);
          }
         
      });
  });


/* ===============================
   Applicant Admit Card 
* ============================= */

jQuery(function(){

  jQuery('.applicant_resultsearch_btn').click(function(){

      var applicant_serial_no = jQuery('#applicant_serial_no').val();
      var site_url = jQuery('.site_url').val();

      if(applicant_serial_no.length !=0){

       var request_url = site_url+'/online-application/applicant/admission-result/search/'+applicant_serial_no;

        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('.applicant_search_result').html(data);
          }
        });

      }else alert("Please Add serial no");

  });

});

/* ===============================
   MBA Admission Subject Priority
* ============================= */

jQuery(function(){
  jQuery('.program').on('change',function(){
      
      var program = jQuery(this).val();
      var list = jQuery('.mslist').val();
      var mslist = list.split(',');

      var match = mslist.indexOf(program);

      if(program==97 || program==98 || program==99)
        jQuery('#mba_form').show();

      else jQuery('#mba_form').hide();
       if(program==97){
        jQuery('#pro_experience').show();
      }else jQuery('#pro_experience').hide();
      
      // jQuery('#program_id').val(program);

  })


});


/* ===============================
   EMBA Profesional Experience
* ============================= */

jQuery(function() {

    jQuery('.add-field').click(function(){

      var multi_count = jQuery('.multi_count').val();
      var site_url = jQuery('.site_url').val();

      multi_count = parseInt(multi_count)+1;

     var request_url = site_url+'/online-application/experience/'+multi_count;
        jQuery.ajax({
           url: request_url,
           type: "get",
           success:function(data){
             jQuery('.multi-fields').append(data);
             jQuery('.multi_count').val(multi_count);
           }
        });


    });

    
});

jQuery('.multi-field-wrapper').on('click', '.remove-field', function() {

      var multi_count = jQuery('.multi_count').val();
      if(multi_count !=1)
        jQuery('.pro_div_'+multi_count).remove();

       multi_count = parseInt(multi_count)-1;
      jQuery('.multi_count').val(multi_count);
});


/* ===============================
   Date Picker
* ============================= */

  // $(document).ready(function(){
  //   var date_input=$('.date'); //our date input has the name "date"
  //   var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  //   date_input.datepicker({
  //     format: 'yyyy/mm/dd',
  //     container: container,
  //     todayHighlight: true,
  //     autoclose: true,
  //   });
  // });




  // jQuery(function() {
  //   $(".todayBox").click(function() {

  //     var dateStr;
  //     if (this.checked) {
  //       var now = new Date();
  //       dateStr = now.getFullYear() + "/" + (now.getMonth() + 1) + "/" + now.getDate();
  //       $(".enterDate").hide();
  //       $(".till_now").show();

  //     } else {
  //       dateStr = "";
  //       $(".enterDate").show();
  //       $(".till_now").hide();
  //     }
  //     // $(".enterDate").val(dateStr);
      


  //   });
  // });
/* ===============================
   Date Picker
* ============================= */

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


jQuery(function () {


   jQuery('.multi-field-wrapper').on('click','.form_date_group',function(){
    var date_class = '.form_date_'+jQuery(this).data('count');
        jQuery(date_class).datetimepicker({
            allowInputToggle: true,
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
       });

  });
});
jQuery(function() {
   jQuery("input:checkbox").click(function() {
    
      var date_field = '.'+$(this).data('till');
      if(jQuery(this).is(':checked')){
        // jQuery('.date_field_show').show();
        jQuery(date_field).hide();
        // var date_field_show = 'disabled';
        
      }
      else 
        // jQuery('.date_field_show').hide();
        jQuery(date_field).show();
       
      
    });
    });



   /* ===============================
   add_ssc_subject
   * ============================= */

   jQuery(function() {

    jQuery('.add_ssc_subject').click(function(){
      var multi_ssc_subject_count = jQuery('.multi_ssc_subject_count').val();
      var site_url = jQuery('.site_url').val();
      multi_ssc_subject_count = parseInt(multi_ssc_subject_count)+1;
      var request_url = site_url+'/online-application/form/'+multi_ssc_subject_count+'/ssc';
      jQuery.ajax({
       url: request_url,
       type: "get",
       success:function(data){
         jQuery('.multi_ssc_subject').append(data);
         jQuery('.multi_ssc_subject_count').val(multi_ssc_subject_count);

        // jQuery("#remCF").on('click',function(){
        //     jQuery(this).parent().parent().remove();
        // });

       }
     });
    });


  });


   /* ===============================
   add_ssc_subject delete
   * ============================= */

  jQuery(function(){

    jQuery('.multi_ssc_subject_row').on("click",".delete_ssc_sub", function(){


        var row = jQuery(this).data('subgrd');

        jQuery('.ssc_add_subject_wrapper_'+row).remove();
        var multi_ssc_subject_count = jQuery('.multi_ssc_subject_count').val();
         multi_ssc_subject_count--;
        jQuery('.multi_ssc_subject_count').val(multi_ssc_subject_count);

    });
  });







   /* ===============================
    add_hsc_subject
   * ============================= */

   jQuery(function() {

    jQuery('.add_hsc_subject').click(function(){
      var multi_hsc_subject_count = jQuery('.multi_hsc_subject_count').val();
      var site_url = jQuery('.site_url').val();
      multi_hsc_subject_count = parseInt(multi_hsc_subject_count)+1;
      var request_url = site_url+'/online-application/form/'+multi_hsc_subject_count+'/hsc';
      jQuery.ajax({
       url: request_url,
       type: "get",
       success:function(data){
         jQuery('.multi_hsc_subject').append(data);
         jQuery('.multi_hsc_subject_count').val(multi_hsc_subject_count);
       }
     });
    });

  });



   /* ===============================
   add_hsc_subject delete
   * ============================= */

  jQuery(function(){

    jQuery('.multi_hsc_subject_row').on("click",".delete_hsc_sub", function(){


        var row = jQuery(this).data('hscsubgrd');

        jQuery('.hsc_add_subject_wrapper_'+row).remove();
        var multi_hsc_subject_count = jQuery('.multi_hsc_subject_count').val();
         multi_hsc_subject_count--;
        jQuery('.multi_hsc_subject_count').val(multi_hsc_subject_count);

    });
  });



/* ===============================
    Till Now Checkbox
  ============ */
   jQuery(function() {
    jQuery('.multi-field-wrapper').on('click','input:checkbox',function(){
    
      var date_field = '.'+$(this).data('till');
      var row = $(this).data('till');
      if(jQuery(this).is(':checked')){
    
        jQuery('#period_to_'+row).val('0000-00-00');
        jQuery(date_field).hide();
        
      }
      else 
        jQuery(date_field).show();
       
    });
 });




/*##########################################
# SSC Grade Equvalent
############################################
*/

jQuery(function(){
    jQuery('.multi_field_ssc_grade_wrapper').on('change','.select_point',function(){

        var grade_point = jQuery(this).val();
        var subgp=jQuery(this).data('subgp');
        var site_url = jQuery('.site_url').val();


        if (grade_point == 5){

            var grade= 'A+';

        }
        if (grade_point == 4){

            var grade= 'A';

        }

        if(grade_point == 3.50){

            var grade= 'A-';
            
        }

        if(grade_point == 3){

            var grade= 'B';

        }

        if(grade_point == 2){

            var grade= 'C';

        }

        if(grade_point == 1){

            var grade= 'D';

        }
        if(grade_point == 0){

            var grade= 'F';
            
        }
        if(grade_point <0){
          var grade= 'F';
        }

        jQuery('#ssc_olevel_subject_grade_'+subgp).val(grade);

    });
});


/*##########################################
# HSC Grade Equvalent
############################################
*/

jQuery(function(){
    jQuery('.multi_field_hsc_grade_wrapper').on('change','.select_hsc_point',function(){

        var grade_point = jQuery(this).val();
        var hscsubgp=jQuery(this).data('hscsubgp');
        var site_url = jQuery('.site_url').val();

        if (grade_point == 5){

            var grade= 'A+';

        }
        if (grade_point == 4){

            var grade= 'A';

        }

        if(grade_point == 3.50){

            var grade= 'A-';
            
        }

        if(grade_point == 3){

            var grade= 'B';

        }

        if(grade_point == 2){

            var grade= 'C';

        }

        if(grade_point == 1){

            var grade= 'D';

        }
        if(grade_point == 0){

            var grade= 'F';
            
        }
        if(grade_point <0){
          var grade= 'F';
        }

        jQuery('#hsc_alevel_subject_grade_'+hscsubgp).val(grade);

    });
});


/*###########################
# loading Button
#############################
*/ 
$('.loadingButton').on('click', function () {
  var $btn = $(this).button('loading');
});






