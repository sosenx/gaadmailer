<?php 
/*
 * Plugin Name: Gaad mailer
 * Text Domain: gaad-mailer
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


if ( ! defined( 'ABSPATH' ) ) exit;
ini_set('max_execution_time', 60*10); //10 minutes


/*
* var WPLANG string ala ma kota
*/
if ( !defined( 'WPLANG'))                         define( 'WPLANG',                       'pl_PL' );
if ( !defined( 'GMAILER_ENV'))                    define( 'GMAILER_ENV',                  'DEV' );

if ( !defined( 'WCM_PLUGIN_NAME'))                define( 'WCM_PLUGIN_NAME',              trim(dirname(plugin_basename(__FILE__)), '/') );
if ( !defined( 'GMAILER_DIR' ) )                  define( 'GMAILER_DIR',                  plugin_dir_path( __FILE__) );
if ( !defined( 'GMAILER_THEME_FILES_DIR' ) )      define( 'GMAILER_THEME_FILES_DIR',      GMAILER_DIR . 'theme_files' );
if ( !defined( 'WCM_PLUGIN_APP_TEMPLATES_DIR' ) ) define( 'WCM_PLUGIN_APP_TEMPLATES_DIR', GMAILER_DIR . 'templates' );
if ( !defined( 'WCM_PLUGIN_DIR') )                define( 'WCM_PLUGIN_DIR',               GMAILER_DIR . '/' . WCM_PLUGIN_NAME );
if ( !defined( 'WCM_PLUGIN_URL') )                define( 'WCM_PLUGIN_URL',               WP_PLUGIN_URL . '/' . WCM_PLUGIN_NAME );
if ( !defined( 'GMAILER_FORCE_FILES_UPDATED') )   define( 'GMAILER_FORCE_FILES_UPDATED',  true );

// Load plugin class files
require_once( 'class/class-wcm-hooks-mng.php' );
require_once( 'inc/class-wcm-filters.php' );
require_once( 'inc/class-wcm-actions.php' );
require_once( 'class/class-rest-api.php' );  
require_once( 'inc/class-wcm-admin-actions.php' );
require_once( 'vendor/autoload.php' );
require_once( 'class/abstract-imap-watch.php' );





$core_hooks = new wcm_hooks_mng( 'core' );
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array('wcm_actions::core_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array('wcm_actions::common_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array('wcm_actions::app_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', 'wcm_actions::core_styles');
$core_hooks->add_hook( 'action', 'init', array( 'wcm_actions::localisation', 10, 0 ) );
$core_hooks->add_hook( 'action', 'after_setup_theme', array( 'wcm_actions::update_theme_files', 10, 0 ) );
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', 'wcm_actions::common_styles');

//usuwanie wersji dołąćzanej do nazwy pliku
$core_hooks->add_hook( 'filter', array('style_loader_src', 'script_loader_src'), array( 'wcm_filters::remove_verion_suffix', 9999, 1 ) );
//defer
$core_hooks->add_hook( 'filter', array('script_loader_tag' ), array( 'wcm_filters::add_defer_attribute', 10, 2 ) );
$core_hooks->add_hook( 'filter', array('clean_url' ), array( 'wcm_admin_actions::ikreativ_async_scripts', 11, 1) );


//ajax
//$core_hooks->add_hook( 'action', 'wp_ajax_nopriv_', array('wcm_actions::', 10, 0, true));
//$core_hooks->add_hook( 'action', 'wp_ajax_', array('wcm_actions::', 10, 0, true));


$core_hooks->apply_hooks();




?>
