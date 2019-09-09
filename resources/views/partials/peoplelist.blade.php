<div class="people-list col-lg-4" id="people-list">
    <div class="search" style="text-align: center">
        <a href="{{route('home')}}" style="font-size:16px; text-decoration:none; color: white;"><i class="fa fa-user"></i> {{auth()->user()->name}}</a>
    </div>
    <ul class="list">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
        <li id="user-{{$inbox->withUser->id}}" class="clearfix">
            <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
            @if(auth()->user()->id == $inbox->thread->sender->id)
                <div class="about">
                    <div class="name">{{$inbox->withUser->name}}</div>
                    <div class="status">
                        <span class="fa fa-reply"></span>
                        <span>{{substr($inbox->thread->message, 0, 20)}}</span>
                        @if ($inbox->thread->is_seen)
                            <span class="fa fa-check"></span> 
                        @endif
                    </div>
                </div>
            @else
                <div class="about @if(!$inbox->thread->is_seen) new @endif">
                    <div class="name">{{$inbox->withUser->name}}</div>
                    <div class="status">
                        <span>{{substr($inbox->thread->message, 0, 20)}}</span>
                    </div>
                </div>
            @endif
            </a>
        </li>
            @endif
        @endforeach

    </ul>
</div>
