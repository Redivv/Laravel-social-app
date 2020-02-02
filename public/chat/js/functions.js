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
      var active = "";
      var selectedConvo = "";
      if($thread.length){
        if ($('#user-'+data.sender.id).hasClass('activeUser')) {
          active = "activeUser";
        }
        if ($('#user-'+data.sender.id).hasClass('active')) {
          selectedConvo = "active";
        }
        $('#user-'+data.sender.id).remove();
      }
      var html = '<li data-id="'+data.sender.id+'" id="user-'+data.sender.id+'" class="clearfix thread row '+active+" "+selectedConvo+'">'+
          '<a class="row col-12" href="/message/'+data.sender.name+'">'+
          '<div class="threadForms col-12">'+
          '<form action="/message/'+data.sender.id+'" class="talkDeleteConversation float-left" method="POST">'+
          '<input type="hidden" name="_method" value="DELETE">'+
          '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'+
          '<button class="btn btn-link btn-sm" type="submit" data-tool="tooltip" title="'+toolDeleteConvo+'"><i class="fas fa-times"></i></button>'+
          '</form>'+
          '<form action="/message/'+data.sender.id+'" class="talkBlockConversation" method="POST">'+
          '<input type="hidden" name="_method" value="PATCH">'+
          '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'+
          '<button class="btn btn-link btn-sm" type="submit" data-tool="tooltip" title="'+toolBlockConvo+'"><i class="fas fa-user-times"></i></button>'+
          '</form>'+
          '</div>'+
          '<div class="profile-picture col-2">'+
                '<img src="'+__baseUrl+'/img/profile-pictures/'+data.sender.picture+'" alt="profile picture">'+
            '</div>'+
            '<div class="about col-10 '+is_new+'">'+
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
      $('#people-list').children('.list').prepend(html);
      $('[data-tool="tooltip"]').tooltip();

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