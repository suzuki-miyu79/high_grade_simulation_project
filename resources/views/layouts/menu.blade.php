<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>

<body>
    <div id="menuModal" class="modal">
        <div class="modal-content">
            <div class="close-menu" onclick="closeMenu()">
                <img src="{{asset('storage/x-icon.png')}}" alt="">
            </div>
            <div class="modal-content-nav">
                <ul>
                    @yield('menu1')
                    @yield('menu2')
                    @yield('menu3')
                </ul>
            </div>
        </div>
    </div>

    <script>
        function closeMenu() {
            document.getElementById('menuModal').style.display = 'none';
        }
    </script>
</body>

</html>
