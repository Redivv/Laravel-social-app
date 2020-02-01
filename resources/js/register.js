import "lightbox2";
$(document).ready(function () {
    $('#profile-picture').change(function(evt){
        var files = evt.target.files; // FileList object
        
        // Empty the preview list
        $('#picture-preview').empty();

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            $(this).val("");
            alert("Niewłaściwy Typ Pliku!");
            $('#picture-preview').empty();
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<a href="', e.target.result,'" data-lightbox="register"><img class="profile-picture" src="', e.target.result,
                                '" title="', escape(theFile.name), '" alt="Picture Preview"/></a>'].join('');
            $('#picture-preview').prepend(span, null);
            $('#message-data').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
    })
});