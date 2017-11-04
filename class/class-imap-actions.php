<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-imap-action.php');


class imapActions {
	private $actions = NULL;

	/**
	*
	*/
	function __construct(){		
		if ( is_null($this->actions) ) {
			$this->getActionsFromDB();
		}
		return $this;
	}


	/**
	* Ustawia prywatną zmienna actions
	*/
	function setActions( array $actions ){		
		$this->actions = $actions;
	}


	/**
	* Zwraca prywatną zmienna actions
	*/
	function getActions( ){		
		return $this->actions;
	}

	/**
	*
	*/
	function getActionsFromDB(){		
		global $wpdb;
		$actions_table_name = IMAP_DB_TABLE_PREFIX . 'actions';
		$r = $wpdb->get_results( "SELECT * FROM `" . $actions_table_name . "`", ARRAY_A );
 		$max = count( $r );
 		$a = array();

 		for ($i=0; $i<$max ; $i++) {
 			$a[ sanitize_title( $r[ $i ][ 'label' ] ) ] = new imapAction( $r[ $i ] );
 		}

 		$this->setActions( $a );
		return $this;
	}
	
}


?>