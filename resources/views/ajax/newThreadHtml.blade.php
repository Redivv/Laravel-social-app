@foreach($threads as $inbox)
    @if(!is_null($inbox->thread))
<li id="user-{{$inbox->withUser->id}}" class="clearfix">
    <form action="{{route('conversation.delete',['id'=>$inbox->withUser->id])}}" class="talkDeleteConversation float-left" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-link btn-sm" type="submit"><i class="fas fa-times"></i></button>
    </form>
    <form action="{{route('conversation.block',['id'=>$inbox->withUser->id])}}" class="talkBlockConversation" method="POST">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="btn btn-link btn-sm" type="submit"><i class="fas fa-user-times"></i></button>
    </form>
    <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
    <div class="about">
        <div class="name">{{$inbox->withUser->name}}</div>
        <div class="status">
            <span class="fa fa-reply"></span>
            <span>
                @if ($inbox->thread->pictures)
                    <i class="far fa-file-image"></i>
                @endif
                {{substr($inbox->thread->message, 0, 20)}}
            </span>
            <span id="to-be-seen-thread" class="fa fa-check d-none"></span> 
        </div>
    </div>
    </a>
</li>
    @endif
@endforeach
