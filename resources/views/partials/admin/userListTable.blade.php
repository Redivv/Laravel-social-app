<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.profileTicketTable1')}}</th>
            <th scope="col">{{__('admin.profileTicketTable2')}}</th>
            <th scope="col">{{__('admin.userListTable3')}}</th>
            <th scope="col">
                {{__('admin.profileTicketTable3')}}
                <span id="userList-fetchBtn" class="fetchBtn" title="{{__('admin.fetch')}}" data-placement="bottom">
                    <i class="fas fa-sync"></i>
                </span>
                <span id="userList-searchBtn" class="searchBtn" data-toggle="modal" data-target="#userListModal" title="{{__('admin.search')}}" data-placement="bottom">
                    <i class="fas fa-search"></i>
                </span>
            </th>
        </tr>
    </thead>
    <tbody id="userList-table">
        @foreach ($elements as $element)
            <tr>
                <th scope="row">{{$element->name}}</th>
                <td>
                    <a href="{{asset('img/profile-pictures/'.$element->picture)}}" data-lightbox="picture-{{$element->id}}" data-title="{{__('admin.userListImageCaption', ['user' => $element->name])}}">
                        <img class="profilePicture" src="{{asset('img/profile-pictures/'.$element->picture)}}">
                    </a>
                </td>
                <td>{{$element->created_at->diffForHumans()}}</td>
                <td>
                    <form class="adminForm" method="post">
                        <button name="delete" type="submit" class="btn form-btn listBtn">
                        {{__('admin.userDelete')}} 
                        </button>
                        <input type="hidden" name="elementId" value="{{$element->id}}">
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>