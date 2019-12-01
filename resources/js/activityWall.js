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
        autocomplete:false
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
    })
    //                              ------------------------delete'owanie znajomych
    $('.deleteFriend').on('click',function() {
        //local var in JS == let
        //get name of friend you want to delete
        let friendName = $(this).data('name');
        let confirmation = confirm("Na pewno chcesz usunąć "+friendName+"?");
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
                    alert("no,usuwamy");
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
    })
    
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