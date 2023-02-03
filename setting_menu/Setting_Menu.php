<?php

if( ! class_exists( 'Setting_Menu' ) ):

class Setting_Menu {
  public function __construct () {
    self::define_constants();
    self::includes();
    self::action_hooks();

    add_action( 'admin_enqueue_scripts', array( __CLASS__, 'hook_admin_enqueue_scripts' ) );
  }

  /**
  * Difine the constants.
  *
  * @access private
  * @since 1.0.0
  * @static
  */
  private static function define_constants () {

    define( 'TROMS_MENU_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
    define( 'TROMS_MENU_PATH', dirname( __FILE__ ) );
    define( 'TROMS_MENU_URL', plugin_dir_url( __FILE__ ) );
  }


  /**
  * Includes files that must be used for Troms theme.
  *
  * @access private
  * @since 1.0.0
  * @static
  */
  private function includes () {

    require_once( TROMS_MENU_PATH . '/operation/Menu_Functions.php' );
  }

  private function action_hooks () {
    add_action( 'admin_menu', array( __CLASS__, 'troms_add_top_menu' ) );
    add_action( 'admin_menu', array( __CLASS__, 'troms_add_content_submenu' ) );
  }

  public function hook_admin_enqueue_scripts () {

    wp_enqueue_style( 'style', TROMS_SEO_URL . '/src/css/setting.css', array(), '1.0.0', false );

  }
  
  /**
  * add troms setting menu.
  *
  * @access public
  * @since 1.0.0
  * @static
  */
  public function troms_add_top_menu () {
    add_menu_page(
      'Troms設定',
      'Troms設定',
      'manage_options',
      'troms-setting',
      array( 'Menu_Functions', 'troms_menu_top' ),
      'dashicons-chart-pie',
      20,
    );
  }
  /**
  * add troms content setting sub-menu.
  *
  * @access public
  * @since 1.0.0
  * @static
  */
  public function troms_add_content_submenu () {
    add_submenu_page(
      'troms-setting',
      'サイトマップ設定',
      'サイトマップ設定',
      'manage_options',
      'troms-content-sub',
      array( 'Menu_Functions', 'troms_menu_sitemap' ),
      2
    );
  }
}

new Setting_Menu();

endif;
