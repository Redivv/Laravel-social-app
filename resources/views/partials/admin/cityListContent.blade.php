@if (count($elements) > 0)
    <table class="table table-fixed table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{__('admin.tagListTable1')}}</th>
                <th scope="col">{{__('admin.cityListTable2')}}</th>
                <th scope="col">{{__('admin.profileTicketTable3')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($elements as $element)
                <tr>
                    <th scope="row">{{$element->name}}</th>
                    <td>{{$element->created_at}}</td>
                    <td>
                        <form class="adminForm" method="post">
                            <button type="submit" class="btn form-btn editBtn">
                                {{__('admin.edit')}} 
                            </button>
                            <button type="submit" class="btn form-btn deleteBtn">
                                {{__('admin.delete')}} 
                            </button>
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