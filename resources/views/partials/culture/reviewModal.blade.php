<div class="modal fade" id="reviewModal" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">{{$cultureItem->name}} - {{__('admin.itemReview')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <article class="modal-body">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </article>
            <div class="modal-footer">
                <h6 class="author">
                    {{__('culture.author')}} - <a href="{{route('ProfileOtherView',['user' => $cultureItem->user->name])}}" target="__blank">
                        {{$cultureItem->user->name}}
                    </a>
                </h6>
            </div>
        </div>
    </div>
</div>