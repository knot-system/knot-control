<?php

if( ! $core ) exit;

$data = $_POST;

if( is_array($data) ) {
	foreach( $data as $module_id => $config ) {

		$module = $core->modules->get($module_id);

		if( ! $module ) continue;

		$module->update_config($config);

	}
}

php_redirect( 'settings/?updated' );
