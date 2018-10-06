/*
*Image Uploader Script
*/



function showRequest(formData, jqForm, options) { 
    jQuery("#validation-errors").hide().empty();

 
   
    return true; 
} 
function showResponse(response, statusText, xhr, $form)  {



    if(response.success == "invalid_format"){


        jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>File Type or Size is not valid!!</strong></div>');
        jQuery("#validation-errors").show();
        jQuery(".image_loader").removeClass('loading_icon'); 

    }else if(response.success == "filesize"){


        jQuery("#validation-errors").append('<div class="alert alert-danger"><strong>File Height or Width is not valid!!</strong></div>');
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
    
    

   
}
 
jQuery(document).ready(function() {

    
    var options = { 
        beforeSubmit:  showRequest,
        success:       showResponse,
        dataType: 'json' 
        }; 
     jQuery('body').delegate('#image','change', function(){

        jQuery(".image_loader").addClass('loading_icon');   ////loader remove

         jQuery('#upload').ajaxForm(options).submit();   
    
     }); 
});