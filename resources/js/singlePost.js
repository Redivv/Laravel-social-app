$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
})


function main() {

    $('#editPostDesc').emojioneArea({
        pickerPosition: "top",
        placeholder: "\xa0",
        autocomplete: false,
    });
    
    $('.commentsDesc').emojioneArea({
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

    $('.postDelete').on('click',function(){
        deletePost(this);
    });

    $('.likePostButton').on('click',function() {
        likePost(this);
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

    $('#commentEditModal').on('show.bs.modal', function (event) {
        let button      = $(event.relatedTarget);
        let commentId   = button.data('id');
        let modal       = $(this);
        let comment     = $('#com-'+commentId);

        let content     = comment.find('.commentDesc').html().trim();

        console.log(content);

        modal.find('.emojionearea-editor').html(content);
        modal.find('#editPostDesc').val(content);

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

function addReplyForm(selected) {
    $('#replyForm').remove();
    let parentId = $(selected).data('id');
    let formHtml = '<div class="replyForm">'+
    '<form id="replyForm" method="post">'+
        '<div class="input-group row">'+
            '<input type="text" name="commentDesc" id="replyInput" class="form-control replyDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">'+
            '<div class="input-group-append col-1 commentButtons">'+
                '<i class="fas fa-user-tag"></i>'+
                '</div>'+
            '</div>'+
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