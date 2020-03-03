@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.cultureItem')}}
    </title>
@endsection

@section('content')
<div class="container-flex culture_page-container">
    <div class="row">
        <aside class="col-md-1 advertisment-placeholder">
        </aside>
        <div class="col-md-10">
            <div class="container item-container">
                <section id="item_header" class="row itemHeader centeredItems">
                    <figure class="col-md-2  thumbnail  itemBorder">
                        @if($pictures = json_decode($cultureItem->pictures))
                            <a href="{{asset('images/culture/'.$pictures[0])}}" data-lightbox="Item" data-title="picture">
                                <img src="{{asset('images/culture/'.$pictures[0])}}" alt="thumbnail" class="img-thumbnail">
                            </a>
                        @else
                            <img src="{{asset('images/culture/book.jpg')}}" alt="brak zdjęcia" class="img-thumbnail">
                        @endif
                    </figure>
                    <hgroup class="col-md-4  itemBorder">
                        <h3 class=" itemTitle">
                            {{$cultureItem->name}}
                        </h3>
                        @if($catAttr=json_decode($cultureCategory->attributes))
                            @if ($DecAttributes=json_decode($cultureItem->attributes))
                                @php
                                    foreach ($catAttr as $key => $attr) {
                                        if ($attr=='author') {
                                            echo('<h4 class=" itemAuthor">');
                                            echo($DecAttributes[$key]);
                                            echo('</h4>');
                                        }
                                        if ($attr=='date') {
                                         echo('<h4 class=" itemDate">');
                                         echo($DecAttributes[$key]);
                                         echo('</h4>');
                                        }
                                    }   
                                @endphp
                            @endif
                        @endif
                    </hgroup>
                    <div class="col-md-4  tagHolder  itemBorder container row">
                        @if($catAttr)
                            @foreach ($DecAttributes as $attribute)
                                
                                <div class=" col  tag">
                                    <p>#{{$attribute}}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-2 likeItem text-center ico ">
                        

                        @auth
                            <button class="btn  cultureLikeBtn @if($cultureItem->liked()) active @endif" data-id="{{$cultureItem->id}}" data-tool="tooltip" title="{{__('culture.likeItem')}}" data-placement="bottom">
                                <i class="fas fa-fire fa-5x"></i>
                                <span class="badge badge-pill likesCount @if($cultureItem->likeCount<=0 ) invisible @endif">
                                    {{$cultureItem->likeCount}}
                                </span>
                            </button>
                        @else
                        <button class="btn cultureLikeBtn">
                            <i class="fas fa-fire fa-5x"></i>
                            <span class="badge badge-pill likesCount @if($cultureItem->likeCount <=0 ) invisible @endif">
                                {{$cultureItem->likeCount}}
                            </span>
                        </button>

                        @endauth
                    </div>
                </section>
                <hr>
                <div class="row">
                    <section id="description" class="col-md-12 description">
                        {{$cultureItem->description}}
                        {{-- Desc>
                        Mateusz Morawczyk po raz kolejny zaskakuje nas smiałością swoich twierdzeń podatkowych.
                        Szczerze to nie ma się czemu dziwić, gdyż maszyna państwowa a także cały model pięćsetpluscentryczny nie może się utrzymać
                        przy obecnym poziomie opodatkowania.
                        Czytelnikowi oczywiście nie jest mówione to w prost, aczkolwiek wywierany jest na nim mocny przekaz podprogowy a propo
                        prodobrozmianizmu. --}}
                    </section>
                    {{--  --------------------------------------------------------------pictures --}}
                    <section id="picture_gallery" class="col-md-12 pictures row centeredItems">
                        
                        
                        @if ($pictures)
                        @foreach ($pictures as $picture)
                                <div class="col BookPicture">
                                    {{-- @if ($loop->iteration == 4)   
                                        <div class="mt-2"> 
                                            kek
                                            <a class="morePhotos" href="{{route('viewPost',['post' => $cultureItem->id])}}" target="__blank">{{__('profile.remainingPhotos')}} ({{$loop->remaining+1}})</a>
                                        </div>
                                    @break
                                    @else --}}
                                        <a href="{{asset('images/culture/'.$picture)}}" data-lightbox="post{{$cultureItem->id}}-Pictures">
                                            <img class="img-thumbnail" src="{{asset('images/culture/'.$picture)}}" alt="Culture Picture">
                                        </a>
                                    {{-- @endif --}}
                                </div>
                            @endforeach
                        @endif
                    </section>
                </div>
                <hr>
                <section id="item_review" class="row  review">
                    {{$cultureItem->review}}
                    
                </section>
                <hr>
                <section class="row  similar-entries">
                    <div class="col-md-12 simiarEntries center">
                        <h4>{{__('culture.similarItems')}}:</h4>
                    </div>
                    @foreach ($similarEntries as $entry)
                    <a class="col-md-4  thumbnail  itemBorder  itemBorder-left container row" href="{{asset('culture/'.$entry->name_slug)}}">
                        <figure class="col nextThumbnail">
                            @if ($pic=json_decode($entry->pictures))  
                            <img src="{{asset('images/culture/'.$pic[0])}}" alt="kek" class="img-thumbnail img-thumbnail-small">
                            @else
                            <img src="{{asset('images/culture/book.jpg')}}" alt="kek" class="img-thumbnail img-thumbnail-small">
                            @endif
                        </figure>
                        <figcaption class="col  anotherItem">
                            <h5 class="suggestedTitle">
                                {{$entry->name}}
                            </h5>
                        </figcaption>
                    </a>
                    @endforeach
                </section>
                <hr>
                <section class="row  comments">
                    comments

                    <br>
                    /coments
                </section>
            </div>
        </div>
        <aside class="col-md-1  advertisment-placeholder">
        </aside>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        var base_url= "{{url('/')}}";
    </script>
    <script src="{{asset('js/culture.js')}}"></script>
@endpush