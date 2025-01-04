<?php
/*
Plugin Name: Related Posts Elementor
Description: A custom Elementor widget to display related posts based on the current post's categories.
Version: 1.0
Author: Adalberto H. Vega
Text Domain: related-posts-elementor
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load translations
function related_posts_elementor_load_textdomain() {
    load_plugin_textdomain('related-posts-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'related_posts_elementor_load_textdomain');

// Load Elementor Widget
function register_related_posts_widget($widgets_manager) {
    require_once __DIR__ . '/elementor/class-related-posts-widget.php';
    $widgets_manager->register(new \Elementor_Related_Posts_Widget());
}
add_action('elementor/widgets/register', 'register_related_posts_widget');

// Enqueue Styles
function related_posts_enqueue_styles() {
    wp_enqueue_style('related-posts-style', plugins_url('assets/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'related_posts_enqueue_styles');
