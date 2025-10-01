<?php
/**
 * Plugin Name: Post Highlight Block
 * Description: Bloco para exibir posts em destaque (um grande + pequenos).
 * Version: 1.0.0
 * Author: Renato de Jesus
 * Text Domain: post-highlight
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Carrega traduções do plugin
function phb_load_textdomain() {
    load_plugin_textdomain( 'post-highlight', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'phb_load_textdomain' );

// Registra o bloco e seus assets
function phb_register_block() {

    register_block_type( __DIR__, [
        'render_callback' => 'phb_render_block',
    ] );
}
add_action( 'init', 'phb_register_block' );


// Adiciona campos extras à REST API para uso no editor Gutenberg
add_action( 'rest_api_init', function () {
    register_rest_field( 'post', 'featured_media_url', [
        'get_callback' => function ( $post ) {
            $thumb = get_the_post_thumbnail_url( $post['id'], 'large' );
            return $thumb ?: 'https://via.placeholder.com/400x200';
        },
    ] );

    register_rest_field( 'post', 'category_names', [
        'get_callback' => function ( $post ) {
            $cats = get_the_category( $post['id'] );
            return wp_list_pluck( $cats, 'name' );
        },
    ] );
} );

// Renderização do bloco
require_once __DIR__ . '/render.php';