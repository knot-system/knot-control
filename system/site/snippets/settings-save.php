<?php

if( ! $core ) exit;

$data = $_POST;

$success = true;

if( is_array($data) ) {
	foreach( $data as $module_id => $config ) {

		$module = $core->modules->get($module_id);

		if( ! $module ) continue;

		if( ! $module->update_config($config) ) {
			$success = false;
		}

	}
	
}

if( $success ) {
	php_redirect( 'settings/?success' );
} else {
	php_redirect( 'settings/?error' );
}
