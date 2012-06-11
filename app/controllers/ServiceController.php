<?php
	class ServiceController {
		public function __construct() {
		}

		public function add() {
			if ( $_POST['tab'] == 'boat' ) {
				if ( Init::$db->first('SELECT count(*) from boat') >= 20 ) {
					echo "{status:-1, msg: 'Maximum number of boats reached'}";
					return false;
				}
			}

			$newId = Init::$db->insertFrom( $_POST );
			echo "{status:0, newId: $newId, tab: '" . $_POST['tab'] . "'}";

			if ( ( $_POST['name'] == 'Library' ) && (  $_POST['tab'] == 'boat' ) ) {
				LG ( $_POST, 'LIB');
				Init::$db->query("DELETE FROM boat_has_book");
				Init::$db->query("INSERT INTO boat_has_book (id_boat,id_book) VALUES (${newId},1)");
			}
		}

		public function del() {
			extract( $_POST );
			Init::$db->query($sql ="DELETE FROM $tab WHERE id=$id");
			echo "{status:0, id: $id, tab: '$tab'}";
		}

		public function reassign() {
			LG ( $_POST ,' reassigining');
			$now = microtime(true);
			extract ( $_POST );
			if ( isset($last_boat) && $last_boat != 'undefined' ) {
				Init::$db->query("UPDATE boat_has_student SET id_boat=$id_boat WHERE id_student=$id_student");
			} else {
				Init::$db->query("INSERT INTO boat_has_student (id_boat,id_student) VALUES ($id_boat, $id_student)");
			}
			LG( 1000*(microtime(true) - $now ), 'now ');
		}

		public function updateStudent() {
			extract ( $_POST );
			//$has_skipair = ( $has_skipair === 'true' ) ? 1 : 0;
			$sql = "UPDATE student SET last_name ='$last_name', first_name='$first_name', has_skipair=$has_skipair WHERE id=$id_student";
			LG( $sql);
			Init::$db->query( $sql );
		}
	}
?>
