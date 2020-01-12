@foreach ($users as $element)
    <tr>
        <th scope="row">{{$element->name}}</th>
        <td>
            <a href="{{asset('img/profile-pictures/'.$element->picture)}}" data-lightbox="picture-{{$element->id}}" data-title="{{__('admin.userListImageCaption', ['user' => $element->name])}}">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$element->picture)}}">
            </a>
        </td>
        <td>{{$element->created_at->diffForHumans()}}</td>
        <td>
            <form class="adminForm" method="post">
                <button name="delete" type="submit" class="btn form-btn listBtn">
                {{__('admin.userDelete')}} 
                </button>
                <input type="hidden" name="elementId" value="{{$element->id}}">
            </form>
        </td>
    </tr>
@endforeach