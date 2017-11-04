<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-imap-trigger.php');


class imapTriggers {
	private $triggers = NULL;

	/**
	*
	*/
	function __construct(){		
		if ( is_null($this->triggers) ) {
			$this->getTriggersFromDB();
		}
		return $this;
	}


	/**
	* Ustawia prywatną zmienna triggers
	*/
	function setTriggers( array $triggers ){		
		$this->triggers = $triggers;
	}


	/**
	* Zwraca prywatną zmienna triggers
	*/
	function getTriggers( ){		
		return $this->triggers;
	}

	/**
	*
	*/
	function getTriggersFromDB(){		
		global $wpdb;
		$triggers_table_name = IMAP_DB_TABLE_PREFIX . 'triggers';
		$r = $wpdb->get_results( "SELECT * FROM `" . $triggers_table_name . "`", ARRAY_A );
 		$max = count( $r );
 		$t = array();

 		for ($i=0; $i<$max ; $i++) {
 			$t[ sanitize_title( $r[ $i ][ 'label' ] ) ] = new imapTrigger( $r[ $i ] );
 		}

 		$this->setTriggers( $t );
		return $this;
	}
	
}


?>