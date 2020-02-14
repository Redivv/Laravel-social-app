@extends('layouts.app')

@section('content')
<div class="container-flex culture_page-container">
    <div class="row">
        <aside class="col-md-2 culture_advertisment-placeholder">
            add
        </aside>
        <div class="col-md-8">
            <div class="container culture_item-container">
                <div class="row culture_centeredItems">
                    <div class="col-md-2 culture_itemBorder">
                       Thumbnail
                       <br>
            
                    <!--<a href="{{asset('img/safo_logo.png')}}" data-lightbox="Profile" data-title="kek">-->
                        <img src="{{asset('img/safo_logo.png')}}" alt="kek" class="img-thumbnail" height="42" width="84">
                    <!--</a>-->
            
            
                    </div>
                    <hgroup class="col-md-4 culture_itemBorder">
                        <h3 class="culture_itemTitle">
                            
                            O Obrotach ceł niebieskich
                        </h3>
                        <h4 class="culture_itemAuthor">
                            M.Morawczyk
                        </h4>
                        <h6 class="culture_itemDate">
                            6.9.69420
                        </h6>
                    </hgroup>
                    <div class="col-md-4 culture_tagHolder culture_itemBorder container row">
                        <div class=" col-3 culture_tag">#finanse</div>
                        <div class=" col-3 culture_tag">#dobra</div>
                        <div class=" col-3 culture_tag">#zmiana</div>
                        <div class=" col-3 culture_tag">#zmiana</div>
                        <div class=" col-3 culture_tag">#zmiana</div>
                        <div class=" col-3 culture_tag">#zmiana</div>
                    </div>
                    <div class="col-md-2 culture_likeItem text-center ico ">
                        <button class="btn ">
                            <i class="fas fa-fire fa-5x"></i>
                            <span class="badge badge-pill likesCount">5</span>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <section class="col-md-12">
                        Desc>
                        Mateusz Morawczyk po raz kolejny zaskakuje nas smiałością swoich twierdzeń podatkowych.
                        Szczerze to nie ma się czemu dziwić, gdyż maszyna państwowa a także cały model pięćsetpluscentryczny nie może się utrzymać
                        przy obecnym poziomie opodatkowania.
                        Czytelnikowi oczywiście nie jest mówione to w prost, aczkolwiek wywierany jest na nim mocny przekaz podprogowy a propo
                        prodobrozmianizmu.
                    </section>
                    <section class="col-md-12">
                        Pictures
                    </section>
                </div>
                <hr>
                <section class="row culture_review">
                    👏👏 meme review
                </section>
                <hr>
                <div class="row culture_similar-entries">
                    <article class="col-md-4 culture_thumbnails">
                        entry 1
                    </article>
                    <article class="col-md-4 culture_thumbnails">
                        entry 2
                    </article>
                    <article class="col-md-4 culture_thumbnails">
                        entry 3
                    </article>
                </div>
                <hr>
                <section class="row culture_comments">
                    comments
                </section>
            </div>
        </div>
        <aside class="col-md-2 culture_advertisment-placeholder">
            ad
        </aside>
    </div>
</div>

@endsection