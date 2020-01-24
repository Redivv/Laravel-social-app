<!-- Modal -->
<div class="modal fade" id="tagPartnerModal" tabindex="-1" role="dialog" aria-labelledby="tagPartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="tagPartnerModalLabel">{{{__('profile.searchTitle')}}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form id="friendsSearch" class="container searchForm">
                <div class="form-group row">
                    <label class="col-12 p-0" for="friendsSearchInput">
                        {{__('profile.searchFriend')}}
                    </label>
                    <input id="friendsSearch-input" class="form-control col-md-9" name="cryteria" type="text">
                    <button class="col-md-2 col-sm-12 btn form-btn ml-md-2 ml-sm-0 mt-md-0 mt-sm-2" type="submit">{{__('admin.searchButton')}}</button>
                </div>
            </form>
            <output class="row" id="friends-searchOut"></output>
            <button id="tagPartnerButton" class="col-12 btn form-btn mt-2">{{__('profile.addPartner')}}</button>
        </div>
    </div>
    </div>
</div>