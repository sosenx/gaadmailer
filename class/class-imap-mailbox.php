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
		$this->mailbox = new \PhpImap\Mailbox( $this->host, $this->login, $this->pass, __DIR__);
		$mailsIds = $this->mailbox->searchMailbox('ALL');
		$triggers = $this->getTriggers();
		$emailsInfo = $this->mailbox->getMailsInfo( $mailsIds );
		
		foreach ($emailsInfo as $key => $header) {	
			if ( IMAP_PROCESS_ONLY_UNSEEN && $header->seen === 1 ) {
				continue;
			}		

			foreach ($triggers as $tkey => $trigger) {
				/*
				* Sprawdzanie, czy wiadomosc pasuje do triggera
				*/	
				
				if ( (int)$this->id !== (int)$trigger->mailbox_id) {
					continue;
				}	
				elseif ( $trigger->check( $header ) ) {
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

					$this->markEmailAfterProcess( $header->uid, $header );

				}				
			}
		}
	}
	

	/**
	* Flaguje wiadomość w zależności od ustawienia stalej IMAP_MARK_AFTER_PROCESS
	*
	* @param int $uid Id wiadomości email
	*/
	function markEmailAfterProcess( int $uid, $header = NULL ){		
		

		switch( IMAP_MARK_AFTER_PROCESS ){

			case 'seen' : 
				$this->mailbox->markMailAsRead( $uid );
			break;

			case 'unseen' : 
				$this->mailbox->markMailAsUnread( $uid );
			break;

			default : 	
				if ( IMAP_PROCESS_ONLY_UNSEEN ) {
					$this->mailbox->markMailAsUnread( $uid );
					return;
				} 

				if ( !is_null( $header ) ) {
					
					if ( $header->seen == 0) {
						$this->mailbox->markMailAsUnread( $uid );
						return;
					} else {
						$this->mailbox->markMailAsRead( $uid );
						return;
					}
				}


			break;	
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