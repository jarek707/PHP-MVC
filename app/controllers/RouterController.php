<?php
class RouterController {
	static $action = '';
	static $method = '';

	public function __construct( $htaccess = true ) {
		
		if ( $htaccess ) {
			list($blank,$action,$method) = explode('/', $_SERVER['REQUEST_URI']);
			$methodA = explode('_',$method);
		} else {
			$action = $_GET['action'];
			if ( isset( $_GET['method'] ) ) {
				$methodA = explode('_',$_GET['method']);
			} else {
				if ( isset( $_POST['method'] ) ) {
					$methodA = explode('_',$_POST['method']);
				} else {
					$methodA = explode('_',$_POST['parent_tab']);
				}
			}
		}
		self::$action = ucfirst( $action );

		self::$method = lcfirst( array_shift($methodA) );
		if ( self::$method == '' ) self::$method = 'index';
		if ( self::$action == '' ) self::$action = 'home';
		foreach ( $methodA as $m ) self::$method .= ucfirst( strtolower($m) );

	  //$start = microtime( true );
		try                    { Init::newController( self::$action )->{self::$method}(); }
		catch ( Exception $e ) { echo $e->getMessage(); }
		//LG ( microtime(true) - $start );
	}
}
?>
