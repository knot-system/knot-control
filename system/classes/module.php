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
	
}
