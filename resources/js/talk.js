$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));


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
                    '<div class="message temporary-message float-right">'+
                        unescape(dataTemp)+
                    '</div>'+
                '</li>';
                $('#talkMessages').append(html);
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
                var $thread = $('#user-'+response.receiver_id);
                if($thread.length)
                    $('#user-'+response.receiver_id).remove();
                $('#people-list .list').prepend(response.html2);
                $('.talkDeleteConversation').on('submit',function(e){
                    if(!confirm(deleteConvo)) {
                        e.preventDefault();
                    }   
                });
            }
        });

    });


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

    $('.talkDeleteConversation').on('submit',function(e){
        if(!confirm(deleteConvo)) {
            e.preventDefault();
        }   
    });
});