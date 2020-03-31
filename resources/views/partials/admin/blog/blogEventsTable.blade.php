<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.tagListTable1')}}</th>
            <th scope="col">{{__('admin.eventSTART')}}</th>
            <th scope="col">{{__('admin.eventSTOP')}}</th>
            <th scope="col">{{__('admin.eventURL')}}</th>
            <th scope="col">
                {{__('admin.profileTicketTable3')}}
            </th>
        </tr>
    </thead>
    <tbody id="eventList-table">
        @foreach ($elements as $element)
            <tr>
                <th scope="row">{{$element->name}}</th>
                <td> {{$element->starts_at}} </td>
                <td>{{$element->ends_at}}</td>
                <td>
                    <a href="{{$element->url}}">{{$element->url}}</a>
                </td>
                <td>
                    <form class="adminForm d-inline">
                        <a type="submit" class="btn form-btn" href="{{route('adminBlog')."?elementType=event&elementId=".$element->id}}">
                            {{__('admin.edit')}} 
                        </a>
                        <input type="hidden" name="elementType" value="event">
                        <input type="hidden" name="elementId"   value="{{$element->id}}">
                        <button name="delete" type="submit" class="btn form-btn listBtn deleteBtn">
                            {{__('admin.delete')}} 
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>