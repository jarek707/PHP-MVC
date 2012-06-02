<?php
class Db {
  static $link = null;

	public function __construct() {
	  if ( self::$link == null ) {
		  extract( Init::$config );
		  self::$link = mysql_connect($dbhost, $dbuserName, $dbpassword);
		  mysql_select_db($dbname, self::$link);
	  }
	}

	public function all( $sql ) {
	  $retA = array();
	  $res = mysql_query( $sql );
	  if ( $res ) {
	    if ( mysql_num_rows($res) === 0 ) {
	      return false;
	    } else {
				while ( $row = mysql_fetch_assoc( $res ) ) {
					array_push( $retA, $row );
				}
			}
		} else {
		  LG( mysql_error() . ' SQL:' . $sql , 'DB' );
		}
	  return $retA;
	}

	public function row( $sql ) {
		return ( $retA = $this->all( $sql ) ) ? $retA[0] : $retA;
	}

	public function first( $sql ) {
		return ( $retA = $this->all( $sql ) ) ? array_pop($retA[0]) : $retA;
	}

	public function one( $sql, $whichOne = 0 /* either 0 or column name */ ) {
		if ( $whichOne === 0 ) {
			return $this->first( $sql );
		} else {
			$retA = $this->all( $sql );
			return ( $retA ) ? $retA[0][$whichOne] : $retA;
		}
	}

	public function resultEmpty( $sql ) {
	  $res = mysql_query( $sql );
		return mysql_num_rows( $res ) !== 0;
	}

	public function query( $sql ) {
		$res = mysql_query( $sql );
		if ( !$res ) {
		  LG( mysql_error() . ' SQL:' . $sql , 'DB' );
		}
	}
}

class DAO extends Db {
}

$db = new Db();
