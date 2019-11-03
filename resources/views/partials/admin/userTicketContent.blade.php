@if (count($tickets) > 0)
    <table class="table table-fixed table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{__('admin.profileTicketTable1')}}</th>
                <th scope="col">{{__('admin.userTicketTable1')}}</th>
                <th scope="col">{{__('admin.profileTicketTable3')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <th scope="row">{{$ticket->data['user_name']}}</th>
                    <td><img src="{{asset('img/profile-pictures/'.$ticket->data['reason'])}}" alt="" srcset=""></td>
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
    @else
        <div class="alert alert-success" role="alert">
                {{__('admin.noContent')}}
        </div>
@endif