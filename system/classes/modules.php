<?php


class Modules {
	
	private $modules = [];
	private $module_names = [
		'eigenheim' => 'Eigenheim',
		'sekretaer' => 'Sekretär',
		'postamt' => 'Postamt',
		'einwohnermeldeamt' => 'Einwohnermeldeamt',
		'homestead-control' => 'Homestead Control',
	];
	private $module_update_urls = [
		'eigenheim' => [
			'release_api' => 'https://api.github.com/repos/maxhaesslein/eigenheim/releases',
			'dev_zipball' => 'https://github.com/maxhaesslein/eigenheim/archive/refs/heads/main.zip',
		],
		'sekretaer' => [
			'release_api' => 'https://api.github.com/repos/maxhaesslein/sekretaer/releases',
			'dev_zipball' => 'https://github.com/maxhaesslein/sekretaer/archive/refs/heads/main.zip',
		],
		'postamt' => [
			'release_api' => 'https://api.github.com/repos/maxhaesslein/postamt/releases',
			'dev_zipball' => 'https://github.com/maxhaesslein/postamt/archive/refs/heads/main.zip',
		],
		'einwohnermeldeamt' => [
			'release_api' => 'https://api.github.com/repos/maxhaesslein/einwohnermeldeamt/releases',
			'dev_zipball' => 'https://github.com/maxhaesslein/einwohnermeldeamt/archive/refs/heads/main.zip',
		],
		'homestead-control' => [
			'release_api' => 'https://api.github.com/repos/maxhaesslein/homestead-control/releases',
			'dev_zipball' => 'https://github.com/maxhaesslein/homestead-control/archive/refs/heads/main.zip',
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
