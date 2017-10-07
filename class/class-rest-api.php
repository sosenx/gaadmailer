<?php
 
class wcm_rest_api /*extends WP_REST_Controller*/ {

 function getCheepestVariation( $id, $variations_table = NULL ){ 
   if( is_null( $variations_table ) ){
     $variations_table = wcm_rest_api::getVariationsTable( $id );
   }
   $lo_price = 9999999;
   $lo_var = array();
   foreach( $variations_table as $variation) {
     if( $price === '' ){
       continue;
     }
     
      $price = (float)$variation["price"];
     
      if( $price < $lo_price && $variation["product_id"] == $id ){
        $lo_price = $price;
        $lo_var = $variation;        
      } 
   }   
   
   
   
   return $lo_var;   
 }
   
 function getCrossSell( $id, $variations_table ){ 
  $r = array(); 
  $crosssells = get_post_meta( $id, '_crosssell_ids',true);
   
   if( empty( $crosssells ) ){
     return $r;
   }
   
  $query = new WP_Query( array( 'post_type' => 'product', 'post__in' => $crosssells ) );
  $max = count( $query->posts );
   for( $i=0; $i<$max; $i++){
     $post = $query->posts[ $i ];     
     
     $image = get_the_post_thumbnail_url( $post, 'wcm-cross-sell-mini');
    // echo '<pre>'; echo var_dump( $image ); echo '</pre>';
     $r[] = array(
        'id' => $post->ID, 
        'type' => wcm_rest_api::get_typ_kalendarza( $post->ID ), 
        'thumbnail' => $image,       
        'permalink' => get_permalink( $post->ID ), 
        'title' => $post->post_title,
        'cvariation' => wcm_rest_api::getCheepestVariation( $post->ID ), 
       
     );
   }
   
 
  return  $r;
 }
  /*
  * Pobiera listę nakladów danego produktu
  */
  

  
  
  function getThumbnail( $image_path, $label = 'thumbnail' ) {
    $imageId = wcm_rest_api::getImageId( $image_path );
    $image_thumb = wp_get_attachment_image_src($imageId, 'thumbnail');
    
    return $image_thumb[0];
  }  
  
  function getImageId( $image_path ) {
    global $wpdb;
    $image_url = ( $_SERVER["SERVER_PORT"] == 443 ? 'https://' : 'http://' ) . $_SERVER["HTTP_HOST"] . $image_path;    
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));   
      return $attachment[0]; 
  }
  
  
  public static function compareStr($a, $b, $lvl = 15){
    $a = trim((string)$a);
    $b = trim((string)$b);
    
   // echo '<pre> ab'; echo var_dump($a, $b ); echo '</pre>';
    $len = strlen( $a );
    $times_check = $lvl;
    $check = true;
    for( $i=0; $i<$times_check; $i++){
      $index = mt_rand(0, $len-1);
      //echo '<pre>$index: '; echo var_dump($index, $a[$index] , $b[$index], $a[$index] == $b[$index], $len); echo '</pre>';
      $check = $a[$index] == $b[$index];
      if( !$check ){
       // echo '<pre> zle: '; echo var_dump($a[$index], $b[$index]); echo '</pre>';
        break;
      }
    }
    return $check;
  }
  
  
  
  
  /**
   * Register the routes for the objects of the controller.
   */
  static function register_routes() {
    
    register_rest_route( WCM_API_PLUGIN_NAME . '/v1', '/test', array(
      'methods' => 'GET',
      'callback' => 'test',
    ) );
    
    
  }
  
  
  
}


function test() { 
/*
$posts = get_posts();
  wp_send_json($posts);*/
wp_send_json(array( 'koot' => -1 ));

}


