<?php 

/**
 * Template Name: GAAD Mailer IMAP Watch
 
 * 
 */


?>
<?php get_header(); ?>
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