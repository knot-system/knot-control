<?php

if( ! $core ) exit;

$menu = [
	'dashboard' => 'Dashboard',
	'settings' => 'Settings',
	'updates' => 'Updates',
	'action/logout' => 'Logout',
];

$active_slug = un_trailing_slash_it(implode('/', $core->route->get('request')));

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
