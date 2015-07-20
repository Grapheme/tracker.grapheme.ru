<?php

$app = new Illuminate\Foundation\Application;
$env = $app->detectEnvironment(array(
	'vkharseev' => array('DNS'),
    'digitalocean' => array('www.grapheme.ru')
));
$app->bindInstallPaths(require __DIR__.'/paths.php');
$framework = $app['path.base'].'/vendor/laravel/framework/src';
require $framework.'/Illuminate/Foundation/start.php';
date_default_timezone_set(Config::get('app.timezone'));
return $app;
