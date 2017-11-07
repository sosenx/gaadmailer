<?php 

/**
 * Template Name: GAAD Mailer IMAP Watch
 
 * 
 */


?>
<?php get_header(); 

if ( isset( $_GET[ IMAP_GET_VAR_NAME ] ) ) {
  switch( $_GET[ IMAP_GET_VAR_NAME ] ){
    case IMAP_GET_READER_CODE : 
      $imap_reader = new imapwatch\imapReader();
    break;
    case IMAP_GET_WORKER_CODE : 

      $imap_worker_config = array(
        'workers' => array( 
          'wyslij-email-z-platnosciami' => '/imap-workers/fotokalendarzeZamowienieEmailAction.php',
          'wyslij-email-do-gada' => '/imap-workers/testEmailAction.php',
          'test2-email-action' => '/imap-workers/test2EmailAction.php'
        )
      );
      $imap_worker = new imapwatch\imapWorker( $imap_worker_config );
      
    break;
  }
}

?>
<div id="imap-watch-app">
    
    
    <div class="container">
     
      <div class="row">
        <div class="col-12">GAAD Mailer</div>
      </div>
        
      <div class="row">
        <div class="col-4">
          <ul>
            <li><router-link to="/"><?php _e( 'Dashboard', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/templates"><?php _e( 'EMails templates', 'gaad-mailer' ); ?></router-link></li>           
          </ul>
        </div>  
        
        <div class="col-8">
          <router-view></router-view>          
        </div>
        
      </div>      
    </div>
    
  
</div> 


<?php get_footer(); ?>