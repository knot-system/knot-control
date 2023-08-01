<?php

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
	<p><strong>Fehler:</strong> Seite nicht gefunden</p>
	<p><a class="button" href="<?= url('updates') ?>">zu den Updates</a>
	<?php
}
