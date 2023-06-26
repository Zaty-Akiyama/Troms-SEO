<?php
function sethtmlspecialchars($data){
  if (is_array($data)) {
    return array_map("sethtmlspecialchars",$data);
  } else {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
  }
}

class Menu_Functions {

  public static function troms_menu_top () {
    echo <<<HTML
    <div>
      <h1>Tromsテーマ設定</h1>
      
  
    </div>
    HTML;
  }
  
  public static function troms_menu_sitemap () {
    require_once( TROMS_MENU_PATH . '/operation/Sitemap_Menu.php' );

    $sitemap_menu = new Sitemap_Menu();
    $sitemap_menu->echo();
  } 
}

new Menu_Functions();
?>
