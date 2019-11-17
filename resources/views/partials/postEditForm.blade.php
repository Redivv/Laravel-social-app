<form id="editPost" method="post">
    <output id="modalPicture-preview">
        @if ($pictures = json_decode($post->pictures))
            @foreach ($pictures as $picture)
                <img class="thumb" src="{{asset('img/post-pictures/'.$picture)}}">
            @endforeach
        @endif
    </output>
    <textarea id="editPostDesc" name="postDesc">{{$post->desc}}</textarea>
    <div class="friendsWallButtons">
        <span class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}"><i class="fas fa-user-tag"></i></span>
        <label for="editPicture" class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.addImage')}}"><i class="far fa-image"></i></label>
        <input type="file" class="d-none" name="editPicture[]" accept="image/*" id="editPicture" multiple>
    </div>
    <input type="hidden" value="{{$post->id}}" name="postId">
    <div class="friendsWallSendButton">
        <button name="sendPost" id="editPostButton" type="submit" class="btn btn-block">{{__('activityWall.editPost')}}</button>
    </div>
</form>