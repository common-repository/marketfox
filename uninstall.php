<?php  
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

define( 'MARKETFOX__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_constants.php' );
require_once( MARKETFOX__PLUGIN_DIR . 'mfox_posts_sync_worker.php' );
$mfox_posts_fetch_worker->deactivation();
