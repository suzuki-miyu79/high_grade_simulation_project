<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>明日のご予約のお知らせ</title>
</head>

<body>
    <p>{{ $name }}さん</p><br>
    <p>いつも「Rese」をご利用いただきまして誠にありがとうございます。</p>
    <p>ご予約いただいた店舗へのご来店日が明日となりましたので、再度ご連絡させていただきます。</p><br>
    <p>店舗名: {{ $restaurantName }}</p>
    <p>来店日: {{ $reservation->date }}</p>
    <p>来店時間: {{ $appointmentTime }}</p><br>
    <p>※ご予約をキャンセルされる際は、マイページからお願いします。</p>
</body>

</html>
