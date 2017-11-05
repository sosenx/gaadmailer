<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-imap-mailbox.php' );

class imapReader extends imapWatch {
	
	private $todos;
	/**
	*
	*/
	function __construct(){
		parent::__construct();
		
		return $this;
	}
	
}


?>