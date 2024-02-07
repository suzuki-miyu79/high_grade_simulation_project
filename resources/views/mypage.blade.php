@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <div class="mypage__content">
        <div class="reservation-status">
            <h2 class="reservation-status__title">予約状況</h2>
            @foreach ($numberedReservations as $reservation)
                <div class="reservation-status__content">
                    <div class="reservation-status__content-top">
                        <div class="reservation-status__content-top__icon">
                            <img src="image/clock-icon.png" alt="">
                        </div>
                        <div class="reservation-status__content-top__order">
                            <p>予約{{ $reservation->number }}</p>
                        </div>
                        <div class="reservation-status__content-top__cancel">
                            <a href="{{ route('reservation.cancel', ['reservation_id' => $reservation->id]) }}">
                            <img src="image/cancel-icon.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="reservation-status__table">
                        <table class="reservation-status__inner">
                            <tr class="reservation-status__info">
                                <th class="reservation-status__info-column">Shop</th>
                                <td class="reservation-status__info-data-entry">{{ $reservation->restaurant->name }}</td>
                            </tr>
                            <tr class="reservation-status__info">
                                <th class="reservation-status__info-column">Date</th>
                                <td class="reservation-status__info-data-entry">{{ $reservation->date }}</td>
                            </tr>
                            <tr class="reservation-status__info">
                                <th class="reservation-status__info-column">Time</th>
                                <td class="reservation-status__info-data-entry">{{ $reservation->formattedTime }}</td>
                            </tr>
                            <tr class="reservation-status__info">
                                <th class="reservation-status__info-column">Number</th>
                                <td class="reservation-status__info-data-entry">{{ $reservation->number }}人</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="favolite-restaurants">
            <p class="user-name">{{ Auth::user()->name }}さん</p>
            <h2 class="favolite-restaurants__title">お気に入り店舗</h2>
            <div class="favolite-restaurants__content">
                @foreach ($restaurants as $restaurant)
                    <div class="restaurant-card">
                        <div class="restaurant-card__img">
                            <img src="{{ $restaurant->image }}" alt="{{ $restaurant->name }}">
                        </div>
                        <div class="restaurant-card__content">
                            <div class="content__name">
                                <p>{{ $restaurant->name }}</p>
                            </div>
                            <div class="content__tag">
                                <div class="content__tag-area">
                                    <p>#{{ $restaurant->area->prefectures_name }}</p>
                                </div>
                                <div class="content__tag-genre">
                                    <p>#{{ $restaurant->genre->genre_name }}</p>
                                </div>
                            </div>
                            <div class="restaurant-card__content-bottom">
                                <div class="content-bottom__detail-button">
                                    <a href="{{ route('restaurant.detail', ['restaurant_id' => $restaurant->id]) }}"
                                        class="content-bottom__detail-button-submit">詳しくみる</a>
                                </div>
                                <div class="content-bottom__favorite-button">
                                    <button id="favoriteButton" class="content-bottom__favorite-button-submit"
                                        data-restaurant-id="{{ $restaurant->id }}"
                                        data-logged-in="{{ auth()->check() ? 'true' : 'false' }}"
                                        data-favorited="{{ $restaurant->isFavoritedByUser(auth()->user()) ? 'true' : 'false' }}">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="{{ asset('js/favorite.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/5dc5d1378e.js" crossorigin="anonymous"></script>
@endsection
