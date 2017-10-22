<?php
 



 /**
 * KOOOT jest taaki kochany.
 *
 * Description.
 *
 * @since x.x.x
 *
 * @see Function/method/class relied on
 * @link URL
 * @global type $varname Description.
 * @global type $varname Description.
 *
 * @param type $var Description.
 * @param type $var Optional. Description. Default.
 * @return type Description.
 */
class wcm_rest_api /*extends WP_REST_Controller*/ {


  
  
  /**
  * Register the routes for the objects of the controller.
  *
  * Here you csn register routes uses by your application.
  *
  * @retrun Bool
  */
  static function register_routes() {
    
    register_rest_route( WCM_API_PLUGIN_NAME . '/v1', '/test', array(
      'methods' => 'GET',
      'callback' => 'test',
    ) );
    
    return true;
  }
  
  
  
}


function test() { 
/*
$posts = get_posts();
  wp_send_json($posts);*/
wp_send_json(array( 'koot' => -1 ));

}


