<?php
class Router {

	public function __construct() {
		if ( isset( $_GET['method'] ) ) {
			$methodA = explode('_',$_GET['method']);
		} else {
			if ( isset( $_POST['method'] ) ) {
				$methodA = explode('_',$_POST['method']);
			} else {
				$methodA = explode('_',$_POST['parent_tab']);
			}
		}
		$method = lcfirst( array_shift($methodA) );
		foreach ( $methodA as $m ) $method .= ucfirst( strtolower($m) );
		LG( $_POST, ' post in router' );
		LG( $_GET, 'get in router' );

	  //$start = microtime( true );
		Init::newController( ucfirst($_GET['action']) )->$method();
		//LG ( microtime(true) - $start );
	}

}
?>
