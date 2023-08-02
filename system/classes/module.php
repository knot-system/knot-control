<?php


class Module {

	private $id;
	private $name;
	private $abspath;
	private $baseurl;
	private $urls;
	private $version;

	function __construct( $id, $name, $abspath, $baseurl, $urls  ) {

		$this->id = $id;
		$this->name = $name;
		$this->abspath = $abspath;
		$this->baseurl = $baseurl;
		$this->urls = $urls;
		$this->version = $this->get_current_version();

	}

	function get_current_version() {

		$version_file = $this->abspath.'system/version.txt';
		
		if( ! file_exists($version_file) ) return false;

		$version = trim(file_get_contents($version_file));

		return $version;
	}

	function get( $field ) {
		if( $field == 'id' ) {
			return $this->id;
		} elseif( $field == 'name' ) {
			return $this->name;
		} elseif( $field == 'path' ) {
			return $this->abspath;
		} elseif( $field == 'urls' ) {
			return $this->urls;
		} elseif( $field == 'version' ) {
			return $this->version;
		}
	}

	function search_update() {
		if( ! $this->urls || empty($this->urls['release_api']) ) return [];

		$api_url = $this->urls['release_api'];

		$json = get_remote_json( $api_url );
	
		if( ! $json || ! is_array($json) ) {
			return [];
		}

		$new_releases = [];
		
		$version_number_old = explode('.', $this->version);

		foreach( $json as $release ) {

			$version_number_new = explode('.', $release->name);

			if( $version_number_new[0] > $version_number_old[0] 
			 || ($version_number_new[0] == $version_number_old[0] && $version_number_new[1] > $version_number_old[1] )
			 || ($version_number_new[0] == $version_number_old[0] && $version_number_new[1] == $version_number_old[1] && $version_number_new[2] > $version_number_old[2] )
			){
				$new_releases[] = $release;
			} else {
				break;
			}

		}

		return $new_releases;
	}

	function update( $update_to ) {

		$version = 'latest';
		if( $update_to == 'dev' ) $version = 'dev';

		echo '<p>Updating '.$this->name.' …</p>';
		flush();

		touch( $this->abspath.'update' );

		time_nanosleep(0,500000000); // sleep for 0.5 seconds

		$response = request_get_remote( $this->baseurl.'?update=true&step=install&version='.$version );

		?>
		<code class="response">
			<?php echo strip_tags( $response, ['br','p','ul','ol','li'] ); ?>
		</code>
		<?php
		flush();

		time_nanosleep(0,500000000); // sleep for 0.5 seconds

		request_get_remote( $this->baseurl ); // trigger re-creation of missing files

		flush();

		return true;
	}
	
	function get_config_current( $option ) {
		// TODO
		return NULL;
	}

	function get_config_default( $option ) {
		// TODO
		return 'default-value';
	}

	function get_config_description( $option ) {
		$description = '';

		if( $option == 'theme' ) {
			$description = 'you can add more themes in the <code>theme/</code> subfolder';
		} elseif( $option == 'theme-color-scheme' ) {
			$description = 'not all themes support (all) color schemes';
		}

		// TODO

		return $description;
	}

	function get_themes() {

		$theme_folder = new Folder( $this->abspath.'theme' );
		$themes = $theme_folder->get_subfolders();

		$theme_list = [];

		foreach( $themes as $theme_subfolder ) {
			$folder_name = $theme_subfolder['name'];
			$path = $theme_subfolder['path'];
			$theme_file = $path.'theme.php';
			if( ! file_exists($theme_file) ) continue;

			$theme = include($theme_file);

			$name = $folder_name;
			if( ! empty($theme['name']) ) $name = $theme['name'];

			$version = false;
			if( ! empty($theme['version']) ) $version = $theme['version'];

			if( $version ) $name .= ' (v.'.$version.')';

			$theme_list[$folder_name] = $name;
		}

		asort($theme_list);

		// move default theme to first position:
		$default = $theme_list['default'];
		unset($theme_list['default']);
		array_unshift( $theme_list, $default );

		return $theme_list;
	}

	function get_theme_colorschemes() {
		// TODO: make them dynamic and let the theme.php configure the available color schemes
		return [ 'default' => 'Default (blue)', 'green' => 'Green', 'red' => 'Red', 'lilac' => 'Lilac' ];
	}

}
