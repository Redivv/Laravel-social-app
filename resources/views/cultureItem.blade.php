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
                    <div class="col-md-2 culture_thumbnail culture_itemBorder">
                    <!--<a href="{{asset('img/safo_logo.png')}}" data-lightbox="Profile" data-title="kek">-->
                        <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="kek" class="img-thumbnail">
                    <!--</a>-->
            
            
                    </div>
                    <hgroup class="col-md-4 culture_itemBorder">
                        <h3 class="culture_itemTitle">
                            
                            O Obrotach Ceł Niebiańskch
                        </h3>
                        <h4 class="culture_itemAuthor">
                            M.Morawiecki
                        </h4>
                        <h6 class="culture_itemDate">
                            6.9.69420
                        </h6>
                    </hgroup>
                    <div class="col-md-4 culture_tagHolder culture_itemBorder container row">
                        <div class=" col culture_tag" data-tool="tooltip" data-placement="bottom" title="Wyszukaj po tagu: #finanse">
                            <p>#finanse</p>
                        </div>
                        <div class=" col culture_tag">
                            <p>#dobra</p>
                        </div>
                        <div class=" col culture_tag">
                            <p>#zmiana</p>
                        </div>
                        <div class=" col culture_tag">
                            <p>#państwo polzgie</p>
                        </div>
                        <div class=" col culture_tag">
                            <p>#cebulacy</p>
                        </div>
                        <div class=" col culture_tag">
                            <p>#fanatycy 500+</p>
                        </div>
                    </div>
                    <div class="col-md-2 culture_likeItem text-center ico ">
                        <button class="btn culture_likeBtn ">
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
                    <section class="col-md-12 culture_pictures">
                        <div class="col-md-3 BookPicture">
                            <img src="{{asset('img/profile-pictures/default-picture.png')}}" class="img-thumbnail" alt="kek">
                        </div>
                    </section>
                </div>
                <hr>
                <section class="row culture_review">
                    👏👏 meme review
                </section>
                <hr>
                <div class="row culture_similar-entries">
                    <article class="col-md-4 culture_thumbnail culture_itemBorder culture_itemBorder-left">
                        <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="kek" class="img-thumbnail img-thumbnail-small">
                        entry 1
                    </article>
                    <article class="col-md-4 culture_thumbnail culture_itemBorder">
                        entry 2
                    </article>
                    <article class="col-md-4 culture_thumbnail culture_itemBorder">
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