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
	private $executor;
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
	function getPhpClass( ){
		if ( $this->phpClassExists ) {
			$this->executor = new $this->phpClass( $this->action['config'] );
			return $this->executor;
		}

		return parent::__construct();
	}

	/**
	*
	*/
	function parseAction( string $json ){
		$this->action = json_decode( $json, true );
		$this->phpClass = $this->action[ 'php-class' ];
		$this->phpClassExists = class_exists( $this->phpClass );
		return $this->action;
	}
	
}


?>