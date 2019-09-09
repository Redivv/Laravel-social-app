function playSound(sound,element){
    element.setAttribute('src', sound);
    element.play();
  }

  function addNewMessage(data,seconds_text,time_text) {
    var html = '<li id="message-' + data.id + '">' +
          '<div class="message-data">' +
          '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Message"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
          '<span class="message-data-time">1 '+seconds_text+' '+time_text+'</span>' +
          '</div>' +
          '<div class="message my-message">' +
          data.message +
          '</div>' +
          '</li>';
    $('#talkMessages').append(html);
 }

  function updateThreads(data, is_new = '') {
    var html = '<li id="user-'+data.sender.id+'" class="clearfix">'+
          '<a href="/message/'+data.sender.id+'">'+
            '<div class="about '+is_new+'">'+
              '<div class="name">'+data.sender.name+'</div>'+
              '<div class="status">'+
              '<span>'+data.message.substring(0,20)+'</span>'+
              '</div>'
          '</div>'
          '</a>'
        '</li>';
    var $thread = $('#user-'+data.sender.id);
    if($thread.length){
      $('#user-'+data.sender.id).remove();
    }
    $('#people-list .list').prepend(html);
    }

    function makeOneMessageSeen(data) {
      var request = $.ajax({
      method: "post",
      url: __baseUrl+'/ajax/message/seen/'+data.id,
      data: {"_method": "PATCH", "sender": data.sender.id}
      });

      request.done(function (response) {
        if (response.status == 'success') {
          new_messages--;
          if(new_messages <= 0){
            $(document).prop('title', title);
          }else{
            $(document).prop('title', '('+new_messages+') '+title);
          }
        }
      });
    }

    function makeAllMessagesSeen(sender_id) {
        var request = $.ajax({
            method: "post",
            url: __baseUrl+'/ajax/message/seen/0',
            data: {"_method": "PATCH", "sender": sender_id}
            });
      
            request.done(function (response) {
              if (response.status == 'success') {
                new_messages -= response.seen_messages;
                if(new_messages <= 0){
                  $(document).prop('title', title);
                }else{
                  $(document).prop('title', '('+new_messages+') '+title);
                }
              }
            });
    }