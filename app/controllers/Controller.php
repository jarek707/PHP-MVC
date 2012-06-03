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

		public function setTplVar( $var, $val ) { self::$tpl->$var=$val; }

		public function setIncludes( $cssA = array(), $jsA = array() ) {
			$cssA = ( $cssA ) ? $cssA : array('app.less');
			$jsA  = ( $jsA  ) ? $jsA  : array('jquery', 'app.c');
			
			self::$tpl->rootUrl = Init::$rootUrl;

			$outS = '';
			foreach ( array( 'css' => $cssA, 'js' => $jsA ) as $typ => $fileList ) 
				foreach ( $fileList as $file ) {
					$this->setTplVar('file', $file);
					$outS .= $this->render("page/${typ}.tpl");
				}
			return $outS;
		}

		public function setHeader( $cssA = array(), $jsA = array() ) {
			$this->setTplVar('headContent', $this->setIncludes( $cssA, $jsA ));
			return $this->render('page/head.tpl');
		}

		public function setFooter() {
			return $this->render('page/foot.tpl');
		}
	}
?>
