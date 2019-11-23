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

    $('#addPost').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "\xa0",
        autocomplete:false,
        buttonTitle: ""
    });

    $('.commentsDesc').emojioneArea({
        pickerPosition: "top",
        placeholder: "Napisz Komentarz",
        autocomplete:false,
        buttonTitle: "kokok",
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

    $('#postPicture').change(function(evt){
        var files = evt.target.files; // FileList object
        
        // Empty the preview list
        $('#picture-preview').empty();

        
        let html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>';
        $('#picture-preview').append(html);
        let tag = $(this);

        $('.resetPicture').one('click',function() {
            if (confirm(resetImgMsg)) {
                tag.val("");
                $('#picture-preview').empty();
            }
        })

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            $(this).val("");
            alert(badFileType);
            $('#picture-preview').empty();
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
                                '" title="', escape(theFile.name), '"/>'].join('');
            $('#picture-preview').append(span, null);
            $('.emojionearea-editor').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
    });

    $('#wallPost').on('submit',function(e) {
        e.preventDefault();
        if ($('#postPicture').val() || $('#addPost').val()) {
            let url = baseUrl + "/user/ajax/newPost";
            let tag = $(this);

            $(document).one("ajaxSend", function(){
                tag[0].reset();
                $('.emojionearea-editor').empty();
                $('#picture-preview').empty();
                let html = '<div id="spinner" class="ajaxSpinner">'+
                    '<div class="spinner-border text-dark" role="status">'+
                        '<span class="sr-only">Loading...</span>'+
                    '</div>'+
                '</div>';
                if ($('.noContent').length) {
                    $('.noContent').remove();
                }
                $('#friendsWallFeed').prepend(html);
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
                    $('#spinner').remove();
                    $('#friendsWallFeed').prepend(response.html);
                    $('.postDelete').on('click',function(){
                        deletePost(this);
                    })
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
                $('#spinner').remove();
            });
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
                        span.innerHTML = ['<img class="thumb" src="', e.target.result,
                                            '" title="', escape(theFile.name), '"/>'].join('');
                        $('#modalPicture-preview').append(span, null);
                        $('.emojionearea-editor').focus();
                        };
                    })(f);

                    // Read in the image file as a data URL.
                    reader.readAsDataURL(f);
                    }
                    $('input[name="noPicture"]').remove();
                });

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
                                $('#post'+post).replaceWith(response.html);
                                $('#post'+post).next().remove();
                            }
                        });
                        
                        
                        request.fail(function (xhr){
                            alert(xhr.responseJSON.message);
                        });
                    }
                });
            }
        });
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
      })

    $('#editModal').on('hide.bs.modal', function () {
        $('#editPicture').off('change');
        $(this).find('.modal-body').html('');
    });

    $('.postDelete').off('click');

    $('.postDelete').on('click',function(){
        deletePost(this);
    });

    $('.btnComment').on('click',function() {
        let commentBox = $(this).parent().parent().next();

        commentBox.removeClass('d-none');
        commentBox.find('.emojionearea-editor').focus();
    });

    $('.commentsForm').on('submit',function(e){
        e.preventDefault();
        let tag = $(this);
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
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }else{
            alert("Nie możesz dodać komentarza bez treści");
        }
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
            alert(xhr.responseJSON.message);
        });
    }
}