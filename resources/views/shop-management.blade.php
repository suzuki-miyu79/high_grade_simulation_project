@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop-management.css') }}">
@endsection

@section('content')
    <div class="shop-management__content">
        <div class="reservation-info">
            <div class="restaurant-info__select">
                <p>店舗選択：</p>
                <form method="GET" action="{{ route('shop-management.index') }}">
                    @csrf
                    <select name="selected_restaurant_id" id="selected_restaurant_id" onchange="this.form.submit()">
                        @if ($managedRestaurants->isEmpty())
                            <option value="" disabled selected>店舗情報なし</option>
                        @else
                            @foreach ($managedRestaurants as $restaurant)
                                <option value="{{ $restaurant->id }}"
                                    {{ $restaurant->id == $selectedRestaurantId ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </form>
            </div>
            <h2 class="reservation-info__title">予約情報</h2>

            @foreach ($reservations as $reservation)
                <div class="reservation-info__content">
                    <div class="reservation-info__content-top">
                        <div class="reservation-info__content-top__icon">
                            <img src="image/clock-icon.png" alt="">
                        </div>
                        <div class="reservation-info__content-top__order">
                            <p>情報{{ $loop->index + 1 }}</p>
                        </div>
                    </div>
                    <div class="reservation-info__table">
                        <table class="reservation-info__inner">
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Name</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->user->name }}</td>
                            </tr>
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Date</th>
                                <td class="reservation-info__data-data-entry">{{ $reservation->date }}</td>
                            </tr>
                            <tr class="reservation-info__data">
                                <th class="reservation-info__data-column">Time</th>
                                <td class="reservation-info__data-data-entry">
                                    {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
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
            <p class="authority-name">店舗管理ページ</p>
            <h2 class="restaurant-info__title">店舗情報</h2>
            <div class="restaurant-info__content">
                {{-- CSVインポート --}}
                <div class="restaurant-info__import-inner" id="import-form">
                    <form class="restaurant-info__import-form" id="import_restaurant_form"
                        action="{{ route('restaurant.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="restaurant-info__import-form--csv">
                            <label>CSVファイル：</label>
                            <input class="input-file" type="file" id="csv_file" name="csv_file" accept=".csv">
                        </div>
                        <div class="restaurant-info__import-form--button">
                            <button type="submit" id="import-button"
                                class="restaurant-info__import-form--button-submit">インポート</button>
                        </div>
                    </form>
                </div>
                {{-- 新規作成用ボタン --}}
                <div class="restaurant-info__create">
                    <button id="new-create-button" onclick="showNewForm()">新規作成</button>
                </div>
                {{-- 新規作成ボタンクリック時に表示する新規登録フォーム --}}
                <div class="restaurant-info__create-inner" id="new-form" style="display: none;">
                    <form id="new_restaurant_form" action="{{ route('restaurant.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="restaurant-info__form">
                            <label>店名：</label>
                            <input class="input-name" type="text" id="name" name="name" value="">
                        </div>
                        <div class="restaurant-info__form--image">
                            <label>画像：</label>
                            <input class="input-image" type="file" id="image" name="image">
                        </div>
                        <div class="restaurant-info__form">
                            <label>エリア：</label>
                            <select class="select-area" id="area" name="area">
                                <option value="">選択してください</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">
                                        {{ $area->prefectures_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="restaurant-info__form">
                            <label>ジャンル：</label>
                            <select class="select-genre" id="genre" name="genre">
                                <option value="">選択してください</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}">
                                        {{ $genre->genre_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="restaurant-info__form--textarea">
                            <label>説明：</label>
                            <textarea id="overview" name="overview" cols="30" rows="10"></textarea>
                        </div>
                        <div class="restaurant-info__form--button">
                            <button type="submit" id="register-button"
                                class="restaurant-info__form--button-submit">登録</button>
                        </div>
                    </form>
                </div>
                {{-- 管理店舗が存在する場合に表示する更新フォーム --}}
                <div class="restaurant-info__create-inner" id="update-form">
                    @if ($selectedRestaurant)
                        <form id="update_restaurant_form"
                            action="{{ route('restaurant.update', $selectedRestaurant->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="restaurant_id" name="restaurant_id"
                                value="{{ $selectedRestaurant->id ?? '' }}">
                            <div class="restaurant-info__form">
                                <label>店名：</label>
                                <input class="input-name" type="text" id="name" name="name"
                                    value="{{ $selectedRestaurant->name ?? '' }}">
                            </div>
                            <div class="restaurant-info__form--image">
                                <label>画像：</label>
                                <input class="input-image" type="file" id="image" name="image">
                            </div>
                            <div class="restaurant-info__form">
                                <label>エリア：</label>
                                <select class="select-area" id="area" name="area">
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ $currentArea && $currentArea->id == $area->id ? 'selected' : '' }}>
                                            {{ $area->prefectures_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="restaurant-info__form">
                                <label>ジャンル：</label>
                                <select class="select-genre" id="genre" name="genre">
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->id }}"
                                            {{ $currentGenre && $currentGenre->id == $genre->id ? 'selected' : '' }}>
                                            {{ $genre->genre_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="restaurant-info__form--textarea">
                                <label>説明：</label>
                                <textarea id="overview" name="overview" cols="30" rows="10">{{ $selectedRestaurant->overview ?? '' }}</textarea>
                            </div>
                            <div class="restaurant-info__form--button">
                                <button type="submit" id="update-button"
                                    class="restaurant-info__form--button-submit">更新</button>
                            </div>
                        </form>
                    @else
                        {{-- 管理店舗が存在しない場合に表示する登録フォーム --}}
                        <form id="new_restaurant_form" action="{{ route('restaurant.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="restaurant-info__form">
                                <label>店名：</label>
                                <input class="input-name" type="text" id="name" name="name" value="">
                            </div>
                            <div class="restaurant-info__form--image">
                                <label>画像：</label>
                                <input class="input-image" type="file" id="image" name="image">
                            </div>
                            <div class="restaurant-info__form">
                                <label>エリア：</label>
                                <select class="select-area" id="area" name="area">
                                    <option value="">選択してください</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">
                                            {{ $area->prefectures_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="restaurant-info__form">
                                <label>ジャンル：</label>
                                <select class="select-genre" id="genre" name="genre">
                                    <option value="">選択してください</option>
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->id }}">
                                            {{ $genre->genre_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="restaurant-info__form--textarea">
                                <label>説明：</label>
                                <textarea id="overview" name="overview" cols="30" rows="10"></textarea>
                            </div>
                            <div class="restaurant-info__form--button">
                                <button type="submit" id="register-button"
                                    class="restaurant-info__form--button-submit">登録</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        // 新規作成ボタンがクリックされたときの処理
        function showNewForm() {
            document.getElementById('new-form').style.display = 'block';
            document.getElementById('update-form').style.display = 'none';
        }
    </script>
@endsection
