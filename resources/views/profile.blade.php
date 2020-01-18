@extends('layouts.app')

@section('content')
    <div class="spinnerOverlay d-none">
        <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid row p-0">
        <div class="col-3 text-center profileData row">
            <div class="col-12 userName">
                <span>
                    <i class="fas fa-user"></i>
                    <span id="userName">{{$user->name}}</span>
                </span>
            </div>
            <div class="col-12 userPicture">
                <a href="{{asset('img/profile-pictures/'.$user->picture)}}" data-lightbox="Profile" data-title="{{__("profile.photo", ['user' => $user->name])}}">
                    <img src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="{{__("profile.photo", ['user' => $user->name])}}">
                </a>
            </div>
            <div class="col-12 userStatus">
                <span>
                    {{__('profile.lastActive')}}
                    @if ($user->status == "online")
                        <span style="font-weight: bold; color: lawngreen !important">{{{__('profile.active')}}}</span>
                    @else
                        {{$user->updated_at->diffForHumans()}}
                    @endif
                </span>
            </div>
            <div class="col-12 userData row">

                @if ($user->city_id)
                    <div class="col userDataCell">
                        <main>
                            {{$user->city->name}}
                        </main>
                    </div>
                @endif
                
                @if ($user->age)
                    <div class="col userDataCell">
                        <main>
                            {{$user->displayAge()}}
                        </main>
                    </div>
                @endif

                @if ($user->relationship_status != null)
                    <div class="col userDataCell">
                        <main>
                            @if ($user->relationship_status)
                                {{__('profile.status_taken')}}
                            @else
                                {{__('profile.status_free')}}
                            @endif
                        </main>
                    </div>
                @endif

            </div>
            <div class="col-12 userDesc">
                <span>
                    @if (str_word_count($user->description) > 25)
                        {{Illuminate\Support\Str::words($user->description, 25, "...")}}
                        <button class="col-4 mx-auto btn" data-toggle="modal" data-target="#expandInfoModal" data-content="desc" data-id="{{$user->id}}">{{__('profile.readMore')}}</button>
                    @else
                        {{$user->description}}
                    @endif
                </span>
            </div>
            @if($tags)
                <div class="col-12 userTags row">
                    @foreach ($tags as $tag)
                        <span class="col-3">
                            {{$tag}} 
                        </span>
                        @if ($loop->iteration == 6 && $loop->remaining > 0)
                            <button class="col-4 mx-auto btn" data-toggle="modal" data-target="#expandInfoModal" data-content="tags" data-id="{{$user->id}}">{{__('profile.moreContent',['remaining' => $loop->remaining])}}</button>
                            @break
                        @endif
                    @endforeach
                </div>
            @endif
            @if($friends)
                <div class="col-12 userFriends">
                    <button class="btn" data-tool="tooltip" title="{{__('profile.allFriends')}}" data-placement="bottom" data-toggle="modal" data-target="#expandInfoModal" data-content="friends" data-id="{{$user->id}}">
                        {{__('profile.closeFriends',['amount' => $friends])}}
                    </button>
                </div>
            @endif
            <div class="col-12 userButtons row">
                <div class="col text-center ico likeProfile">
                    <button class="btn likeUser @if(auth()->check()) likeBtn @endif @if($user->liked()) active @endif" data-id="{{$user->id}}" data-tool="tooltip" title="{{__('profile.likeUser')}}" data-placement="bottom">
                        <i class="fas fa-fire"></i>
                        <span class="badge likesAmount @if($user->likeCount <= 0) invisible @endif">
                            {{$user->likeCount}}
                        </span>
                    </button>
                </div>
                @if (auth()->check() && $user->id != auth()->id())
                    <div class="col ico">
                        <a href="{{route('message.read', ['name' => $user->name])}}" target="__blank">
                            <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.messageUser')}}" data-placement="bottom">
                                <i class="far fa-comment-dots"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col ico addFriend" data-name="{{$user->name}}" id="{{$user->name}}">
                            @if($user->isFriendWith(auth()->user()))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend3')}}" data-placement="bottom">
                                    <i class="active fas fa-user-friends"></i>
                                </button>
                            @elseif(auth()->user()->hasSentFriendRequestTo($user))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend2')}}" data-placement="bottom">
                                    <i class="active fas fa-user-check"></i>
                                </button>
                            @else
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend1')}}" data-placement="bottom">
                                    <i class="active fas fa-user-plus"></i>
                                </button>
                            @endif
                    </div>
                    <div class="col ico">
                        <button class="btn reportBtn text-reset" data-name="{{$user->name}}" data-tool="tooltip" title="{{__('profile.reportUser')}}" data-placement="bottom">
                            <i class="fas fa-exclamation"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-center col-md-9 col-sm-12 container activity">
            @if (session()->has('guest'))
                <div class="alert alert-warning" role="alert" style="width: 100%; align-self:center;">
                    <b>{{session()->get('guest')}}</b>
                </div>
            @else
                <header>
                    {{__('profile.activityHeader')}}
                </header>
                <div class="activityPosts"> 
                    @include('partials.profile.userActivity')
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="expandInfoModal" tabindex="-1" role="dialog" aria-labelledby="expandInfoModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{__('profile.modalTitle')}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var base_url                = "{{url('/')}}";
        var reportUser              = "{{__('searcher.reportUser')}}";
        var deletePostMsg           =  "{{__('activityWall.deletePost')}}";
        var reportUserReason        = "{{__('searcher.reportUserReason')}}";
        var reportUserReasonErr     = "{{__('searcher.reportUserReasonErr')}}";
        var reportUserSuccess       = "{{__('searcher.reportUserSuccess')}}";

        var userName                = "{{$user->name}}"
    </script>
    <script src="{{asset('js/profile.js')}}"></script>
@endpush