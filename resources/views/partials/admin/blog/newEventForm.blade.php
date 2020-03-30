@if ($elementType === "post" && $element)
<div class="returnBtn">
    <a class="btn form-btn" href="{{route('adminBlog')}}">
      {{__('admin.back')}}
    </a>
</div>
<form id="newPostForm" method="post" enctype="multipart/form-data">
    @method('put')
    <div class="form-group">
        <label for="postName">{{__('admin.postName')}}</label>
        <input type="text" class="postName form-control" name="postName" id="postName" value="{{$element->name}}" required>
    </div>
    <div class="form-group">
        <label class="d-block" for="postCategory">{{__('admin.itemCategory')}}</label>
        <input id="postCategory" class="postCategory form-control" name="postCategory" value="{{$element->category->name}}">
    </div>
    <div class="form-group">
        <label class="d-block" for="postThumbnail">{{__('admin.itemThumbnail')}}</label>
        <input type="file" name="postThumbnail" class="postThumbnail form-control-file" id="postThumbnail" accept="image/*" >
        <output id="postThumbnail-out">
            <a href="{{asset('img/blog-pictures/'.json_decode($element->thumbnail)[0])}}" data-lightbox="previewThumb">
                <img src="{{asset('img/blog-pictures/'.json_decode($element->thumbnail)[0])}}" alt="Post Thumbnail">
            </a>
        </output>
    </div>
    <div class="form-group">
        <label class="d-block" for="postTags">{{__('admin.itemTags')}}</label>
        <div class="input-group">
            <input class="postTags form-control" id="postTags" name="postTags[]">
            <div class="input-group-append">
                <button class="btn" type="button" id="addTagBtn">{{__('searcher.add')}}</button>
            </div>
        </div>
        <output class="row" id="postTags-out">
            @if ($tags = $element->tagNames())
                @foreach ($tags as $tag)
                <div class="col postTag" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteTags')}}">
                    <span>{{$tag}}</span>
                    <input type="hidden" name="postTags[]" value="{{$tag}}">
                </div>
                @endforeach
            @endif
        </output>
    </div>
    <div class="form-group postDesc-box">
        <label class="d-block" for="postDesc">{{__('profile.desc')}}</label>
        <textarea name="postDesc" class="postDesc form-control" id="postDesc">{!!$element->description!!}</textarea>
    </div>
    <input type="hidden" name="postId" value="{{$element->id}}">
    <button class="btn btn-block newPostButton" type="submit">{{__('searcher.add')}}</button>
</form>
@else
<form id="newPostForm" method="post" enctype="multipart/form-data">
    @method('put')
    <div class="form-group">
        <label for="postName">{{__('admin.postName')}}</label>
        <input type="text" class="postName form-control" name="postName" id="postName" required>
    </div>
    <div class="form-group">
        <label class="d-block" for="postCategory">{{__('admin.itemCategory')}}</label>
        <input id="postCategory" class="postCategory form-control" name="postCategory" required>
    </div>
    <div class="form-group">
        <label class="d-block" for="postThumbnail">{{__('admin.itemThumbnail')}}</label>
        <input type="file" name="postThumbnail" class="postThumbnail form-control-file" id="postThumbnail" accept="image/*" required>
        <output id="postThumbnail-out"></output>
    </div>
    <div class="form-group">
        <label class="d-block" for="postTags">{{__('admin.itemTags')}}</label>
        <div class="input-group">
            <input class="postTags form-control" id="postTags" name="postTags[]">
            <div class="input-group-append">
                <button class="btn" type="button" id="addTagBtn">{{__('searcher.add')}}</button>
            </div>
        </div>
        <output class="row" id="postTags-out">
        </output>
    </div>
    <div class="form-group postDesc-box">
        <label class="d-block" for="postDesc">{{__('profile.desc')}}</label>
        <textarea name="postDesc" class="postDesc form-control" id="postDesc" required></textarea>
    </div>

    <button class="btn btn-block newPostButton" type="submit">{{__('searcher.add')}}</button>
</form>
@endif