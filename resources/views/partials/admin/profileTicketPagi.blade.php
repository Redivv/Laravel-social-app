@foreach ($tickets as $ticket)
    <tr>
        <th scope="row">{{$ticket->data['user_name']}}</th>
        <td>
            <a href="{{asset('img/profile-pictures/'.$ticket->data['image'])}}" data-lightbox="ticket-{{$ticket->id}}" data-title="{{__('admin.profileTicketImageCaption', ['user' => $ticket->data['user_name']])}}">
                <img src="{{asset('img/profile-pictures/'.$ticket->data['image'])}}" class="profilePicture" alt="Profile Picture Ticket">
            </a>
        </td>
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