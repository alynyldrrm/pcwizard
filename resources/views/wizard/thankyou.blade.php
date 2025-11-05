<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teşekkürler</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Teşekkürler!</h1>

    <p>Siparişiniz başarıyla tamamlandı.</p>
    <p>Sipariş Numarası: <strong>{{ $orderId }}</strong></p>

    <a href="{{ route('wizard.index') }}">Ana Sayfaya Dön</a>
</body>
</html>
