<div class="modal fade" id="tagUsersModal" tabindex="-1" role="dialog" aria-labelledby="tagUsersModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('activityWall.tagUsersTitle')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tagUsers" class="mt-4" method="post">
                    <div class="form-group">
                        <input id="tagUserName" placeholder="{{__('activityWall.tagUsersTitle')}}" class="form-control" name="username">
                    </div>
                    
                    <output id="taggedUsers" class="row"></output>

                    <div class="form-group friendsWallSendButton mb-0">
                        <button class="btn btn-block" type="submit">{{__('activityWall.tagUsersBtn')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>