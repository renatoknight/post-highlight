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
    // CSS do bloco (editor e frontend)
    wp_register_style(
        'phb-block-style',
        plugins_url( 'build/style-index.css', __FILE__ ),
        [],
        filemtime( plugin_dir_path( __FILE__ ) . 'build/style-index.css' )
    );

    // JS do bloco (editor)
    wp_register_script(
        'phb-block-script',
        plugins_url( 'build/index.js', __FILE__ ),
        [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components' ],
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
        true
    );

    register_block_type( __DIR__, [
        'editor_script'   => 'phb-block-script',
        'render_callback' => 'phb_render_block',
        'style'           => 'phb-block-style',
        'editor_style'    => 'phb-block-style',
    ] );
}
add_action( 'init', 'phb_register_block' );

// Renderização do bloco
require_once __DIR__ . '/render.php';