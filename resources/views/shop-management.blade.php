@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop-management.css') }}">
@endsection

@section('content')
    <div class="shop-management__content">
        <div class="reservation-info">
            <div class="restaurant-info__select">
                <p>店舗選択</p>
                <select name="restaurant" id="restaurant">
                    <option value="{{ $restaurantName }}">{{ $restaurantName }}</option>
                </select>
            </div>
            <h2 class="reservation-info__title">予約情報</h2>

            @foreach ($reservations as $reservation)
                <div class="reservation-info__content">
                    <div class="reservation-info__content-top">
                        <div class="reservation-info__content-top__icon">
                            <img src="image/clock-icon.png" alt="">
                        </div>
                        <div class="reservation-info__content-top__order">
                            <p>情報</p>
                        </div>
                    </div>
                    <div class="reservation-info__table">
                        <table class="reservation-info__inner">
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Name</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->restaurant->name }}</td>
                            </tr>
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Date</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->date }}</td>
                            </tr>
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Time</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->time }}</td>
                            </tr>
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Number</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->number }}人</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="restaurant-info">
            <p class="user-name">店舗代表者</p>
            <h2 class="restaurant-info__title">店舗情報</h2>
            <div class="restaurant-info__content">
                <div class="restaurant-info__create">
                    <button>新規作成</button>
                </div>
                <div class="restaurant-info__create-inner">
                    <div class="restaurant-info__form">
                        <p>店名</p>
                        <input type="text">
                    </div>
                    <div class="restaurant-info__form">
                        <form action="{{ route('store.image') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="image">
                            <button type="submit">画像をアップロード</button>
                        </form>
                    </div>
                    <div class="restaurant-info__form">
                        <p>エリア</p>
                        <select name="" id=""></select>
                    </div>
                    <div class="restaurant-info__form">
                        <p>ジャンル</p>
                        <select name="" id=""></select>
                    </div>
                    <div class="restaurant-info__form--textarea">
                        <p>説明</p>
                        <textarea name="" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
