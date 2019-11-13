$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    main();
})


function main() {
    $('#addPost').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "\xa0"
    });
}