<?php

// Version: 0.1.0

if( ! $core ) exit;

$route = $core->route->get('request');

if( empty($route[1]) ) {
	snippet('updates-overview');
} elseif( $route[1] == 'check' ) {
	snippet('updates-check');
} elseif( $route[1] == 'install' ) {
	snippet('updates-install');
} else {
	?>
	<p><strong>Error:</strong> page not found</p>
	<p><a class="button" href="<?= url('updates') ?>">go to updates</a>
	<?php
}
