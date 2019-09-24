<div class="people-list col-lg-4" id="people-list">
    <div class="search" style="text-align: center">
        <a href="{{route('home')}}" style="font-size:16px; text-decoration:none; color: white;"><i class="fa fa-user"></i> {{auth()->user()->name}}</a>
    </div>
    <ul class="list">
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
            <div class="profile-picture">
                <img src="{{asset('img/profile-pictures/'.$inbox->withUser->picture)}}" alt="profile picture">
            </div>
            @if(auth()->user()->id == $inbox->thread->sender->id)
                <div class="about">
                    <div class="name">
                        {{$inbox->withUser->name}}
                    </div>
                    <div class="status">
                        <span class="fa fa-reply"></span>
                        <span>
                            @if ($inbox->thread->pictures)
                                <i class="far fa-file-image"></i>
                            @endif
                            {{substr($inbox->thread->message, 0, 20)}}
                        </span>
                        @if ($inbox->thread->is_seen)
                            <span class="fa fa-check"></span> 
                        @else
                            <span id="to-be-seen-thread-{{$inbox->thread->conversation_id}}" class="fa fa-check d-none"></span> 
                        @endif
                    </div>
                </div>
            @else
                <div class="about @if(!$inbox->thread->is_seen) new @endif">
                    <div class="name">{{$inbox->withUser->name}}</div>
                    <div class="status">
                        <span>
                            @if ($inbox->thread->pictures)
                                <i class="far fa-file-image"></i>
                            @endif
                            {{substr($inbox->thread->message, 0, 20)}}
                        </span>
                    </div>
                </div>
            @endif
            </a>
        </li>
            @endif
        @endforeach

    </ul>
</div>
