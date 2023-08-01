<?php


class Route {

	public $route;
	public $request;

	function __construct() {

		global $core;

		$request = $_SERVER['REQUEST_URI'];
		$request = preg_replace( '/^'.preg_quote($core->basefolder, '/').'/', '', $request );

		$request = explode( '?', $request );
		$request = $request[0];

		$request = explode( '/', $request );

		$this->request = $request;


		if( ! empty($request[0]) && $request[0] == 'action' ) {

			if( ! empty($request[1]) ) {
				$action = $request[1];

				$redirect_path = '';

				if( $action == 'logout' ) {

					$core->user->logout();

				} elseif( $action == 'login' ) {

					if( ! empty($_POST['path']) ) {
						$_SESSION['login_redirect_path'] = trailing_slash_it($_POST['path']);
					}

					$core->user->authorize( $_POST );
					exit;

				} elseif( $action == 'redirect' ) {

					$redirect_path = 'dashboard/';
					if( isset($_SESSION['login_redirect_path']) ) {
						$redirect_path = $_SESSION['login_redirect_path'];
					}

					$core->user->login();

				}

				if( isset($_SESSION['login_redirect_path']) ) {
					unset($_SESSION['login_redirect_path']);
				}
				
				$this->redirect( $redirect_path );

			}

			$this->route = array(
				'template' => '404',
			);

			return $this; // always end here if an action is set
		}


		if( $core->user->authorized() ) {

			if( empty($request[0]) ) {
				
				$homepage = trailing_slash_it('dashboard');

				$this->redirect($homepage);

			} else {

				$template = $request[0];

				if( ! file_exists($core->abspath.'system/site/'.$template.'.php') ) {
					$template = '404';
				}

				$this->route = array(
					'template' => $template,
					'request' => $request,
				);

			}

			
		} else {

			$core->user->logout();

			$this->route = array(
				'template' => 'login'
			);
	
		}

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
	
	function redirect( $path ) {
		php_redirect( $path );
	}

}
