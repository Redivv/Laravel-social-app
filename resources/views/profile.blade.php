@extends('layouts.profile')

@section('name')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'nazwy' --}}
    {{__('profile.username')}}: 
    {{-- pobranie nazwy użytkownika z DB --}}
    {{ $user ->name }}
</p>
    
@endsection
@section('city')
@if($user->birth_year!='err0000')
    <p class="profile-paragraph">
        {{-- Tłumaczenie 'miasta' --}}
        {{__('profile.city')}}: 
        {{-- Pobieranie miejsca zamieszkania użytkownika z DB (w razie braku System powie, że nie podano) --}}
        @if ( !$user->city_id )
            {{-- Tłumaczenie 'nie podano' --}}
            <b class='text-danger'>{{__('profile.city_err')}}</b>
        @else
            {{ $user->city->name }}
        @endif
    </p>
@else
@endif
@endsection

@auth
@if ($user->id == Auth::user()->id)
    @section('email')
    <p class="profile-paragraph">
        {{-- Tłumaczenie 'Adresu email' --}}
        {{__('profile.email')}}: 
        {{-- pobranie maila użytkownika z DB --}}
        {{ $user ->email }}
    </p>
    @endsection
    @if (!$user->email_verified_at)
        <div class="alert alert-danger" role="alert">
            {{__('profile.verifyEmailAlert')}}
        </div>
    @endif
@endif
@endauth

@section('birth')
    @if($user->birth_year!='err0000')
        <p class="profile-paragraph">
            {{__('profile.birth')}}: 
            {{-- pobranie roku ur użytkownika z DB --}}
            {{ $user ->birth_year }}
        </p>
    @else
    @endif
@endsection

@section('relations')
    <p class="profile-paragraph">
        {{__('profile.status')}}:
        @if($user->relationship_status==0)
            {{__('profile.status_free')}}
        @else
            {{__('profile.status_taken')}}
        @endif
        
    </p>
        

@endsection

@auth
    @section('buttons')
        <div class="mt-4 mb-4 row col-12 text-right">
            <div class="col-3 profileButtons @if ($user->id != auth()->user()->id) likeUserBtn @endif @if($user->liked()) active @endif" data-id="{{$user->id}}">
                <i class="fas fa-fire"></i><span class="badge likesAmount @if($user->likeCount <= 0) invisible @endif">{{$user->likeCount}}</span>
            </div>
            @if ($user->id != auth()->user()->id)
                <div class="col-3 profileButtons @if(($user->isFriendWith(auth()->user())) || ($user->hasSentFriendRequestTo(auth()->user()))) active @else friendBtn @endif" data-name="{{$user->name}}">
                    @if($user->isFriendWith(auth()->user()))
                        <i class="fas fa-user-friends"></i>
                    @elseif($user->hasSentFriendRequestTo(auth()->user()))
                        <i class="fas fa-user-check"></i>
                    @else
                        <i class="fas fa-user-plus"></i>
                    @endif
                </div>
            @endif
            @if ($user->id != auth()->user()->id)
                <div class="col-3 profileButtons">
                    <a href="/message/{{$user->name}}" target="__blank"><i class="far fa-comment-dots"></i></a>
                </div>
            @endif
            @if ($user->id != auth()->user()->id)
                <div class="col-3 profileButtons reportUser" data-name="{{$user->name}}">
                    <i class="fas fa-exclamation"></i>
                </div>
            @endif
        </div>
    @endsection
@endauth

@section('photo')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>{{__('profile.photo')}}:</p>
        </div>
        <div class="col-md-8 foto_frame">
            {{-- pobieranie nazyw zdjęcia z DB i znalezienie go w folderze profile-pictures --}}
            <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
        </div>
        @if ($user->pending_picture && $user->id != auth()->id())
            <div class="mt-2 alert alert-info" role="alert">
                {{__('profile.pictureInfo')}}
            </div>
        @endif
    </div>
@endsection

@section('desc')
    <hr>
    @if($user->description=='err0000')
        <b class='text-danger'>{{__('profile.access_err')}}</b>
    @else
        {{__('profile.desc')}}:
        @if (!$user->description)
            {{__('profile.desc_err')}}
        @else
            <br>
            <div class="desc">
            {{ $user ->description }}
            </div>
        @endif
    @endif
@endsection
    
@section('tags')
    @if(Auth::check() || $user->hidden_status == 0)
        <div class="text-center"><h3>{{__('profile.Tags')}}<h3></div>
        @if (count($tags) > 0)
            <div class="tagList row mt-3 text-center">
                @include('partials.tagList')
            </div>
        @else
            <div class="text-center text-muted mt-4"><h4>{{__('profile.emptyTags')}}<h4></div>
        @endif
    @endif
@endsection


@section('endform')
<div>
    @auth
        @if($user->id == Auth::user()->id)
        <a href="{{route('ProfileEdition')}}" style="margin-left:20px;" class="btn form-btn button"><b>{{__('profile.edit')}}</b></a>
        @endif    
    @endauth
</div>
@endsection

@push('scripts')
    <script>

        var base_url = "{{url('/')}}";

        var reportUser              = "{{__('searcher.reportUser')}}";
        var reportUserReason        = "{{__('searcher.reportUserReason')}}";
        var reportUserReasonErr     = "{{__('searcher.reportUserReasonErr')}}";
        var reportUserSuccess       = "{{__('searcher.reportUserSuccess')}}";

    </script>
    <script defer>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.likeUserBtn').on('click',function() {
            likeUser(this);
        });

        $('.friendBtn').on('click',function() {
            addFriend(this);
        });

        $('.reportUser').on('click',function() {
            reportUser(this);
        })

        function likeUser(selected) {
            let userId = $(selected).data('id');
            let url = base_url+"/user/ajax/likeUser";

            let currentAmount = $(selected).find('.likesAmount').html().trim();
            if ($(selected).hasClass('active')) {

                $(selected).removeClass('active');
                $(selected).find('.likesAmount').html(parseInt(currentAmount)-1);
                if (currentAmount == 1) {
                    $(selected).find('.likesAmount').addClass('invisible');
                }

            }else{
                
                $(selected).addClass('active');
                $(selected).find('.likesAmount').html(parseInt(currentAmount)+1);
                if (currentAmount == 0) {
                    $(selected).find('.likesAmount').removeClass('invisible');
                }
            }

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": "patch", userId:userId}
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }

        function addFriend(selected){
            //get name of friend you want to delete
            let friendName = $(selected).data('name');
            //get url we want to visit with ajax
            let url= baseUrl+"/friends/ajax/add/"+friendName;

            let html= '<i class="fas fa-user-check"></i>';
            $(selected).find('i').replaceWith(html);
            $(selected).addClass('active');
            $(selected).removeClass('friendBtn');
            //make request in ajax:
            var request = $.ajax({
                //select method
                method : 'post',
                //select destination
                url: url,
                //select content we want to send:
                data: {
                    //here, we just want to change our method to "put", since it is strictly laravelish method
                    //and is unavaible in html.
                    "_method":"put",
                    //we don't need to change anything else, because we send user name in url.
                }
            });
            //if our request is unsuccesfull:
            request.fail(function (xhr){
                //we get our response as alert.
                alert(xhr.responseJSON.message);
            });
        }

        function reportUser(selected) {
            let reportReason = prompt(reportUserReason);
                if (reportReason.trim() == '') {
                    alert(reportUserReasonErr);
                }else{
                    $('.spinnerOverlay').removeClass('d-none');

                    let userName = $(selected).data('name');
                    let url = base_url+"/user/report";

                    var request = $.ajax({
                        method : 'post',
                        url: url,
                        data: {"_method": 'PUT', userName:userName, reason:reportReason.trim()}
                    });
                    
                    
                    request.done(function(response){
                        if (response.status === 'success') {
                            $('.spinnerOverlay').addClass('d-none');
                            alert(reportUserSuccess);
                            
                            $(selected).addClass('active');
                            $('.reportUser').off('click');
                        }
                    });
                    
                    
                    request.fail(function (xhr){
                        alert(xhr.responseJSON.message);
                    });
                }
            }

    </script>
@endpush