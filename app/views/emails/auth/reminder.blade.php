<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Новый пароль</h2>
		<div>
            Чтобы изменить пароль, заполните форму перейдя по <a href="{{ URL::to('password/reset',$token) }}">ссылке</a><br/>
            Эта ссылка истекает через {{ Config::get('auth.reminder.expire', 60) }} минут.
		</div>
	</body>
</html>