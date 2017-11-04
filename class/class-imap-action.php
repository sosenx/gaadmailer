<?php
namespace imapwatch;
/**
*
*/


class imapAction {
	public $slug;
	private $config;
	/**
	*
	*/
	function __construct( array $input ){

		$this->setSlug( $input[ 'label' ] );
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
	* Zwraca slug triggera
	*/
	function getSlug(){
		return $this->slug;
	}


	/**
	*
	*/
	function setConfig( array $input ){

		if ( is_array( $input ) ) {
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