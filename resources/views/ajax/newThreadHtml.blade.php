@foreach($threads as $inbox)
    @if(!is_null($inbox->thread))
<li id="user-{{$inbox->withUser->id}}" class="clearfix">
    <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
    <div class="about">
        <div class="name">{{$inbox->withUser->name}}</div>
        <div class="status">
            <span class="fa fa-reply"></span>
        <span>{{substr($inbox->thread->message, 0, 20)}}</span>
        <span id="to-be-seen-thread" class="fa fa-check d-none"></span> 
        </div>
    </div>
    </a>
</li>
    @endif
@endforeach
