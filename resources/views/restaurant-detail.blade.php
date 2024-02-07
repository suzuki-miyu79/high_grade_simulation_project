@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/restaurant-detail.css') }}">
@endsection

@section('content')
    <div class="detail-page__content">
        <div class="detail-page__content-detail">
            <div class="nav">
                <button class="nav__icon" id="openMenuButton" onclick="openMenu()">
                    <div class="nav__icon-line">
                        <div class="line1"></div>
                        <div class="line2"></div>
                        <div class="line3"></div>
                    </div>
                </button>
                <h1>Rese</h1>
            </div>
            <div class="detail-top">
                {{-- ひとつ前のページに戻る --}}
                <a href="javascript:history.back()" class="detail-top__button">&lt;</a>
                <h2 id="restaurantName">{{ $restaurant->name }}</h2>
            </div>
            <div class="detail-image">
                <img src="{{ $restaurant->image }}" alt="{{ $restaurant->name }}">
            </div>
            <div class="detail-tag">
                <div class="detail-tag-area">
                    <p>#{{ $restaurant->area->prefectures_name }}</p>
                </div>
                <div class="detail-tag-genre">
                    <p>#{{ $restaurant->genre->genre_name }}</p>
                </div>
            </div>
            <div class="detail-description">
                {{ $restaurant->overview }}
            </div>
        </div>
        <div class="detail-page__content-reservation">
            <div class="detail-page__content-reservation-inner">
                <h3>予約</h3>
                <form action="{{ route('reservation.store', ['restaurant_id' => $restaurant->id]) }}" method="post">
                    @csrf
                    {{-- 予約日選択 --}}
                    <div class="reservation__form">
                        <input type="date" id="reservation_date" name="reservation_date"
                            value="{{ old('reservation_date', now()->format('Y-m-d')) }}"
                            onchange="updateConfirmationInfo()">
                    </div>

                    {{-- 予約時間選択 --}}
                    <div class="reservation__form">
                        <select id="reservation_time" name="reservation_time" onchange="updateConfirmationInfo()">
                            {{-- 開始時間と終了時間を定義 --}}
                            @php
                                $startTime = strtotime('17:00');
                                $endTime = strtotime('22:00');
                            @endphp

                            @for ($time = $startTime; $time <= $endTime; $time += 1800)
                                {{-- 30分ずつ増加 --}}
                                @php
                                    $formattedTime = date('H:i', $time);
                                @endphp
                                <option value="{{ $formattedTime }}"
                                    {{ old('reservation_time') == $formattedTime ? 'selected' : '' }}>
                                    {{ $formattedTime }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- 予約人数選択 --}}
                    <div class="reservation__form">
                        <select id="reservation_number" name="reservation_number" onchange="updateConfirmationInfo()">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}"
                                    {{ old('reservation_number') == $i ? 'selected' : '' }}>
                                    {{ $i }}人</option>
                            @endfor
                        </select>
                    </div>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                    {{-- 予約内容の確認 --}}
                    <div class="confirmation__table">
                        <table class="confirmation__inner">
                            <tr class="confirmation__info">
                                <th class="confirmation__info-column">Shop</th>
                                <td class="confirmation__info-data-entry" id="confirmation_restaurant_name">
                                    {{ $restaurant->name }}</td>
                            </tr>
                            <tr class="confirmation__info">
                                <th class="confirmation__info-column">Date</th>
                                <td class="confirmation__info-data-entry" id="confirmation_date">
                                    {{ $formData['reservation_date'] ?? '' }}</td>
                            </tr>
                            <tr class="confirmation__info">
                                <th class="confirmation__info-column">Time</th>
                                <td class="confirmation__info-data-entry" id="confirmation_time">
                                    {{ $formData['reservation_time'] ?? '' }}</td>
                            </tr>
                            <tr class="confirmation__info">
                                <th class="confirmation__info-column">Number</th>
                                <td class="confirmation__info-data-entry" id="confirmation_number">
                                    {{ $formData['reservation_number'] ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="reservation__button">
                        <button type="submit" class="reservation__button-submit">予約する</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- 予約内容の即時表示 --}}
        <script>
            // セレクトボックスの選択が変更されたときに呼び出される関数
            function updateConfirmationInfo() {
                var date = document.getElementById('reservation_date').value;
                var time = document.getElementById('reservation_time').value;
                var number = document.getElementById('reservation_number').value;

                // ビューの更新
                document.getElementById('confirmation_date').innerText = date;
                document.getElementById('confirmation_time').innerText = time;
                document.getElementById('confirmation_number').innerText = number + '人';
            }
        </script>
    @endsection
