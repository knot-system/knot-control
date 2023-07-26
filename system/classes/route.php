<?php


class Route {

	public $route;

	function __construct() {

		global $core;

		$request = $_SERVER['REQUEST_URI'];
		$request = preg_replace( '/^'.preg_quote($core->basefolder, '/').'/', '', $request );

		$request = explode( '?', $request );
		$request = $request[0];

		$request = explode( '/', $request );


		$template = '404';

		if( ! empty($request[0]) && file_exists( $core->abspath.'system/site/'.$request[0].'.php' ) ){
			$template = $request[0];
		}


		$this->route = array(
			'template' => $template,
			'request' => $request,
		);
		
		return $this;
	}

	function get( $name = false ) {

		if( $name ) {

			if( ! is_array($this->route) ) return false;

			if( ! array_key_exists($name, $this->route) ) return false;

			return $this->route[$name];
		}

		return $this->route;
	}
	
}
