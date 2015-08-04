<?php

/**
 * Plugin Name: White Bracket Theme Options
 * Plugin URI: 
 * Description: Lets users define and edit global options.
 * Version: 1.0
 * Author: White Bracket
 * Author URI: 
 * License: GPL2
 */

/*  This program is free software; you can redistribute it and/or modify
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

// Make sure we don't expose any info if called directly

if ( !function_exists( 'add_action' ) ) {
	echo "ERROR: Plugin can not be called directly";
	exit;
}

if ( is_admin() ){

    if( isset($_GET["page"]) ):
        function white_bracket_theme_options_enqueue($hook) {
            if ( $_GET["page"] == 'white-bracket-theme-options' ):
                wp_enqueue_script( 'white-bracket-theme-options-js', plugin_dir_url( __FILE__ ) . 'js/white-bracket-theme-options.js' );
                wp_enqueue_style( 'white-bracket-theme-options-css', plugin_dir_url( __FILE__ ) . 'css/white-bracket-theme-options.css' );
            else:
                return;
            endif;
        }
        add_action( 'admin_enqueue_scripts', 'white_bracket_theme_options_enqueue' );
    endif;

	require_once dirname( __FILE__ ) . '/admin/admin.php';
}

require_once dirname( __FILE__ ) . '/white-bracket-functions.php';