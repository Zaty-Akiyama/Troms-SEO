<?php

class Troms_SEO_Menu_Functions {

  public static function troms_menu_top () {
    echo <<<HTML
    <div>
      <h1>Tromsテーマ設定</h1>
      
  
    </div>
    HTML;
  }
  
  public static function troms_menu_sitemap () {
    require_once( TROMS_MENU_PATH . '/operation/class-troms-seo-sitemap-menu.php' );

    $sitemap_menu = new Troms_SEO_Sitemap_Menu();
    $sitemap_menu->echo();
  } 
}

new Troms_SEO_Menu_Functions();
?>
