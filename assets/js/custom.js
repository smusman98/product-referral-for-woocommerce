// jQuery(document).ready( function() {
//     jQuery("#click-me").click( function(e) {
//         e.preventDefault();
//         jQuery.ajax({
//             type : "post",
//             dataType : "json",
//             url : ajaxurl,
//             data : {action: "custom_ajax"},
//             success: function(response) {
//                 if(response.type == "success") {
//                     alert(response);
//                 }
//                 else {
//                     alert(response);
//                 }
//             }
//         })
//
//     })
//
// })

// jQuery(document).ready(function () {
//     //Add Form
//     jQuery("#prfwc-add-btn").click(function(){
//         jQuery(".form-table:last").after(jQuery(".form-table:first").clone(true));
//     });
//
//     //Delete Form
//     jQuery("#prfwc-remove-btn").click(function() {
//         if(jQuery(".form-table").length != 1)
//             jQuery(".form-table:last").remove();
//     });
// })
