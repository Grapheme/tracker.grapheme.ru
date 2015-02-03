<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <p>Добро пожаловать на {{ HTML::link(URL::route('home'),'Tracker Grapheme') }}.<p>
        Для доступа на сайт воспользуйтесь логином и паролем:
        Логин: {{ @$login }}<br>
        Пароль: {{ @$password }}
    </p>
</div>
</body>
</html>