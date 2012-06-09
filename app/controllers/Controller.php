<?php
	// Minor violation of seperating HTML from PHP happens here.
	class Controller {
		static $viewDir = '';
		static $tpl     = false;
		static $action  = '';
		static $method  = '';

		public function __construct() {
			if ( !self::$tpl ) self::$tpl = new View();
			self::$tpl->rootUrl = Init::$rootUrl;

			self::$method  = $method = RouterController::$method;
			self::$action  = lcfirst(  RouterController::$action);
			self::$viewDir = $viewDir = Init::$rootView . 'tpl/' . self::$action . '/' ;
			
			$errS = '';
			if ( !file_exists( $viewDir ) ) {
				$errS .= "<br />Add template directory for this controller at <b>$viewDir</b>";
			} 
			if ( !file_exists( "${viewDir}${method}.tpl" ) ) {
				$errS .= "<br />Add template: <br /><b><i>&nbsp;&nbsp;${viewDir}${method}.tpl</i></b><br /> for method <b>${method}</b><br />";
			}

			if ( Init::$weAreInDevelopment ) {
				echo $errS;
			} else {
				if ( $errS )
					echo 'Page <b><i>' . Init::$rootUrl . self::$action . '/' . self::$method . '</i></b> does not exist';
			}
		}

		public function __call($action, $method) {
			if ( Init::$weAreInDevelopment ) {
				echo "<br />Add method:<i><b><br />&nbsp; public function " . self::$method 
					. "() {}</i></b><br /> to file <b>" . Init::$rootCtrl .  ucfirst(self::$action) 
					. 'Controller.php</b>';
			} else {
				//header('Location:' . Init::$rootUrl);
			}
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

		public function setIncludes( $cssA = array('app.less'), $jsA = array('jquery','app.coffee') ) {

			$outS = '';
			foreach ( array( 'css' => $cssA, 'js' => $jsA ) as $typ => $fileList ) 
				foreach ( $fileList as $file ) {
					$this->setTplVar('file', $file);
					$outS .= $this->render("page/${typ}.tpl");
				}
			return $outS;
		}

		public function setHeader( $cssA = array(), $jsA = array('jquery','app.coffee') ) {
			$this->setTplVar('headContent', $this->setIncludes( $cssA, $jsA ));
			return $this->render('page/head.tpl');
		}

		public function setFooter() {
			return $this->render('page/foot.tpl');
		}
	}
?>
