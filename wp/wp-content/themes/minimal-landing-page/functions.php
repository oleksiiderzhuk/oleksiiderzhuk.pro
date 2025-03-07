<?php
function minimalistic_landing_page_assets() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'minimalistic_landing_page_assets' );
