document.addEventListener('DOMContentLoaded', function () {
    var favoriteButtons = document.querySelectorAll('.content-bottom__favorite-button-submit');

    favoriteButtons.forEach(function (button) {

        var restaurantId = button.getAttribute('data-restaurant-id');
        var isFavorited = button.getAttribute('data-favorited') === 'true';

        // ページがロードされたときにお気に入りの状態を設定
        setFavoriteButtonStyle(button, isFavorited);

        button.addEventListener('click', function (event) {
            // ログインしているか確認
            var isLoggedIn = button.getAttribute('data-logged-in') === 'true';

            if (isLoggedIn) {
                // axiosをCDNから直接読み込む
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js';
                script.onload = function () {
                    // axiosが読み込まれた後にtoggleFavoriteを呼び出す
                    toggleFavorite(restaurantId, button);
                };

                document.head.appendChild(script);
            } else {
                // ログインページに遷移
                window.location.href = '/login';
            }
        });
    });

    function toggleFavorite(restaurantId, button) {
        axios.post('/restaurants/' + restaurantId + '/favorite')
            .then(function (response) {
                setFavoriteButtonStyle(button, response.data.status === 'added');
            })
            // エラーレスポンスを取得し、ダイアログ表示
            .catch(function (error) {
                var errorMessage = error.response.data.message;
                alert(errorMessage);
            });
    }

    // お気に入り登録ボタンのスタイル
    function setFavoriteButtonStyle(button, isFavorited) {
        var icon = button.querySelector('.fa-heart');

        if (isFavorited) {
            icon.style.color = 'red';
        } else {
            icon.style.color = '#f2f2f2';
        }
    }
});