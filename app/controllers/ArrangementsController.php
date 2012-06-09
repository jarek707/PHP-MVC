<?php
	Init::newController( '' );

	class ArrangementsController extends Controller {
		static $boatM    = null;
		static $studentM = null;

		public function __construct() {
			echo $this->setHeader( array('app.less'), array('jquery', 'jquery-ui', 'app.coffee', 'arrange.coffee') );
			self::$studentM = Init::newModel( 'Student' );
			self::$boatM    = Init::newModel( 'Boat' );
		}

		public function index() {
			self::$tpl->itemType = 'boat';
			$boats = self::$boatM->getAll( self::$tpl->itemType );
			self::$tpl->boatList = '';
			$boat_idx = 1;
			foreach ( $boats as $id_boat => $boat ) {
				self::$tpl->id = $boat['id'];
				self::$tpl->boatStudents = '';
				$boat_students = self::$studentM->getBoatStudents($boat['id']);
				LG( $boat_students );
				if ( $boat_students ) {
					foreach ( $boat_students as $id => $student ) {
						self::$tpl->extract($student);
						self::$tpl->id_student = $id;
						self::$tpl->boatStudents .= $this->render('boat_student');
					}
				}
				self::$tpl->boatName = $boat['name'];
				self::$tpl->library = ( self::$boatM->getLibraryBoatId() == $boat['id'] ) ? 'library' : '';
				self::$tpl->boatList .= $this->render('boat');
				if ( !($boat_idx++ % 5) )  {
					self::$tpl->boatList .= '</div><div class="toggle show"></div><div class="boatLine">';
				}
			}
			self::$tpl->boatList = '<div class="toggle show"></div><div class="boatLine">' . self::$tpl->boatList . '</div>';
			echo $this->render('boat_list');

			$unassigned = self::$studentM->getUnassigned();
			self::$tpl->unassigned = '';
			foreach ( $unassigned as $id => $student ) {
				self::$tpl->extract($student);
				self::$tpl->id_student = $id;
				self::$tpl->unassigned .= $this->render('boat_student');
			}

			echo $this->render('unassigned');
			echo $this->setFooter();
		}
	}
?>
