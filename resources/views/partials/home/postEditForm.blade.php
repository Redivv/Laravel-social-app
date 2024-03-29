<form id="editPost" method="post">
    <output id="modalPicture-preview">
        @if ($pictures = json_decode($post->pictures))
            <div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt" data-tool="tooltip" title="{{__('activityWall.deleteImages')}}" data-placement="bottom"></i></div>
            @foreach ($pictures as $picture)
            <a href="{{asset('img/post-pictures/'.$picture)}}" data-lightbox="postEdit">
                <img class="thumb" src="{{asset('img/post-pictures/'.$picture)}}" alt="Post Picture">
            </a>
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
        <span class="additionalButton tagUserButton" data-id="{{$post->id}}" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}">
            <i class="fas fa-user-tag"></i>
        </span>
        <label for="editPicture" class="additionalButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.addImage')}}">
            <i class="far fa-image"></i>
        </label>
        <input type="file" class="d-none" name="editPicture[]" accept="image/*" id="editPicture" multiple>

        <div class="custom-control custom-switch">
            <input type="checkbox" name="isPublic" class="custom-control-input" id="isPublicSwitch2" @if(!$post->is_public) checked @endif @if($post->type == "AdminPost") disabled @endif>
            <label class="custom-control-label" for="isPublicSwitch2">{{__('activityWall.togglePublic')}}</label>
        </div>
    </div>
    <input type="hidden" value="{{$post->id}}" name="postId">
    <div class="friendsWallSendButton">
        <button name="sendPost" id="editPostButton" type="submit" class="btn btn-block">{{__('activityWall.editPost')}}</button>
    </div>
</form>