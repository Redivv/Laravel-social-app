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
    <link rel="stylesheet" href="{{asset('chat/css/chat.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src={{asset('js/app.js')}}></script>

    
    
    
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
          <form action="" method="post" id="talkSendMessage" enctype="multipart/form-data">
                <label for="upload-pictures"><i class="far fa-images"></i></label>
                <input id="upload-pictures" class="d-none" name="pictures[]" type="file" accept="image/*" multiple>
                <output id="picture-preview"></output>
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
          var blockConvo = "{{__('chat.blockConvo')}}";
          var pagi = 0;
          var stop_pagi = false;
      </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="{{asset('chat/js/talk.js')}}"></script>
    <script src="{{asset('chat/js/functions.js')}}"></script>

    <script>
        var focus_status = true;

        $(window).focus(function() {
          
          focus_status = true;
          makeAllMessagesSeen('{{$sender}}');
        }).blur(function() {
          focus_status = false;
        });

        var new_messages = 0;
        var title = $(document).prop('title');

        var newmsg = function(data) {
            new_messages++;
            $(document).prop('title', '('+new_messages+') '+title);
            playSound('{{asset("chat/new_message.mp3")}}', audioElement);
            if($("div.chat-with").text() == data.sender.name){
              addNewMessage(data.id);
              $( "div.chat-history" ).scrollTop($('div.chat-history').prop('scrollHeight'));
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
            $('.seen-info-'+e.conversation_id).removeClass('d-none');

            $('#to-be-seen-thread-'+e.conversation_id).removeClass('d-none');
            $('#to-be-seen-thread-'+e.conversation_id).removeAttr('id');
        });

        Echo.join('online')
          .here((users) => {
              this.active_id = new Array();
              users.forEach(function(us){
                  active_id.push(us.id);
              })
                  let active_idCopy = active_id;
                  $('li.thread').each(function(){
                      if (active_idCopy.length > 1) {
                          if (active_idCopy.includes($(this).data('id'))) {
                              $(this).addClass('activeUser');
                              active_idCopy = active_idCopy.filter(u => (u !== $(this).data('id')));
                          }
                          console.log(active_id);
                      }else{
                          return false;
                      }
                  })
          })
          .joining((user) => {
              this.active_id.push(user.id);
              $('li.thread[data-id="'+user.id+'"]').addClass('activeUser');
          })
          .leaving((user) => {
          this.active_id = this.active_id.filter(u => (u !== user.id));
          $('li.thread[data-id="'+user.id+'"]').removeClass('activeUser');
          })
    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}

  </body>
</html>
