<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-imap-task.php' );

class imapWorker {
	
	private $todos;
	/**
	*
	*/
	function __construct(){
		
		$this->getTodos();
		return $this;
	}

	/**
	* Tworzy listę obsugiwanych przez Imap Watcher skrzynek
	*/
	function getTodos(){		
		global $wpdb;		
		$table_name = IMAP_DB_TABLE_PREFIX . 'todo';
		$r = $wpdb->get_results( "SELECT * FROM `" . $table_name . "` WHERE `status` LIKE 'added' LIMIT 0, 10", ARRAY_A );
 		$max = count( $r );

		if ( is_array( $r ) && $max > 0) {
			for ($i=0; $i < $max ; $i++) { 
				$this->todos[] = new imapTask( $r[ $i ] );
			}
		}
	}	
}


?>