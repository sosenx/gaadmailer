<?php
namespace imapwatch; 

if ( !defined( 'IMAP_ROOT_DIR')){
	/**
	* Katalog glowny
	*/
	define( 'IMAP_ROOT_DIR', \GMAILER_DIR . '/class/' );
}

if ( !defined( 'IMAP_WATCH_ENV')){
	/**
	* Tryby dzialania aplikacji:
		- PRESTART - sprawdza i tworzy tabele mySQL
		- DEV - development - tworzy dodadkowe logi
		- PROD - tryb produkcyjny
	*/
	define( 'IMAP_WATCH_ENV', 'PRESTART' );
}
 

if ( !defined( 'IMAP_DB_TABLE_PREFIX')){
	/**
	* Przedrostek nazw tabel mysql
	*/
	define( 'IMAP_DB_TABLE_PREFIX', 'imw_' );
}


if ( !defined( 'IMAP_DB_TABLE_AUTOCREATE_ON_TEST')){
	/**
	* Przedrostek nazw tabel mysql
	*/
	define( 'IMAP_DB_TABLE_AUTOCREATE_ON_TEST', true );
}



?>