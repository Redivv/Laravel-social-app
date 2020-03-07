@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.culture')}}   -    {{$cultureItem->name}}
    </title>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

<div class="container-fluid culture_page-container">
    <div class="item-container">
        @auth
            @if (auth()->user()->isAdmin())
                <div class="col-12 adminButtons">
                    <a href="{{route('adminCulture')."?elementType=cultureItem&elementId=".$cultureItem->id}}" data-tool="tooltip" title="{{__('admin.edit')}}" data-placement="bottom">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="cultureItem" action="#" class="deleteItem">
                        @method('delete')
                        <input type="hidden" name="elementId" value="{{$cultureItem->id}}">
                        <button class="btn" type="submit" data-tool="tooltip" title="{{__('admin.delete')}}" data-placement="bottom">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @endif
        @endauth
        <section id="item_header" class="row itemHeader text-center">
            <figure class="col-md-2  thumbnail">
                @if($thumb = json_decode($cultureItem->thumbnail)[0])
                    <a href="{{asset('img/culture-pictures/'.$thumb)}}" data-lightbox="Item" data-title="{{$cultureItem->name}}">
                        <img src="{{asset('img/culture-pictures/'.$thumb)}}" alt="thumbnail">
                    </a>
                @endif
            </figure>
            <hgroup class="col-md-4 row ">
                <h3 class="col-12 itemTitle">
                    {{$cultureItem->name}}
                </h3>
                @if ( ($attrLabels = json_decode($cultureItem->category->attributes)) && ($attrValues = json_decode($cultureItem->attributes)))
                    @foreach ($attrLabels as $key => $label)
                        @if (isset($attrValues[$key]))
                            <p class="col itemAttr">
                                <span class="font-weight-bold">{{$label}}</span>: {{$attrValues[$key]}}
                            </p>
                        @endif
                    @endforeach    
                @endif
            </hgroup>
            <div class="col-md-4  tagHolder container row">
                @if($tags = $cultureItem->tagNames())
                    @foreach ($tags as $tag)
                        <a class="col tag" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom" href="{{route('culture.searchResults').'?titleName=&itemTags[]=&itemTags[]='.$tag.'&options=likesSort&sortOptionsDir=desc'}}">
                            <p># {{$tag}}</p>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="col-md-2 likeItem text-center ico ">
                @auth
                    <button class="btn likeBtn cultureLikeBtn @if($cultureItem->liked()) active @endif" data-id="{{$cultureItem->id}}" data-tool="tooltip" title="{{__('culture.likeItem')}}" data-placement="bottom">
                        <i class="fas fa-fire fa-5x"></i>
                        <span class="badge badge-pill likesCount @if($cultureItem->likeCount<=0 ) invisible @endif">
                            {{$cultureItem->likeCount}}
                        </span>
                    </button>
                @else
                <button class="btn cultureLikeBtn">
                    <i class="fas fa-fire fa-5x"></i>
                    <span class="badge badge-pill likesCount @if($cultureItem->likeCount <=0 ) invisible @endif">
                        {{$cultureItem->likeCount}}
                    </span>
                </button>

                @endauth
            </div>
        </section>
        <div class="row">
            <section id="description" class="col-md-12 description">
                <p>
                    {!!$cultureItem->description!!}
                </p>
            </section>
            
            <section id="picture_gallery" class="col-md-12 pictures row centeredItems">
                @if ($pictures = json_decode($cultureItem->pictures))
                @foreach ($pictures as $picture)
                        <div class="col itemPicture">
                            <a href="{{asset('img/culture-pictures/'.$picture)}}" data-lightbox="Item Pictures">
                                <img  src="{{asset('img/culture-pictures/'.$picture)}}" alt="Culture Picture">
                            </a>
                        </div>
                    @endforeach
                @endif
            </section>
        </div>
        <hr>
        <section id="itemReview" class="row  review">
            <h4 class="sectionTitle">
                {{__('admin.itemReview')}}
            </h4>
            @if ($cultureItem->review)
                <button type="button" class="btn reviewBtn" data-toggle="modal" data-target="#reviewModal" data-itemid="{{$cultureItem->id}}">
                    <h5>{{__('culture.displayReview')}}</h5>
                </button>
                @include('partials.culture.reviewModal')
            @else
                <h5 class="noReview">
                    {{__('culture.noReview')}}
                </h5>
            @endif
        </section>
        <hr>
        @if (count($similarEntries) > 0)
            <section class="similar-entries">
                <h4 class="sectionTitle">{{__('culture.similarItems')}}</h4>
                <output id="simmilarItems" class="row">
                    @foreach ($similarEntries as $entry)
                    <a class="simmilarItem col container row" href="{{route('culture.read',['cultureItem' => $entry->name_slug])}}">
                        <figure class="col-12 itemThumb">
                            @if ($pic = json_decode($entry->thumbnail)[0])  
                                <img src="{{asset('img/culture-pictures/'.$pic)}}" alt="item thumbnail">
                            @endif
                        </figure>
                        <figcaption class="col-12">
                            <h5 class="suggestedTitle">
                                {{$entry->name}}
                            </h5>
                        </figcaption>
                    </a>
                    @endforeach
                </output>
            </section>
            <hr>
        @endif
        <section class="row  itemComments">
            <h4 class="sectionTitle">{{__('profile.comment')}}</h4>
            <form class="commentsForm" data-id="{{$cultureItem->id}}" method="post">
                <div class="input-group row">
                    <input type="text" name="commentDesc" class="form-control commentsDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">
                    <div class="input-group-append col-1 commentButtons">
                        <i class="fas fa-user-tag commentUserTag" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" title="{{__('activityWall.tagUser')}}" data-placement="bottom"></i>
                    </div>
                </div>
                <output id="commentUserTags" class="row"></output>
            </form>
            <output class="commentsFeed"  id="feed-{{$cultureItem->id}}">
                @if(count($cultureItem->comments) > 0) 
                    @include('partials.culture.itemComments')
                @endif
            </output>
        </section>
    </div>
</div>

@include('partials.home.commentEditModal')

@include('partials.home.tagUsersModal')

@endsection

@push('styles')
    <style>
        .navCulture > .nav-link{
            color: #f66103 !important;
        }
        .modal-backdrop{
            z-index: 998 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush

@push('scripts')
    <script>
        var baseUrl= "{{url('/')}}";
        var savedChanges        =  "{{__('profile.savedChanges')}}";
        var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
        var confirmMsg          =  "{{__('admin.confirmMsg')}}";
        var badFileType             =  "{{__('chat.badFileType')}}";
        var deleteImages            =  "{{__('activityWall.deleteImages')}}";
        var deleteTags              =  "{{__('activityWall.deleteTags')}}";
        var deletePostMsg           =  "{{__('activityWall.deletePost')}}";
        var emptyCommentMsg         =  "{{__('activityWall.emptyComment')}}";
        var deleteCommentMsg        =  "{{__('activityWall.deleteComment')}}";
        var userNotFound            =  "{{__('activityWall.noUserFound')}}";
        var deleteUserTag           =  "{{__('activityWall.deleteTaggedUser')}}";
        var emptyUser               =  "{{__('activityWall.emptyUser')}}";
        var tagUserMessage          =  "{{__('activityWall.tagUser')}}";
    </script>

    <script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>
    <script src="{{asset('js/emoji.js')}}"></script>

    <script src="{{asset('js/culture.js')}}"></script>

    <script>
        $('.commentsDesc:first').emojioneArea({
            pickerPosition: "top",
            placeholder: "Napisz Komentarz",
            inline: false,
            events: {
                keypress: function(editor,e) {
                    if (e.keyCode == 13 || e.which == 13) {
                        e.preventDefault();
                        editor.parent().prev().val(this.getText());
                        editor.parent().prev().parent().submit(); 
                    }
                }
            }
        });
    </script>
@endpush