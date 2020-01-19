<div class="container">
    @if ($tags && count($tags) > 0)
        <div class="row modalTags">
            @foreach ($tags as $tag)
                <span class="col-5">
                    {{$tag}} 
                </span>
            @endforeach
        </div>
    @else
        <span class="noInfo">{{__('profile.emptyModal')}}</span> 
    @endif
</div>