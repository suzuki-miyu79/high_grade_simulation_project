<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/message.css') }}">
</head>

<body>
    <header>
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
    </header>

    <main>
        <div class="message__content">
            <div class="message-card">
                <div class="message-card-msg">
                    <p>@yield('msg')</p>
                </div>
                <div class="message-card-btn">
                    <a href="@yield('href')">@yield('btn')</a>
                </div>
            </div>
        </div>

    </main>

    <div id="menuModal" style="display: none;">
        @auth
            {{-- 認証済みの場合 --}}
            @include('menu-authenticated')
        @else
            {{-- 未認証の場合 --}}
            @include('menu-guest')
        @endauth
    </div>

    <script>
        function openMenu() {
            var menuModal = document.getElementById('menuModal');
            menuModal.style.display = 'block';
        }
    </script>
</body>

</html>
