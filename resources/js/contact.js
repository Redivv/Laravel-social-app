import "lightbox2";

$(document).ready(function() {
    main();
    $('[data-tool="tooltip"]').tooltip();
});


function main() {
    $('#EmailContent').emojioneArea({
        pickerPosition: "bottom",
        placeholder: descPlaceholder,
    });

    $('#EmailAttachments').change(function(evt){
        var files = evt.target.files; // FileList object
        
        // Empty the preview list
        $('#EmailAttachmentsOut').empty();

        
        let html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt" data-tool="tooltip" title="'+deleteImages+'" data-placement="bottom"></i></div>';
        $('#EmailAttachmentsOut').append(html);
        $('[data-tool="tooltip"]').tooltip();
        let tag = $(this);

        $('.resetPicture').one('click',function() {
            if (confirm(resetImgMsg)) {
                tag.val("");
                $('#EmailAttachmentsOut').empty();
                $('.tooltip:first').remove();
            }
        })

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            $(this).val("");
            alert(badFileType);
            $('#EmailAttachmentsOut').empty();
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<a href="', e.target.result,'" data-lightbox="newPost"><img class="thumb" src="', e.target.result,
                                '" title="', escape(theFile.name), '" alt="Picture Preview"/></a>'].join('');
            $('#EmailAttachmentsOut').append(span, null);
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
    });

}