
@if (count($threads) > 0)
    @foreach($threads as $inbox)
        @if(!is_null($inbox->thread))
            <li data-id="{{$inbox->withUser->id}}" id="user-{{$inbox->withUser->id}}" class="clearfix thread row @if($inbox->withUser->status == 'online') activeUser @endif">
                <a class="row col-12" href="{{route('message.read', ['name'=>$inbox->withUser->name])}}">
                    <div class="threadForms col-12">
                        <form action="{{route('conversation.delete',['id'=>$inbox->withUser->id])}}" class="talkDeleteConversation float-left" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-link btn-sm" type="submit" data-tool="tooltip" title="{{__('chat.deleteConvoTool')}}"><i class="fas fa-times"></i></button>
                        </form>
                        <form action="{{route('conversation.block',['id'=>$inbox->withUser->id])}}" class="talkBlockConversation" method="POST">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-link btn-sm ml-2" type="submit" data-tool="tooltip" title="{{__('chat.blockConvoTool')}}"><i class="fas fa-user-times"></i></button>
                        </form>
                    </div>
                    <div class="profile-picture col-2">
                        <img src="{{asset('img/profile-pictures/'.$inbox->withUser->picture)}}" alt="profile picture">
                    </div>
                    @if(auth()->id() == $inbox->thread->sender->id)
                        <div class="about col-10">
                            <div class="name">
                                {{$inbox->withUser->name}}
                            </div>
                            <div class="status">
                                <span class="fa fa-reply"></span>
                                <span>
                                    @if ($inbox->thread->pictures)
                                        <i class="far fa-file-image"></i>
                                    @endif
                                    {!!nl2br(Str::limit($inbox->thread->message,20,'...'))!!}
                                </span>
                                @if ($inbox->thread->is_seen)
                                    <span class="fa fa-check"></span> 
                                @else
                                    <span id="to-be-seen-thread-{{$inbox->thread->conversation_id}}" class="fa fa-check d-none"></span> 
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="about @if(!$inbox->thread->is_seen) new @endif col-10">
                            <div class="name">{{$inbox->withUser->name}}</div>
                            <div class="status">
                                <span>
                                    @if ($inbox->thread->pictures)
                                        <i class="far fa-file-image"></i>
                                    @endif
                                    {!!nl2br(Str::limit($inbox->thread->message,20,'...'))!!}
                                </span>
                            </div>
                        </div>
                    @endif
                </a>
            </li>
        @endif
    @endforeach
@else
    <div class="noSearchResulsts">{{__('chat.noResults')}}</div>
@endif