$(document).ready(function () {
    // Setup Ajax csrf for future requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Scroll chat history to newest message on start
    $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));

    // Preload loading gif for immediate display
    var img = new Image();
    img.src = __baseUrl+"/chat/loading.gif";

    // Bind scroll function to chat history for pagination request
    $( "div.chat-history" ).bind('scroll',chk_scroll);

    // Enter key will send a message, Shift+enter will do normal break
    var shift_pressed = false;
    $('#message-data').keydown(function(e){

        if(((e.keyCode || e.which) == 16)) {
            shift_pressed = true;
        }

        if((((e.keyCode || e.which) == 13) && shift_pressed === false)) {
            e.preventDefault();
            $('#talkSendMessage').submit();
        }
    });
    $('#message-data').keyup(function(e){
        if(((e.keyCode || e.which) == 16)) {
            shift_pressed = false;
        }
    });

    // Sending a message dynamicly
    $('#talkSendMessage').on('submit', function(e) {
        e.preventDefault();
        if ($('#message-data').val() || $('#upload-pictures').val()) { 
            var url, request, tag;
            tag = $(this);
            url = __baseUrl + '/ajax/message/send';


            $(document).one("ajaxSend", function(){
                tag[0].reset();
                $('#picture-preview').empty();
                let html = '<li class="clearfix" id="to-be-replaced">'+
                        '<img src="'+img.src+'">'+
                '</li>';
                $('#talkMessages').append(html);
                $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));
            }); 

            var request = $.ajax({
                method: "post",
                url: url,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: new FormData(this)
            });

            request.done(function (response) {
                if (response.status == 'success') {
                    $('#to-be-replaced').replaceWith(response.html);
                    $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));
                    var $thread = $('#user-'+response.receiver_id);
                    var active_flag = true;
                    if($thread.length){
                        if ($('#user-'+response.receiver_id).hasClass('activeUser')) {
                            active_flag = 'activeUser';
                        }
                        $('#user-'+response.receiver_id+'+hr').remove();
                        $('#user-'+response.receiver_id).remove();
                    }
                    $('#people-list .list').prepend(response.html2);
                    if (active_id.includes(parseInt(response.receiver_id,10))) {
                        $('#user-'+response.receiver_id).addClass('activeUser');
                    }

                    $('.talkDeleteConversation').on('submit',function(e){
                        if(!confirm(deleteConvo)) {
                            e.preventDefault();
                        }   
                    });
                    
                    $('.talkBlockConversation').on('submit',function(e){
                        if(!confirm(blockConvo)) {
                            e.preventDefault();
                        }   
                    });
                }
            });
            request.fail(function (xhr){
                if(xhr.responseJSON.status == "blocked-user"){
                    alert(xhr.responseJSON.msg);
                }
                $('#to-be-replaced').remove();
            });
        }else{
            alert('Nie możesz wysłać pustej wiadomości');
        }
    });

    // Soft deleting a message dynamicly
    $('body').on('click', '.talkDeleteMessage', function (e) {
        e.preventDefault();
        var tag, url, id, request;

        tag = $(this);
        id = tag.data('message-id');
        url = __baseUrl + '/ajax/message/delete/' + id;

        if(!confirm(deleteMessage)) {
            return false;
        }

        request = $.ajax({
            method: "post",
            url: url,
            data: {"_method": "DELETE"}
        });

        request.done(function(response) {
           if (response.status == 'success') {
                $('#message-' + id).hide(500, function () {
                    $(this).remove();
                });
           }
        });
    })
    // Confirm blocking or deleting a Convo
    $('.talkDeleteConversation').on('submit',function(e){
        if(!confirm(deleteConvo)) {
            e.preventDefault();
        }   
    });
    $('.talkBlockConversation').on('submit',function(e){
        if(!confirm(blockConvo)) {
            e.preventDefault();
        }   
    });

    $('#upload-pictures').change(function(evt){
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
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
                                '" title="', escape(theFile.name), '"/>'].join('');
            $('#picture-preview').prepend(span, null);
            $('#message-data').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
    })

    
    
});

// On full up scroll chat history try to load more messages
function chk_scroll(e) {
    var elem = $(e.currentTarget);
    if (elem.scrollTop() == 0 && stop_pagi === false){
        pagi++;
        var url = window.location.href;
        var request = $.ajax({
            method: "get",
            url: url,
            data:{pagi:pagi}
        });

        request.done(function (response) {
            if (response.status == 'success') {
                if(response.html != ''){
                    $('#talkMessages').prepend('<li id="top-msg"></li>');
                    $('#talkMessages').prepend(response.html);
                    $("div.chat-history").scrollTop($("div.chat-history").scrollTop() + $("#top-msg").position().top - $("div.chat-history").height()/4 + $("#top-msg").height()/4);
                    stop_pagi = response.stop;
                }
            }
        });
    }
}