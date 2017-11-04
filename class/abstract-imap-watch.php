<?php
namespace imapwatch;

require_once( 'config-imap.php' );

require_once( 'class-imap-log.php' );
if( IMAP_WATCH_ENV === 'PRESTART' || IMAP_WATCH_ENV === 'DEV'){ require_once( 'class-imap-dev-log.php' ); }

require_once( 'class-imap-triggers.php' );
require_once( 'class-imap-actions.php' );

require_once( 'class-create-table.php' );

require_once( 'class-imap-prestart.php' );
require_once( 'class-imap-reader.php' );



/**
*
*/ 
abstract class imapWatch{
	/*
	*Przchowuje wczytane z bazy skrzynki pocztowe
	*/
	private $mail_boxes;
	private $triggers;
	private $actions;




	/**
	*
	*/
	function __construct(){
		if( IMAP_WATCH_ENV === 'PRESTART'){
			$this->checkDB();
		}

		/**
		* Pobieram skrzynki
		*/
		$this->getMailBoxes();


		/**
		* Pobieram triggery
		*/
		$this->getTriggers();

		/**
		* Pobieram akcje
		*/
		$this->getActions();

		

		return $this;		
	}
	
	/**
	* Pobiera triggery
	*/
	function getActions(){
		$this->actions	= new imapActions();
	}


	/**
	* Pobiera triggery
	*/
	function getTriggers(){
		$this->triggers	= new imapTriggers();
	}

	/**
	* Tworzy listę obsugiwanych przez Imap Watcher skrzynek
	*/
	function getMailBoxes(){		
		global $wpdb;		
		$mailboxes_table_name = IMAP_DB_TABLE_PREFIX . 'mailbox';
		$r = $wpdb->get_results( "SELECT * FROM `" . $mailboxes_table_name . "`", ARRAY_A );
 		$max = count( $r );

		if ( is_array( $r ) && $max > 0) {
			for ($i=0; $i < $max ; $i++) { 
				$this->addMailbox( $r[ $i ] );
			}
		}

		return $this->mail_boxes;
	}


	/**
	* Dodaje skrzynkę pocztową do klasy
	*/
	function addMailbox( array $mailbox ){		
		$this->mail_boxes[ sanitize_title($mailbox['label'] ) ]= $mailbox;		
	}
	
	/**
	* Sprawdza i tworzy środowisko aplikacji
	*/
	function checkDB(){
		prestart::test('db_tables');		
		return $this;
	}
	
}


?>