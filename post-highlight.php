<?php
/**
 * Plugin Name: Post Highlight Block
 * Description: Bloco para exibir posts em destaque (um grande + pequenos).
 * Version: 1.0.0
 * Author: Renato de Jesus
 * Text Domain: post-highlight
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function phb_load_textdomain() {
    load_plugin_textdomain( 'post-highlight', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'phb_load_textdomain' );

function phb_register_block() {
    register_block_type( __DIR__, [
        'render_callback' => 'phb_render_block',
    ] );
}
add_action( 'init', 'phb_register_block' );

require_once __DIR__ . '/render.php';