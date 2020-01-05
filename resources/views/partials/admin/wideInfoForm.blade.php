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
        <label for="infoMailCheck">{{__('admin.mailCheck')}}</label>
        <input type="checkbox" name="infoMailCheck" id="infoMailCheck">
    </div>
    <div class="form-group">
        <label class="d-block" for="infoMailTitle">{{__('admin.infoTitleContent')}}</label>
        <input class="infoMailTitle" name="infoMailTitle" id="infoMailTitle">

        <label class="d-block" for="infoMailDesc">{{__('admin.infoContent')}}</label>
        <textarea class="infoMailDesc" name="infoMailDesc" id="infoMailDesc"></textarea>
    </div>
    
    <button class="btn btn-block wideInfoBtn" type="submit">{{__('admin.infoSend')}}</button>
</form>