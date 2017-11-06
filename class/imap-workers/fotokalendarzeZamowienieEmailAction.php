<?php
namespace imapwatch\workers;

/**
* Odczytuje dane z tresci emaila i generuje wiadomość z iformacjami o dalszych krokach: zaplata, podanie danych do wysylki
*/
class fotokalendarzeZamowienieEmailAction extends \imapwatch\imapTaskWorker{

public $r = 3;
	/**
	*
	*/
	function __construct( array $input ){
		parent::__construct( $input );
		$this->addRules();

		
		$this->addOperation( array( $this, "parseTemplate") );
		$this->addOperation( array($this, "step3"), array("seven", "eight")  );

			return $this;
	}

	/**
	* Modyfiakacja zasad
	*/
	function addRules( ){					
		//$this->addRule( 'header_rules', 'from', array( 'regExp' => '/(.*)/') );	
	}

	/**
	*
	*/
	function step2( $a, $b ){			
		$this->test = ' mamuta!';

	}

	/**
	*
	*/
	function step3( $a, $b ){			
		$d=$this->test;
$r=1;
	}



}