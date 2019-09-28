@extends('layouts.chat')

@section('content')
    <div class="chat-history">
        <ul id="talkMessages">
            @foreach($messages as $message)
                @if($message->sender->id == auth()->user()->id)
                    <li class="clearfix" id="message-{{$message->id}}">
                        <div class="message-data align-right">
                            <span class="message-data-time" >{{$message->humans_time}} {{__('chat.time')}}</span> &nbsp; &nbsp;
                            <span class="message-data-name" >{{$message->sender->name}}</span>
                            <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fas fa-times"></i></a>
                        </div>
                        <div class="message other-message float-right">
                            @if ($message->pictures)
                                @foreach (json_decode($message->pictures) as $picture)
                                    <img class="picture" src="{{asset('img/message-pictures/'.$picture)}}">
                                @endforeach  
                            @endif
                            {!!nl2br($message->toHtmlString()->toHtml())!!}
                        </div>
                        @if ($message->is_seen)
                            <div class="clearfix"><span>{{__('chat.seen')}}</span></div>
                        @endif
                    </li>
                @else
                    <li id="message-{{$message->id}}">
                        <div class="message-data">
                            <span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fas fa-times" style="margin-right: 3px;"></i></a>{{$message->sender->name}}</span>
                            <span class="message-data-time">{{$message->humans_time}} {{__('chat.time')}}</span>
                        </div>
                        <div class="message my-message">
                            @if ($message->pictures)
                                @foreach (json_decode($message->pictures) as $picture)
                                    <img class="picture" src="{{asset('img/message-pictures/'.$picture)}}">
                                @endforeach  
                            @endif
                            {!!nl2br($message->toHtmlString()->toHtml())!!}
                        </div>
                    </li>
                @endif
            @endforeach


        </ul>

    </div> <!-- end chat-history -->

@endsection
