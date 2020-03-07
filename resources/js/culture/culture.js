import "lightbox2";

import {
    addNewTagInputFromIn,
    addOnClickDeleteEventOnRemove,
    sendAjaxRequestToWithFormData,
    getDataByAjaxFromUrlWithData,
    showSpinnerOverlay
} from "./cultureFunctions";

$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[data-tool="tooltip"]').tooltip();

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

    $('.commentDelete').on('click',function(e) {
        deleteComment(this);
    });

    $('.likeCommentButton').on('click',function() {
        likeComment(this);
    });

    $('.replyButton').on('click',function() {
        addReplyForm(this);
    });

    addOnClickDeleteEventOnRemove(".itemTag");

    $('input[type=radio][name=options]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });

    $('.likeBtn').on('click',function(e) {
        e.preventDefault();
        likeItem(this);
    });

    $('.deleteItem').on('submit',function(e) {
        e.preventDefault();
        if (confirm(confirmMsg)) {
            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/culture/deleteItem",this);
            $(this).parents('.resultBox').remove();
            alert(savedChanges);
        }
    });
    
    $('#searchTags').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this,'#searchTags-out');
        }
    });

    $('#searchTags').next().find('.tagsBtn').on('click',function() {
        let input = $(this).parent().prev();
        addNewTagInputFromIn(input,'#searchTags-out');
    });
    
    $('.repliesMoreBtn').on('click',function() {
        loadReplies(this);
    });

    $('.cultureSection').on('click',function(e) {
        e.preventDefault();
        
        let catName = $(this).data('category');
        if (catName == "all") {
            $('#searchCategory-data').remove();
            $("#cultureSearch").submit();
        }
        let html = '<input id="searchCategory-data" type="hidden" name="searchCategory" value="'+catName+'">';

        if ($('#searchCategory-data').length) {
            $('#searchCategory-data').replaceWith(html);
        }else{
            $("#cultureSearch").prepend(html);
        }
        $("#cultureSearch").submit();
    });

    $('#reviewModal').one('show.bs.modal',function(e) {
       let itemId = $(e.relatedTarget).data('itemid');
       getDataByAjaxFromUrlWithData(baseUrl+"/culture/ajax/getReview",itemId);
    });

    $("#searchTags").autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: baseUrl+"/ajax/tag/autocompleteHobby",
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
        minLength: 1
    });

    $('#editPostDesc').emojioneArea({
        pickerPosition: "top",
        placeholder: "\xa0",
        autocomplete: false,
    });

    
    $('.commentsForm').on('submit',function(e){
        addComment(e,this);
    });
    
    $('.commentsMoreBtn').on('click',function() {
        loadMoreComments(this);
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
                    data: {"_method": 'PATCH',data:newComment, commentId:commentId, commentType: "culture"}
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
            
            let url = baseUrl+'/user/ajax/getCultCommentTaggedUsers/'+itemId;

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

}

function tagUsersComment(selected) {
    if (!$('#taggedUsers').find('#tagSpinner').length) {
        let taggedUsers = $('#taggedUsers').html().trim();
        $('#tagUsersModal').modal('hide');

        $(selected).parents('.input-group').next().html(taggedUsers);
    }
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
                data: {"_method": "PUT", data:data, parentId:parentId, commentType: "culture"}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
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

    let url = baseUrl + "/user/ajax/getCultReplies/"+parentId;

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

    let url = baseUrl + "/user/ajax/getCultComments/"+itemId;

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

function tagUsersPostModal(selected) {
    if (!$('#taggedUsers').find('#tagSpinner').length) {
        let taggedUsers = $('#taggedUsers').html().trim();
        console.log (taggedUsers);
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
        data: {'_method':'PATCH', commentId:commentId, commentType: "culture"}
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
            data: {'_method': 'DELETE',id:commentId, commentType:"culture"}
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
    
            $('#feed-'+itemId).prepend(html);
        });

        let data = tag.serializeArray();
        let url = baseUrl + "/user/ajax/newComment";

        if (data[0].value.trim() != "") {

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": "PUT", data:data, itemId:itemId, commentType: "culture"}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
                    $('#feed-'+itemId).prepend(response.html);
                    
                    $('[data-tool="tooltip"]').tooltip();

                    $('.commentDelete').off('click');

                    let commentAmount = $('#post'+itemId).find('.postCommentsCount').html().trim();

                    if (commentAmount == "") {
                        commentAmount = 0;
                    }
                    $('#post'+itemId).find('.postCommentsCount').html(parseInt(commentAmount)+1);

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


function likeItem(selected) {
    let itemId = $(selected).data('id');
    let url = baseUrl+"/culture/ajax/likeItem";

    let currentAmount = $(selected).find('.likesCount').html();
    console.log(currentAmount);
    
    if ($(selected).hasClass('active')) {

        $(selected).removeClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)-1);
        if (currentAmount == 1) {
            $(selected).find('.likesCount').addClass('invisible');
        }
    }else{
        
        $(selected).addClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)+1);
        if (currentAmount == 0) {
            $(selected).find('.likesCount').removeClass('invisible');
        }
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {"_method": "patch", itemId:itemId}
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
        $('.deleteItem').on('submit',function(e) {
            e.preventDefault();
            if (confirm(confirmMsg)) {
                showSpinnerOverlay();
                sendAjaxRequestToWithFormData(baseUrl+"/culture/deleteItem",this);
                $(this).parents('.resultBox').remove();
                $(".tooltip:first").remove();
            }
        });
    });
}