<form id="editPost" method="post">
    <output id="modalPicture-preview">
        @if ($pictures = json_decode($post->pictures))
            <div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>
            @foreach ($pictures as $picture)
                <img class="thumb" src="{{asset('img/post-pictures/'.$picture)}}">
            @endforeach
        @endif
    </output>
    <textarea id="editPostDesc" name="postDesc">{{$post->desc}}</textarea>
    <output id="postTaggedUsersModal" class="row">
        @if ($taggedUsers = json_decode($post->tagged_users))
            @foreach ($taggedUsers as $tag)
                <div class="col-3 taggedUser">
                    <label class="taggedUserLabel">{{$tag}}</label>
                </div>
            @endforeach
        @endif
    </output>
    <div class="friendsWallButtons">
        <span class="additionalButton tagUserButton" data-id="{{$post->id}}" data-toggle="modal" data-target="#tagUsersModal"><i class="fas fa-user-tag"></i></span>
        <label for="editPicture" class="additionalButton"><i class="far fa-image"></i></label>
        <input type="file" class="d-none" name="editPicture[]" accept="image/*" id="editPicture" multiple>
    </div>
    <input type="hidden" value="{{$post->id}}" name="postId">
    <div class="friendsWallSendButton">
        <button name="sendPost" id="editPostButton" type="submit" class="btn btn-block">{{__('activityWall.editPost')}}</button>
    </div>
</form>