<?php

if( ! class_exists( 'Class_Sitemap' ) ):

class Class_Sitemap {
  public function __construct () {
    add_action( 'init', array( $this, 'add_routing' ) );

    add_filter( 'query_vars', array( $this, 'add_query_vars' ) );

    register_activation_hook( TROMS_SEO_FILE, array( $this, 'flush_rewrite_rules' ) );

    add_action( 'template_redirect', array( $this, 'sitemap_controller' ) );
    
    add_filter( 'redirect_canonical', array($this, 'remove_end_slash'), 10, 2 );
  }

  public function add_routing () {
    add_rewrite_rule( '^sitemap-(.+)\.xml$', 'index.php?sitemap_params=$matches[1]', 'top' );
  }
  public function add_query_vars ( $query_vars ) {
    $query_vars[] = 'sitemap_params';
    return $query_vars;
  }
  public function flush_rewrite_rules () {
    $this->add_routing();
    flush_rewrite_rules();

    require_once( TROMS_MENU_PATH . '/operation/Menu_Functions.php' );
    new Menu_Functions;
  }
  public function remove_end_slash ($redirect_url, $request_url) {
    if( get_query_var('sitemap_params') === '' ) return $redirect_url;

    return $request_url;
  }

  public function sitemap_controller () {
    global $wp_query;

    $controll_query = isset( $wp_query->query_vars['sitemap_params']) ? $wp_query->query_vars['sitemap_params'] : false;
    if( !$controll_query ) return;

    if( $controll_query === 'index' ) {
      $this->sitemap_base();
    }else {
      $this->sitemap_single($controll_query);
    }    

    exit;
  }

  private function sitemap_base () {
    header("Content-type: text/xml; charset=utf-8");

    echo <<<XML
    <?xml version="1.0" encoding="UTF-8" ?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    XML;

    $sitemap_type_json = get_option( 'troms_seo_sitemap_type' );
    $sitemap_type = json_decode($sitemap_type_json, true);

    foreach ($sitemap_type as $key => $value) {
      if( preg_match('/^type_/', $key) || !$value) continue;

      $uri = home_url() . '/sitemap-' . $key . '.xml';
      echo <<<XML
        <sitemap>
          <loc>$uri</loc>
        </sitemap>
      XML;
    }


    echo <<<XML
    </sitemapindex>
    XML;
  }
  private function sitemap_single ( $type ) {
    $sitemap_type_json = get_option( 'troms_seo_sitemap_type' );
    $sitemap_type = json_decode($sitemap_type_json, true);

    if( !$sitemap_type[$type] ) return;

    header("Content-type: text/xml; charset=utf-8");

    echo <<<XML
    <?xml version="1.0" encoding="UTF-8" ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    XML;

    $home_url = home_url();
    echo <<<XML

      <url>
        <loc>$home_url</loc>
      </url>
    XML;

    if( $sitemap_type["type_$type"] === "post" ) {
      
      $args = array(
        'post_type' => $type,
        'posts_per_page' => -1
      );
      $custom_query = new WP_Query( $args );
      if ( $custom_query->have_posts() ) :
      while ( $custom_query->have_posts() ) : 
        $custom_query->the_post();

        $url = get_permalink();
        $date = get_the_date() === get_the_modified_date() ? get_the_date("Y-m-d") : get_the_modified_date("Y-m-d");

        echo <<<XML

          <url>
            <loc>$url</loc>
            <lastmod>$date</lastmod>
          </url>
        XML;
      endwhile;
      endif;
      wp_reset_postdata();
    }elseif( $sitemap_type["type_$type"] === "taxonomy" ) {
      $terms = get_terms( array(
        'taxonomy' => $type,
        'hide_empty' => false,
      ) );

      if ( !empty( $terms ) ):
      foreach ( $terms as $term ) :
        $term_meta = get_option( "taxonomy_term_" . $term->term_id );
        $url = get_term_link( $term );    
    
        echo <<<XML
          <url>
            <loc>$url</loc>
          </url>
        XML;
      endforeach;
      endif;
    }

    echo <<<XML

    </urlset>
    XML;
  }
}

new Class_Sitemap;
endif;