<?php
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

class MfoxJs
{
  private $options;

  public function __construct()
  {
    $this->options = get_option( 'marketfox_api' );
    add_action( 'wp_head', array( $this, 'inject_mf_js'), 100 );
  }

  public function inject_mf_js(){
    if($this->options['site_key']){
      ?>
      <!-- start Marketfox --> <script> function load_asyc(t,n){var e=document,o=e.createElement(t),s=e.getElementsByTagName("head")[0];for(i in n)o.setAttribute(i,n[i]);s.appendChild(o)}load_asyc("link",{rel:"manifest",href:"<?php print plugin_dir_url( MARKETFOX__PUSH_ASSETS_DIR."manifest.json")."manifest.json" ?>"}),load_asyc("script",{async:!0,src:"https://assets-marketfox-io.s3.amazonaws.com/subdomain/custom/<?php print $this->options['site_key']; ?>.js"}),marketfox={track:function(t,n,e,o,i){this.proxy(t,n,e,o,i)},register:function(t,n,e,o,i){this.proxy(t,n,e,o,i)},people:{set:function(t){marketfox.proxy("user",t)}},proxy:function(t,n,e,o,i){var s=this;"object"==typeof window.MarketFoxSdk?this.sdkSync(t,n,e,o,i):window.document.body.addEventListener("mf:sdkloaded",function(){s.sdkSync(t,n,e,o,i)},!1)},sdkSync:function(t,n,e){window.MarketFoxSdk[t](n,e)}}; </script> 
      <script type="text/javascript">
        window.workerFile = "<?php print plugin_dir_url( MARKETFOX__PUSH_ASSETS_DIR."mf_worker.php")."mf_worker.php"?>"
      </script>
      <!-- end Marketfox -->
      <?php
    }
  }
}

if( !is_admin() )
  $marketfox_js = new MfoxJs();

