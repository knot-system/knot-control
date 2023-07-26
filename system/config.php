<?php

// NOTE: you can overwrite these options:
// - in your custom theme, via /theme/{themename}/config.php
// - and/or via the config.php in the root folder

return [
	'debug' => false, // show additional information when an error occurs
	'theme' => 'default',
	'theme-color-scheme' => 'default', // depends on the theme; the default theme supports 'blue' (default), 'green', 'red', 'lilac'
	'modules' => [ // all available modules and their relative paths
		'eigenheim' => '../eigenheim/',
		'sekretaer' => '../sekretaer/',
		'postamt' => '../postamt/',
		'einwohnermeldeamt' => '../einwohnermeldeamt/',
		'homestead-control' => './', // this should point to the current directory
	],
	'cookie_lifetime' => 60*60*24*10, // 10 days, in seconds
	'cache_lifetime' => 60*60*24*30, // 30 days, in seconds
	'session_lifetime' => 60*60*24*10, // 10 days, in seconds
	'scope' => 'manage',
	'user_agent' => 'maxhaesslein/homestead-control/', // version will be automatically appended
];
