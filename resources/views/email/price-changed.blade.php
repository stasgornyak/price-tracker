<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Price Changed') }}</title>
</head>
<body>
    <h1>Dear {{ $user_name }}!</h1>
    <p>The price of the <strong> {{ $item_name }} </strong> you were interested in has changed from <strong> {{ number_format($old_price, 2, '.', ' ').' UAH' }} </strong> to <strong> {{ number_format($new_price, 2, '.', ' ').' UAH' }}</strong> .</p>
    <p>Link: <a href="{{ $url }}">{{ $url }}</a></p>
</body>
</html>
