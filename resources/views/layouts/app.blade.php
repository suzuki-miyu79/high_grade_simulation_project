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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

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
