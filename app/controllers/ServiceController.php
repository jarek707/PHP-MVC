<?php
	class ServiceController {
		public function __construct() {
		}

		public function add() {
			$newId = Init::$db->insertFrom( $_POST );
			echo "{status:0, newId: $newId, tab: '" . $_POST['tab'] . "'}";
		}

		public function del() {
			extract( $_POST );
			Init::$db->query($sql ="DELETE FROM $tab WHERE id=$id");
			echo "{status:0, id: $id, tab: '$tab'}";
		}

		public function reassign() {
			LG ( $_POST ,' reassigining');
			extract ( $_POST );
			if ( isset($last_boat) && $last_boat != 'undefined' ) {
				Init::$db->query("UPDATE boat_has_student SET id_boat=$id_boat WHERE id_student=$id_student");
			} else {
				Init::$db->query("INSERT INTO boat_has_student (id_boat,id_student) VALUES ($id_boat, $id_student)");
			}
		}
	}
?>
