<?php
namespace imapwatch;

/**
*
*/
class prestart{
	
	/**
	*
	*/
	function __construct(){	
		return $this;
	}
	

/**
CREATE TABLE `mailbox` (
  `id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL,
  `host` varchar(200) NOT NULL,
  `login` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `mailbox`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mailbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
*/



	/**
	* Wykonuje testy przed startem systemu
	*/
	public static function test( string $mode = 'db_tables' ) : bool {	

		if ($mode == 'db_tables') {
			return prestart::test_db_tables( IMAP_DB_TABLE_AUTOCREATE_ON_TEST );
		}

		return false;
	}

	/**
	* Sprawdza czy istnieje tabela w bazie danych o podanej nazwie
	*/
	public static function db_table_exists( string $name ) : bool {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM information_schema.tables WHERE table_name = '" . $name . "'" );
		return empty( $results ) ? false : true;
	}

	/**
	*
	*/
	public static function test_db_tables( bool $create_on_false = NULL ) : bool {	
		
		$tables = array( 
			IMAP_DB_TABLE_PREFIX . 'mailbox',
			IMAP_DB_TABLE_PREFIX . 'triggers',
			IMAP_DB_TABLE_PREFIX . 'actions',
			IMAP_DB_TABLE_PREFIX . 'log',
			IMAP_DB_TABLE_PREFIX . 'devlog',
			IMAP_DB_TABLE_PREFIX . 'todo',
		);
		$test_ok = true;

		$max = count ( $tables );
		for ($i=0; $i < $max; $i++) { 
			if ( !prestart::db_table_exists( $tables[ $i ] ) ) {
				$test_ok = false;

				if ( filter_var($create_on_false, FILTER_VALIDATE_BOOLEAN)) {
					$create_table_method_name = str_replace ( IMAP_DB_TABLE_PREFIX, '', $tables[ $i ] );
					if ( method_exists( '\\imapwatch\\db\\createTable', $create_table_method_name ) ) {
						db\createTable::$create_table_method_name();
					} else break;					
				} else break;
			}
		}

		log::write( array(
			'test' => 'test', )
			);
		return $test_ok;
		
	}
	


}


?>