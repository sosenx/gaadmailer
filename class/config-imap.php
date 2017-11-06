<?php
namespace imapwatch; 



if ( !defined( 'IMAP_ROOT_DIR')){
	/**
	* Katalog glowny
	*/
	define( 'IMAP_ROOT_DIR', \GMAILER_DIR . '/class/' );
}

if ( !defined( 'IMAP_EMAILS_TEMPLATES_DIR')){
	/**
	* Katalog glowny
	*/
	define( 'IMAP_EMAILS_TEMPLATES_DIR', \WCM_PLUGIN_APP_TEMPLATES_DIR . '/imap-watch/emails/' );
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


if ( !defined( 'IMAP_PROCESS_ONLY_UNSEEN')){
	/**
	* Przetwarzanie tylko nowych ( nieprzeczytanych wiadomości)
	*/
	define( 'IMAP_PROCESS_ONLY_UNSEEN', false );
}


if ( !defined( 'IMAP_MARK_AFTER_PROCESS')){
	/**
	* Nadawanie flagi przeczytana dla przetworzonych wiadomości
	*/
	define( 'IMAP_MARK_AFTER_PROCESS', 'seen' );
}


if ( !defined( 'IMAP_DEFAULT_ACTION_CLASS')){
	/**
	* Nadawanie flagi przeczytana dla przetworzonych wiadomości
	*/
	define( 'IMAP_DEFAULT_ACTION_CLASS', 'def\def\fede' );
}


if ( !defined( 'IMAP_GET_VAR_NAME' ) ){
	/**
	* Nadawanie flagi przeczytana dla przetworzonych wiadomości
	*/
	define( 'IMAP_GET_VAR_NAME', 'gaadImapCode' );
}

if ( !defined( 'IMAP_GET_READER_CODE' ) ){
	/**
	* Nadawanie flagi przeczytana dla przetworzonych wiadomości
	*/
	define( 'IMAP_GET_READER_CODE', '2432-fd32-4s5l-fds2' );
}

if ( !defined( 'IMAP_GET_WORKER_CODE' ) ){
	/**
	* Nadawanie flagi przeczytana dla przetworzonych wiadomości
	*/
	define( 'IMAP_GET_WORKER_CODE', '4s5l-fds2-2432-fd32' );
}






?>