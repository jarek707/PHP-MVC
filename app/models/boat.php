<?php
	Init::newModel( 'Model' );

	class Boat extends Model {
		private $list = null;
		private $library_boat_id = -1;
		
		public function __construct() {
			$this->library_boat_id = Init::$db->first('SELECT id_boat from boat_has_book');
			$lists = Init::$db->all("
				SELECT b.id boat_id, b.name boat_name, 
					CONCAT(id_student_skipair1,id_student_skipair2,id_student_skipair3,id_student_skipair4) ski_list
					FROM boat b 
					JOIN boat_has_skipair bs ON (b.id=bs.id_boat)
			");
			$this->list = $this->reindex_by( $list, 'boat_id' );
		}

		public function getLibraryBoatId() {
			return $this->library_boat_id;
		}
	}
?>
