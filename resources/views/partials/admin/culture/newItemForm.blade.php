@if ($elementType === "item" && $element)
<div class="returnBtn">
    <a class="btn form-btn" href="{{route('adminCulture')}}">
      {{__('admin.back')}}
    </a>
</div>
<form id="newItemForm" method="post" enctype="multipart/form-data">
    @method('put')
    <div class="form-group">
        <label class="d-block" for="itemCategory">{{__('admin.itemCategory')}}</label>
        <select id="itemCategory" class="itemCategory form-control" name="itemCategory">
            <option value="0">-- {{__('admin.selectCategory')}} --</option>
            @foreach ($categories as $cat)
                <option value="{{$cat->id}}" @if($element->category_id == $cat->id) selected @endif data-attrs="{{$cat->attributes}}">{{$cat->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="itemName">{{__('admin.itemName')}}</label>
        <input type="text" class="itemName form-control" name="itemName" id="itemName" value="{{$element->name}}" required>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemThumbnail">{{__('admin.itemThumbnail')}}</label>
        <input type="file" name="itemThumbnail" class="itemThumbnail form-control-file" id="itemThumbnail" accept="image/*" required>
        <output id="itemThumbnail-out">
            <a href="{{asset('img/culture-pictures/'.json_decode($element->thumbnail)[0])}}" data-lightbox="previewThumb">
                <img src="{{asset('img/culture-pictures/'.json_decode($element->thumbnail)[0])}}" alt="Item Thumbnail">
            </a>
        </output>
    </div>
    <div id="newItemAttributes" class="form-group">

        @if ($attrs = json_decode($element->category->attributes))
            @php
                $itemAttrs = json_decode($element->attributes);
            @endphp
            @foreach ($attrs as $key => $attr)
                <div class="attrBox">
                    <label class="d-block" for="itemAttr{{$key}}-new">
                        {{$attr}}
                    </label>
                    <input value="{{$itemAttrs[$key]}}" class="itemAttr form-control col-6" name="itemAttr[]" id="itemAttr{{$key}}-new">
                </div>
            @endforeach
        @endif
    </div>
    <div class="form-group">
        <label class="d-block" for="itemTags">{{__('admin.itemTags')}}</label>
        <div class="input-group">
            <input class="itemTags form-control" id="itemTags" name="itemTags[]">
            <div class="input-group-append">
                <button class="btn" type="button" id="addTagBtn">{{__('searcher.add')}}</button>
            </div>
        </div>
        <output class="row" id="itemTags-out">
            @if ($tags = $element->tagNames())
                @foreach ($tags as $tag)
                <div class="col itemTag" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteTags')}}">
                    <span>{{$tag}}</span>
                    <input type="hidden" name="itemTags[]" value="{{$tag}}">
                </div>
                @endforeach
            @endif
        </output>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemDesc">{{__('profile.desc')}}</label>
        <textarea name="itemDesc" class="itemDesc form-control" id="itemDesc" rows="3" required >{{$element->description}}</textarea>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemImages">{{__('admin.itemImages')}}</label>
        <input type="file" name="itemImages[]" class="itemImages form-control-file" id="itemImages" multiple accept="image/*">
        <output id="itemImages-out">
            @if ($pictures = json_decode($element->pictures))
                <div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt" id="resetImages" data-tool="tooltip" title="{{__('activityWall.deleteImages')}}" data-placement="bottom"></i></div>
                @foreach ($pictures as $picture)
                <a href="{{asset('img/culture-pictures/'.$picture)}}" data-lightbox="previewImage">
                    <img src="{{asset('img/culture-pictures/'.$picture)}}" alt="Item Thumbnail">
                </a>
                @endforeach
            @endif
        </output>
    </div>
    <div class="form-group itemReview-box">
        <label class="d-block" for="itemReview">{{__('admin.itemReview')}}</label>
        <textarea name="itemReview" class="itemReview form-control" id="itemReview">{!!$element->review!!}</textarea>
    </div>

    <button class="btn btn-block newItemButton" type="submit">{{__('searcher.add')}}</button>
</form>
@else
<form id="newItemForm" method="post" enctype="multipart/form-data">
    @method('put')
    <div class="form-group">
        <label class="d-block" for="itemCategory">{{__('admin.itemCategory')}}</label>
        <select id="itemCategory" class="itemCategory form-control" name="itemCategory">
            <option value="0" selected>-- {{__('admin.selectCategory')}} --</option>
            @foreach ($categories as $cat)
                <option value="{{$cat->id}}" data-attrs="{{$cat->attributes}}">{{$cat->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="itemName">{{__('admin.itemName')}}</label>
        <input type="text" class="itemName form-control" name="itemName" id="itemName" required>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemThumbnail">{{__('admin.itemThumbnail')}}</label>
        <input type="file" name="itemThumbnail" class="itemThumbnail form-control-file" id="itemThumbnail" accept="image/*" required>
        <output id="itemThumbnail-out"></output>
    </div>
    <div id="newItemAttributes" class="form-group">
        <div class="noCategoryInfo">{{__('admin.selectCategory')}}</div>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemTags">{{__('admin.itemTags')}}</label>
        <div class="input-group">
            <input class="itemTags form-control" id="itemTags" name="itemTags[]">
            <div class="input-group-append">
                <button class="btn" type="button" id="addTagBtn">{{__('searcher.add')}}</button>
            </div>
        </div>
        <output class="row" id="itemTags-out">
        </output>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemDesc">{{__('profile.desc')}}</label>
        <textarea name="itemDesc" class="itemDesc form-control" id="itemDesc" rows="3" required ></textarea>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemImages">{{__('admin.itemImages')}}</label>
        <input type="file" name="itemImages[]" class="itemImages form-control-file" id="itemImages" multiple accept="image/*">
        <output id="itemImages-out"></output>
    </div>
    <div class="form-group itemReview-box">
        <label class="d-block" for="itemReview">{{__('admin.itemReview')}}</label>
        <textarea name="itemReview" class="itemReview form-control" id="itemReview"></textarea>
    </div>

    <button class="btn btn-block newItemButton" type="submit">{{__('searcher.add')}}</button>
</form>
@endif