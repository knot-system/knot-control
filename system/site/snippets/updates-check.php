<?php

if( ! $core ) exit;

$selected_modules = [];
if( ! empty($_POST['modules']) ) $selected_modules = $_POST['modules'];

if( ! count($selected_modules) ) {
	?>
	<p><strong>Fehler:</strong> kein Modul ausgewählt</p>
	<p><a class="button" href="<?= url('updates') ?>">Module wählen</a>
	<?php
} else {

	?>
	<form action="<?= url('updates/install/') ?>" method="POST">
		<?php
		foreach( $selected_modules as $module_id ) {

			$module = $core->modules->get($module_id);

			if( ! $module ) continue;

			$new_releases = $module->search_update();

			$action = 're-install';
			$selected = 'skip';
			$new_version = false;
			if( count($new_releases) ) {
				$new_version = ' (v.'.$new_releases[0]->name.')';
				$action = 'update to';
				$selected = 'update';
			}


			$id = $module->get('id');

			?>
			<input type="hidden" name="modules[]" value="<?= $id ?>">
			<p><strong><?= $module->get('name') ?></strong> (v.<?= $module->get('version') ?>)</p>
			<select name="<?= $id ?>">
				<option value="stable"<?php if( $selected == 'update' ) echo ' selected'; ?>><?= $action ?> stable version<?= $new_version ?></option>
				<option value="dev">update to unstable dev version</option>
				<option value="skip"<?php if( $selected == 'skip' ) echo ' selected'; ?>>skip update</option>
			</select>
			<?php
			if( count($new_releases) ) {
				foreach( $new_releases as $new_release ) {

					$changelog = $new_release->body;
					if( ! $changelog ) continue;

					$changelog = htmlentities($changelog);
					$changelog = nl2br($changelog);
					?>
					<p><strong>v.<?= $new_release->name ?></strong></p>
					<?= $changelog ?>
					<?php
				}
			}
			?>
			<hr>
			<?php
		}
		?>
		<p><button>start update</button></p>
	</form>
	<?php
}
