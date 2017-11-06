<?php
namespace imapwatch;

/**
* Szablon dla klas wykonujacych konkretne zadania pochodzace z listy todo
* Klasa ta musi być samodzielna ale dobrzy by byo jakby bazowaa na tej abstrakcji
*/
abstract class imapTaskWorker {
	
	private $mailbox_id;
	private $email_id;
	private $config;

	/**
	*
	*/
	function __construct( array $input ){
	
		$this->configure( $input );
		return $this;
	}

	/**
	*
	*/
	function configure( array $input ){
		$this->config = $input;
		
		return $this;
	}


	
}


?>