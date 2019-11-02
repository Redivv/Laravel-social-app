@if (count($elements) > 0)
    <table class="table table-fixed table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{__('admin.profileTicketTable1')}}</th>
                <th scope="col">{{__('admin.userTicketTable1')}}</th>
                <th scope="col">{{__('admin.profileTicketTable3')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($elements as $element)
                <tr>
                    <th scope="row">{{$ticket->data['user_name']}}</th>
                    <td><img src="{{asset('img/profile-pictures/'.$ticket->data['reason'])}}" alt="" srcset=""></td>
                    <td>
                        <form class="adminForm" method="post">
                            <button type="submit" class="btn form-btn acceptBtn">
                            {{__('admin.userDelete')}} 
                            </button>
                            <button type="submit" class="btn form-btn denyBtn">
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
                {{__('admin.noContentList')}}
        </div>
@endif