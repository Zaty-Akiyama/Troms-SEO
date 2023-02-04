<?php

if ( !class_exists( 'Create_Post_Field' ) ):
class Create_Post_Field {
  public function __construct () {

    add_action('wp_loaded', array($this, 'add_excerpt'));

  }

  /**
   * カスタムフィールドの作成
   * SEO関連は全てまとめる
   * 
   * get_option( 'troms_seo_sitemap_type' )でその投稿タイプがfalseの時はデフォルト値に戻す
   * 
   * title = title | site_title
   * description 抜粋
   * 
   * noindex.nofollowに関してはデフォルトで設定しない、falseの時設定する
   * 
   * 隠しmetakey は _meta
   */

   public function add_excerpt () {
    $public_args = array(
      'public' => true,
      '_builtin' => false
    );

    $custom_post_types = get_post_types( $public_args, 'names', 'and' );

    $default_post = array('post', 'page');
    $post_types = $default_post + $custom_post_types;

    foreach ($post_types as $post_type) {
      add_post_type_support( $post_type, 'excerpt' );
    }
   }
}

new Create_Post_Field;

endif;