<div class="modal fade" id="commentEditModal" tabindex="-1" role="dialog" aria-labelledby="commentEditModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('activityWall.editCommModalTitle')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editComment" method="post">
                    <input id="editPostDesc" name="nameDesc">
                    <output id="commentModalUserTagged" class="row">
                    </output>
                    <div class="friendsWallButtons">
                        <span class="additionalButton tagUserButton commentModal" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}">
                            <i class="fas fa-user-tag"></i>
                        </span>
                    </div>
                    <div class="friendsWallSendButton">
                        <button name="sendPost" id="editCommentButton" type="submit" class="btn btn-block">{{__('activityWall.editPost')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>