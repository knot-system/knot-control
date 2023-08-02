<?php

if( ! $core ) exit;


$request = $core->route->get('request');

if( ! empty($request[1]) && $request[1] == 'save' ) {
	snippet( 'settings-save' );
} else {
	snippet( 'settings-form' );
}
