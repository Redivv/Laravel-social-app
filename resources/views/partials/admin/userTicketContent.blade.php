@if (count($tickets) <= 0)
    <div class="alert alert-success" role="alert">
            {{__('admin.noContent')}}
    </div>
@endif

<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.profileTicketTable1')}}</th>
            <th scope="col">{{__('admin.userTicketTable1')}}</th>
            <th scope="col">{{__('admin.profileTicketTable4')}}</th>
            <th scope="col">{{__('admin.profileTicketTable3')}}<span id="userTicket-fetchBtn" class="fetchBtn"><i class="fas fa-sync"></i></span></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tickets as $ticket)
            <tr>
                <th scope="row"><a href="profile/{{$ticket->data['user_name']}}">{{$ticket->data['user_name']}}</a></th>
                <td>{{$ticket->data['reason']}}</td>
                <td>{{$ticket->created_at->diffForHumans()}}</td>
                <td>
                    <form class="adminForm" method="post">
                        <button name="accept" type="submit" class="btn form-btn ticketBtn deleteBtn">
                        {{__('admin.userDelete')}} 
                        </button>
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}">
                        <button name="refuse" type="submit" class="btn form-btn ticketBtn denyBtn">
                            {{__('admin.ignore')}}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>