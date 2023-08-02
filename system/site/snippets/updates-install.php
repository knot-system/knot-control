<?php

// Version: 0.1.0

if( ! $core ) exit;

$modules = [];
if( ! empty($_POST['modules']) ) $modules = $_POST['modules'];

if( ! count($modules) ) {
	?>
	<p><strong>Error:</strong> no module selected</p>
	<p><a class="button" href="<?= url('updates') ?>">select module</a>
	<?php
} else {

	?>
	<p>starting update …</p>
	<?php
	foreach( $modules as $module_id ) {

		$module = $core->modules->get($module_id);

		if( ! $module ) continue;

		if( empty($_POST[$module_id]) ) continue;

		$update_to = $_POST[$module_id];

		if( ! $update_to ) continue;

		if( $update_to == 'skip' ) continue;

		$success = $module->update($update_to);

		if( ! $success ) {
			echo '<p><strong>error</strong> while updating '.$module->get('name').'</p>';
		}
	}
	?>
	<p>all done.</p>
	<p><a class="button" href="<?= url('updates') ?>">refresh this page</a></p>
	<?php

}
