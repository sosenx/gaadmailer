<?php 

   
class wcm_actions {
  
  
  public static function localisation(){
    $languages_dir = dirname( dirname( plugin_basename(__FILE__))) .'/languages/';
    load_plugin_textdomain('gaad-mailer', false, $languages_dir );    
    return true;
  }
  
  /**
  * Copy a file, or recursively copy a folder and its contents
  * @author      Aidan Lister <aidan@php.net>
  * @version     1.0.1
  * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
  * @param       string   $source    Source path
  * @param       string   $dest      Destination path
  * @param       int      $permissions New folder creation permissions
  * @return      bool     Returns true on success, false on failure
  */
  public static function xcopy($source, $dest, $permissions = 0755) {
      // Check for symlinks
      if (is_link($source)) {
          return symlink(readlink($source), $dest);
      }

      // Simple copy for a file
      if (is_file($source)) {
          return copy($source, $dest);
      }

      // Make destination directory
      if (!is_dir($dest)) {
          mkdir($dest, $permissions);
      }

      // Loop through the folder
      $dir = dir($source);
      while (false !== $entry = $dir->read()) {
          // Skip pointers
          if ($entry == '.' || $entry == '..') {
              continue;
          }

          // Deep copy directories
          wcm_actions::xcopy("$source/$entry", "$dest/$entry", $permissions);
      }

      // Clean up
      $dir->close();
      return true;
  }

  
  /*
  * Tworzy niezbędne pluginowi pliki i katalogi w bieżącym szablonie
  * Trzeba dopisac akcje zmiany parametru wcm_files_updated kiedy zmienia sie szablon z panelu!!!!!!!!!!!!!!!!
  */
  public static function update_theme_files( ){
   
    $wcm_files_updated = filter_var( get_option( 'wcm_files_updated', 'false' ), FILTER_VALIDATE_BOOLEAN);
    if( !$wcm_files_updated || GMAILER_FORCE_FILES_UPDATED ){
      if( wcm_actions::xcopy( GMAILER_THEME_FILES_DIR, get_template_directory() ) ){
        update_option( 'wcm_files_updated', 'true', '', 'yes' );
        
        return true;
      }
      
    }
    update_option( 'wcm_files_updated', 'false', '', 'yes' );  
    return false;
    
  }
    
  public static function login_user( $username = NULL, $userpwd = NULL ){
    if( $_POST ){
   
      $username = trim($_POST[ 'username' ]);
      $userpwd = trim($_POST[ 'userpwd' ]);
      $check = wp_authenticate_username_password( NULL, $username, $userpwd );

     /* if( $check instanceof WP_Error ){
        //obsługa błędów
        
        ?><script id="login-wp-error" type="application/javascript">
          var login_wp_error = <?php echo json_encode( WP_Error ); ?>;          
          </script><?php

        
      } else*/if( $check instanceof WP_User ){
        $creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $userpwd;
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );
        wp_set_current_user($user->ID);
      }      
    }    
  }
  
  public static function put_templates( $dir ){
    global $post;
    $tpl_dir = opendir( $dir = str_replace( '\\', '/', $dir ) );
    $post_slug = $post->post_name; 

    while ( $f = readdir($tpl_dir) ){
      $id = array();
      preg_match('/(.*)[\.]{1}.*$/', $f, $id);
      $id = basename( $dir ) . '-' . empty( $id ) ? $f : $id[ 1 ];
     
      $template = $dir . '/'.$f;      
      if( is_file( $template ) ){
        $template_id = $post_slug . '-' . str_replace( '-php', '', sanitize_title( $id ) );
        ?><script type="template/javascript" id="<?php echo $template_id; ?>"><?php require_once( $template ); ?></script><?php
      }
      
    }
  }
  
  /**
  * Generates javascript/template for common components
  */
  public static function app_templates(){
   global $post;
   $post_slug = $post->post_name; 
   
   //common components templates
   wcm_actions::put_templates( WCM_PLUGIN_APP_TEMPLATES_DIR );
   //app templates
   wcm_actions::put_templates( WCM_PLUGIN_APP_TEMPLATES_DIR . '/' . $post_slug );
  }
  
  
  public static function common_styles(){
    global $post;
    $post_slug = $post->post_name;

    if(  GMAILER_ENV === 'DEV' ){
      wp_enqueue_style( 'gmailer-app-css', WCM_PLUGIN_URL . '/css/gmailer-app.css', false, false);
      wp_enqueue_style( 'gmailer-upload-csv-css', WCM_PLUGIN_URL . '/css/upload-csv.css', false, false);
      
    }
    
    if(  GMAILER_ENV === 'DIST' ){
      wp_enqueue_style( 'gmailer-upload-csv-css', WCM_PLUGIN_URL . '/dist/css/'.$post_slug.'/app.min.css', false, false);
    }
     
    
  }
  


  public static function app_scripts(){
    global $post;
    $post_slug = $post->post_name;



    if(  GMAILER_ENV === 'DEV' ){

    if ($post_slug == 'imap-watch') {
      wp_enqueue_script( 'imap-watch-dashboard-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/dashboard.js', array( 'vue-js' ), false, true );
      wp_enqueue_script( 'imap-watch-templates-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/templates.js', array( 'vue-js' ), false, true );
      
      wp_enqueue_script( 'imap-watch-app-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/imap-watch-app.js', 
          array( 
            'vue-js',
            'imap-watch-dashboard-js'
          ),
          false, true );

         

    }

      // main feture app  (legacy ideology of plugin features base) 
      if ($post_slug == 'mailer') {

        wp_enqueue_script( 'gmailer-dashboard-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/dashboard.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-filters-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/filters.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-templates-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/templates.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-archives-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/archives.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-send-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/send.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-outbox-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/outbox.js', array( 'vue-js' ), false, true );
        wp_enqueue_script( 'gmailer-upload-csv-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/upload-csv.js', array( 'vue-js' ), false, true );

        wp_enqueue_script( 'gmailer-app-js', WCM_PLUGIN_URL . '/js/'.$post_slug.'/gmailer-app.js', 
          array( 
            'vue-js',
            'gmailer-dashboard-js'
          ),
          false, true );

        } 
      }

      

    
    if(  GMAILER_ENV === 'DIST' ){
      wp_enqueue_script( 'gmailer-app-dist-js', WCM_PLUGIN_URL . '/dist/js/app.min.js', array( 'vue-js' ), false, true );    
    } 
    
    add_action('wp_head', 'wcm_actions::app_templates', 9);
  }
  
  public static function common_scripts(){
     
  //   wp_enqueue_script( 'gmailer-app-js', WCM_PLUGIN_URL . '/js/gmailer-app.js', array( 'vue-js' ), false, true );
  }
  
  public static function test(){
    echo "test ok";
    die();
  }
  
  /*
  * Skrypty główne wczytywane na każdej posdtronie
  */
  public static function core_scripts(){
    
    wp_enqueue_script( 'tether-js', WCM_PLUGIN_URL . '/node_modules/tether/dist/js/tether.min.js', false, false, null );
    wp_enqueue_script( 'vue-js', 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null );
    wp_enqueue_script( 'vue-router-js', 'https://unpkg.com/vue-router/dist/vue-router.js', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'vue-x-js', 'https://unpkg.com/vuex', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'tether-js', 'jquery' ), false, null );
   // wp_enqueue_script( 'jquery-ui-js', WCM_PLUGIN_URL . '/vendor/jquery-ui-1.12.1.custom/jquery-ui.js#defer', array( 'jquery' ), false, null );
   // wp_enqueue_script( 'jquery-scroll-to-js', WCM_PLUGIN_URL . '/vendor/jquery.scrollTo/jquery.scrollTo.min.js#defer', array( 'jquery' ), false, null );
    
    //wp_enqueue_script( 'tinymce-js', WCM_PLUGIN_URL . '/vendor/tinymce/tinymce.min.js?apiKey=13pwfa6nx81qfgrzdpr67td5atfu38w6mv5dfh81tja7wl69', false, false, null );
    
    
    
  }
    
  
  /*
  * Style główne wczytywane na każdej posdtronie
  */
  public static function core_styles(){
    
    wp_enqueue_style( 'bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, false);
    wp_enqueue_style( 'tether-css', WCM_PLUGIN_URL . '/node_modules/tether/dist/css/tether.min.css', false, false);
    //wp_enqueue_style( 'fonts-css', WCM_PLUGIN_URL . '/css/fonts.css', false, false);
    
    
    
    
  }
  
  
  
  public function _constructor(){
    
    return $this;
  }
  
  
}



?>