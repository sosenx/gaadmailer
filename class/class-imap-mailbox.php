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
	public $parentObj;

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
			$f=1;		
			foreach ($triggers as $tkey => $trigger) {
				/*
				* Sprawdzanie, czy wiadomosc pasuje do triggera
				*/				
				if ( $trigger->check( $header ) ) {
					$mail = $this->mailbox->getMail( $header->uid );
					$this->parentObj->getTodos()->add( 
						array(
							'mailbox_id' => $trigger->mailbox_id, 
							'email_id' => $header->uid,
							'action_id' => $trigger->action_id,
							'action' => $this->getAction( $trigger->action_id )->toJSON(), 
							'header' => json_encode( $header ), 
							'textPlain' => $mail->textPlain,
							'textHtml' => $mail->textHtml
						)
					);
				//	$this->mailbox->moveMail( $header->uid, str_replace( 'INBOX', 'Archives', $this->host ) );
				//	$this->mailbox->deleteMail( $header->uid );
					$r=1;

				}
				
			}
		}

	}
	
	/**
	* Pobiera triggery
	*/
	function getAction( int $actionId ){		
		return $this->parentObj->getAction( $actionId );		
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