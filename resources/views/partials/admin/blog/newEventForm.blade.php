@if ($elementType === "event" && $element)
    <div class="returnBtn">
        <a class="btn form-btn" href="{{route('adminBlog')}}">
        {{__('admin.back')}}
        </a>
    </div>
    <form id="newEventForm" method="post" enctype="multipart/form-data">
        @method('put')
        <div class="form-group">
            <label for="eventName">{{__('admin.eventName')}}</label>
            <input type="text" class="eventName form-control" name="eventName" id="eventName" value="{{$element->name}}" required>
        </div>
        <div class="form-group">
            <label class="d-block" for="eventURL">{{__('admin.itemCategory')}}</label>
            <input id="eventURL" class="eventURL form-control" name="eventURL" value="{{$element->url}}">
        </div>

        @php
            $start = explode(" ",$element->starts_at);
            $stop = explode(" ",$element->ends_at);
        @endphp
        <div class="form-group">
            <label class="d-block" for="eventSTART">{{__('admin.eventSTART')}}</label>
            <div class="input-group">
                <input type="date" name="eventSTART[date]" class="form-control" required value="{{$start[0]}}">
                <input type="time" name="eventSTART[time]" class="form-control" required value="{{substr($start[1],0,-3)}}">
            </div>
        </div>
        <div class="form-group">
            <label class="d-block" for="eventSTOP">{{__('admin.eventSTOP')}}</label>
            <div class="input-group">
                <input type="date" name="eventSTOP[date]" class="form-control" required value="{{$stop[0]}}">
                <input type="time" name="eventSTOP[time]" class="form-control" required value="{{substr($stop[1],0,-3)}}">
            </div>
        </div>

        <input type="hidden" name="eventId" value="{{$element->id}}">
        
        <button class="btn btn-block newPostButton" type="submit">{{__('searcher.add')}}</button>
    </form>
@else
    <form id="newEventForm" method="post">
        @method('put')
        <div class="form-group">
            <label for="eventName">{{__('admin.eventName')}}</label>
            <input type="text" class="eventName form-control" name="eventName" id="eventName" required>
        </div>
        <div class="form-group">
            <label class="d-block" for="eventURL">{{__('admin.eventURL')}}</label>
            <input id="eventURL" class="eventURL form-control" name="eventURL" required>
        </div>

        <div class="form-group">
            <label class="d-block" for="eventSTART">{{__('admin.eventSTART')}}</label>
            <div class="input-group">
                <input type="date" name="eventSTART[date]" class="form-control" required>
                <input type="time" name="eventSTART[time]" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="d-block" for="eventSTOP">{{__('admin.eventSTOP')}}</label>
            <div class="input-group">
                <input type="date" name="eventSTOP[date]" class="form-control" required>
                <input type="time" name="eventSTOP[time]" class="form-control" required>
            </div>
        </div>


        <button class="btn btn-block newPostButton" type="submit">{{__('searcher.add')}}</button>
    </form>
@endif