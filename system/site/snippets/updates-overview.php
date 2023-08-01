<?php

if( ! $core ) exit;

$modules = $core->modules->get();

?>
<form action="<?= url('updates/check') ?>" method="POST">

	<ul>
		<?php
		foreach( $modules as $module ) {
			?>
			<li>
				<label><input type="checkbox" name="modules[]" value="<?= $module->get('id') ?>" checked> <strong><?= $module->get('name') ?></strong> (aktuell: v.<?= $module->get('version') ?>)</label>
			</li>
			<?php
		}
		?>
	</ul>

	<p><button>check for new versions</button></p>

</form>
