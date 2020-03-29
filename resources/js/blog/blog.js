import "lightbox2";
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interaction from "@fullcalendar/interaction";
import plLocale from "@fullcalendar/core/locales/pl";

var pagi = 1;

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

    $('.likeBtn').on('click',likePost);


    $('.blogFeed-posts:first').on('scroll',function() {
        pagiPosts(this);
    });

    
    addOnClickDeleteEventOnRemove('.postTag');

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

    $('input[type=radio],select').change(function() {
        $('#sortForm').submit();
    });
        

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

function likePost() {
    let url = baseUrl+'/blog/likePost';
    let button = $(this);
    let likesAmountElement = $(button).children('.likesAmount');
    let id = $(this).data('id');
    $('.tooltip:first').remove();

    if ($(button).hasClass('active')) {
        $(button).removeClass('active');
        let likeAmount = parseInt($(likesAmountElement).html()) - 1;
        $(likesAmountElement).html(likeAmount);

        if(likeAmount == 0){
            $(likesAmountElement).addClass('d-none');
        }
    }else{
        $(button).addClass('active');
        let likeAmount = parseInt($(likesAmountElement).html()) + 1;
        $(likesAmountElement).html(likeAmount);

        if($(likesAmountElement).hasClass('d-none')){
            $(likesAmountElement).removeClass('d-none');
        }
    }

    var request = $.ajax({
        method: "post",
        url: url,
        data: {"_method": "patch",id:id}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJson.message);
    });
}

function pagiPosts(el) {
    if($(el).scrollTop() + $(el).innerHeight() >= $(el)[0].scrollHeight - 100) {
        $(el).off('scroll');
        pagi++;
        let url = $(location).attr('href');

        if (url.indexOf('?') == -1) {
            url = url+"?pagi="+pagi;
         }else{
            url = url+"&pagi="+pagi;
         }

        var request = $.ajax({
            method : 'get',
            url: url,
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('.blogFeed-posts:first').append(response.html);

                $('[data-tool="tooltip"]').tooltip();

                
                $('.deletePost').off('submit');
                $('.deletePost').on('submit',function(e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        showSpinnerOverlay();
                        sendAjaxRequestToWithFormData(baseUrl+"/blog/deletePost",this);
                        $(this).parents('.blogFeed-post').remove();
                        $(".tooltip:first").remove();
                    }
                });

                $('.likeBtn').off('click');
                $('.likeBtn').on('click',likePost);

                if (response.stop) {
                    $('.blogFeed-posts:first').on('scroll',function() {
                        pagiPosts(this);
                    });
                }

            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJson.message);
        });
    }
}