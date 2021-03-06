<?php
/*
Plugin Name:  Chromathagraphic
Description:  Chromatagraphic Synesthetic 10-Key for WordPress
Version:      0
Author:       geoskogen
Author URI:   https://joseph-scoggins.com
Text Domain:  chromath
*/

defined( 'ABSPATH' ) or die( 'We make the path by walking.' );

if (is_admin()) {

  if ( !class_exists( 'Chromath_Admin' ) ) {
     include_once 'classes/chromath_admin.php';
  }

  $admin= new Chromath_Admin(
    ['main'],
    ['main']
  );

} else {
  // frontend resources
  if ( !class_exists( 'Chromath_Templater' ) ) {
     include_once 'classes/chromath_templater.php';
  }

  if ( !class_exists( 'Chromath_Router' ) ) {
     include_once 'classes/chromath_router.php';
  }
  // inject the subdomain of your app here:
  $router = new Chromath_Router('chromathagraphic');

  // add names of main css and js files
  $frontend = new Chromath_Templater(
    $router,
    ['main','button_link'],
    ['main'],
    'parasol.png',
    'child-style'
  );

  add_action( 'wp_head', [$frontend,'favicon_tag'], 2, null );

}

?>
