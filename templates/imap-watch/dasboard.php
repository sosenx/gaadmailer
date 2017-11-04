<div id="dasboard">
  
  <?php _e( 'gaad dasboard imap watch', 'gaad-mailer' ) ?>
  <?php

  $imapw = new imapwatch\imapReader();
$R=1;
/*
$mailbox = new PhpImap\Mailbox('{imap.dpoczta.pl:993/imap/ssl}INBOX', 'zamowienia@fotokalendarze2018.pl', '7lho848EcaP4', __DIR__);

// Read all messaged into an array:
$mailsIds = $mailbox->searchMailbox('ALL');
// Get the first message and save its attachment(s) to disk:

$patt = array(
	'email' => '/<td>Email:.*href="mailto:(.*)"/m',
	'amount' => '/Amount total:<\/td><td>zł (\d+.?\d{0,})</m'
);
$matches = array();

$max = count ( $mailsIds );
for( $i=0; $i<$max; $i++){
	$mail = $mailbox->getMail($mailsIds[$i]);
	$mailbox->markMailAsUnread($mailsIds[$i]);
	$matches[$mailsIds[$i]] = array();
	foreach ($patt as $key => $value) {
		$matches[$mailsIds[$i]][$key] = array();
		preg_match_all( $value, $mail->textHtml, $matches[$mailsIds[$i]][$key] );			
	}

	echo '<div>from: '.$matches[$mailsIds[$i]]['email'][1][0].',  amount: '.$matches[$mailsIds[$i]]['amount'][1][0].'</div>';
	echo "<hr>";
	
}

$imap = imap_open("{imap.dpoczta.pl:993/imap/ssl}INBOX",  'zamowienia@fotokalendarze2018.pl', '7lho848EcaP4' );
*/
  ?>
</div>