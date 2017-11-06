<?php
namespace imapwatch\workers;

/**
* Odczytuje dane z tresci emaila i generuje wiadomość z iformacjami o dalszych krokach: zaplata, podanie danych do wysylki
*/
class fotokalendarzeZamowienieEmailAction extends \imapwatch\imapTaskWorker{


	/**
	*
	*/
	function __construct( array $input ){
		parent::__construct( $input );
		

		return $this;
	}

	/**
	*
	*/
	function step1( $a, $b ){			
		$d=1;

	}


	/**
	*
	*/
	function executeTask( ){			
		call_user_func_array(array($this, "step1"), array("three", "four"));

	}

}