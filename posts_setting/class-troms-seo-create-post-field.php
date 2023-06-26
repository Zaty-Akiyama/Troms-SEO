<?php

if ( !class_exists( 'Troms_SEO_Create_Post_Field' ) ):
class Troms_SEO_Create_Post_Field {

  public $post_types;

  public function __construct () {

    add_action('wp_loaded', array($this, 'init'));
    
    require_once( TROMS_SEO_PATH . '/posts_setting/class-troms-seo-front-end.php' );

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

  public function init () {

    $public_args = array(
      'public' => true,
      '_builtin' => false
    );
    $custom_post_types = get_post_types( $public_args, 'names', 'and' );
    $default_post = array('post', 'page');
    $this->post_types = array_merge($default_post, $custom_post_types);

    $this->add_excerpt();
    add_action('add_meta_boxes', array($this, 'troms_seo_create_post_field'));
    add_action( 'save_post', array($this, 'save_seo_field_data') );
  }

  public function troms_seo_create_post_field () {
    foreach ($this->post_types as $post_type) {
      add_meta_box(
        'troms_seo_post_field',
        'Troms SEO',
        array($this, 'troms_seo_post_field'),
        $post_type,
        'normal',
        'high'
      );
    }
  }

  public function save_seo_field_data () {
    if ( !isset($_POST['_troms_seo_nonce']) || !wp_verify_nonce($_POST['_troms_seo_nonce'], 'troms_seo_nonce') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    $post_id = $_POST['post_ID'];
    $post_type = get_post_type($post_id);

    if ( !current_user_can('edit_post', $post_id) ) return;


    if ( !in_array($post_type, $this->post_types) ) return;

    $noindex_checked = isset( $_POST['troms_noindex'] );

    update_post_meta( $post_id, '_troms_seo_noindex', $noindex_checked );
  }

  public function troms_seo_post_field () {
    require_once( TROMS_SEO_PATH . '/posts_setting/class-troms-seo-field-box-html.php' );

    $noindex_checked = get_post_meta( get_the_ID(), '_troms_seo_noindex', true );

    $Box_HTML = new Troms_SEO_Field_Box_HTML(['noindex' => $noindex_checked]);
    $Box_HTML->echo();
  }

  private function add_excerpt () {
    foreach ($this->post_types as $post_type) {
      add_post_type_support( $post_type, 'excerpt' );
    }
  }
}

new Troms_SEO_Create_Post_Field;

endif;