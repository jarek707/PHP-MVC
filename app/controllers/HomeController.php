<?php
	Init::newController( '' );

	class HomeController extends Controller {
		public function __construct() {
			echo $this->setHeader(array('app.less', 'home') );
		}

		public function index() {
			echo $this->render('index');
			echo $this->setFooter();
		}

		public function create() {
		}

		public function find() {
		}
	}
?>
