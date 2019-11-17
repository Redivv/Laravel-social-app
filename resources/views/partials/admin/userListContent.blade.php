@if (count($elements) > 0)
    <table class="table table-fixed table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{__('admin.profileTicketTable1')}}</th>
                <th scope="col">{{__('admin.profileTicketTable2')}}</th>
                <th scope="col">{{__('admin.userListTable3')}}</th>
                <th scope="col">{{__('admin.profileTicketTable3')}}<span id="userList-fetchBtn" class="fetchBtn"><i class="fas fa-sync"></i></span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($elements as $element)
                <tr>
                    <th scope="row">{{$element->name}}</th>
                    <td><img class="profilePicture" src="{{asset('img/profile-pictures/'.$element->picture)}}"></td>
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
    @else
        <div class="alert alert-warning" role="alert">
                {{__('admin.noContentList')}}
        </div>
@endif