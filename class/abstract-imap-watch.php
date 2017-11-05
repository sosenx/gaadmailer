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
require_once( 'class-imap-todo.php' );



/**
*
*/ 
abstract class imapWatch{
	/*
	*Przchowuje wczytane z bazy skrzynki pocztowe
	*/
	private $mail_boxes_data;
	private $mail_boxes;
	private $triggers;
	private $actions;
	private $todos;


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
		$this->getMailBoxesData();


		/**
		* Pobieram triggery
		*/
		$this->getTriggersFromDB();

		/**
		* Pobieram akcje
		*/
		$this->getActionsFromDB();

		/**
		* Pobieram akcje
		*/
		$this->connect();

		/*
		* Tworzenie listy zadan do wykonania przez workera 
		*/
		$this->todos = new imapToDo();
		

		return $this;		
	}
	
	/**
	* Łączy się ze zdefiniowanymi skrzynkami
	*/
	function connect(){
		foreach ($this->mail_boxes_data as $key => $value) {
			$this->mail_boxes[ $key ] = new imapMailbox( $value, $this );
		}
	}


	/**
	* Pobiera triggery
	*/
	function getTodos(){
		if ( is_null( $this->todos ) ) {
			/*
			* Tworzenie listy zadan do wykonania przez workera 
			*/
			$this->todos = new imapToDo();
		}
		return $this->todos;
	}

	/**
	* Pobiera triggery
	*/
	function getActionsFromDB(){
		$this->actions	= new imapActions();
	}


	/**
	* Pobiera akcje
	*/
	function getActions( ){
		return $this->actions;
	}

	/**
	* Pobiera akcje
	*/
	function getAction( int $action_id ){
		$actions = $this->getActions();
		foreach ($actions->getActions() as $key => $value) {
			if ( (int)$value->getId() == (int)$action_id) {
				return $value;
			}
		}
		return false;
	}


	/**
	* Pobiera triggery z bazy danych
	*/
	function getTriggersFromDB(){
		$this->triggers	= new imapTriggers();		
	}

	/**
	* Pobiera triggery
	*/
	function getTriggers( $id = NULL){

		if ( !is_null( $id ) ) {
			$tr = $this->triggers->getTriggers();
			$r = array();	
			foreach ( $tr as $key => $value) {
				if ( $value->mailbox_id === $id ) {
					array_push( $r, $value );
				}				
			}			
			return $r;
		} else {
			return $this->triggers;	
		}		
	}

	/**
	* Tworzy listę obsugiwanych przez Imap Watcher skrzynek
	*/
	function getMailBoxesData(){		
		global $wpdb;		
		$mailboxes_table_name = IMAP_DB_TABLE_PREFIX . 'mailbox';
		$r = $wpdb->get_results( "SELECT * FROM `" . $mailboxes_table_name . "`", ARRAY_A );
 		$max = count( $r );

		if ( is_array( $r ) && $max > 0) {
			for ($i=0; $i < $max ; $i++) { 
				$this->addMailbox( $r[ $i ] );
			}
		}

		return $this->mail_boxes_data;
	}


	/**
	* Dodaje skrzynkę pocztową do klasy
	*/
	function addMailbox( array $mailbox ){		
		$this->mail_boxes_data[ sanitize_title($mailbox['label'] ) ] = $mailbox;		
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