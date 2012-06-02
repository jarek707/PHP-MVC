<?php
class Init {
  //Application Paths
  static $config   = array();
  static $rootUrl  = '';
  static $rootDoc  = '';
  static $rootLog  = '';
  static $rootApp  = '';
  static $urlJs    = '';
  static $urlCss   = '';
  static $rootMod  = '';
  static $rootView = '';
  static $rootCtrl = '';
  static $rootRes  = '';
  static $rootPhpLib  = '';
  static $configRes   = '';

	static $prod_dev = '';
  static $fp         = null;
  static $profile_ID =   -1;

  static $db         =   -1;
  
  public function __construct() {

    if ( self::$rootUrl == '' ) {
      if ( !isset($_SESSION) || $_SESSION == NULL)
      	session_start();
			self::$rootDoc = $_SERVER['DOCUMENT_ROOT'] . '/';
      self::$rootUrl = $_SERVER['HTTP_HOST'] . '/';

      $profile = ( isset($_SERVER['SERVER_ENV']) ) ? $_SERVER['SERVER_ENV'] : 'local';

	    $configA = parse_ini_file( self::$rootDoc . 'config/app.ini', true );
      self::$config = $configA[$profile];

		
      $this->init($configA['paths']);

      require_once(self::$rootPhpLib . 'View.php');
     // $this->build_assets();
    }
  }

  private function init($pathsA) {
    foreach ( $pathsA as $pFix => $relPath ) {
      if ( substr( $pFix, 0, 3 ) === 'url' ) {
      	self::${"{$pFix}"} = 'http://' . self::$rootUrl . $relPath . '/';
      } else {
      	self::${"{$pFix}"} = self::$rootDoc . $relPath . '/';
      }
    }
    self::$prod_dev = '/publisher';

    require_once( self::$rootApp . 'models/db.php');
    self::$db = new Db();
  }

  public function refactoring() { return self::$refactoring; }

  // This builds .css out of .less files
  public function build_assets() {
		require_once(self::$rootPhpLib . 'lessc.inc.php');

		$dirA = scandir( $assetsDir = self::$rootApp . 'assets/css/' );
		foreach ( $dirA as $file_name ) {
			if ( substr($file_name,-5) == '.less') {
			  lessc::ccompile( $assetsDir . $file_name, $assetsDir . $file_name . '.css' );
			}
		}

  }
	// Factory
	public static function newModel( $model, $createInstance = true )          { 
		list( $model ) = explode('.', $model );
		require_once(self::$rootMod . lcfirst($model) . '.php');
		if ( $createInstance ) return new $model(); 
	}

	public static function newController( $controller, $createInstance = true ) { 
		$ctrl = $controller . 'Controller';
		require_once(self::$rootCtrl . $ctrl . '.php'); 
		if ( $createInstance ) return new $ctrl(); 
	}

  public static function log( $msg, $head = 'LOG' ) {
    self::$fp == null ? self::$fp = fopen(Init::$rootLog . 'mvc.log', 'a+') : null;
    ob_start();
    	var_dump( $msg );
    	fwrite( self::$fp, $head . ':' . ob_get_contents() );
    ob_end_clean();
  }
}

// GLOBAL Scope
function LG( $msg, $head='LOG' ) { Init::log( $msg, $head ); }

function timeIt( $class, $method, $args = array() ) {
  $start = microtime( true );
  call_user_func_array( array( $class, $method ), $args );
  LG( "CALL TO ${class} ${method} took" . (microtime(true) - $start) );
}

function DMP( $var, $lab="DUMP" ) {
  $tpl = new View();
  ob_start();
  	var_dump( $var );
  	$tpl->dumpS = preg_replace("/\n/","<br />", addslashes(ob_get_contents()));
  ob_end_clean();
  $tpl->lab = $lab;
  echo $tpl->render( 'common/var_dump.tpl' );
}

new Init();
?>
