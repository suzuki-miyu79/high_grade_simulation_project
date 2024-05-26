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
            {{-- 口コミ投稿または口コミ情報表示 --}}
            @if (is_null($userReview))
                @if (auth()->user()->role === null)
                    <div class="detail-review">
                        <a href="{{ route('review.create', ['restaurant_id' => $restaurant->id]) }}">口コミを投稿する</a>
                    </div>
                @endif
                <div class="info__button">
                    <button id="loadReviewsButton" class="info__button-submit">口コミを表示</button>
                </div>
            @else
                <div class="detail-review__info">
                    <div class="info__button">
                        <button id="loadReviewsButton" class="info__button-submit">全ての口コミ情報</button>
                    </div>
                    <div class="info__line-top"></div>
                    <div class="info__menu">
                        @if (auth()->check())
                            {{-- 編集リンク：口コミを書いた本人のみ表示 --}}
                            @if (auth()->user()->id === $userReview->user_id)
                                <a href="{{ route('review.edit', ['restaurant_id' => $restaurant->id, 'review_id' => $userReview->id]) }}"
                                    class="info__menu-edit">口コミを編集</a>
                            @endif

                            {{-- 削除リンク：口コミを書いた本人または管理者に表示 --}}
                            @if (auth()->user()->id === $userReview->user_id || auth()->user()->role === 'admin')
                                <form
                                    action="{{ route('review.destroy', ['restaurant_id' => $restaurant->id, 'review_id' => $userReview->id]) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="info__menu-delete">口コミを削除</button>
                                </form>
                            @endif
                        @endif
                    </div>
                    <div class="info-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star">★</span>
                        @endfor
                    </div>
                    <div class="info-review">
                        {{ $userReview->review }}
                    </div>
                </div>
            @endif
            <div class="info__line-top" style="display: none;"></div>
            <div class="info--other-review" style="display: none;">
                <div id="additionalReviews">
                    @foreach ($otherReviews as $review)
                        <div class="info-review-item">
                            <div class="info__line-top"></div>
                            <div class="info__menu">
                                @if (auth()->check())
                                    {{-- 削除リンク：管理者のみ表示 --}}
                                    @if (auth()->user()->role === 'admin')
                                        <form
                                            action="{{ route('review.destroy', ['restaurant_id' => $restaurant->id, 'review_id' => $userReview->id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="info__menu-delete">口コミを削除</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                            <div class="info-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star">★</span>
                                @endfor
                            </div>
                            <div class="info-review">{{ $review->review }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="info__line-bottom" style="display: none;"></div>
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
                        @error('reservation_date')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 予約時間選択 --}}
                    <div class="reservation__form">
                        <select id="reservation_time" name="reservation_time" onchange="updateConfirmationInfo()">
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
                                <option value="{{ $formattedTime }}"
                                    {{ old('reservation_time') == $formattedTime ? 'selected' : '' }}>
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
                        <select id="reservation_number" name="reservation_number" onchange="updateConfirmationInfo()">
                            @for ($n = 1; $n <= 20; $n++)
                                <option value="{{ $n }}"
                                    {{ old('reservation_number') == $n ? 'selected' : '' }}>
                                    {{ $n }}人</option>
                            @endfor
                        </select>
                        @error('reservation_number')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

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

        <script>
            // 予約内容の即時表示
            // セレクトボックスの選択が変更されたときにビューを更新する
            function updateConfirmationInfo() {
                var date = document.getElementById('reservation_date').value;
                var time = document.getElementById('reservation_time').value;
                var number = document.getElementById('reservation_number').value;

                // ビューの更新
                document.getElementById('confirmation_date').innerText = date;
                document.getElementById('confirmation_time').innerText = time;
                document.getElementById('confirmation_number').innerText = number + '人';
            }

            // ユーザーが投稿した口コミ表示
            document.addEventListener('DOMContentLoaded', function() {
                // ログインユーザーの評価値を取得。口コミがない場合は0に設定。
                const rating = {{ $userReview ? $userReview->rating : 0 }};
                const stars = document.querySelectorAll('.info-rating .star');

                for (let i = 0; i < stars.length; i++) {
                    if (i < rating) {
                        stars[i].classList.add('filled');
                    } else {
                        stars[i].classList.remove('filled');
                    }
                }
                // 口コミがある場合のみ、ページの一番下のラインを表示
                if (rating > 0) {
                    document.querySelector('.info__line-bottom').style.display = 'block';
                }

                // 口コミ表示ボタンのクリック動作
                document.getElementById('loadReviewsButton').addEventListener('click', function() {
                    // 他の口コミセクションを表示
                    document.querySelector('.info--other-review').style.display = 'block';

                    // 口コミアイテムを取得し、評価を設定
                    const reviewItems = document.querySelectorAll('.info-review-item');

                    // 他のユーザーの口コミ情報をJavaScriptの配列に変換
                    const otherReviews = {!! json_encode($otherReviews) !!};

                    if (otherReviews.length === 0 && rating === 0) {
                        // 上のラインを表示
                        document.querySelector('.info__line-top').style.display = 'block';
                        // 口コミがない場合、メッセージを表示
                        document.querySelector('.info--other-review').innerHTML = "この店舗の口コミはまだありません";
                        // 下のラインを表示
                        document.querySelector('.info__line-bottom').style.display = 'block';
                    } else {
                        // 各口コミアイテムについて処理を行う
                        reviewItems.forEach((item, index) => {
                            const rating = otherReviews[index] ? otherReviews[index].rating :
                                0; // 各口コミの評価を取得
                            const stars = item.querySelectorAll('.info-rating .star');

                            // 評価に基づいて星を表示する
                            stars.forEach((star, i) => {
                                if (i < rating) {
                                    star.classList.add('filled');
                                } else {
                                    star.classList.remove('filled');
                                }
                            });
                        });

                        // ページの一番下のラインを表示
                        document.querySelector('.info__line-bottom').style.display = 'block';
                    }
                });
            });
        </script>
    @endsection
