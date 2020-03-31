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

    $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
    
    main();
});

function main() {

    $('#editPostDesc').emojioneArea({
        pickerPosition: "top",
        placeholder: "\xa0",
        autocomplete: false,
    });

    $('#commentEditModal').on('show.bs.modal', function (event) {
        let button      = $(event.relatedTarget);
        let commentId   = button.data('id');
        let modal       = $(this);
        let comment     = $('#com-'+commentId);

        let content     = comment.find('.commentDesc').html().trim();
        let taggedUsers = comment.find('.commentTags').html().trim();

        modal.find('.emojionearea-editor').html(content);
        modal.find('#editPostDesc').val(content);
        modal.find('#commentModalUserTagged').html(taggedUsers);
        modal.find('.tagUserButton').data('id',commentId);
        modal.find('.tagUserButton').data('modal','true');

        $('#editComment').off('submit');

        $('#editComment').on('submit',function(e) {
            e.preventDefault();

            let tag = $(this);
            let newComment = $(this).serializeArray();

            if (newComment[0].value.trim() != "") {
                let url = baseUrl+"/user/ajax/editComment";

                $(document).one("ajaxSend", function(){   
                    $('#commentEditModal').modal('hide');
                    $('.spinnerOverlay').removeClass('d-none');         
                    tag[0].reset();
                    $('.emojionearea-editor').empty();
                });

                var request = $.ajax({
                    method : 'post',
                    url: url,
                    data: {"_method": 'PATCH',data:newComment, commentId:commentId, commentType: "blog"}
                });
                
                
                request.done(function(response){
                    if (response.status === 'success') {
                        comment.replaceWith(response.html);
                        $('.spinnerOverlay').addClass('d-none');

                        $('.commentDelete').off('click');
                        $('.commentDelete').on('click',function(e) {
                            deleteComment(this);
                        });
    
                        $('.likeCommentButton').off('click');
                        $('.likeCommentButton').on('click',function() {
                            likeComment(this);
                        });

                        $('.replyButton').off('click');
                        $('.replyButton').on('click',function() {
                            addReplyForm(this);
                        });
                        
                        $('[data-tool="tooltip"]').tooltip();
                    }
                });
                
                
                request.fail(function (xhr){
                    $.each(xhr.responseJSON.errors,function(key,value) {
                        alert(value);
                    });
                    $('.spinnerOverlay').addClass('d-none');
                });
            }else{
                alert(emptyCommentMsg);
            }
        });

    });

    $('#tagUsersModal').on('show.bs.modal',function(event) {
        let button = $(event.relatedTarget);
        let itemId;
        if (itemId = $(button).data('id')) {
            let html = '<div id="tagSpinner" class="col-3">'+
                '<div class="spinner-border" role="status">'+
                    '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';

            $('#taggedUsers').html(html);
            
            let url = baseUrl+'/user/ajax/getBlogCommentTaggedUsers/'+itemId;

            var request = $.ajax({
                method : 'get',
                url: url,
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('#taggedUsers').html(response.html);

                    $('.taggedUser').off('click');
                    $('.taggedUser').on('click',function() {
                       if (confirm(deleteUserTag)) {
                           $(this).remove();
                       } 
                    });
                }
            });
            
            
            request.fail(function (xhr){
                $.each(xhr.responseJSON.errors,function(key,value) {
                    alert(value);
                });
            });

        }

        $("#tagUserName").autocomplete({
 
            source: function(request, response) {
                $.ajax({
                    url: baseUrl+"/ajax/tag/autocompleteUser",
                    data: {
                        term : request.term
                    },
                    dataType: "json",
                    success: function(data){
                        var resp = $.map(data,function(obj){
                        return obj.name;
                    }); 
                    response(resp);
                    }
                });
            },
            minLength: 1,
            appendTo: '#tagUsers'
        });

        $('#tagUserName').on('keydown',function(e) {
            if (e.keyCode == 13 || e.which == 13) {
                e.preventDefault();
                addTagUser(this);
            }
        });

        $('#tagUsers').on('submit',function(e) {
            e.preventDefault();
            if (itemId) {
                tagUsersPostModal(button);
            }else if($(button).hasClass('commentUserTag')){
                tagUsersComment(button);
            }else{
                tagUsers();
            }
        });
    });

    $('#tagUsersModal').on('hide.bs.modal',function() {

        $('#taggedUsers').empty();
        $('#tagUserName').off('keydown');
        $('#tagUsers').off('submit');
        $('.taggedUser').off('click');

    });

    $('.repliesMoreBtn').on('click',function() {
        loadReplies(this);
    });
    
    $('.commentsMoreBtn').on('click',function() {
        loadMoreComments(this);
    });

    
    $('.commentsForm').on('submit',function(e){
        addComment(e,this);
    });
    
    $('.commentDelete').on('click',function(e) {
        deleteComment(this);
    });
    
    $('.likeCommentButton').on('click',function() {
        likeComment(this);
    });
    
    $('.replyButton').on('click',function() {
        addReplyForm(this);
    });

    $('.likeBtn').on('click',likePost);


    $('.blogFeed-posts:first').on('scroll',function() {
        pagiPosts(this);
    });

    
    addOnClickDeleteEventOnRemove('.postTag');

    if (typeof events !== 'undefined') {

        var calendarEl = document.getElementById('calendar');
    
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin,interaction ],
            header:{
                left:   'title',
                center: '',
                right:  'prev,next'
            },
            aspectRatio: 1,
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
    }

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

function addComment(event, selected) {

    event.preventDefault();
        let tag = $(selected);
        let itemId = tag.data('id');

        $(document).one("ajaxSend", function(){
            tag[0].reset();
            tag.find('.emojionearea-editor').empty();
            
            let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';
    
            $('#commentsFeed').prepend(html);
        });

        let data = tag.serializeArray();
        let url = baseUrl + "/user/ajax/newComment";

        if (data[0].value.trim() != "") {

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": "PUT", data:data, itemId:itemId, commentType: "blog"}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
                    $('#commentsFeed').prepend(response.html);
                    
                    $('[data-tool="tooltip"]').tooltip();

                    $('.commentDelete').off('click');
                    $('.commentDelete').on('click',function(e) {
                        deleteComment(this);
                    });

                    $('.likeCommentButton').off('click');
                    $('.likeCommentButton').on('click',function() {
                        likeComment(this);
                    });
                    
                    $('.replyButton').off('click');
                    $('.replyButton').on('click',function() {
                        addReplyForm(this);
                    });
                }
            });
            
            
            request.fail(function (xhr){
                $('.ajaxSpinner').remove();
                $.each(xhr.responseJSON.errors,function(key,value) {
                    alert(value);
                });
            });
        }else{
            alert(emptyCommentMsg);
        }
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

function deleteComment(selected) {
    if (confirm(deleteCommentMsg)) {
        let url = baseUrl + "/user/ajax/deleteComment";
        let commentId = $(selected).data('id');
        $('.spinnerOverlay').removeClass('d-none');

        var request = $.ajax({
            method : 'post',
            url: url,
            data: {'_method': 'DELETE',id:commentId, commentType:"blog"}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#com-'+commentId).siblings('.commentRepliesBox').remove();

                $('#com-'+commentId).remove();
                $('.spinnerOverlay').addClass('d-none');
            }
        });
        
        
        request.fail(function (xhr){
            $.each(xhr.responseJSON.errors,function(key,value) {
                alert(value);
            });
        });
    }
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

function loadMoreComments(selected) {

    let button = $(selected);
    let itemId = button.data('id');
    let pagi = $(button).data('pagi');

    let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
        '</div>'+
    '</div>';

    $(document).one("ajaxSend", function(){   
        button.parents('.commentsFeed').append(html);
    });

    let url = baseUrl + "/user/ajax/getBlogComments/"+itemId;

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {pagi:pagi}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            if (pagi == 0) {
                button.prev().remove();
            }

            button.parents('.commentsFeed').append(response.html);
            $('.ajaxSpinner').remove();
            button.remove();
                    
            $('[data-tool="tooltip"]').tooltip();
            
            $('.commentDelete').off('click');
            $('.commentDelete').on('click',function(e) {
                deleteComment(this);
            });

            $('.replyButton').off('click');
            $('.replyButton').on('click',function() {
                addReplyForm(this);
            });

            $('.likeCommentButton').off('click');
            $('.likeCommentButton').on('click',function() {
                likeComment(this);
            });

            $('.repliesMoreBtn').off('click');
            $('.repliesMoreBtn').on('click',function() {
                loadReplies(this);
            });

            $('.commentsMoreBtn').off('click');
            $('.commentsMoreBtn').on('click',function() {
                loadMoreComments(this);
            });
        }
    });
    
    
    request.fail(function (xhr){
        $.each(xhr.responseJSON.errors,function(key,value) {
            alert(value);
        });
    });
}

function loadReplies(selected) {

    let button = $(selected);
    let parentId = button.data('id');
    let pagi = $(button).data('pagi');

    let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
        '</div>'+
    '</div>';

    $(document).one("ajaxSend", function(){   
        button.parents('.commentRepliesBox').append(html);
    });

    let url = baseUrl + "/user/ajax/getBlogReplies/"+parentId;

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {pagi:pagi}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            if (pagi == 0) {
                button.prev().remove();
            }
            button.parents('.commentRepliesBox').append(response.html);
            $('.ajaxSpinner').remove();
            button.remove();
                    
            $('[data-tool="tooltip"]').tooltip();

            $('.commentDelete').off('click');
            $('.commentDelete').on('click',function(e) {
                deleteComment(this);
            });

            $('.likeCommentButton').off('click');
            $('.likeCommentButton').on('click',function() {
                likeComment(this);
            });

            $('.repliesMoreBtn').off('click');
            $('.repliesMoreBtn').on('click',function() {
                loadReplies(this);
            });
        }
    });
    
    
    request.fail(function (xhr){
        $.each(xhr.responseJSON.errors,function(key,value) {
            alert(value);
        });
    });
}

function likeComment(selected) {
    let commentId = $(selected).data('id');
    let url = baseUrl + "/user/ajax/likeComment";

    let likesCount = $(selected).siblings('.likesCount').html().trim();

    if (likesCount == "") {
        likesCount = 0;
    }

    likesCount = parseInt(likesCount);

    if ($(selected).hasClass('active')) {
        $(selected).removeClass('active');
        if (likesCount - 1 == 0) {
            $(selected).siblings('.likesCount').html('');
        }else{
            $(selected).siblings('.likesCount').html(likesCount-1);
        }
    }else{
        $(selected).addClass('active');
        $(selected).siblings('.likesCount').html(likesCount+1);
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {'_method':'PATCH', commentId:commentId, commentType: "blog"}
    });
}

function addReplyForm(selected) {
    $('#replyForm').remove();
    let parentId = $(selected).data('id');
    let formHtml = '<div class="replyForm">'+
    '<form id="replyForm" method="post">'+
        '<div class="input-group row">'+
            '<input type="text" name="commentDesc" id="replyInput" class="form-control replyDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">'+
            '<div class="input-group-append col-1 commentButtons">'+
                '<i class="fas fa-user-tag commentUserTag" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" data-placement="bottom" title="'+tagUserMessage+'"></i>'+
            '</div>'+
        '</div>'+
        '<output id="replyUsersTag"></output>'
        '</form>'+
    '</div>';

    $(formHtml).insertAfter('#com-'+parentId);
                    
    $('[data-tool="tooltip"]').tooltip();

    $('#replyInput').emojioneArea({
        pickerPosition: "top",
        placeholder: "Napisz Komentarz",
        inline: false,
        events: {
            keypress: function(editor,e) {
                if (e.keyCode == 13 || e.which == 13) {
                    e.preventDefault();
                    editor.parent().prev().val(this.getText());
                    editor.parent().prev().parent().submit(); 
                }
            }
          }
    });

    $('#replyForm').find('.emojionearea-editor').focus();

    $('#replyForm').off('submit');

    $('#replyForm').on('submit',function(e) {
        e.preventDefault();
        let tag = $(this);

        $(document).one("ajaxSend", function(){
            tag[0].reset();
            tag.parent().remove();
            
            let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';
    
            $('#com-'+parentId).next().prepend(html);
        });

        let data = tag.serializeArray();
        let url = baseUrl + "/user/ajax/newComment";

        if (data[0].value.trim() != "") {

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": "PUT", data:data, parentId:parentId, commentType: "blog"}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
                    console.log(response.html);
                    $('#com-'+parentId).next().prepend(response.html);
                    
                    $('[data-tool="tooltip"]').tooltip();

                    $('.commentDelete').off('click');
                    $('.commentDelete').on('click',function(e) {
                        deleteComment(this);
                    });

                    $('.likeCommentButton').off('click');
                    $('.likeCommentButton').on('click',function() {
                        likeComment(this);
                    });
                }
            });
            
            
            request.fail(function (xhr){
                $('.ajaxSpinner').remove();
                $.each(xhr.responseJSON.errors,function(key,value) {
                    alert(value);
                });
            });
        }else{
            alert(emptyCommentMsg);
        }
    });
}

function addTagUser(selected) {
    let userName = $(selected).val().trim();

    if (userName != "") {

        $(selected).val('');
        let html = '<div id="tagSpinner" class="col-3">'+
            '<div class="spinner-border" role="status">'+
                '<span class="sr-only">Loading...</span>'+
            '</div>'+
        '</div>';

        $('#taggedUsers').append(html);

        let url = baseUrl+'/user/ajax/checkUser';
        var request = $.ajax({
            method : 'post',
            url: url,
            data: {userName: userName}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                html = '<div class="col-3 taggedUser">'+
                    '<label class="taggedUserLabel" data-tool="tooltip" title="'+deleteTags+'" data-placement="bottom">'+userName+'</label>'+
                    '<input type="hidden" value="'+response.userId+'" name="taggedUser[]">'+
                '</div>';
                $('#taggedUsers').find('#tagSpinner').replaceWith(html);
                
                $('[data-tool="tooltip"]').tooltip();

                $('.taggedUser').off('click');
                $('.taggedUser').on('click',function() {
                if (confirm(deleteUserTag)) {
                    $(this).remove();
                    $('.tooltip:first').remove();
                } 
                });
            }
        });
        
        
        request.fail(function (xhr){
            alert(userNotFound);
            $('#taggedUsers').find('#tagSpinner').remove();
        });
    }else{
        alert(emptyUser);
    }
}

function tagUsersComment(selected) {
    if (!$('#taggedUsers').find('#tagSpinner').length) {
        let taggedUsers = $('#taggedUsers').html().trim();
        $('#tagUsersModal').modal('hide');

        $(selected).parents('.input-group').next().html(taggedUsers);
    }
}

function tagUsersPostModal(selected) {
    if (!$('#taggedUsers').find('#tagSpinner').length) {
        let taggedUsers = $('#taggedUsers').html().trim();
        $('#tagUsersModal').modal('hide');

        let output;

        if (selected.data('modal')) {
            output = '#commentModalUserTagged';
        }else{
            output = '#postTaggedUsersModal' 
        }
        $(output).html(taggedUsers);

        if ($(output).html().trim() == "") {
            let html = "<input type='hidden' name='noTags' value='true'>";
            $(output).html(html)
        }
    }
}