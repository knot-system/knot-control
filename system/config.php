<?php

// NOTE: you can overwrite these options:
// - in your custom theme, via /theme/{themename}/config.php
// - and/or via the config.php in the root folder

return [
	'debug' => false, // show additional information when an error occurs
	'theme' => 'default',
	'modules' => [ // all available modules and their relative paths
		'eigenheim' => '../eigenheim/',
		'sekretaer' => '../sekretaer/',
		'postamt' => '../postamt/',
		'einwohnermeldeamt' => '../einwohnermeldeamt/',
		'homestead-control' => './', // this should point to the current directory
	],
];
