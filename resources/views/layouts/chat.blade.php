<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    @include('partials.favicon')
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Chat</title>
    <script>
      window.Laravel = {!! json_encode([
          'user' => auth()->check() ? auth()->user()->id : null,
      ]) !!};
    </script>
    
    <link rel="stylesheet" href="{{asset('chat/css/reset.css')}}">

    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>

    <link rel="stylesheet" href="{{asset('chat/css/chat.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    
    
    
  </head>

  <body>
    <div class="container p-0 clearfix body">
   @include('partials.peoplelist')
    
    <div class="chat col-lg-8">
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
                <textarea name="message-data" id="message-data" placeholder ="{{__('chat.placeholder')}}" rows="3"></textarea>
                <input type="hidden" name="_id" value="{{@request()->route('id')}}">
                <button type="submit">{{__('chat.send')}}</button>
          </form>
        </div> <!-- end chat-message -->
      @endif
      
    </div> <!-- end chat -->
    
  </div> <!-- end container -->


      <script>
          var __baseUrl = "{{url('/')}}";
          var audioElement = document.createElement('audio');
          var deleteConvo = "{{__('chat.deleteConvo')}}";
          var deleteMessage = "{{__('chat.deleteMessage')}}";
          var pagi = 0;
          var stop_pagi = false;
      </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src={{asset('js/app.js')}}></script>
    <script src="{{asset('chat/js/talk.js')}}"></script>
    <script src="{{asset('chat/js/functions.js')}}"></script>

    <script>
        var focus_status = true;
        $(window).focus(function() {
          focus_status = true;
          makeAllMessagesSeen(window.Laravel.user);
        }).blur(function() {
          focus_status = false;
        });

        var new_messages = 0;
        var title = $(document).prop('title');

        var newmsg = function(data) {
            new_messages++;
            console.log(focus_status);
            $(document).prop('title', '('+new_messages+') '+title);
            playSound('{{asset("chat/new_message.mp3")}}', audioElement);
            if($("div.chat-with").text() == data.sender.name){
              addNewMessage(data,'{{__("chat.second")}}','{{__("chat.time")}}');
              updateThreads(data);
              if(focus_status){
                makeOneMessageSeen(data);
              }

            }else{
              updateThreads(data,'new');
            }
        }

        Echo.private(`seen.` + window.Laravel.user)
          .listen('MessagesWereSeen', (e) => {
            $('.seen_info').removeClass('d-none');

            $('#to-be-seen-thread').removeClass('d-none');
            $('#to-be-seen-thread').removeAttr('id');
        });
    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}

  </body>
</html>
