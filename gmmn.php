<?php
/**
 * Plugin Name: Genesis Mobile Multi Nav
 * Plugin URI: https://github.com/MikeGillihan/Genesis Mobile Multi Nav
 * Description: Concatenates multi Genesis nav menus into a single mobile nav.
 * Version: 0.0.1
 * Author: Michael Gillihan
 * Author URI: http://mikegillihan.com/
 * License: GPL2
 */

/*  Copyright 2014 Michael Gillihan

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

define( 'GMMN_VERSION', '0.0.1' );
define( 'GMMN_NAME', 'Genesis Mobile Multi Nav');

/**
 * Enqueue Plugin Scripts
 * 
 * @since 0.0.1
 */
add_action( 'wp_enqueue_scripts', 'gmmn_enqueue_scripts', 999 );
function gmmn_enqueue_scripts() {

  $handle = sanitize_title_with_dashes( GMMN_NAME );

  // Check for Genesis Mobile First Menu
  if( wp_script_is( 'mobile-first-responsive-menu', 'enqueued' ) ) {
    wp_dequeue_script( 'mobile-first-responsive-menu' );
  };

  // Enqueue Plugin CSS
  wp_enqueue_style( $handle , plugins_url( 'gmmn.css', __FILE__ ) );

  // Enqueue Theme JS
  wp_enqueue_script( $handle . '-js', plugins_url( 'gmmn.js', __FILE__ ), array('jquery') );

   // Check for Dashicons
  if( wp_style_is( 'dashicons', 'enqueued' ) ) {
    return;
  } else {
    wp_enqueue_style( 'dashicons' );
  };
}
?>