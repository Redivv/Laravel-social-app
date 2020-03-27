import "lightbox2";

import {
    addNewTagInputFromIn,
    addOnClickDeleteEventOnRemove,
    sendAjaxRequestToWithFormData,
    getDataByAjaxFromUrlWithData,
    showSpinnerOverlay
} from "./blogFunctions";

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    main();
});

function main() {
    $('[data-tool="tooltip"]').tooltip();

    
    $('.deletePost').on('submit',function(e) {
        e.preventDefault();
        if (confirm(confirmMsg)) {
            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/blog/deletePost",this);
            $(this).parents('.blogFeed-post').remove();
            $(".tooltip:first").remove();
        }
    });

}