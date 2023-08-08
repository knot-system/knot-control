<?php


class Modules {
	
	private $modules = [];
	private $module_names = [
		'knot-site' => 'Knot Site',
		'knot-home' => 'Knot Home',
		'knot-daemon' => 'Knot Daemon',
		'knot-auth' => 'Knot Auth',
		'knot-control' => 'Knot Control',
	];
	private $module_update_urls = [
		'knot-site' => [
			'release_api' => 'https://api.github.com/repos/knot-system/knot-site/releases',
			'dev_zipball' => 'https://github.com/knot-system/knot-site/archive/refs/heads/main.zip',
		],
		'knot-home' => [
			'release_api' => 'https://api.github.com/repos/knot-system/knot-home/releases',
			'dev_zipball' => 'https://github.com/knot-system/knot-home/archive/refs/heads/main.zip',
		],
		'knot-daemon' => [
			'release_api' => 'https://api.github.com/repos/knot-system/knot-daemon/releases',
			'dev_zipball' => 'https://github.com/knot-system/knot-daemon/archive/refs/heads/main.zip',
		],
		'knot-auth' => [
			'release_api' => 'https://api.github.com/repos/knot-system/knot-auth/releases',
			'dev_zipball' => 'https://github.com/knot-system/knot-auth/archive/refs/heads/main.zip',
		],
		'knot-control' => [
			'release_api' => 'https://api.github.com/repos/knot-system/knot-control/releases',
			'dev_zipball' => 'https://github.com/knot-system/knot-control/archive/refs/heads/main.zip',
		],
	];

	function __construct() {
		global $core;

		$this->module_list = $core->config->get('modules');

		$abspath = $core->abspath;

		foreach( $this->module_list as $module_id => $module_path ) {

			$module_path = $abspath.$module_path;

			$module_path = realpath($module_path);

			if( ! is_dir($module_path) ) continue;

			if( ! array_key_exists($module_id, $this->module_names) ) continue;
			if( ! array_key_exists($module_id, $this->module_update_urls) ) continue;

			$module_path = trailing_slash_it($module_path);

			$module_name = $this->module_names[$module_id];

			$module_url = un_trailing_slash_it($core->baseurl);
			$module_url = explode('/', $module_url);
			array_pop($module_url);
			$module_url[] = str_replace('../', '', $core->config->get('modules')[$module_id]);
			$module_url = implode('/', $module_url);

			$module_urls = $this->module_update_urls[$module_id];

			$this->modules[$module_id] = new Module( $module_id, $module_name, $module_path, $module_url, $module_urls );

		}

	}

	function get( $module = false ) {

		if( $module ) {
			if( array_key_exists($module, $this->modules) ) {
				return $this->modules[$module];
			} else {
				return false;
			}
		}

		return $this->modules;
	}

}
