@if (count($posts) > 0)
    @foreach ($posts as $post)
        <div class="postBox container-fluid">
            <article id="post{{$post->id}}" class="post">
                <header class="postAuthor row">
                    <div class="col-1">
                        <a href="{{route('ProfileOtherView',['user' => $post->user->name])}}">
                            <img class="postAuthorPicture" src="{{asset('img/profile-pictures/'.$post->user->picture)}}">
                        </a>
                    </div>
                    <div class="col-6 postAuthorName">
                        <a class="postAuthorLink" href="{{route('ProfileOtherView',['user' => $post->user->name])}}">
                            {{$post->user->name}}@if($post->user->is_admin)<small class="text-muted adminStatus">{{__('activityWall.adminStatus')}}</small>@endif
                        </a>
                    </div>
                    @if ($post->user_id == auth()->user()->id)
                        <div class="col-4 postAuthorButtons">
                            <a href="{{route("viewPost",['post' => $post->id])}}" target="__blank"><i class="fas postEdit fa-edit"></i></a>
                            <i class="fas postDelete fa-times" data-id="{{$post->id}}"></i>
                        </div>
                    @endif
                </header>
                <main class="postDesc row">
                    <div class="postPhotos col-12">
                        @if ($pictures = json_decode($post->pictures))
                            @foreach ($pictures as $picture)
                                @if ($loop->iteration == 4)   
                                <div class="mt-2"> 
                                    <a class="morePhotos" href="{{route('viewPost',['post' => $post->id])}}" target="__blank">Pozostałe Zdjęcia ({{$loop->remaining+1}})</a>
                                </div>
                                @break
                                @else
                                    <a href="{{asset('img/post-pictures/'.$picture)}}" data-lightbox="post{{$post->id}}-Pictures">
                                        <img class="postPicture" src="{{asset('img/post-pictures/'.$picture)}}">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="postContent col-12">
                        @switch($post->type)
                            @case('newPicture')
                                @if ($taggedUsers = json_decode($post->tagged_users))
                                    <a href="{{route('ProfileOtherView',['user' => $taggedUsers[0]])}}" target="__blank">{{$taggedUsers[0]}}</a> {{__('activityWall.friendNewPicture')}}
                                @endif
                                @break
                            @case('newFriend')
                                @if ($taggedUsers = json_decode($post->tagged_users))
                                    <a href="{{route('ProfileOtherView',['user' => $taggedUsers[0]])}}" target="__blank">{{$taggedUsers[0]}}</a>
                                    {{__('activityWall.newFriend')}}
                                    <a href="{{route('ProfileOtherView',['user' => $taggedUsers[1]])}}" target="__blank">{{$taggedUsers[1]}}</a>
                                @endif
                                @break
                            @case('newPartner')
                                @if ($taggedUsers = json_decode($post->tagged_users))
                                    <a href="{{route('ProfileOtherView',['user' => $taggedUsers[0]])}}" target="__blank">{{$taggedUsers[0]}}</a>
                                    {{__('activityWall.newPartner')}}
                                    <a href="{{route('ProfileOtherView',['user' => $taggedUsers[1]])}}" target="__blank">{{$taggedUsers[1]}}</a>
                                @endif
                                @break
                            @default
                                @php
                                    $regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
                                    preg_match_all($regex, e($post->desc), $postLinks);
                                    if (isset($postLinks)) {
                                        $embedLinks = array();
                                        foreach ($postLinks[0] as $link) {
                                            $data = CachedEmbed::create($link);
                                            if ($data->code) {
                                                $embedLinks[] = $data->code;
                                            }else{
                                                $html = "<a href='".$link."' target='__blank'>".$link."</a>";
                                                $embedLinks[] = $html;
                                            }
                                        }
                                        echo nl2br(preg_replace_array($regex, $embedLinks, e($post->desc)));
                                    }
                                @endphp
                        @endswitch
                    </div>
                    @if ($post->type == "default")
                        <div class="postTags col-12 row">
                            @if ($taggedUsers = json_decode($post->tagged_users))
                                @foreach ($taggedUsers as $tags)
                                    <a href="{{route('ProfileOtherView',['user' => $tags])}}" class="col-4 postTaggedUser" target="__blank">
                                        <span class="taggedUserLabel">{{$tags}}</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </main>
                <footer class="postFooter row">
                    <button class="col-5 btn btn-block likePostButton @if($post->liked()){{"active"}}@endif" data-id="{{$post->id}}"><i class="fas fa-fire"></i><span class="badge likesCount badge-pill badge-warning">@if($post->likeCount != 0){{$post->likeCount}}@endif</span> {{__('activityWall.like')}}</button>
                    <a class="col-5 btn btnComment btn-block" href="{{route("viewPost",['post' => $post->id])}}" target="__blank">
                        <i class="far fa-comments"></i>
                        <span class="badge postCommentsCount badge-pill badge-warning">@if(count($post->comments) > 0){{count($post->comments)}}@endif</span>
                        {{__('profile.comment')}}
                    </a>
                </footer>
            </article>
            <hr>
        </div>
    @endforeach
@else
    <div class="noActivity">
        {{__('profile.noActivity')}}
    </div>
@endif