<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <p>Добро пожаловать на {{ HTML::link(URL::route('home'),'Tracker Grapheme') }}.<p>
    </p>{{ Auth::user()->fio }} приглашает Вас присоединится.</p>
    <p>Перейдите по <a href="{{ URL::route('invite',['token'=>@$token]) }}">ссылке</a> для совершения этого действия.</p>
</div>
</body>
</html>