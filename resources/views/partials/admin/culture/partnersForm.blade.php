<form id="partnersForm" method="post" enctype="multipart/form-data">
    @method("put")
    <div class="partnersBox row">
        <div id="newPartnerButton" class="newPartnerBox col">
            <i class="fas fa-plus" data-tool="tooltip" data-placement="bottom" title="{{__('admin.cultureAddItem')}}"></i>
        </div>
    </div>
    <button class="btn form-btn w-100" type="submit">
        {{__('searcher.add')}}
    </button>
</form>