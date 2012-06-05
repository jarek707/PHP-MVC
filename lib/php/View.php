<?php
class View
{
		private static $rootTpl = '';
		private $constVars = array();

		private static $_genericSubTemplateIndex = 0;
		/**
		*
		*/
		public static function callHelper(/* $helperName, arg1, arg2 ... */)
		{
				$args = func_get_args();
				$helperName = array_shift($args);
				$ucHelperName = ucfirst($helperName);

				$fileName = Init::$docRoot . "application/views/helpers/$ucHelperName.php";

				if (file_exists($fileName)) {
						require_once $fileName;

						$className = "ITV_View_Helper_$ucHelperName";
				} else {
						$className = "Zend_View_Helper_$ucHelperName";
				}

				$helper = new $className();

				return call_user_func_array(array($helper, $helperName), $args);
		}

		protected static $cachedFiles = array();

		// END STATIC DEFINITIONS

		public function __construct( $args = array() )
		{
				$this->setConsts( $args );

				if ( self::$rootTpl == '' ) 
					self::$rootTpl = Init::$rootApp . 'views/tpl/';
		}

		private function setConsts( $varList ) {
			if ( is_array( $valList ) ) {
				$this->constVars = $valList;
			} elseif (is_string( $varList )) {
				$vars = explode(',', $varList );
				foreach ( $vars as $varName ) {
					array_push( $this->constVars, trim($varName) );
				}
			}
		}


		public function render( $fileName, $data = null ) {
		$this->outS = '';

		try {
			return $this->_run( $fileName );
		} catch (Exception $e) {
			LG( $e->getMessage(), 'View Render:' );
			return '';
		}
	}

	private function assign( $arg ) {
		if ( $arg ) 
			array_push( $this->store, $arg );
 	}
		

		private $_inJsTemplatingMode = false;

		/**
		*
		*/
		private function _throwException($message)
		{
				throw new Exception($message);
		}

		/**
		*
		*/
		private function _getVarValue($var)
		{
				if (is_null($this->$var) || $this->$var === '') {
						if ($this->_inJsTemplatingMode) {
								// for javascript templating
								return "[%% $var %%]";
						} else {
								return '';
						}
				} else {
						return $this->$var;
				}
		}

		/**
		*
		*/
		private function _getQuotedString(&$inStr, $shiftFromString = false)
		{
				$value = null;
				foreach (array('"', "'") as $quote) {
						if (strpos($inStr, $quote) === 0) {
								@list($value, $rest) = explode($quote, substr($inStr, 1), 2);
						}
				}

				if (is_null($value)) { // not quoted: it's a variable name
						@list($var, $rest) = preg_split('/\s+/', $inStr, 2);
						$value = $this->_getVarValue($var);
				}

				$rest = ltrim($rest);
				if (substr($rest, 0, 1) == '.') { // '.' concatenates
						$rest = ltrim(substr($rest, 1));
						$value .= $this->_getQuotedString($rest, true);
				}

				if ($shiftFromString) { $inStr = $rest; }

				return $value;
		}

		/**
		*
		*/
		private function _getAssignments($inStr, $shiftFromString = false)
		{
				$rest = ltrim($inStr);
				$retArr = array();
				while (!empty($rest)) {
						if (substr($rest, 0, 7) == 'EXTRACT') {
								@list($varName, $rest) = preg_split('/\s+/', substr($rest, 8), 2);
								if (!is_array($this->$varName)) {
										if ($this->_inJsTemplatingMode) {
												echo "[%% EXTRACT $varName %%]";
										}
								} else {
										$retArr = array_merge($retArr, $this->$varName);
								}
								continue;
						}
						@list($varName, $rest) = explode('=', $rest, 2);
						$rest = ltrim($rest);
						$quotedString = $this->_getQuotedString($rest, true);
						$retArr[$varName] = $quotedString;
						$rest = ltrim($rest);
				}

				return $retArr;
		}

		/**
		*
		*/
		private function _validateCondition($inStr)
		{
				@list($var, $rest) = preg_split('/\s+/m', $inStr, 2);

				if ($negativeValue = ($var == 'NOT')) {
						@list($var, $rest) = preg_split('/\s+/m', $rest, 2);
				}

				if (strpos($rest, 'EQUALS') === 0) {
						$compareVar = $this->_getQuotedString(ltrim(substr($rest, 6)));
						$isTrue = $this->$var == $compareVar;
				} else {
						$isTrue = !(empty($this->$var));
				}

				return $negativeValue != $isTrue;
		}

		/**
		*
		*/
		public function renderWith($fileName, $data = null)
		{
				if (is_null($data)) { return $this->render($fileName); }

				$save = array();
				foreach ($data as $key => $value) { $save[$key] = $this->$key; }
				$this->assign($data);
				try { 
							$retStr = $this->render($fileName);
							$this->assign($save);
				} catch( Exception $e) { LG( $e->getMessage(), 'View Render With:') ; }

				return $retStr;
		}

		/**
		*
		*/
		public function renderForJavascript($label, $tplFileName, $data = array())
		{
				$this->_inJsTemplatingMode = true;
				$data['ITER'] = 'Js';
				$html = $this->renderWith($tplFileName, $data);
				$this->jsCode()->addTemplate(
								$label,
								array('total' => 0,
											'html'	=> $html,
											'data'	=> array())
				);
				$this->_inJsTemplatingMode = false;
		}

		/**
		*
		*/
		public function iterateOver($arr,
																$subTplFileName,
																$extra = null,
																$jsTemplateName = null)
		{
				$save = array();
				$iterSave = $this->ITER;

				if (!is_null($extra)) {
						foreach ($extra as $key => $value) {
								$save[$key] = $this->$key;
						}
						$this->assign($extra);
				}

				$retStr = '';
				
				if (is_array($arr)) {
						foreach ($arr as $i => $data) {
								$this->ITER = $i;
								$retStr .= $this->renderWith($subTplFileName, $data);
						}
				} else {
						if (!$this->_inJsTemplatingMode) {
								$this->_throwException('Trying to iterate a template over a non array');
						} else {
								$varName = $arr; // overloaded
								$index = self::$_genericSubTemplateIndex++;
								$this->ITER = '_jsTemplate_';

								$this->jsCode()->addSubTemplate($index, $this->render($subTplFileName));
								$retStr = "[%% ITERATE $varName $index %%]";
						}
				}

				if (!is_null($jsTemplateName)) {
						$this->ITER = '_jsTemplate_';

						$this->_inJsTemplatingMode = true;
						$this->jsCode()->addTemplate(
								$jsTemplateName,
								array('total' => count($arr),
											'html'	=> $this->render($subTplFileName),
											'data'	=> $arr)
						);
						$retStr .= "<span id=\"jsTemplatePlaceholder_$jsTemplateName\"></span>";
						$this->_inJsTemplatingMode = false;
				}

				$this->ITER = $iterSave;
				//$this->assign($save);

				return $retStr;
		}

		/**
		*
		*/
	private function getTplFile( $fileName ) {
		// if file has no file extension look in common *.tpl files
		if ( preg_match('/^[a-zA-Z0-9]*$/', $fileName) ) {
			$fileName = self::$rootTpl . 'common/' . $fileName . '.tpl';
		} else {
				$fileName = self::$rootTpl . $fileName;
		}

		if (!array_key_exists($fileName, self::$cachedFiles)) {
			if (strrchr($fileName, '.') != '.tpl') {
				ob_start();
					include $fileName;
					$guts = ob_get_contents();
				ob_end_clean();
			} else {
				$guts = file_get_contents($fileName);
			}

			if (!$guts) { $this->_throwException("Could not read file $fileName"); }

			self::$cachedFiles[$fileName] = $guts;
		} else {
			$guts = self::$cachedFiles[$fileName];
		}
		return $guts;
	}

	protected function _run()
	{
		$htmlS = '';
		if ( !($guts = $this->getTplFile( $tplFile = func_get_arg(0) )) ) return;

		$ifCount = 0;

		$save = array();

		while (!empty($guts)) {
			// we use list() = explode() to cursor through the file
			@list($raw, $guts) = explode('[%%', $guts, 2);
			if (!$ifCount) {
					$htmlS .= $raw;
			}

			if (empty($guts)) { break; }

			@list($paramStr, $guts) = explode('%%]', ltrim($guts), 2);

			@list($first, $paramStr) = preg_split('/\s+/m', ltrim($paramStr), 2);
			LG( $first, ' first ' );
			LG( $gust , ' guts' );

			if ($ifCount > 0) {
					switch ($first) {
							case 'ENDIF': if (--$ifCount) { continue 2; }
							case 'ELSE':	break 1;
							case 'IF':		$ifCount++;
							default:			continue 2;
					}
			}

			switch ($first) {
				case 'ELSE':
					switch ($ifCount) {
						case 0: $ifCount = 1; break;
						case 1: $ifCount = 0; break;
						default:
					}
				case '':
				case 'ENDIF': // if we got this far then the if evaluated to true
					break;

				// IF
				case 'IF':
					if (!$this->_validateCondition($paramStr)) { $ifCount = 1; }
					break;

				// SET
				case 'SET':
					$assignments = ($this->_getAssignments($paramStr));
					// variable scope is only this templates and sub templates
					foreach ($assignments as $key => $null) { $save[$key] = $this->$key; }
					$this->assign($assignments);
					break;

				// RENDER
				case 'RENDER':
					@list($subTplFilename, $rest) = preg_split('/\s+/m', $paramStr, 2);
					if (substr($rest, 0, 4) == 'WITH') {
						// get extra params
						$vars = $this->_getAssignments(substr($rest, 5));
					} else {
						$vars = null;
					}

					if (substr($rest, 0, 2) == 'IF') {
						if (!$this->_validateCondition(substr(ltrim($rest), 3))) {
							break;
						}
					}
					$htmlS .= $this->renderWith($subTplFilename, $vars);
					break;

				case 'LOOP':
					break;
				// ITERATE
				case 'ITERATE':
					@list($propName, $subTplFilename, $rest) = preg_split('/\s+/m', $paramStr, 3);

					if (substr($rest, 0, 10) == 'JAVASCRIPT') {
						$rest = ltrim(substr($rest, 10));
						$jsTplName = $this->_getQuotedString($rest, true);
					} else {
						$jsTplName = null;
					}
					if (substr($rest, 0, 4) == 'WITH') {
						$extra = $this->_getAssignments(substr($rest, 5));
					} else {
						$extra = array();
					}
					
					$prop = ($propName == 'NULL') ? array() : $this->$propName;

					if ($this->_inJsTemplatingMode && !is_array($prop)) {
						$prop = $propName;
					}

					$htmlS .= $this->iterateOver($prop,
											$subTplFilename,
											$extra,
											$jsTplName);
					break;

				// HELPER
				case 'HELPER':
					@list($funcName, $argStr) = preg_split('/\s+/m', $paramStr, 2);
					$args = array();
					while (($argStr = ltrim($argStr)) != '') {
						if (strpos($argStr, '"') === 0) {
							@list($arg, $argStr) =
								preg_split('"', substr($argStr, 1), 2);
							$args[] = $arg;
						} else if (strpos($argStr, "'") === 0) {
							@list($arg, $argStr)
								= explode("'", substr($argStr, 1), 2);
							$args[] = $arg;
						} else {
							@list($arg, $argStr) = preg_split('/\s+/m', $argStr, 2);
							$args[] = $this->_getVarValue($arg);
						}
					}
					$htmlS .= call_user_func_array(array($this, $funcName), $args);
					$params = array();
					break;

				// VARIABLE SUBSTITUTION
				default:
					$htmlS .= $this->_getVarValue($first);
					if (!empty($paramStr) || (trim($paramStr) != '')) {
						$this->_throwException(
							'Too many params within [%% %%]'
						);
					}
				}
			} // chunk

			if (!$ifCount) {
				@list($raw, $guts) = explode('[%%', $guts, 2);
				$htmlS .= $raw;
			}

			$this->assign($save);
			return $htmlS;
		} // function
		
		public function jsWrap( $jsString ) {
				return "<script type='text/javascript'>\n$jsString\n</script>";
		}

		public function extract( $argsA, $keysA=false ) {
			if ( !$keysA ) $keysA = array_keys($argsA);
				foreach ( $keysA as $key ) { 
					$this->$key = $argsA[$key];
			}
		} 

		public function clear( $varList ) {
			$vars = get_object_vars( $this );
			if ( is_array( $varList ) ) $varA = $varList;
			// CSV case
			$varA = split(',', $varList );
			if ( $varList )
				foreach ($varA as $varName ) $this->$varName = '';
		}

		public function clearOthers( $varList ) {
			$vars = get_object_vars( $this );
			if ( is_array( $valList ) ) $varA = $valList;
			// CSV case
			array_merge( $varA = split(',', $varList ), $this->constVars );
			foreach ($vars as $varName => $varVal ) {
				if ( !in_array(trim($varName), $varA) ) {
					$this->$varName = '';
				}
			}
		}

		public function JSONSet( $inS ) {
			$params = explode( ',' , $inS );
			foreach ( $params as $pair ) {
				list($name, $val) = explode(':', $pair);
				$this->$name = $val;
			}
		}
}
