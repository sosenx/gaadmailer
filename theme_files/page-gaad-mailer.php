<?php 

/**
 * Template Name: GAAD Mailer App
 
 * 
 */


?>
<?php get_header(); ?>
<div id="gmailer-app">
    
    
    <div class="container">
     
      <div class="row">
        <div class="col-12">GAAD Mailer</div>
      </div>
        
      <div class="row">
        <div class="col-4">
          <ul>
            <li><router-link :name="test23" to="/"><?php _e( 'Dashboard', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/upload-csv"><?php _e( 'Upload CSV', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/filters"><?php _e( 'Filters', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/templates"><?php _e( 'Mail templates', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/archives"><?php _e( 'Archives', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/outbox"><?php _e( 'Outbox', 'gaad-mailer' ); ?></router-link></li>
            <li><router-link to="/send"><?php _e( 'Send subscription', 'gaad-mailer' ); ?></router-link></li>            
          </ul>
        </div>  
        
        <div class="col-8">
          <router-view></router-view>          
        </div>
        
      </div>      
    </div>
    
  
</div> 


<?php get_footer(); ?>