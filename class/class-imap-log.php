<?php
namespace imapwatch;
/**
*
*/


class log{
	
	/**
	*
	*/
	function __construct(){
		parent::__construct();
		return $this;
	}

	/**
	* zapisuje rekord do logu w bazie danych
	*/
	public static function write( array $input ){		
		global $wpdb;

		$r = $wpdb->insert( IMAP_DB_TABLE_PREFIX . 'log', array(
			'log' => json_encode( $input, true )
			)
		);
		return $r;
	}
	
}


?>