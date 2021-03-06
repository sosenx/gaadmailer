<?php
namespace imapwatch\db;
/**
*
*/
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class createTable {

	/**
	* Tworzy tabele mailbox
	*/
	public static function mailbox(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'mailbox';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `label` varchar(50) NOT NULL,
		  `host` varchar(200) NOT NULL,
		  `login` varchar(200) NOT NULL,
		  `pass` varchar(200) NOT NULL,		  
		  `active` TINYINT(1) NOT NULL DEFAULT '1',		  
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}


	/**
	* Tworzy tabele triggers
	*/
	public static function triggers(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'triggers';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `mailbox_id` mediumint(9) NOT NULL,
		  `label` varchar(50) NOT NULL,
		  `action_id` varchar(200) NOT NULL,
		  `json` text NOT NULL,
		  `active` TINYINT(1) NOT NULL DEFAULT '1',
		  
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}


	/**
	* Tworzy tabele actions
	*/
	public static function actions(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'actions';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `label` varchar(50) NOT NULL,		  
		  `json` text NOT NULL,
		  
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}



	/**
	* Tworzy tabele log
	*/
	public static function log(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'log';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `log` text NOT NULL,		  
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}


	/**
	* Tworzy tabele devlog
	*/
	public static function devlog(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'devlog';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `log` text NOT NULL,		  
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}

	/**
	* Tworzy tabele todo
	*/
	public static function todo(){
		global $wpdb;	
		$table_name = IMAP_DB_TABLE_PREFIX . 'todo';		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `mailbox_id` mediumint(9) NOT NULL,		  
		  `email_id` mediumint(9) NOT NULL,
		  `action_id` mediumint(9) NOT NULL,
		  `action` text NOT NULL,
		  `header` text NOT NULL,
		  `textPlain` text NULL,
		  `textHtml` text NOT NULL,
		  `status` varchar(50) NOT NULL DEFAULT 'added',
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		return dbDelta( $sql );
	}


	/**
	*
	*/
	function __construct(){	
		return $this;
	}
	
}


?>