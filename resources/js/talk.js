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
        var url, request, tag, data;
        tag = $(this);
        url = __baseUrl + '/ajax/message/send';
        data = tag.serialize(); 


        $(document).one("ajaxSend", function(){
            tag[0].reset();
            let dataTemp = data.split('&')[0];
            dataTemp = dataTemp.replace('message-data=','');
            if(dataTemp.trim()){
                let html = '<li class="clearfix" id="to-be-replaced">'+
                        '<img src="'+img.src+'">'+
                '</li>';
                $('#talkMessages').append(html);
                $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));
            }
        }); 

        var request = $.ajax({
            method: "post",
            url: url,
            data: data
        });

        request.done(function (response) {
            if (response.status == 'success') {
                $('#to-be-replaced').replaceWith(response.html);
                $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));
                var $thread = $('#user-'+response.receiver_id);
                if($thread.length)
                    $('#user-'+response.receiver_id).remove();
                $('#people-list .list').prepend(response.html2);

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
                $('#to-be-replaced').remove();
            }
        });

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