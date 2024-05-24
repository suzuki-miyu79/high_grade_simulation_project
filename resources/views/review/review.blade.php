@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
    <form action="{{ route('review.store', ['restaurant_id' => $restaurant->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="review-page__content">
            <div class="review-page__content-inner">
                <div class="review-page__content-left">
                    <div class="left-top">
                        <p>今回のご利用はいかがでしたか？</p>
                    </div>
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
                            <span data-rating="1">★</span>
                            <span class="star-rating2" data-rating="2">★</span>
                            <span class="star-rating2" data-rating="3">★</span>
                            <span class="star-rating2" data-rating="4">★</span>
                            <span class="star-rating2" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="{{ old('rating') }}">
                    </div>
                    <div class="form-group form-group-review">
                        <label for="review">口コミを投稿</label>
                        <textarea id="review" name="review" class="form-control" placeholder="カジュアルな夜のお出かけにおすすめのスポット" required>{{ old('review') }}</textarea>
                        <div class="length">
                            <p id="char-count" class="char-count">0/400（最大文字数）</p>
                            <p id="char-error" class="char-error">400文字以内で入力してください。</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image">画像の追加</label>
                        <div id="image-dropzone" class="dropzone">
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
                <button type="submit" class="submit">口コミを投稿</button>
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

                // 初期状態の星の設定
                if (ratingInput.value && ratingInput.value >= star.getAttribute('data-rating')) {
                    star.classList.add('selected'); // 評価以下の星を選択状態にする
                }
            });

            // 画像アップロード関連の変数を定義
            const dropzone = document.getElementById('image-dropzone'); // ドロップゾーンを取得
            const fileInput = document.getElementById('image'); // ファイル入力を取得
            const errorMessage = document.getElementById('error-message'); // エラーメッセージを取得

            // ドロップゾーンをクリックしたときにファイル入力をクリック
            dropzone.addEventListener('click', () => fileInput.click());

            // ドラッグオーバー時にドロップゾーンのスタイルを変更
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault(); // デフォルトのドラッグオーバー動作を無効化
                dropzone.classList.add('dragover'); // ドロップゾーンにドラッグオーバークラスを追加
            });

            // ドラッグリーブ時にドロップゾーンのスタイルを元に戻す
            dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));

            // ドロップイベント時の処理
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault(); // デフォルトのドロップ動作を無効化
                dropzone.classList.remove('dragover'); // ドロップゾーンのドラッグオーバークラスを削除

                if (e.dataTransfer.files.length) {
                    const file = e.dataTransfer.files[0]; // ドロップされたファイルを取得
                    if (isValidFileType(file)) {
                        fileInput.files = e.dataTransfer.files; // ファイル入力に設定
                        previewImage({
                            target: fileInput
                        }); // プレビューを表示
                        errorMessage.style.display = 'none'; // エラーメッセージを非表示に
                    } else {
                        fileInput.value = ''; // ファイル入力をクリア
                        dropzone.style.backgroundImage = 'none'; // プレビューを非表示に
                        errorMessage.style.display = 'block'; // エラーメッセージを表示
                    }
                }
            });

            // ファイルの形式がJPEGまたはPNGかをチェックする関数
            function isValidFileType(file) {
                const validTypes = ['image/jpeg', 'image/png']; // 有効なファイル形式を定義
                return validTypes.includes(file.type); // ファイルの形式が有効かをチェック
            }

            // 商品画像の表示
            window.previewImage = function(event) {
                const input = event.target;
                const reader = new FileReader();

                reader.onload = function() {
                    const dropzone = document.getElementById('image-dropzone');
                    dropzone.style.backgroundImage = `url('${reader.result}')`;
                };

                if (input.files && input.files[0]) {
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // 文字数カウントとエラーチェック
            const reviewTextarea = document.getElementById('review'); // 口コミのテキストエリアを取得
            const charCount = document.getElementById('char-count'); // 文字数表示の要素を取得
            const charError = document.getElementById('char-error'); // エラーメッセージの要素を取得
            const submitBtn = document.getElementById('submit-btn'); // 送信ボタンを取得

            // テキストエリアの入力イベントを監視
            reviewTextarea.addEventListener('input', function() {
                const currentLength = reviewTextarea.value.length; // 現在の入力文字数を取得
                charCount.textContent = `${currentLength}/400（最大文字数）`; // 文字数表示を更新

                // 文字数が400を超えたらエラーメッセージを表示し、送信ボタンを無効化
                if (currentLength > 400) {
                    charError.style.display = 'block'; // エラーメッセージを表示
                    submitBtn.disabled = true; // 送信ボタンを無効化
                } else {
                    charError.style.display = 'none'; // エラーメッセージを非表示
                    submitBtn.disabled = false; // 送信ボタンを有効化
                }
            });

            // ページロード時に初期文字数を設定
            const initialLength = reviewTextarea.value.length;
            charCount.textContent = `${initialLength}/400（最大文字数）`;
        });
    </script>
@endsection
