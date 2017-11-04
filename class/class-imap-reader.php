<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-mailbox.php' );

class imapReader extends imapWatch{
	private $mboxes;

	/**
	*
	*/
	function __construct(){
		parent::__construct();
		return $this;
	}
	
}


?>