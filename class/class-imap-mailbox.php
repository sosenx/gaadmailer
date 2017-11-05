<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-email.php' );

class imapMailbox{

	private $id;
	private $host;
	private $login;
	private $pass;
	private $mailbox;
	private $parentObj;

	/**
	*
	*/
	function __construct( array $input, $parentObj ){
		if($this->parseInput( $input )){
			$this->mailbox = new \PhpImap\Mailbox( $this->host, $this->login, $this->pass, __DIR__);
			$this->id = $input[ 'id' ];
			$this->parentObj = $parentObj;

			/*
			* Sprawdzanie skrzynki 
			*/
			$this->checkMailbox();
			
		} 
		return $this;
	}

	/**
	*
	*/
	function checkMailbox(){
		$mailsIds = $this->mailbox->searchMailbox('ALL');
		$triggers = $this->getTriggers();
		$emailsInfo = $this->mailbox->getMailsInfo( $mailsIds );
		
		foreach ($emailsInfo as $key => $header) {
			foreach ($triggers as $tkey => $trigger) {
				/*
				* Sprawdzanie, czy wiadomosc pasuje do triggera
				*/
				if ( $trigger->check( $header ) ) {
					

				}
				
			}
		}

	}
	
	/**
	* Pobiera triggery
	*/
	function getTriggers(){		
		return $this->parentObj->getTriggers( $this->id );		
	}

	/**
	*
	*/
	function getParentObj(){		
		return $this->parentObj;
	}



	/**
	*
	*/
	function parseInput( array $input ){
		if ( is_array( $input )) {
			if ( !is_null( $input[ 'host' ] ) ) {
				$this->host = $input[ 'host' ];
			}
			if ( !is_null( $input[ 'login' ] ) ) {
				$this->login = $input[ 'login' ];
			}
			if ( !is_null( $input[ 'pass' ] ) ) {
				$this->pass = $input[ 'pass' ];
			}
		}
		return true;
	}

}


?>