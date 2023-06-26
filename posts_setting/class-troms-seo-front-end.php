<?php

class Troms_SEO_Front_End {
  public function __construct () {
    add_action( 'wp_robots', array( $this, 'troms_noindex' ), 9999 );
  }

  public function troms_noindex ( array $robots ) {
    $troms_seo_noindex = get_post_meta( get_the_ID(), '_troms_seo_noindex', true );

    if( "1" === $troms_seo_noindex ) {
      $robots = ['noindex' => true, 'nofollow' => true];
    }
    
    return $robots;
  }
}
new Troms_SEO_Front_End();