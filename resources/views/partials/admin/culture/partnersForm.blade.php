<form id="partnersForm" method="post" enctype="multipart/form-data">
    @method("put")
    <div class="partnersBox row">
        @foreach ($partners as $partner)
            <div class="form-group partner col">
                <div class="partnerDelete"><i class="fas fa-times" data-tool="tooltip" title="{{__('admin.delete')}}"></i></div>
                <output class="partnerThumb" id="partner{{$partner->id}}">
                    <a href="{{asset("img/partner-pictures/".$partner->thumbnail)}}" data-lightbox="partners" title="Partnerzy">
                        <img src="{{asset("img/partner-pictures/".$partner->thumbnail)}}" alt="Partner Picture">
                    </a>
                </output>
                <input class="partnerThumb-input" type="file" name="existingPartners[{{$partner->id}}][image]">
                <input type="hidden" name="existingPartners[{{$partner->id}}][id]" value="{{$partner->id}}">
                <input type="text" name="existingPartners[{{$partner->id}}][name]" class="form-control" placeholder="Name" required value="{{$partner->name}}">
                <input type="text" name="existingPartners[{{$partner->id}}][url]" class="form-control mt-2" placeholder="Url" required value="{{$partner->url}}">
            </div>
        @endforeach
        <div id="newPartnerButton" class="newPartnerBox col">
            <i class="fas fa-plus" data-tool="tooltip" data-placement="bottom" title="{{__('admin.cultureAddItem')}}"></i>
        </div>
    </div>
    <button class="btn form-btn w-100" type="submit">
        {{__('searcher.add')}}
    </button>
</form>