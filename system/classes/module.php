<?php


class Module {

	private $id;
	private $name;
	private $abspath;
	private $baseurl;
	private $urls;
	private $version;
	private $config = NULL;
	private $themes = NULL;
	private $config_definitions = NULL;

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

	function load_config() {

		if( $this->config !== NULL ) return $this->config;

		$local_config_file = $this->abspath.'config.php';
		$core_config_file = $this->abspath.'system/config.php';

		$local_config = [];
		if( file_exists($local_config_file) ) {
			$local_config = include($local_config_file);
		}

		$core_config = [];
		if( file_exists($core_config_file) ) {
			$core_config = include($core_config_file);
		}

		$config = [
			'local' => $local_config,
			'core' => $core_config,
		];

		$this->config = $config;

		return $config;
	}

	function get_config_definitions() {
		
		if( $this->config_definitions !== NULL ) return $this->config_definitions;

		$config_definitions_file = $this->abspath.'system/config_definitions.php';

		$config_definitions = [];
		if( file_exists($config_definitions_file) ) {
			$config_definitions = include($config_definitions_file);
		}

		$this->config_definitions = $config_definitions;

		return $config_definitions;
	}
	
	function get_editable_config_options() {
		
		$config_definitions = $this->get_config_definitions();

		if( empty($config_definitions) ) return [];

		$config_options = [];
		foreach( $config_definitions as $key => $config_definition ) {
			if( empty($config_definition['type']) ) continue;
			$config_options[$key] = $config_definition['type'];
		}

		return $config_options;
	}

	function get_config_type( $option ) {

		$config_definitions = $this->get_config_definitions();

		if( empty($config_definitions) ) return false;
		if( empty($config_definitions[$option]) ) return false;
		if( empty($config_definitions[$option]['type'])) return false;

		return $config_definitions[$option]['type'];

		return $config_options;	
	}

	function get_config_current( $option ) {

		$config = $this->load_config();

		if( array_key_exists($option, $config['local']) ) {
			return $config['local'][$option];
		}

		return NULL;
	}

	function get_config_default( $option ) {

		$config = $this->load_config();

		if( array_key_exists($option, $config['core']) ) {
			return $config['core'][$option];
		}

		return NULL;
	}

	function get_config_description( $option ) {
		
		$config_definitions = $this->get_config_definitions();

		if( empty($config_definitions) ) return '';
		if( empty($config_definitions[$option]) ) return '';
		if( empty($config_definitions[$option]['description'])) return '';

		return $config_definitions[$option]['description'];
	}

	function get_themes() {

		if( $this->themes !== NULL ) return $this->themes;

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
		$theme_list = array_merge( ['default' => $default], $theme_list);

		$this->themes = $theme_list;

		return $theme_list;
	}

	function get_array_options( $option ) {

		$config_definitions = $this->get_config_definitions();

		if( empty($config_definitions) ) return [];
		if( empty($config_definitions[$option]) ) return [];
		if( empty($config_definitions[$option]['description'])) return [];

		return $config_definitions[$option]['options'];
	}

	function update_config( $new_config_options ) {

		$config = $this->load_config();
		$editable_config_options = $this->get_editable_config_options();

		foreach( $new_config_options as $option => $new_value ) {

			if( ! array_key_exists($option, $editable_config_options) ) {
				// skip options that are not set as 'editable'
				continue;
			}

			if( $new_value == 'default' ) {
				// remove existing options, that are now set to 'default'
				unset($config['local'][$option]);
				continue;
			}

			// set new option value
			$config['local'][$option] = $new_value;
		}

		return $this->save_config_file( $config['local'] );
	}

	function save_config_file( $config ) {

		$new_config_content = "<?php\r\n\r\nreturn [\r\n";
		foreach( $config as $option => $value ) {
			$new_config_content .= $this->stringify_config_option( $option, $value );
		}
		$new_config_content .= "];\r\n";
		
		return file_put_contents( $this->abspath.'config.php', $new_config_content );
	}

	function stringify_config_option( $key, $value, $depth = 1 ) {

		$string = '';

		if( $key ) {
			// associative array
			$string .= str_repeat( '	', $depth );
			$string .= "'".$key."' => ";
		}

		if( is_int($value) ) {

			$new_value = $value;

		} elseif( is_bool($value) ) {

			if( $value ) {
				$new_value = 'true';
			} else {
				$new_value = 'false';
			}

		} elseif( is_array($value) ) {

			if( array_is_list($value) ) {
				$new_value = '[';
				// array is sequential
				foreach( $value as $subvalue ) {
					$new_value .= $this->stringify_config_option( false, $subvalue, $depth+1 );
				}
				$new_value = rtrim($new_value, ','); // remove last ',';
				$new_value .= ']';
			} else {
				$new_value = "[\r\n";
				// array is associative
				foreach( $value as $subkey => $subvalue ) {
					$new_value .= $this->stringify_config_option( $subkey, $subvalue, $depth+1 );
				}
				$new_value .= str_repeat( '	', $depth );
				$new_value .= ']';
			}

		} else {
			// is string, or unknown

			$type = $this->get_config_type( $key );
			if( $type == 'complex' ) {
				if( ! $value ) return '';

				$new_value = $value; // should already be a stringified array or similar, we want to save it 'as-is'
			} else {
				$new_value = "'".$value."'";
			}

		}

		$string .= $new_value;

		if( $key ) {
			// associative array
			$string .= ",\r\n";
		} else {
			// sequential array
			$string .= ', ';
		}

		return $string;
	}

}
