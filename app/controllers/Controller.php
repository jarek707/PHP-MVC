<?php
	// Minor violation of seperating HTML from PHP happens here.
	class Controller {
		static $viewDir = '';
		static $tpl     = false;
		static $action  = '';
		static $method  = '';

		public function __construct() {
			if ( !self::$tpl ) self::$tpl = new View();

			self::$action  = $action  = RouterController::$action;
			self::$method  = $method  = RouterController::$method;
			self::$viewDir = $viewDir = Init::$rootView . 'tpl/' . lcfirst($action) . '/' ;

			if ( !file_exists( $viewDir ) ) {
				echo "<br />Add template directory for this controller at <b>$viewDir</b>";
			} 
			if ( !file_exists( "${viewDir}${method}.tpl" ) ) {
				echo "<br />Add template for method <b>${method}</b> in <b>${viewDir}${method}.tpl</b>";
			}
		}

		public function __call($action, $method) {
			echo "<br />Add method <b>public function " . RouterController::$method 
				. "()</b> to file <b>" . Init::$rootCtrl .  RouterController::$action 
				. 'Controller.php</b>';
		}

		// Template name is either full path inside rootView or just a file name without .tpl
		protected function render( $tplName ) {
			if ( ( strpos($tplName, '/') === false ) && (strpos($tplName, '.') === false ) ) {
				return 	( file_exists(self::$viewDir . $tplName . '.tpl') )
								? self::$tpl->render(self::$action . '/' . $tplName . '.tpl')
								: "Missing template $tplName";
			} else {
				return 	( file_exists(Init::$rootView . "tpl/${tplName}") )
								? self::$tpl->render($tplName)
								: "Missing template $tplName";
			}	
		}
	}
?>
