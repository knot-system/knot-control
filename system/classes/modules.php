<?php


class Modules {
	
	private $modules = [];

	function __construct() {
		global $core;

		$this->module_list = $core->config->get('modules');

		$abspath = $core->abspath;

		foreach( $this->module_list as $module_name => $module_path ) {

			$module_path = $abspath.$module_path;

			$module_path = trailing_slash_it(realpath($module_path));

			if( ! is_dir($module_path) ) continue;

			$this->modules[$module_name] = $module_path;

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
