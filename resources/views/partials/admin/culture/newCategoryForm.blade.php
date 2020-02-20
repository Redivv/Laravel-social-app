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