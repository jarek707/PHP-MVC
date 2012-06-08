<?php
	Init::newController( '' );

	class EditsController extends Controller {
		private $model = null;

		public function __construct() {
			echo $this->setHeader( array('app.less', 'home') );
			$this->model = Init::newModel( 'Model' );
		}

		public function index() {
			echo "<div class='editWrap'>";
				self::$tpl->itemType = 'boat';
				self::$tpl->ItemType = 'Boats';
				echo $this->_showByType();

				self::$tpl->itemType = 'student';
				self::$tpl->ItemType = 'Students';
				echo $this->_showByType();
			echo "</div>";
		}

		private function _showByType() { // should be moved to helper
			$items = $this->model->getAll( self::$tpl->itemType );
			self::$tpl->items= '';
			if ( $items ) {
				foreach ( $items as $item ) {
					self::$tpl->extract( $item );
					self::$tpl->items .= $this->render(self::$tpl->itemType);
				}
			}
			self::$tpl->header = $this->render( self::$tpl->itemType . '_head');
			return 	$this->render('index') . $this->render('add_' . self::$tpl->itemType);
		}

		public function add() {
			echo $this->render('add'). $this->setFooter();;
		}

/*		public function show() {
			echo "<div class='editWrap'>";
				self::$tpl->itemType = 'boat';
				self::$tpl->ItemType = 'Boats';
				echo $this->_showByType();

				self::$tpl->itemType = 'student';
				self::$tpl->ItemType = 'Students';
				echo $this->_showByType();
			echo "</div>";
		}
		*/

		public function find() {
		}
	}
?>
