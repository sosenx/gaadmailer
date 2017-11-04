<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-email.php' );

class mailbox{
	private $host;
	private $user;
	private $pass;


	/**
	*
	*/
	function __construct( array $input ){
		if($this->parseInput( $input )){
			
		}
		return $this;
	}

	/**
	*
	*/
	function parseInput( array $input ){
		
		return true;
	}

}


?>