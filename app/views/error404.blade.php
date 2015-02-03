<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Page Not Found :(</title>
	<style>
		body { margin: 0; padding: 0; height: 100%; }
		h1 { height: 326px; margin:0; padding: 0; font-size: 16.6875em; line-height: 314px; color: #31ab87; font-family: sans-serif; text-align: center; }
		p { color: #4d4d4d; text-align: center; }
		.back-to-main { text-decoration: none; display:inline-block; margin: 0 auto; padding: .25em 1em .35em; border: 1px solid #0f9e74; border-radius: 2px; background-color: #5bbfa1; color: #fff; text-shadow: 1px 1px 0px #53ac92; filter: dropshadow(color=#53ac92, offx=1, offy=1); }
		.link-404 { color: #4d4d4d;
			transition: all .4s ease;
			-webkit-transition: all .4s ease;
			-moz-transition: all .4s ease;
			-o-transition: all .4s ease;
			-ms-transition: all .4s ease;
		}
		.link-404:hover { color: #497999; }
		.container { margin: 0 0 3em; }
		.container > p { margin: 2.5em 0 0; }
	</style>
</head>
<body>
<div class="wrapper">
	<div class="main clearfix">
		<div class="container">
			<h1>404</h1>
			<p>Страница не найдена или не существует.<br>Попробуйте начать с <a class="link-404" href="{{ URL::route('home') }}">главной страницы</a>.</p>
		</div>
	</div>
</div>
</body>
</html>
