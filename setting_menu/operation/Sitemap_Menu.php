<?php

class Sitemap_Menu {

  public function __construct () {
    if ( !get_option( 'troms_seo_sitemap_type' ) ) self::create_default_sitemap_type_options();
    if ( !get_option( 'troms_seo_sitemap_custom_url' ) ) self::create_default_sitemap_url_options();
  }

  private function sethtmlspecialchars($data){
    if (is_array($data)) {
      return array_map("sethtmlspecialchars",$data);
    } else {
      return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
  }

  /**
   * サイトマップ分割化で表示する投稿タイプ、タクソノミーを選択するoptionを生成する
   * 初期設定の時のみ
   */
  private function create_default_sitemap_type_options () {
    $public_args = array(
      'public' => true,
      '_builtin' => false
    );

    $custom_taxonomies = get_taxonomies( $public_args, 'names', 'and' );
    $custom_post_types = get_post_types( $public_args, 'names', 'and' );
    
    $sitemap_type = array(
      'post' => true,
      'type_post' => 'post',
      'page' => true,
      'type_page' => 'post',
      'category' => false,
      'type_category' => 'taxonomy',
      'post_tag' => false,
      'type_post_tag' => 'taxonomy',
    );

    foreach ($custom_post_types as $value) {
      $sitemap_type[$value] = true;
      $sitemap_type["type_$value"] = "post";
    }
    foreach ($custom_taxonomies as $value) {
      $sitemap_type[$value] = false;
      $sitemap_type["type_$value"] = "taxonomy";
    }
    $sitemap_type_json = json_encode($sitemap_type);

    add_option( 'troms_seo_sitemap_type', $sitemap_type_json );
  }

  /**
   * サイトマップの特定のURLについて設定するoptionの初期設定
   */
  private function create_default_sitemap_url_options () {
    $sitemap_custom_url = array(
      "add_url" => array(),
      "remove_url" => array()
    );
    $sitemap_custom_url_json = json_encode($sitemap_custom_url);

    add_option( 'troms_seo_sitemap_custom_url', $sitemap_custom_url_json );
  }

  /**
   * 表示タイプの設定を更新
   */
  private function update_sitemap_type_options ($new_options) {

    $sitemap_type_json = get_option( 'troms_seo_sitemap_type' );
    $sitemap_type = json_decode($sitemap_type_json, true);

    foreach ($sitemap_type as $key => $value) {
      if( preg_match('/^type_/', $key) ) continue;
      $sitemap_type[$key] = array_key_exists($key, $new_options);
    }

    $sitemap_type_json = json_encode($sitemap_type);
    update_option( 'troms_seo_sitemap_type', $sitemap_type_json );
  }

  /**
   * サイトマップの特定のURLについて設定の更新
   */
  private function update_sitemap_url_options ($new_options) {

    $sitemap_url_json = get_option( 'troms_seo_sitemap_custom_url' );
    $sitemap_url = json_decode($sitemap_url_json, true);

    $sitemap_url['add_url'] = $new_options['add_url'];
    $sitemap_url['remove_url'] = $new_options['remove_url'];

    $sitemap_url_json = json_encode($sitemap_url);
    update_option( 'troms_seo_sitemap_custom_url', $sitemap_url_json );
  }

  /**
   * Post送信されたときの処理
   */
  private function post_process () {
    $posts = self::sethtmlspecialchars($_POST);
    if( count($posts) === 0 ) return;

    $sitemap_types = array();
    $sitemap_urls = array(
      "add_url" => array(),
      "remove_url" => array()
    );

    foreach ($posts as $key => $value) {
      if( preg_match('/^type_/', $key) ) {
        $sitemap_types[$value] = $value;
      }else if( preg_match('/^url_0_/', $key) && $value !== "") {
        $sitemap_urls["add_url"][] = $value;
      }else if( preg_match('/^url_1_/', $key) && $value !== "") {
        $sitemap_urls["remove_url"][] = $value;
      }
    }

    self::update_sitemap_type_options($sitemap_types);
    self::update_sitemap_url_options($sitemap_urls);
  }

  /**
   * 表示されるHTML
   */
  public function echo () {

    self::post_process();

    $sitemap_type_json = get_option( 'troms_seo_sitemap_type' );
    $sitemap_type = json_decode($sitemap_type_json, true);

    $sitemap_custom_url_json = get_option( 'troms_seo_sitemap_custom_url' );
    $sitemap_urls = json_decode($sitemap_custom_url_json, true);

    require_once( TROMS_MENU_PATH . '/operation/Box_Type_Setting.php' );

    $Box = new Box_Type_Setting;
  
    echo <<<HTML
    <h1 class="menu-sitemap">サイトマップ設定</h1>
    <form method="post" action="#">
      <fieldset>
        <legend>表示するタイプ</legend>
    HTML;

    //表示するタイプの設定
    foreach ($sitemap_type as $key => $value) {
      if( preg_match('/^type_/', $key) ) continue;

      $Box::box_echo( $key, $value, $sitemap_urls["type_$key"] );
    }

    echo <<<HTML
      </fieldset>
    HTML;

    // 追加するURLの設定
    echo <<<HTML
    <div class="jsUrlWrapper url-wrapper">
      <p>追加で設定するURLを設定してください。"https://"必須</p>
    HTML;
    $i = 1;

    foreach($sitemap_urls['add_url'] as $value) {
      echo <<<HTML
        <input type="url" name="url_0_$i" id="url_0_$i" pattern="https://.*" placeholder="https://example.com" size="30" value="$value">
      HTML;
      $i++;
    }
    
    echo <<<HTML
      <input type="url" name="url_0_$i" id="url_0_$i" pattern="https://.*" placeholder="https://example.com" size="30">
    </div>
    HTML;
    
    //除外するURLの設定
    echo <<<HTML
    <div class="jsUrlWrapper url-wrapper">
      <p>除外するURLを設定してください。"https://"必須</p>
    HTML;
    $i = 1;

    foreach($sitemap_urls['remove_url'] as $value) {
      echo <<<HTML
        <input type="url" name="url_1_$i" id="url_1_$i" pattern="https://.*" placeholder="https://example.com" size="30" value="$value">
      HTML;
      $i++;
    }
    
    echo <<<HTML
      <input type="url" name="url_1_$i" id="url_1_$i" pattern="https://.*" placeholder="https://example.com" size="30">
    </div>
    HTML;

    $path = TROMS_SEO_URL;
    echo <<<HTML
      <input type="submit" value="決定">
    </form>
    <script src="$path/src/js/sitemap_menu.js" ></script>
    HTML;
  }
}