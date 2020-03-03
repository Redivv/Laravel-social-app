import "lightbox2";

let shiftPressed = false;

let idleTimer = null;
let idleWait = 1000;

let spinnerHtml = '<div class="spinner-border text-warning d-block mx-auto mt-2" role="status">'+
                    '<span class="sr-only">Loading...</span>'
                '</div>';

$(document).ready(function () {
    $('[data-tool="tooltip"]').tooltip();
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

    // Bind scroll function to chat history for pagination request
    $( "ul.list" ).bind('scroll',chk_scroll_down);
    
    // Sending a message dynamicly
    $('#talkSendMessage').on('submit', function(e) {
        e.preventDefault();
        if ($('#message-data').val() || $('#upload-pictures').val()) { 
            var url, request, tag;
            tag = $(this);
            url = __baseUrl + '/ajax/message/send';


            $(document).one("ajaxSend", function(){
                tag[0].reset();
                $('.emojionearea-editor').empty();
                $('#picture-preview').empty();
                $('.chat-history').removeClass('imagePresent');
                let html = '<li class="clearfix" id="to-be-replaced">'+
                                '<div class="spinner-border text-dark" role="status">'+
                                    '<span class="sr-only">Loading...</span>'+
                                '</div>'+
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
                    $('[data-tool="tooltip"]').tooltip();
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
                    $('#people-list').children('.list').prepend(response.html2);
                    $('[data-tool="tooltip"]').tooltip();
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
                else if(xhr.status == 422){
                    for (let [key, value] of Object.entries(xhr.responseJSON.errors)) {
                        alert(`Błąd ${key}: ${value}`);
                    }
                }
                $('#to-be-replaced').remove();
            });
        }else{
            alert('Empty Message');
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
    });

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
            alert(badFileType);
            $('#picture-preview').empty();
            $('.chat-history').removeClass('imagePresent');
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<a href="'+e.target.result+'" data-lightbox="PreviewImages" data-title="Preview Images"><img class="thumb" src="', e.target.result,
                                '" title="', escape(theFile.name), '" alt="Picture Preview"/></a>'].join('');
            $('#picture-preview').prepend(span, null);
            $('.emojionearea-editor').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        $('.chat-history').addClass('imagePresent');
        }
    });

    let emoji = $('#message-data').emojioneArea({
        filtersPosition: "bottom",
        autocomplete:false,
        events: {
            keydown: function(editor,e) {
                if( !shiftPressed && (e.keyCode == 13 || e.which == 13)){
                    e.preventDefault();
                    $('#message-data').val(this.getText());
                    $('#talkSendMessage').submit();
                }else if(e.keyCode == 16 || e.which == 16){
                    shiftPressed = true;
                }else{
                    shiftPressed = false;
                }
            }
          }
    });

    emoji[0].emojioneArea.setFocus();

    $('#searchForConvo').on('keyup',function(e){
        searchConvo(this,e);
    });

    

    $('#showPeopleList').on('click',function() {
        if ($('#people-list').hasClass('show')){
                $('#people-list').removeClass('show');
                $(this).html('<i class="fas fa-arrow-left"></i>');
                setTimeout(function(){
                    $('.darkOverlay').addClass('d-none');
                }, 900);
        }else{
            $('#people-list').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');
            
            $('.darkOverlay').one('click',function(){
                $('#people-list').removeClass('show');
                $('#showPeopleList').html('<i class="fas fa-arrow-left"></i>');
                setTimeout(function(){
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });
    
});

// On full up scroll chat history try to load more messages
function chk_scroll(e) {
    var elem = $(e.currentTarget);
    $(elem).off('scroll');
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
                    if(!stop_pagi){
                        $(elem).bind('scroll',chk_scroll);
                    }
                }
            }
        });
    }
}

function chk_scroll_down(e) {
    var elem = $(e.currentTarget);
    $(elem).off('scroll');
    if (($(elem).scrollTop() + $(elem).innerHeight() >= $(elem)[0].scrollHeight) && (stop_pagi_convo === false)){
        pagi_convo++;
        var url = __baseUrl + '/ajax/message/getMore/'+pagi_convo;
        var request = $.ajax({
            method: "get",
            url: url,
            data:{pagi:pagi_convo}
        });

        request.done(function (response) {
            if (response.status == 'success') {
                $(elem).append(response.html);
                stop_pagi_convo = response.stop;
                $(elem).bind('scroll',chk_scroll_down);
                if(!stop_pagi_convo){
                    $(elem).bind('scroll',chk_scroll_down);
                }
            }
        });
    }
}

function searchConvo(selected,event) {
    let searchCryteria = $('#searchForConvo').val().trim();
    if (searchCryteria != "") {

        clearTimeout(idleTimer);
            
            idleTimer = setTimeout(function () { 
                
                let url = __baseUrl+"/ajax/message/searchConvo";

                var request = $.ajax({
                    method : 'get',
                    url: url,
                    data: {searchCryteria:searchCryteria}
                });
                
                
                request.done(function(response){
                    if (response.status === 'success') {

                        $('ul.searchList').html(response.html);
                        $('[data-tool="tooltip"]').tooltip();

                        $('.talkDeleteConversation').off('submit');
                        $('.talkDeleteConversation').on('submit',function(e){
                            if(!confirm(deleteConvo)) {
                                e.preventDefault();
                            }   
                        });
                    
                        $('.talkBlockConversation').off('submit');
                        $('.talkBlockConversation').on('submit',function(e){
                            if(!confirm(blockConvo)) {
                                e.preventDefault();
                            }   
                        });
                    }
                });
                
                
                request.fail(function (xhr){
                    $.each(xhr.responseJSON.errors,function(key,value) {
                        alert(value);
                    });
                });


            }, idleWait);

        $(selected).siblings('label').html('<i class="fas fa-times"></i>');

        $('ul.searchList').html(spinnerHtml);

        $('ul.list').addClass('d-none');
        $('ul.searchList').removeClass('d-none');

        $(selected).siblings('label').off('click');
        $(selected).siblings('label').one('click',function() {
            clearSearchInput();
        });



    }else{
        clearTimeout(idleTimer);

        $(selected).siblings('label').html('<i class="fas fa-search"></i>');

        $('ul.searchList').addClass('d-none');

        $('ul.searchList').html(spinnerHtml);

        $('ul.list').removeClass('d-none');
    }
}

function clearSearchInput() {

    clearTimeout(idleTimer);

    $('#searchForConvo').val("");
    $('#searchForConvo').siblings('label').html('<i class="fas fa-search"></i>');

    $('ul.searchList').addClass('d-none');

    $('ul.searchList').html(spinnerHtml);

    $('ul.list').removeClass('d-none');
}