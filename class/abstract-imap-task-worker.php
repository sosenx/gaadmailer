<?php
namespace imapwatch;

/**
* Szablon dla klas wykonujacych konkretne zadania pochodzace z listy todo
* Klasa ta musi być samodzielna ale dobrzy by byo jakby bazowaa na tej abstrakcji
*/
abstract class imapTaskWorker {
	
	private $mailbox_id;
	private $email_id;
	private $config;
	private $operations = array();
	/*
	* Konfiguracja przekazana z akcji
	*/
	private $actionConfig;

	private $header_rules = array(
		'subject' => array(			
			'regExp' => '/(.*)/m',

		)
	);

	private $text_plain_rules = array(); 
	private $html_body_rules = array();

	/*
	* Czy zadanie zostalo juz wykonane
	*/
	private $executed = false;
	/**
	*
	*/
	function __construct( array $input ){
	
		$this->configure( $input );		
		$this->addRules( );
		$this->addActionRules( );

		return $this;
	}

	/**
	*
	*/
	function configure( array $input ){
		$this->config = $input;
		$this->config[ 'header' ] = json_decode( $input[ 'header' ], true );
		$this->config[ 'action' ] = json_decode( $input[ 'action' ], true );
		$this->actionConfig = $this->config[ 'action' ]['config'];
		
		return $this;
	}


	/**
	* Dodaje reguly z obiektu akcji
	*/
	function addActionRules( ){
		$rules_src = array( 'header_rules', 'text_plain_rules', 'html_body_rules' );

		if ( isset( $this->actionConfig['rules'] ) ) {
			foreach ( $this->actionConfig['rules'] as $type => $rules ) {
				if ( in_array( $type, $rules_src) ) {					
					foreach ($rules as $rule_slug => $rule_data) {
						$this->addRule( $type, $rule_slug, $rule_data);
					}
				}	
			}
		}	
	}


	/**
	* Dodaje reguly zbierania danych z naglowka wiadomosci
	*/
	function addRules( ){
		$this->addRule( 'header_rules', 'from', array( 'regExp' => '/(.*)/', 'label' => 'Od') );	
	}

	/**
	* Dodaje regule do listy 
	*
	* @param string $type typ, ktorego dotyczy zasada. Odpowiada wartosci klasy
	* @param string $slug indeks pod ktorym przechowywana jest 
	* @param array $rule_data 
	*/
	function addRule( string $type, string $slug, array $rule_data ){
		$this->{$type}[ $slug ] = $rule_data;
	}



	/**
	* Dodaje operacje wykonywane na zbiorach danych po skanowaniu wiadomosci
	*/
	function addOperation( array $function, array $param_arr = NULL ){
		$this->operations[] = array( $function, $param_arr);
	}


	/**
	*
	*/
	function isExecuted( ){
		return $this->executed;
	}

	/**
	*
	*/
	function markExecuted( ){
		$this->executed = true;
	}


	
	/**
	*
	*/
	function executeTask( ){			
		if ( !$this->isExecuted() ) {
			
			foreach ($this->operations as $key => $value) {
				call_user_func_array( $value[0], $value[1]);
			}	
			$this->markExecuted();
		}
		
		

	}
}


?>