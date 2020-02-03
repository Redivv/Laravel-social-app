@foreach($messages as $message)
@if($message->sender->id == auth()->user()->id)
<li class="authMessage row" id="message-{{$message->id}}">
    <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" data-tool="tooltip" title="{{__('chat.deleteMessageTool')}}"><i class="fas fa-times"></i></a>
    <div class="message-data col-12">
        <span class="message-data-time">{{$message->humans_time}} {{__('chat.time')}}</span> &nbsp; &nbsp;
        <span class="message-data-name">{{$message->sender->name}}</span>
    </div>
    <div class="message my-message col-12">
        @if ($message->pictures)
        <output class="messagePictures">
            @foreach (json_decode($message->pictures) as $picture)
                <a href="{{asset('img/message-pictures/'.$picture)}}" data-lightbox="message-{{$message->id}}" data-title="{{__('chat.messagePicture')}}">
                    <img class="picture" src="{{asset('img/message-pictures/'.$picture)}}" alt="Message Picture">
                </a>
            @endforeach  
        </output>
        @endif
        @if (!empty($message->message))
            <div class="messageText">
                <p>{!!nl2br($message->toHtmlString()->toHtml())!!}</p>
            </div>
        @endif
    </div>
    <div class="clearfix seenInfo seen-info-{{$message->conversation_id}} @if(!$message->is_seen) d-none @endif">
        <span>{{__('chat.seen')}}</span>
    </div>
</li>
@else
<li class="clearfix" id="message-{{$message->id}}">
    <div class="message-data col-12">
        <span class="message-data-name">{{$message->sender->name}}</span>
        <span class="message-data-time">{{$message->humans_time}} {{__('chat.time')}}</span> &nbsp; &nbsp;
    </div>
    <div class="message other-message">
        @if ($message->pictures)
        <output class="messagePictures">
            @foreach (json_decode($message->pictures) as $picture)
                <a href="{{asset('img/message-pictures/'.$picture)}}" data-lightbox="message-{{$message->id}}" data-title="{{__('chat.messagePicture')}}">
                    <img class="picture" src="{{asset('img/message-pictures/'.$picture)}}" alt="Message Picture">
                </a>
            @endforeach  
        </output>
        @endif
        @if (!empty($message->message))
            <div class="messageText">
                <p>{!!nl2br($message->toHtmlString()->toHtml())!!}</p>
            </div>
        @endif
    </div>
</li>
@endif
@endforeach