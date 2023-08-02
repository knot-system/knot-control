<?php

// Version: 0.1.0

if( ! $core ) exit;

$modules = $core->modules->get();

?>
<form action="<?= url('updates/check') ?>" method="POST">

	<?php
	foreach( $modules as $module ) {
		?>
		<label><input type="checkbox" name="modules[]" value="<?= $module->get('id') ?>" checked> <strong><?= $module->get('name') ?></strong> (installed: v.<?= $module->get('version') ?>)</label>
		<?php
	}
	?>

	<p><button>check for new versions</button></p>

</form>
