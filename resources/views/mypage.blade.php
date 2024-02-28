@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <div class="mypage__content">
        <div class="reservation-status">
            <h2 class="reservation-status__title">予約状況</h2>
            @foreach ($orderedReservations as $reservation)
                <div class="reservation-status__content">
                    <div class="reservation-status__content-top">
                        <div class="reservation-status__content-top__icon">
                            <img src="image/clock-icon.png" alt="">
                        </div>
                        <div class="reservation-status__content-top__order">
                            <p>予約{{ $loop->index + 1 }}</p>
                        </div>
                        <div class="reservation-status__content-top__cancel">
                            <a href="{{ route('reservation.cancel', ['reservationId' => $reservation->id]) }}">
                                <img src="image/cancel-icon.png" alt="">
                            </a>
                        </div>
                    </div>

                    <!-- 代表的なエラーメッセージの表示 -->
                    @if ($errors->any())
                        <div class="alert">
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

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
                            <tr>
                                <td colspan="3">
                                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- 予約変更/レビュー投稿ボタン -->
                    @if ($reservation->hasPassed())
                        <!-- 予約日時が過ぎた場合レビューボタンを表示 -->
                        <div id="review-button-{{ $reservation->id }}" class="reservation-status__modify">
                            <div class="reservation-status__review-button">
                                <button class="reservation-status__review-button-submit" href="#"
                                    onclick="toggleReviewForm('{{ $reservation->id }}')">レビューを書く</button>
                            </div>
                        </div>
                    @else
                        <!-- 予約日時が過ぎていない場合予約変更ボタンを表示 -->
                        <div id="modify-button-{{ $reservation->id }}" class="reservation-status__modify">
                            <div class="reservation-status__modify-button">
                                <button class="reservation-status__modify-button-submit" href="#"
                                    onclick="toggleReservationForm('{{ $reservation->id }}')">予約内容を変更する</button>
                            </div>
                        </div>
                    @endif

                    {{-- toggleReservationFormで表示するフォーム --}}
                    <div id="reservation-form-{{ $reservation->id }}" style="display: none;">
                        <!-- 予約内容変更フォーム -->
                        <form action="{{ route('reservation.update', ['reservationId' => $reservation->id]) }}"
                            method="post">
                            @csrf
                            @method('PUT')
                            {{-- 予約日選択 --}}
                            <div class="reservation__form">
                                {{-- 初期値を予約情報から取得 --}}
                                <input type="date" id="reservation_date" name="reservation_date"
                                    value="{{ old('reservation_date', $reservation->date) }}">
                                @error('reservation_date')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- 予約時間選択 --}}
                            <div class="reservation__form">
                                <select id="reservation_time" name="reservation_time">
                                    {{-- 開始時間と終了時間を定義 --}}
                                    @php
                                        $startTime = strtotime('0:00');
                                        $endTime = strtotime('23:30');
                                    @endphp
                                    @for ($time = $startTime; $time <= $endTime; $time += 1800)
                                        {{-- 30分ずつ増加 --}}
                                        @php
                                            $formattedTime = date('H:i', $time);
                                        @endphp
                                        {{-- 初期値を予約情報から取得 --}}
                                        <option value="{{ $formattedTime }}"
                                            {{ old('reservation_time', $reservation->formattedTime) == $formattedTime ? 'selected' : '' }}>
                                            {{ $formattedTime }}
                                        </option>
                                    @endfor
                                </select>
                                @error('reservation_time')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- 予約人数選択 --}}
                            <div class="reservation__form">
                                <select id="reservation_number" name="reservation_number">
                                    @for ($n = 1; $n <= 20; $n++)
                                        {{-- 初期値を予約情報から取得 --}}
                                        <option value="{{ $n }}"
                                            {{ old('reservation_number', $reservation->number) == $n ? 'selected' : '' }}>
                                            {{ $n }}人</option>
                                    @endfor
                                </select>
                                @error('reservation_number')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="reservation-status__modify-button">
                                <button class="reservation-status__modify-button-submit"
                                    type="submit">この内容で予約を変更する</button>
                            </div>
                        </form>
                    </div>

                    {{-- toggleReviewFormで表示するフォーム --}}
                    <div id="review-form-{{ $reservation->id }}" style="display: none;">
                        {{-- レビュー投稿フォーム --}}
                        <form action="{{ route('reviews.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="restaurant_id" value="{{ $reservation->restaurant->id }}">
                            <div class="evaluation">
                                <p>お店の満足度</p>
                                <select name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="comment">
                                <p>コメント</p>
                                <textarea name="comment" rows="4"></textarea>
                            </div>
                            <div class="reservation-status__review-button">
                                <button class="reservation-status__review-button-submit" type="submit">レビューを送信する</button>
                            </div>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="favorite-restaurants">
            <p class="user-name">{{ Auth::user()->name }}さん</p>
            <h2 class="favorite-restaurants__title">お気に入り店舗</h2>
            <div class="favorite-restaurants__content">
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
    <script>
        // 予約変更フォームを表示する処理
        function toggleReservationForm(reservationId) {
            var button = document.getElementById('modify-button-' + reservationId);
            var form = document.getElementById('reservation-form-' + reservationId);

            if (button && form) {
                button.style.display = 'none'; // ボタンを非表示にする
                form.style.display = 'block'; // フォームを表示する
            }
        }

        // レビューフォームを表示する処理
        function toggleReviewForm(reservationId) {
            var button = document.getElementById('review-button-' + reservationId);
            var form = document.getElementById('review-form-' + reservationId);

            if (button && form) {
                button.style.display = 'none'; // ボタンを非表示にする
                form.style.display = 'block'; // フォームを表示する
            }
        }
    </script>
@endsection
