<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    @yield('css')
</head>

<body>
    <header>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <!-- 代表的なエラーメッセージの表示 -->
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

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
        @yield('content')
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
