<form id="newItemForm" method="post" enctype="multipart/form-data">
    @method('put')
    <div class="form-group">
        <label class="d-block" for="itemCategory">{{__('admin.itemCategory')}}</label>
        <select id="itemCategory" class="itemCategory" name="itemCategory">
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
    <div id="newItemAttributes" class="form-group">
        <span class="noCategoryInfo">{{__('admin.selectCategory')}}</span>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemTags">{{__('admin.itemTags')}}</label>
        <div class="input-group">
            <input class="itemTags form-control" id="itemTags">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="addTagBtn">{{__('searcher.add')}}</button>
            </div>
        </div>
        <output class="row w-100" id="itemTags-out">
        </output>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemDesc">{{__('profile.desc')}}</label>
        <textarea class="itemDesc form-control" id="itemDesc" rows="3" required ></textarea>
    </div>
    <div class="form-group">
        <label class="d-block" for="itemImages">{{__('admin.itemImages')}}</label>
        <input type="file" class="itemImages form-control-file" id="itemImages" multiple accept="image/*">
        <output id="itemImages-out"></output>
    </div>
    <div class="form-group itemReview-box">
        <label class="d-block" for="itemReview">{{__('admin.itemReview')}}</label>
        <textarea class="itemReview form-control" id="itemReview">peni</textarea>
    </div>

    <button class="btn btn-block newCategoryButton" type="submit">{{__('searcher.add')}}</button>
</form>