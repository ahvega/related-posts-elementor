<?php
/**
 * Plugin Name: Related Posts Elementor Widget
 * Plugin URI: https://yourwebsite.com/related-posts-elementor
 * Description: A powerful Elementor widget that displays related posts in a beautiful, responsive carousel layout. Features include smart container-based responsive design, customizable navigation, overlay controls, and advanced post query options.
 * Version: 1.1.0
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author: Adalberto H. Vega
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: related-posts-elementor
 * Domain Path: /languages
 *
 * Related Posts Elementor Widget is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Related Posts Elementor Widget is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Related Posts Elementor Widget. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
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
