@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/restaurant-list.css') }}">
@endsection

@section('content')
    <header class="header">
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
        <form action="{{ route('restaurant.index') }}" method="GET">
            @csrf
            <div class="search">
                <select onchange="submit(this.form)" name="area" id="area" class="search__form-area">
                    <option value="">All area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->prefectures_name }}"
                            {{ request('area') == $area->prefectures_name ? 'selected' : '' }}>{{ $area->prefectures_name }}
                        </option>
                    @endforeach
                </select>
                <div class="line"></div>
                <select onchange="submit(this.form)" name="genre" id="genre" class="search__form-genre">
                    <option value="">All genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->genre_name }}"
                            {{ request('genre') == $genre->genre_name ? 'selected' : '' }}>{{ $genre->genre_name }}</option>
                    @endforeach
                </select>
                <div class="line"></div>
                <img src="image/search-icon.png" alt="">
                <input type="text" name="keyword" id="keyword" class="search__form-name"
                    value="{{ request('keyword') }}" placeholder="Search …">
            </div>
        </form>
    </header>

    <main>
        <div class="restaurant-list">
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
    </main>
    <script src="{{ asset('js/favorite.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/5dc5d1378e.js" crossorigin="anonymous"></script>
@endsection
