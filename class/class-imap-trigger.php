<?php
namespace imapwatch;
/**
*
*/


class imapTrigger {
	public $slug;

	/**
	*
	*/
	function __construct( array $input ){

		$this->setSlug( $input[ 'label' ] );
		$this->setRules( json_decode( $input[ 'json' ], true ) );
		return $input;
	}


	/**
	* Ustawia slug triggera
	*
	* @param string $slug slug triggera
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
	function setRules( array $input ){

		if ( is_array( $input ) ) {
			$this->rules = $input[ 'rules' ];			
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