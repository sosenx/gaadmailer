<?php
namespace imapwatch;
/**
*
*/
require_once( 'class-imap-task.php' );
require_once( 'abstract-imap-task-worker.php' );

class imapWorker {
	
	private $todos;
	public $config;
	/**
	*
	*/
	function __construct( array $config ){
		$this->configure( $config );
		$this->getTodos();
		
		$this->process();
		return $this;
	}

	/**
	* Tworzy listę obsugiwanych przez Imap Watcher skrzynek
	*/
	function getTodos(){		
		global $wpdb;		
		$table_name = IMAP_DB_TABLE_PREFIX . 'todo';
		$r = $wpdb->get_results( "SELECT * FROM `" . $table_name . "` WHERE `status` LIKE 'added' LIMIT 0, 10", ARRAY_A );
 		$max = count( $r );

		if ( is_array( $r ) && $max > 0) {
			for ($i=0; $i < $max ; $i++) { 
				$this->todos[] = new imapTask( $r[ $i ] );
			}
		}
	}	



	/**
	* Zapisuje konfiguracje i uruchamia inne metody komplementujace klasę
	*/
	function process( ){
		if ( is_null( $this->todos ) ) {
			return false;
		}
		
		foreach ( $this->todos as $key => $task) {
			$taskExecutor = $task->getPhpClass();
			$taskExecutor->executeTask();
			}
		
	}

	/**
	* Zapisuje konfiguracje i uruchamia inne metody komplementujace klasę
	*/
	function configure( array $config_array ){
		$this->config = $config_array;
		$this->addRegisteredWorkers();
	}


	/**
	* Tworzy listę obsugiwanych przez Imap Watcher skrzynek
	*/
	function addRegisteredWorkers( ){
		$workers = $this->config[ 'workers' ];
		foreach ($workers as $i => $workerClassFile) {

			try {				
				require_once( GMAILER_DIR . '/class/'. $workerClassFile );
			} catch (Exception $e) {
			    echo 'Worker file is missing: \''.$workerClassFile.'\'',  $e->getMessage(), "\n";
			}
		}
	}
}


?>