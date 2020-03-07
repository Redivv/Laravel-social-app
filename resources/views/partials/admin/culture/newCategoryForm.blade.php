@if ($elementType === "category" && $element)
<div class="returnBtn">
    <a class="btn form-btn" href="{{route('adminCulture')}}">
      {{__('admin.back')}}
    </a>
</div>
<form id="newCategoryForm">
  @method('put')
  <div class="form-group">
      <label for="categoryName">{{__('admin.categoryName')}}</label>
      <input type="text" class="categoryName form-control" name="categoryName" id="categoryName" value="{{$element->name}}" required>
  </div>
  <div class="form-group newCultureAttributes">
    <label class="d-block" for="categoryAttr1">{{__('admin.attrs')}}</label>
    @if ($attrs = json_decode($element->attributes))
    @foreach ($attrs as $attribute)
      <div class="attrBox row @if ($loop->iteration > 1) mt-2 @endif">
        <input class="categoryAttr form-control col" name="categoryAttr[]" id="categoryAttr{{$loop->iteration}}" required value="{{$attribute}}">
        @if ($loop->iteration === 1)
        <span class="categoryAttrAppend col">
            <i class="fas fa-plus" data-tool="tooltip" title="{{__('admin.addNewAttr')}}" data-placement="bottom"></i>
        </span>
        @else
        <span class="categoryAttrDelete col">
            <i class="fas fa-times" data-tool="tooltip" title="{{__('admin.deleteAttrMsg')}}" data-placement="bottom"></i>
          </span>
        @endif
      </div>
    @endforeach
    @endif
  </div>
  <input type="hidden" name="categoryId" value="{{$element->id}}">
  <button class="btn btn-block newCategoryButton" type="submit">{{__('admin.change')}}</button>
</form>
@else
  <form id="newCategoryForm">
      @method('put')
      <div class="form-group">
          <label for="categoryName">{{__('admin.categoryName')}}</label>
          <input type="text" class="categoryName form-control" name="categoryName" id="categoryName" required>
      </div>
      <div class="form-group newCultureAttributes">
        <label class="d-block" for="categoryAttr1">{{__('admin.attrs')}}</label>
        <div class="attrBox row">
          <input class="categoryAttr form-control col" name="categoryAttr[]" id="categoryAttr1" required >
          <span class="categoryAttrAppend col">
              <i class="fas fa-plus" data-tool="tooltip" title="{{__('admin.addNewAttr')}}" data-placement="bottom"></i>
          </span>
        </div>
      </div>

      <button class="btn btn-block newCategoryButton" type="submit">{{__('searcher.add')}}</button>
  </form>
@endif