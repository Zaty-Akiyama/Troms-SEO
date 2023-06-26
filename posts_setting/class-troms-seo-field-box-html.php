<?php

class Troms_SEO_Field_box_HTML {
  public $args = array();

  public function __construct ( $args ) {
    $this->args['noindex'] = $args['noindex'] ?? false;
  }

  public function echo () {
    $noindex_checked = !! $this->args['noindex'] ? 'checked' : '';
    echo <<<HTML
    <div>
      <input id="troms_noindex" name="troms_noindex" type="checkbox" value="troms_noindex" $noindex_checked>
      <label for="troms_noindex">検索に表示しない</label>
    </div>
    HTML;
    wp_nonce_field( 'troms_seo_nonce', '_troms_seo_nonce' );

    echo <<<HTML
      <input type="submit" class="button updatemeta button-small" value="更新">
    HTML;
  }
}

