<?php
/**
 * Genesis Responsive Navigation Menu
 * 
 * Plugin Name: Genesis Responsive Navigation Menu
 * Plugin URI: https://github.com/MikeGillihan/genesis-responsive-navigation-menu
 * Description: Transforms standard Genesis navigation menus into a accessible, mobile-first and responsive navigation menu.
 * Version: 1.1.0
 * Author: Michael Gillihan
 * Author URI: http://mikegillihan.com/
 * License: GPL2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       genesis-header-nav
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/MikeGillihan/genesis-responsive-navigation-menu
 * GitHub Branch:     master
 * Credits:
 *   Gary Jones - Genesis Header Nav - https://github.com/GaryJones/genesis-header-nav
 *   Ozzy Rodriguez - Genesis Responsive Menu 2.0 - http://ozzyrodriguez.com/tutorials/genesis/genesis-responsive-menu-2-0/
 *   Brad Potter - http://bradpotter.com/responsive-mobile-navigation-menu-for-the-genesis-theme-framework/
 *   Brad Dalton - Genesis Mobile Responsive Nav Menu - http://wpsites.net/web-design/genesis-mobile-responsive-nav-menu-sub-menu/
 * 
 * @package   Genesis_Responsive_Navigation_Menu
 * @author    Michael Gillihan
 * @license   GPL-2.0+
 * @link      https://github.com/MikeGillihan/genesis-responsive-navigation-menu
 * @copyright 2014 Michael Gillihan
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

define( 'GRNM_VERSION', '1.1.0' );
define( 'GRNM_NAME', 'Genesis Responsive Navigation Menu');

/**
 * Enqueue Plugin Scripts
 * 
 * @since 0.0.1
 */
add_action( 'wp_enqueue_scripts', 'grnm_enqueue_scripts', 999 );
function grnm_enqueue_scripts() {

  $handle = sanitize_title_with_dashes( GRNM_NAME );

  // Check for Genesis Mobile First Menu
  if( wp_script_is( 'mobile-first-responsive-menu', 'enqueued' ) ) {
    wp_dequeue_script( 'mobile-first-responsive-menu' );
  };

  // Enqueue Plugin CSS
  wp_enqueue_style( $handle , plugins_url( 'grnm.css', __FILE__ ), array('dashicons') );

  // Enqueue Theme JS
  wp_enqueue_script( $handle . '-js', plugins_url( 'grnm.js', __FILE__ ), array('jquery') );
}

add_action( 'genesis_init', 'load_grnm', 99 );
function load_grnm() {
  // Unhook 'primary' & 'secondary' navs from 'genesis_after_header'
  remove_action( 'genesis_after_header', 'genesis_do_nav' );
  remove_action( 'genesis_after_header', 'genesis_do_subnav');

  // Register new 'header' navigation
  add_action( 'init', 'grnm_register_nav_menu' );

  /**
   * Hook 'header', 'primary', & 'secondary' navs to 'genesis_header'
   * Applies `genesis_header_nav_priority` filter. Use a value of 6-9 to add the nav before title + widget area, or
   * 11-14 to add it after. If you want to add it in between, you'll need to remove and re-build `genesis_do_header()`
   * so that the output of the widget area is in a different function that can be hooked to a later priority.
   */
  add_action( 'genesis_header', 'grnm_show_menu', apply_filters( 'genesis_header_nav_priority', 11 ) );
  add_action( 'genesis_header', 'genesis_do_nav', apply_filters( 'genesis_header_nav_priority', 12 ) );
  add_action( 'genesis_header', 'genesis_do_subnav', apply_filters( 'genesis_header_nav_priority', 13 ) );
}

/**
 * Register the menu location.
 *
 * @since 1.1.0
 */
function grnm_register_nav_menu() {
  register_nav_menu( 'header', __( 'Header', 'genesis-header-nav' ) );
}

/**
 * Display the menu.
 *
 * @since 1.0.0
 */
function grnm_show_menu() {
  $class = 'menu genesis-nav-menu menu-header';
  if ( genesis_superfish_enabled() ) {
    $class .= ' js-superfish';
  }
  genesis_nav_menu(
    array(
      'theme_location' => 'header',
      'menu_class'     => $class,
    )
  );
}

add_filter( 'body_class', 'grnm_remove_header_full', 15 );
/**
 * Remove then conditionally re-add header-full-width body class.
 *
 * As well as just checking for something being in the header right widget area, or the action having something
 * hooked* in, we also need to check to see if the header navigation has a menu assigned to it. Only if all are
 * false can we* proceed with saying the header-full-width class should be applied.
 *
 * Function must be hooked after priority 10, so Genesis has had a chance to do the filtering first.
 * 
 * @since 1.1.0
 *
 * @see genesis-config.php, 
 */
function grnm_remove_header_full( array $classes ) {
  // Loop through existing classes to remove 'header-full-width'
  foreach ( $classes as $index => $class ) {
    if ( 'header-full-width' === $class ) {
      unset( $classes[$index] );
      break; // No need to check the rest.
    }
  }
  // Do all the checks
  if ( ! is_active_sidebar( 'header-right' ) && ! has_action( 'genesis_header_right' ) && ! has_nav_menu( 'primary' ) )
    $classes[] = 'header-full-width';
  return $classes;
}
?>