<?php 
class wcm_admin_actions{
  public static function custom_shop_order_column($columns){
      //add columns
      $columns['my-column1'] = __( 'Title1','theme_slug');
      //$columns['my-column2'] = __( 'Title2','theme_slug');

      return $columns;
  }

  
  public static function custom_orders_list_column_content( $column, $order_id ){
      global $post;

      switch ( $column )
      {
          case 'my-column1' :
              $order = new WC_Order( $order_id );
              $order_items = $order->get_items();
              $meta_wanted_keys = array( 'pa_naklad', 'pa_format' );
          
          $aa = array();
          $bb = array();
          foreach( $order_items as $_id => $value){
            $meta_data = $value->get_formatted_meta_data();
            
            
            
            $aa[] = '<h4 style="margin:.2rem 0 .2rem 0;">'.$value->get_id().'#'.$value->get_name() . '</h4>';
           
            foreach( $meta_data as $key => $value){
          
              if( in_array( $value->key, $meta_wanted_keys ) ){
                $bb[] = '<strong style="color:#b9b9b9">'. $value->display_key . '</strong>: ' . strip_tags($value->display_value).' ';
              }
              
            }
            
            echo implode( ' ', $aa) . implode( ',  ', $bb) . '';
            
            
          }
          
              
              break;

          case 'my-column2' :
              $order_id = $the_order->id;
              $myVarTwo = wc_get_order_item_meta( $order_id, '_the_meta_key2', true );
              echo 231453;
              break;
      }
  }
  
  // defer load
  public static function ikreativ_async_scripts($url){
    if ( strpos( $url, '#defer') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#defer', '', $url );
    else
	return str_replace( '#defer', '', $url )."' defer='defer"; 
    }

  
  
  
  
  public static function wcm_create_production_pdf(){
    $path = $_POST['od']['path'];
    $order_id = $_POST['od']['order_id'];
    $order_item_id = $_POST['od']['order_item_id'];
    
    $order = new WC_order ( $order_id );
    $order_status = $order->get_status();
    
    $cuid = get_current_user_id();
    $owner_user_id = get_post_meta( $order->get_id(), '_customer_user', true );
    
    $r = array();
    //echo '<pre>'; echo var_dump( $_POST['od']['path'] ); echo '</pre>';
    $json_file_path = WCM_USER_UPLOADS . '/' . $owner_user_id . '/oi-' . $order_item_id . '/order-item-'. $order_item_id .'.json';
    if( is_file( $json_file_path ) ){ 
      $json_calendar_data = json_decode( file_get_contents( $json_file_path), true );
      
      //echo '<pre> ds: '; echo var_dump($json_calendar_data); echo '</pre>';
      $_POST['calendar'] = $json_calendar_data;
    }
    
      
    $r['path'] = $path;    
    $cal_type = $_POST['od']['cal_type'];
    $creator_class_filename = WCM_PLUGIN_DIR . '/class/' . str_replace( array('_pdf', '_'), array('', '-'), $cal_type ) . '-pdf.php';
    
    $r[ 'cal_type' ] = $cal_type;
    $r[ 'creator_class_filename' ] = $creator_class_filename;
    $r[ 'creator_class_is_file' ] = is_file($creator_class_filename);
    
    
    
    /*
    * Projekt zewnętrzny
    */
    if( filter_var( $json_calendar_data["externalproject"], FILTER_VALIDATE_BOOLEAN ) ){
        $external_fpath = $json_calendar_data["externalprojectpath"];
      
        $r1 = array( 'externalproject' => true );
        $r1['path'] = $external_fpath;
        $r1['file_exists'] = is_file( $external_fpath );
        $r1[ 'cal_type' ] = $cal_type;
    
      wp_send_json( $r1 );
      
    }
    
    if( is_file($creator_class_filename) ){
      require_once( $creator_class_filename );
    }
    
    $cal_pdf = new $cal_type();
    
    $pdf_filename = $cal_pdf->create_pdf( $order_item_id );
    
    $r = $_POST['od'];
    $r['path'] = $pdf_filename;
    $r['file_exists'] = is_file($pdf_filename);
    
    wp_send_json( $r );
  }
  
  public static function wcm_del_order_production_files(){
    echo '<pre> wcm_del_order_production_files: '; echo var_dump( $_POST); echo '</pre>';
    
  }
  
  public static function wcm_brief(){    
    
    $brief_pdf = new wcm_product_brief( $_POST['od']['order_id'], $_POST['od']['order_item_id'] );
    $brief_filename = $brief_pdf->createBrief();
    $r = array(
      'brief' => $brief_filename, 
    );  
    wp_send_json( $r );
  }
  
  /*
  * 
  */
  public static function wcm_get_order_production_files(){
    $order = new WC_order ( $_POST['od']['order_id'] );
    $order_status = $order->get_status();
    $items = $order->get_items();
    $cuid = get_current_user_id();
    $owner_user_id = get_post_meta( $order->get_id(), '_customer_user', true );
    
    $r = array();
    if ( $order->has_status( 'on-hold' ) ) {
        $r['error'] = "Zamówienie nie zostało opłacone. Produkcja nie jest możliwa bez ręcznej zmiany statusu.";
        wp_send_json( $r );
    }
     
    if( !current_user_can( 'administrator' ) ){
        $r['error'] = "Nie posiadasz wystarczających uprawnień by generować pliki produkcyjne.";
        wp_send_json( $r );
    }
   
    if( !empty( $items ) ){
      //echo '<pre>'; echo var_dump($items); echo '</pre>';
      foreach( $items as $item => $item_data ){
        $path = WCM_USER_UPLOADS . '/' . $owner_user_id . '/oi-' . $item_data->get_id(). '/order-item-'. $item_data->get_id() .'.json';
       
        $_meta_data = $item_data->get_meta_data();
        foreach( $_meta_data as $k => $v  ){
          if( $v->key === "calendar_data_id" ){
            $cik = $v->value;
          }
        }
        
        
        $oi_dir = dirname( $path );
        if( !is_dir( $oi_dir ) ){
           mkdir( $oi_dir, 0775); 
           $json_path = WCM_USER_UPLOADS . '/' . $cuid . '/' . $cik . '/'. $cik. '.json';
          if( is_file($json_path ) ){
            $json = json_decode( file_get_contents( $json_path ), JSON_OBJECT_AS_ARRAY );
           
            if( !is_null( $json ) ){
              $imagesList = $json['imagesList'];

              if( is_array( $imagesList ) ){
                foreach( $imagesList as $label => $image ){
                  if( $image != 'false' ){

                    $image_path = str_replace( array( 'https://', $_SERVER['HTTP_HOST']), '', $image );
                    $tmp937 = explode( 'user_uploads',  $image_path);
                    $image_path = WCM_USER_UPLOADS . $tmp937[1];                   
                    //dwie powyzsze linie to łatka po przeniesienieu na dhosting

                    if( is_file( $image_path ) ){
                      $basename = basename( $image_path );
                      $dest_path = $oi_dir .'/'.$basename;
                      if( !is_file( $dest_path ) ){
                        copy( $image_path, $dest_path );                      
                      } 
                      $json['imagesList'][$label] = $dest_path;
                    }                
                  }
                }
              }          
            }    
            $new_json = json_encode( $json, FILTER_FORCE_ARRAY);
            $new_json_file = $oi_dir . '/order-item-' . $item_data->get_id() . '.json';

            file_put_contents( $new_json_file, $new_json ); 
            
          }
          
          
        }
        
        
        
        if( is_file( $path ) ){
            $calendar = json_decode( file_get_contents( $path ), true );
            
            if( !is_null( $calendar ) ){
              $_POST['calendar'] = $calendar;
              $cal_type = str_replace('-', '_', $_POST['calendar']['type']) . '_pdf';
              $creator_class_filename = WCM_PLUGIN_DIR . '/class/' . $_POST['calendar']['type'] . '-pdf.php';

              if( is_file($creator_class_filename) ){
                require_once( $creator_class_filename );
              }
              
              $cal_pdf = new $cal_type();              
              
              $pdf_filename = str_replace( '//', '/', $cal_pdf->create_pdf( $item_data->get_id(), true ) );
              
              
              $file_exists = is_file( $pdf_filename );
              $product_id = $item_data['product_id'];
              $variation_id = $item_data["variation_id"];
              //$product = new WC_Product_Variable ( $variation_id );
              //echo '<pre>'; echo var_dump($product); echo '</pre>';
              //$product = new WC_Product ( $product_id );
              //$brief_pdf_filename = WCM_USER_UPLOADS . '/' . $owner_user_id . '/oi-' . $item_data->get_id() . '/'. $item_data->get_id() .'-'. $_POST['od']['order_id'] .'-brief.pdf';
              
              
              $brief_pdf = new wcm_product_brief( $_POST['od']['order_id'], $item_data->get_id() );
              $brief_filename = $brief_pdf->output_filename;
              //if( is_file( $brief_filename ) ){
                $brief_filename = $brief_pdf->createBrief();  
              //}
              
              $product_data = array(
                'title' => $item_data['name'], 
              );

            
              setlocale( LC_ALL, array('pl_PL', 'pl','Polish_Poland.28592') );
              date_default_timezone_set('Europe/Warsaw');
              
              $r['order_items'][] = array(
                'filectime' => filectime( $pdf_filename ), 
                'filecdate' => iconv("ISO-8859-2","UTF-8",ucfirst(
                    strftime ("%A %d %B %Y - %H:%M", filectime( $pdf_filename ) )
                    )), 
                
                'calendar' => $calendar, 
                'product' => $product_data,
                'path' => str_replace( '/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/','../', $pdf_filename),
                'file_exists' => $file_exists, 
                'cal_type' => $cal_type, 
                'order_item_id' => $item_data->get_id(), 
                'owner_user_id' => $owner_user_id, 
                'order_id' => $_POST['od']['order_id'], 
                //'brief' => $brief_filename
                'brief' => false
              );
              
              if( !$file_exists ){
                $r['calendar'] = $calendar;
              }
              
            }
          
        }          
      }
    }
    

    
    //echo '<pre>'; echo var_dump($cuid, $order_status, $items); echo '</pre>';
    
    wp_send_json( $r );
  }
  
  
  public static function admin_bar_menu(){
    echo '<div id="gaad_wcm_pg"></div>';
  }
  
  public static function wcm_get_order_calendar_data() {
    chdir('/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/');
    $order_id = $_POST['oid'];
    $userId = get_post_meta( $order_id, '_customer_user', true ); 
    $r = array( 'oid' => $order_id, );
    $cdata = get_post_meta( $order_id, 'calendar-data', true );
    $ustr = array('u0104', 'u0106', 'u0118', 'u0141','u0143','u00d3','u015a','u0179','u017b','u0105','u0107','u0119','u0142','u0144','u00f3','u015b','u017a','u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż'); 
    $cdata = str_replace( $ustr, $plstr, $cdata );
    $r['calendar'] = json_decode( $cdata ); 
    $calendar = json_decode( file_get_contents( $r['calendar']->json ) );
    
    $filename = dirname( $r['calendar']->json ) . '/' . $order_id . '-' . $userId . '-' . $calendar->calendar->type . '.pdf';
    $filename = dirname( $r['calendar']->json ) . '/' . $calendar->calendar->type . '.pdf';
    
    
    //echo '<pre>'; echo var_dump( json_decode(stripslashes($cdata)), $cdata ); echo '</pre>';
    
    if( is_file( $filename ) ){
      $r['file'] = $filename;
    }
    
    if( $_POST['action'] == __FUNCTION__ ){
      wp_send_json( $r ); 
      
    }
    return $cdata;
  }
  
  public static function generate_production_files() {
    echo '<pre>'; echo var_dump('generate_production_filess'); echo '</pre>';
  }
  
  public static function register__order_action_buttons() {
    /**
     * This snippet will add cancel order button to all (not cancelled) orders.
     */
    add_filter( 'woocommerce_admin_order_actions', 'generate_production_files_button', PHP_INT_MAX, 2 );
    function generate_production_files_button( $actions, $the_order ) {
        if ( ! $the_order->has_status( array( 'cancelled' ) ) ) { // if order is not cancelled
            $actions['generate_production_files'] = array(
                'url'       => wp_nonce_url( admin_url( 'edit.php?post_type=shop_order&a=generate_production_files&order_id=' . $the_order->id ), 'wcm-gen-order-files' ),
                'name'      => __( 'Pobierz pliki', 'woocommerce' ),
                'action'    => "view gen-prod-f", // setting "view" for proper button CSS
            );
        }
        return $actions;
    }
    add_action( 'admin_head', 'generate_production_files_button_css' );
    function generate_production_files_button_css() {        
        echo '<style>.view.gen-prod-f::after { font: normal normal normal 14px/1 FontAwesome; content: "\f019" !important; }</style>';
    }
    
  }
  
  public static function register__product__rodzaj_kalendarza() {
    // create a new taxonomy
   
    register_taxonomy(
        'typ_kalendarza',
        'product',
        array(
          'label' => __( 'Typ kalendarza' ),
          'rewrite' => array( 'slug' => 'typ_kalendarza' ),

        )
      );
    
    
  }
  
  public function __constructor(){
    
    return $this;
  }
  
  public function admin_styles(){
    
    wp_enqueue_style( 'font-awesomes-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, false);
    wp_enqueue_style( 'wcm-alert-css', WCM_PLUGIN_URL . '/css/common/alert.css', false, false);
    wp_enqueue_style( 'wcm-alert-admin-gen-pf-css', WCM_PLUGIN_URL . '/css/admin/alert-admin-gen-pf.css', false, false);
    return $this;
  }
  
    
  public function admin_scripts(){
    wp_enqueue_script( 'wcm-alert-js', WCM_PLUGIN_URL . '/js/product/alert.js', array( 'vue-js' ), false, true );
    
    wp_enqueue_script( 'wcm-alert-admin-gen-pf-item-js', WCM_PLUGIN_URL . '/js/admin/alert-admin-gen-pf-item.js', array( 'vue-js' ), false, true );
    
    wp_enqueue_script( 'wcm-alert-admin-gen-pf-js', WCM_PLUGIN_URL . '/js/admin/alert-admin-gen-pf.js', array( 'vue-js' ), false, true );
    wp_enqueue_script( 'wcm-production-generator-js', WCM_PLUGIN_URL . '/js/admin/production_generator.js', array( 'vue-js' ), false, true );
    
    wp_enqueue_script( 'vue-js', 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null );
    return $this;
  }
  
    /*
  * Generates javascript/template tags for product page
  */
  public static function admin_templates(){ 
    $dirs = array(
      WCM_PLUGIN_TEMPLATES_DIR . '/common',
      WCM_PLUGIN_ADMIN_TEMPLATES_DIR
    );
    foreach( $dirs as $dir ){
      if( is_dir( $dir ) ){
        $tpl_dir = opendir( $dir );    

        while ($f = readdir($tpl_dir) ){
          $id = array();
          preg_match('/(.*)[\.]{1}.*$/', $f, $id);
          $id = basename( $dir ) . '-' . $id[ 1 ]; 

          if( is_file( $template = $dir . '/'.$f ) ){
            ?><script type="template/javascript" id="<?php echo $id; ?>"><?php require_once( $template ); ?></script><?php
          }      
        }               
      }      
    }    
  }  
  
  
}



?>