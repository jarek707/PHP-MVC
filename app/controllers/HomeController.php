<?php
	Init::newController( '' );

	class HomeController extends Controller {
		public function __construct() {
		}

		public function index() {
			echo $this->render('index');
			echo self::$tpl->render('home/index.tpl');
		}

		public function create() {
		}

		public function find() {
		}
	}
?>
