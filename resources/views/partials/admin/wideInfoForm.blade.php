<form id="adminInfoForm">
    <div class="form-group">
        <label for="infoNotCheck">{{__('admin.wideNotCheck')}}</label>
        <input type="checkbox" class="infoNotCheck" name="infoNotCheck" id="infoNotCheck">
    </div>
    <div class="form-group">
      <label class="d-block" for="infoNotDesc">{{__('admin.infoContent')}}</label>
      <textarea class="infoNotDesc" name="infoNotDesc" id="infoNotDesc"></textarea>
    </div>
    <hr>
    <div class="mt-4 form-group">
        <label for="infoWallCheck">{{__('admin.wideWallCheck')}}</label>
        <input type="checkbox" name="infoWallCheck" id="infoWallCheck">
    </div>
    <div class="form-group">
        <output id="adminPicture-preview"></output>
        <label class="d-block" for="infoWallDesc">{{__('admin.infoContent')}}</label>
        <textarea class="infoWallDesc" name="infoWallDesc" id="infoWallDesc"></textarea>
        <div class="friendsWallButtons">
            <span class="additionalButton"><i class="fas fa-user-tag"></i></span>
            <label for="postPicture" class="additionalButton"><i class="far fa-image"></i></label>
            <input type="file" class="d-none" name="postPicture[]" accept="image/*" id="postPicture" multiple>
        </div>
    </div>
    
    <button class="btn btn-block wideInfoBtn" type="submit">{{__('admin.infoSend')}}</button>
</form>