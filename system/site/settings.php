<?php

if( ! $core ) exit;

head_html();

snippet('header');

$groups = [
	'eigenheim' => [
		'site_title' => 'string',
		'theme' => 'theme',
		'theme-color-scheme' => 'theme-color-scheme',
		'microsub' => 'url',
		'indieauth-metadata' => 'url',
		'posts_per_page' => 'int',
		'link_detection' => 'bool',
		'link_preview' => 'bool',
	],
	'sekretaer' => [
		'theme' => 'theme',
		'theme-color-scheme' => 'theme-color-scheme',
		'microsub' => 'bool',
		'micropub' => 'bool',
		'datetime_format' => 'string',
		'link_preview_nojs_refresh' => 'bool',
		'link_preview_autorefresh' => 'bool',
		'show_item_content' => 'bool',
	],
	'postamt' => [
		'force_refresh_posts' => 'bool',
		'refresh_on_connect' => 'bool',
	],
	'einwohnermeldeamt' => [
		'theme' => 'theme',
		'theme-color-scheme' => 'theme-color-scheme',
	]
];

?>
<main class="settings">

	<h1>Settings</h1>

	<form id="settings-form" action="<?= url('settings') ?>" method="post">

		<?php
		foreach( $groups as $module_id => $settings ) {

			$module = $core->modules->get($module_id);

			if( ! $module ) continue;

			$name = $module->get('name');

			?>
			<fieldset>
				<legend><?= $name ?></legend>
				<?php
				foreach( $settings as $option => $type ) {

					$value = $module->get_config_current( $option );
					$default = $module->get_config_default( $option );
					$description = $module->get_config_description( $option ) ;

					?>
					<label>
						<strong><?= $option ?></strong><br>
						<?php
						if( $type == 'bool' ) {
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
						} elseif( $type == 'theme-color-scheme' ) {
							$color_schemes = $module->get_theme_colorschemes();
							if( ! $value ) $value = $default;
							?>
							<select name="<?= $module_id ?>[<?= $option ?>]">
								<?php
								foreach( $color_schemes as $color_scheme_id => $color_scheme_name ) {
									?>
									<option value="<?= $color_scheme_id ?>"<?php if( $value == $color_scheme_id ) echo ' selected'; ?>><?= $color_scheme_name ?></option>
									<?php
								}
								?>
							</select>
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

		<div class="save-button-wrapper"><button disabled>update settings</button></div>

	</form>

	<p><small>(You can also change these and more settings by directly editing the <code>config.php</code> files in the module directories)</small></p>

</main>
<?php

snippet('footer');

foot_html();
