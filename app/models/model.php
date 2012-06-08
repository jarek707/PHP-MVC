<?php
	class Model {

		public function getAll( $type ) {
			$retA = Init::$db->all($sql = "SELECT * FROM $type");
			return $retA;
		}

		public function reindex_by( $targetA, $id, $subId = false ) {
			if ( !$targetA ) return array(); 

			$workA = array();
			foreach ( $targetA as $line ) {
				$itemId = $line[$id];
				unset( $line[$id] );
				if ( $subId ) {
					$subItemId = $line[$subId];
					unset($line[$subId]);
					$workA[$itemId][$subItemId] = $line;
				} else  {
					$workA[$itemId] = $line;
				}
			}
			return $workA;
		}
	}
?>
