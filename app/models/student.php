<?php
	Init::newModel( 'Model' );

	class Student extends Model {
		private $list = null;
		
		public function __construct() {
			$list = Init::$db->all($sql = "
				SELECT b.id boat_id, s.id student_id, 
						CONCAT(s.first_name,' ', s.last_name) student_name, s.has_skipair
					FROM boat b 
					JOIN boat_has_student bs ON (b.id=bs.id_boat)
					JOIN student s ON (s.id=bs.id_student)
			");
			$this->list = $this->reindex_by( $list, 'boat_id', 'student_id' );
		}

		public function getBoatStudents( $boat_id ) {
			return ( $this->list[$boat_id] );
	 	}
	 	
	 	public function getUnassigned() {
			$list = Init::$db->all($sql = "
				SELECT s.id, CONCAT(s.first_name,' ', s.last_name) student_name, s.has_skipair
				FROM student s 
				LEFT JOIN boat_has_student bs ON ( bs.id_student=s.id ) 
				WHERE bs.id_boat is NULL
			");
			return $this->reindex_by($list, 'id');
	 	}
	}
?>
