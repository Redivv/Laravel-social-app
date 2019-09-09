<li class="clearfix" id="message-{{$message->id}}">
    <div class="message-data align-right">
        <span class="message-data-time" >{{$message->humans_time}} {{__('chat.time')}}</span> &nbsp; &nbsp;
        <span class="message-data-name" >{{$message->sender->name}}</span>
        <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
    </div>
    <div class="message other-message float-right">
        {{$message->message}}
    </div>
    <div class="seen_info clearfix d-none"><span>{{__('chat.seen')}}</span></div>
</li>
