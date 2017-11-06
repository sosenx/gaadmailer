<?php
namespace imapwatch;
/**
*
*/


class imapToDo {

	private $todos = array();

	/**
	*
	*/
	function __construct(){
		
		return $this;
	}


	/**
	*
	*/
	function add( array $input ){		
		global $wpdb;
		$input = array_merge( array( 'status' => 'added' ), $input );

		$check = "SELECT *  FROM `imw_todo` WHERE `mailbox_id` = " . $input['mailbox_id'] . " AND `email_id` = ". $input['email_id'] ." AND `action_id` = ". $input['action_id'];
		$r = $wpdb->get_results( $check, ARRAY_A);
		$task_exists = is_array( $r ) && !empty( $r );
		
		if ( $task_exists ) {
			log::write( array(
				'msg' => 'proba ponownego dodania maila: '.$input['mailbox_id'].':'.$input['email_id'].':'. $input['action_id'] 
			));
			return false;
		} else {
			$r = $wpdb->insert( IMAP_DB_TABLE_PREFIX . 'todo', $input);
			return $r;	
		}
		
		
	}
	
}


?>