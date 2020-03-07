@foreach ($taggedUsers as $tag)
    <div class="col-3 taggedUser">
        <label class="taggedUserLabel">{{$tag->name}}</label>
        <input type="hidden" value="{{$tag->id}}" name="taggedUser[]">
    </div>
@endforeach