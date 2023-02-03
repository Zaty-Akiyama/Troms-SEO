<?php

class Create_Post_Field {
  public function __construct () {

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
}