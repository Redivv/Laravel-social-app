@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.cultureItem')}}
    </title>
@endsection

@section('content')
<div class="container-flex culture_page-container">
    <div class="row">
        <aside class="col-md-2 advertisment-placeholder">
            ad
        </aside>
        <div class="col-md-8">
            <div class="container item-container">
                <div class="row  centeredItems">
                    <div class="col-md-2  thumbnail  itemBorder">
                        @if($pictures = json_decode($cultureItem->pictures))
                            <a href="{{asset('images/culture/'.$pictures[0])}}" data-lightbox="Item" data-title="picture">
                                <img src="{{asset('images/culture/'.$pictures[0])}}" alt="thumbnail" class="img-thumbnail">
                            </a>
                        @else
                            <img src="images/culture/book.jpg" alt="brak zdjęcia" class="img-thumbnail">
                        @endif

            
                    </div>
                    <hgroup class="col-md-4  itemBorder">
                        <h3 class=" itemTitle">
                            {{$cultureItem->name}}
                            {{-- O Obrotach Ceł Niebiańskch --}}
                        </h3>
                        @if ($DecAttributes=json_decode($cultureItem->attributes))
                            
                        <h4 class=" itemAuthor">
                            {{$DecAttributes[0]}}
                        </h4>
                        <h6 class=" itemDate">
                            {{$DecAttributes[1]}}
                        </h6>
                        @endif
                    </hgroup>
                    <div class="col-md-4  tagHolder  itemBorder container row">
                        @foreach ($DecAttributes as $attribute)
                            
                            <div class=" col  tag">
                                <p>#{{$attribute}}</p>
                            </div>
                        @endforeach
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
                </div>
                <hr>
                <div class="row">
                    <section class="col-md-12">
                        {{$cultureItem->description}}
                        {{-- Desc>
                        Mateusz Morawczyk po raz kolejny zaskakuje nas smiałością swoich twierdzeń podatkowych.
                        Szczerze to nie ma się czemu dziwić, gdyż maszyna państwowa a także cały model pięćsetpluscentryczny nie może się utrzymać
                        przy obecnym poziomie opodatkowania.
                        Czytelnikowi oczywiście nie jest mówione to w prost, aczkolwiek wywierany jest na nim mocny przekaz podprogowy a propo
                        prodobrozmianizmu. --}}
                    </section>
                    {{--  --------------------------------------------------------------pictures --}}
                    <section class="col-md-12  pictures row">
                        
                        
                        @if ($pictures)
                        @foreach ($pictures as $picture)
                                <div class="col-md-3 BookPicture">
                                    @if ($loop->iteration == 4)   
                                        <div class="mt-2"> 
                                            kek
                                            {{-- <a class="morePhotos" href="{{route('viewPost',['post' => $cultureItem->id])}}" target="__blank">{{__('profile.remainingPhotos')}} ({{$loop->remaining+1}})</a> --}}
                                        </div>
                                    @break
                                    @else
                                        <a href="{{asset('images/culture/'.$picture)}}" data-lightbox="post{{$cultureItem->id}}-Pictures">
                                            <img class="img-thumbnail" src="{{asset('images/culture/'.$picture)}}" alt="Culture Picture">
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </section>
                </div>
                <hr>
                <section class="row  review">
                    {{$cultureItem->review}}
                    
                </section>
                <hr>
                <div class="row  similar-entries">
                    <div class="col-md-12 simiarEntries center">
                        {{__('culture.similarItems')}}:
                    </div>
                    @foreach ($similarEntries as $entry)
                    <a class="col-md-4  thumbnail  itemBorder  itemBorder-left container row" href="{{asset('culture/'.$entry->name_slug)}}">
                        <div class="col-md-12  nextThumbnail">
                            @if ($pic=json_decode($entry->pictures))  
                            <img src="{{asset('images/culture/'.$pic[0])}}" alt="kek" class="img-thumbnail img-thumbnail-small">
                            @else
                            <img src="{{asset('images/culture/book.jpg')}}" alt="kek" class="img-thumbnail img-thumbnail-small">
                            @endif
                        </div>
                        <div class="col  anotherItem">
                            <h5 class="suggestedTitle">
                                {{$entry->name}}
                            </h5>
                        </div>
                    </a>
                    @endforeach
                </div>
                <hr>
                <section class="row  comments">
                    comments
                </section>
            </div>
        </div>
        <aside class="col-md-2  advertisment-placeholder">
            ad
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