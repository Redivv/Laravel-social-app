<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.tagListTable1')}}</th>
            <th scope="col">{{__('admin.cityListTable2')}}</th>
            <th scope="col">
                {{__('admin.profileTicketTable3')}}
                <span id="cityList-fetchBtn" class="fetchBtn" title="{{__('admin.fetch')}}" data-placement="bottom">
                    <i class="fas fa-sync"></i>
                </span>
                <span id="cityList-searchBtn" class="searchBtn" data-toggle="modal" data-target="#cityListModal" title="{{__('admin.search')}}" data-placement="bottom">
                    <i class="fas fa-search"></i>
                </span>
            </th>
        </tr>
    </thead>
    <tbody id="cityList-table">
        @foreach ($elements as $element)
            <tr>
                <th scope="row">{{$element->name}}</th>
                <td>{{$element->created_at->diffForHumans()}}</td>
                <td>
                    <form class="adminForm" method="post">
                        <button name="edit" type="submit" class="btn form-btn listBtn editBtn">
                            {{__('admin.edit')}} 
                        </button>
                        <input type="hidden" name="elementId" value="{{$element->id}}">
                        <button name="delete" type="submit" class="btn form-btn listBtn deleteBtn">
                            {{__('admin.delete')}} 
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>