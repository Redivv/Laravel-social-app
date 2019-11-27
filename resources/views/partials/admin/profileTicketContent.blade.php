@if (count($tickets) <= 0)
    <div class="alert alert-success" role="alert">
            {{__('admin.noContent')}}
    </div>
@endif

<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.profileTicketTable1')}}</th>
            <th scope="col">{{__('admin.profileTicketTable2')}}</th>
            <th scope="col">{{__('admin.profileTicketTable4')}}</th>
            <th scope="col">{{__('admin.profileTicketTable3')}}<span id="profileTicket-fetchBtn" class="fetchBtn"><i class="fas fa-sync"></i></span></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tickets as $ticket)
            <tr>
                <th scope="row">{{$ticket->data['user_name']}}</th>
                <td><img src="{{asset('img/profile-pictures/'.$ticket->data['image'])}}" class="profilePicture"></td>
                <td>{{$ticket->created_at->diffForHumans()}}</td>
                <td>
                    <form class="adminForm" method="post" enctype="multipart/form-data">
                        <button name="accept" type="submit" class="btn ticketBtn form-btn acceptBtn">
                        {{__('admin.accept')}} 
                        </button>
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}">
                        <button name="refuse" type="submit" class="btn ticketBtn form-btn denyBtn">
                            {{__('admin.refuse')}}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>