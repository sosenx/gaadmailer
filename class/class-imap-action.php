<?php
namespace imapwatch;
/**
*
*/


class imapAction {
	private $id;
	public $slug;
	private $config;
	private $phpClass;
	/**
	*
	*/
	function __construct( array $input ){

		$this->setSlug( $input[ 'label' ] );
		$this->id = $input[ 'id' ];
		$this->setConfig( json_decode( $input[ 'json' ], true ) );
		return $input; 
	}


	/**
	* Ustawia slug akcji
	*
	* @param string $slug slug akcji
	*/
	function setSlug( string $slug ){
		$this->slug = sanitize_title( $slug );
	}

	/**
	* Zwraca id akcji
	*/
	function toJSON(){
		$data = array(
			'id' => $this->getId(),
			'php-class' => $this->getPhpClass(),
			'slug' => $this->getSlug(),
			'config' => $this->config
		);
		return json_encode( $data );
	}

	/**
	* Zwraca nazwe klasy obslugujacej akcję
	*/
	function getPhpClass(){
		return $this->phpClass;
	}	

	/**
	* Zwraca id akcji
	*/
	function getId(){
		return $this->id;
	}

	/**
	* Zwraca slug triggera
	*/
	function getSlug(){
		return $this->slug;
	}


	/**
	* Ustawie i waliduje zmienne konfiguracyjne klasy
	*/
	function setConfig( array $input ){

		if ( is_array( $input ) ) {
			$phpClass = !isset( $input['php-class'] ) ? IMAP_DEFAULT_ACTION_CLASS : $input['php-class'];
			$this->phpClass = $phpClass;			
			$this->config = $input[ 'config' ];			
		}
	}

	/**
	* Zwraca warunki
	*/
	function getRules(){
		return $this->rules;
	}

	/**
	* Zwraca konkretny warunek warunki
	*/
	function getRule( int $index ){
		return !is_null( $this->rules[ $index ] ) ? $this->rules[ $index ] : false;
	}
	
}


?>