<?php

if( ! $core ) exit;

$menu = [
	'dashboard' => 'Dashboard',
	'settings' => 'Settings',
	'updates' => 'Updates',
	'action/logout' => 'Logout',
];

$request = $core->route->get('request');

$active_slug = false;
if( ! empty($request[0]) ) $active_slug = $request[0];

?>
<header>
	<ul class="menu">
		<?php
		foreach( $menu as $slug => $name ) {
			?>
			<li<?php
			if( $slug == $active_slug ) echo ' class="active"';
			?>>
				<a href="<?= url($slug) ?>"><?= $name ?></a>
			</li>
			<?php
		}
		?>
	</ul>
</header>
