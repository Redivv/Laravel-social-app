import "lightbox2";

$(document).ready(function() {

    $('[data-tool="tooltip"]').tooltip()
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
})


function main() {

    $(window).on('scroll',function() {
        showScrollUp();
    });

    $('#showSidePanels').on('click',function() {
        if ($('.friendsList:first').hasClass('show') || $('.wallExtraFunctions:first').hasClass('show')) {
            $('.wallExtraFunctions').removeClass('show');
            $('.friendsList').removeClass('show');
            $(this).html('<i class="fas fa-arrows-alt-h"></i>');
            setTimeout(function(){
                $('.darkOverlay').addClass('d-none');
            }, 900);
        }else{
            $('.wallExtraFunctions').addClass('show');
            $('.friendsList').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');

            $('.darkOverlay').off('click');
            $('.darkOverlay').on('click',function(){
                $('.wallExtraFunctions').removeClass('show');
                $('.friendsList').removeClass('show');
                $('#showSidePanels').html('<i class="fas fa-arrows-alt-h"></i>');
                setTimeout(function(){
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });

    $('#editPostDesc').emojioneArea({
        pickerPosition: "top",
        placeholder: "\xa0",
        autocomplete: false,
    });

    $('#commentsInputDesc').emojioneArea({
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

    $('#editModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let post = button.data('id');
        let modal = $(this);

        let spinnerHtml = '<div class="spinnerBox text-center mt-2">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
            '</div>'+
            '</div>';

        modal.find('.modal-body').html(spinnerHtml);

        let url = baseUrl+'/user/ajax/getPost/'+post;

        var request = $.ajax({
            method : 'get',
            url: url
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                modal.find('.modal-body').html(response.html);
                $('.resetPicture').one('click',function() {
                    if (confirm(resetImgMsg)) {
                        $('#editPicture').val("");
                        $('#modalPicture-preview').empty();
                        modal.find('#editPost').prepend('<input name="noPicture" type="hidden" value="noPicture">');
                    }
                });
                 $('#editPostDesc').emojioneArea({
                    pickerPosition: "bottom",
                    placeholder: "\xa0",
                    autocomplete:false
                });

                $('#editPicture').change(function(evt){
                    var files = evt.target.files; // FileList object
                    
                    // Empty the preview list
                    $('#modalPicture-preview').empty();

                    let html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>';
                    $('#modalPicture-preview').append(html);
                    let tag = $(this);

                    $('.resetPicture').one('click',function() {
                        if (confirm(resetImgMsg)) {
                            tag.val("");
                            $('#modalPicture-preview').empty();
                            modal.find('#editPost').prepend('<input name="noPicture" type="hidden" value="noPicture">');
                        }
                    });

                    // Loop through the FileList and render image files as thumbnails.
                    for (var i = 0, f; f = files[i]; i++) {

                    // Only process image files.
                    if (!f.type.match('image.*')) {
                        $(this).val("");
                        alert(badFileType);
                        $('#modalPicture-preview').empty();
                        break;
                    }

                    var reader = new FileReader();

                    // Closure to capture the file information.
                    reader.onload = (function(theFile) {
                        return function(e) {
                        // Render thumbnail.
                        var span = document.createElement('span');
                        span.innerHTML = ['<a href="', e.target.result,'" data-lightbox="editPost"><img class="thumb" src="', e.target.result,
                                            '" title="', escape(theFile.name), '"/></a>'].join('');
                        $('#modalPicture-preview').append(span, null);
                        $('.emojionearea-editor').focus();
                        };
                    })(f);

                    // Read in the image file as a data URL.
                    reader.readAsDataURL(f);
                    }
                    $('input[name="noPicture"]').remove();
                });

                $('#editPost').off('submit');

                $('#editPost').on('submit',function(e) {
                    e.preventDefault();
                    if ($('#editPicture').val() || $('#editPostDesc').val()) {
                        let url = baseUrl + "/user/ajax/editPost";
                        let tag = $(this);
            
                        $(document).one("ajaxSend", function(){   
                            $('#editModal').modal('hide');
                            $('.spinnerOverlay').removeClass('d-none');         
                            tag[0].reset();
                            $('.emojionearea-editor').empty();
                            $('#modalPicture-preview').empty();
                        });
            
                        var request = $.ajax({
                            method: "post",
                            url: url,
                            enctype: 'multipart/form-data',
                            processData: false,
                            contentType: false,
                            data: new FormData(this)
                        });
                        
                        
                        request.done(function(response){
                            if (response.status === 'success') {
                                $('.spinnerOverlay').addClass('d-none'); 
                                $('#post'+post).parent().replaceWith(response.html);

                                $('.postDelete').off('click');
                                $('.postDelete').on('click',function(){
                                    deletePost(this);
                                });

                                $('.likePostButton').off('click');
                                $('.likePostButton').on('click',function() {
                                    likePost(this);
                                });

                                $('.commentsForm').off('submit');
                                $('.commentsForm').on('submit',function(e){
                                    addComment(e,this);
                                });

                                $('.btnComment').one('click',function() {
                                    $('#post'+post).parent().find('.commentsDesc:first').emojioneArea({
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
                                    getComments(this);
                                });
                            }
                        });
                        
                        
                        request.fail(function (xhr){
                            $('.spinnerOverlay').addClass('d-none'); 
                            alert(xhr.responseJSON.message);
                        });
                    }
                });
            }
        });
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    });

    $('#editModal').on('hide.bs.modal', function () {
        $('#editPicture').off('change');
        $(this).find('.modal-body').html('');
    });

    $('#tagUsersModal').on('show.bs.modal',function(event) {
        let button = $(event.relatedTarget);
        let postId;
        if (postId = $(button).data('id')) {
            let html = '<div id="tagSpinner" class="col-3">'+
                '<div class="spinner-border" role="status">'+
                    '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';

            $('#taggedUsers').html(html);

            let url;
            if ($(button).hasClass('commentModal')) {
                url = baseUrl+'/user/ajax/getCommentTaggedUsers/'+postId;
            }else{
                url = baseUrl+'/user/ajax/getTaggedUsers/'+postId;
            }

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
                alert(xhr.responseJSON.message);
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
            if (postId) {
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

    $('.postDelete').on('click',function(){
        deletePost(this);
    });

    $('.likePostButton').on('click',function() {
        likePost(this);
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
                    data: {"_method": 'PATCH',data:newComment, commentId:commentId}
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
                    }
                });
                
                
                request.fail(function (xhr){
                    alert(xhr.responseJSON.message);
                    $('.spinnerOverlay').addClass('d-none');
                });
            }else{
                alert(emptyCommentMsg);
            }
        });

    });

    //                              ------------------------delete'owanie znajomych
    $('.deleteFriend').on('click',function() {
        //local var in JS == let
        //get name of friend you want to delete
        let friendName = $(this).data('name');
        let confirmation = confirm(deleteFriend+friendName+"?");
        //get url we want to visit with ajax
        if(confirmation==true){
            let url= baseUrl+"/friends/ajax/delete/"+friendName;
            //make request in ajax:
            var request = $.ajax({
                //select method
                method : 'post',
                //select destination
                url: url,
                //select content we want to send:
                data: {
                    //here, we just want to change our method to "delete", since it is strictly laravelish method
                    //and is unavaible in html.
                    "_method":"delete",
                }
            });
            //if our request is succesfull, in other words, our response code is 200:
            request.done(function(response){
                //if status made by response is 'succes':
                if (response.status === 'success') {
                    alert(friendDeleted);
                    let edit = $('#'+friendName);
                    let html= '<li class="displaynan"></li>';
                    $(edit).replaceWith(html);
                    //we delete object, that is not necessary from now.
                    // $(this).parents('.friendObject').remove();
                }
            });
            //if our request is unsuccesfull:
            request.fail(function (xhr){
                //we get our response as alert.
                alert(xhr.responseJSON.message);
            });
        }
    });

    $('.reportBtn').on('click',function() {
        let reportReason = prompt(reportUserReason);
        if (reportReason.trim() == '') {
            alert(reportUserReasonErr);
        }else{
            $('.spinnerOverlay').removeClass('d-none');

            let userName = $(this).data('name');
            let url = base_url+"/user/report";

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": 'PUT', userName:userName, reason:reportReason.trim()}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.spinnerOverlay').addClass('d-none');
                    alert(reportUserSuccess);
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }
    });

    $('.commentsForm').on('submit',function(e){
        addComment(e,this);
    });

    $('.commentDelete').on('click',function(e) {
        deleteComment(this);
    });

    $('.replyButton').on('click',function() {
        addReplyForm(this);
    });

    $('.likeCommentButton').on('click',function() {
        likeComment(this);
    });

    $('.repliesMoreBtn').on('click',function() {
        loadReplies(this);
    });

    $('.commentsMoreBtn').on('click',function() {
        loadMoreComments(this);
    });
    
}

function deletePost(selected) { 
    if (confirm(deletePostMsg)) {
        let url = baseUrl + "/user/ajax/deletePost";
        let postId = $(selected).data('id');
        $('.spinnerOverlay').removeClass('d-none');

        var request = $.ajax({
            method : 'post',
            url: url,
            data: {'_method': 'DELETE',id:postId}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#post'+postId).next().remove();
                $('#post'+postId).remove();
                $('.spinnerOverlay').addClass('d-none');
            }
        });
        
        
        request.fail(function (xhr){
            $('.spinnerOverlay').addClass('d-none');
            alert(xhr.responseJSON.message);
        });
    }
}

function deleteComment(selected) {
    if (confirm(deleteCommentMsg)) {
        let url = baseUrl + "/user/ajax/deleteComment";
        let commentId = $(selected).data('id');
        $('.spinnerOverlay').removeClass('d-none');

        var request = $.ajax({
            method : 'post',
            url: url,
            data: {'_method': 'DELETE',id:commentId}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#com-'+commentId).siblings('.commentRepliesBox').remove();

                if (!$(selected).hasClass('replyDelete')) {
                    let commAmount = $(selected).parents('.postComments').prev().find('.postCommentsCount').html().trim();
                    if (commAmount - 1 <= 0) {
                        $(selected).parents('.postComments').prev().find('.postCommentsCount').html("");
                    }else{
                        $(selected).parents('.postComments').prev().find('.postCommentsCount').html(commAmount - 1);
                    }
                }
                $('#com-'+commentId).remove();
                $('.spinnerOverlay').addClass('d-none');
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    }
}

function getComments(selected) {

        $('.commentsForm').on('submit',function(e){
            addComment(e,this);
        });
    
        let pagi = $(selected).data('pagi');
        let postId = $(selected).data('id');
        let commentBox = $('#post'+postId).next();

        if(!($(commentBox).find('.emojionearea-editor').length)){
            $(commentBox).find('.commentsDesc').emojioneArea({
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
        }

        let commentsCount = $('#post'+postId).find('.postCommentsCount');

        commentBox.removeClass('d-none');
        commentBox.find('.emojionearea-editor').focus();
        
        if (commentsCount.text().trim() != "") {
            
            let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';

            $('#feed-'+postId).html(html);

            let url = baseUrl + "/user/ajax/getComments/"+postId;

            var request = $.ajax({
                method : 'get',
                url: url,
                data: {pagi:pagi}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('#feed-'+postId).html(response.html);
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
                alert(xhr.responseJSON.message);
            });
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
                '<i class="fas fa-user-tag commentUserTag" data-toggle="modal" data-target="#tagUsersModal"></i>'+
            '</div>'+
        '</div>'+
        '<output id="replyUsersTag"></output>'
        '</form>'+
    '</div>';
    let parentComment = $('#com-'+parentId);
    $(formHtml).insertAfter('#com-'+parentId);

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
                data: {"_method": "PUT", data:data, parentId:parentId}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
                    $('#com-'+parentId).next().prepend(response.html);

                    $('.commentDelete').off('click');
                    $('.commentDelete').on('click',function(e) {
                        deleteComment(this);
                    });
                }
            });
            
            
            request.fail(function (xhr){
                $('.ajaxSpinner').remove();
                alert(xhr.responseJSON.message);
            });
        }else{
            alert(emptyCommentMsg);
        }
    });
}

function addComment(event, selected) {
    event.preventDefault();
        let tag = $(selected);
        let postId = tag.data('id');

        $(document).one("ajaxSend", function(){
            tag[0].reset();
            tag.find('.emojionearea-editor').empty();
            
            let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';
    
            $('#feed-'+postId).prepend(html);
        });

        let data = tag.serializeArray();
        let url = baseUrl + "/user/ajax/newComment";

        if (data[0].value.trim() != "") {

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": "PUT", data:data, postId:postId}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.ajaxSpinner').remove();
                    $('#feed-'+postId).prepend(response.html);
                    $('.commentDelete').off('click');

                    let commentAmount = $('#post'+postId).find('.postCommentsCount').html().trim();

                    if (commentAmount == "") {
                        commentAmount = 0;
                    }
                    $('#post'+postId).find('.postCommentsCount').html(parseInt(commentAmount)+1);

                    $('.commentDelete').off('click');
                    $('.commentDelete').on('click',function(e) {
                        deleteComment(this);
                    });

                    $('.likeCommentButton').off('click');
                    $('.likeCommentButton').on('click',function() {
                        likeComment(this);
                    })
                    
                    $('.replyButton').off('click');
                    $('.replyButton').on('click',function() {
                        addReplyForm(this);
                    });
                }
            });
            
            
            request.fail(function (xhr){
                $('.ajaxSpinner').remove();
                alert(xhr.responseJSON.message);
            });
        }else{
            alert(emptyCommentMsg);
        }
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

    let url = baseUrl + "/user/ajax/getReplies/"+parentId;

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
        alert(xhr.responseJSON.message);
    });
}

function loadMoreComments(selected) {

    let button = $(selected);
    let postId = button.data('id');
    let pagi = $(button).data('pagi');

    let html = '<div id="spinner" class="ajaxSpinner">'+
            '<div class="spinner-border text-dark" role="status">'+
                '<span class="sr-only">Loading...</span>'+
        '</div>'+
    '</div>';

    $(document).one("ajaxSend", function(){   
        button.parents('.commentsFeed').append(html);
    });

    let url = baseUrl + "/user/ajax/getComments/"+postId;

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
        alert(xhr.responseJSON.message);
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
        data: {'_method':'PATCH', commentId:commentId}
    });
}

function likePost(selected) {
    let postId = $(selected).data('id');
    let url = baseUrl + "/user/ajax/likePost";

    let likesCount = $(selected).children('.likesCount').html().trim();
    if (likesCount == "") {
        likesCount = 0;
    }
    likesCount = parseInt(likesCount);

    if ($(selected).hasClass('active')) {
        $(selected).removeClass('active');
        if (likesCount - 1 == 0) {
            $(selected).children('.likesCount').html('');
        }else{
            $(selected).children('.likesCount').html(likesCount-1);
        }
    }else{
        $(selected).addClass('active');
        $(selected).children('.likesCount').html(likesCount+1);
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {'_method':'PATCH', postId:postId}
    });
}

function pagiPosts() {
    if(($(window).scrollTop() + $(window).height() > $(document).height() - 50)) {
        $(window).off('scroll');
        pagi++;
        let url = baseUrl + "/user/ajax/getMorePosts";

        let sortParam = getUrlParameter('sortBy');

        var request = $.ajax({
            method : 'get',
            url: url,
            data: {pagiTime:pagi,sortBy:sortParam}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#friendsWallFeed').append(response.html);
                if (response.stopPagi == false) {

                    $(window).on('scroll',function() {
                        pagiPosts();
                        showFetchBtn(position);
                        showScrollUp();
                    });

                }else{

                    $(window).on('scroll',function() {
                        showFetchBtn(position);
                        showScrollUp();
                    });
                }

                $('.btnComment').off('click');
                $('.btnComment').one('click',function() {
                    getComments(this);
                });

                $('.postDelete').off('click');
                $('.postDelete').on('click',function(){
                    deletePost(this);
                });

                $('.likePostButton').off('click');
                $('.likePostButton').on('click',function() {
                    likePost(this);
                });
            
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    }
}

function refreshWall(selected) {
    $(selected).addClass('d-none');
    $(selected).removeClass('ready');

    $('.spinnerOverlay').removeClass('d-none');

    let url = baseUrl+"/user/home";

    var request = $.ajax({
        method : 'get',
        url: url,
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            $(selected).removeClass('spin');
            $('#friendsWallFeed').html(response.html);
            $('.spinnerOverlay').addClass('d-none');

            window.scrollTo(0,0);

            $('.postDelete').off('click');
            $('.postDelete').on('click',function(){
                deletePost(this);
            });
        
            $('.likePostButton').off('click');
            $('.likePostButton').on('click',function() {
                likePost(this);
            });

            $('.btnComment').off('click');
            $('.btnComment').one('click',function() {
                getComments(this);
            });
            
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
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
                    '<label class="taggedUserLabel">'+userName+'</label>'+
                    '<input type="hidden" value="'+response.userId+'" name="taggedUser[]">'+
                '</div>';
                $('#taggedUsers').find('#tagSpinner').replaceWith(html);

                $('.taggedUser').off('click');
                $('.taggedUser').on('click',function() {
                if (confirm(deleteUserTag)) {
                    $(this).remove();
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

function tagUsers() {
    if (!$('#taggedUsers').find('#tagSpinner').length) {
        let taggedUsers = $('#taggedUsers').html().trim();
        $('#tagUsersModal').modal('hide');

        $('#postTaggedUsers').html(taggedUsers);
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

function showFetchBtn() {
    if ($('#wallFetchBtn').hasClass('ready')) {
        var scroll = $(window).scrollTop();
        if(scroll > position) {
            $('#wallFetchBtn').addClass('d-none');
        } else {
            $('#wallFetchBtn').removeClass('d-none');
        }
        position = scroll;
    }
}

function showScrollUp() {
    var y = $(this).scrollTop();
    if (y >= 100) {
        $('#scrollUpAnchor').css('left','0');
    } else {
        $('#scrollUpAnchor').css('left', '-100%');
    }
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};