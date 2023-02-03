<?php
/**
 * Troms-SEO Plugin
 * 
 * Plugin name: Troms-SEO
 * 
 * Description: ZATYのWordPressプラグイン規格TromsのSEO対策用プラグインです。
 * Version: 1.0.0
 * Author: ZATY
 * Author URI: https://zaty.jp
 * 
 */

if( ! class_exists( 'Troms_SEO' ) ):

class Troms_SEO {
  public function __construct () {
    self::define_constants();

    self::includes();
  }

  private function define_constants () {
    define( 'TROMS_SEO_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
    define( 'TROMS_SEO_PATH', dirname( __FILE__ ) );
    define( 'TROMS_SEO_URL', plugin_dir_url( __FILE__ ) );
    define( 'TROMS_SEO_FILE', __FILE__ );
  }

  private function includes () {
    require_once( TROMS_SEO_PATH . '/sitemap/Create_Sitemap.php' );
    require_once( TROMS_SEO_PATH . '/setting_menu/Setting_Menu.php' );
    require_once( TROMS_SEO_PATH . '/posts_setting/Create_Post_Field.php' );
  }

}

new Troms_SEO();

endif;