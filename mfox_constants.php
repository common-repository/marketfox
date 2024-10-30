<?php

if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

define( 'MARKETFOX_VERSION', '0.1' );
define( 'MARKETFOX_MINIMUM_WP_VERSION', '3.7' );
define( 'MARKETFOX_API_BASE_URL', 'https://api.marketfox.io/api/v1' );
define( 'MARKETFOX_API_ARTICLES_URL', '/articles' );
define( 'MARKETFOX_POST_ID_META', 'mfox_post_id' );
define( 'MARKETFOX__PUSH_ASSETS_DIR', plugin_dir_path( __FILE__ )."push_assets/" );
