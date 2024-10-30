<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

class MfoxAdminMenuPage
{
  private $options;

  public function __construct()
  {
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
  }

  public function add_plugin_page()
  {
    add_options_page(
      'Marketfox Settings', 
      'Marketfox', 
      'manage_options', 
      'marketfox-settings', 
      array( $this, 'create_admin_page' )
    );
  }

  public function create_admin_page()
  {
    $this->options = get_option( 'marketfox_api' );
    ?>
    <div class="wrap">
      <h1>Marketfox Settings</h1>
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields( 'mfox_api_settings_group' );
        do_settings_sections( 'marketfox-settings' );
        submit_button();
      ?>
      </form>
    </div>
    <?php
  }

  public function page_init()
  {        
    register_setting(
      'mfox_api_settings_group', // Option group
      'marketfox_api', // Option name
      array( $this, 'sanitize' ) // Sanitize
    );

    add_settings_section(
      'mfox_api_setting_section_id', // ID
      'Marketfox API Settings', // Title
      array( $this, 'print_section_info' ), // Callback
      'marketfox-settings' // Page
    );  

    add_settings_field(
      'api_key', // ID
      'API Key', // Title 
      array( $this, 'api_key_callback' ), // Callback
      'marketfox-settings', // Page
      'mfox_api_setting_section_id' // Section           
    );      

    add_settings_field(
      'site_key', 
      'Site Key', 
      array( $this, 'site_key_callback' ), 
      'marketfox-settings', 
      'mfox_api_setting_section_id'
    );      
  }

  public function sanitize( $input )
  {
    $new_input = array();
     if( isset( $input['api_key'] ) )
        $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

     if( isset( $input['site_key'] ) ){
      $new_input['site_key'] = sanitize_text_field( $input['site_key'] );
      if(!empty($new_input['site_key'])){
        $this->rewrite_mf_worker_js($new_input['site_key']);
        $mfox_posts_sync_worker = new MfoxPostsSyncWorker();
        $mfox_posts_sync_worker->deactivation();
        $mfox_posts_sync_worker->activation();
      }
    }
    return $new_input;
  }

  public function print_section_info()
  {
    print 'Enter your marketfox api details below:';
  }

  public function api_key_callback()
  {
    printf(
      '<input type="text" id="api_key" name="marketfox_api[api_key]" value="%s" />',
      isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
    );
  }

  public function site_key_callback()
  {   
    printf(
      '<input type="text" id="site_key" name="marketfox_api[site_key]" value="%s" />',
      isset( $this->options['site_key'] ) ? esc_attr( $this->options['site_key']) : ''
    );
  }

  public function rewrite_mf_worker_js($site_key)
  { 
    $worker_file = fopen( MARKETFOX__PUSH_ASSETS_DIR."mf_worker.php", "w");
    $template_file = file_get_contents( MARKETFOX__PUSH_ASSETS_DIR."mf_worker.js.template", "true");
    fwrite($worker_file, str_replace("{{WEBSITE_KEY}}",$site_key,$template_file));
    fclose($worker_file);
  }
}

if(is_admin() )
  $marketfox_settings_page = new MfoxAdminMenuPage();
