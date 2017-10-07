<?php
//echo '<pre>'; echo var_dump( WCM_PLUGIN_DIR ); echo '</pre>';

require_once( '/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/plugins/gaad-woocommerce-calendar-maker/vendor/SimpleImage/src/claviska/SimpleImage.php' );
//require_once( '/wp-content/plugins/gaad-woocommerce-calendar-maker/vendor/SimpleImage/src/claviska/SimpleImage.php' );
function cleanFiles( $dir ){
  chdir('/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/');
  $files = glob(  $dir . '/*' );
  $max = count ( $files );
  for( $i = 0; $i<$max; $i++){
    
    if( preg_match( '/'. $_POST['part'] .'--/', $files[$i] ) && is_file( $files[$i] ) ){        
        unlink( $files[$i] );
    }
    
    
  }
  
}

if( $_FILES[0]["error"] == 0 ){
  
  if( $_POST['externalProject'] == 'true' ){    
    $path = '/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/user_uploads/'  .  $_POST['uid'].'/' . $_POST["cartItem"].'/';
    $source = is_null( $source ) ? $_FILES[ 0 ][ 'tmp_name' ] : $source;
    $file_name = $_FILES[ 0 ][ 'name' ];
    $file_path = $path . $file_name ;
    if( move_uploaded_file( $source, $file_path ) ){
      $json_file = $path .'/'. $_POST["cartItem"] .'.json';
      if( is_file($json_file) ){
        $json = json_decode( file_get_contents( $json_file ), true );
        if( !is_null( $json ) ){
          $json["valid"] = true;
          $json["externalproject"] = true;
          $json["externalprojectpath"] = $file_path;
          
          file_put_contents( $json_file, json_encode($json) );
          
        }
      }
      
      
    }
    
    
} else{
  chdir('/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/');
  
  $files = array( 
    'part' => $_POST[ 'part' ],
    'cwd' => getcwd(), );
  
  $page = '';

  $tmp = 'user_uploads/'  .  $_POST['uid'].'/' . $_POST["cartItem"];
  
  if( !is_dir( $tmp ) ){
    mkdir( $tmp, 0777, true );
  }
  
  
    $file_name = array();
    preg_match('/.*[\.]{1}(.*)$/', $_FILES[0]["name"], $file_name);
    
    
  cleanFiles( $tmp );
  
  
    $source = is_null( $source ) ? $_FILES[ 0 ][ 'tmp_name' ] : $source;		
		$attachment_id = $_POST[ 'part' ];
    $fileID = uniqid();
   // $fileID = '';
  
    if( isset( $_POST[ 'page' ] ) ){
      $page = (int)$_POST[ 'page' ] > 1 ? '--'. $_POST[ 'page' ] .'--' : '';
    }  
  
		$file_name = $attachment_id . '--' . $fileID . $page . '.' . $file_name[1];
		
		//no src file, escape
		if( !is_file( $source ) ){
			$files['error'] = true;
			
			return;
		} else {
						
			try {		
				if( move_uploaded_file( $source, $target = $tmp . '/' . $file_name ) ){	
          sleep(1);
          //tworzenie miniatury pliku
            $image = new \claviska\SimpleImage();
            $image
              ->fromFile($target)                     
              ->resize(500,  null)->toFile( dirname( $target ) . '/th500---' . $file_name , 'image/png', 50)
              ->resize(80,  null)->toFile( dirname( $target ) . '/th80---' . $file_name , 'image/png', 45);

          //echo '<pre>'; echo var_dump($target, $image); echo '</pre>';
          
					chmod( $files['uploaded'] = $tmp . '/' . $file_name, 0777 );					
          $files['imagesize'] = getimagesize($files['uploaded']);
					$files['uploaded'] = 'https://' . $_SERVER['HTTP_HOST'] . '/wp-content/' . $files['uploaded'];
          
          $files['error'] = false;
				}
			} catch ( Exception $e) { $files['uploaded'] =  'Error: '. $e->getMessage(); }
			
		}
	
  
  
  
  
  
  
  
  
  
  
  
  echo json_encode( $files );
	die();
  
}}



?>