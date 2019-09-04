<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
      <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Chat</title>
    
    
    <link rel="stylesheet" href="{{asset('chat/css/reset.css')}}">

    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>

        <link rel="stylesheet" href="{{asset('chat/css/style.css')}}">

    
    
    
  </head>

  <body>
    <div class="container clearfix body">
   @include('partials.peoplelist')
    
    <div class="chat">
      <div class="chat-header clearfix">
        <div class="chat-about">
            @if(isset($user))
                <div class="chat-with">{{@$user->name}}</div>
            @else
                <div class="chat-with">No Thread Selected</div>
            @endif
        </div>
      </div> <!-- end chat-header -->
      
      @yield('content')
      
      @if(isset($user))
        <div class="chat-message clearfix">
          <form action="" method="post" id="talkSendMessage">
                <textarea name="message-data" id="message-data" placeholder ="Type your message" rows="3"></textarea>
                <input type="hidden" name="_id" value="{{@request()->route('id')}}">
                <button type="submit">Send</button>
          </form>
        </div> <!-- end chat-message -->
      @endif
      
    </div> <!-- end chat -->
    
  </div> <!-- end container -->


      <script>
          var __baseUrl = "{{url('/')}}";
      </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="{{asset('chat/js/talk.js')}}"></script>

    <script>
        var show = function(data) {
            alert(data.sender.name + " - '" + data.message + "'");
        }

        var msgshow = function(data) {
            if($("div.chat-with").text() == data.sender.name){
              var html = '<li id="message-' + data.id + '">' +
              '<div class="message-data">' +
              '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
              '<span class="message-data-time">1 Second {{__("chat.time")}}</span>' +
              '</div>' +
              '<div class="message my-message">' +
              data.message +
              '</div>' +
              '</li>';
              $('#talkMessages').append(html);
            }

            html = '<li id="user-'+data.sender.id+'" class="clearfix">'+
              '<a href="/message/'+data.sender.id+'">'+
                '<div class="about">'+
                  '<div class="name">'+data.sender.name+'</div>'+
                  '<div class="status">'+
                   '<span>'+data.message.substring(0,20)+'</span>'+
                  '</div>'
               '</div>'
              '</a>'
            '</li>';

            var $thread = $('#user-'+data.sender.id);
            if($thread.length)
                $('#user-'+data.sender.id).remove();
            $('#people-list .list').prepend(html);

            console.log(data);
        }

    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}

  </body>
</html>
