@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
    <form action="{{ route('review.update', ['restaurant_id' => $restaurant->id, 'review_id' => $review->id]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="review-page__content">
            <div class="review-page__content-inner">
                <div class="review-page__content-left">
                    <div class="left-top">
                        <p>今回のご利用はいかがでしたか？</p>
                    </div>
                    <div class="restaurant-card">
                        <div class="restaurant-card__img">
                            <img src="{{ $review->image ? asset('storage/' . $review->image) : $restaurant->image }}"
                                alt="{{ $restaurant->name }}">
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
                </div>
                <div class="review-page__line"></div>
                <div class="review-page__content-right">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="rating">体験を評価してください</label>
                        <div class="star-rating">
                            <span data-rating="1" class="{{ $review->rating >= 1 ? 'selected' : '' }}">★</span>
                            <span data-rating="2" class="{{ $review->rating >= 2 ? 'selected' : '' }}">★</span>
                            <span data-rating="3" class="{{ $review->rating >= 3 ? 'selected' : '' }}">★</span>
                            <span data-rating="4" class="{{ $review->rating >= 4 ? 'selected' : '' }}">★</span>
                            <span data-rating="5" class="{{ $review->rating >= 5 ? 'selected' : '' }}">★</span>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="{{ old('rating', $review->rating) }}">
                    </div>
                    <div class="form-group form-group-review">
                        <label for="review">口コミを投稿</label>
                        <textarea id="review" name="review" class="form-control" placeholder="カジュアルな夜のお出かけにおすすめのスポット" required>{{ old('review', $review->review) }}</textarea>
                        <div class="length">
                            <p id="char-count" class="char-count">{{ strlen(old('review', $review->review)) }}/400（最大文字数）
                            </p>
                            <p id="char-error" class="char-error">400文字以内で入力してください。</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image">画像の追加</label>
                        <div id="image-dropzone" class="dropzone"
                            style="background-image: url('{{ $review->image ? asset('storage/' . $review->image) : 'none' }}')">
                            クリックして画像を追加<br><span>またはドラッグアンドドロップ</span>
                            <input type="file" id="image" name="image" accept="image/jpeg, image/png"
                                style="display: none;" onchange="previewImage(event)">
                        </div>
                        <div id="error-message" class="error-message">
                            対応していないファイル形式です。JPEGまたはPNG形式の画像をアップロードしてください。</div>
                    </div>
                </div>
            </div>
            <div class="button">
                <button type="submit" class="submit">口コミを更新</button>
            </div>
        </div>
    </form>

    <script src="{{ asset('js/favorite.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/5dc5d1378e.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 星評価関連の変数を定義
            const stars = document.querySelectorAll('.star-rating span'); // すべての星を取得
            const ratingInput = document.getElementById('rating'); // 隠し入力フィールドを取得

            // すべての星にクリックイベントを追加
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating'); // クリックされた星の評価を取得
                    ratingInput.value = rating; // 隠し入力フィールドに評価を設定

                    // 星の選択状態を更新
                    stars.forEach(s => {
                        s.classList.remove('selected'); // すべての星の選択状態を解除
                        if (s.getAttribute('data-rating') <= rating) {
                            s.classList.add('selected'); // 評価以下の星を選択状態にする
                        }
                    });
                });
            });

            // ページロード時に初期状態の星の設定
            if (ratingInput.value) {
                stars.forEach(star => {
                    if (star.getAttribute('data-rating') <= ratingInput.value) {
                        star.classList.add('selected'); // 評価以下の星を選択状態にする
                    }
                });
            }

            // 画像アップロード関連の変数を定義
            const dropzone = document.getElementById('image-dropzone'); // ドロップゾーンを取得
            const fileInput = document.getElementById('image'); // ファイル入力を取得
            const errorMessage = document.getElementById('error-message'); // エラーメッセージを取得

            // ドロップゾーンをクリックしたときにファイル入力をクリック
            dropzone.addEventListener('click', () => fileInput.click());

            // ドラッグオーバー時にドロップゾーンのスタイルを変更
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault(); // デフォルトのドラッグオーバー動作を無効化
            });

            // ドロップイベント時の処理
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault(); // デフォルトのドロップ動作を無効化
                const files = e.dataTransfer.files; // ドロップされたファイルを取得
                if (files.length > 0) {
                    fileInput.files = files; // ファイル入力に設定
                    previewImage({
                        target: fileInput
                    }); // プレビューを表示
                }
            });
        });

        // プレビュー画像を表示する関数
        function previewImage(event) {
            const file = event.target.files[0]; // アップロードされたファイルを取得
            const reader = new FileReader(); // FileReaderオブジェクトを作成

            reader.onload = function(e) {
                const dropzone = document.getElementById('image-dropzone'); // ドロップゾーンを取得
                dropzone.style.backgroundImage = `url(${e.target.result})`; // ドロップゾーンにプレビュー画像を設定
            };

            reader.readAsDataURL(file); // 画像ファイルをData URLとして読み込む
        }
    </script>
@endsection
