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
add_action('init', 'related_posts_elementor_load_textdomain');

// Load Elementor Widget
function register_related_posts_widget($widgets_manager) {
    require_once __DIR__ . '/elementor/widgets/related-posts-carousel.php';
    $widgets_manager->register(new \Related_Posts_Carousel_Widget());
}
add_action('elementor/widgets/register', 'register_related_posts_widget');

// Ajax handler for getting post type taxonomies
function get_post_type_taxonomies_handler() {
    // Verify nonce
    if (!check_ajax_referer('get_post_type_taxonomies', 'nonce', false)) {
        wp_send_json_error('Invalid nonce');
        return;
    }

    // Get post type from request
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
    if (empty($post_type)) {
        wp_send_json_error('Post type is required');
        return;
    }

    // Get taxonomies for the post type
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $taxonomy_options = [];

    foreach ($taxonomies as $taxonomy) {
        $taxonomy_options[$taxonomy->name] = $taxonomy->label;
    }

    wp_send_json_success($taxonomy_options);
}
add_action('wp_ajax_get_post_type_taxonomies', 'get_post_type_taxonomies_handler');

// Enqueue Styles and Scripts
function related_posts_enqueue_assets() {
    // Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://unpkg.com/swiper@8/swiper-bundle.min.css',
        [],
        '8.0.0'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://unpkg.com/swiper@8/swiper-bundle.min.js',
        [],
        '8.0.0',
        true
    );

    // Plugin CSS
    wp_enqueue_style(
        'related-posts-carousel',
        plugins_url('assets/css/related-posts-carousel.css', __FILE__),
        [],
        '1.0.0'
    );

    // Plugin JS
    wp_enqueue_script(
        'related-posts-carousel',
        plugins_url('assets/js/related-posts-carousel.js', __FILE__),
        ['jquery', 'swiper'],
        '1.0.0',
        true
    );

    // Localize script for Ajax
    wp_localize_script('related-posts-carousel', 'relatedPostsCarousel', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('get_post_type_taxonomies')
    ]);
}
add_action('wp_enqueue_scripts', 'related_posts_enqueue_assets');
