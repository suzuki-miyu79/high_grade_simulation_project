document.addEventListener('DOMContentLoaded', async function () {

    function isLoggedIn() {
        const reservationDateInput = document.getElementById('reservation_date');
        return reservationDateInput.getAttribute('data-logged-in') === 'true';
    }

    // 予約フォームのsubmitイベントを拾ってAjax通信
    document.querySelector('.reservation__form').addEventListener('submit', async function (event) {
        event.preventDefault();

        // ユーザーが日付を選択する前にログインしているか確認
        if (!(await isLoggedIn())) {
            // ログインしていない場合はログインフォームにリダイレクト
            window.location.href = '/login';
            return; // リダイレクトしたら以降の処理を中断
        }

        // 現在のURLを取得
        const currentUrl = window.location.href;

        // 予約情報を取得
        const formData = new FormData(this);

        // URLからrestaurant_idを取得
        const matchResult = currentUrl.match(/\/detail\/(\d+)/);
        const restaurantId = matchResult ? matchResult[1] : null;

        // レストランIDを手動で追加
        formData.append('restaurant_id', restaurantId);

        // Ajax通信
        try {
            const response = await axios.post('/reservations', formData);
            // 予約情報をテーブルに表示
            document.getElementById('confirmation_restaurant_name').textContent = response.data.restaurant_name;
            document.getElementById('confirmation_date').textContent = response.data.reservation_date;
            document.getElementById('confirmation_time').textContent = response.data.reservation_time;
            document.getElementById('confirmation_number').textContent = response.data.reservation_number;
        } catch (error) {
            console.error(error);
        }
    });
});