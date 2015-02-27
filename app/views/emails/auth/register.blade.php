<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <p>Добро пожаловать на {{ HTML::link(URL::route('home'),'Tracker Grapheme') }}.<p>
    </p>Для доступа на сайт воспользуйтесь логином и паролем:</p>
    <p>
        Логин: {{ @$login }}<br>
        Пароль: {{ @$password }}
    </p>
</div>
</body>
</html>