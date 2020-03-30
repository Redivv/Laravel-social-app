<table class="table table-fixed table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">{{__('admin.tagListTable1')}}</th>
            <th scope="col">{{__('admin.attrs')}}</th>
            <th scope="col">{{__('admin.cityListTable2')}}</th>
            <th scope="col">
                {{__('admin.profileTicketTable3')}}
            </th>
        </tr>
    </thead>
    <tbody id="cityList-table">
        @foreach ($elements as $element)
            <tr>
                <th scope="row">{{$element->name}}</th>
                <td>
                    @if ($attrs = json_decode($element->attributes))
                        @foreach ($attrs as $attribute)
                            {{__($attribute)}}@if ($loop->remaining > 0) , @endif
                        @endforeach
                    @endif
                </td>
                <td>{{$element->created_at->diffForHumans()}}</td>
                <td>
                    <form class="adminForm d-inline">
                        <button type="submit" class="btn form-btn">
                            {{__('admin.edit')}} 
                        </button>
                        <input type="hidden" name="elementType" value="cultureCategory">
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