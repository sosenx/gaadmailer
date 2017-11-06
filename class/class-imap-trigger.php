<?php
namespace imapwatch;
/**
*
*/


class imapTrigger {
	public $slug;
	public $mailbox_id;
	public $action_id;

	/**
	*
	*/
	function __construct( array $input ){

		$this->setSlug( $input[ 'label' ] );
		$this->setMailboxId( $input[ 'mailbox_id' ] );
		$this->setActionId( $input[ 'action' ] );
		$this->setRules( json_decode( $input[ 'json' ], true ) );
		return $input;
	}


	/**
	* Sprawdza, czy podana wiadomość pasuje do triggera
	*
	* @param object $mailHeader Obiekt z nagówkiem emaila
	*/
	function check( $mailHeader ){
		$comp = array();
		$rules = $this->getRules();
		foreach ( $rules as $key => $rule ) {
			foreach ($mailHeader as $field => $value) {
				if ( $rule['data']['field'] === $field) {
					array_push( $comp, $this->compare( $rule['compare'], $rule['data']['value'], $value ) );
				}				
			}			
		}

		/*
		* Po przejrzeniu zasad triggera sprawdzanie czy wszystkie testy day wynik pozytywny
		* Tutaj będzie można dodac warunek dla zasad: spenione wszystiue, speniona przynbajmniej jedna etc
		*/
		$testOK = true;
		foreach ($comp as $key => $value) {
			if ( !filter_var( $value, FILTER_VALIDATE_BOOLEAN) ) {				
				return false;
			}
		}

		return $testOK;		
	}

 
	/**
	* Ustawia mailboxId triggera
	*
	* @param string $compare Sposob porownania
	*/
	function compare( string $compare, string $str1, string $str2 ){
		$regExp = $compare[0] === '/';
		if ( $regExp ) {
			# code...
		} else {
			switch ( $compare) {
				case '==':
					return $str1 == $str2;
					break;
				case '*==':
					$matches = array();
					preg_match_all('/'.$str1.'/', $str2, $matches);
					if ( empty( $matches[0] ) ) {
						return false;
					}
					return !is_null( $matches[0][0] ) && $matches[0][0] == $str1 ? true : false;										
					break;	
			}
		}
		
	}

	/**
	* Ustawia mailboxId triggera
	*
	* @param string $slug Mailbox Id triggera
	*/
	function setMailboxId( string $mailbox_id ){
		$this->mailbox_id = $mailbox_id;
	}
	
	/**
	* Ustawia mailboxId triggera
	*
	* @param string $action_id action_id triggera
	*/
	function setActionId( string $action_id ){
		$this->action_id = $action_id;
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