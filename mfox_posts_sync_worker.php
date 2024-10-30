<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

class MfoxPostsSyncWorker{
  private $options;

  public function __construct()
  {
    $this->options = get_option( 'marketfox_api' );
    add_action('fetch_marketfox_posts_cron', array($this, 'fetch_marketfox_posts'));
  }

  public function activation() {
    if (! wp_next_scheduled ( 'fetch_marketfox_posts_cron' )) {
      wp_schedule_event(time(), '15min', 'fetch_marketfox_posts_cron');
    }
  }

  public function fetch_marketfox_posts() {
    $latest_posts_request = $this->get_from_mfox_api(MARKETFOX_API_ARTICLES_URL);
    $latest_posts = json_decode($latest_posts_request["body"]);
    foreach ($latest_posts as $post) {
      $this->create_or_update_posts_from_mfox_post($post);
    }
    return $latest_posts;
  }

  public function get_from_mfox_api($url){
    if($this->options['api_key'] && $this->options['site_key']){
      $args = array(
         'headers' => array(
            'Authorization' => $this->options["api_key"].":".$this->options["site_key"],
            'Content-Type' => 'application/json'
          )
        );
      $url =  MARKETFOX_API_BASE_URL.$url;
      return wp_remote_request( $url, $args );
    }
    return null;
  }

  public function get_existing_mf_post_using_meta($mfox_post_id){
    $args = array(
        'meta_key' => MARKETFOX_POST_ID_META,
        'meta_value' => $mfox_post_id,
        'post_type' => 'post',
        'post_status' => 'any',
        'posts_per_page' => 2
    );
    $posts = get_posts($args);
    if($posts){
      return $posts[0]->ID;
    }  
  }

  public function create_or_update_posts_from_mfox_post($post){
    try {
      $existing_mf_post = $this->get_existing_mf_post_using_meta($post->id);

      $user_id = get_current_user_id();
      $category_id = get_cat_ID($post->category);
      if(!$category_id){
        $category_id = wp_create_category($post->category);
      }
      
      $post_params = array(
        'post_title' => $post->title,
        'post_name' => $post->slug,
        'post_content' => $post->description,
        'post_status' => 'publish',
        'post_date' => date($post->published_at),
        'post_author' => $user_id,
        'post_type' => 'post',
        'post_category' => array($category_id)
      );

      if($existing_mf_post){
        $post_params["ID"] = $existing_mf_post;
        wp_update_post( $post_params );
      }else{
        $post_id = wp_insert_post($post_params);
        add_post_meta($post_id, MARKETFOX_POST_ID_META , $post->id, true);
      }
    }
    catch(Exception $e) {
      var_dump($e);
    }
  }

  public function deactivation() {
    wp_clear_scheduled_hook('fetch_marketfox_posts_cron');
  }
}

$mfox_posts_sync_worker = new MfoxPostsSyncWorker();

