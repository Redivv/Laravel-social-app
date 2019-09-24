function playSound(sound,element){
    element.setAttribute('src', sound);
    element.play();
  }

  function addNewMessage(id) {
    var request = $.ajax({
      method: "get",
      url: __baseUrl+'/ajax/message/get/'+id
      });

      request.done(function (response) {
        if (response.status == 'success') {
          $('#talkMessages').append(response.html);
        }
      });
 }

  function updateThreads(data, is_new = '') {
      var $thread = $('#user-'+data.sender.id);
      var active_flag = '';
      if($thread.length){
        if ($('#user-'+data.sender.id).hasClass('activeUser')) {
          active_flag = 'activeUser';
        }
        $('#user-'+data.sender.id+'+hr').remove();
        $('#user-'+data.sender.id).remove();
      }
      var html = '<li data-id="'+data.sender.id+'" id="user-'+data.sender.id+'" class="clearfix thread '+active_flag+'">'+
          '<form action="/message/'+data.sender.id+'" class="talkDeleteConversation float-left" method="POST">'+
          '<input type="hidden" name="_method" value="DELETE">'+
          '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'+
          '<button class="btn btn-link btn-sm" type="submit"><i class="fas fa-times"></i></button>'+
          '</form>'+
          '<form action="/message/'+data.sender.id+'" class="talkBlockConversation" method="POST">'+
          '<input type="hidden" name="_method" value="PATCH">'+
          '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'+
          '<button class="btn btn-link btn-sm" type="submit"><i class="fas fa-user-times"></i></button>'+
          '</form>'+
          '<a href="/message/'+data.sender.id+'">'+
          '<div class="profile-picture">'+
                '<img src="'+__baseUrl+'/img/profile-pictures/'+data.sender.picture+'" alt="profile picture">'+
            '</div>'+
            '<div class="about '+is_new+'">'+
              '<div class="name">'+data.sender.name+'</div>'+
              '<div class="status">'+
              '<span>';
              if(data.pictures != null){
                html += '<i class="far fa-file-image"></i> ';
              }
              if(data.message != null){
                html += data.message.substring(0,20);
              }
              html += '</span>'+
              '</div>'+
          '</div>'+
          '</a>'+
      '</li>'+
      '<hr style="background-color:#f66103;">';
      $('#people-list .list').prepend(html);

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