<?php

if( ! $core ) exit;

head_html();

snippet('header');

?>
<main class="settings">

	<h1>Settings</h1>

	<?php
	if( isset($_GET['success']) ) {
		?>
		<p class="success message">all settings were updated</p>
		<?php
	} elseif( isset($_GET['error']) ) {
		?>
		<p class="error message"><strong>Error:</strong> something went wrong while updating the settings. Please make sure, that all <code>config.php</code> files in the root directories of the modules are writeable!</p>
		<?php
	}
	?>

	<form id="settings-form" action="<?= url('settings/save') ?>" method="post">

		<?php
		foreach( $this->modules->get() as $module_id => $module ) {

			$settings = $module->get_editable_config_options();

			if( empty($settings) ) continue;

			$name = $module->get('name');

			?>
			<fieldset>
				<legend><?= $name ?></legend>
				<?php
				foreach( $settings as $option => $type ) {

					$value = $module->get_config_current( $option );
					$default = $module->get_config_default( $option );
					$description = $module->get_config_description( $option );

					?>
					<label>
						<strong><?= $option ?></strong><br>
						<?php
						if( $type == 'bool' ) {

							if( $default ) $default = 'True';
							else $default = 'False';

							?>
							<select name="<?= $module_id ?>[<?= $option ?>]">
								<option value="true"<?php if( $value === true ) echo ' selected'; ?>>True</option>
								<option value="false"<?php if( $value === false ) echo ' selected'; ?>>False</option>
								<option value="default"<?php if( $value !== true && $value !== false ) echo ' selected'; ?>>Default (<?= $default ?>)</option>
							</select>
							<?php
						} elseif( $type == 'string' ) {
							?>
							<input type="text" value="<?= $value ?>" placeholder="<?= $default ?>">
							<?php
						} elseif( $type == 'url' ) {
							?>
							<input type="url" value="<?= $value ?>" placeholder="<?= $default ?>">
							<?php
						} elseif( $type == 'int' ) {
							if( $value ) $value = (int) $value;
							if( $default ) $default = (int) $default;
							?>
							<input type="number" value="<?= $value ?>" placeholder="<?= $default ?>" step="1" min="0">
							<?php
						} elseif( $type == 'theme' ) {
							$themes = $module->get_themes();
							if( ! $value ) $value = $default;
							?>
							<select name="<?= $module_id ?>[<?= $option ?>]">
								<?php
								foreach( $themes as $theme_id => $theme_name ) {
									?>
									<option value="<?= $theme_id ?>"<?php if( $value == $theme_id ) echo ' selected'; ?>><?= $theme_name ?></option>
									<?php
								}
								?>
							</select>
							<?php
						} elseif( $type == 'array' ) {
							$array_options = $module->get_array_options( $option );
							if( ! $value ) $value = $default;
							?>
							<select name="<?= $module_id ?>[<?= $option ?>]">
								<?php
								foreach( $array_options as $array_option_value => $array_option_name ) {
									?>
									<option value="<?= $array_option_value ?>"<?php if( $value == $array_option_value ) echo ' selected'; ?>><?= $array_option_name ?></option>
									<?php
								}
								?>
							</select>
							<?php
						} elseif( $type == 'complex' ) {
							if( ! $value ) $value = '';
							if( is_array($value) ) {
								$value = $module->stringify_config_option( false, $value, 0 );
								$value = trim($value);
								$value = rtrim($value, ','); // remove last ','
							}
							?>
							<textarea name="<?= $module_id ?>[<?= $option ?>]"><?= $value ?></textarea>
							<?php
						}

						if( $description ) echo '<br><small>'.$description.'</small>';
						?>
					</label>
					<?php
				}
				?>
			</fieldset>
			<?php
		}
		?>

		<div class="save-button-wrapper"><button>update settings</button></div>

	</form>

	<p><small>(You can also change these and more settings by directly editing the <code>config.php</code> files in the module directories)</small></p>

</main>
<?php

snippet('footer');

foot_html();
