<?php

return array(

	'feedback' => array(
		'address' => 'support@grapheme.ru',
	),

	'driver' => 'smtp',
	'host' => 'smtp.mailgun.org',
	'port' => 587,
	'from' => array('address' => 'no-reply@tracker.grapheme.ru', 'name' => 'Tracker Grapheme'),
	'encryption' => 'tls',
	'username' => null,
	'password' => null,
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => TRUE,
);
