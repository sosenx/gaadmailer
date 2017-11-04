<?php
namespace imapwatch;
/**
*
*/
require_once( 'config-imap.php' );
require_once( 'class-create-table.php' );
require_once( 'class-imap-prestart.php' );
require_once( 'class-imap-reader.php' );


 
abstract class imapWatch{
	/*
	*Przchowuje wczytane z bazy skrzynki pocztowe
	*/
	private $mail_boxes;




	/**
	*
	*/
	function __construct(){
		if( IMAP_WATCH_ENV === 'PRESTART'){
			$this->checkDB();
		}
		return $this;
	}
	
	/**
	*
	*/
	function checkDB(){
		prestart::test('db_tables');
		

		return $this;
	}
	
}


?>