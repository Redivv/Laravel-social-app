@foreach ($tags as $element)
    <tr>
        <th scope="row">{{$element->name}}</th>
        <td>{{$element->count}}</td>
        <td>
            <form class="adminForm" method="post">
                <button name="edit" type="submit" class="btn form-btn listBtn editBtn">
                    {{__('admin.edit')}} 
                </button>
                <input type="hidden" name="elementId" value="{{$element->id}}">
                <button name="delete" type="submit" class="btn form-btn listBtn deleteBtn">
                    {{__('admin.delete')}} 
                </button>
            </form>
        </td>
    </tr>
@endforeach