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
        placeholder: "\xa0"
    });

    $('#postPicture').change(function(evt){
        var files = evt.target.files; // FileList object
        
        // Empty the preview list
        $('#picture-preview').empty();

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
            $('#picture-preview').prepend(span, null);
            $('.emojionearea-editor').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
    })

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
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
                $('#spinner').remove();
            });
        }
    })
}