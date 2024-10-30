<?php
/**
 * @package Marketfox
 */
/*
Plugin Name: Marketfox
Plugin URI: https://www.marketfox.io/
Description: Create fully customizable landing pages, in-app messages, web-forms with drag and drop designer and zero coding. Target specific users based on their behavior and activities in website. Responsive templates to personalise in any platform. 

To get started: 1) <a href="https://www.marketfox.io/">Sign up for a Marketfox account</a> to get the API credentials, 2) Go to your Marketfox plugin settings page and save your information.

Version: 0.4
Author: Marketfox
Author URI: https://www.marketfox.io/
License: GPLv2 or later
Text Domain: marketfox
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2017 Marketfox, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

define( 'MARKETFOX__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_constants.php' );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_utils.php' );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_admin_menu_page.php' );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_js.php' );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_posts_sync_worker.php' );

