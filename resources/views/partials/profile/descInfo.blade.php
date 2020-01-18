<div class="container modalDesc">
    @if ($desc && !empty(trim($desc)))
        {{$desc}}
    @else
        <span class="noInfo">{{__('profile.emptyModal')}}</span> 
    @endif
</div>