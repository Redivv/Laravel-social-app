@if (count($elements) > 0)
    @include('partials.admin.cityListTable')
    <!-- Modal -->
    <div class="modal fade" id="cityListModal" tabindex="-1" role="dialog" aria-labelledby="cityListModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="cityListModalLabel">{{{__('admin.searchTitle')}}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="cityListSearch" class="container searchForm" data-target="cityList">
                    <div class="form-group row">
                        <label class="col-12 p-0" for="cityListSearchInput">
                            {{__('admin.searchCity')}}
                        </label>
                        <input id="cityListSearch-input" class="form-control col-md-9" name="cryteria" type="text">
                        <button class="col-md-2 col-sm-12 btn form-btn ml-md-2 ml-sm-0 mt-md-0 mt-sm-2" type="submit">{{__('admin.searchButton')}}</button>
                    </div>
                </form>
                <output id="cityList-searchOut"></output>
            </div>
        </div>
        </div>
    </div>
    @else
        <div class="alert alert-warning" role="alert">
                {{__('admin.noContentList')}}
        </div>
@endif