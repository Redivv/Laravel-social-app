<a class="sortOptions @if(!request()->sortBy) active @endif" href="{{route('home')}}">{{__('activityWall.sortNone')}}</a>
<a class="sortOptions @if(request()->sortBy == "public") active @endif" href="{{route('home')."?sortBy=public"}}">{{__('activityWall.sortPublic')}}</a>
<a class="sortOptions @if(request()->sortBy == "friends") active @endif" href="{{route('home')."?sortBy=friends"}}">{{__('activityWall.sortFriends')}}</a>
<a class="sortOptions @if(request()->sortBy == "admin") active @endif" href="{{route('home')."?sortBy=admin"}}">{{__('activityWall.sortAdmin')}}</a>