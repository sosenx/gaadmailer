<?php
namespace imapwatch;
/**
*
*/

class triggers {
	private $triggers;

	/**
	*
	*/
	function __construct(){		
		if ( is_null($this->triggers) ) {
			$this->getTriggers();
		}
		return $this;
	}

	/**
	*
	*/
	function getTriggers(){		
		return $this;
	}
	
}


?>