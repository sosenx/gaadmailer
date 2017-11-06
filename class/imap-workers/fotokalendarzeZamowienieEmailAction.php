<?php
namespace imapwatch\workers;

/**
* Odczytuje dane z tresci emaila i generuje wiadomość z iformacjami o dalszych krokach: zaplata, podanie danych do wysylki
*/
class fotokalendarzeZamowienieEmailAction extends \imapwatch\imapTaskWorker{

public $r = 3;
	/**
	*
	*/
	function __construct( array $input ){
		parent::__construct( $input );
		$this->addRules();

		
		$this->addOperation( array( $this, "parseTemplate" ) );
		$this->addOperation( array( $this, "setRespondTo" ), array( $this->acquire_reciever() )  );
		$this->addOperation( array( $this, "sendResponse" ) );

			return $this;
	}



	/**
	* Funkcja ma zadanie wybrac i zwrocic adres email na który ma zostać wysana odpowiedź.php
	*
	* Funkcja nadpisuje abstrakcje i zwraca pobrany za pomoca rules adres emal z tablicy message_data
	*/
	function acquire_reciever( ){					
		$reciever = IMAP_ADMIN_EMAIL;
		try {
			if ( isset($this->message_data['html_body']['email'][0]) ) {
				$reciever =  $this->message_data['html_body']['email'][0];	
			}			
		} catch (Exception $e) {
			$reciever = IMAP_ADMIN_EMAIL;
		}		
		$reciever = is_null( $reciever ) ? IMAP_ADMIN_EMAIL : $reciever;
		return $reciever;
	}

	/**
	* Modyfiakacja zasad
	*/
	function addRules( ){					
		//$this->addRule( 'header_rules', 'from', array( 'regExp' => '/(.*)/') );	
	}

	/**
	*
	*/
	function step2( $a, $b ){			
		$this->test = ' mamuta!';

	}

	/**
	*
	*/
	function step3( $a, $b ){			
		$d=$this->test;
$r=1;
	}



}