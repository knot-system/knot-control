<?php

if( ! $core ) exit;

head_html();

snippet('header');

$modules = $core->modules->get();

?>
<main class="error-404">

	<h1>Dashboard</h1>

<ul>
	<li>Login: <strong><?= $core->user->get('me') ?></strong></li>
	<li>Module:
		<ul>
		<?php
		foreach( $modules as $module ) {
			echo '<li><strong>'.$module->get('name').'</strong> v.'.$module->get('version').' ('.$module->get('path').')</li>';
		}
		?>
		</ul>
	</li>
</ul>


</main>
<?php

snippet('footer');

foot_html();
