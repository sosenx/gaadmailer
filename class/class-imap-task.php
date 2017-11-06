<?php
namespace imapwatch;
/**
*
*/


class imapTask {
	
	private $task;
	private $action;
	private $phpClass;
	private $phpClassExists;
	/**
	*
	*/
	function __construct( array $todo_record_array ){
		
		$this->parseAction( $todo_record_array[ 'action' ] );

		return $this;
	}

	/**
	*
	*/
	function parseAction( string $json ){
		$this->action = json_decode( $json, true );
		$this->phpClass = $this->action[ 'php-class' ];
		$this->phpClassExists = class_exists( str_replace( '\\', '\\\\', $this->phpClass));
		return $this->action;
	}
	
}


?>