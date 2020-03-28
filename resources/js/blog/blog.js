import "lightbox2";
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interaction from "@fullcalendar/interaction";
import plLocale from "@fullcalendar/core/locales/pl";

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

    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin,interaction ],
        locale: "pl",
        locales: [plLocale],
        events: JSON.parse(events),
        firstDay: 1,
        defaultView: 'dayGridMonth',
        
        eventClick: function(info) {
            
            info.jsEvent.preventDefault(); // don't let the browser navigate

            if (info.event.url) {
                window.open(info.event.url,"_blank");
            }
        }
    });

    calendar.render();
        

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

    $('#tagName').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this,'#searchTags');
        }
    });

    $('.addTagButton').on('click',function() {
        addNewTagInputFromIn($('#tagName'),'#searchTags');
    });

    $('#showSearchMenu').on('click', function () {
        if ($('.blogExtraPanes').hasClass('show')) {
            $('.blogExtraPanes').removeClass('show');
            $(this).html('<i class="fas fa-search"></i>');
            setTimeout(function () {
                $('.darkOverlay').addClass('d-none');
            }, 900);
        } else {
            $('.blogExtraPanes').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');

            $('.darkOverlay').one('click', function () {
                $('.blogExtraPanes').removeClass('show');
                $('#showSearchMenu').html('<i class="fas fa-search"></i>');
                setTimeout(function () {
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });

}