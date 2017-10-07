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
        <div class="col-4">
          <ul>
            <li><router-link to="/"><?php echo __('Dashboard', 'gmailer'); ?></router-link></li>
            <li><router-link to="/bar">Go to Bar</router-link></li>
          </ul>
        </div>  
        
        <div class="col-8">
          <router-view></router-view>          
        </div>
        
      </div>      
    </div>
    
  
</div> 


<?php get_footer(); ?>