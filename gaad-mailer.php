<?php 
/*
 * Plugin Name: Gaad mailer
 * Version: 1.0
 * Plugin URI: 
 * Description: Simple mailer with CSV to DB import
 * Author: Bartek Sosnowski 
 * Requires at least: 4.4
 * Tested up to: 4.8
 *
 * @package WordPress
 * @author Bartek Sosnowski
 * @since 1.0.0
 */
?>

<?php 
  if ( ! defined( 'ABSPATH' ) ) exit;
  ini_set('max_execution_time', 60*10); //10 minutes

if ( ! defined( 'GMAILER_DIR' ) ) define( 'GMAILER_DIR', plugin_dir_path( __FILE__) );


  echo '<pre>'; echo var_dump( GMAILER_DIR  ); echo '</pre>';
  

?>
