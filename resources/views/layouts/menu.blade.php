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
            <button class="close-menu" onclick="closeMenu()">&times;</button>
            <div class="modal-content-nav">
                <ul>
                    @yield('menu')
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
