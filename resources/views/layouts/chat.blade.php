@extends('layouts.app')

@section('content')
  <div class="darkOverlay d-none"></div>

  <div class="container p-0 clearfix chatBody row">
    @include('partials.peoplelist')

    <div class="chat col-9">
      <div class="chat-header clearfix">
        <div class="chat-about">
            @if(isset($user))
                <div class="chat-with">{{$user->name}}</div>
                <div data-id="{{$user->id}}" id="status" class="text-muted">
                    @if ($user->status == "online")
                      <span style="font-weight: bold; color: lawngreen !important">{{__('chat.active')}}</span>
                    @else
                      {{__('chat.lastActive')}} {{$user->updated_at->diffForHumans()}}
                    @endif
                </div>
            @else
                <div class="chat-with">{{__('chat.noThread')}}</div>
            @endif
        </div>
      </div> <!-- end chat-header -->
      
      @yield('contentChat')

      <hr>

      @if(isset($user))
        <div class="chat-message clearfix">
          <form action="" method="post" id="talkSendMessage"class="message-box" enctype="multipart/form-data">
                <label for="upload-pictures"><i class="far fa-images"></i></label>
                <input id="upload-pictures" class="d-none" name="pictures[]" type="file" accept="image/*" multiple>
                <output id="picture-preview"></output>
                <textarea style="border-color: Transparent !important;" name="message-data" id="message-data" placeholder ="{{__('chat.placeholder')}}" rows="3"></textarea>
                <input type="hidden" name="_id" value="{{$sender}}">
                <button class="btn" type="submit"><i class="fas fa-paper-plane"></i></button>
          </form>
        </div> <!-- end chat-message -->
      @endif
      
    </div> <!-- end chat -->

    <button id="showPeopleList" class="btn"><i class="fas fa-arrow-left"></i></button>

  </div> <!-- end container -->
    
@endsection

@push('scripts')
  <script>
      var audioElement  = document.createElement('audio');
      var __baseUrl     = "{{url('/')}}";
      var deleteConvo   = "{{__('chat.deleteConvo')}}";
      var deleteMessage = "{{__('chat.deleteMessage')}}";
      var blockConvo    = "{{__('chat.blockConvo')}}";
      var badFileType   = "{{__('chat.badFileType')}}";

      var pagi            = 0;
      var pagi_convo      = 0;
      var stop_pagi       = false;
      var stop_pagi_convo = false;
  </script>
  
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
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

    var active_id = new Array();
    Echo.join('online')
      .joining((user) => {
          axios.patch('/api/user/'+ user.name +'/online', {
                  api_token : user.api_token
          });
      })

      .leaving((user) => {
          axios.patch('/api/user/'+ user.name +'/offline', {
              api_token : user.api_token
          });
      })

      .listen('UserOnline', (e) => {
          $('#user-'+e.user.id).addClass('activeUser');
          if (e.user.id == $('#status').data('id')) {
              $('#status').html('<span style="font-weight: bold; color: lawngreen !important">{{__("chat.active")}}</span>');
          }
      })

      .listen('UserOffline', (e) => {
          $('#user-'+e.user.id).removeClass('activeUser');
          if (e.user.id == $('#status').data('id')) {
              $('#status').html('{{__("chat.lastActive1sec")}}');
          }
      });
  </script>
  <script src="{{asset('js/emoji.js')}}"></script>
  <script src="{{asset('chat/js/talk.js')}}"></script>
  {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}
    
@endpush
