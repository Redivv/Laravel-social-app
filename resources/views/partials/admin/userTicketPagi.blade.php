@foreach ($tickets as $ticket)
    <tr>
        <th scope="row">
            <a href="{{route('ProfileOtherView',['user' => $ticket->data['user_name']])}}" target="__blank">
                {{$ticket->data['user_name']}}
            </a>
        </th>
        <td>{{$ticket->data['reason']}}</td>
        <td>{{$ticket->created_at->diffForHumans()}}</td>
        <td>
            <a href="{{route('ProfileOtherView',['user' => $ticket->data['author']])}}" class="font-weight-bold" target="__blank">
                {{$ticket->data['author']}}
            </a>    
        </td>
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