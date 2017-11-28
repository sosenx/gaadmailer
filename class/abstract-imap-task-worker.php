<?php
namespace imapwatch;

/**
* Szablon dla klas wykonujacych konkretne zadania pochodzace z listy todo
* Klasa ta musi być samodzielna ale dobrzy by byo jakby bazowaa na tej abstrakcji
*/


abstract class imapTaskWorker {
	
/*
* 
*/
	private $respondTo;
	private $mailbox_id;
	private $email_id;
	private $config;
	/*
	*Skrot do hedera wiadomosci
	*/
	private $headerConfig;
	/*
	*Skrot do plain text
	*/
	private $textPlain;
	/*
	*Skrot do html body
	*/
	private $htmlBody;

	private $parsedResponseTemplate;


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
	* Przechowuje dane zebrane za pomoca regul
	*/
	public $message_data = array(
		'header' => array(),
		'text_plain' => array(),
		'html_body' => array()
	);

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
		$this->getMessageData();

		return $this;
	}




	/**
	* Wysyą wiadomość ze skompletowanych danych
	*/
	function sendResponse( ){
		$mail = new \PHPMailer\PHPMailer\PHPMailer();

		$mail->setFrom('from@example.com', 'Your Name');
		$mail->addAddress( $this->respondTo );
		$mail->Subject  = 'Odpowiedz na maila z imapwatch';
		$mail->Body     = $this->parsedResponseTemplate;
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
/**/
		$mail->SMTPDebug = 2; //Alternative to above constant
		$mail->IsSMTP();
	    $mail->Host = "smtp.dpoczta.pl";
	    $mail->Port = 25;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = false;		
	    $mail->Username = "tes@antykimarki.pl"; 
	    $mail->Password = "r52be2Sc3bEX"; 
	    $mail->From = "tes@antykimarki.pl";

		if(!$mail->send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
		  echo 'Message has been sent.';
		  $this->setStatus('response sent: ' . $this->respondTo);
		}
		$a= 1;

	}
/**
	* Przeglada kolejne czescie wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function setStatus( string $status ){
		global $wpdb;
		$r = $wpdb->update( IMAP_DB_TABLE_PREFIX . 'todo', array('status' => $status ), array( 'id' => $this->config['id']) );
		$s=1;
	}

	/**
	* Przeglada kolejne czescie wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function setRespondTo( string $email_adress ){
		$this->respondTo = $email_adress;
	}



/**
	* Przeglada kolejne czescie wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function getMessageData(  ){
		$this->getHeaderData();
		$this->getTextPlainData();
		$this->getHtmlBodyData();
	}

	/**
	* Przeglada kolejne naglowek wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function getHeaderData(  ){
		$r = array();
		foreach ($this->header_rules as $rule_slug => $rule_data ) {
			$rule_matches = array();
			foreach ( $this->headerConfig as $index => $value) {
				if ( $index === $rule_slug) {
					$matches = array();					
					if ( preg_match( $rule_data['regExp'], $value, $matches ) ) {
						if ( !$this::is_empty( $matches ) ) {
							$r[ $rule_slug ] = $matches;
						}	
						
					}
				}
			}
			$r[ $rule_slug ][] = $rule_matches;
		}
		$this->message_data[ 'header' ] = $r;
	}

	/**
	* Przeglada czesc tekstowa wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function getTextPlainData(  ){
		/*
		* Escape, no data
		*/
		if ( is_null( $this->textPlain ) ) {
			return false;
		}

		$r = array();
		foreach ($this->text_plain_rules as $rule_slug => $rule_data ) {
			$matches = array();
			@preg_match_all( $rule_data['regExp'], $this->textPlain, $matches);
			if ( !$this::is_empty( $matches ) ) {
				$r[ $rule_slug ] = $matches;
			}			
		}
		$this->message_data[ 'text_plain' ] = $r;
	}

	/**
	* Przeglada czesc htmlowa wiadomosci i szuka za pomoca regul pasujkacych elementow
	*/
	function getHtmlBodyData(  ){
		/*
		* Escape, no data
		*/
		if ( is_null( $this->htmlBody ) || ( is_string( $this->htmlBody ) && strlen( $this->htmlBody ) == 0 ) ) {
			return false;
		}

		$r = array();
		foreach ($this->html_body_rules as $rule_slug => $rule_data ) {
			$matches = array();
			$regExp = $rule_data['regExp'];
			$htmlBody = /*$this->textPlain . */$this->htmlBody;
			preg_match_all( $regExp, $htmlBody, $matches);
			if ( !$this::is_empty( $matches ) ) {
				$r[ $rule_slug ] = $matches[1];
			}
		}
		$this->message_data[ 'html_body' ] = $r;
	}

	/**
	* Sprawdza wynik preg_match_all, zwraca false jezeli zawiera puste tablice
	*/
	function is_empty( array $matches ){
		if ( !empty( $matches ) ) {
			$r = false;
			foreach ($matches as $key => $value) {
				if ( empty( $value )) {
					$r = true;
				} else $r = false;
			}			
			return $r;
		}
		return true;
	}


	/**
	* przeprowadza niezbedna do dzialania klasy konfiguracje i translacje zmiennych
	*/
	function configure( array $input ){
		$this->config = $input;
		$this->headerConfig = json_decode( $input[ 'header' ], true );
		$this->config[ 'action' ] = json_decode( $input[ 'action' ], true );
		$this->actionConfig = $this->config[ 'action' ]['config'];
		$this->textPlain = $input['textPlain'];
		$this->htmlBody = $input['textHtml'];

		if ( is_null( $this->htmlBody ) ) {
			$this->htmlBody = $this->textPlain;
		}

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
	* parseTemplateCb
	*/
	public static function parseTemplateCallback( string $buffer ){
		return $buffer;
	}

	/**
	* parseTemplate
	*/
	function parseTemplate( array $param_arr = NULL ){
		$tpl_dir = IMAP_EMAILS_TEMPLATES_DIR . $this->actionConfig['template']['file'] . '/';		
		$tpl_index = $tpl_dir . 'index.php';
		if ( is_file( $tpl_index ) ) {

			ob_start("\imapwatch\imapTaskWorker::parseTemplateCallback");
			include( $tpl_index );				
			$this->parsedResponseTemplate = ob_get_flush();
		}
		
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
	* Wykonuje zarejestrowane operacje
	*/
	function executeTask( ){			
		if ( !$this->isExecuted() ) {
			
			foreach ($this->operations as $key => $value) {
				$args = !is_null( $value[1] ) ? $value[1] : array();
				call_user_func_array( $value[0], $args );
			}	
			$this->markExecuted();
		}
	}

	/**
	* Funkcja ma zadanie wybrac i zwrocic adres email na który ma zostać wysana odpowiedź.php
	*
	* Adres email powinien zostać pobrany z tresci za pomocą Rule (konfiguracja zasad)
	* Zakądamy, że wiadomości (triggery) będą miay stalą, przewidywalną tresc, więc przy
	* odpowiednio skonfigurowanych zasadach dame bedą zawsze w tym samym miejscu tablicy message_data
	*/
	function acquire_reciever( ){					
		return IMAP_ADMIN_EMAIL;
	}
}


?>